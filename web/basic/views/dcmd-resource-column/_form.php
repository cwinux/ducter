<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-image-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'res_type')->dropDownList($type,['id'=>'res_type','onchange'=>'typechange()'])->label('类型') ?>

    <?= $form->field($model, 'res_table')->textInput(['maxlength' => 64])->label('表名称') ?>

    <?= $form->field($model, 'colum_name')->dropDownList($column,['id'=>'colum_name'])->label('字段名称') ?>

    <?= $form->field($model, 'display_name')->textInput(['maxlength' => 64])->label('显示名称') ?>

    <?= $form->field($model, 'display_order')->textInput(['maxlength' => 2])->label('显示顺序') ?>

    <?= $form->field($model, 'display_list')->dropDownList(array(0=>'否',1=>'是'))->label('是否主页显示') ?>

    <div class="form-image">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script src="./jquery-2.1.1.min.js"></script>
<script>
  $(document).ready(function(){  
    typeload();  
  });
  function typechange(){
    colum_name.options.length=0; 
    var options=$("#res_type option:selected");
    url = "/index.php?r=dcmd-resource-column/get-info";
    $.get(url,{type:options.val()},function(data){
      data = $.parseJSON(data);
      document.getElementById("dcmdrescolumn-res_table").value=data.table;
      var columns = data.column;
        for(var key in columns) {
          objOption=document.createElement("OPTION");
          objOption.text = columns[key];
          objOption.value = columns[key];
          colum_name.options.add(objOption); 
      }
    });
  }

  function typeload(){
    var options=$("#res_type option:selected");
    url = "/index.php?r=dcmd-resource-column/get-info";
    $.get(url,{type:options.val()},function(data){
      data = $.parseJSON(data);
      document.getElementById("dcmdrescolumn-res_table").value=data.table;
      var columns = data.column;
        for(var key in columns) {
          objOption=document.createElement("OPTION");
          objOption.text = columns[key];
          objOption.value = columns[key];
          colum_name.options.add(objOption); 
      }
    });
  }
</script>
