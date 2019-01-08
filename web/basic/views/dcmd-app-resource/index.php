<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '资源和产品列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    if( Yii::$app->getSession()->hasFlash('success') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success', //这里是提示框的class
            ],
            'body' => Yii::$app->getSession()->getFlash('success'), //消息体
        ]);
    }
    if( Yii::$app->getSession()->hasFlash('error') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
            ],
            'body' => "<font color=red>".Yii::$app->getSession()->getFlash('error')."</font>",
        ]);
    }
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'app_id','label'=>'产品名称','content' => function($model, $key, $index, $column) { return Html::a($model->getAppName($model['app_id']), Url::to(['dcmd-app/view', 'id'=>$model['app_id']]));}),
            array('attribute'=>'res_id','label'=>'唯一行号','content' => function($model, $key, $index, $column) { return Html::a('<font class="sourceID">'.$model['res_id'], Url::to(['dcmd-resource/view', 'res_id'=>$model['res_id'], 'res_type'=>$model['res_type']]));}),
            array('attribute'=>'res_name', 'label'=>'资源名称', 'enableSorting'=>false,),
            array('attribute'=>'res_type', 'label'=>'资源类型', 'enableSorting'=>false,),
            array('attribute'=>'is_own', 'label'=>'是否所有者', 'filter'=>false, 'enableSorting'=>false,'content' => function($model, $key, $index, $column) { return $model['is_own'] ? '是':'否';}),
//            ['class' => 'yii\grid\ActionColumn',  "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>

</div>
