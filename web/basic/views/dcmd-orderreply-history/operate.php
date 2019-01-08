<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '私有云';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="./jquery-2.1.1.min.js"></script>
<script>
function unuse() {
  $("#w0").attr("action", "/index.php?r=dcmd-private/unuse");
}
function used() {
  $("#w0").attr("action", "/index.php?r=dcmd-private/used");
}
function offline() {
  $("#w0").attr("action", "/index.php?r=dcmd-private/offline");
}
function reuse() {
  $("#w0").attr("action", "/index.php?r=dcmd-private/reuse");
}
function vmAction($val) {
  if (confirm("确定执行"+$val+"操作？")) { 
    if($val == "关机") {
      alert("确定执行"+$val); 
    }
    else if($val == "开机"){
      alert("确定执行"+$val);
    }
    else {
      alert("确定执行"+$val);
    }
  }
}
</script>
<script>
function vmAct(x) {
  var id = x.parentNode.parentNode.getAttribute("id");
  window.setInterval(function(){
         getStatus(id);
     },1000);
}
function getStatus(tempid){
//  tempid = x.parentNode.parentNode.getAttribute("id");
  url = "/index.php?r=dcmd-private/off"; 
  $.get(url,{id:tempid},function(data){
    data = $.parseJSON(data);
    address = "#" + tempid;
    $(address).find("td").eq(5).html(data.data);
  });
}
</script>
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
        'id' => 'e',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){ return ['id'=>$model->vm_uuid];}, 
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'id'=>'vmList'],
        'columns' => [
//            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'app_name', 'label' => '集群名称', 'content'=> function($model, $key, $index, $column) { return Html::a($model->getClusterByIP($model['app_name']), Url::to(['dcmd-node-group/view', 'id'=>$model->getNodeIDByIP($model['app_name'])]));}),
            //array('attribute'=>'vm_sn', 'label'=>'虚拟机SN', 'content'=> function($model, $key, $index, $column) { return Html::a($model['vm_sn'], Url::to(['dcmd-private/view', 'id'=>$model['id']]));}),
            array('attribute'=>'host_ip', 'label' => '宿主机IP', 'content'=> function($model, $key, $index, $column) { return Html::a($model['host_ip'], Url::to(['dcmd-node/view', 'id'=>$model->getNodeID($model['host_ip'])]));}),
            array('attribute'=>'vm_ip', 'label'=>'内网IP', 'content'=> function($model, $key, $index, $column) { return Html::a($model['vm_ip'], Url::to(['dcmd-private/view', 'id'=>$model['id']]));}),
            array('attribute'=>'state', 'label'=>'VM使用状态', 'enableSorting'=>false, 'filter'=>["使用"=>"使用","未使用"=>"未使用","待下线"=>"待下线","可回收"=>"可回收"]),
            array('attribute'=>'business', 'label'=>'业务名称', 'enableSorting'=>false),
            [
                'label'=>'运行状态',
                'format'=>'raw',
                'value'=> function(){
                    return "dd";
//Html::label('sa','state');
                }
            ],
            [
                'label'=>'操作',
                'format'=>'raw',
                'value' => function(){
                     return Html::dropDownList('operate','选择操作',['选择操作'=>'选择操作','开机'=>'开机','重启'=>'重启','关机'=>'关机'],['onchange'=>'vmAct(this)']);
                   // return Html::dropDownList('operate','选择操作',['选择操作'=>'选择操作','开机'=>'开机','重启'=>'重启','关机'=>'关机'],['onchange'=>'vmAction('.'$(this).val()'.')']);
//['onchange'=>'this.form.submit()']); 
                    //return Html::a('添加权限组', $url, ['title' => '审核']);
                }
            ],
       ],
              
    ]); ?>
</div>
</form>

<script>
//var int=self.setInterval("getStatus1()",1000);
function getStatus1(){
         $("#vmList tr").each(function(i, v){
                tempid = $(this).attr("id");
                if (tempid != "" && tempid != null) {
                     var address = ""
                     if (tempid != "e-filters") {
                         address = "#" + tempid;
                         if ($(address).find("td").eq(5).html()=="关机"){
                           $(address).find("td").eq(5).html("正在运行");
                         }
                         else {
                           $(address).find("td").eq(5).html("关机");
                         }
                     }
                }

         });
}
</script>
