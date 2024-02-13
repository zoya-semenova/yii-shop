<?php

/**
 * @var $this yii\web\View
 * @var $model \common\models\Product
 * @var $roles yii\rbac\Role[]
 */

$this->title = Yii::t('backend', 'Update {modelClass}: ', ['modelClass' => 'Product']) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Update')];
?>
<div class="product-update">

    <?php echo $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
