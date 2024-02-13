<?php

declare(strict_types=1);

use yii\db\Migration;

class m150725_192746_create_categories_table extends Migration
{
    private const TABLE = '{{%categories}}';

    public function safeUp(): void
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'alias' => $this->string(),
            'title' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable(self::TABLE);
    }
}
