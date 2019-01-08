<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdApp */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="dcmd-app-form">

    <?php $form = ActiveForm::begin(['options' => ['name' => 'app_form'],]); ?>

    <?= $form->field($model, 'app_id')->textInput(['maxlength' => 64, 'value' => $model->getAppName($app_id), 'readonly' => true])->label('产品名称') ?>

    <?= $form->field($model, 'network')->textInput(['maxlength' => 64])->label('网段') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div class="ant-col-24" style="padding-left: 5px; padding-right: 5px;"><hr></div>
</div>
<div class="tips">
  <span style="display: inline-block; width: 20px; text-align: right;"></span>
  <span style="width: 20px; height: 20px; background-color: rgb(135, 208, 104); display: inline-block; margin-left: 8px; border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;"></span>
  <span style="margin-left: 8px; border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;">可用</span>
  <span style="width: 20px; height: 20px; background-color: rgb(255, 85, 0); display: inline-block; margin-left: 18px; border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;"></span>
  <span style="margin-left: 8px; border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;">被占用</span>
  <span style="width: 20px; height: 20px; background-color: rgb(217, 217, 217); display: inline-block; margin-left: 18px; border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;"></span>
  <span style="margin-left: 8px; border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;">不可用</span>
  <span style="width: 20px; height: 20px; background-color: rgb(0,0,255); display: inline-block; margin-left: 18px; border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;"></span>
  <span style="margin-left: 8px; border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;">已选中</span>
</div>
<div class="segment">
</div>
<div style="position: absolute; top: 0px; left: 0px; width: 100%;"><div data-reactroot=""><div class="ant-tooltip  ant-tooltip-placement-top  ant-tooltip-hidden" style="left: 571px; top: 996px; transform-origin: 50% 51px;"><div class="ant-tooltip-content"><div class="ant-tooltip-arrow"></div><div class="ant-tooltip-inner"></div></div></div></div></div>
<script src="./jquery-2.1.1.min.js"></script>
<script type="text/javascript">
  function loadfun(x){
    var net = "<?php echo $net; ?>";
    var find_array = net.split(".");
    var find_str = find_array[0] + "." + find_array[1];
    if($(x).css('background-color') == "rgb(255, 85, 0)") {
      alert("已占用，不能选择!");
    }else {
      var value = find_str+"."+$(x).attr("value")+".0/24";
      var textbox = document.getElementById("dcmdappsegment-network");
      if(chosed.contains($(x).attr("value"))){
        textbox.value = "";
        chosed.removeByValue($(x).attr("value"));
        $(x).css("background-color","rgb(135, 208, 104)");
      }else {
        textbox.value = value;
        chosed.push($(x).attr("value"));
        $(x).css("background-color","blue");
      }
      if((chosed.max()-chosed.min()+1) == chosed.length){
        if(num = power2(chosed.length)){
          var segment = find_str+"." +chosed.min() + ".0/" + (24-num);
          textbox.value = segment;
        };
      }  
    }
  }

  function power2(x,n) {
    n = n || 0;
    if(x === 1) {
      return n;
    }
    if( x < 1) {
      return false;
    }
    return power2(x / 2, n + 1);
  }

  Array.prototype.contains = function (val) {
    for (i in this) {
      if (this[i] == val) return true;
    }
    return false;
  }
  
  Array.prototype.removeByValue = function(val) {
    for(var i=0; i<this.length; i++) {
      if(this[i] == val) {
        this.splice(i, 1);
        break;
      }
    }
  }

  Array.prototype.min = function() {
    var min = parseInt(this[0]);
    var len = this.length;
    for (var i = 1; i < len; i++){ 
      if (parseInt(this[i]) < min){
        min = parseInt(this[i]); 
      } 
    }
    return min;
  }
  //最大值
  Array.prototype.max = function() { 
    var max = parseInt(this[0]);
    var len = this.length; 
      for (var i = 1; i < len; i++){ 
        if (parseInt(this[i]) > max) { 
          max = parseInt(this[i]); 
        } 
    } 
    return max;
  }

  var chosed = new Array();
  var net = "<?php echo $net; ?>";
  window.onload = function(){
    url = "/index.php?r=dcmd-app/get-segments";
    $.get(url,{net:net}, function(data){
      if(data){
        data = $.parseJSON(data);
      }else {
        data = [];
      }
      var find_array = net.split(".");
      var find_str = find_array[0] + "." + find_array[1];
      for(var i=0; i<16; i++){
          for(var j=0; j<16; j++){
            var find_string = find_str + "." + (i*16+j) + ".0/24";
            if(j==0){
              $('.segment').append('<span style="display: inline-block; width: 20px; text-align: right;">'+(i*16)+'</span>');
            }
            if(data.toString().indexOf(find_string) > -1){
              $('.segment').append('<span style="width: 20px; height: 20px; background-color: rgb(255, 85, 0); display: inline-block; margin-left: 8px; border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;" value='+(i*16+j)+' onclick=loadfun(this)></span>');
            }else{
              $('.segment').append('<span style="width: 20px; height: 20px; background-color: rgb(135, 208, 104); display: inline-block; margin-left: 8px; border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;" value='+(i*16+j)+' onclick=loadfun(this)></span>');
            }
          }
          $('.segment').append('<br/>');
      }
      });
  }

  $(document).on('mouseover','.segment span',function(e){
    var index = $(this).attr("value");
    var net = "<?php echo $net; ?>";
    var find_array = net.split(".");
    var find_str = find_array[0] + "." + find_array[1];
    tip="<p class='tip'><font size='2'>"+find_str+"."+index+".0/24</font></p>";
    $(".segment").append(tip);
    $(".tip").css({"top":(e.pageY-30)+"px","left":(e.pageX-50)+"px","position":"absolute", "background": "gray", "box-shadow": "-2px -2px 0 -1px #c4c4c4", "box-shadow": "0 2px 8px rgba(0,0,0,.3)", "color": "white"}).show("fast");
  });
  
  $(document).on('mouseleave','.segment span',function(e){
    $(".tip").remove();
  });
</script>
