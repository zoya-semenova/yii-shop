<?php

declare(strict_types=1);

use common\models\Category;
use yii\db\Migration;

class m150725_192744_tag extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('tag');
    }
}
