<?php

namespace app\models;

use app\behaviours\BoxEventBehaviour;
use app\enums\BoxStatusEnum;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * @property int $id
 * @property float|null $weight
 * @property float|null $length
 * @property float|null $width
 * @property float|null $height
 * @property string $reference
 * @property int $status
 * @property string $created_at
 *
 * @property Product[] $products
 *
 * Модель коробки
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
            BoxEventBehaviour::class,
        ];
    }

    /**
     * @param float|null $weight
     * @param float|null $height
     * @param float|null $length
     * @param float|null $width
     * @param string $reference
     * @return self
     *
     * Создание коробки
     */
    public static function create(
        ?float $weight,
        ?float $height,
        ?float $length,
        ?float $width,
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

    /**
     * @param float|null $weight
     * @param float|null $height
     * @param float|null $length
     * @param float|null $width
     * @param string $reference
     * @param int $status
     */
    public function edit(
        ?float $weight,
        ?float $height,
        ?float $length,
        ?float $width,
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

    /**
     * @return ActiveQuery
     *
     * Отношение с таблицей boxes_products
     */
    public function getBoxesProducts(): ActiveQuery
    {
        return $this->hasMany(BoxProduct::class, ['box_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     *
     * Отношение с таблицей products
     */
    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])
            ->via('boxesProducts');
    }

    /**
     * @return int
     *
     * Сумма товаров shipped
     */
    public function getShippedTotalQuantity(): int
    {
        return $this->getProducts()
            ->sum('shipped_quantity') ?? 0;
    }

    /**
     * @return int
     *
     * Сумма товаров received
     */
    public function getReceivedTotalQuantity(): int
    {
        return $this->getProducts()
            ->sum('received_quantity') ?? 0;
    }

    /**
     * @return int
     *
     * Общая сумма товаров
     */
    public function getTotalProductQuantity(): int
    {
        return $this->getShippedTotalQuantity() + $this->getReceivedTotalQuantity();
    }

    /**
     * @return string
     *
     * Получить статус
     */
    public function getStatusLabel(): string
    {
        return BoxStatusEnum::from($this->status)
            ->label();
    }

    /**
     * @param int $status
     *
     * Изменить статус
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }


    /**
     * @return bool
     *
     * Совпадает ли кол-во товаров shipped и received
     */
    public function isQuantityMatch(): bool
    {
        foreach ($this->products as $product) {
            if ($product->shipped_quantity !== $product->received_quantity) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     *
     * Наименование таблицы
     */
    public static function tableName(): string
    {
        return '{{%boxes}}';
    }
}
