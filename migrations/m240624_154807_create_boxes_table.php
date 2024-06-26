<?php


use app\enums\BoxStatusEnum;
use app\models\Box;
use yii\db\Migration;

class m240624_154807_create_boxes_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable(Box::tableName(), [
            'id' => $this->primaryKey(),
            'weight' => $this->float(2),
            'length' => $this->float(2),
            'width' => $this->float(2),
            'height' => $this->float(2),
            'reference' => $this->string()
                ->notNull(),
            'status' => $this->tinyInteger()
                ->defaultValue(BoxStatusEnum::EXPECTED->value),
            'created_at' => $this->timestamp()
                ->notNull()
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable(Box::tableName());
    }
}
