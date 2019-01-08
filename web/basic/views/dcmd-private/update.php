<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = 'VM: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'VM分配', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->vm_ip, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-node-update">


    <?= $this->render('_form', [
        'model' => $model,
        'disabled' => "disabled",
    ]) ?>

</div>
