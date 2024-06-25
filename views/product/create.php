<?php

use app\forms\ProductForm;
use app\helpers\ButtonConfig;
use app\helpers\Html;
use app\helpers\InputConfig;
use yii\widgets\ActiveForm;

/**
 * @var ProductForm $productForm
 */
$form = ActiveForm::begin([
    'errorCssClass' => 'text-danger',
]); ?>

<?= $form->field($productForm, 'title') ?>
<?= $form->field($productForm, 'sku') ?>
<?= $form->field($productForm, 'shipped_quantity')
    ->input('text', ['id' => 'shipped_quantity'])
?>
<?= $form->field($productForm, 'received_quantity')->render(function () use ($productForm) {
    $inputConfig = new InputConfig(
        'text',
        'ProductForm[received_quantity]',
        $productForm->received_quantity,
        'Received Quantity',
        [
                'class' => 'form-control', 'id' => 'received_quantity',
            ],
    );

    $buttonConfig = new ButtonConfig('Match', [
        'class' => 'btn btn-outline-secondary',
        'onclick' => "copyFrom('shipped_quantity', 'received_quantity')",
    ]);

    return Html::inputGroup($inputConfig, $buttonConfig);
})
?>
<?= $form->field($productForm, 'price') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>