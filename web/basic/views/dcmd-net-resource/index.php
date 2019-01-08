<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '网络资源';
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
    /*此处对容器设置了高度，一般不建议对容器设置高度，一般使用overflow:auto;属性设置容器根据内容自适应高度，如果不指定高度或不设置自适应高度，容器将默认为1个字符高度，容器下方的布局元素（footer）设置margin-top:属性将无效*/
    margin-top:20px;/*此处讲解margin的用法，设置content与上面header元素之间的距离*/
    background:#0FF;
     
}
#Content-Left{
    height:float;
    width:=15%;
//    margin:20px;/*设置元素跟其他元素的距离为20像素*/
    float:left;/*设置浮动，实现多列效果，div+Css布局中很重要的*/
    background:rgba(255, 255, 255, 0.5);
}
#Content-Main{
    height:float;
    width:85%;
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
/*注：Content-Left和Content-Main元素是Content元素的子元素，两个元素使用了float:left;设置成两列，这个两个元素的宽度和这个两个元素设置的padding、margin的和一定不能大于父层Content元素的宽度，否则设置列将失败*/
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
<form id="w0" action="/index.php?r=dcmd-network/delete-all" method="post">
<div class="dcmd-network-index">

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
</div>
<div>
   <div id="Content-Left">
     <div id="contents">
       IDC网段
     </div>
   </div>
   <div id="Content-Main" style="text-indent:1cm">
      <table class="tab">
         <tr><th height="50px" bgcolor="#D0D0D0" width="970px">Segment Info</th></tr>
         <tr><td>
           <div id="Td-Left">
             <div id="segment_info">
               <p></p>
               <p>网段:</p>
               <p>网关:</p>
               <p>掩码:</p>
             </div>
           </div>
           <div id="Td-Right">
             <div id="descDiv" style="z-index: 5; color: blue; position: absolute;margin-left:90px; margin-top:90px;display: block;"></div>
             <canvas id="can1" width="300" height="200"></canvas>
           </div>
         </td></tr>
      </table>
      <p></p>
      <table class="tab">
         <tr><th height="50px" bgcolor="#D0D0D0" width="970px">Segment List</th></tr>
         <tr><td>
           <div id="segment_list">
           </div>
         </td></tr>
      </table>
   </div>
</div>
<script src="./jquery-2.1.1.min.js"></script>
<script>
$(document).ready(function(){ 
  url = "/index.php?r=dcmd-net-resource/net-list";
  $.get(url,function(data){
    if(data) {
      data=$.parseJSON(data);
    }else {
      data = [];
    }
    var content = "";
    for(var i=0; i<data.length; i++){
      var index = data[i].split(".");
      var network = index[0] + "." + index[1] + ".0.0/16";
      content = content + '<button type="button" style="border:none;background:rgba(255, 255, 255, 0.5);display:block;color:blue" value="12" onclick="getSegments(this)">' + network + '</button>';
    }
    $(contents).html(content);
  });
}) 

function getSegments(x) {
  ret_msg = '<table id="table" class="table table-striped table-bordered"><tbody>';
  ret_msg = ret_msg + "<th>ID</th><th>网段</th><th>集群</th><th>vlan</th>";
  url = "/index.php?r=dcmd-net-resource/segment-list";
  var segment = $(x).text();
  var content = "<p></p>";
  var seg = segment.split(".");
  content = content + "<p>网段: " + segment + "</p>";
  content = content + "<p>网关: " + seg[0] + "." + seg[1] + "." + "0.1";
  content = content + "<p>掩码: 255.255.0.0";
  $(segment_info).html(content);
  $.get(url,{net:segment}, function(data){
    if(data) {
      data=$.parseJSON(data);
    }else {
      data = [];
    }
    var net2vlan = '<?php echo $model->getNet(); ?>';
    var id2name = '<?php echo $model->getName(); ?>';
    n2v = JSON.parse(net2vlan);
    i2n = JSON.parse(id2name);
    var net_count = 0;
    for(var i=0; i<data.length; i++) {
      var segm = data[i]["network"];
      var app_id = data[i]['app_id'];
      tr = "<tr><td>"+data[i]["id"]+"</td><td>"+data[i]["network"]+"</td><td>"+ i2n[app_id]  +"</td><td>"+ n2v[segm] +"</td></tr>";
      if((data[i]["network"]).indexOf("/") >= 0) {
        var subnet = (data[i]["network"]).split("/")[1];
        var net_count = Math.pow(2,24-parseInt(subnet)) + net_count;
      }
      ret_msg = ret_msg + tr;
    }
    ret_msg = ret_msg + "</tbody></table>";
    descDiv.innerHTML = (String(net_count/256*100)).substring(0,4)+"%";
    $(segment_list).html(ret_msg);
    var canv=document.getElementById("can1");
    var ctx=canv.getContext("2d");
    var num=[net_count,256-net_count];
    var col=["#F00","#EFFFD7"]
    var j=0,j1=1.5*Math.PI,j2=0,jiao=0;
    var sun=0;
    for(var i=0;i<num.length;i++){
      sun=sun+num[i];
    }
    for(var i=0;i<num.length;i++){
      j=2*Math.PI*num[i]/sun;
      j2=j1+j;
      ctx.beginPath();
      ctx.fillStyle=col[i];
      ctx.arc(100,100,80,j1,j2,false);
      ctx.lineTo(100,100);
    
      ctx.closePath();
    
      ctx.fill();
      jiao=j1+j/2;
      ctx.font="20px Arial";
      ctx.fillStyle="#000";
      j1=j1+j;
    }
  });
};  
</script>
</form>
