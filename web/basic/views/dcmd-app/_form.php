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
   
    <?= $form->field($model, 'app_type')->textInput(['maxlength' => 16])->label('产品类型') ?>

    <?= $form->field($model, 'service_tree')->textInput(['maxlength' => 255])->label('服务树') ?>

    <?= $form->field($model, 'comp_id')->dropDownList($company)->label('所属公司') ?>

    <?= $form->field($model, 'sa_gid')->dropDownList($sys_user_group,[Yii::$app->user->getIdentity()->admin != 1 ? "disabled" : "" =>true])->label("系统组") ?>

    <?= $form->field($model, 'svr_gid')->dropDownList($svr_user_group,[Yii::$app->user->getIdentity()->admin != 1 ? "disabled" : "" =>true])->label("业务组") ?>

    <?= $form->field($model, 'depart_id')->dropDownList($depart)->label('部门')?>
 
    <?= $form->field($model, 'is_self')->dropDownList(array(0=>'否',1=>'是'),[Yii::$app->user->getIdentity()->admin != 1 ? "disabled" : "" =>true])->label('业务组可操作') ?> 

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
