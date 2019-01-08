<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdApp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-app-form">

    <?php $form = ActiveForm::begin(['options' => ['name' => 'app_form'],]); ?>

    <?= $form->field($model, 'app_name')->textInput(['maxlength' => 128, $model->app_name ? "disabled" : "" =>true])->label('产品名称') ?>

    <?= $form->field($model, 'idc_id')->dropDownList($country,['id'=>'country','prompt'=>"",'onchange'=>'counchange()'])->label('国家') ?>

    <?= $form->field($model, 'idc_id')->dropDownList([],['id'=>'area','prompt'=>"", 'onchange'=>'areachange()'])->label('地区') ?>

    <?= $form->field($model, 'idc_id')->dropDownList([],['id'=>'dc'])->label('机房') ?>

    <?= $form->field($model, 'state')->dropDownList([1=>"在线",0=>"下线"])->label('状态') ?>

    <?= $form->field($model, 'app_type')->dropDownList(["生产"=>"生产","测试"=>"测试"])->label('用户名') ?>

    <?= $form->field($model, 'user')->textInput(['maxlength' => 64])->label('用户名') ?>

    <?= $form->field($model, 'passwd')->textInput(['maxlength' => 64])->label('密码') ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6, 'maxlength' => 256])->label('说明') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
  function loadfun(){
    counchange();
    areachange();
  }
  function counchange(){
    app_form.area.options.length=0; 
    var options=$("#country option:selected");
    url = "/index.php?r=dcmd-cbase-app/getarea";
    $.get(url,{country:options.val()},function(data){
      data = $.parseJSON(data);
      for (i=0;i<data.length;i++){
        objOption=document.createElement("OPTION");
        objOption.text = data[i];
        objOption.value = data[i];
        app_form.area.options.add(objOption); 
      }
    });
  }
  function areachange(){
    app_form.dc.options.length=0; 
    var options=$("#country option:selected");
    var options1=$("#area option:selected");
    url = "/index.php?r=dcmd-cbase-app/getdc";
    $.get(url,{country:options.val(),area:options1.val()},function(data){
      data = $.parseJSON(data);
      result = {}
      result = data;
      for (var key in result){ 
        objOption=document.createElement("OPTION");
        objOption.text = data[key];
        objOption.value = key;
        app_form.dc.options.add(objOption); 
      }
    });
  }
</script>
