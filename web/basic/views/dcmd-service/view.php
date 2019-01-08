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

$this->title = $model->svr_name;
$this->params['breadcrumbs'][] = ['label' => '服务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
window.onload=function(){alert("test");}

function pass() { 
  alert("确定接收!");
  var app_id = <?php echo $model['app_id']; ?>;
  var svr_id = <?php echo $model['svr_id']; ?>;
  $("#w0").attr("action", "/index.php?r=dcmd-service/accept-file&app_id="+app_id+"&svr_id="+svr_id);
}
function reject() {
  alert("确定驳回!");
  var app_id = <?php echo $model['app_id']; ?>;
  var svr_id = <?php echo $model['svr_id']; ?>;
  $("#w0").attr("action", "/index.php?r=dcmd-service/reject-file&app_id="+app_id+"&svr_id="+svr_id);
}
</script>
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
<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-service-l" onclick="showDiv('dcmd-service');this.className='codeDemomouseOnMenu'">服务信息</li>
  <li class="codeDemomouseOutMenu" id="dcmd-pool-group-l" onclick="showDiv('dcmd-pool-group');this.className='codeDemomouseOnMenu'">服务池组</li>
  <li class="codeDemomouseOutMenu" id="dcmd-task-tempt-l" onclick="showDiv('dcmd-task-tempt');this.className='codeDemomouseOnMenu'">任务模版</li>
  <li class="codeDemomouseOutMenu" id="dcmd-compile-l" onclick="showDiv('dcmd-compile');this.className='codeDemomouseOnMenu'">编译</li>
  <li class="codeDemomouseOutMenu" id="dcmd-upload-l" onclick="showDiv('dcmd-upload');this.className='codeDemomouseOnMenu'">版本审核</li>
  <li class="codeDemomouseOutMenu" id="dcmd-pkg-l" onclick="showDiv('dcmd-pkg');this.className='codeDemomouseOnMenu'">服务版本</li>
  <li class="codeDemomouseOutMenu" id="dcmd-script-l" onclick="showDiv('dcmd-script');this.className='codeDemomouseOnMenu'">服务脚本</li>
  <li class="codeDemomouseOutMenu" id="dcmd-service-port-l" onclick="showDiv('dcmd-service-port');this.className='codeDemomouseOnMenu'">服务端口</li>
</ul>
<div class="dcmd-service-view" id="dcmd-service">
    <div style="background:#f1f1f1;padding:10px;margin-top:10px">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            array('attribute'=>'svr_name', 'label'=>'服务名'),
            array('attribute'=>'svr_alias', 'label'=>'服务别名'),
            array('attribute'=>'service_tree', 'label'=>'服务树'),
            array('attribute'=>'svr_path', 'label'=>'安装路径'),
            array('attribute'=>'run_user', 'label'=>'运行用户'),
            array('attribute'=>'svr_mem', 'label'=>'容器内存/m'),
            array('attribute'=>'svr_cpu', 'label'=>'容器CPU'),
            array('attribute'=>'svr_net', 'label'=>'容器Net/m'),
            array('attribute'=>'svr_io', 'label'=>'容器IO'),
            array('attribute'=>'image_name', 'label'=>'容器镜像'),
            array('attribute'=>'app_id', 'label'=>'所属产品', 'value'=>$model->getAppName($model['app_id'])),
            array('attribute'=>'node_multi_pool', 'label'=>'节点多池子', 'value'=>$model->convert($model['node_multi_pool'])),
            array('attribute'=>'owner', 'label'=>'拥有者', 'value'=>$model->getUserName($model['owner'])),
            array('attribute'=>'comment', 'label'=>'说明'),
            array('attribute'=>'utime', 'label'=>'修改时间'),
            array('attribute'=>'ctime', 'label'=>'创建时间'),
            array('attribute'=>'opr_uid', 'label'=>'修改者', 'value'=>$model->getUserName($model['opr_uid'])),
        ],
    ]) ?>
    <p>
        <?= Html::a('更新', ['update', 'id' => $model->svr_id], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>
    <!--    <?= Html::a('删除', ['delete', 'id' => $model->svr_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>-->
    </p>
    </div> <br>
   <div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'pool_group','label'=>'服务池组','filter'=>$pool_group),
            array('attribute'=>'svr_pool','label'=>'服务池','content' => function($model, $key, $index, $column) { return Html::a($model['svr_pool'], Url::to(['dcmd-service-pool/view', 'id'=>$model['svr_pool_id']]),$options = ['target' => '_blank']);}),
            array('attribute'=>'env_ver', 'label'=>'配置版本', 'filter'=>false, 'enableSorting'=>false,),
            array('attribute'=>'svr_pool','label'=>'服务池任务','content' => function($model, $key, $index, $column) { return Html::a($model->getTask($model['svr_pool_id']), Url::to(['dcmd-task-async/monitor-task', 'task_id'=>$model->getTaskId($model['svr_pool_id'])]));}),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}','urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-service-pool/delete', 'id'=>$model['svr_pool_id'], 'svr_id'=>$model['svr_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加', ['dcmd-service-pool/create', 'app_id'=>$model['app_id'], 'svr_id'=>$model['svr_id']], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>
        <?= Html::a('cp from', ['dcmd-service-pool/select-service-pool', 'app_id'=>$model['app_id'], 'svr_id'=>$model['svr_id']], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>
    </p>
    </div>

   <div  style="background:#f1f1f1;padding:10px;margin-top:10px">
   <p style="font-size:15px;color:#0066CC">组件信息</p>
    <?= GridView::widget([
        'dataProvider' => $resProvider,
        'filterModel' => $resSearch,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'res_id','label'=>'唯一行号','content' => function($model, $key, $index, $column) { return Html::a('<font class="sourceID">'.$model['res_id'], Url::to(['dcmd-resource/view', 'res_id'=>$model['res_id'], 'res_type'=>$model['res_type']]));}),
            //array('attribute'=>'res_id','label'=>'唯一行号','content' => function($model, $key, $index, $column) { return Html::a('<font class="sourceID">'.$model['res_id'], Url::to(['dcmd-resource/view', 'id'=>$model['res_id']]));}),
            array('attribute'=>'res_name', 'label'=>'资源名称', 'filter'=>false, 'enableSorting'=>false,),
            array('attribute'=>'svr_pool_id', 'label'=>'服务池名称', 'filter'=>false, 'enableSorting'=>false,'content' => function($model, $key, $index, $column) { return $model->getPool($model['svr_pool_id']);}),
            array('attribute'=>'res_type', 'label'=>'资源类型', 'filter'=>false, 'enableSorting'=>false,),
            array('attribute'=>'is_own', 'label'=>'是否所有者', 'filter'=>false, 'enableSorting'=>false,'content' => function($model, $key, $index, $column) { return $model['is_own'] ? '是':'否';}),
        ],
    ]); ?>
   </div>

