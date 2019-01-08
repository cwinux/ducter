<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroup */

$this->title = '添加故障信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-node-group-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
