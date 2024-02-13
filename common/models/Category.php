<?php

declare(strict_types=1);

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Category model
 *
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property integer $created_at
 * @property integer $updated_at
 *
 */
class Category extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%categories}}';
    }

    public function attributeLabels(): array
    {
        return [
            'title' => Yii::t('common', 'Title'),
            'alias' => Yii::t('common', 'Alias'),
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
            [['title', 'alias'], 'unique'],
            [['title', 'alias'], 'filter', 'filter' => '\yii\helpers\Html::encode']
        ];
    }

    public function getId(): int
    {
        return $this->getPrimaryKey();
    }

}
