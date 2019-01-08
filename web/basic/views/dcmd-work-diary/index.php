<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '工作记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/index.php?r=dcmd-work-diary/delete-all" method="post">
<div class="dcmd-fault-index">

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
            array('attribute'=>'type', 'label' => '类型', 'enableSorting'=>false),
            array('attribute'=>'description', 'label' => '问题描述', 'enableSorting'=>false),
            array('attribute'=>'process', 'label'=>'处理过程', 'enableSorting'=>false),
            array('attribute'=>'date', 'label' => '日期', 'enableSorting'=>false),
            array('attribute'=>'cost_time', 'label'=>'花费时间(min)', 'enableSorting'=>false),
            array('attribute'=>'jira_add', 'label'=>'工单地址', 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn','template'=>'{update}', 'urlCreator'=>function($action, $model, $key, $index) {return Url::to(['dcmd-work-diary/update','id'=>$model['diary_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
              
          //  ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
       // ],
    ]); ?>
    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
        <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
    </p>
</div>
</form>
