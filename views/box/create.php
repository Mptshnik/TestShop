<?php

use app\enums\BoxStatusEnum;
use app\forms\BoxForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var BoxForm $boxForm
 */
?>
<?php $form = ActiveForm::begin([
   'errorCssClass' => 'text-danger',
]); ?>

<?= $form->field($boxForm, 'weight') ?>
<?= $form->field($boxForm, 'height') ?>
<?= $form->field($boxForm, 'length') ?>
<?= $form->field($boxForm, 'width') ?>
<?= $form->field($boxForm, 'reference') ?>
<?= $boxForm->box ? $form->field($boxForm, 'status')
    ->dropDownList(BoxStatusEnum::options(), [
        'disabled' => $boxForm->box->getTotalProductQuantity() === 0,
    ]) : null
?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>