<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '设备池';
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/ducter/index.php?r=dcmd-node-group/delete-all" method="post">
<div class="dcmd-node-group-index">

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
            array('attribute' => 'ngroup_name',  'label'=>'设备池', 'content'=>function($model, $key, $index, $column) {return Html::a($model['ngroup_name'], Url::to(['dcmd-node-group/view', 'id'=>$model['ngroup_id']]));}),
            array('attribute' => 'gid', 'content'=>function($model, $key, $index, $column) { return Html::a($model->getGname($model['gid']), Url::to(['dcmd-group/view', 'id'=>$model['gid']]));}, 'label' => '系统组', 'filter'=>$groupId),
            array('attribute'=>'location', 'label'=>'区域', 'enableSorting'=>false),
            array('attribute'=>'gtype', 'label'=>'类别', 'enableSorting'=>false),
            array('attribute'=>'operators', 'label'=>'运营商', 'enableSorting'=>false),
            array('attribute'=>'mach_room', 'label'=>'机房', 'enableSorting'=>false),
            array('attribute'=>'manage_ip', 'label'=>'管理地址', 'enableSorting'=>false),
            array('attribute'=>'net', 'label'=>'虚拟机实例地址', 'enableSorting'=>false),
	['class' => 'yii\grid\ActionColumn',  "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
        <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
</form>
