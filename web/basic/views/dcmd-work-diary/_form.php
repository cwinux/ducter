<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-vm-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'type')->dropDownList(['生态'=>'生态','公有云'=>'公有云'])->label('类型') ?>

    <?= $form->field($model, 'description')->textArea(['maxlength' => 255])->label('问题描述') ?>

    <?= $form->field($model, 'process')->textArea(['maxlength' => 255])->label('处理过程') ?>

    <?= $form->field($model, 'date')->textInput(['maxlength' => 32])->label('日期') ?>

    <?= $form->field($model, 'cost_time')->textInput(['maxlength' => 4])->label('花费时间(min)') ?>

    <?= $form->field($model, 'jira_add')->textInput(['maxlength' => 128])->label('工单地址') ?>
 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

