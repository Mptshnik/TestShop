<?php

namespace app\repository;

use app\models\Box;
use yii\db\Exception;

class BoxRepository
{
    public function find(int $id): Box
    {
        if (! $box = Box::findOne($id)) {
            throw new Exception('Box not found');
        }

        return $box;
    }

    public function save(Box $box): void
    {
        if (! $box->save()) {
            throw new Exception('Saving error.');
        }
    }
}