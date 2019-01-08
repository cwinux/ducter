<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = "异常VM信息";
$this->params['breadcrumbs'][] = ['label' => '异常VM', 'url' => ['invalid']];
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
            'state:text:虚拟机状态',
            'cpu:text:CPU',
            'memory:text:内存',
            'disk:text:磁盘',
            'flavor_name:text:虚拟机规格',
            'image_name:text:镜像名称',
            'utime:text:修改时间',
        ],
    ]) ?>
</div>
