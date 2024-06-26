<?php

use app\enums\BoxStatusEnum;
use app\models\Box;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/**
 * @var ActiveDataProvider $dataProvider
 */
?>

<div class="d-flex flex-row">
    <h1>Коробки</h1>
    <div class="ms-auto">
        <a href="/box/create" class="btn btn-primary">Добавить</a>
    </div>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'emptyText' => 'Нет данных',
    'columns' => [
        'id',
        'date' => [
           'attribute' => 'created_at',
           'label' => 'Date',
        ],
        'weight' => [
            'attribute' => 'weight',
            'label' => 'Weight',
        ],
        'status' => [
            'attribute' => 'status',
            'label' => 'Status',
            'value' => function (Box $box) {
                return BoxStatusEnum::from($box->status)->label();
            },
        ],
        [
            'class' => ActionColumn::class,
        ],
    ]
]); ?>