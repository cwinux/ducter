<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '资源类型';
$this->params['breadcrumbs'][] = $this->title;
?>

<form id="w0" action="/ducter/index.php?r=dcmd-resource-table/delete-all" method="post">
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
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'res_type', 'label'=>'类型','enableSorting'=>false),
            array('attribute'=>'res_table', 'label'=>'数据表', 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{view}{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("view" == $action) return Url::to(['dcmd-resource-table/view','id'=>$model['id']]);else if("delete" == $action) return Url::to(['dcmd-resource-table/delete','id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
//            ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
        <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
    </p>

</div>
</form>
