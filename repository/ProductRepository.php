<?php

namespace app\repository;

use app\models\Product;
use yii\db\Exception;

/**
 * Репозиторий товаров.
 */
class ProductRepository
{
    /**
     * @param int $id
     * @return Product
     * @throws Exception
     *
     * Получить товар по id
     */
    public function find(int $id): Product
    {
        if (!$product = Product::findOne($id)) {
            throw new Exception('Product not found');
        }

        return $product;
    }

    /**
     * @param Product $product
     * @throws Exception
     *
     * Сохранение товара в базе
     */
    public function save(Product $product): void
    {
        if (!$product->save()) {
            throw new Exception('Saving error.');
        }
    }

    /**
     * @param Product $product
     * @throws Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     *
     * Удаление товара из базы
     */
    public function delete(Product $product): void
    {
        if (!$product->delete()) {
            throw new Exception('Deletion error.');
        }
    }
}
