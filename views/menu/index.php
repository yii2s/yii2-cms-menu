<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel infoweb\menu\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Menus');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php // Flash message ?>
    <?php if (Yii::$app->getSession()->hasFlash('menu')): ?>
    <div class="alert alert-success">
        <p><?= Yii::$app->getSession()->getFlash('menu') ?></p>
    </div>
    <?php endif; ?>

    <?php if (Yii::$app->user->can('createMenu')): ?>
    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
            'modelClass' => 'Menu',
        ]), ['create'], ['class' => 'btn btn-success']); ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id'=>'grid-pjax']); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}{pager}",
        'columns' => [
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'name',
                'format' => 'raw',
                'value'=>function ($model) {
                    return Html::a(Html::encode($model->name), Url::toRoute(['update', 'id' => $model->id]), [
                        'title' => Yii::t('app', 'Update'),
                        'data-pjax' => '0',
                        'data-toggle' => 'tooltip',
                        'class' => 'edit-model',
                    ]);
                },
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => (Yii::$app->user->can('Superadmin')) ? '{update} {delete} {menu-item}' : '{update} {menu-item}',
                'buttons' => [
                    'menu-item' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', $url, [
                            'title' => Yii::t('app', 'Menu Items'),
                            'data-pjax' => '0',
                            'data-toggle' => 'tooltip',
                            //'data-placement' => 'left',
                        ]);
                    },
                ],
                'updateOptions'=>['title'=> 'Update', 'data-toggle'=>'tooltip'],
                'deleteOptions'=>['title'=> 'Delete', 'data-toggle'=>'tooltip'],
                'urlCreator' => function($action, $model, $key, $index) {
    
                    if ($action == 'menu-item')
                    {
                        $params = is_array($key) ? $key : ['menu-id' => (int) $key];
                        $params[0] = $action . '/index';
                    } else {
                        $params = is_array($key) ? $key : ['id' => (int) $key];
                        $params[0] = 'menu' . '/' . $action;
                    }
    
                    return Url::toRoute($params);
                },
                'width' => '100px',
            ]
        ],
        'responsive' => true,
        'floatHeader' => true,
        'floatHeaderOptions' => ['scrollingTop' => 88],
        'hover' => true
    ]); ?>
    <?php Pjax::end(); ?>

</div>