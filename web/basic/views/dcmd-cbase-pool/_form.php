<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-pool-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pool_name')->textInput(['maxlength' => 128, $model->pool_name ? "disabled" : "" => true])->label('服务池子名称') ?>

    <?= $form->field($model, 'app_id')->textInput(["style"=>"display:none"])->label('产品',["style"=>"display:none"]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
