<?php

declare(strict_types=1);

use yii\db\Migration;

class m150725_192747_create_products_table extends Migration
{
    private const TABLE = '{{%products}}';

    public function safeUp(): void
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'alias' => $this->string(),
            'title' => $this->string()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'price' => $this->decimal()->notNull(),
            'image' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-product-category_id',
            self::TABLE,
            'category_id',
            'categories',
            'id',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        $this->dropForeignKey(
            'fk-product-category_id',
            self::TABLE
        );

        $this->dropTable(self::TABLE);
    }
}
