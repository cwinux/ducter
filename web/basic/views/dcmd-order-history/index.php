<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '工单日志';
$this->params['breadcrumbs'][] = ['label' => '日志管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
function pass() {
  $("#w0").attr("action", "/index.php?r=dcmd-audit/pass");
}
function reject() {
  $("#w0").attr("action", "/index.php?r=dcmd-audit/reject");
}
</script>
<form id="w0" action="/ducter/index.php?r=dcmd-order-history/delete-all" method="post">
<div class="dcmd-order-history-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'bill_id','label'=>'工单号','enableSorting'=>false),
            array('attribute'=>'action', 'label'=>'操作', 'enableSorting'=>false),
            array('attribute'=>'state', 'label'=>'状态', 'enableSorting'=>false),
            array('attribute'=>'apply_user', 'label'=>'申请人', 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{view}', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
    </p>
</div>
</form>