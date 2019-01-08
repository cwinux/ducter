<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务脚本详情';
$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">
#Container{
    width:1000px;
    margin:0 auto;/*设置整个容器在浏览器中水平居中*/
    background:#CF3;
}
#Header{
    height:80px;
    background:#093;
}
#logo{
    padding-left:50px;
    padding-top:20px;
    padding-bottom:50px;
}
#Content{
    height:600px;
    /*此处对容器设置了高度，一般不建议对容器设置高度，一般使用overflow:auto;属性设置容器根据内容自适应高度，如果不指定高度或不设置自适应高度，容器将默认为1个字符高度，容器下方的布局
元素（footer）设置margin-top:属性将无效*/
    margin-top:20px;/*此处讲解margin的用法，设置content与上面header元素之间的距离*/
    background:#0FF;

}
#Content-Left{
    height:float;
    width:48%;
//    margin:20px;/*设置元素跟其他元素的距离为20像素*/
    float:left;/*设置浮动，实现多列效果，div+Css布局中很重要的*/
    background:rgba(255, 255, 255, 0.5);
}
#Content-Main{
    height:float;
    width:48%;
    margin-left:20px;/*设置元素跟其他元素的距离为20像素*/
    float:right;/*设置浮动，实现多列效果，div+Css布局中很重要的*/
    background:#FFFFFF;
}

#Td-Left{
    height:float;
    width:200px;
//    margin:20px;/*设置元素跟其他元素的距离为20像素*/
    float:left;/*设置浮动，实现多列效果，div+Css布局中很重要的*/
background:#F0F0F0;
}
#Td-Right{
    height:float;
    width:80px;
    margin-left:400px;/*设置元素跟其他元素的距离为20像素*/
    float:left;/*设置浮动，实现多列效果，div+Css布局中很重要的*/
    background:#F0F0F0;
}
/*注：Content-Left和Content-Main元素是Content元素的子元素，两个元素使用了float:left;设置成两列，这个两个元素的宽度和这个两个元素设置的padding、margin的和一定不能大于父层Content元素>的宽度，否则设置列将失败*/
#Footer{
    height:40px;
    background:#90C;
    margin-top:20px;
}
.Clear{
    clear:both;
}
.tab{border-top:1px solid #000;border-left:1px solid #000;text-align:left}
.tab td{border-bottom:1px solid #000;border-left:1px solid #000;background:#F0F0F0}
</style>

<div class="dcmd-service-pool-audit-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    if( Yii::$app->getSession()->hasFlash('success') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success', //这里是提示框的class
            ],
            'body' => Yii::$app->getSession()->getFlash('success'), //消息体
        ]);
    }
    if( Yii::$app->getSession()->hasFlash('error') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
            ],
            'body' => "<font color=red>".Yii::$app->getSession()->getFlash('error')."</font>",
        ]);
    }
    ?>
<div>
<div>
<div id="Content-Left" style="height: 700px;overflow-x:auto;overflow-y:auto; background-color: #000; color: #FFF; padding: 10px 3px 10px 10px;">
<p>已保存脚本:</p>
 </br>
 <?php echo $result; ?>
</div>

<div id="Content-Main" style="height: 700px; overflow-x:auto;overflow-y:auto;background-color: #000; color: #FFF; padding: 10px 3px 10px 10px;">
<p>线上脚本:</p>
 </br>
 <?php echo $result_online; ?>
</div>

</div>
</div>

<div id="shellButton"><input type="button" value="提交审批" class='btn btn-primary' onclick="add_submit()"></input>&nbsp;&nbsp;<input type="button" value="删除" class='btn btn-success' onclick="delete_save()"></input></div>
</div>
<script>
function add_submit() {
  var id = <?php echo $id;?>;
  var options=$("#uploadform-pool_group option:selected");
  url = "?r=dcmd-service/submit-audit";
  $.get(url,{id:id}, function(data){
    alert(data);
  });
}

function delete_save() {
  var id = <?php echo $id;?>;
  url = "?r=dcmd-service/delete-draft";
  $.get(url,{id:id}, function(data){
    alert(data);
  });
  document.getElementById("saveButton").click(); 
}
</script>
