<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */

$this->title = '修改端口';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-pool-create">

    <?= $this->render('_update_pool_port', [
        'model' => $model,
    ]) ?>

</div>
