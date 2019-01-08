<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-network-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idc')->textInput(['maxlength' => 64])->label('网段') ?>

    <?= $form->field($model, 'segment')->dropDownList(["test"])->label('IDC') ?>

    <?= $form->field($model, 'type')->dropDownList(["宿主机ip段"=>"宿主机ip段","虚拟机ip段"=>"虚拟机ip段"])->label('网段类型') ?>
 
    <?= $form->field($model, 'comment')->textInput(['maxlength' => 4])->label('vlan') ?>

    <div class="form-network">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
