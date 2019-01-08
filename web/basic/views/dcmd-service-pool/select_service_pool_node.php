<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '选择服务器';
$this->params['breadcrumbs'][] = $this->title;
?>

<form id="w0" action="<?php echo Url::to(['copy-service-pool',]); ?>" method="post">
<div class="dcmd-service-pool-node-index">

    <input type="text" name="app_id" value="<?php echo $app_id; ?>" style="display:none">
    <input type="text" name="svr_id" value="<?php echo $svr_id; ?>" style="display:none">
    <input type="text" name="select_svr_pool" value="<?php echo $select_svr_pool; ?>" style="display:none">
    <input type="text" name="create_pool_name" value="<?php echo $create_pool_name; ?>" style="display:none">
    <?php echo "选择服务器:</a>";
     echo "<br>";
     foreach($service_pool_node as $item) {
        echo "<input  class='checkItems'  name='SvrPoolNode".$item."' type=\"checkbox\" value='$item' />$item";
        echo "<br>";
     }
     
     ?>
    <div >
        <?= Html::submitButton('下一步',   ['class' => 'btn btn-success' ])?>
    </div>
</div>
</form>

<script language="JavaScript">
function showServicePoolNode(appPool){
 if( document.getElementById(appPool).style.display=='none'){
     document.getElementById(appPool).style.display='block';
 }else{
     document.getElementById(appPool).style.display='none';
 }
        
}
</script>

