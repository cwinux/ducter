<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdUser */

$this->title = '添加用户';
$this->params['breadcrumbs'][] = ['label' => '用户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-user-create">

    <?= $this->render('_form', [
        'model' => $model,
        'depart' => $depart,
        'company' => $company,
        'disabled' => '',
    ]) ?>

</div>
