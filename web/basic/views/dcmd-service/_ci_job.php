<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-pkg-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'source_branch')->textInput(['maxlength' => 64])->label('源码分支') ?>

    <?= $form->field($model, 'source_sha1')->textInput(['maxlength' => 128])->label('源码sha1') ?>

    <?= $form->field($model, 'source_xml')->textInput(['maxlength' => 64])->label('源码xml') ?>

    <?= $form->field($model, 'pkg_version')->textInput(['maxlength' => 64])->label('软件版本') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
