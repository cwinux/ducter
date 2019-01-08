<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = '更新列定义信息: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '资源定义', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-column-update">


    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
        'column' => $column,
        'disabled' => "disabled",
    ]) ?>

</div>
