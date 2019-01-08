<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = "设备详细信息:".$model->ip;
$this->params['breadcrumbs'][] = ['label' => '设备列表', 'url' => ['index']];
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
    d('dcmd-run-task').style.display='none';   
    d('dcmd-run-opr').style.display='none';    
    d('dcmd-os-info').style.display='none'; 
    d('dcmd-os-user').style.display='none';
    d('dcmd-unfinish-task').style.display='none';
    d('dcmd-app').style.display='none';
    d('dcmd-node-info').style.display='none';
    d('dcmd-vm-info').style.display='none'; 
    d(parm).style.display = '';    
   
    if(parm == 'dcmd-vm-info') getVmInfo();
    if(parm == 'dcmd-node-info') getNodeInfo(); 
    if(parm == 'dcmd-run-task') getRuningTask();
    if(parm == 'dcmd-run-opr') getRuningOpr(); 
    if(parm == 'dcmd-os-info') getOsInfo();
    if(parm == 'dcmd-os-user') getOsUser();
    if(parm == 'dcmd-unfinish-task') getTask();
    if(parm == 'dcmd-app') getApp();
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }

//-->
</SCRIPT>
<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-node-l" onclick="showDiv('dcmd-node');this.className='codeDemomouseOnMenu'">设备信息</li>
  <li class="codeDemomouseOutMenu" id="dcmd-run-task-l" onclick="showDiv('dcmd-run-task');this.className='codeDemomouseOnMenu'">在运行任务</li>
  <li class="codeDemomouseOutMenu" id="dcmd-run-opr-l" onclick="showDiv('dcmd-run-opr');this.className='codeDemomouseOnMenu'">在运行操作</li>
  <li class="codeDemomouseOutMenu" id="dcmd-os-info-1" onclick="showDiv('dcmd-os-info');this.className='codeDemomouseOnMenu'">系统信息</li>
  <li class="codeDemomouseOutMenu" id="dcmd-os-user-1" onclick="showDiv('dcmd-os-user');this.className='codeDemomouseOnMenu'">用户信息</li>
  <li class="codeDemomouseOutMenu" id="dcmd-unfinish-task-l" onclick="showDiv('dcmd-unfinish-task'); this.className='codeDemomouseOnMenu'">未归档任务</li>
  <li class="codeDemomouseOutMenu" id="dcmd-app-l" onclick="showDiv('dcmd-app');this.className='codeDemomouseOnMenu'">所属产品</li>
  <li class="codeDemomouseOutMenu" id="dcmd-app-l" onclick="showDiv('dcmd-node-info');this.className='codeDemomouseOnMenu'">设备cmdb信息</li>
</ul>

<div class="dcmd-node-view" id="dcmd-node">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ip:text:服务器IP',
            array('attribute'=>'ngroup_id', 'label'=>'服务器组ID', 'value'=>$model->getNodeGname($model['ngroup_id'])),
            'host:text:主机名',
            'public_ip:text:公网IP',
            'bend_ip:text:带外IP',
            'sid:text:资产序列号',
            'did:text:设备序列号',
            'comment:text:说明',
        ],
    ]) ?>
    <p>
        <?= Html::a('更改', ['update', 'id' => $model->nid], ['class' => 'btn btn-primary',  (Yii::$app->user->getIdentity()->admin == 1) ? "": "style"=>"display:none"]) ?>
    </p>
</div>

<div class="dcmd-vm-info-view" id="dcmd-vm-info">
</div>

<div class="dcmd-node-info-view" id="dcmd-node-info">
</div>

<div class="dcmd-run-task-view" id="dcmd-run-task">

</div>

<div class="dcmd-run-task-view" id="dcmd-run-opr">
</div>

<div class="dcmd-os-info-view" id="dcmd-os-info">
</div>

<div class="dcmd-os-user-view" id="dcmd-os-user">
</div>

<div class="dcmd-unfinish-task" id="dcmd-unfinish-task">
</div>

<div class="dcmd-app" id="dcmd-app">

</div>
<script>
var getRuningTask = function () {
         ip="<?php echo $model->ip; ?>";
         $.get("?r=dcmd-node/get-running-task", { "ip":ip }, function (data, status) {
                                status == "success" ? function () {
                                        $('#dcmd-run-task').html(data);
                                } () : "";
                        }, "text");
};

var getRuningOpr = function () {
         ip="<?php echo $model->ip; ?>";
         $.get("?r=dcmd-node/get-running-opr", { "ip":ip }, function (data, status) {
                                status == "success" ? function () {
                                        $('#dcmd-run-opr').html(data);
                                } () : "";
                        }, "text");
};

var getOsInfo = function () {
         ip="<?php echo $model->ip; ?>";
         $.get("?r=dcmd-node/os-info", { "ip":ip }, function (data, status) {
                                status == "success" ? function () {
                                        $('#dcmd-os-info').html(data);
                                } () : "";
                        }, "text");
};

var getOsUser = function () {
         ip="<?php echo $model->ip; ?>";
         $.get("?r=dcmd-node/os-user", { "ip":ip }, function (data, status) {
                                status == "success" ? function () {
                                        $('#dcmd-os-user').html(data);
                                } () : "";
                        }, "text");
};

var getTask = function() {
   ip="<?php echo $model->ip; ?>";
   $.get("?r=dcmd-node/task-list", { "ip":ip }, function (data, status) {
       status == "success" ? function () {
         $('#dcmd-unfinish-task').html(data);
       } () : "";
   }, "text");
};

var getApp = function() {
   ip="<?php echo $model->ip; ?>";
   $.get("?r=dcmd-node/app-list", { "ip":ip }, function (data, status) {
       status == "success" ? function () {
         $('#dcmd-app').html(data);
       } () : "";
   }, "text");
}

var getNodeInfo = function() {
   ip="<?php echo $model->ip; ?>";
   $.get("?r=dcmd-node/node-info", { "ip":ip}, function (data, status) {
       status == "success" ? function () {
         $('#dcmd-node-info').html(data);
       } () : "";
   });
}

var getVmInfo = function() {
   ip="<?php echo $model->ip; ?>";
   $.get("?r=dcmd-node/vm-info", { "ip":ip}, function (data, status) {
       status == "success" ? function () {
         $('#dcmd-vm-info').html(data);
       } () : "";
   });
}
</script>
