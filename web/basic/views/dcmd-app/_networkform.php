<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdApp */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="dcmd-app-form">

    <?php $form = ActiveForm::begin(['options' => ['name' => 'app_form'],]); ?>

    <?= $form->field($model, 'app_id')->textInput(['maxlength' => 64, 'value' => $model->getAppName($app_id), 'readonly' => true])->label('产品名称') ?>

    <?= $form->field($model, 'network')->textInput(['maxlength' => 64])->label('网段') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div class="ant-col-24" style="padding-left: 5px; padding-right: 5px;"><hr></div>
</div>
