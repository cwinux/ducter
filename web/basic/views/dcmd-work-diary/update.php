<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = '工作日志';
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-work-update">


    <?= $this->render('_form', [
        'model' => $model,
        'disabled' => "disabled",
    ]) ?>

</div>
