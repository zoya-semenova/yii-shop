<?php

/**
 * @var $this yii\web\View
 * @var $model \common\models\Product
 * @var $roles yii\rbac\Role[]
 */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Продукта',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <?php echo $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
