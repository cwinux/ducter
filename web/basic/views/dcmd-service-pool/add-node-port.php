<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */

$this->title = '添加端口';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-pool-create">

    <?= $this->render('_add_port', [
        'model' => $model,
    ]) ?>

</div>
