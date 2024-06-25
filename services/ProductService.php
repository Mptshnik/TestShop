<?php

namespace app\services;

use app\forms\ProductForm;
use app\models\Product;
use app\repository\ProductRepository;

/**
 * Сервис товаров.
 */
class ProductService
{
    public function __construct(private ProductRepository $repository)
    {
    }

    /**
     * @param int $id
     * @return Product
     * @throws \yii\db\Exception
     *
     * Получить товар по id
     */
    public function find(int $id): Product
    {
        return $this->repository->find($id);
    }

    /**
     * @param ProductForm $form
     * @return Product
     * @throws \yii\db\Exception
     *
     * Создание товара
     */
    public function create(ProductForm $form): Product
    {
        $product = Product::create(
            $form->title,
            $form->sku,
            $form->shipped_quantity,
            $form->received_quantity,
            $form->price,
        );

        $this->repository->save($product);

        return $product;
    }

    /**
     * @param ProductForm $form
     * @return Product
     * @throws \yii\db\Exception
     *
     * Редактирование товара
     */
    public function edit(ProductForm $form): Product
    {
        $product = $form->product;

        $product->edit(
            $form->title,
            $form->sku,
            $form->shipped_quantity,
            $form->received_quantity,
            $form->price,
        );

        $this->repository->save($product);

        return $product;
    }

    /**
     * @param Product $product
     * @throws \yii\db\Exception
     *
     * Удаление товара
     */
    public function delete(Product $product): void
    {
        $this->repository->delete($product);
    }
}
