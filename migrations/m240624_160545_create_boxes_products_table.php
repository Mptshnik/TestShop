<?php

use app\models\BoxProduct;
use yii\db\Migration;

class m240624_160545_create_boxes_products_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable(BoxProduct::tableName(), [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()
                ->notNull(),
            'box_id' => $this->integer()
                ->notNull(),
        ]);

        $this->createIndex(
            'idx-products-product_id',
            'boxes_products',
            'product_id'
        );

        $this->addForeignKey(
            'fk-products-product_id',
            'boxes_products',
            'product_id',
            'products',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-boxes-box_id',
            'boxes_products',
            'box_id'
        );

        $this->addForeignKey(
            'fk-boxes-box_id',
            'boxes_products',
            'box_id',
            'boxes',
            'id',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        $this->dropTable(BoxProduct::tableName());
    }
}
