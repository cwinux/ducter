<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '工单回调日志';
$this->params['breadcrumbs'][] = ['label' => '日志管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/index.php?r=dcmd-orderreply-history/delete-all" method="post">
<div class="dcmd-alarm-history-index">

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
            array('attribute'=>'order_id', 'label'=>'工单ID', 'enableSorting'=>false),
            array('attribute'=>'bill_id', 'label'=>'bill id', 'enableSorting'=>false),
            array('attribute'=>'action', 'label'=>'操作', 'enableSorting'=>false),
            array('attribute'=>'result', 'label'=>'结果', 'enableSorting'=>false),
            array('attribute'=>'callback', 'label'=>'回调', 'enableSorting'=>false),
            array('attribute'=>'state', 'label'=>'状态', 'enableSorting'=>false),
            array('attribute'=>'count', 'label'=>'次数', 'enableSorting'=>false),
            array('attribute'=>'errmsg', 'label'=>'错误信息', 'enableSorting'=>false),
            array('attribute'=>'ctime', 'label'=>'创建时间', 'enableSorting'=>false),
            array('attribute'=>'utime', 'label'=>'修改时间', 'enableSorting'=>false),
//            ['class' => 'yii\grid\ActionColumn','template'=>'{view}', 'urlCreator'=>function($action, $model, $key, $index) {return Url::to(['dcmd-operate-history/view','id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
              
          //  ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
       // ],
    ]); ?>
    <p>
        <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
    </p>
</div>
</form>
