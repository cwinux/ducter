<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '选择服务';
$this->params['breadcrumbs'][] = $this->title;
?>

<form id="w0" action="<?php echo Url::to(['copy-service',]); ?>" method="post">
<div class="dcmd-service-pool-node-index">

    <input type="text" name="app_id" value="<?php echo $app_id; ?>" style="display:none">
    
    <?php echo "选择服务:</a>";
     echo "<br>";
     foreach($service as $item) {
        echo "<input  class='checkItems'  name='SvrName' type=\"radio\" value='$item' />$item";
        echo "<br>";
     }
     
     ?>
    <div>
    <label>新建服务名称:</label><br>
    <input type="text" name="svr_name" value="">
    </div>
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

