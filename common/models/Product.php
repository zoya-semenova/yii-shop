<?php

declare(strict_types=1);

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;
use common\components\CategoryValidator;


/**
 * Product model
 *
 * @property integer $id
 * @property string $title
 * @property number $price
 * @property string $image
 * @property integer $created_at
 * @property integer $updated_at
 */
class Product extends ActiveRecord
{
    public ?UploadedFile $upload = null;

    public bool $remove = false;

    public string|array $tags = "";

    private const IMG_PATH = '@webroot/images/products/';

    public static function tableName(): string
    {
        return '{{%products}}';
    }

    public function attributeLabels(): array
    {
        return [
            'title' => Yii::t('common', 'Title'),
            'alias' => Yii::t('common', 'Alias'),
            'price' => Yii::t('common', 'Price'),
            'image' => Yii::t('common', 'Image'),
            'remove' => Yii::t('common', 'Delete Image'),
            'category_id' => Yii::t('common', 'Category'),
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
            [['category_id', 'title'], 'required'],
            [['category_id'], 'integer'],
            ['category_id', CategoryValidator::class],
            [['price'], 'number', 'min' => 1],
            [['title'], 'filter', 'filter' => '\yii\helpers\Html::encode'],
            ['image', 'image', 'extensions' => 'png, jpg'],
            ['remove', 'boolean', 'trueValue' => true, 'falseValue' => false, 'strict' => true],
            ['tags', 'each', 'rule' => ['integer']],
        ];
    }

    public function getImgPath(): string
    {
        return self::IMG_PATH;
    }

    public function getId(): int
    {
        return $this->getPrimaryKey();
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getCategoryTitle(): string
    {
        return $this->category->title ? $this->category->title : '';
    }

    public function getTagProduct(): ActiveQuery
    {
        return $this->hasMany(
            ProductTag::class, ['product_id' => 'id']
        );
    }

    public function getDropTags(): array
    {
        $data = Tag::find()->asArray()->all();

        return ArrayHelper::map($data, 'id', 'title');
    }

    public function setTags(array $tagsId): void
    {
        $this->tags = $tagsId;
    }

    public function getTags(): array
    {
        return ArrayHelper::getColumn(
            $this->getTagProduct()->all(), 'tag_id'
        );
    }

    public function afterSave($insert, $changedAttributes): void
    {
        ProductTag::deleteAll(['product_id' => $this->id]);

        if (is_array($this->tags) && !empty($this->tags)) {
            $values = [];
            foreach ($this->tags as $id) {
                $values[] = [$this->id, $id];
            }
            self::getDb()->createCommand()
                ->batchInsert(ProductTag::tableName(), ['product_id', 'tag_id'], $values)->execute();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function uploadImage(): bool|string
    {
        if ($this->upload) {
            $name = md5(uniqid(rand(), true)) . '.' . $this->upload->extension;
            $source = Yii::getAlias(self::IMG_PATH.'source/' . $name);
            if ($this->upload->saveAs($source)) {
                $large = Yii::getAlias(self::IMG_PATH.'large/' . $name);
                Image::thumbnail($source, 1000, 1000)->save($large, ['quality' => 100]);
                $medium = Yii::getAlias(self::IMG_PATH.'medium/' . $name);
                Image::thumbnail($source, 500, 500)->save($medium, ['quality' => 95]);
                $small = Yii::getAlias(self::IMG_PATH.'small/' . $name);
                Image::thumbnail($source, 250, 250)->save($small, ['quality' => 90]);
                return $name;
            }
        }
        return false;
    }

    public static function removeImage($name): void
    {
        if (!empty($name)) {
            $source = Yii::getAlias(self::IMG_PATH.'source/' . $name);
            if (is_file($source)) {
                unlink($source);
            }
            $large = Yii::getAlias(self::IMG_PATH.'large/' . $name);
            if (is_file($large)) {
                unlink($large);
            }
            $medium = Yii::getAlias(self::IMG_PATH.'medium/' . $name);
            if (is_file($medium)) {
                unlink($medium);
            }
            $small = Yii::getAlias(self::IMG_PATH.'small/' . $name);
            if (is_file($small)) {
                unlink($small);
            }
        }
    }

    public function afterDelete(): void
    {
        parent::afterDelete();
        self::removeImage($this->image);
    }
}
