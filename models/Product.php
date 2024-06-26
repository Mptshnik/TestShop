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
 */
class Product extends BaseModel
{
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

    public function getBoxesProducts(): ActiveQuery
    {
        return $this->hasMany(BoxProduct::class, ['product_id' => 'id']);
    }

    public function getBoxes(): ActiveQuery
    {
        return $this->hasMany(Box::class, ['id' => 'box_id'])
            ->via('boxesProducts');

    }

    public static function tableName(): string
    {
        return '{{%products}}';
    }
}