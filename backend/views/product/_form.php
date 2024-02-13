<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Category;
use yii\helpers\ArrayHelper;

/**
 * @var $this yii\web\View
 * @var $model \common\models\Product
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
    $categories = Category::find()->asArray()->all();
    $items = ArrayHelper::map($categories,'id','title');
    echo $form->field($model, 'category_id')->dropDownList($items, $param);
    ?>
    <?= $form->field($model, 'price')->textInput(['maxlength' => true]); ?>
    <fieldset>
        <legend>Загрузить изображение</legend>
        <?= $form->field($model, 'image')->fileInput(); ?>
        <?php
        if (!empty($model->image)) {
            $img = $model->getImgPath() . $model->image;
            if (is_file($img)) {
                $url = $model->getImgPath() . $model->image;
                echo 'Уже загружено ', Html::a('изображение', $url, ['target' => '_blank']);
                echo $form->field($model,'remove')->checkbox();
            }
        }
        ?>
    </fieldset>
    <fieldset>
        <legend>Теги</legend>
        <?= $form->field($model, 'tags')->widget(\kartik\select2\Select2::className(), [
            'model' => $model,

            'data' => $model->getDropTags(),
            'options' => [
                'multiple' => true,
            ],
            'pluginOptions' => [
                'tags' => true,
            ],

            /*
            'name' => 'tags[]',
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'tags' => $model->getDropTags(),
                'allowClear' => true,
                'multiple' => true
            ],
            */
        ]); ?>
    </fieldset>
    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end() ?>

</div>
