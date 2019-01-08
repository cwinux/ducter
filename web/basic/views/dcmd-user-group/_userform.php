<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdUserGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => 128, $model->getUsername($model->uid) ? "disabled" : "" =>true])->label('用户名称') ?>

    <?= $form->field($model, 'gid')->textInput(['maxlength' => 128, $model->getGroupname($model->gid) ? "disabled" : "" =>true])->label('用户组') ?>

    <?= $form->field($model, 'is_leader')->dropDownList(array(0=>'否',1=>'是'))->label('是否leader') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
