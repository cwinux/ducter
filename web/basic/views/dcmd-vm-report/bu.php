<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '私有云';
$this->params['breadcrumbs'][] = $this->title;
?>
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
           // array('attribute'=>'cluster', 'label' => '集群名称', 'content'=> function($model, $key, $index, $column) { return Html::a($model['ip'], Url::to(['dcmd-node/view', 'id'=>$model['nid']]));}),
           // array('attribute'=>'host_ip', 'label' => '宿主机IP', 'content'=> function($model, $key, $index, $column) { return Html::a($model['ip'], Url::to(['dcmd-node/view', 'id'=>$model['nid']]));}),
            array('attribute'=>'cluster', 'label' => '业务名称', 'enableSorting'=>false),
            array('attribute'=>'vm_sn', 'label'=>'所属BU', 'content'=> function($model, $key, $index, $column) { return Html::a($model['vm_sn'], Url::to(['dcmd-private/view', 'id'=>$model['id']]));}),
            ['class' => 'yii\grid\ActionColumn','template'=>'{view}{update}{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("view" == $action) return Url::to(['dcmd-private/view','id'=>$model['id']]);else if (("update" == $action))return Url::to(['dcmd-private/update','id'=>$model['id']]);else return Url::to(['dcmd-private/update','id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
              
       //     ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
       // ],
    ]); ?>
    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
        <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
</form>
