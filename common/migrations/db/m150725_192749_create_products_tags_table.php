<?php

declare(strict_types=1);

use yii\db\Migration;

class m150725_192749_create_products_tags_table extends Migration
{
    private const TABLE = '{{%products_tags}}';

    public function safeUp(): void
    {
        $this->createTable(self::TABLE, [
            'product_id' => $this->integer(),
            'tag_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'PRIMARY KEY(product_id, tag_id)',
        ]);

        // creates index for column `post_id`
        $this->createIndex(
            'idx-product_tag-product_id',
            self::TABLE,
            'product_id'
        );

        // add foreign key for table `post`
        $this->addForeignKey(
            'fk-product_tag-product_id',
            self::TABLE,
            'product_id',
            'products',
            'id',
            'CASCADE'
        );

        // creates index for column `tag_id`
        $this->createIndex(
            'idx-product_tag-tag_id',
            self::TABLE,
            'tag_id'
        );

        // add foreign key for table `tag`
        $this->addForeignKey(
            'fk-product_tag-tag_id',
            self::TABLE,
            'tag_id',
            'tags',
            'id',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        // drops foreign key for table `post`
        $this->dropForeignKey(
            'fk-product_tag-product_id',
            self::TABLE
        );

        // drops index for column `post_id`
        $this->dropIndex(
            'idx-product_tag-product_id',
            self::TABLE
        );

        // drops foreign key for table `tag`
        $this->dropForeignKey(
            'fk-product_tag-tag_id',
            self::TABLE
        );

        // drops index for column `tag_id`
        $this->dropIndex(
            'idx-product_tag-tag_id',
            self::TABLE
        );

        $this->dropTable(self::TABLE);
    }
}
