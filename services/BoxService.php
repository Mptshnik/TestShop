<?php

namespace app\services;

use app\forms\BoxForm;
use app\forms\BoxListForm;
use app\models\Box;
use app\models\Product;
use app\repository\BoxRepository;
use Shuchkin\SimpleXLSXGen;

/**
 * Сервис коробок.
 */
class BoxService
{
    public function __construct(private BoxRepository $repository)
    {
    }

    /**
     * @param int $id
     * @return Box
     * @throws \yii\db\Exception
     *
     * Получить коробку по id
     */
    public function find(int $id): Box
    {
        return $this->repository->find($id);
    }


    /**
     * @param BoxForm $form
     * @return Box
     * @throws \yii\db\Exception
     *
     * Создание коробки
     */
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

    /**
     * @param BoxForm $form
     * @return Box
     * @throws \yii\db\Exception
     *
     * Редактирование коробки
     */
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

    /**
     * @param array $data
     * @return array
     * @throws \yii\db\Exception
     *
     * Изменение статуса выбранных коробок
     */
    public function changeStatus(array $data): array
    {
        $status = $data['status'];
        $listId = $data['listId'];

        /** @var Box[] $boxes */
        $boxes = Box::find()
            ->where(['in', 'id', $listId])
            ->all();

        $data = [
            BoxListForm::FORM_NAME => [
                'status' => $status,
            ],
        ];

        foreach ($boxes as $box) {
            $form = new BoxListForm($box);

            if ($form->load($data) && $form->validate()) {
                $box->setStatus($status);

                $this->repository->save($box);
            } else {
                return [
                    'success' => false,
                    'message' => implode(',', $form->getFirstErrors()),
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'saved',
        ];
    }

    /**
     * @param array $data
     * @return array
     * @throws \yii\db\Exception
     *
     * Обновление поля коробки AJAX запросом
     */
    public function updateAjax(array $data): array
    {
        $box = $this->find($data['editableKey']);

        $editableIndex = $data['editableIndex'];
        $editableAttribute = $data['editableAttribute'];

        $value = $data['Box'][$editableIndex];

        $boxListForm = new BoxListForm($box);

        $data = [
            BoxListForm::FORM_NAME => $value,
        ];

        if ($boxListForm->load($data) && $boxListForm->validate()) {
            $box->setAttribute($editableAttribute, $boxListForm->$editableAttribute);

            $this->repository->save($box);

            return [
                'output' => $boxListForm->$editableAttribute,
                'message' => 'Success',
            ];
        }

        return [
            'output' => $box->$editableAttribute,
            'message' => implode(',', $boxListForm->getFirstErrors()),
        ];
    }

    /**
     * @param Box $box
     * @throws \yii\db\Exception
     *
     * Удаление коробки
     */
    public function delete(Box $box): void
    {
        $this->repository->delete($box);
    }

    /**
     * @param array $data
     * @param bool $useKeysAsHeader
     * @return SimpleXLSXGen|null
     *
     * Экспорт в Excel
     */
    public function export(array $data, bool $useKeysAsHeader = false): ?SimpleXLSXGen
    {
        if (empty($data)) {
            return null;
        }

        $header = $useKeysAsHeader ? array_keys($data[0]) : [];

        $exportData = array_merge([$header], $data);

        return SimpleXLSXGen::fromArray($exportData);
    }

    /**
     * @param Box $box
     * @param Product $product
     */
    public function addProduct(Box $box, Product $product): void
    {
        $box->link('products', $product);
    }
}
