<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-pkg-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'version')->textInput(['maxlength' => 16])->label('版本') ?>

    <?= $form->field($model, 'md5')->textInput(['maxlength' => 64])->label('md5') ?>

    <?= $form->field($model, 'passwd')->textInput(['maxlength' => 128])->label('passwd') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>