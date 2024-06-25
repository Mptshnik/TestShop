<?php

namespace app\repository;

use app\models\Box;
use yii\db\Exception;

/**
 * Репозиторий коробки.
 */
class BoxRepository
{
    /**
     * @param int $id
     * @return Box
     * @throws Exception
     *
     * Получить коробку по id
     */
    public function find(int $id): Box
    {
        if (!$box = Box::findOne($id)) {
            throw new Exception('Box not found');
        }

        return $box;
    }

    /**
     * @param Box $box
     * @throws Exception
     *
     * Сохранение коробки в базе
     */
    public function save(Box $box): void
    {
        if (!$box->save()) {
            throw new Exception('Saving error.');
        }
    }

    /**
     * @param Box $box
     * @throws Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     *
     * Удаление коробки
     */
    public function delete(Box $box): void
    {
        if (!$box->delete()) {
            throw new Exception('Deletion error.');
        }
    }
}
