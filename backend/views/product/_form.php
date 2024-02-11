<?php

use common\models\User;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model backend\models\ProductForm
 * @var $form yii\bootstrap\ActiveForm
 * @var $roles yii\rbac\Role[]
 * @var $permissions yii\rbac\Permission[]
 */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin() ?>
    <?php echo $form->field($model, 'title') ?>
    <?php
    $category = Yii::$app->request->get('category') ?: 0;
    $param = ['options' => [$category => ['selected' => true]]];
    $categories = \common\models\Category::find()->asArray()->all();
    $items = \yii\helpers\ArrayHelper::map($categories,'id','title');
    echo $form->field($model, 'category_id')->dropDownList($items, $param);
    ?>
    <?= $form->field($model, 'price')->textInput(['maxlength' => true]); ?>
    <fieldset>
        <legend>Загрузить изображение</legend>
        <?= $form->field($model, 'image')->fileInput(); ?>
        <?php
        if (!empty($model->image)) {
            $img = Yii::getAlias('@webroot') . '/images/products/source/' .  $model->image;
            if (is_file($img)) {
                $url = Yii::getAlias('@web') . '/images/products/source/' .  $model->image;
                echo 'Уже загружено ', Html::a('изображение', $url, ['target' => '_blank']);
                echo $form->field($model,'remove')->checkbox();
            }
        }
        ?>
    </fieldset>
    <fieldset>
        <legend>Tags</legend>
        <?= $form->field($model, 'tags')->widget(\kartik\select2\Select2::className(), [
            'model' => $model,
            'data' => $model->getdropTags(),
            'options' => [
                'multiple' => true,
            ],
            'pluginOptions' => [
                'tags' => true,
            ],
        ]); ?>
    </fieldset>
    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end() ?>

</div>
