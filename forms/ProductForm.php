<?php

namespace app\forms;

use app\models\Product;
use yii\base\Model;

/**
 * Форма создания/редактирования товара.
 */
class ProductForm extends Model
{
    public $title;
    public $sku;
    public $shipped_quantity;
    public $received_quantity;
    public $price = 0;

    public function __construct(public ?Product $product = null, array $config = [])
    {
        if ($product) {
            $this->title = $product->title;
            $this->sku = $product->sku;
            $this->shipped_quantity = $product->shipped_quantity;
            $this->received_quantity = $product->received_quantity;
            $this->price = $product->price;
        }

        parent::__construct($config);
    }

    /**
     * @return array
     *
     * Список правил
     */
    public function rules(): array
    {
        return [
            [
                ['title', 'sku', 'shipped_quantity'], 'required',
            ],
            [
                'sku', 'unique',
                'filter' => $this->product ? ['!=', 'sku', $this->product->sku] : null,
                'targetClass' => Product::class,
                'targetAttribute' => 'sku',
            ],
            [
                'price', 'number', 'numberPattern' => '/^\d+(\.\d{1,2})?$/', 'min' => 0,
            ],
            [
                ['shipped_quantity', 'shipped_quantity', 'received_quantity', 'price'], 'integer', 'min' => 0,
            ],
            [
                ['received_quantity', 'price'], 'default', 'value' => 0,
            ],
            [
                ['sku', 'title'], 'string', 'max' => 255,
            ],
        ];
    }
}
