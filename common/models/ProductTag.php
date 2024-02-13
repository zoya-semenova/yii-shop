<?php

declare(strict_types=1);

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * ProductTag model
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $tag_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \common\models\UserProfile $userProfile
 */
class ProductTag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%products_tags}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['tag_id', 'product_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'tag_id' => Yii::t('backend', 'Tag ID'),
            'product_id' => Yii::t('backend', 'Post ID'),
        ];
    }

    public function getPost(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getTag(): ActiveQuery
    {
        return $this->hasOne(Tag::class, ['id' => 'tag_id']);
    }
}
