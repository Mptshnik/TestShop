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

class ProductController extends Controller
{
    public function __construct(
        string $id,
        Module $module,
        private ProductService $service,
        array $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionDelete(int $id): string|Response
    {
        $product = $this->service->find($id);
        /* @var Box $box */
        $box = $product->getBoxes()->one();

        $this->service->delete($product);

        return $this->redirect([
            'box/view',
            'id' => $box->id,
        ]);
    }
}