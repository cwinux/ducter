<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-pool-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_id')->textInput(['maxlength' => 128, 'value' => $model->getAppName($model->app_id), "disabled" => true])->label('产品名称') ?>

    <?= $form->field($model, 'svr_id')->textInput(['maxlength' => 128, 'value' => $model->getSvrName($model->svr_id), "disabled" => true])->label('服务名称') ?>

    <?= $form->field($model, 'pool_group')->textInput(['maxlength' => 128])->label('服务池组') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
