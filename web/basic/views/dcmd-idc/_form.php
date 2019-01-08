<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-network-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dc')->textInput(['maxlength' => 64])->label('IDC') ?>

    <?= $form->field($model, 'area')->textInput(['maxlength' => 64])->label('区域') ?>

    <?= $form->field($model, 'country')->textInput(['maxlength' => 64])->label('国家') ?>
 

    <div class="form-network">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
