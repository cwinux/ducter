<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-pool-form">

    <?php $form = ActiveForm::begin(); ?>

   <!-- <?= $form->field($model, 'app_id')->textInput(["disabled" => true,'value'=>$model->getApp($model['app_id'])])->label('产品') ?>

    <?= $form->field($model, 'svr_id')->textInput(["disabled" => true,'value'=>$model->getService($model['svr_id'])])->label('服务') ?>

    <?= $form->field($model, 'svr_pool_id')->textInput(['maxlength' => 128, "disabled" => true,'value'=>$model->getPool($model['svr_pool_id'])])->label('服务池子名称') ?> -->

    <?= $form->field($model, 'version')->textInput(['maxlength' => 512])->label('版本') ?>

    <?= $form->field($model, 'md5')->textInput(['maxlength' => 128])->label('md5') ?>

    <?= $form->field($model, 'passwd')->textInput(['maxlength' => 128])->label('passwd') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
