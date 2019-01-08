<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdApp */

$this->title = '添加网段';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-app-create">
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


    <?= $this->render('_networkform', [
        'model' => $model,
        'dc' => $dc,
//        'network' => $network,
  //      'sys_user_group' => $sys_user_group,
  //      'svr_user_group' => $svr_user_group,
  //      'app_type' => $app_type,
  //      'country' => $country,
    ]) ?>

</div>
