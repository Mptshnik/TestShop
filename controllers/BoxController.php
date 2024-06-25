<?php

namespace app\controllers;

use app\filters\BoxFilter;
use app\forms\BoxForm;
use app\forms\BoxListForm;
use app\forms\ProductForm;
use app\services\BoxService;
use app\services\ProductService;
use Yii;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

/**
 * Контроллер коробок.
 */
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


    /**
     * @return string
     * @throws \Exception
     *
     * Получить список
     */
    public function actionIndex(): string
    {
        $boxListForm = new BoxListForm();
        $filterModel = new BoxFilter();
        $dataProvider = $filterModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filterModel' => $filterModel,
            'boxListForm' => $boxListForm,
        ]);
    }

    /**
     * @return string|Response
     *
     * Создание коробки
     */
    public function actionCreate(): string|Response
    {
        $boxForm = new BoxForm();

        if ($boxForm->load(Yii::$app->request->post()) && $boxForm->validate()) {
            $box = $this->service->create($boxForm);

            return $this->redirect(['view', 'id' => $box->id]);
        }

        return $this->render('create', [
            'boxForm' => $boxForm,
        ]);

    }

    /**
     * @return array
     *
     * Редактирование атрибута коробки
     */
    public function actionUpdateAjax(): array
    {
        $data = Yii::$app->request->post();

        if (!isset($data['hasEditable'])) {
            return [];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $this->service->updateAjax($data);
    }

    /**
     * @param int $id
     * @return string|Response
     *
     * Редактирование коробки
     */
    public function actionUpdate(int $id): string|Response
    {
        $box = $this->service->find($id);

        $boxForm = new BoxForm($box);

        if ($boxForm->load(Yii::$app->request->post()) && $boxForm->validate()) {
            $box = $this->service->edit($boxForm);

            return $this->redirect(['view', 'id' => $box->id]);
        }

        return $this->render('create', [
            'boxForm' => $boxForm,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     *
     * Удаление коробки
     */
    public function actionDelete(int $id): Response
    {
        $box = $this->service->find($id);

        $this->service->delete($box);

        return $this->redirect('index');
    }


    /**
     * @return array
     * @throws BadRequestHttpException
     *
     * Изменение статуса выбранных коробок
     */
    public function actionChangeStatus(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            return $this->service->changeStatus(Yii::$app->request->post());
        }

        throw new BadRequestHttpException('Invalid request');

    }

    /**
     * @throws BadRequestHttpException
     *
     * Экспорт выбранных коробок в excel
     */
    public function actionExport(): void
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $file = $this->service->export(Yii::$app->request->post(), true);

            if ($file) {
                $file->downloadAs('boxes.xlsx');
            }
        } else {
            throw new BadRequestHttpException('Invalid request');
        }
    }

    /**
     * @param int $id
     * @return string|Response
     *
     * Получить карточку коробки. Добавление товара. Вывод списка товаров в коробке.
     */
    public function actionView(int $id): string|Response
    {
        $box = $this->service->find($id);

        $productDataProvider = new ActiveDataProvider([
            'query' => $box->getProducts(),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
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
