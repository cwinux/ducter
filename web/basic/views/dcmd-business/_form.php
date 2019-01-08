<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-product-bu">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'product')->dropDownList($product)->label('业务名称') ?>

    <?= $form->field($model, 'bu')->dropDownList($bu)->label('所属BU') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

