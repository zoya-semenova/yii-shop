<?php

namespace backend\models;

use common\models\Category;
use common\models\Product;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * Create category form
 */
class ProductForm extends Model
{
    public $category_id;

    public $title;

    public $price;

    public $upload;

    public $remove;

    public $image;

    private $model;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['title', 'filter', 'filter' => 'trim'],
            ['title', 'required'],
            ['title', 'string', 'min' => 2, 'max' => 255],
        ];
    }

    /**
     * @return Product
     */
    public function getModel(): Product
    {
        if (!$this->model) {
            $this->model = new Product();
        }
        return $this->model;
    }

    /**
     * @param Product $model
     * @return mixed
     */
    public function setModel($model)
    {
        $this->category_id = $model->category_id;
        $this->title = $model->title;
        $this->price = $model->price;
        // старое изображение, которое надо удалить, если загружено новое
        $old = $model->image;

        // если отмечен checkbox «Удалить изображение»
        if ($model->remove) {
            // удаляем старое изображение
            if (!empty($old)) {
                $model::removeImage($old);
            }
            // сохраняем в БД пустое имя
            $model->image = null;
            // чтобы повторно не удалять
            $old = null;
        } else {
            // оставляем старое изображение
            $model->image = $old;
        }
        // загружаем изображение и выполняем resize исходного изображения
        $model->upload = UploadedFile::getInstance($model, 'image');
        echo "<pre>"; print_r($model);exit;
        if ($new = $model->uploadImage()) { // если изображение было загружено
            // удаляем старое изображение
            if (!empty($old)) {
                $model::removeImage($old);
            }
            // сохраняем в БД новое имя
            $model->image = $new;
        }

        $this->model = $model;

        return $this->model;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'title' => Yii::t('common', 'Title'),
        ];
    }

    /**
     * Signs user up.
     * @return bool|null
     * @throws Exception
     */
    public function save(): ?bool
    {
        if ($this->validate()) {
            $model = $this->getModel();
            $model->title = $this->title;
            $model->price = $this->price;
            $model->image = $this->image;
            $model->category_id = $this->category_id;
            if (!$model->save()) {
                throw new Exception('Model not saved');
            }

            return !$model->hasErrors();
        }

        return null;
    }
}
