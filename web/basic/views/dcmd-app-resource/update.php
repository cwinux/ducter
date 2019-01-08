<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdUser */

$this->title = '修改信息';
$this->params['breadcrumbs'][] = '修改用户信息';
?>
<div class="dcmd-user-update">

    <?= $this->render('_form', [
        'model' => $model,
        'ser_array' => $ser_array,
        'ser_pool_array' => $ser_pool_array,
    ]) ?>

</div>
