<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */

$this->title = "资源详情";
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
<form id="w0" action="/ducter/index.php?r=dcmd-resource/resource-submit" method="post">
<div>
  <select name="sel" id="sel" onFocus="Myselect()" onChange="test()" style="width:130px">
    <option selected="selected">--请选择类型--</option>
  </select>
</div>
<br>
<div id="resource-view">
</div>
<p>
  <input class="btn btn-success" type="button" value="添加" onclick="show_modal()"></input>
  <input class="btn btn-success" type="button" onclick="test1()" value="删除"></input>
</p>
<!-- 模态框（Modal） -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h5 class="modal-title" value="">添加资源</h5>
                </div>
     <div class="modal-body" id="addItems">
        <div style="width:90%; margin:0 auto;">
           <div style="height:450px;margin:20px;margin-left:10px;">
            <!-- option必须带有 value 的值 -->
            <select id="type_select" data-edit-select="1" onFocus="create()" onChange="create_form()" style="width:130px">
              <option selected="selected">--请选择类型--</option>
            </select>
            <br>
            <div id="add_form" style="height:450px;margin-top:10px;overflow:auto">
            </div>
          </div>
        </div>
        <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
        </div>
      </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>
<script>
function test1() {
  url = "/ducter/index.php?r=dcmd-resource/resource-delete";
  var chk_value =[];
  var options=$("#sel option:selected");
  $('input[name="selection[]"]:checked').each(function(){
    chk_value.push($(this).val());
  });
  if (chk_value.length==0) {
    alert('未选择!');
  }else {
  $.post(url,{selected:chk_value,type:options.val()}, function(data,status){
    if (data == "success") {
      alert("删除成功!");
      test();
    }
  });
  }
}
</script>
<script type="text/javascript">
var resType = <?php echo $resType;?>;
//var counts;
counts=0;
arr = new Array("Mysql","Cbase","LVS","Redis");	
counts=resType.length;
function Myselect(){
  sel.options = 0;
  var i;
  i = 0;
  for (var key in resType) {
    sel.options[i] = new Option(resType[key],key);
    i++;
  }
}

function test() {
  url = "/index.php?r=dcmd-resource/get-resource";
  var options=$("#sel option:selected");
  $.get(url,{type:options.val()},function(data){
    $('#resource-view').html(data);
  });
}

function show_modal(){
  $('#addModal').modal('show');
}

function create(){
  sel.options = 0;
  var i;
  i = 0;
  for (var key in resType) {
    type_select.options[i] = new Option(resType[key],key);
    i++;
  }
}

function create_form(){
  url = "/index.php?r=dcmd-resource/add-resource";
  var options=$("#type_select option:selected");
  $.get(url,{type:options.val()},function(data){
    $('#add_form').html(data);
  });
}

</script>

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
