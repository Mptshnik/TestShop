<?php

use app\enums\BoxStatusEnum;
use app\filters\BoxFilter;
use app\forms\BoxListForm;
use app\helpers\Html;
use app\models\Box;
use kartik\date\DatePicker;
use kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var ActiveDataProvider $dataProvider
 * @var BoxFilter $filterModel
 * @var BoxListForm $boxListForm
 */
?>

<div class="d-flex flex-row">
    <h1>Коробки</h1>
    <div class="ms-auto">
        <a href="/box/create" class="btn btn-primary">Добавить</a>
        <button onclick="exportBoxes()" class="btn btn-success">Report</button>
    </div>
</div>
<div class="d-flex flex-row">
    <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['box/index'])]); ?>

    <?= $form->field($filterModel, 'search') ?>

    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary mb-3']) ?>

    <?php ActiveForm::end(); ?>
</div>
<div class="d-flex flex-row">
    <div class="input-group mb-3">
        <button onclick="onStatusChange()" class="btn btn-outline-secondary" type="button">Change status</button>
        <?=Html::dropDownList('box-statuses', null, BoxStatusEnum::options(), ['class' => 'form-control', 'id' => 'box-statuses']) ?>
    </div>
</div>

<?= GridView::widget([
    'id' => 'gridBoxes',
    'dataProvider' => $dataProvider,
    'filterModel' => $filterModel,

    'emptyText' => 'Нет данных',
    'rowOptions' => function (Box $box) {
        if (!$box->isQuantityMatch()) {
            return ['class' => 'product-quantity'];
        }

        return [];
    },
    'columns' => [
        [
            'class' => CheckboxColumn::class,
            'multiple' => true,
            'name' => 'boxes',
            'checkboxOptions' => function (Box $box) {
                return [
                    'id' => sprintf('box-check-%s', $box->id),
                    'value' => json_encode([
                        'id' => $box->id,
                        'date' => $box->created_at,
                        'weight' => $box->weight,
                        'status' => $box->getStatusLabel(),
                    ]),
                ];
            },
        ],
        'id',
        [
            'attribute' => 'created_at',
            'label' => 'Date',
            'format' => [
                'datetime',
                'php:d.m.Y H:i',
            ],
            'filter' => DatePicker::widget([
                'model' => $filterModel,
                'attribute' => 'created_at_from',
                'attribute2' => 'created_at_to',
                'type' => DatePicker::TYPE_RANGE,
                'separator' => 'to',
                'pluginOptions' => [
                    'format' => 'dd.mm.yyyy',
                    'autoClose' => true,
                ],
            ]),
        ],
        [
            'class' => EditableColumn::class,
            'refreshGrid' => false,
            'attribute' => 'weight',
            'label' => 'Weight',
            'editableOptions' => function (Box $box) use ($boxListForm) {
                return [
                    'name' => 'weight',
                    'asPopover' => true,
                    'model' => $boxListForm,
                    'attribute' => 'weight',
                    'options' => [
                        'class'=>'form-control',
                    ],
                    'formOptions' => [
                         'action' => Url::to(['box/update-ajax']),
                    ],
                ];
            },
        ],
        [
            'class' => EditableColumn::class,
            'attribute' => 'status',
            'label' => 'Status',
            'editableOptions' => function (Box $box) {
                return [
                    'name'=>'status',
                    'value' => $box->status,
                    'asPopover' => true,
                    'header' => 'Status',
                    'inputType' => Editable::INPUT_DROPDOWN_LIST,
                    'data' => BoxStatusEnum::options(),
                    'displayValueConfig'=> BoxStatusEnum::options(),
                    'formOptions' => [
                        'action' => Url::to(['box/update-ajax', 'id' => $box->id]),
                    ],
                ];
            },
            'content' => function (Box $box) {
                return Html::dropDownList('status[]', $box->status, BoxStatusEnum::options(), ['class' => 'form-control']);
            },
            'filter' => Html::activeDropDownList(
                $filterModel,
                'status',
                BoxStatusEnum::options(),
                [
                    'class' => 'form-control',
                    'prompt' => 'All',
                ],
            ),
        ],
        [
            'class' => ActionColumn::class,
        ],
    ],
]); ?>