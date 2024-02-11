<?php

declare(strict_types=1);

use common\models\Category;
use yii\db\Migration;

class m150725_192742_category extends Migration
{

    public function safeUp(): void
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(32),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('{{%category}}');
    }
}
