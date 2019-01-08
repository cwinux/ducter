<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdUserGroup */

$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-user-update">

    <?= $this->render('_userform', [
        'model' => $model,
    ]) ?>

</div>
