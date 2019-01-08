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

    <?= $form->field($model, 'app_alias')->textInput(['maxlength' => 128])->label('产品别名') ?>

    <?= $form->field($model, 'country')->dropDownList($country,['onchange'=>'counchange()'])->label('国家') ?>

    <?= $form->field($model, 'area')->dropDownList([],['id'=>'area','onchange'=>'areachange()'])->label('地区') ?>

    <?= $form->field($model, 'dc')->dropDownList([],['id'=>'dc'])->label('机房') ?>

    <?= $form->field($model, 'endpoint')->textInput(['maxlength' => 256])->label('endpoint') ?>

    <?= $form->field($model, 'vip')->textInput(['maxlength' => 32])->label('vip') ?>

    <?= $form->field($model, 'net')->textInput(['maxlength' => 32])->label('net') ?>

    <?= $form->field($model, 'inner_id')->textInput(['maxlength' => 64])->label('内部id') ?>

    <?= $form->field($model, 'outer_id')->textInput(['maxlength' => 64])->label('外部id') ?>

    <?= $form->field($model, 'version')->textInput(['maxlength' => 32])->label('版本') ?>

    <?= $form->field($model, 'region')->textInput(['maxlength' => 32])->label('region') ?>

    <?= $form->field($model, 'type')->dropDownList($app_type)->label('类型') ?>

    <?= $form->field($model, 'tenant')->textInput(['maxlength' => 32])->label('租户') ?>

    <?= $form->field($model, 'account')->textInput(['maxlength' => 32])->label('账号') ?>

    <?= $form->field($model, 'passwd')->textInput(['maxlength' => 32])->label('密码') ?>

    <?= $form->field($model, 'db_host')->textInput(['maxlength' => 32])->label('数据库主机') ?>

    <?= $form->field($model, 'db_passwd')->textInput(['maxlength' => 32])->label('数据库密码') ?>

    <?= $form->field($model, 'self_service')->dropDownList(['0'=>'是','1'=>'否'])->label('是否自助服务') ?>

    <?= $form->field($model, 'threshold')->textInput(['maxlength' => 2])->label('分配阈值') ?>

    <?= $form->field($model, 'oversale_rate')->textInput(['maxlength' => 2])->label('超售比') ?>

    <?= $form->field($model, 'sa_gid')->dropDownList($sys_user_group)->label("系统组") ?>

    <?= $form->field($model, 'svr_gid')->dropDownList($svr_user_group)->label("业务组") ?>

    <?= $form->field($model, 'depart_id')->dropDownList($depart)->label('部门')?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6, 'maxlength' => 512])->label('说明') ?>

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
    var options=$("#dcmdapp-country option:selected");
    url = "/index.php?r=dcmd-app/getarea";
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
    var options=$("#dcmdapp-country option:selected");
    var options1=$("#area option:selected");
    url = "/index.php?r=dcmd-app/getdc";
    $.get(url,{country:options.val(),area:options1.val()},function(data){
      data = $.parseJSON(data);
      for (i=0;i<data.length;i++){ 
        objOption=document.createElement("OPTION");
        objOption.text = data[i];
        objOption.value = data[i];
        app_form.dc.options.add(objOption); 
      }
    });
  }
</script>
