<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = "故障信息";
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

<div class="dcmd-vm-view" id="dcmd-vm">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //array('attribute'=>'ngroup_id', 'label'=>'集群信息', 'value'=>$model->getNodeGname($model['ngroup_id'])),
            'app_name:text:集群信息',
            'host_ip:text:宿主机IP',
            'host_fault:text:宿主机故障信息',
            'vm_uuid:text:虚拟机SN',
            'vm_ip:text:虚拟机IP',
            'vm_fault:text:虚拟机故障信息',
            'start_time:text:故障起始时间',
            'is_confirm:text:是否确认',
            'confirm_time:text:确认时间',
            'erase_time:text:恢复时间',
            'confirm_user:text:确认人',
            'erase_user:text:处理人',
            'comment:text:说明',
        ],
    ]) ?>
    <p>
        <?= Html::a('更改', ['update', 'id' => $model->fault_id], ['class' => 'btn btn-primary',  (Yii::$app->user->getIdentity()->admin == 1) ? "": "style"=>"display:none"]) ?>
    </p>
</div>
