<?php

namespace app\services;

use app\forms\BoxForm;
use app\models\Box;
use app\models\Product;
use app\repository\BoxRepository;

class BoxService
{
    public function __construct(private BoxRepository $repository)
    {
    }

    public function find(int $id): Box
    {
        return $this->repository->find($id);
    }

    public function create(BoxForm $form): Box
    {
        $box = Box::create(
            $form->weight,
            $form->height,
            $form->length,
            $form->width,
            $form->reference,
        );

        $this->repository->save($box);

        return $box;
    }

    public function edit(BoxForm $form): Box
    {
        $box = $form->box;

        $box->edit(
            $form->weight,
            $form->height,
            $form->length,
            $form->width,
            $form->reference,
            $form->status
        );

        $this->repository->save($box);

        return $box;
    }

    public function addProduct(Box $box, Product $product): void
    {
        $box->link('products', $product);
    }
}