<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */

$this->title = "端口信息:".$ip;
$this->params['breadcrumbs'][] = $this->title;
?>
<SCRIPT LANGUAGE="JavaScript">
<!--  
  var d = function(o)  {
    return document.getElementById(o);
  }

//  window.onload=function(){
//    <?php
//      if(!empty($show_div))
//      echo "document.getElementById('". $show_div."-l').click()";
//    ?>
// }
 
  function showDiv(parm){
    writeCookie('serviceTab', parm);
    d('dcmd-service').style.display = 'none';    
    d('dcmd-task-tempt').style.display='none'; 
    d('dcmd-compile').style.display='none';
    d('dcmd-upload').style.display='none';
    d('dcmd-pkg').style.display='none';
    d('dcmd-script').style.display='none';
    d('dcmd-pool-group').style.display='none';
    d('dcmd-service-port').style.display='none';
    d(parm).style.display = '';    
    
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }

  function showRes(parm){
    d('dcmd-mysql').style.display = 'none';    
    d('dcmd-redis').style.display='none'; 
    d(parm).style.display = '';    
    for(var i in d('reMenu').getElementsByTagName('LI')){        
     d('reMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }
//-->
</SCRIPT>

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
<form id="w0" method="post">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
        //    array('attribute'=>'ip','label'=>'ip'),
            //array('attribute'=>'res_id','label'=>'唯一行号','content' => function($model, $key, $index, $column) { return Html::a('<font class="sourceID">'.$model['res_id'], Url::to(['dcmd-resource/view', 'id'=>$model['res_id']]));}),
            array('attribute'=>'port_name', 'label'=>'端口名', 'filter'=>false, 'enableSorting'=>false,),
            array('attribute'=>'port', 'label'=>'端口', 'filter'=>false, 'enableSorting'=>false),
        ],
    ]); ?>
   </div>

</div>

