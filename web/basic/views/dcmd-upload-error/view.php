<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = "镜像详细信息:".$model->id;
$this->params['breadcrumbs'][] = ['label' => '镜像管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

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

<div class="dcmd-image-view" id="dcmd-image">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name:text:镜像名称',
            'image_version:text:版本号',
            'os:text:系统',
        ],
    ]) ?>
    <p>
        <?= Html::a('更改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary',  (Yii::$app->user->getIdentity()->admin == 1) ? "": "style"=>"display:none"]) ?>
    </p>
</div>
