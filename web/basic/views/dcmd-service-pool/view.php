<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */

$this->title = $model->svr_pool;
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

//window.onload=function(){
//  <?php
//    if(!empty($show_div))
//     echo "document.getElementById('". $show_div."-l').click()";
//  ?>
//}

 
  function showDiv(parm){
    writeCookie('poolTab', parm);
    d('dcmd-service-pool').style.display = 'none';    
    d('dcmd-service-pool-node').style.display='none';    
    d('dcmd-service-pool-attr').style.display='none';
    d('dcmd-service-pool-source').style.display='none';
    d('dcmd-conf-version').style.display='none';
    d('dcmd-service-pool-port').style.display='none';
    d('dcmd-service-docker').style.display='none';
    d(parm).style.display = '';    
    
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }
</SCRIPT>
<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-service-pool-node-l" onclick="showDiv('dcmd-service-pool-node');this.className='codeDemomouseOnMenu'">服务池设备</li>
  <li class="codeDemomouseOutMenu" id="dcmd-service-pool-source-l" onclick="showDiv('dcmd-service-pool-source');this.className='codeDemomouseOnMenu'">服务池组件</li>
  <li class="codeDemomouseOutMenu" id="dcmd-service-pool-l" onclick="showDiv('dcmd-service-pool');this.className='codeDemomouseOnMenu'">服务池信息</li>
  <li class="codeDemomouseOutMenu" id="dcmd-service-docker-l" onclick="showDiv('dcmd-service-docker');this.className='codeDemomouseOnMenu'">容器信息</li>
  <li class="codeDemomouseOutMenu" id="dcmd-service-pool-attr-l" onclick="showDiv('dcmd-service-pool-attr');this.className='codeDemomouseOnMenu'">服务池属性</li>
  <li class="codeDemomouseOutMenu" id="dcmd-conf-version-l" onclick="showDiv('dcmd-conf-version');this.className='codeDemomouseOnMenu'">配置版本</li>
  <li class="codeDemomouseOutMenu" id="dcmd-service-pool-port-l" onclick="showDiv('dcmd-service-pool-port');this.className='codeDemomouseOnMenu'">服务池端口</li>
</ul>
<form id="w0" action="/index.php?r=dcmd-service-pool-node/delete-select" method="post">
<div class="dcmd-service-pool-node-view" id="dcmd-service-pool-node">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'ip','label'=>'IP', 'enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model["ip"], Url::to(['dcmd-node/view-ip', 'ip'=>$model['ip']]));},),
            array('attribute'=>'ip', 'label'=>'主机名', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'ip', 'label'=>'连接状态','enableSorting'=>false, 'filter'=>false,  'content'=>function($model, $key,$index, $column) { return $model->getAgentState($model['ip']);}),
            array('attribute'=>'tag', 'label'=>'软件版本', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'env_ver', 'label'=>'配置版本', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>"ip", 'label'=>'端口','enableSorting'=>false,'filter'=>false,'content' => function($model, $key, $index, $column) { return Html::a("查看", Url::to(['dcmd-service-pool/node-port', 'svr_pool_id'=>$model['svr_pool_id'],'ip'=>$model['ip']]));},),
            ['class' => 'yii\grid\ActionColumn','template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-service-pool-node/delete','id'=>$model['id'], 'svr_pool_id'=>$model['svr_pool_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? true : false],
        ],
    ]); ?>

    <p>
        <?= Html::a('添加', ['dcmd-service-pool-node/select-node-group', 'app_id'=>$model['app_id'], 'svr_id'=>$model['svr_id'], 'svr_pool_id'=>$model['svr_pool_id']], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>
       <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> 
       <?= Html::a('操作', ['dcmd-service-pool/opr', 'svr_pool_id'=>$svr_pool_id], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>
       <?= Html::a('重复操作', ['dcmd-service-pool/repeat-opr', 'svr_pool_id'=>$svr_pool_id], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?> 
 
    </p>
</div>

<div class="dcmd-service-pool-view" id="dcmd-service-pool" style="display:none" >
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           array('attribute'=>'svr_pool', 'label'=>'服务池'),
           array('attribute'=>'pool_group', 'label'=>'服务池组'),
           array('attribute'=>'repo', 'label'=>'版本地址'),
           array('attribute'=>'env_ver', 'label'=>'配置版本'),
           array('attribute'=>'env_md5', 'label'=>'配置md5'),
           array('attribute'=>'tag', 'label'=>'软件版本'),
           array('attribute'=>'tag_task_id', 'label'=>'软件版本所属任务'),
           array('attribute'=>'comment', 'label'=>'说明'),
        ],
    ]) ?>
    <p>
        <?= Html::a('更新', ['update', 'id' => $model->svr_pool_id], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>

<div class="dcmd-service-docker-view" id="dcmd-service-docker" style="display:none" >
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           array('attribute'=>'image_url', 'label'=>'镜像地址'),
           array('attribute'=>'image_name', 'label'=>'镜像名称'),
           array('attribute'=>'image_user', 'label'=>'harbor用户名'),
           array('attribute'=>'svr_mem', 'label'=>'内存'),
           array('attribute'=>'svr_cpu', 'label'=>'CPU'),
           array('attribute'=>'svr_net', 'label'=>'Net'),
           array('attribute'=>'svr_io', 'label'=>'IO'),
        ],
    ]) ?>
    <p>
        <?= Html::a('更新', ['update', 'id' => $model->svr_pool_id], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>

<div class="dcmd-conf-version-view" id="dcmd-conf-version" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $conf_version,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'version','label'=>'配置版本', 'enableSorting'=>false,),
            array('attribute'=>'md5', 'label'=>'配置md5', 'enableSorting'=>false, 'filter'=>false),
