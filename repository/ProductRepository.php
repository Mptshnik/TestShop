<?php

namespace app\repository;

use app\models\Box;
use app\models\Product;
use yii\db\Exception;

class ProductRepository
{
    public function find(int $id): Product
    {
        if (! $product = Product::findOne($id)) {
            throw new Exception('Product not found');
        }

        return $product;
    }

    public function save(Product $product): void
    {
        if (! $product->save()) {
            throw new Exception('Saving error.');
        }
    }

    public function delete(Product $product): void
    {
        if (! $product->delete()) {
            throw new Exception('Deletion error.');
        }
    }
}