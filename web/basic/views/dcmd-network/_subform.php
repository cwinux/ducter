<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-subnet-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_name')->textInput(['maxlength' => 64])->label('集群名称') ?>

    <?= $form->field($model, 'host_segment')->textInput(['maxlength' => 64])->label('物理机网段') ?>

    <?= $form->field($model, 'vm_segment')->textInput(['maxlength' => 64])->label('虚拟机网段') ?>
 
    <?= $form->field($model, 'gateway')->textInput(['maxlength' => 64])->label('网关') ?>

    <?= $form->field($model, 'vlan')->textInput(['maxlength' => 4])->label('vlan') ?>

    <div class="form-network">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
