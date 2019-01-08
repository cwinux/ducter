<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '搜索';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
.search-result {
    position: absolute;
    -webkit-transform: translate(-50%, -50%);
    -moz-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    top:47%;
    left:65%;
}

.result {
    height:float;
    width:70%;
    margin-left:60px;/*设置元素跟其他元素的距离为20像素*/
    float:right;/*设置浮动，实现多列效果，div+Css布局中很重要的*/
    background:rgba(255, 255, 255, 0.5);
}

.search-wrapper {
    position: absolute;
    -webkit-transform: translate(-50%, -50%);
    -moz-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    top:25%;
    left:8%;
}

.search-wrapper.active {}

.search-wrapper .input-holder {
    height: 50px;
    width:50px;
}
</style>
<div class="dcmd-fault-index">

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
<form>
      <div class="search-wrapper active">
        <input type="radio" name="myRadio" value="ip"> IP
        <input type="radio" name="myRadio" value="sn"> SN
        <input type="radio" name="myRadio" value="net"> NET
        <div class="input-holder"><textarea id="keyWord" class="search-input" type="textarea" rows="18" cols="35" placeholder="请输入关键词"></textarea><input type="button" style="font-family:宋体; font-size:13px; background:#2894FF; color: white; border-radius:5px; height:30px; width:70px" onclick="search();" value="Search"></input></div>
      </div>
        <div class="result" id="result"></div>
</form>
<script type="text/javascript">
  function search(){
    var rad = document.getElementsByName("myRadio");
    var type = "ip";
    for(var i=0;i<rad.length;i++)
    {
       if(rad[i].checked)
         var type = rad[i].value;
    }
    keyword = document.getElementById("keyWord").value;
    _html = "您搜索的结果如下:";
    if(!keyword.length){
      alert("关键词不能为空。");
    }
    else if(type != "net"){
      url = "/index.php?r=dcmd-search/vm-search";
      $.get(url,{type:type,keyword:keyword},function(data,status){
        if(status == "success") {
          data = $.parseJSON(data);
          msg = '<table id="table" class="table" style="font-family:'+'宋体'+'; font-size:15px;"><tbody>';
          msg += '<tr><th>服务器IP</th><th>虚拟机IP</th><th>虚拟机SN</th><th>状态</th><th>规格</th><th>系统</th><th>业务名称</th><th>模块名称</th><th>联系人</th></tr>';
          for(var i=0; i<data.length; i++) {
            if(data[i]["state"] == 0) {
              state = "未使用";
            }
            else if (data[i]["state"] == 1) {
              state = "使用中";
            }
            else{
              state = "可回收";
            }
            msg += '<tr><td>'+data[i]["host_ip"]+'</td><td>'+data[i]["vm_ip"]+'</td><td>'+data[i]["vm_uuid"]+'</td><td>'+state+'</td><td>'+data[i]["flavor_name"]+'</td><td>'+data[i]["os"]+'</td><td>'+data[i]["business"]+'</td><td>'+data[i]["module"]+'</td><td>'+data[i]["contacts"]+'</td></tr>'; 
          }
          msg += '</tbody></table>';
          _html += "<b>" + msg + "</b>";
          document.getElementById('result').innerHTML=_html;
        }
        else {
          alert("查询错误!")
        }
      });
    }
    else {
      url = "/index.php?r=dcmd-search/net-search";
      $.get(url,{network:keyword},function(data,status){
        if(status == "success") {
          data = $.parseJSON(data);
          msg = '<table id="table" class="table" style="font-family:'+'宋体'+'; font-size:15px;"><tbody>';
          msg += '<tr><th>集群</th><th>IP</th><th>网段</th></tr>';
          for(var i=0; i<data.length; i++) {
            msg += '<tr><td>'+data[i]["app"]+'</td><td>'+data[i]["ip"]+'</td><td>'+data[i]["net"]+'</td></tr>'; 
          }
          msg += '</tbody></table>';
          _html += "<b>" + msg + "</b>";
          document.getElementById('result').innerHTML=_html;
        }
        else {
          alert("查询错误!")
        }
      }); 
  }
  }   

  function searchToggle(obj, evt){
    alert("test");
    var container = $(obj).closest('.search-wrapper');
    
    if(!container.hasClass('active')){
      container.addClass('active');
      evt.preventDefault();
    }
    else if(container.hasClass('active') && $(obj).closest('.input-holder').length == 0){
      container.removeClass('active');
      // clear input
      container.find('.search-input').val('');
      // clear and hide result container when we press close
      container.find('.result-container').fadeOut(100, function(){$(this).empty();});
    }
  }
  
  function submitFn(obj, evt){
    alert("dd")
    value = $(obj).find('.search-input').val().trim();
    alert(value);
    _html = "您搜索的结果如下:";
    if(!value.length){
      _html = "关键词不能为空。";
      $(obj).find('.result-container').html('<span>'+_html+'</span>');
      $(obj).find('.result').html('<span> </span>');
    }
    else{
      $(obj).find('.result-container').html('<span> </span>');
      url = "/index.php?r=dcmd-search/net-search";
      $.get(url,{network:value},function(data,status){
        if(status == "success") {
          data = $.parseJSON(data);
          msg = '<table id="table" class="table" style="font-family:'+'宋体'+'; font-size:15px;"><tbody>';
          msg += '<tr><th>集群</th><th>IP</th><th>网段</th></tr>';
          for(var i=0; i<data.length; i++) {
            msg += '<tr><td>'+data[i]["app"]+'</td><td>'+data[i]["ip"]+'</td><td>'+data[i]["net"]+'</td></tr>'; 
          }
          msg += '</tbody></table>';
          _html += "<b>" + msg + "</b>";
          $(obj).find('.result').html('<span>' + _html + '</span>');
        }
        else {
          alert("查询错误!")
        }
      });
    }
    $(obj).find('.result-container').fadeIn(100);
    
    evt.preventDefault();
  }
</script>
