<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = "报警信息";
$this->params['breadcrumbs'][] = ['label' => '报警日志', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

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

<div class="dcmd-alarm-history-view" id="dcmd-alarm-history">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //array('attribute'=>'ngroup_id', 'label'=>'集群信息', 'value'=>$model->getNodeGname($model['ngroup_id'])),
            'fault_id:text:故障ID',
            'app_name:text:集群名称',
            'host_ip:text:宿主机IP',
            'vm_ip:text:虚机IP',
            'sms:text:短信信息',
            'email:text:邮件信息',
            'content:text:内容',
            'send_time:text:发送时间',
        ],
    ]) ?>
</div>
