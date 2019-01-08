<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-pkg-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ci_type')->textInput(['maxlength' => 16])->label('CI类型') ?>

    <?= $form->field($model, 'ci_jenkins_url')->textInput(['maxlength' => 64])->label('jenkins地址') ?>

    <?= $form->field($model, 'ci_url')->textInput(['maxlength' => 512])->label('项目地址') ?>

    <?= $form->field($model, 'ci_user')->textInput(['maxlength' => 64])->label('用户名') ?>

    <?= $form->field($model, 'ci_passwd')->passwordInput()->label('密码') ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
