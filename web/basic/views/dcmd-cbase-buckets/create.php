<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */

$this->title = '添加';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-pool-create">

    <?= $this->render('_form', [
        'model' => $model,
        'user' => $user,
    ]) ?>

</div>
