<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '报警日志';
$this->params['breadcrumbs'][] = ['label' => '日志管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/index.php?r=dcmd-alarm-history/delete-all" method="post">
<div class="dcmd-alarm-history-index">

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
            array('attribute'=>'fault_id', 'label' => '故障ID', 'content'=> function($model, $key, $index, $column) { return Html::a($model['fault_id'], Url::to(['dcmd-fault/view', 'id'=>$model['fault_id']]));}),
            array('attribute'=>'app_name', 'label' => '集群名称', 'content'=> function($model, $key, $index, $column) { return Html::a($model['app_name'], Url::to(['dcmd-app/view', 'id'=>$model->getAppIDByName($model['app_name'])]));}),
            array('attribute'=>'host_ip', 'label' => '宿主机IP', 'content'=> function($model, $key, $index, $column) { return Html::a($model['host_ip'], Url::to(['dcmd-node/view', 'id'=>$model->getNodeIDByIP($model['host_ip'])]));}),
            array('attribute'=>'vm_ip', 'label'=>'虚拟机IP', 'content'=> function($model, $key, $index, $column) { return Html::a($model['vm_ip'], Url::to(['dcmd-private/view', 'id'=>$model->getVmIDByIP($model['vm_ip'])]));}),
            array('attribute'=>'sms', 'label'=>'短信信息', 'enableSorting'=>false),
            array('attribute'=>'email', 'label'=>'邮件信息', 'enableSorting'=>false),
            array('attribute'=>'content', 'label'=>'内容', 'enableSorting'=>false),
            array('attribute'=>'send_time', 'label'=>'发送时间', 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn','template'=>'{view}', 'urlCreator'=>function($action, $model, $key, $index) {return Url::to(['dcmd-alarm-history/view','id'=>$model['alarm_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
              
          //  ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
       // ],
    ]); ?>
    <p>
        <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
    </p>
</div>
</form>
