<?php

namespace app\models;

/**
 * @property float $weight
 * @property int $product_count
 * @property int $box_id
 * @property bool $is_quantity_match
 *
 * Модель события
 */
class Event extends BaseModel
{
    /**
     * @param float $weight
     * @param int $product_count
     * @param bool $isQuantityMatch
     * @param int $boxId
     * @return self
     *
     * Создание события
     */
    public static function create(
        float $weight,
        int $product_count,
        bool $isQuantityMatch,
        int $boxId,
    ): self {
        $event = new static();

        $event->weight = $weight;
        $event->product_count = $product_count;
        $event->is_quantity_match = $isQuantityMatch;
        $event->box_id = $boxId;

        return $event;
    }

    /**
     * @return string
     *
     * Наименование таблицы
     */
    public static function tableName(): string
    {
        return '{{%events}}';
    }
}
