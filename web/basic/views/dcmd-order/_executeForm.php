<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskCmd */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="dcmd-order-execute-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $content ?>

    <div class="form-group" align="center">
        <?= Html::Button('提交' , ['onclick' => 'operate()', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::Button('驳回' , ['onclick' => 'reject()', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
var getVm = function() {
  $('#vmPool').html("");
  var app_name = document.getElementById('dcmdorder-callback').value;
  if(app_name == "") return 0;
  $('#vmPool').load("?r=dcmd-order/get-vms&app_name="+app_name); 
  return 0;
}

var taskTypeSelect = function(){
  tasktype=$('#dcmdtasktemplate-task_cmd_id').val();
  $('#taskTypeArgDiv').load("?r=dcmd-task-template/get-task-type-arg&task_cmd_id="+tasktype);
}

var test = function() {
  var result = true;
  var tables = document.getElementsByTagName("table");
  for(i = 0;i < tables.length;i++){
    var id = tables[i].id
    var val = document.getElementById(id).getAttribute('value');
    var checkbox = document.getElementById(id).getElementsByTagName("input");
    var j=0;
    var l=0;
    while(j<checkbox.length){
      if((checkbox[j].type == "checkbox") && (checkbox[j].checked == true))
      {
        l++
      }
      j++;
    }
    if(l != val){
      alert(id+":请选择正确个数的虚拟机！");
      result = false;
    }
  }
  return result;
}

var operate = function() {
  if(!test()){
    Ids = [];
    var tit = document.title;
    var code_Values = document.getElementsByTagName("input"); 
    for(i = 0;i < code_Values.length;i++){ 
      if(code_Values[i].type == "checkbox") 
      {
       if(code_Values[i].checked == true) 
       {
         Ids.push(code_Values[i].value);
       }
      } 
    }
    data = {Ids : JSON.stringify(Ids), Tit : tit};
    url = "/index.php?r=dcmd-order/handle"; 
    $.post(url,data,function(data){
      data = $.parseJSON(data);
      if (confirm(data.result)) {
        window.location.href='/index.php?r=dcmd-order/index'; 
      }
    });
  }
}

var reject = function(){
  var tit = document.title;
  url = "/index.php?r=dcmd-order/reject";
  $.get(url,{id:tit},function(data){
    data = $.parseJSON(data);
    alert(data.data);
    window.location.href='/index.php?r=dcmd-order/index';
  });

}

var getId = function(){
  var id = document.getElementsByName('active').value;
  alert(id);
}
</script>
