<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */

$this->title = '添加服务';
$this->params['breadcrumbs'][] = ['label' => '服务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-create">
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
    <?= $this->render('_createForm', [
        'data' => $data,
        'model' => $model,
        'ser_array' => $ser_array,
        'ser_pool_array' => $ser_pool_array,
        'dnsModel' => $dnsModel,
        'dnsProvider' => $dnsProvider,
        'slbModel' => $slbModel,
        'slbProvider' => $slbProvider,
    ]) ?>

</div>
