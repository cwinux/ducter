<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = '更新IDC网段信息: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '网段管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-network-update">
    <?= $this->render('_form', [
        'model' => $model,
        'disabled' => "disabled",
    ]) ?>

</div>
