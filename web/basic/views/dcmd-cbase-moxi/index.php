<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务池设备';
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
function opr() {
  $("#w0").attr("action", "/ducter/index.php?r=dcmd-service-pool-node/opr");
}
function repeatopr() {
  $("#w0").attr("action", "/ducter/index.php?r=dcmd-service-pool-node/repeat-opr");
}
</script>
<form id="w0" action="/index.php?r=dcmd-service-pool-node/delete-all" method="post">
<div class="dcmd-service-pool-node-index">

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
            array('attribute'=>'app_name','label'=>'产品名称','content' => function($model, $key, $index, $column) { return Html::a($model['app_name'], Url::to(['/dcmd-cbase-app/view', 'id'=>$model['app_id']]));}),
            array('attribute'=>'bucket','label'=>'Bucket名称'),
            array('attribute'=>'moxi_ip','label'=>'Moxi IP'),
            array('attribute'=>'business','label'=>'业务名称'),
            array('attribute'=>'module','label'=>'模块名称'),
            array('attribute'=>'contacts','label'=>'联系电话'),
//            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
</div>
</form>
