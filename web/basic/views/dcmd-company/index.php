<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '公司列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-user-index">

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
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'comp_name', 'label'=>'公司名称',),
            array('attribute'=>'comment', 'label'=>'说明'),
//            ['class' => 'yii\grid\ActionColumn',  "visible"=>(Yii::$app->user->getIdentity()->admin == 1 && Yii::$app->user->getIdentity()->sa == 1) ? true : false, ],
        ],
    ]); ?>
</div>
