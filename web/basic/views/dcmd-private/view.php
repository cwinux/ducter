<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = "VM:".$model->vm_ip;
$this->params['breadcrumbs'][] = ['label' => 'VM分配', 'url' => ['index']];
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

<SCRIPT LANGUAGE="JavaScript">
<!--
  var d = function(o)  {
    return document.getElementById(o);
  }
 
  function showDiv(parm){
    d('dcmd-node').style.display = 'none';    
    d(parm).style.display = '';    
   
    if(parm == 'dcmd-vm-info') getVmInfo();
    if(parm == 'dcmd-app') getApp();
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }

//-->
</SCRIPT>
<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-node-l" onclick="showDiv('dcmd-node');this.className='codeDemomouseOnMenu'">VM详细信息</li>
</ul>

<div class="dcmd-vm-view" id="dcmd-vm">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //array('attribute'=>'ngroup_id', 'label'=>'集群信息', 'value'=>$model->getNodeGname($model['ngroup_id'])),
            'app_name:text:集群信息',
            'host_ip:text:宿主机IP',
            'vm_uuid:text:虚拟机SN',
            'vm_ip:text:虚拟机内网IP',
            'vm_pub_ip:text:虚拟机公网IP',
            array('attribute'=>'state', 'label'=>'VM使用状态', 'enableSorting'=>false, 'value'=> $model->getState($model['state'])),
            'borrow:text:外借设备',
            'cpu:text:CPU',
            'memory:text:内存',
            'disk:text:磁盘',
            'flavor_name:text:虚拟机规格',
            'os:text:OS',
            'business:text:业务名称',
            'module:text:模块名称',
            'contacts:text:使用人',
            'order_id:text:工单id',
            'bill_id:text:bill id',
        ],
    ]) ?>
    <p>
        <?= Html::a('更改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary',  (Yii::$app->user->getIdentity()->admin == 1) ? "": "style"=>"display:none"]) ?>
    </p>
</div>
