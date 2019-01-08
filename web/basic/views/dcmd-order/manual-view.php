<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePoolNode */

$this->title = "工单信息";
$this->params['breadcrumbs'][] = ['label' => '工单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
function execute() {
  alert("dd");
  $("#w0").attr("action", "/index.php?r=dcmd-order/execute");
}
</script>
<div class="dcmd-manual-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'bill_id:text:工单ID',
            'action:text:操作',
            'args:text:参数',
            'callback:text:回调函数',
            'state:text:状态',
            'errmsg:text:错误信息',
            'apply_user:text:申请人',
            'apply_time:text:申请时间',
            'ctime:text:创建时间',
            'utime:text:修改时间',
        ],
    ]) ?>
    <p>
        <?= Html::a('执行工单', ['dcmd-order/execute', 'order_id' => $model->order_id], ['class' => 'btn btn-primary' ]) ?>
    </p>
</div>
