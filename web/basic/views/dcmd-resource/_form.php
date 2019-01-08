<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-resource-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_id')->textInput(['maxlength' => 128, $model->app_id])->label('产品名称') ?>

    <?= $form->field($model, 'svr_id')->dropDownlist($ser_array)->label('服务名称') ?>

    <?= $form->field($model, 'svr_pool_id')->dropDownlist($ser_pool_array)->label('服务池') ?>

    <?= $form->field($model, 'res_type')->dropDownlist(['MySQL','Cbase','DNS','Mq','LVS','SLB','Mongo','Mcluster','Gluster','Oracle'])->label('资源类型') ?>

    <?= $form->field($model, 'res_id',['inputOptions'=>['placeholder'=>'点击选择资源','class'=>'form-control','onclick'=>'addFault()','style'=>'font-size:12px']])->label('资源名称') ?>

    <?= $form->field($model, 'is_own')->dropDownlist(array(0=>"否", 1=>"是"))->label('是否为拥有者') ?>
  
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h5 class="modal-title" value="">选择资源</h5>
                </div>
     <div class="modal-body" id="addItems">
	<div style="width:90%; margin:0 auto;">
          <div class="dcmd-data-view" id="dcmd-data">
          <?= GridView::widget([
                  'dataProvider' => $dnsProvider,
                  'filterModel' => $dnsModel,
                  'tableOptions' => ['class' => 'table table-striped table-bordered', 'id'=>'resList'],
                  'columns' => [
                      ['class' => 'yii\grid\CheckboxColumn'],
                      array('attribute'=>'res_id','label'=>'唯一行号'),
                      array('attribute'=>'res_name', 'label'=>'资源名称','enableSorting'=>false,),
                      array('attribute'=>'contact', 'label'=>'联系人','enableSorting'=>false,),
                  ],
              ]); ?>
          </div>
        </div>
        <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" onclick="addInfo()">提交</button>
        </div>
      </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>
<script src="./jquery-2.1.1.min.js"></script>
<script>
  function addFault() {
    $('#addModal').modal('show');
  }

//  function test() {
//    changeURL(url);
//    return false;
//  }

$('#addItems').delegate('a','click',function(event){
    event.preventDefault();
    url = $(this).attr('href');
    changeURL(url);
    //test(url);
});

//$(document).ready(function(){$("#addItems a").click(function( event ) {
//    event.preventDefault();
//    test();
//    changeURL();
//});
//});
</script>
    <script type="text/javascript">
    var changeURL = function(url){
        url = url;
        load = false;
        var content = document.getElementById("addItems");   
        var ajax = new XMLHttpRequest();
        ajax.withCredentials = true;

        // 调用数据回掉函数
        var ajaxCallback = function(){
            if(ajax.readyState == 4){
                load = false;
//                result = eval('('+ajax.responseText+')');
                //var result = $.parseHTML(ajax.responseText); 
                //alert(result);
                var re = ajax.responseText;
                var htmlTextText = $('p').html(re);
                var es = $('p').html(re).eq(1).find("#addItems");
                content.innerHTML = es.html();
            }
        };
 
        // 点击事件
       // document.getElementById('next').onclick = function(event){
            if(!load){
                load = true;
//                content.innerHTML = '加载中数据中...(注意看数据返回后url改变)';
//                url = 'http://10.129.30.127'+url;
//                ajax.open('GET','http://10.129.30.127/index.php?r=dcmd-resource%2Fcreate&app_id=52&page=9', true);
                ajax.open('GET',url,true);
                ajax.onreadystatechange = ajaxCallback;
                ajax.send(url);
                return false;
            }
       // };
    };
// 
//    // 检测是否支持
//    try{
//        //监听事件
//        window.addEventListener('DOMContentLoaded', changeURL, false);
//    }catch(e){
//        alert('浏览器不支持，请使用支持html5的浏览器'); 
//    }
// 
    </script>
