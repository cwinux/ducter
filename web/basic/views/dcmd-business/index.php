<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = "VM状态不匹配:";
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
    d('dcmd-diff').style.display = 'none';    
    d('dcmd-less').style.display='none';   
    d('dcmd-more').style.display='none';    
    d('dcmd-assign-more').style.display='none'; 
    d(parm).style.display = '';    
   
    if(parm == 'dcmd-diff') getDiff();
    if(parm == 'dcmd-less') getLess(); 
    if(parm == 'dcmd-more') getMore();
    if(parm == 'dcmd-assign-more') getAssignMore(); 
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }

//-->
</SCRIPT>
<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-diff-l" onclick="showDiv('dcmd-diff');this.className='codeDemomouseOnMenu'">规格不一致</li>
  <li class="codeDemomouseOutMenu" id="dcmd-less-l" onclick="showDiv('dcmd-less');this.className='codeDemomouseOnMenu'">CMDB缺少主机</li>
  <li class="codeDemomouseOutMenu" id="dcmd-more-l" onclick="showDiv('dcmd-more');this.className='codeDemomouseOnMenu'">CMDB多余主机</li>
  <li class="codeDemomouseOutMenu" id="dcmd-assign-more-1" onclick="showDiv('dcmd-assign-more');this.className='codeDemomouseOnMenu'">分配表多与主机</li>
</ul>

<div class="dcmd-diff-view" id="dcmd-diff">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            array('attribute'=>'product', 'label' => '业务名称', 'enableSorting'=>false),
            array('attribute'=>'bu', 'label' => '所属BU', 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
</div>

<div class="dcmd-less-view" id="dcmd-less">
</div>

<div class="dcmd-more-view" id="dcmd-more">
</div>

<div class="dcmd-assign-more-view" id="dcmd-assign-more">
</div>

<script>
var getDiff = function () {
         ip="10.100.150.200";
         $.get("?r=dcmd-node/get-running-task", { "ip":ip }, function (data, status) {
                                status == "success" ? function () {
                                        $('#dcmd-run-task').html(data);
                                } () : "";
                        });
};

var getLess = function () {
         ip="10.100.150.200";
         $.get("?r=dcmd-node/get-running-opr", { "ip":ip }, function (data, status) {
                                status == "success" ? function () {
                                        $('#dcmd-run-opr').html(data);
                                } () : "";
                        });
};

var getMore = function () {
         ip="10.100.150.200";
         $.get("?r=dcmd-node/os-info", { "ip":ip }, function (data, status) {
                                status == "success" ? function () {
                                        $('#dcmd-os-info').html(data);
                                } () : "";
                        });
};

var getAssignMore = function () {
         ip="10.100.150.200";
         $.get("?r=dcmd-node/os-user", { "ip":ip }, function (data, status) {
                                status == "success" ? function () {
                                        $('#dcmd-os-user').html(data);
                                } () : "";
                        });
};

var getNodeInfo = function() {
   ip="10.100.150.200";
   $.get("?r=dcmd-node/node-info", { "ip":ip}, function (data, status) {
       status == "success" ? function () {
         $('#dcmd-node-info').html(data);
       } () : "";
   });
}

var getVmInfo = function() {
   ip="10.100.150.200";
   $.get("?r=dcmd-node/vm-info", { "ip":ip}, function (data, status) {
       status == "success" ? function () {
         $('#dcmd-vm-info').html(data);
       } () : "";
   });
}
</script>
