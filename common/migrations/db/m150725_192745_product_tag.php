<?php

declare(strict_types=1);

use common\models\Category;
use yii\db\Migration;

class m150725_192745_product_tag extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('product_tag', [
            'product_id' => $this->integer(),
            'tag_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'PRIMARY KEY(product_id, tag_id)',
        ]);

        // creates index for column `post_id`
        $this->createIndex(
            'idx-product_tag-product_id',
            'product_tag',
            'product_id'
        );

        // add foreign key for table `post`
        $this->addForeignKey(
            'fk-product_tag-product_id',
            'product_tag',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        // creates index for column `tag_id`
        $this->createIndex(
            'idx-product_tag-tag_id',
            'product_tag',
            'tag_id'
        );

        // add foreign key for table `tag`
        $this->addForeignKey(
            'fk-product_tag-tag_id',
            'product_tag',
            'tag_id',
            'tag',
            'id',
            'CASCADE'
        );
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        // drops foreign key for table `post`
        $this->dropForeignKey(
            'fk-product_tag-product_id',
            'post_tag'
        );

        // drops index for column `post_id`
        $this->dropIndex(
            'idx-product_tag-product_id',
            'product_tag'
        );

        // drops foreign key for table `tag`
        $this->dropForeignKey(
            'fk-product_tag-tag_id',
            'product_tag'
        );

        // drops index for column `tag_id`
        $this->dropIndex(
            'idx-product_tag-tag_id',
            'product_tag'
        );

        $this->dropTable('product_tag');
    }
}
