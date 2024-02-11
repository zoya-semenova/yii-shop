<?php

use yii\grid\ActionColumn;
use common\grid\EnumColumn;
use common\models\User;
use trntv\yii\datetime\DateTimeWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\JsExpression;

/**
 * @var $this yii\web\View
 * @var $searchModel backend\models\search\CategorySearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('backend', 'Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <p>
        <?php echo Html::a(Yii::t('backend', 'Create {modelClass}', [
            'modelClass' => 'Product',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'grid-view table-responsive'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',

            [
                'attribute' => 'category_id',
                'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Category::find()->asArray()->all(), 'id', 'title'),
                'value' => function($data){
                    return $data->getCategoryTitle();
                }
            ],
            /*
            [
                'attribute' => 'category_id',
                'value' => function ($model, $key, $index, $column) {
                    //  var_dump($model); var_dump($key); exit;
                    return Html::activeDropDownList($model, 'category_id',
                        \yii\helpers\ArrayHelper::map(\common\models\Category::find()->all(), 'id', 'title')

                    );
                },
                'format' => 'raw',
                ],*/
            [
                'attribute' => 'created_at',
                'format' => 'datetime',

                'filter' => DateTimeWidget::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'phpDatetimeFormat' => 'dd.MM.yyyy',
                    'momentDatetimeFormat' => 'DD.MM.YYYY',
                    'clientEvents' => [
                        'dp.change' => new JsExpression('(e) => $(e.target).find("input").trigger("change.yiiGridView")')
                    ],
                ])

            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'login' => function ($url) {
                        return Html::a(
                            '<i class="fa fa-sign-in" aria-hidden="true"></i>',
                            $url,
                            [
                                'title' => Yii::t('backend', 'Login')
                            ]
                        );
                    },
                ],
                'visibleButtons' => [
                    'login' => Yii::$app->user->can('administrator')
                ]

            ],
        ],
    ]); ?>

</div>
