<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-pool-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_id')->textInput(['maxlength' => 128, $model->app_id ? "disabled" : "" => true])->label('产品名称') ?>

    <?= $form->field($model, 'bucket_name')->textInput(['maxlength' => 64])->label('Buckets名称') ?>

    <?= $form->field($model, 'type')->dropDownList(['Cache Buckets'=>'Cache Buckets', 'Persistent Buckets' => 'Persistent Buckets'])->label('Buckets类型') ?>

    <?= $form->field($model, 'uid')->dropDownList([1 => "test"])->label('用户') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
