<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '故障报告';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
TABLE {
FONT: 12px 细明体; CURSOR: default
}
TD {
FONT: 12px 细明体; CURSOR: default
}

ol {
	list-style: none outside none;
	margin: 0;
	padding: 0;
}

.ui-step {
	color: #b7b7b7;
	padding: 0 60px;
	margin-bottom: 35px;
	position: relative;
}
.ui-step:after {
	display: block;
	content: "";
	height: 0;
	font-size: 0;
	clear: both;
	overflow: hidden;
	visibility: hidden;
}
.ui-step li {
	float: left;
	position: relative;
}
.ui-step .step-end {
	width: 120px;
	position: absolute;
	top: 0;
	right: -60px;
}
.ui-step-line {
	height: 5px;
	background-color: #e0e0e0;
	box-shadow: inset 0 1px 1px rgba(0,0,0,.2);
	margin-top: 15px;
}
.step-end .ui-step-line { display: none; }
.ui-step-cont {
	width: 120px;
	position: absolute;
	top: 0;
	left: -15px;
	text-align: center;
}
.ui-step-cont-number {
	display: inline-block;
*zoom:1;
	position: absolute;
	left: 0;
	top: 0;
	width: 30px;
	height: 30px;
	line-height: 30px;
	color: #fff;
	background: url(ui-step_cover_30x30.png) center no-repeat\9;
	border-radius: 50%;
	border-left: 2px solid #fff;
	border-right: 2px solid #fff;
	border: 2px solid rgba(224,224,224,1);
	font-family: tahoma;
	font-weight: bold;
	font-size: 16px;
	background-color: #b9b9b9;
	box-shadow: inset 1px 1px 2px rgba(0,0,0,.2);
}
.ui-step-cont-text {
	position: relative;
	top: 34px;
	left: -42px;
	font-size: 12px;
}
/** 步骤数定义 **/
.ui-step-3 li { width: 50%; }
.ui-step-4 li { width: 33.3%; }
.ui-step-5 li { width: 25%; }
.ui-step-6 li { width: 20%; }
/** The default style (默认风格) **/
/* Done */
.step-done .ui-step-cont-number { background-color: #85e085; }
.step-done .ui-step-cont-text { color: #85e085; }
.step-done .ui-step-line { background-color: #6c6; }
/* Active */
.step-active .ui-step-cont-number { background-color: #3c3; }
.step-active .ui-step-cont-text {
	color: #3c3;
	font-weight: bold;
}
.step-active .ui-step-line { background-color: #e0e0e0; }
/** Yellow **/
/* Done */
.ui-step-yellow .step-done .ui-step-cont-number { background-color: #ffc966; }
.ui-step-yellow .step-done .ui-step-cont-text { color: #ffc966; }
.ui-step-yellow .step-done .ui-step-line { background-color: #ffcc33; }
/* Active */
.ui-step-yellow .step-active .ui-step-cont-number { background-color: orange; }
.ui-step-yellow .step-active .ui-step-cont-text { color: orange; }
.ui-step-yellow .step-active .ui-step-line { background-color: #e0e0e0; }
/** Blue **/
/* Done */
.ui-step-blue .step-done .ui-step-cont-number { background-color: #69f; }
.ui-step-blue .step-done .ui-step-cont-text { color: #69f; }
.ui-step-blue .step-done .ui-step-line { background-color: #4c99e6; }
/* Active */
.ui-step-blue .step-active .ui-step-cont-number { background-color: #06c; }
.ui-step-blue .step-active .ui-step-cont-text { color: #06c; }
.ui-step-blue .step-active .ui-step-line { background-color: #e0e0e0; }
/** Red **/
/* Done */
.ui-step-red .step-done .ui-step-cont-number { background-color: #f99; }
.ui-step-red .step-done .ui-step-cont-text { color: #f99; }
.ui-step-red .step-done .ui-step-line { background-color: #fc9c9c; }
/* Active */
.ui-step-red .step-active .ui-step-cont-number { background-color: #f66; }
.ui-step-red .step-active .ui-step-cont-text { color: #f66; }
.ui-step-red .step-active .ui-step-line { background-color: #e0e0e0; }
</style>
<form id="w0" action="/index.php?r=dcmd-fault/report-delete-all" method="post">
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
   <label style="font-family:宋体; font-size:13px;">IP:</label>
   <input type="text" id="title" style="height:20px;font-family:宋体;"></input>
   <label style="font-family:宋体; font-size:13px;">进度:</label>
   <select id="process" style="width:80px; margin-left:7px;">
     <option value="ALL">ALL</option>
     <option value="发现故障">发现故障</option>
     <option value="已报修">已报修</option>
     <option value="完成">完成</option>
   </select>
   <input type="button" style="font-family:宋体; font-size:13px; background:#2894FF; color: white; border-radius:5px; margin-left:7px; height:25px" value="Search" onclick="search_fault();"/>
   <p></p>
   <div id="info">
   <?php
     echo $msg;
   ?>
   </div>
    <p>
        <input type="button" class="btn btn-sucess" ="display:none" style="background:#00BB00;color:white" value="添加故障" onclick="addFault();"/>
        <input type="button" class="btn btn-sucess" ="display:none" style="background:#00BB00;color:white" value="导出Excel" onclick="method5(table);"/>
        <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
    </p>
</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h5 class="modal-title" value="">故障信息</h5>
                </div>
                <div class="modal-body" id="addItems">                
                </div>               
                <div style="width:70%; margin:0 auto;">
                        <ol class="ui-step ui-step-3">
                                <li id ="step-1" class="step-start step-done">
                                        <div class="ui-step-line"></div>
                                        <div class="ui-step-cont">
                                                <span class="ui-step-cont-number">1</span>
                                                <span class="ui-step-cont-text">发现故障</span>
                                        </div>
                                </li>
                                <li id="step-2" class="step-start">
                                        <div class="ui-step-line"></div>
                                        <div class="ui-step-cont">
                                                <span class="ui-step-cont-number" onclick="fixed()">2</span>
                                                <span class="ui-step-cont-text">报修</span>
                                        </div>
                                </li>
                                <li id="step-3" class="step-end">
                                        <div class="ui-step-line"></div>
                                        <div class="ui-step-cont">
                                                <span class="ui-step-cont-number" onclick="finished()">3</span>
                                                <span class="ui-step-cont-text">完成</span>
                                        </div>
                                </li>
                        </ol>
                </div> 
                <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" onclick="fillInfo()">自动填充</button>
                        <button type="button" class="btn btn-primary" onclick="addInfo()">提交</button>
                </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h5 class="modal-title" id="myModalLabel" value="">修改故障信息</h5>
		</div>
		<div class="modal-body" id="faltItems">
		       
		</div>
                <div style="width:70%; margin:0 auto;">
                        <ol class="ui-step ui-step-3">
                                <li id ="step1" class="step-start step-done">
                                        <div class="ui-step-line"></div>
                                        <div class="ui-step-cont">
                                                <span class="ui-step-cont-number">1</span>
                                                <span class="ui-step-cont-text">发现故障</span>
                                        </div>
                                </li>
                                <li id="step2" class="step-start">
                                        <div class="ui-step-line"></div>
                                        <div class="ui-step-cont">
                                                <span class="ui-step-cont-number" onclick="fixed1()">2</span>
                                                <span class="ui-step-cont-text">报修</span>
                                        </div>
                                </li>
                                <li id="step3" class="step-end">
                                        <div class="ui-step-line"></div>
                                        <div class="ui-step-cont">
                                                <span class="ui-step-cont-number" onclick="finished1()">3</span>
                                                <span class="ui-step-cont-text">完成</span>
                                        </div>
                                </li>
                        </ol>
                </div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			<button type="button" class="btn btn-primary" onclick="submits()">提交更改</button>
		</div>
    </div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>
</form>
<script type="text/javascript">
  var rowCount = 0;
  var colCount = 2;
  function addRow(){
    rowCount++;
    var rowTemplate = '<tr class="tr_add"><td></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1" ><input type="text"/></td><td class="cl1" ><input type="text"/></td><td class="cl1" ><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td><td class="cl1"><input type="text"/></td></tr>';
    $('#table tbody').append(rowTemplate);
  }
  function saveRow(){
    url="/index.php?r=dcmd-fault/add-fault";
    $("#table tr").each(function(){
      var tdArr = $(this).children();
      if($(this).hasClass("tr_add")){
        var fault_title = tdArr.eq(1).find("input").val();
        var fault_area = tdArr.eq(2).find("input").val();
        var custom_type = tdArr.eq(3).find("input").val();
        var custom_name = tdArr.eq(4).find("input").val();
        var fault_date = tdArr.eq(5).find("input").val();
        var case_num = tdArr.eq(6).find("input").val();
        var fault_level = tdArr.eq(7).find("input").val();
        var find_way = tdArr.eq(8).find("input").val();
        var fault_show = tdArr.eq(9).find("input").val();
        var sustained_time = tdArr.eq(10).find("input").val();
        var start_time = tdArr.eq(11).find("input").val();
        var discover_time = tdArr.eq(12).find("input").val();
        var handle_time = tdArr.eq(13).find("input").val();
        var recover_time = tdArr.eq(14).find("input").val();
        var reason = tdArr.eq(15).find("input").val();
        var reason_type = tdArr.eq(16).find("input").val();
        var response_model = tdArr.eq(17).find("input").val();
        var response_person = tdArr.eq(18).find("input").val();
        var handle_process = tdArr.eq(19).find("input").val();
        var improve = tdArr.eq(20).find("input").val();
        var fault_status = "发现故障";
        $.get(url,{fault_title:fault_title,fault_area:fault_area,custom_type:custom_type,custom_name:custom_name,fault_date:fault_date,case_num:case_num,fault_level:fault_level,find_way:find_way,fault_show:fault_show,sustained_time:sustained_time,start_time:start_time,discover_time:discover_time,handle_time:handle_time,recover_time:recover_time,reason:reason,reason_type:reason_type,response_model:response_model,response_person:response_person,handle_process:handle_process,improve:improve,fault_status:fault_status},function(data){
          data = $.parseJSON(data);
          alert(data.data);
          if(data.data=="sucess") {
            window.location.reload();
          }
        });
      }
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
        alert("请不要使用IE浏览器！");
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
          link.download = "故障报告.xls";
          link.href = uri + base64(format(template, ctx));
          link.click(); 
         // window.location.href = uri + base64(format(template, ctx))
      }
  })()

  function test(x) {
    var id = $(x).attr("value");
    document.getElementById('myModalLabel').value=id;
    url = "/index.php?r=dcmd-fault/get-fault";
    $.get(url, {fault_id:id}, function(data){ 
      var tab = data.split("</table>")[0];
      $('#faltItems').html(tab);
      $('#myModal').modal('show');
      if(data.split("</table>")[1]=="已报修") document.getElementById("step2").className = "step-start step-done";
      else if(data.split("</table>")[1]=="完成") {
        document.getElementById("step2").className = "step-start step-done";
        document.getElementById("step3").className = "step-end step-done";
      }
      else {
        document.getElementById("step2").className = "step-start";
        document.getElementById("step3").className = "step-end";
      }
    });
  }

  function submits() {
    var fault_id = document.getElementById('myModalLabel').value;
    var fault_title = $("input[name='fault_title']").val();
    var fault_area = $("input[name='fault_area']").val();
    var custom_type = $("input[name='custom_type']").val();
    var custom_name = $("input[name='custom_name']").val();
    var fault_date = $("input[name='fault_date']").val();
    var case_num = $("input[name='case_num']").val();
    var fault_level = $("input[name='fault_level']").val();
    var find_way = $("input[name='find_way']").val();
    var fault_show = $("input[name='fault_show']").val();
    var sustained_time = $("input[name='sustained_time']").val();
    var start_time = $("input[name='start_time']").val();
    var discover_time = $("input[name='discover_time']").val();
    var handle_time = $("input[name='handle_time']").val();
    var recover_time = $("input[name='recover_time']").val();
    var reason = $("input[name='reason']").val();
    var reason_type = $("input[name='reason_type']").val();
    var response_model = $("input[name='response_model']").val();
    var response_person = $("input[name='response_person']").val();
    var handle_process = $("input[name='handle_process']").val();
    var improve = $("input[name='improve']").val();
    if(document.getElementById("step3").className=="step-end step-done") var fault_status = "完成";
    else if(document.getElementById("step3").className != "step-end step-done" && (document.getElementById("step2").className=="step-start step-done")) var fault_status = "已报修";
    else var fault_status = "发现故障";
    url = "/index.php?r=dcmd-fault/update-fault";
    $.get(url,{fault_id:fault_id,fault_title:fault_title,fault_area:fault_area,custom_type:custom_type,custom_name:custom_name,fault_date:fault_date,case_num:case_num,fault_level:fault_level,find_way:find_way,fault_show:fault_show,sustained_time:sustained_time,start_time:start_time,discover_time:discover_time,handle_time:handle_time,recover_time:recover_time,reason:reason,reason_type:reason_type,response_model:response_model,response_person:response_person,handle_process:handle_process,improve:improve,fault_status:fault_status},function(data){
      data = $.parseJSON(data);
      alert(data.data);
      if(data.data=="sucess") {
        window.location.reload();
      }
    });
  }

  function addFault() {
    var trs = '<table id="table" class="table table-striped table-bordered"><tbody><tr><th>故障类型</th><td><select id="fault_type"><option value="重启">重启</option><option value="宕机">宕机</option></select></td><th>故障机器</th><td><input type="text" name="fault_host" value=""/></td></tr><tr><th>故障标题</th><td><input type="text" name="fault_title"  value=""/></td><th>发生区域</th><td><input type="text" name="fault_area" value=""/></td></tr><tr><th>影响客户类型</th><td><input type="text" name="custom_type"  value=""/></td><th>影响客户名称</th><td><input type="text" name="custom_name" value=""/></td></tr><tr><th>发生日期</th><td><input type="text" name="fault_date"  value=""/></td><th>Case编号</th><td><input type="text" name="case_num" value=""/></td></tr><tr><th>故障级别</th><td><input type="text" name="fault_level"  value=""/></td><th>故障发现途径</th><td><input type="text" name="find_way" value=""/></td></tr><tr><th>故障现象</th><td><input type="text" name="fault_show"  value=""/></td><th>影响时长</th><td><input type="text" name="sustained_time" value=""/></td></tr><tr><th>故障开始时间</th><td><input type="text" name="start_time"  value=""/></td><th>故障发现时间点</th><td><input type="text" name="discover_time" value=""/></td></tr><tr><th>开始处理时间</th><td><input type="text" name="handle_time"  value=""/></td><th>服务恢复时间</th><td><input type="text" name="recover_time" value=""/></td></tr><tr><th>原因</th><td><input type="text" name="reason"  value=""/></td><th>原因分类</th><td><input type="text" name="reason_type" value=""/></td></tr><tr><th>责任部门</th><td><input type="text" name="response_model"  value=""/></td><th>责任人</th><td><input type="text" name="response_person" value=""/></td></tr><tr><th>处理过程</th><td><input type="text" name="handle_process"  value=""/></td><th>改进措施</th><td><input type="text" name="improve" value=""/></td></tr></tbody></table>';
    $('#addItems').html(trs);
    $('#addModal').modal('show');
    document.getElementById("step-2").className = "step-start";
    document.getElementById("step-3").className = "step-end";
  }

  function addInfo() {
    var fault_title = $("input[name='fault_title']").val();
    var fault_area = $("input[name='fault_area']").val();
    var custom_type = $("input[name='custom_type']").val();
    var custom_name = $("input[name='custom_name']").val();
    var fault_date = $("input[name='fault_date']").val();
    var case_num = $("input[name='case_num']").val();
    var fault_level = $("input[name='fault_level']").val();
    var find_way = $("input[name='find_way']").val();
    var fault_show = $("input[name='fault_show']").val();
    var sustained_time = $("input[name='sustained_time']").val();
    var start_time = $("input[name='start_time']").val();
    var discover_time = $("input[name='discover_time']").val();
    var handle_time = $("input[name='handle_time']").val();
    var recover_time = $("input[name='recover_time']").val();
    var reason = $("input[name='reason']").val();
    var reason_type = $("input[name='reason_type']").val();
    var response_model = $("input[name='response_model']").val();
    var response_person = $("input[name='response_person']").val();
    var handle_process = $("input[name='handle_process']").val();
    var improve = $("input[name='improve']").val();
    if(document.getElementById("step-3").className=="step-end step-done") var fault_status = "完成";
    else if(document.getElementById("step-3").className != "step-end step-done" && (document.getElementById("step-2").className=="step-start step-done")) var fault_status = "已报修";
    else var fault_status = "发现故障";
    url = "/index.php?r=dcmd-fault/add-fault";
    $.get(url,{fault_title:fault_title,fault_area:fault_area,custom_type:custom_type,custom_name:custom_name,fault_date:fault_date,case_num:case_num,fault_level:fault_level,find_way:find_way,fault_show:fault_show,sustained_time:sustained_time,start_time:start_time,discover_time:discover_time,handle_time:handle_time,recover_time:recover_time,reason:reason,reason_type:reason_type,response_model:response_model,response_person:response_person,handle_process:handle_process,improve:improve,fault_status:fault_status},function(data){
      data = $.parseJSON(data);
      alert(data.data);
      if(data.data=="sucess") {
        window.location.reload();
      }
    });
  }

  function fillInfo() {
    var fault_host = $("input[name='fault_host']").val();
    var obj = document.getElementById("fault_type");
    var index = obj.selectedIndex;
    var fault_type = obj.options[index].value;
    document.getElementsByName("fault_title")[0].value = fault_host + fault_type;
    document.getElementsByName("response_model")[0].value = "云平台";
    document.getElementsByName("response_person")[0].value = "刁文波";
    document.getElementsByName("fault_show")[0].value = fault_host + fault_type;
    document.getElementsByName("custom_type")[0].value = "生态客户";
    document.getElementsByName("find_way")[0].value = "监控报警";
    document.getElementsByName("improve")[0].value = "暂无";
    url = "/index.php?r=dcmd-fault/host-info";
    $.get(url,{host_ip:fault_host}, function(data) {
      if(data) {
        data = $.parseJSON(data);
        document.getElementsByName("custom_name")[0].value = data.custom;
        document.getElementsByName("fault_area")[0].value = data.location;
      }
    });
  }

  function fixed() {
    if(document.getElementById("step-2").className == "step-start") document.getElementById("step-2").className = "step-start step-done";
    else {
      document.getElementById("step-2").className = "step-start";
      document.getElementById("step-3").className = "step-end";
    }
  }

  function finished() {
    document.getElementById("step-2").className = "step-start step-done";
    document.getElementById("step-3").className = "step-end step-done";
  }

  function fixed1() {
    document.getElementById("step2").className = "step-start step-done";
  }

  function finished1() {
    document.getElementById("step2").className = "step-start step-done";
    document.getElementById("step3").className = "step-end step-done";
  }

  function search_fault(){
    var title = document.getElementById('title').value;
    var process = document.getElementById('process').value;
    var url = "/index.php?r=dcmd-fault/fault-search";
    $.get(url, {"title":title, "process":process}, function(data, status) {
       status == "success" ? function () {
         $('#info').html(data);
       } () : "";
   });
  }
</script>
