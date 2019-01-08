<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = "网段信息:".$model->id;
$this->params['breadcrumbs'][] = ['label' => '网段管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

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
<script src="./jquery-2.1.1.min.js"></script>
<script>
  var d = function(o)  {
    return document.getElementById(o);
  }

  function showDiv(parm){
    d('dcmd-used-ips').style.display = 'none';    
    d('dcmd-unuse-ips').style.display='none';   
    d(parm).style.display = '';    
   
    if(parm == 'dcmd-unuse-ips') getUnuseIps(); 
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }
</script>
<div class="dcmd-network-view" id="dcmd-network">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'app_name:text:集群名称',
            'host_segment:text:物理机网段',
            'vm_segment:text:虚拟机网段',
            'gateway:text:网关',
            'vlan:text:vlan'
        ],
    ]) ?>
</div>
<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-used-ips-l" onclick="showDiv('dcmd-used-ips');this.className='codeDemomouseOnMenu'">使用中IP</li>
  <li class="codeDemomouseOutMenu" id="dcmd-unuse-ips-l" onclick="showDiv('dcmd-unuse-ips');this.className='codeDemomouseOnMenu'">未使用IP</li>
</ul>
<div class="dcmd-used-ips-view" id="dcmd-used-ips">
    <?php echo $ips ?>
</div>
<div class="dcmd-unuse-ips-view" id="dcmd-unuse-ips">
</div>
<p>
    <input type=button id="btn1" value="首页" >
    <input type=button id="btn2" value="上一页">
    <input type=button id="btn3" value="下一页" >
    <input type=button id="btn4" value="尾页" >
</p>
<script>
 var pageSize = 15;//每页显示的记录条数
 var curPage=0;
 var lastPage;
 var direct=0;
 var len;
 var page;
 $(document).ready(function(){    
     len =$("#table tr").length;
        page=len % pageSize==0 ? len/pageSize : Math.floor(len/pageSize)+1;//根据记录条数，计算页数
      //  alert("page==="+page);
        curPage=1;
        displayPage(1);//显示第一页
        $("#btn1").click(function(){
         curPage=1;
         displayPage();
     });
        $("#btn2").click(function(){
         direct=-1;
         displayPage();
     });
        $("#btn3").click(function(){
         direct=1;
         displayPage();
     });
        $("#btn4").click(function(){
         curPage=page;
         displayPage();
     });
 });
 
 function displayPage(){
  if((curPage <=1 && direct==-1) || (curPage >= page && direct==1)){
   direct=0;
   alert("已经是第一页或者最后一页了");
   return;
  }
  lastPage = curPage;
  curPage = (curPage + direct + len) % len;
     var begin=(curPage-1)*pageSize;//起始记录号
     var end = begin + pageSize;
  if(end > len ) end=len;
     $("#table tr").hide();
     $("#table tr").each(function(i){
         if((i>=begin && i<end) || i==0)//显示第page页的记录
             $(this).show();
     });

 }
var getUnuseIps = function() {
   segment="<?php echo $model->vm_segment; ?>";
   $.get("?r=dcmd-network/unuse-ips", { "segment":segment}, function (data, status) {
       status == "success" ? function () {
         $('#dcmd-unuse-ips').html(data);
       } () : "";
   });
}
</script>
