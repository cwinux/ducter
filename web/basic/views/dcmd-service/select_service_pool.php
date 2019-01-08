<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '选择服务池子';
$this->params['breadcrumbs'][] = $this->title;
?>

<form id="w0" action="<?php echo Url::to(['show-node-list','app_id'=>$app_id, 'svr_id'=>$svr_id]); ?>" method="post">
<div class="dcmd-service-pool-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <input type="text" name="app_id" value="<?php echo $app_id; ?>" style="display:none">
    <input type="text" name="svr_id" value="<?php echo $svr_id; ?>" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'svr_pool_id','label'=>'服务池', 'value'=>function($model, $key, $index, $column) { return $model->getPool($model['svr_pool_id']);},),
        ],
    ]); ?>

    <div >
        <?= Html::submitButton('确认部署',   ['class' => 'btn btn-success' ])?>
    </div>
</div>
</form>
