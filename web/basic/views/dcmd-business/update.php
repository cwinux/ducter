<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = '业务-BU' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '业务&BU', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-product-update">


    <?= $this->render('_form', [
        'model' => $model,
        'product' => $product,
        'bu' => $bu,
        'disabled' => "disabled",
    ]) ?>

</div>
