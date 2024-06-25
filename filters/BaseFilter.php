<?php

namespace app\filters;

use Exception;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Базовый класс фильтра.
 */
class BaseFilter extends Model
{
    protected string $modelClass;

    public function init(): void
    {
        if (!$this->modelClass) {
            throw new InvalidConfigException(get_class($this) . '::$modelClass must be set.');
        }
        parent::init();
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     * @throws Exception
     *
     * Фильтрация по параметрам
     */
    public function search(array $params): ActiveDataProvider
    {
        $this->load($params);

        if (!$this->validate()) {
            throw new Exception('Not valid params');
        }

        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        $query = $this->prepareQuery($modelClass::find());

        $dataProvider = $this->prepareDataProvider(new ActiveDataProvider([
            'query' => $query,
        ]));

        if (isset($params['show']) && $params['show'] == 'all') {
            $dataProvider->pagination = false;
        }

        return $dataProvider;
    }

    /**
     * @param ActiveQuery $query
     * @return ActiveQuery
     *
     * Запрос к базе
     */
    public function prepareQuery(ActiveQuery $query): ActiveQuery
    {
        return $query;
    }

    /**
     * @param ActiveDataProvider $dataProvider
     * @return ActiveDataProvider
     *
     * Получить $dataProvider с учетом сортировок
     */
    public function prepareDataProvider(ActiveDataProvider $dataProvider): ActiveDataProvider
    {
        $sort = $this->prepareSort();

        if ($sort) {
            $dataProvider->sort = new Sort($sort);
        }

        return $dataProvider;
    }

    /**
     * @return array|null
     *
     * Получить список полей для сортировки
     */
    public function prepareSort(): array|null
    {
        return null;
    }
}
