<?php

namespace app\filters;

use app\enums\BoxStatusEnum;
use app\models\Box;
use Yii;
use yii\db\ActiveQuery;

/**
 * Фильтр коробок.
 */
class BoxFilter extends BaseFilter
{
    protected string $modelClass = Box::class;

    public $created_at_from = null;
    public $created_at_to = null;
    public $search = null;
    public $status = null;

    /**
     * @return array
     *
     * Правила валидации
     */
    public function rules(): array
    {
        return [
            [
               ['created_at_from', 'created_at_to'], 'date', 'format' => 'd.m.Y',
            ],
            [
                'status', 'in', 'range' => BoxStatusEnum::values(),
            ],
            [
                'search', 'string',
            ],
        ];
    }

    /**
     * @param ActiveQuery $query
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     *
     * Запрос к базе
     */
    public function prepareQuery(ActiveQuery $query): ActiveQuery
    {
        if ($this->search) {
            $query
                ->alias('o')
                ->joinWith(['products p'])
                ->andFilterWhere(['or',
                    ['like', 'p.title', $this->search],
                    ['like', 'p.sku', $this->search],
                    ['like', 'o.id', $this->search],
                    ['like', 'o.reference', $this->search],
                ]);
        }

        if ($this->created_at_from && $this->created_at_to) {
            $from = Yii::$app->formatter->asDate($this->created_at_from, 'php:Y-m-d');
            $to = Yii::$app->formatter->asDate($this->created_at_to, 'php:Y-m-d');

            $query->andFilterWhere(['between', 'created_at', $from, $to]);
        }

        $query->andFilterWhere(['status' => $this->status]);

        return $query;
    }

    /**
     * @return array
     *
     * Список полей для сортировки
     */
    public function prepareSort(): array
    {
        return [
            'defaultOrder' => [
                'created_at' => SORT_DESC,
            ],
            'attributes' => [
                'id',
                'created_at',
                'weight',
                'status',
            ],
        ];
    }
}
