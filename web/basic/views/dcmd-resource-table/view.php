<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = "资源类型对应表:".$model->id;
$this->params['breadcrumbs'][] = ['label' => '资源类型', 'url' => ['index']];
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
            'comment:text:说明'
        ],
    ]) ?>
</div>
<p>表字段信息:</p>
<div class="dcmd-image-view" id="dcmd-image">
    <?php echo $columns;?>
</div>
