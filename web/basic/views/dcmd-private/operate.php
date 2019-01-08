<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\bootstrap\Dropdown;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "私有云管理";
$this->params['breadcrumbs'][] = ['label' => 'VM操作', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="./jquery-2.1.1.min.js"></script>
<script>
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
function myfun()
{
$("#vmList tr").each(function(i, v){
     id = $(this).attr("id");
     if(id != "e-filters"){
       getStatus(id,"status");
   //  alert(id);
     }
  });

}
/*用window.onload调用myfun()*/
//window.onload=myfun;
</script>
<script>
function vmAct(x) {
  var val = $(x).val();
    if (val != "选择操作"){
      if (confirm("确定执行"+val+"操作？")) { 
        if(val == "关机") {
          var action = 'stop'; 
        }
        else if(val == "开机"){
          var action = 'start';
        }
        else if(val == "重启"){
          var action = 'reboot';
        }
      
      
      var id = x.parentNode.parentNode.getAttribute("id");
      vmOperate(id, action);

      var st = window.setInterval(function(){
             getStatus(id,"status");
         },1000);
      window.setTimeout(function(){
             clear(st);
         },10000);
     }
  }
}

function vmOperate(tempid, action){
//  tempid = x.parentNode.parentNode.getAttribute("id");
  url = "/index.php?r=dcmd-private/oprvm"; 
  $.get(url,{id:tempid,action:action},function(data){
    data = $.parseJSON(data);
    alert(data.data);
  });
}

function getStatus(tempid, action){
//  tempid = x.parentNode.parentNode.getAttribute("id");
  url = "/index.php?r=dcmd-private/oprvm"; 
  $.get(url,{id:tempid,action:action},function(data){
    data = $.parseJSON(data);
    address = "#" + tempid;
    $(address).find("td").eq(3).html(data.data);
  });
}
function clear(st){
  $("select#operate").val("选择操作");
  clearInterval(st);
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
            array('attribute'=>'app_name', 'label' => '集群名称', 'content'=> function($model, $key, $index, $column) { return Html::a($model->getClusterByIP($model['app_name']), Url::to(['dcmd-app/view', 'id'=>$model->getNodeIDByIP($model['app_name'])]));}),
            //array('attribute'=>'vm_sn', 'label'=>'虚拟机SN', 'content'=> function($model, $key, $index, $column) { return Html::a($model['vm_sn'], Url::to(['dcmd-private/view', 'id'=>$model['id']]));}),
            array('attribute'=>'host_ip', 'label' => '宿主机IP', 'content'=> function($model, $key, $index, $column) { return Html::a($model['host_ip'], Url::to(['dcmd-node/view', 'id'=>$model->getNodeID($model['host_ip'])]));}),
            array('attribute'=>'vm_ip', 'label'=>'虚拟机IP', 'content'=> function($model, $key, $index, $column) { return Html::a($model['vm_ip'], Url::to(['dcmd-private/opview', 'id'=>$model['id']]));}),
           // array('attribute'=>'state', 'label'=>'VM使用状态', 'enableSorting'=>false, 'filter'=>["0"=>"使用","1"=>"未使用","2"=>"待下线","3"=>"可回收"], 'content'=> function($model, $key, $index, $column) { return $model->getState($model['state']);}),
            array('attribute'=>'state', 'label'=>'运行状态', 'enableSorting'=>false),
/*            [
                'label'=>'运行状态',
                'format'=>'raw',
                'value'=> function(){
                    return "";
//Html::label('sa','state');
                }
            ],*/
            [
                'label'=>'操作',
                'format'=>'raw',
                'value' => function(){
                     return Html::dropDownList('operate','选择操作',['选择操作'=>'选择操作','开机'=>'开机','重启'=>'重启','关机'=>'关机'],['onchange'=>'vmAct(this)','id'=>'operate']);
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
