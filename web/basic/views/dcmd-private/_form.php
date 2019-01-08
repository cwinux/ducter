<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-vm-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'host_ip')->textInput(['maxlength' => 16, "disabled"=>true])->label('宿主机IP') ?>

    <?= $form->field($model, 'vm_ip')->textInput(['maxlength' => 16, "disabled"=>true])->label('内网IP') ?>

    <?= $form->field($model, 'state')->dropDownList(["0"=>"未使用","1"=>"使用中","2"=>"可回收"])->label('VM使用状态') ?>

    <?= $form->field($model, 'business')->textInput(['maxlength' => 128, "disabled"=>true])->label('业务名称') ?>
 
    <?= $form->field($model, 'contacts')->textInput(['maxlength' => 128, "disabled"=>true])->label('使用人') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

