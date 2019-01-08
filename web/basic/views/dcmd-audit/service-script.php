<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务脚本审批';
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
function pass() {
  $("#w0").attr("action", "/index.php?r=dcmd-audit/pass-script");
}
function reject() {
  $("#w0").attr("action", "/index.php?r=dcmd-audit/reject-script");
}
</script>
<form id="w0" action="/ducter/index.php?r=dcmd-service-pool-node/delete-all" method="post">
<div class="dcmd-service-pool-audit-index">

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
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'app_id', 'label'=>'产品名称','enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model->getAppName($model['app_id']), Url::to(['dcmd-app/view', 'id'=>$model['app_id']]));},),
            array('attribute'=>'svr_id', 'label'=>'服务名称','enableSorting'=>false,'content' => function($model, $key, $index, $column) { return Html::a($model->getSvrName($model['svr_id']), Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]));},),
            array('attribute'=>'pool_group', 'label'=>'服务池组','enableSorting'=>false),
            array('attribute'=>'svr_id', 'label'=>'提交人','enableSorting'=>false,'content' => function($model, $key, $index, $column) { return $model->getUser($model['opr_uid']);}),
            array('attribute'=>'script_md5', 'label'=>'提交人','enableSorting'=>false),
            array('attribute'=>'script', 'label'=>'脚本', 'enableSorting'=>false,'content' => function($model, $key, $index, $column) { return Html::a("查看", Url::to(['dcmd-audit/load-content', 'id'=>$model['id']]),['target'=>'_blank']);}),
//            array('attribute'=>'opr_uid', 'label'=>'提交人', 'enableSorting'=>false,'content' => function($model, $key, $index, $column) { return $model->getUserName($model['opr_uid']);}),
           // ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
        <?= Html::submitButton('通过', ['class' =>'btn btn-success', 'onClick'=>"pass()"])?>&nbsp;&nbsp;
        <?= Html::submitButton('驳回', ['class' =>'btn btn-success', 'onClick'=>"reject()"])?>&nbsp;&nbsp;
</div>
</form>
