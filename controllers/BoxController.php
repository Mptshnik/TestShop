<?php

namespace app\controllers;

use app\forms\BoxForm;
use app\forms\ProductForm;
use app\models\Box;
use app\services\BoxService;
use app\services\ProductService;
use Yii;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\Response;

class BoxController extends Controller
{
    public function __construct(
        string $id,
        Module $module,
        private BoxService $service,
        private ProductService $productService,
        array $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $query = Box::find();

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $provider,
        ]);
    }

    public function actionCreate(): string|Response
    {
        $boxForm = new BoxForm();

        if ($boxForm->load(Yii::$app->request->post()) && $boxForm->validate()) {
            $box = $this->service->create($boxForm);

            return $this->redirect(['view', 'id' => $box->id]);
        } else {
            return $this->render('create', [
                'boxForm' => $boxForm,
            ]);
        }
    }

    public function actionView(int $id): string|Response
    {
        $box = $this->service->find($id);

        //todo вынести в сервис
        $productDataProvider = new ActiveDataProvider([
            'query' => $box->getProducts(),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $productForm = new ProductForm();

        if ($productForm->load(Yii::$app->request->post()) && $productForm->validate()) {
            $id = $this->productService->create($productForm)->id;

            $product = $this->productService->find($id);

            $this->service->addProduct($box, $product);

            return $this->redirect(['view', 'id' => $box->id]);
        }

        return $this->render('view', [
            'box' => $box,
            'productForm' => $productForm,
            'productDataProvider' => $productDataProvider,
        ]);
    }
}