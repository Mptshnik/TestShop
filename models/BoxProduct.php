<?php

namespace app\models;

/**
 * @property int $id
 * @property int $product_id
 * @property int $box_id
 * @property int $product_count
 *
 * Модель смежной таблицы коробок и товаров
 */
class BoxProduct extends BaseModel
{
    /**
     * @return string
     *
     * Наименование таблицы
     */
    public static function tableName(): string
    {
        return '{{%boxes_products}}';
    }
}
