<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */

$this->title = $model->pool_name;
$this->params['breadcrumbs'][] = ['label' => '服务池子', 'url' => ['index']];
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
  var d = function(o)  {
    return document.getElementById(o);
  }
 
  function showDiv(parm){
    d('dcmd-service-pool').style.display = 'none';    
    d('dcmd-service-pool-node').style.display='none';    
    d('dcmd-service-pool-attr').style.display='none';
    d(parm).style.display = '';    
    
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }
</SCRIPT>
<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-service-pool-node-l" onclick="showDiv('dcmd-service-pool-node');this.className='codeDemomouseOnMenu'">服务池信息设备</li>
</ul>
<form id="w0" action="/index.php?r=dcmd-cbase-app-node/delete-all" method="post">
<div class="dcmd-service-pool-node-view" id="dcmd-service-pool-node">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'ip','label'=>'IP', 'enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model["ip"], Url::to(['dcmd-node/view-ip', 'ip'=>$model['ip']]));},),
            array('attribute'=>'status', 'label'=>'状态',),
#'enableSorting'=>false, 'filter'=>false,'content' => function($model, $key, $index, $column) {if($model->state) return '在线';else return "下线";},),
            ['class' => 'yii\grid\ActionColumn','template'=>'{update}{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-cbase-app-node/delete','id'=>$model['id']]);else return Url::to(['dcmd-cbase-app-node/update','id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
            [
                'label'=>'更多操作',
                'format'=>'raw',
                'content' => function($model, $key, $index, $column){
                    $url = "http://".$model['ip'].":8091";
                    return Html::a('控制面板', $url, ['title' => '控制面板']); 
                }
            ]        
        ],
    ]); ?>

    <p>
        <?= Html::a('添加', ['dcmd-cbase-app-node/select-node-group', 'app_id'=>$model['app_id']], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 ) ? "" : "style"=>"display:none"]) ?>
       <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> 
 
    </p>
</div>

<script>
  <?php 
    if(!empty($show_div)) 
     echo "document.getElementById('". $show_div."-l').click()";
  ?>
</script>


