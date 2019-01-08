<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "异常VM";
$this->params['breadcrumbs'][] = ['label' => '私有云管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/index.php?r=dcmd-private/reuse" method="post">
<div class="dcmd-node-index">

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
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'app_name', 'label' => '集群名称', 'content'=> function($model, $key, $index, $column) { return Html::a($model->getClusterByIP($model['app_name']), Url::to(['dcmd-app/view', 'id'=>$model->getNodeIDByIP($model['app_name'])]));}),
            //array('attribute'=>'vm_sn', 'label'=>'虚拟机SN', 'content'=> function($model, $key, $index, $column) { return Html::a($model['vm_sn'], Url::to(['dcmd-private/view', 'id'=>$model['id']]));}),
            array('attribute'=>'host_ip', 'label' => '宿主机IP', 'content'=> function($model, $key, $index, $column) { return Html::a($model['host_ip'], Url::to(['dcmd-node/view', 'id'=>$model->getNodeID($model['host_ip'])]));}),
            array('attribute'=>'state', 'label'=>'VM状态', 'enableSorting'=>false),
  //          array('attribute'=>'module', 'label'=>'运行状态', 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn','template'=>'{view}', 'urlCreator'=>function($action, $model, $key, $index) {if ("view" == $action) return Url::to(['dcmd-private/invalidview','id'=>$model['id']]);else return Url::to(['dcmd-private/update','id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
              
          //  ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
       // ],
    ]); ?>
</div>
</form>
