<?php

namespace common\models;

use app\modules\admin\models\Category;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;


/**
 * Category model
 *
 * @property integer $id
 * @property string $title
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \common\models\UserProfile $userProfile
 */
class Product extends ActiveRecord
{
    /**
     * Вспомогательный атрибут для загрузки изображения товара
     */
    public $upload;

    /**
     * Вспомогательный атрибут для удаления изображения товара
     */
    public $remove;

    public $tags = [];

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'title' => Yii::t('common', 'Title'),
            'price' => Yii::t('common', 'Price'),
            'image' => Yii::t('common', 'Image'),
            'remove' => Yii::t('common', 'Delete Image'),
            'category_id' => Yii::t('common', 'Category'),
            'created_at' => Yii::t('common', 'Created at'),
            'updated_at' => Yii::t('common', 'Updated at'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['category_id', 'title'], 'required'],
            [['category_id'], 'integer'],
            [['price'], 'number', 'min' => 1],
            [['title'], 'filter', 'filter' => '\yii\helpers\Html::encode'],
            ['image', 'image', 'extensions' => 'png, jpg'],
            ['remove', 'safe'],
            ['tags', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Возвращает данные о родительской категории
     */
    public function getCategory() {
        return $this->hasOne(\common\models\Category::class, ['id' => 'category_id']);
    }

    /**
     * Возвращает наименование родительской категории
     */
    public function getCategoryTitle() {
        $parent = $this->category;
        return $parent ? $parent->title : '';
    }

    public function getTagProduct()
    {
        /*
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])
            ->viaTable('product_tag', ['product_id' => 'id']);
        */
        return $this->hasMany(
            ProductTag::class, ['product_id' => 'id']
        );
    }
/*
    public function getProductTag()
    {
        return $this->hasOne(ProductTag::className(), ['product_id' => 'id']);
    }
*/
    // you need a getter for select2 dropdown
    public function getdropTags()
    {
        $data = Tag::find()->asArray()->all();
        return ArrayHelper::map($data, 'id', 'title');
    }
/*
// You will need a getter for the current set o Authors in this Book
    public function getTagIds()
    {
        $this->tagIds = \yii\helpers\ArrayHelper::getColumn(
            $this->getTags()->asArray()->all(),
            'tag_id'
        );
        return $this->tagIds;
    }
*/
    public function setTags(array $tagsId): void
    {
        $this->tags = $tagsId;
    }

    /**
     * Return tag ids
     */
    public function getTags(): array
    {
        return ArrayHelper::getColumn(
            $this->getTagProduct()->all(), 'tag_id'
        );
    }
/*
    // You need to save the relations in ProductTag table (adicional code for updates)
    public function afterSave($insert, $changedAttributes)
    {
        $actualTags = [];
        $tagExists = 0; //for updates

        if (($actualTags = ProductTag::find()
                ->andWhere("product_id = $this->id")
                ->asArray()
                ->all()) !== null) {
            $actualTags = ArrayHelper::getColumn($actualTags, 'tag_id');
            $tagExists = 1; // if there is authors relations, we will work it latter
        }
//        print_r($actualTags);
//print_r($this->tags);//exit;
        if (!empty($this->tags)) { //save the relations
            foreach ($this->tags as $id) {print_r($id);
                $actualTags = array_diff($actualTags, [$id]); //remove remaining authors from array
                print_r($actualTags);exit;
                $r = new ProductTag();
                $r->product_id = $this->id;
                $r->tag_id = $id;
                $r->save();
            }
        }

        if ($tagExists == 1) { //delete authors tha does not belong anymore to this book
            foreach ($actualTags as $remove) {
                $r = ProductTag::findOne(['tag_id' => $remove, 'product_id' => $this->id]);
                $r->delete();
            }
        }

        parent::afterSave($insert, $changedAttributes); //don't forget this
    }
*/
    public function afterSave($insert, $changedAttributes)
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
    /**
     * Загружает файл изображения товара
     */
    public function uploadImage() {
        if ($this->upload) { // только если был выбран файл для загрузки
            $name = md5(uniqid(rand(), true)) . '.' . $this->upload->extension;
            // сохраняем исходное изображение в директории source
            $source = Yii::getAlias('@webroot/images/products/source/' . $name);
            if ($this->upload->saveAs($source)) {
                // выполняем resize, чтобы получить еще три размера
                $large = Yii::getAlias('@webroot/images/products/large/' . $name);
                Image::thumbnail($source, 1000, 1000)->save($large, ['quality' => 100]);
                $medium = Yii::getAlias('@webroot/images/products/medium/' . $name);
                Image::thumbnail($source, 500, 500)->save($medium, ['quality' => 95]);
                $small = Yii::getAlias('@webroot/images/products/small/' . $name);
                Image::thumbnail($source, 250, 250)->save($small, ['quality' => 90]);
                return $name;
            }
        }
        return false;
    }

    /**
     * Удаляет старое изображение при загрузке нового
     */
    public static function removeImage($name) {
        if (!empty($name)) {
            $source = Yii::getAlias('@webroot/images/products/source/' . $name);
            if (is_file($source)) {
                unlink($source);
            }
            $large = Yii::getAlias('@webroot/images/products/large/' . $name);
            if (is_file($large)) {
                unlink($large);
            }
            $medium = Yii::getAlias('@webroot/images/products/medium/' . $name);
            if (is_file($medium)) {
                unlink($medium);
            }
            $small = Yii::getAlias('@webroot/images/products/small/' . $name);
            if (is_file($small)) {
                unlink($small);
            }
        }
    }

    /**
     * Удаляет изображение при удалении товара
     */
    public function afterDelete() {
        parent::afterDelete();
        self::removeImage($this->image);
    }
}
