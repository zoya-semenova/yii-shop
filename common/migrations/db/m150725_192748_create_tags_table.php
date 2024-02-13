<?php

declare(strict_types=1);

use yii\db\Migration;

class m150725_192748_create_tags_table extends Migration
{
    private const TABLE = '{{%tags}}';

    public function safeUp(): void
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable(self::TABLE);
    }
}
