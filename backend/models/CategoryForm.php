<?php

namespace backend\models;

use common\models\Category;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Create category form
 */
class CategoryForm extends Model
{
    public $title;

    private $model;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['title', 'filter', 'filter' => 'trim'],
            ['title', 'required'],
            ['title', 'unique', 'targetClass' => Category::class, 'filter' => function ($query) {
                if (!$this->getModel()->isNewRecord) {
                    /** @var $query ActiveQuery */

                    $query->andWhere(['not', ['id' => $this->getModel()->id]]);
                }
            }],
            ['title', 'string', 'min' => 2, 'max' => 255],
        ];
    }

    /**
     * @return Category
     */
    public function getModel(): Category
    {
        if (!$this->model) {
            $this->model = new Category();
        }
        return $this->model;
    }

    /**
     * @param Category $model
     * @return mixed
     */
    public function setModel($model)
    {
        $this->title = $model->title;
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
            if (!$model->save()) {
                throw new Exception('Model not saved');
            }

            return !$model->hasErrors();
        }

        return null;
    }
}
