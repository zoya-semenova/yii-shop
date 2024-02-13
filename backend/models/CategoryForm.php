<?php

declare(strict_types=1);

namespace backend\models;

use common\models\Category;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class CategoryForm extends Model
{
    public $alias;

    public $title;

    private $model;

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

    public function getModel(): Category
    {
        if (!$this->model) {
            $this->model = new Category();
        }
        return $this->model;
    }

    public function setModel(Category $model): Category
    {
        $this->title = $model->title;
        $this->alias = $model->alias;
        $this->model = $model;

        return $this->model;
    }

    public function attributeLabels(): array
    {
        return [
            'title' => Yii::t('common', 'Title'),
        ];
    }

    /**
     * @throws Exception
     */
    public function save(): ?bool
    {
        if ($this->validate()) {
            $model = $this->getModel();
            $model->alias = $this->alias;
            $model->title = $this->title;
            if (!$model->save()) {
                throw new Exception('Model not saved');
            }

            return !$model->hasErrors();
        }

        return null;
    }
}
