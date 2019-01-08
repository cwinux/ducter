<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdAppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '设备池所属产品';
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/ducter/index.php?r=dcmd-app/delete-all" method="post">
<div class="dcmd-app-index">

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
            ['label'=>'产品名称',  'attribute' => 'app_name',  'value' => 'dcmdApp.app_name','content' => function($model, $key, $index, $column) { return Html::a($model->getAppName($model['app_id']), Url::to(['view', 'id'=>$model['app_id']]));}],
            ['label'=>'产品名称',  'attribute' => 'ngroup_name',  'value' => 'dcmdNodeGroup.ngroup_name','content' => function($model, $key, $index, $column) { return Html::a($model->getGroup($model['ngroup_id']), Url::to(['dcmd-node-group/view', 'id'=>$model['ngroup_id']]));}],
            //array('attribute'=>'ngroup_id', 'label'=>'设备池', 'enableSorting'=>false,'content' => function($model, $key, $index, $column) { return $model->getGroup($model['ngroup_id']);}),
        ],
    ]); ?>
</div>
<!--<div>
    <button type="button" onclick="method2()">导出集群所有主机</button>
    <button type="button" onclick="method3()">导出集群VM</button>
</div>-->
</form>
