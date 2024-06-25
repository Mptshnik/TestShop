<?php

use app\models\Event;
use yii\db\Migration;

class m240701_114840_create_events_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable(Event::tableName(), [
            'id' => $this->primaryKey(),
            'weight' => $this->float(2)
                ->defaultValue(0),
            'product_count' => $this->integer()
                ->defaultValue(0),
            'is_quantity_match' => $this->boolean()
            ->defaultValue(false)->defaultValue(false),
            'box_id' => $this->integer()
                ->notNull(),
        ]);

        $this->createIndex(
            'idx-events-box_id',
            Event::tableName(),
            'box_id'
        );

        $this->addForeignKey(
            'fk-events-box_id',
            Event::tableName(),
            'box_id',
            'boxes',
            'id',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        $this->dropTable(Event::tableName());
    }
}
