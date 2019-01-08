<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "私有云管理";
$this->params['breadcrumbs'][] = ['label' => 'VM信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
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
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn', 'checkboxOptions'=>['class'=>'imagezz']],
            array('attribute'=>'app_name', 'label' => '集群名称', 'content'=> function($model, $key, $index, $column) { return Html::a($model->getClusterByIP($model['app_name']), Url::to(['dcmd-app/view', 'id'=>$model->getNodeIDByIP($model['app_name'])]));}),
            //array('attribute'=>'vm_sn', 'label'=>'虚拟机SN', 'content'=> function($model, $key, $index, $column) { return Html::a($model['vm_sn'], Url::to(['dcmd-private/view', 'id'=>$model['id']]));}),
            array('attribute'=>'host_ip', 'label' => '宿主机IP', 'content'=> function($model, $key, $index, $column) { return Html::a($model['host_ip'], Url::to(['dcmd-node/view', 'id'=>$model->getNodeID($model['host_ip'])]));}),
            array('attribute'=>'vm_ip', 'label'=>'VM IP', 'content'=> function($model, $key, $index, $column) { return Html::a($model['vm_ip'], Url::to(['dcmd-private/view', 'id'=>$model['id']]));}),
            array('attribute'=>'state', 'label'=>'VM使用状态', 'enableSorting'=>false, 'filter'=>["0"=>"未使用","1"=>"使用中","2"=>"可回收"], 'content'=> function($model, $key, $index, $column) { return $model->getState($model['state']);}),
            array('attribute'=>'os', 'label'=>'系统', 'enableSorting'=>false),
            array('attribute'=>'flavor_name', 'label'=>'规格', 'enableSorting'=>false),
            array('attribute'=>'business', 'label'=>'业务名称', 'enableSorting'=>false),
            array('attribute'=>'module', 'label'=>'模块名称', 'enableSorting'=>false),
            array('attribute'=>'contacts', 'label'=>'使用人', 'enableSorting'=>false),
  //          array('attribute'=>'module', 'label'=>'运行状态', 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn','template'=>'{view}', 'urlCreator'=>function($action, $model, $key, $index) {if ("view" == $action) return Url::to(['dcmd-private/view','id'=>$model['id']]);else return Url::to(['dcmd-private/update','id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
              
          //  ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
       // ],
    ]); ?>
    <p>
        <?= Html::submitButton('使用中', ['class' =>'btn btn-success', 'onClick'=>"used()", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
        <?= Html::submitButton('未使用', ['class' =>'btn btn-success', 'onClick'=>"unuse()", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
        <?= Html::submitButton('可回收', ['class' =>'btn btn-success', 'onClick'=>"reuse()", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
</form>
<script language="JavaScript" type="text/javascript">
    function method3() {
      url = "/index.php?r=dcmd-private/get-vms";
      var id="-1";
      msg = "<table border='1' cellspacing='0' cellpadding='0'><tr><th>宿主机SN</th><th>SN</th><th>品牌</th><th>型号</th><th>CPU</th><th>内存</th><th>硬盘</th><th>外网IP(多个用半角逗号隔开)</th><th>内网IP(多个用半角逗号隔开)</th><th>负责人</th><th>其他联系人</th><th>业务模块</th><th>模块名称</th><th>操作系统</th><th>服务树ID</th><th>成本状态</th><th>监控状态</th></tr>";
      $(":checkbox:checked").each(function(i,v){
        id = id + "," + $(this).val();
      });
      $.get(url,{id:id},function(data){
        data = $.parseJSON(data);
        for(var i=0;i<data.length;i++)
        {
          tr = "<tr><td>"+data[i]["host_sn"]+"</td><td>"+data[i]["vm_uuid"]+"</td><td>KVM</td><td>KVM</td><td>"+data[i]["cpu"]+"</td><td>"+data[i]["memory"]+"</td><td>"+data[i]
["disk"]+"</td><td></td><td>"+data[i]["vm_ip"]+"</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
          msg = msg + tr;
        }
        msg = msg + "</table>";
        method4(msg,"vms.xlsx");
        });
    }
        var idTmr;
        function  getExplorer() {
            var explorer = window.navigator.userAgent ;
            //ie
            if (explorer.indexOf("MSIE") >= 0) {
                return 'ie';
            }
            //firefox
            else if (explorer.indexOf("Firefox") >= 0) {
                return 'Firefox';
            }
            //Chrome
            else if(explorer.indexOf("Chrome") >= 0){
                return 'Chrome';
            }
            //Opera
            else if(explorer.indexOf("Opera") >= 0){
                return 'Opera';
            }
            //Safari
            else if(explorer.indexOf("Safari") >= 0){
                return 'Safari';
            }
        }
    function method4(tableid,name) {
            if(getExplorer()=='ie')
            {
                var curTbl = document.getElementById(tableid);
                var oXL = new ActiveXObject("Excel.Application");
                var oWB = oXL.Workbooks.Add();
                var xlsheet = oWB.Worksheets(1);
                var sel = document.body.createTextRange();
                sel.moveToElementText(curTbl);
                sel.select();
                sel.execCommand("Copy");
                xlsheet.Paste();
                oXL.Visible = true;

                try {
                    var fname = oXL.Application.GetSaveAsFilename("Excel.xls", "Excel Spreadsheets (*.xls), *.xls");
                } catch (e) {
                    print("Nested catch caught " + e);
                } finally {
                    oWB.SaveAs(fname);
                    oWB.Close(savechanges = false);
                    oXL.Quit();
                    oXL = null;
                    idTmr = window.setInterval("Cleanup();", 1);
                }

            }
            else
            {
                tableToExcel1(tableid,name)
            }
        }     
    var tableToExcel1 = (function() {
            var uri = 'data:application/vnd.ms-excel;base64,',
            template = '<html><head><meta charset="UTF-8"></head><body><table>{table}</table></body></html>',
            base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) },
            format = function(s, c) {
                return s.replace(/{(\w+)}/g,
                function(m, p) { return c[p]; }) }
                    return function(table, name) {
                      //  if (!table.nodeType) table = document.getElementById(table)
                          var  content = "";
                          var ctx = {worksheet: name || 'Worksheet', table: table}
                          var link = document.createElement("a");
                          link.download = "vm.xls";
                          link.href = uri + base64(format(template, ctx));
                          link.click();
            }
        })()
  function clickDownload(aLink)  
  { 
      $.ajaxSetup({  
        async : false //取消异步  
      }); 
      url = "/index.php?r=dcmd-private/get-vms";
      var id="-1";
      $(":checkbox:checked").each(function(i,v){
        id = id + "," + $(this).val();
      });
      $.get(url,{id:id},function(data){
        data = $.parseJSON(data);
        str = "宿主机SN,SN,品牌,型号,CPU,内存,硬盘,外网IP(多个用半角逗号隔开),内网IP(多个用半角逗号隔开),负责人,其他联系人,业务模块,模块名称,操作系统,服务树ID,成本状态,监控状态\n";
        var tr = "";
        for(var i=0;i<data.length;i++)
        {          tr = tr + data[i]["host_sn"]+ "," + data[i]["vm_uuid"]+",KVM,KVM,"+data[i]["cpu"]+","+data[i]["memory"]+","+data[i]["disk"]+", ,"+data[i]["vm_ip"]+", , , , , , , ,\n";
        }
         str = str + tr;
         str =  encodeURIComponent(str);
         aLink.href = "data:text/csv;charset=utf-8,\ufeff"+str;
      });
  } 
</script>
<div>
    <button type="button" onclick="method3()">导出Excel</button>
    <a id="download" onclick="clickDownload(this)" download="downlaod.csv" href="#">导出csv</a>
</div>
