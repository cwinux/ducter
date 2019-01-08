<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePoolNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-pool-node-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ip')->textInput()->label("IP") ?>

    <?= $form->field($model, 'state')->dropDownList(['0' => '下线','1'=>'在线'])->label("状态") ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
