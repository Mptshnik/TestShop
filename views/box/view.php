<?php

use app\forms\ProductForm;
use app\models\Box;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View $this
 * @var Box $box
 * @var ProductForm $productForm
 * @var ActiveDataProvider $productDataProvider
 */
?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                Коробка № <?= $box->id ?>
            </div>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $box,
                    'attributes' => [
                        [
                            'label' => 'Length',
                            'value' => Yii::$app->formatter->asDecimal($box->length ?? 0, 2) . 'cm',
                        ],
                        [
                            'label' => 'Height',
                            'value' => Yii::$app->formatter->asDecimal($box->height ?? 0, 2) . 'cm',
                        ],
                        [
                            'label' => 'Width',
                            'value' => Yii::$app->formatter->asDecimal($box->width ?? 0, 2) . 'cm',
                        ],
                        [
                            'label' => 'Weight',
                            'value' => Yii::$app->formatter->asWeight($box->weight ?? 0, 2),
                        ],
                        'reference',
                        [
                            'label' => 'Status',
                            'value' => $box->getStatusLabel(),
                        ],
                        [
                            'label' => 'Product Count',
                            'value' => $box->getProducts()->count(),
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-header">
                Добавление товара
            </div>
            <div class="card-body">
                <?= $this->render('/product/create', [
                    'productForm' => $productForm,
                ]) ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        Товары
        <?= GridView::widget([
            'dataProvider' => $productDataProvider,
            'emptyText' => 'Нет данных',
            'columns' => [
                'id',
                'title',
                'sku',
                'shipped_quantity',
                'received_quantity',
                [
                    'class' => ActionColumn::class,
                    'template' => '{update} {delete}',
                    'controller' => 'product',
                ],
            ],
        ]); ?>
    </div>
</div>