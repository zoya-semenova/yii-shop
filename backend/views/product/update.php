<?php

/**
 * @var $this yii\web\View
 * @var $model common\models\Category
 * @var $roles yii\rbac\Role[]
 */

$this->title = Yii::t('backend', 'Update {modelClass}: ', ['modelClass' => 'Category']) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Update')];
?>
<div class="category-update">

    <?php echo $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
