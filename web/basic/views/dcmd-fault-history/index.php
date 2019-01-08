<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '故障日志';
$this->params['breadcrumbs'][] = ['label' => '日志管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/index.php?r=dcmd-fault/delete-all" method="post">
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
            array('attribute'=>'app_name', 'label' => '集群名称', 'enableSorting'=>false),
            array('attribute'=>'host_ip', 'label' => '宿主机IP', 'enableSorting'=>false),
            array('attribute'=>'vm_ip', 'label'=>'虚拟机IP', 'enableSorting'=>false),
            array('attribute'=>'vm_fault', 'label'=>'虚拟机故障信息', 'enableSorting'=>false),
            array('attribute'=>'start_time', 'label'=>'故障起始时间', 'enableSorting'=>false),
            array('attribute'=>'is_confirm', 'label'=>'是否确认', 'enableSorting'=>false),
            array('attribute'=>'erase_time', 'label'=>'解决时间', 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn','template'=>'{view}', 'urlCreator'=>function($action, $model, $key, $index) {return Url::to(['dcmd-fault-history/view','id'=>$model['fault_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
              
          //  ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
       // ],
    ]); ?>
    <p>
        <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
    </p>
</div>
</form>