</div>

<div class="dcmd-task-tempt-view" id="dcmd-task-tempt" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $taskTemptDataProvider,
        'layout' => "{items}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'task_tmpt_name', 'label'=>'任务模板名称', 'content'=>function($model, $key, $index,$column) { return  Html::a($model['task_tmpt_name'], Url::to(['dcmd-task-template/view', 'id'=>$model['task_tmpt_id']]));},),
            array('attribute'=>'task_cmd_id', 'value'=>function($model, $key, $index, $col) { return $model['task_cmd'];}, 'label'=>'任务脚本', 'enableSorting'=>false),
            array('attribute'=>'app_id', 'label'=>'产品名称', 'value'=>function($model, $key, $index, $colum) { return $model->getAppName($model['app_id']); }, 'enableSorting'=>false  ),

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index ) {return Url::to(['dcmd-task-template/delete', 'id'=>$model['task_tmpt_id'], 'svr_id'=>$model['svr_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加', ['dcmd-task-template/create-by-svr', 'app_id'=>$model["app_id"], 'svr_id'=>$model["svr_id"]], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>

</div>

<div class="dcmd-compile-view" id="dcmd-compile" style="display:none">
<div style="background:#f1f1f1;padding:10px;margin-top:10px">
   <p>CI信息:</p>
    <?= GridView::widget([
        'dataProvider' => $ciProvider,
        'layout' => "{items}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'ci_type', 'label'=>'CI类型','enableSorting'=>false),
            array('attribute'=>'ci_jenkins_url', 'label'=>'jenkins地址', 'enableSorting'=>false),
            array('attribute'=>'ci_url', 'label'=>'项目地址', 'enableSorting'=>false),
            array('attribute'=>'comment', 'label'=>'说明', 'enableSorting'=>false),
#            array('attribute'=>'app_id', 'label'=>'产品名称', 'value'=>function($model, $key, $index, $colum) { return $model->getAppName($model['app_id']); }, 'enableSorting'=>false  ),
//            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index ) {return Url::to(['dcmd-service/delete-ci', 'ci_id'=>$model['ci_id'], 'app_id'=>$model['app_id'],'svr_id'=>$model['svr_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::a('配置CI', ['dcmd-service/create-ci', 'app_id'=>$model["app_id"], 'svr_id'=>$model["svr_id"]], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>
        <?= Html::a('创建Job', ['dcmd-service/create-ci-job', 'svr_id'=>$model["svr_id"]], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
<p>CI Job信息:</p>
 <?= GridView::widget([
        'dataProvider' => $ciJobProvider,
        'layout' => "{items}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'ci_job', 'label'=>'Job ID', 'enableSorting'=>false),
            array('attribute'=>'state', 'label'=>'状态', 'enableSorting'=>false),
            array('attribute'=>'source_branch','label'=>'git分支','enableSorting'=>false),
            array('attribute'=>'source_sha1','label'=>'git sha1','enableSorting'=>false),
            array('attribute'=>'source_xml','label'=>'git xml','enableSorting'=>false),
            array('attribute'=>'pkg_version','label'=>'版本号','enableSorting'=>false),
            array('attribute'=>'opr_uid','label'=>'创建人','enableSorting'=>false,'content' => function($model, $key, $index, $column) { return $model->getUser($model->opr_uid);}),
            array('attribute'=>'ctime','label'=>'创建时间','enableSorting'=>false),
            array('attribute'=>'state', 'label'=>'', 'enableSorting'=>false,'content' => function($model, $key, $index, $column) { return '<a href="'.$model->ci_url.$model->ci_job.'" target="_blank">查看</a>';}),
//Html::a("查看", ['http://10.110.101.200:8080/job/openweixin/103/'],['target'=>'_blank']);}),
#            array('attribute'=>'app_id', 'label'=>'产品名称', 'value'=>function($model, $key, $index, $colum) { return $model->getAppName($model['app_id']); }, 'enableSorting'=>false  ),
//            ['class' => 'yii\grid\ActionColumn', 'template'=>'{view}{update}{delete}', 'urlCreator'=>function($action, $model, $key, $index ) {return Url::to(['dcmd-service/delete-ci', 'ci_id'=>$model['ci_id'], 'app_id'=>$model['app_id'],'svr_id'=>$model['svr_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>

</div>

<div class="dcmd-upload-view" id="dcmd-upload" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $uploadProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'svr_pool', 'label'=>'服务池','enableSorting'=>false),
            array('attribute'=>'version', 'label'=>'版本','enableSorting'=>false),
            array('attribute'=>'upload_type', 'label'=>'类型','enableSorting'=>false),
            array('attribute'=>'upload_username', 'label'=>'上传者', 'enableSorting'=>false),
            array('attribute'=>'upload_time', 'label'=>'上传时间', 'enableSorting'=>false),
            array('attribute'=>'passwd', 'label'=>'密码','enableSorting'=>false),
            array('attribute'=>'md5', 'label'=>'md5','enableSorting'=>false),
#            array('attribute'=>'app_id', 'label'=>'产品名称', 'value'=>function($model, $key, $index, $colum) { return $model->getAppName($model['app_id']); }, 'enableSorting'=>false  ),

//            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index ) {return Url::to(['dcmd-task-template/delete', 'id'=>$model['task_tmpt_id'], 'svr_id'=>$model['svr_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
        <?= Html::submitButton('通过', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none", 'onClick'=>"pass()"])?>&nbsp;&nbsp;
        <?= Html::submitButton('驳回', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none", 'onClick'=>"reject()"])?>&nbsp;&nbsp;
</div>

<div class="dcmd-pkg-view" id="dcmd-pkg" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $pkgProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'version', 'label'=>'版本','enableSorting'=>false),
  //          array('attribute'=>'passwd', 'label'=>'密码','enableSorting'=>false),
            array('attribute'=>'md5', 'label'=>'md5','enableSorting'=>false),
            array('attribute'=>'username', 'label'=>'上传者', 'enableSorting'=>false),
            array('attribute'=>'ctime', 'label'=>'上传时间', 'enableSorting'=>false),
#            array('attribute'=>'app_id', 'label'=>'产品名称', 'value'=>function($model, $key, $index, $colum) { return $model->getAppName($model['app_id']); }, 'enableSorting'=>false  ),

//            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index ) {return Url::to(['dcmd-task-template/delete', 'id'=>$model['task_tmpt_id'], 'svr_id'=>$model['svr_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加', ['dcmd-service/create-pkg', 'app_id'=>$model['app_id'], 'svr_id'=>$model['svr_id']], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>

<div class="dcmd-pool-group-view" id="dcmd-pool-group" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $groupProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'pool_group', 'label'=>'服务池组名','enableSorting'=>false),
  //          array('attribute'=>'passwd', 'label'=>'密码','enableSorting'=>false),
#            array('attribute'=>'app_id', 'label'=>'产品名称', 'value'=>function($model, $key, $index, $colum) { return $model->getAppName($model['app_id']); }, 'enableSorting'=>false  ),

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index ) {return Url::to(['dcmd-service/delete-pool-group', 'id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加', ['dcmd-service/create-pool-group', 'app_id'=>$model['app_id'], 'svr_id'=>$model['svr_id']], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>

<div class="dcmd-script-view" id="dcmd-script" style="display:none">
<?php echo $ret_msg; ?>
<?= Html::a('添加脚本', ['dcmd-service/upload', 'app_id'=>$model['app_id'], 'svr_id'=>$model['svr_id']], ['target'=>'_blank','class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>&nbsp;&nbsp;&nbsp;
</div>

<div class="dcmd-service-port-view" id="dcmd-service-port" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $svrportProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'port_name', 'label'=>'端口名','enableSorting'=>false),
            array('attribute'=>'protocol', 'label'=>'协议','enableSorting'=>false),
            array('attribute'=>'def_port', 'label'=>'端口号','enableSorting'=>false),
  //          array('attribute'=>'passwd', 'label'=>'密码','enableSorting'=>false),
#            array('attribute'=>'app_id', 'label'=>'产品名称', 'value'=>function($model, $key, $index, $colum) { return $model->getAppName($model['app_id']); }, 'enableSorting'=>false  ),

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index ) {return Url::to(['dcmd-service/delete-service-port', 'svr_port_id'=>$model['svr_port_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加', ['dcmd-service/create-service-port', 'app_id'=>$model['app_id'], 'svr_id'=>$model['svr_id']], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $is_self) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
<!-- </br>
<div style="width:170px">
<div class="form-group field-uploadform-pool_group">
<select id="uploadform-pool_group" class="form-control" name="UploadForm[pool_group]" onFocus="getGroup()">
<option value="">---服务池组---</option>
</select>

<div class="help-block"></div>
</div>
</div>
<?= Html::a('添加脚本', ['dcmd-service/upload', 'app_id'=>$model['app_id'], 'svr_id'=>$model['svr_id']], ['target'=>'_blank','class' => 'btn btn-success']) ?>&nbsp;&nbsp;&nbsp;
<input id="saveButton" type="button" value="已保存脚本" class='btn btn-primary' onclick="loadSaveScript()"></input>&nbsp;&nbsp;&nbsp;
<input type="button" value="审批中脚本" class='btn btn-success' onclick="loadSubmitScript()"></input>&nbsp;&nbsp;&nbsp;
<input type="button" value="审批完成脚本" class='btn btn-primary' onclick="loadOnlineScript()"></input>
<div id="shellDiv" style="height: auto; width: 800px; background-color: #000; color: #FFF; padding: 10px 3px 10px 10px;display:none">
 服务脚本内容:
 <div id="ShellContent" style="margin: 10px 0px 10px 10px; overflow-y: auto; height: auto; overflow-x: hidden"></div>
</div>
<div id="shellButton" style="display:none"><input type="button" value="提交审批" class='btn btn-primary' onclick="add_submit()"></input>&nbsp;&nbsp;<input type="button" value="删除" class='btn btn-success' onclick="delete_save()"></input></div>
 </div> -->
<script>
function getGroup(){
  svr_id="<?php echo $model['svr_id']; ?>";
  url = "/index.php?r=dcmd-service/get-pool-group";
  $.get(url,{svr_id:svr_id},function(data){
    $('#uploadform-pool_group').html(data);
  });
}
</script>
<script>
var loadSubmitScript = function () {
         app_id="<?php echo $model['app_id']; ?>";
         svr_id="<?php echo $model['svr_id']; ?>";
         var options=$("#uploadform-pool_group option:selected");
         document.getElementById('shellDiv').style.display="";
         $.post("?r=dcmd-service/load-submit", {"app_id":app_id,"svr_id":svr_id,"pool_group":options.val()}, function (data, status) {
                                status == "success" ? function () {
                                        document.getElementById('shellButton').style.display="none";
                                        var dataO = jQuery.parseJSON(data); 
                                        $('#ShellContent').html(dataO.result);
                                } () : "";
                        }, "text");
};
</script>
</div>

<script src="./jquery-2.1.1.min.js"></script>
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
<script>
var loadSaveScript = function () {
         app_id="<?php echo $model['app_id']; ?>";
         svr_id="<?php echo $model['svr_id']; ?>";
         var options=$("#uploadform-pool_group option:selected");
         document.getElementById('shellDiv').style.display="";
         $.post("?r=dcmd-service/load-scripts", { "app_id":app_id,"svr_id":svr_id,"pool_group":options.val() }, function (data, status) {
                                status == "success" ? function () {
                                        var dataO = jQuery.parseJSON(data); 
                                        $('#ShellContent').html(dataO.result);
                                        document.getElementById('shellButton').style.display="";
                                        
                                } () : "";
                        }, "text");
};

var loadOnlineScript = function () {
         app_id="<?php echo $model['app_id']; ?>";
         svr_id="<?php echo $model['svr_id']; ?>";
         var options=$("#uploadform-pool_group option:selected");
         document.getElementById('shellDiv').style.display="";
         $.post("?r=dcmd-service/load-online-scripts", { "app_id":app_id,"svr_id":svr_id,"pool_group":options.val() }, function (data, status) {
                                status == "success" ? function () {
                                        var dataO = jQuery.parseJSON(data);
                                        document.getElementById('shellButton').style.display="none"; 
                                        $('#ShellContent').html(dataO.result);
                                        
                                } () : "";
                        }, "text");
};

function add_submit() {
  var app_id = <?php echo $model['app_id'];?>;
  var svr_id = <?php echo $model['svr_id'];?>;
  var options=$("#uploadform-pool_group option:selected");
  url = "?r=dcmd-service/submit-audit";
  $.get(url,{app_id:app_id,svr_id:svr_id,"pool_group":options.val()}, function(data){
    alert(data);
  });
}

function delete_save() {
  var app_id = <?php echo $model['app_id'];?>;
  var svr_id = <?php echo $model['svr_id'];?>;
  var options=$("#uploadform-pool_group option:selected");
  url = "?r=dcmd-service/delete-draft";
  $.get(url,{app_id:app_id,svr_id:svr_id,"pool_group":options.val()}, function(data){
    alert(data);
  });
  document.getElementById("saveButton").click(); 
}

function load_save() {
  alert("test");
}
</script>
<body onload="setTab()">
<script>
  function setTab()
  {
    var show_div = readCookie('serviceTab') + '-l';
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
