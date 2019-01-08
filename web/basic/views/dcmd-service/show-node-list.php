<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '确认务器IP';
$this->params['breadcrumbs'][] = $this->title;
?>

<form id="w0" action="<?php echo Url::to(['add-node',]); ?>" method="post">
<div class="dcmd-service-pool-node-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <input type="text" name="app_id" value="<?php echo $app_id; ?>" style="display:none">
    <input type="text" name="svr_id" value="<?php echo $svr_id; ?>" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'ip','label'=>'IP',),
            array('attribute'=>'svr_pool_id', 'label'=>'服务池',),
        ],
    ]); ?>

    <div >
        <?= Html::submitButton('确认部署',   ['class' => 'btn btn-success' ])?>
    </div>
</div>
</form>
