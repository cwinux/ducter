<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = '故障信息';
$this->params['breadcrumbs'][] = ['label' => $model->fault_id, 'url' => ['view', 'id' => $model->fault_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-fault-update">


    <?= $this->render('_updateform', [
        'model' => $model,
        'disabled' => "disabled",
    ]) ?>

</div>
