<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-vm-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'app_name')->textInput(['maxlength' => 128])->label('集群名称') ?>

    <?= $form->field($model, 'host_ip')->textInput(['maxlength' => 16])->label('宿主机IP') ?>

    <?= $form->field($model, 'host_fault')->textInput(['maxlength' => 16])->label('宿主机故障信息') ?>

    <?= $form->field($model, 'vm_uuid')->textInput(['maxlength' => 128])->label('VM UUID') ?>

    <?= $form->field($model, 'vm_ip')->textInput(['maxlength' => 16])->label('VM IP') ?>
 
    <?= $form->field($model, 'vm_fault')->textInput(['maxlength' => 16])->label('VM故障信息') ?>

    <?= $form->field($model, 'start_time')->textInput(['maxlength' => 32])->label('开始时间') ?>

    <?= $form->field($model, 'is_confirm')->textInput(['maxlength' => 1])->label('是否确认') ?>

    <?= $form->field($model, 'confirm_time')->textInput(['maxlength' => 32])->label('确认时间') ?>
 
    <?= $form->field($model, 'erase_time')->textInput(['maxlength' => 32])->label('处理时间') ?>

    <?= $form->field($model, 'confirm_user')->textInput(['maxlength' => 32])->label('确认人') ?>

    <?= $form->field($model, 'erase_user')->textInput(['maxlength' => 32])->label('处理人') ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => 256])->label('说明') ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

