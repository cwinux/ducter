<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdAppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '产品列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/ducter/index.php?r=dcmd-app/delete-all" method="post">
<div class="dcmd-app-index">

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
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'app_name', 'label'=>'产品名称','content' => function($model, $key, $index, $column) { return Html::a($model['app_name'], Url::to(['view', 'id'=>$model['app_id']]));}),
            ['label'=>'机房',  'attribute' => 'dc',  'value' => 'dcmdDcInfo.dc'],
            array('attribute'=>'ram_total', 'label'=>'内存总量'),
            array('attribute'=>'ram_alocate', 'label'=>'内存分配量'),
            array('attribute'=>'ram_used', 'label'=>'内存使用量'),
            array('attribute'=>'server_num', 'label'=>'服务器数量'),
          //  array('attribute'=>'idc_id', 'label'=>'机房',  'enableSorting'=>false,'content'=>function($model, $key, $index, $column) { return $model->getIdc($model->idc_id);}),
//            array('attribute'=>'dcmd_dc_info.dc', 'label'=>'机房',  'enableSorting'=>false,'content'=>function($model, $key, $index, $column) { return $model->getIdc($model->idc_id);}),
            array('attribute'=>'state', 'label'=>'状态', 'enableSorting'=>false,'content'=>function($model, $key, $index, $column) { return $model->getState($model->state);}),

            ['class' => 'yii\grid\ActionColumn',"visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
//            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-service/delete', 'id'=>$model['svr_id'], 'app_id'=>$model['app_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false ],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success', Yii::$app->user->getIdentity()->admin == 1 ? "" : "style"=>"display:none"]) ?>
    &nbsp;&nbsp;
    <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
<div>
<!--    <button type="button" onclick="method2()">导出集群所有主机</button>
    <button type="button" onclick="method3()">导出集群VM</button>  --!>
</div>
</form>
<script src="./jquery-2.1.1.min.js"></script>
<script>
    function method2() {
      url = "/index.php?r=dcmd-app/get-hosts";
      var app_id="-1";
      msg = "<table border='1' cellspacing='0' cellpadding='0'><tr><th>Ip</th><th>主机名称</th><th>带外Ip</th><th>公网Ip</th></tr>";
      $(":checkbox:checked").each(function(i,v){
        app_id = app_id + "," + $(this).val();
      });
      $.get(url,{app_id:app_id},function(data){
        data = $.parseJSON(data);
        for(var i=0;i<data.length;i++)
        {
          ip = data[i]["ip"];
          host_name = data[i]["host"];
          bend_ip = data[i]["bend_ip"];
          public_ip = data[i]["public_ip"];
          tr = "<tr><td>"+ip+"</td><td>"+host_name+"</td><td>"+bend_ip+"</td><td>"+public_ip+"</td><td></td></tr>";
          msg = msg + tr;
        }
        msg = msg + "</table>";
        method5(msg,"hosts.xlsx");
        });
    }
    function method3() {
      url = "/index.php?r=dcmd-app/get-vms";
      var app_id="-1";
      msg = "<table border='1' cellspacing='0' cellpadding='0'><tr><th>集群</th><th>是否外借</th><th>型号</th><th>宿主机</th><th>外网ip</th><th>内网ip</th><th>uuid</th><th>nat</th><th>os</th><th>flavor</th><th>VM使用状态</th></tr>";
      $(":checkbox:checked").each(function(i,v){
        app_id = app_id + "," + $(this).val();
      });
      $.get(url,{app_id:app_id},function(data){
        data = $.parseJSON(data);
        var state = ["未使用","使用中","可回收"]
        for(var i=0;i<data.length;i++)
        {
          app = data[i]["app_name"];
          index = app.indexOf('_');
          app_name = app.substr(index+1)+'_'+app.substr(0,index);
          tr = "<tr><td>"+app_name+"</td><td>否</td><td>KVM</td><td>"+data[i]["host_ip"]+"</td><td></td><td>"+data[i]["vm_ip"]+"</td><td>" +data[i]["vm_uuid"]+ "</td><td></td><td>"+data[i]["os"]+"</td><td>"+data[i]["flavor_name"]+"</td><td>"+state[data[i]["state"]]+"</td></tr>";
          msg = msg + tr;
        }
        msg = msg + "</table>";
        method4(msg,"cluster_vms.xlsx");
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
    function method5(tableid,name) {
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
            tableToExcel(tableid,name)
        }
    }
    function Cleanup() {
        window.clearInterval(idTmr);
        CollectGarbage();
    }

    var tableToExcel = (function() {
        var uri = 'data:application/vnd.ms-excel;base64,',
        template = '<html><head><meta charset="UTF-8"></head><body><table>{table}</table></body></html>',
        base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) },
        format = function(s, c) {
            return s.replace(/{(\w+)}/g,
            function(m, p) { return c[p]; }) }
                return function(table, name) {
                      var  content = "";
                      var ctx = {worksheet: name || 'Worksheet', table: table}
                      var link = document.createElement("a");
                      link.download = "hosts.xls";
                      link.href = uri + base64(format(template, ctx));
                      link.click();
        }
    })()
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
                          link.download = "cluster_vms.xls";
                          link.href = uri + base64(format(template, ctx));
                          link.click();
            }
        })()
</script>
