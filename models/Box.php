<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * @property int $id
 * @property float $weight
 * @property float $length
 * @property float $width
 * @property float $height
 * @property string $reference
 * @property int $status
 *
 * @property Product[] $products
 */
class Box extends BaseModel
{
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public static function create(
        float $weight,
        float $height,
        float $length,
        float $width,
        string $reference
    ): self {
        $box = new static();

        $box->weight = $weight;
        $box->length = $length;
        $box->width = $width;
        $box->height = $height;
        $box->reference = $reference;

        return $box;
    }

    public function edit(
        float $weight,
        float $height,
        float $length,
        float $width,
        string $reference,
        int $status,
    ): void {
        $this->weight = $weight;
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
        $this->reference = $reference;
        $this->status = $status;
    }

    public function getBoxesProducts(): ActiveQuery
    {
        return $this->hasMany(BoxProduct::class, ['box_id' => 'id']);
    }

    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])
            ->via('boxesProducts');
    }


    public static function tableName(): string
    {
        return '{{%boxes}}';
    }
}