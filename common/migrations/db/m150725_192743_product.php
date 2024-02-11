<?php

declare(strict_types=1);

use common\models\Category;
use yii\db\Migration;

class m150725_192743_product extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'price' => $this->string()->notNull(),
            'image' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-product-category_id',
            'product',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-product-category_id',
            'product'
        );

        $this->dropTable('{{%product}}');
    }
}
