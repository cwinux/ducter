<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = '工单管理';
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
</script>
<SCRIPT LANGUAGE="JavaScript">
<!--
  var d = function(o)  {
    return document.getElementById(o);
  }
  window.onload = showDiv1;
  function showDiv(parm){
    d('dcmd-order').style.display = 'none';    
    d('dcmd-order-manual').style.display='none';   
    d(parm).style.display = '';    

    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }
  function showDiv1(){
    d('dcmd-order').style.display = 'none';    
    d('dcmd-order-manual').style.display='none';   
    d('dcmd-order').style.display = '';    

    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }
//-->
</SCRIPT>

<div class="dcmd-order-manual-view" id="dcmd-order-manual">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            array('attribute'=>'bill_id','label'=>'工单号','enableSorting'=>false),
            array('attribute'=>'action', 'label'=>'操作', 'enableSorting'=>false),
            array('attribute'=>'state', 'label'=>'状态', 'enableSorting'=>false),
            array('attribute'=>'apply_user', 'label'=>'申请人', 'enableSorting'=>false),
            array('attribute'=>'manual', 'label'=>'是否手动操作', 'filter' => ['0'=>'否','1'=>'是'],'enableSorting'=>false, 'content'=>function($model, $key, $index, $column) { return $model->getManual($model['manual']);}),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{view}','urlCreator'=>function($action, $model, $key, $index) {if ("view" == $action) return Url::to(['dcmd-order/manual-view','id'=>$model['order_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
</div>

<script>
var getOrderManual = function() {
   $.get("?r=dcmd-order/get-order-manual", function (data, status) {
       status == "success" ? function () {
         $('#dcmd-order-manual').html(data);
       } () : "";
   });
}
</script>
