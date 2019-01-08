<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-pool-node-form">

    <?php $form = ActiveForm::begin(['options' => ['name' => 'service_pool_port'],]); ?>

    <?= $form->field($model, 'port_name')->textInput(['maxlength' => 16, $model->port_name? "disabled" : "" => true])->label('端口名') ?>

    <?= $form->field($model, 'port')->textInput(['maxlength' => 16,$model->port? "disabled" : "" => true])->label('端口') ?>

    <?= $form->field($model, 'mapped_port')->textInput(['maxlength' => 16])->label('主机端口') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
