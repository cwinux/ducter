<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '资源报表';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
TABLE {
FONT: 14px 细明体; CURSOR: default
}
TD {
FONT: 14px 细明体; CURSOR: default
}
.search-input {
  -webkit-border-radius:5px;//适配以webkit为核心的浏览器(chrome、safari等)
  -moz-border-radius:5px;//适配firefox浏览器
  -ms-border-radius:5px;//适配IE浏览器
  -o-border-radius:5px;//适配opera浏览器
  border-radius:5px;//适配所有浏览器(需要放在最后面，类似于if..else if..else..)
}
</style>
<div class="dcmd-fault-index">

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
   <label style="font-family:宋体; font-size:13px;">区域:</label>
   <select id="area" style="width:80px;margin-left:7px;">
     <option value="ALL">ALL</option>
     <?php 
       foreach($dcInfo as $k=>$v) {
     ?>
     <option>
     <?php
         echo $v;
       }
     ?>
     </option>
   </select>
   <label style="font-family:宋体; font-size:13px;margin-left:20px;">类别:</label>
   <select id="type" style="width:80px;margin-left:7px;">
     <option value="ALL">ALL</option>
     <?php 
       foreach($type as $k=>$v) {
     ?>
     <option>
     <?php
         echo $v;
       }
     ?>
     </option>
   </select>
   <label style="font-family:宋体; font-size:13px;margin-left:20px;">运营商:</label>
   <select id="operate" style="width:80px;margin-left:7px;">
     <option value="ALL">ALL</option>
     <?php 
       foreach($operate as $k=>$v) {
     ?>
     <option>
     <?php
         echo $v;
       }
     ?>
     </option>
   </select>
   <label style="font-family:宋体; font-size:13px;margin-left:20px;">集群名称:</label>
   <select id="app" style="width:80px;margin-left:7px;">
     <option value="ALL">ALL</option>
     <?php 
       foreach($app as $k=>$v) {
     ?>
     <option>
     <?php
         echo $v;
       }
     ?>
     </option>
   </select>
   <button style="font-family:宋体; font-size:13px; background:#2894FF; color: white; border-radius:5px; margin-left:7px; height:25px" onclick="search_app()">Search</button>
   <p></p>
   <div id="info">
   <?php 
     echo $model;
   ?>
   </div>
</div>
<div>
    <button type="button" onclick="method5(table)">导出Excel</button>
</div>
</form>
<script language="JavaScript" type="text/javascript">
  function search_app() {
    var area = document.getElementById('area').value;
    var type = document.getElementById('type').value;
    var operate = document.getElementById('operate').value;
    var app = document.getElementById('app').value;
    var url = "/index.php?r=dcmd-resource-report/get-app";
    $.get(url, {"area":area, "type":type, "operate":operate, "app":app}, function(data, status) {
       status == "success" ? function () {
         $('#info').html(data);
       } () : "";
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
  function method5(tableid) {
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
          tableToExcel(tableid)
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
          if (!table.nodeType) table = document.getElementById(table)
          var ctx = {worksheet: name || 'Worksheet', table: "<table border='1' cellspacing='0' cellpadding='0'>"+table.innerHTML+"</table>"}
          var link = document.createElement("a");
          link.download = "资源报表.xls";
          link.href = uri + base64(format(template, ctx));
          link.click(); 
         // window.location.href = uri + base64(format(template, ctx))
      }
  })()
</script>

