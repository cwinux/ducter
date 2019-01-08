<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = "IDC信息:".$model->dc_id;
$this->params['breadcrumbs'][] = ['label' => '机房管理', 'url' => ['index']];
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
<form id="w0" action="/index.php?r=dcmd-idc/delete-net" method="post">
<div class="dcmd-network-view" id="dcmd-network">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'dc:text:IDC',
            'area:text:区域',
            'country:text:国家',
        ],
    ]) ?>

</div>

