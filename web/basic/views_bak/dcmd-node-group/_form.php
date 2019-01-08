<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-node-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ngroup_name')->textInput(['maxlength' => 128, ])->label("设备池子")?>

    <?= $form->field($model, 'gid')->dropDownList($groups)->label("系统组") ?>

    <?= $form->field($model, 'location')->dropDownList($location)->label("区域")?>

    <?= $form->field($model, 'gtype')->dropDownList($type)->label("类别")?>

    <?= $form->field($model, 'operators')->dropDownList($operator)->label("运营商")?>

    <?= $form->field($model, 'mach_room')->dropDownList($room)->label("机房")?>

    <?= $form->field($model, 'manage_ip')->textInput(['maxlength' => 32, ])->label("管理地址")?>

    <?= $form->field($model, 'net')->textInput(['maxlength' => 32, ])->label("虚拟机实例地址")?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label("说明") ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
