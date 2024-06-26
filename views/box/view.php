<?php

use app\controllers\ProductController;
use app\enums\BoxStatusEnum;
use app\forms\ProductForm;
use app\models\Box;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/**
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
                        //todo форматирование
                        [
                            'label' => 'Length',
                            'value' => Yii::$app->formatter->asDecimal($box->length, 2) . 'cm',
                        ],
                        [
                            'label' => 'Height',
                            'value' => Yii::$app->formatter->asDecimal($box->height, 2) . 'cm',
                        ],
                        [
                            'label' => 'Width',
                            'value' => Yii::$app->formatter->asDecimal($box->width, 2) . 'cm',
                        ],
                        [
                            'label' => 'Weight',
                            'value' => Yii::$app->formatter->asWeight($box->weight, 2),
                        ],
                        'reference',
                        [
                            'label' => 'Status',
                            'value' => BoxStatusEnum::from($box->status)->label()
                        ],
                        [
                            'label' => 'Product Count',
                            'value' => $box->getProducts()->count()
                        ],
                    ]
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
                <?php $form = ActiveForm::begin([
                    'errorCssClass' => 'text-danger',
                ]); ?>

                <?= $form->field($productForm, 'title') ?>
                <?= $form->field($productForm, 'sku') ?>
                <?= $form->field($productForm, 'shipped_quantity')
                    ->input('text', ['id' => 'shipped_quantity'])
                ?>
                <?= $form->field($productForm, 'received_quantity')->render(function () use ($productForm) {
                    $label = Html::label('Received Quantity', '', [
                        'class' => 'form-label',
                    ]);

                    $copyQuantityButton = Html::button('Копировать', [
                        'class' => 'btn btn-outline-secondary',
                        'onclick' => "copyFrom('shipped_quantity', 'received_quantity')"
                    ]);

                    $input = Html::input('text', 'ProductForm[received_quantity]', $productForm->received_quantity, [
                        'class' => 'form-control',
                        'id' => 'received_quantity',
                    ]);

                    $inputGroup = Html::tag('div', $input . $copyQuantityButton, [
                        'class' => 'input-group',
                    ]);

                    return Html::tag('div', $label . $inputGroup, [
                        'class' => 'form-group',
                    ]);
                })
                ?>
                <?= $form->field($productForm, 'price') ?>

                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
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
            ]
        ]); ?>
    </div>
</div>