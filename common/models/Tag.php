<?php

declare(strict_types=1);

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Tag model
 *
 * @property integer $id
 * @property string $title
 * @property integer $created_at
 * @property integer $updated_at
 */
class Tag extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%tags}}';
    }

    public function attributeLabels(): array
    {
        return [
            'title' => Yii::t('common', 'Название'),
            'created_at' => Yii::t('common', 'Created at'),
            'updated_at' => Yii::t('common', 'Updated at'),
        ];
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules(): array
    {
        return [
            [['title'], 'unique'],
            [['title'], 'filter', 'filter' => '\yii\helpers\Html::encode']
        ];
    }

    public function getId(): int
    {
        return $this->getPrimaryKey();
    }

}
