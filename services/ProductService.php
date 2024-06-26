<?php

namespace app\services;

use app\forms\BoxForm;
use app\forms\ProductForm;
use app\models\Box;
use app\models\Product;
use app\repository\BoxRepository;
use app\repository\ProductRepository;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class ProductService
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function find(int $id): Product
    {
        return $this->repository->find($id);
    }

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

    public function delete(Product $product): void
    {
        $this->repository->delete($product);
    }
}