<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = "VM:".$model->vm_ip;
$this->params['breadcrumbs'][] = ['label' => 'VM操作', 'url' => ['operate']];
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
<script>
  function restart(uuid){
    url = "/index.php?r=dcmd-private/oprvm";
    $.get(url,{id:uuid,action:"restart"},function(data){
      data = $.parseJSON(data);
      alert(data.data);
      refresh(uuid);
    });
  }

  function stop(uuid){
    url = "/index.php?r=dcmd-private/oprvm";
    $.get(url,{id:uuid,action:"stop"},function(data){
      data = $.parseJSON(data);
      alert(data.data);
      refresh(uuid);
    });
  }

  function start(uuid){
    url = "/index.php?r=dcmd-private/oprvm";
    $.get(url,{id:uuid,action:"start"},function(data){
      data = $.parseJSON(data);
      alert(data.data);
      refresh(uuid);
    });
  }

  function refresh(uuid){
    var st = window.setInterval(function(){
           getStatus(uuid);
       },1000);
    window.setTimeout(function(){
           clear(st);
       },10000);
  }

  function clear(st){
    clearInterval(st);
  }

  function getStatus(){
//  tempid = x.parentNode.parentNode.getAttribute("id");
    address = "#" + "vmList";
    uuid = $(address).find("tr").eq(2).find("td").eq(0).text();
    url = "/index.php?r=dcmd-private/oprvm"; 
    $.get(url,{id:uuid,action:"status"},function(data){
      data = $.parseJSON(data);
      $(address).find("tr").eq(9).find("td").eq(0).html(data.data);
    });
  }

window.onload=getStatus;
</script>
<script>
   function change(){
     address = "#" + "vmList";
     uuid = $(address).find("tr").eq(2).find("td").eq(0).text();
     alert(uuid);
  }
</script>

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
        'options' => ['class' => 'table table-striped table-bordered', 'id'=>'vmList'],
        'attributes' => [
          //  array('attribute'=>'ngroup_id', 'label'=>'集群信息', 'value'=>"dd"),
            'app_name:text:集群信息',
            'host_ip:text:宿主机IP',
            'vm_uuid:text:虚拟机SN',
            'vm_ip:text:虚拟机IP',
            'cpu:text:CPU',
            'memory:text:内存',
            'disk:text:磁盘',
            'flavor_name:text:虚拟机规格',
            'image_name:text:镜像名称',
            'state:text:虚拟机状态'
        ],
    ]) ?>
    <p>
        <?= Html::Button('开机', ['class' =>'btn btn-success', 'onClick'=>"start('$model->vm_uuid')", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
        <?= Html::Button('关机', ['class' =>'btn btn-success', 'onClick'=>"stop('$model->vm_uuid')", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
        <?= Html::Button('重启', ['class' =>'btn btn-success', 'onClick'=>"restart('$model->vm_uuid')", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
    </p>
</div>
