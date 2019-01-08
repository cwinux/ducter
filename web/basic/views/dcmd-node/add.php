<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = '添加设备';
$this->params['breadcrumbs'][] = ['label' => '设备列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-node-create">
    <?php
    if( Yii::$app->getSession()->hasFlash('success') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success', //这里是提示框的class
            ],
            'body' => Yii::$app->getSession()->getFlash('success'), //消息体
        ]);
    }
    if( Yii::$app->getSession()->hasFlash('error') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
            ],
            'body' => "<font color=red>".Yii::$app->getSession()->getFlash('error')."</font>",
        ]);
    }
   ?>

<div class="dcmd-node-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => 16 ])->label('服务器IP') ?>

    <?= $form->field($model, 'ngroup_id')->dropDownList($node_group)->label('设备池子') ?>

    <?= $form->field($model, 'host')->textInput(['maxlength' => 128])->label('主机名') ?>

    <?= $form->field($model, 'sid')->textInput(['maxlength' => 128])->label('资产序列号') ?>

    <?= $form->field($model, 'did')->textInput(['maxlength' => 128])->label('设备序列号') ?>

    <?= $form->field($model, 'bend_ip')->textInput(['maxlength' => 16])->label('带外IP') ?>

    <?= $form->field($model, 'public_ip')->textInput(['maxlength' => 16])->label('公网IP') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

</div>
