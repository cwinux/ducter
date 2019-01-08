<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务池列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/index.php?r=dcmd-service-pool/delete-all" method="post">
<div class="dcmd-service-pool-index">
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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'bucket_name', 'label'=>'Bucket名称'),
//            array('attribute'=>'dcmdCbsBussiness.uid', 'label'=>'用户名称', 'content'=>function($model, $key, $index, $column) { return $model->getUser($model->uid);}),
            ['label'=>'用户名称',  'attribute' => 'bussiness',  'value' => 'dcmdCbsBussiness.bussiness'],
            array('attribute'=>'dcmdCbsBussiness.app_id', 'label'=>'产品名称', 'enableSorting'=>false,'content'=>function($model, $key, $index, $column) { return Html::a($model->getApp($model['app_id']), Url::to(['dcmd-cbase-app/view', 'id'=>$model['app_id']]));}),
            array('attribute'=>'type', 'label'=>'Buckets类型', 'enableSorting'=>false),
            array('attribute'=>'dcmdCbsBussiness.contracts', 'label'=>'联系方式'),
            ['label'=>'Quto',  'attribute' => 'Quto',  'value' => 'dcmdCbsBucketsStats.Quto'],
            ['label'=>'Ops_sec',  'attribute' => 'Ops_sec',  'value' => 'dcmdCbsBucketsStats.Ops_sec'],
            ['label'=>'RAM Used',  'attribute' => 'RAM_Used',  'value' => 'dcmdCbsBucketsStats.RAM_Used'],
          //  array('attribute'=>'dcmdCbsBucketsStats.Hit_ratio', 'label'=>'Hit ratio', 'enableSorting'=>false),
//            array('attribute'=>'dcmdCbsBucketsStats.RAM_Used', 'label'=>'RAM Used'),
            ['label'=>'Item Counts',  'attribute' => 'Item_Count',  'value' => 'dcmdCbsBucketsStats.Item_Count',],
//            array('attribute'=>'dcmdCbsBucketsStats.Item_Count', 'label'=>'Item Count'),
        ],
    ]); ?>
</div>
</form>
