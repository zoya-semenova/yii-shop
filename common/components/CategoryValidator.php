<?php

namespace common\components;

use yii\validators\Validator;
use common\models\Category;

class CategoryValidator extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = 'Invalid category input.';
    }

    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (!Category::find()->where(['id' => $value])->exists()) {
            $model->addError($attribute, $this->message);
        }
    }
}
