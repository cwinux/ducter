<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '选择服务服务池';
$this->params['breadcrumbs'][] = $this->title;
?>

<form id="w0" action="<?php echo Url::to(['select-service-pool-node',]); ?>" method="post">
<div class="dcmd-service-pool-node-index">

    <input type="text" name="app_id" value="<?php echo $app_id; ?>" style="display:none">
    <input type="text" name="svr_id" value="<?php echo $svr_id; ?>" style="display:none">
    <?php echo "选择服务池:</a>";
     echo "<br>";
     foreach($service_pool as $item) {
        $svr_name = $item['svr'];
        echo "<div><a href=\"javascript:void(0);\" onclick=\"showServicePoolNode('".$item['svr']."Pool')\" style=\"margin:0px 2px 0px 2px\">";
        echo "$svr_name</a></div>";
        echo "<div id='".$item['svr']."Pool'  style=\"display:none\">";
        if($item['svr']) {
          foreach($item['pool'] as $it) {
            echo "<input  class='checkItems' style='margin-left:10px;'  name='SvrPoolName' type=\"radio\" value='$svr_name:$it' />$it";
            echo "</br>";
          }
        }
        echo "</div>";
     }
     
     ?>
</br>
<div>
    <label>新建服务池名称:</label><br>
    <input type="text" name="svr_pool_name" value="">
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

