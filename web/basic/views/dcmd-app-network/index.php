<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '产品网段';
$this->params['breadcrumbs'][] = $this->title;
?>

<form id="w0" action="/index.php?r=dcmd-network/delete-all" method="post">
<div class="dcmd-network-index">

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
 //       'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'app_id', 'label'=>'产品名称', 'enableSorting'=>false, 'content'=>function($model, $key, $index, $column) { return Html::a($model->getAppName($model['app_id']), Url::to(['dcmd-app/view', 'id'=>$model['app_id']]));}),
            array('attribute'=>'network', 'label'=>'网段','enableSorting'=>false),
            array('attribute'=>'network', 'label'=>'IDC', 'enableSorting'=>false, 'content'=>function($model, $key, $index, $column) { return $model->getIdc($model->network);}),
            array('attribute'=>'network', 'label'=>'网段类型', 'enableSorting'=>false, 'content'=>function($model, $key, $index, $column) { return $model->getType($model->network);}),
            array('attribute'=>'network', 'label'=>'vlan', 'enableSorting'=>false, 'content'=>function($model, $key, $index, $column) { return $model->getVlan($model->network);}),
//            ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>

</div>
</form>
