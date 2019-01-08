<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = '更新IDC网段信息: ' . ' ' . $model->dc_id;
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-network-update">
    <?= $this->render('_form', [
        'model' => $model,
        'disabled' => "disabled",
    ]) ?>

</div>
