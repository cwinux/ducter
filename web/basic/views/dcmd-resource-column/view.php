<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = "列定义详细信息:".$model->id;
$this->params['breadcrumbs'][] = ['label' => '资源定义', 'url' => ['index']];
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
            'res_type:text:类型',
            'res_table:text:表名称',
            'colum_name:text:字段名称',
            'display_name:text:显示名称',
            'display_order:text:显示顺序',
            array('attribute'=>'display_list', 'label'=>'是否主页显示', 'enableSorting'=>false,'value'=>($model->display_list == 1?'是':'否')),
        ],
    ]) ?>
    <p>
        <?= Html::a('更改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary',  (Yii::$app->user->getIdentity()->admin == 1) ? "": "style"=>"display:none"]) ?>
    </p>
</div>
