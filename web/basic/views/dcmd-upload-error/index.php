<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '上传错误';
$this->params['breadcrumbs'][] = $this->title;
?>

<form id="w0" action="/ducter/index.php?r=dcmd-image/delete-all" method="post">
<div class="dcmd-node-index">

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
//            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'app_name', 'label'=>'产品名','enableSorting'=>false),
            array('attribute'=>'svr_name', 'label'=>'服务名', 'enableSorting'=>false),
            array('attribute'=>'svr_pool', 'label'=>'服务池', 'enableSorting'=>false),
            array('attribute'=>'upload_type', 'label'=>'类型','enableSorting'=>false),
            array('attribute'=>'upload_username', 'label'=>'上传者', 'enableSorting'=>false),
            array('attribute'=>'version', 'label'=>'版本', 'enableSorting'=>false),
            array('attribute'=>'src_path', 'label'=>'文件路径', 'enableSorting'=>false),
            array('attribute'=>'pkg_file', 'label'=>'包名称', 'enableSorting'=>false),
            array('attribute'=>'errmsg', 'label'=>'错误信息', 'enableSorting'=>false),
//            ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
</div>
</form>
