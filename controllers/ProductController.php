<?php

namespace app\controllers;

use app\forms\ProductForm;
use app\models\Box;
use app\services\ProductService;
use Yii;
use yii\base\Module;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Response;

/**
 * Контроллер товаров.
 */
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

    /**
     * @param int $id
     * @return string|Response
     * @throws Exception
     *
     * Редактирование товара
     */
    public function actionUpdate(int $id): string|Response
    {
        $product = $this->service->find($id);

        $productForm = new ProductForm($product);

        if ($productForm->load(Yii::$app->request->post()) && $productForm->validate()) {
            $product = $this->service->edit($productForm);

            // @var Box $box
            $box = $product->getBoxes()->one();

            return $this->redirect(['box/view', 'id' => $box->id]);
        }

        return $this->render('create', [
            'productForm' => $productForm,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws Exception
     *
     * Удаление товара
     */
    public function actionDelete(int $id): string|Response
    {
        $product = $this->service->find($id);
        // @var Box $box
        $box = $product->getBoxes()->one();

        $this->service->delete($product);

        return $this->redirect([
            'box/view',
            'id' => $box->id,
        ]);
    }
}
