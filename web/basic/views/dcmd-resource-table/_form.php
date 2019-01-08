<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-image-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'res_type')->textInput(['maxlength' => 64])->label('类型') ?>

    <?= $form->field($model, 'res_table')->textInput(['maxlength' => 64])->label('表名称') ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => 512])->label('说明') ?>

    <div class="form-image">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
