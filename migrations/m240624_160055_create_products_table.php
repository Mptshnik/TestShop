<?php

use app\models\Product;
use yii\db\Migration;

class m240624_160055_create_products_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable(Product::tableName(), [
            'id' => $this->primaryKey(),
            'title' => $this->string()
                ->notNull(),
            'sku' => $this->string()
                ->notNull()
                ->unique(),
            'shipped_quantity' => $this->integer()
                ->notNull(),
            'received_quantity' => $this->integer(),
            'price' => $this->float(2),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable(Product::tableName());
    }
}
