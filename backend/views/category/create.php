<?php

/**
 * @var $this yii\web\View
 * @var $model backend\models\CategoryForm
 */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Категории',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <?php echo $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
