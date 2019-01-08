<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-port-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'port_name')->textInput(['maxlength' => 32])->label('端口名') ?>

    <?= $form->field($model, 'port')->textInput(['maxlength' => 16])->label('端口') ?>

    <?= $form->field($model, 'mapped_port')->textInput(['maxlength' => 8])->label('映射端口') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
