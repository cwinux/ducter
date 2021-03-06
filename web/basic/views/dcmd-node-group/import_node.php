<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

/* @var $tdis yii\web\View */
/* @var $model app\models\DcmdAppArchDiagram */

$this->title = '导入机器';

$this->params['breadcrumbs'][] = ['label' => '设备池子', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ngroup_name, 'url' => ['view', 'id' => $model->ngroup_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-node-group-import-node">
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


<div class="dcmd-import-node-form">

 <form id="import_node" name="import_name" enctype="multipart/form-data" method="post">
 <input type='hidden' name='ngroup_id' id='ngroup_id' value=<?php echo $model->ngroup_id;?>>
 <label class="control-label" >数据文件</label>
 <input id="nfile" name="nfile" type='file' />
 <div class="help-block"></div>
 <div id="w0" class="grid-view">
<div class="summary"><font color=red>文件字段格式(支持txt格式,每行一个ip):</font></div>
<table style="border:solid 2px #add9c0;">
<tr ><td style="border:solid 2px #add9c0;">服务器IP</td></tr>
 </table> </div>
 <div>
<button onClick="smit()" class="btn btn-primary">添加</button> 
 </div>
 </form>
</div>
</div>
<script>
function smit() {
  document.import_node.action='/index.php?r=dcmd-node-group/import-node&ngroup_id=<?php echo $model->ngroup_id; ?>';
  document.import_node.submit();
}
</script>
