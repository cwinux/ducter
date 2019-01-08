<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */

$this->title = '添加服务池配置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-conf-create">

    <?= $this->render('_conf', [
        'model' => $model,
    ]) ?>

</div>
