<?php

use common\models\User;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model backend\models\CategoryForm
 * @var $form yii\bootstrap\ActiveForm
 * @var $permissions yii\rbac\Permission[]
 */
?>

<div class="category-form">
    <?php $form = ActiveForm::begin() ?>
    <?php echo $form->field($model, 'title') ?>
    <?php echo $form->field($model, 'alias') ?>
    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>
