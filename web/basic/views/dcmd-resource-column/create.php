<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = '添加列定义';
$this->params['breadcrumbs'][] = ['label' => '资源表定义', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-image-create">
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

    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
        'column' => $column,
        'disabled' => "enable",
    ]) ?>

</div>
