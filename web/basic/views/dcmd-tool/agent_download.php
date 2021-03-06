<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '脚本下载';
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
<table class="table table-striped table-bordered">
<tr>
<td>
<label>apollo_agent_centos6</label>
</td>
<td>
<a href="/ducter/tool/agent/6/dcmd-agent-1.4-1.el6.x86_64.rpm" download="dcmd-agent-1.4-1.el6.x86_64.rpm">dcmd-agent-1.4-1.el6.x86_64.rpm</a>
</td>
</tr>
<tr>
<td>
<label>apollo_agent_centos7</label>
</td>
<td>
<a href="/ducter/tool/agent/7/dcmd-agent-1.4-1.el7.centos.x86_64.rpm" download="dcmd-agent-1.4-1.el7.centos.x86_64.rpm">dcmd-agent-1.4-1.el7.centos.x86_64.rpm</a>
</td>
</tr>
</table>
</div>
</form>
