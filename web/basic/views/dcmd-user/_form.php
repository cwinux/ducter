<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 128, "$disabled"=>"true"])->label("用户名") ?>

    <?= $form->field($model, 'admin')->label('sa',['style' => Yii::$app->user->getIdentity()->admin != 1 ? 'display:none':'display:'])->dropDownList([0=>"否", 1=>"是"],['style' => Yii::$app->user->getIdentity()->admin != 1 ? 'display:none':'display:']) ?>

    <?= $form->field($model, 'sa')->label('admin',['style' => Yii::$app->user->getIdentity()->sa != 1 ? 'display:none':'display:'])->dropDownList([0=>"否",1=>"是"],['style' => Yii::$app->user->getIdentity()->sa != 1 ? 'display:none':'display:']) ?>

    <?= $form->field($model, 'comp_id')->dropDownList($company,['id'=>'compID','prompt'=>'','onchange'=>'userchange()'])->label('公司名称') ?>

    <?= $form->field($model, 'depart_id')->dropDownList([],['id'=>'depID'])->label('部门') ?>

    <?= $form->field($model, 'tel')->textInput(['maxlength' => 128])->label("Tel") ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 64])->label("Email") ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label("说明") ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
  function loadfun(){
    userchange();
  }
  function userchange(){
    depID.options.length=0; 
    var options1=$("#compID option:selected");
    url = "/index.php?r=dcmd-user/getcomp";
    $.get(url,{comp:options1.val()},function(data){
      data = $.parseJSON(data);
      for(i=0;i<data.length;i++) {
        objOption=document.createElement("OPTION");
        objOption.text = data[i]['depart_name'];
        objOption.value = data[i]['depart_id'];
        depID.options.add(objOption); 
      }
    });
  }
</script>