//            array('attribute'=>'passwd', 'label'=>'passwd','enableSorting'=>false, 'filter'=>false),
        ],
    ]); ?>

    <p>
        <?= Html::a('添加', ['dcmd-service-pool/create-conf', 'app_id'=>$model['app_id'], 'svr_id'=>$model['svr_id'], 'svr_pool_id'=>$model['svr_pool_id']], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>

<div class="dcmd-service-poolsource--view" id="dcmd-service-pool-source" style="display:none" >
<?= GridView::widget([
        'dataProvider' => $resProvider,
        'filterModel' => $resSearch,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'res_id','label'=>'唯一行号','content' => function($model, $key, $index, $column) { return Html::a('<font class="sourceID">'.$model['res_id'], Url::to(['dcmd-resource/view', 'res_id'=>$model['res_id'], 'res_type'=>$model['res_type']]));}),
            //array('attribute'=>'res_id','label'=>'唯一行号','content' => function($model, $key, $index, $column) { return Html::a('<font class="sourceID">'.$model['res_id'], Url::to(['dcmd-resource/view', 'id'=>$model['res_id']]));}),
            array('attribute'=>'res_name', 'label'=>'资源名称', 'filter'=>false, 'enableSorting'=>false,),
            array('attribute'=>'res_type', 'label'=>'资源类型', 'filter'=>false, 'enableSorting'=>false,),
             array('attribute'=>'is_own', 'label'=>'是否所有者', 'filter'=>false, 'enableSorting'=>false,'content' => function($model, $key, $index, $column) { return $model['is_own'] ? '是':'否';}),
//            ['class' => 'yii\grid\ActionColumn',  "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>

</div>



<div class="dcmd-service-pool-attr" id="dcmd-service-pool-attr" style="display:none" >
<?php echo $attr_str; ?>
</div>

<div class="dcmd-service-pool-port-view" id="dcmd-service-pool-port" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $poolportProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'port_name', 'label'=>'端口名','enableSorting'=>false),
            array('attribute'=>'port', 'label'=>'服务端口','enableSorting'=>false),
            array('attribute'=>'mapped_port', 'label'=>'主机端口','enableSorting'=>false),
  //          array('attribute'=>'passwd', 'label'=>'密码','enableSorting'=>false),
#            array('attribute'=>'app_id', 'label'=>'产品名称', 'value'=>function($model, $key, $index, $colum) { return $model->getAppName($model['app_id']); }, 'enableSorting'=>false  ),

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{update}', 'urlCreator'=>function($action, $model, $key, $index ) {return Url::to(['dcmd-service-pool/update-pool-port', 'svr_pool_id'=>$model['svr_pool_id'],'id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? true : false],
        ],
    ]); ?>
</div>

<script src="./jquery-2.1.1.min.js">
  <?php 
    if(!empty($show_div)) 
     echo "document.getElementById('". $show_div."-l').click()";
  ?>
</script>
<script>
  $(document).on('mouseover','.sourceID',function(e){
    tip="<p class='tip'><font size='2'>DETAIL</font></p>";
    $(".sourceID").append(tip);
    $(".tip").css({"top":(e.pageY-30)+"px","left":(e.pageX-35)+"px","position":"absolute", "background": "gray", "box-shadow": "-2px -2px 0 -1px #c4c4c4", "box-shadow": "0 2px 8px rgba(0,0,0,.3)", "color": "white"}).show("fast");
  });
  $(document).on('mouseleave','.sourceID',function(e){
    $(".tip").remove();
  }); 
</script>
<body onload="setTab()">
<script>
  function setTab()
  {
    var show_div = readCookie('poolTab') + '-l';
    document.getElementById(show_div).click()
  }
  function saveRadioValue()
  {
      var rad = document.getElementsByName("myRadio");
      var radval = 1;
      for(var i=0;i<rad.length;i++)
      {
         if(rad[i].checked)
               var radval = rad[i].value;
      }
      var cookiename = 'alarmlevel';
      writeCookie(cookiename, radval);
      window.location.reload();

  }
  function writeCookie(name, value, hours) {
      var expire = "";
      hours = 0.2;
      if (hours != null) {
          expire = new Date((new Date()).getTime() + hours * 3600000);
          expire = "; expires=" + expire.toGMTString();
      }
      document.cookie = name + "=" + escape(value) + expire;
  }
  function readCookie(name) {
      var cookieValue = "";
      var search = name + "=";
      if (document.cookie.length > 0) {
          offset = document.cookie.indexOf(search);
          if (offset != -1) {
              offset += search.length;
              end = document.cookie.indexOf(";", offset);
              if (end == -1) end = document.cookie.length;
              cookieValue = unescape(document.cookie.substring(offset, end))
          }
      }
      var rad = document.getElementsByName("myRadio");
      return cookieValue;
  }
</script>


