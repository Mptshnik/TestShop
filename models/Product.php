<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property string $title
 * @property string $sku
 * @property int $shipped_quantity
 * @property int $received_quantity
 * @property float $price
 *
 * @property Box[] $boxes
 *
 * Модель товара
 */
class Product extends BaseModel
{
    /**
     * @param string $title
     * @param string $sku
     * @param int $shipped_quantity
     * @param int $received_quantity
     * @param float $price
     * @return self
     *
     * Создание товара
     */
    public static function create(
        string $title,
        string $sku,
        int $shipped_quantity,
        int $received_quantity,
        float $price,
    ): self {
        $box = new static();

        $box->title = $title;
        $box->sku = $sku;
        $box->shipped_quantity = $shipped_quantity;
        $box->received_quantity = $received_quantity;
        $box->price = $price;

        return $box;
    }

    /**
     * @param string $title
     * @param string $sku
     * @param int $shipped_quantity
     * @param int $received_quantity
     * @param float $price
     */
    public function edit(
        string $title,
        string $sku,
        int $shipped_quantity,
        int $received_quantity,
        float $price,
    ): void {
        $this->title = $title;
        $this->sku = $sku;
        $this->shipped_quantity = $shipped_quantity;
        $this->received_quantity = $received_quantity;
        $this->price = $price;
    }

    /**
     * @return ActiveQuery
     *
     * Отношение с таблицей boxes_products
     */
    public function getBoxesProducts(): ActiveQuery
    {
        return $this->hasMany(BoxProduct::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     *
     * Отношение с таблицей boxes
     */
    public function getBoxes(): ActiveQuery
    {
        return $this->hasMany(Box::class, ['id' => 'box_id'])
            ->via('boxesProducts');

    }

    /**
     * @return string
     *
     * Наименование таблицы
     */
    public static function tableName(): string
    {
        return '{{%products}}';
    }
}
