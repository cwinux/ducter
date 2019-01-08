<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */

$this->title = "资源表定义";
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

<div class="dcmd-app-form">

    <?php $form = ActiveForm::begin(['options' => ['name' => 'app_form'],]); ?>

    <?= $form->field($model, 'res_type')->dropDownList($type)->label("类型") ?>

    <?= $form->field($model, 'colum_name')->textInput(['maxlength' => 16])->label('列名') ?>

    <?= $form->field($model, 'display_name')->textInput(['maxlength' => 255])->label('显示名称') ?>

    <?= $form->field($model, 'display_order')->dropDownList(['1'])->label('显示顺序') ?>

    <?= $form->field($model, 'display_list')->dropDownList(array('0'=>'否','1'=>'是'))->label("是否主页显示") ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
//var counts;
counts=0;
arr = new Array("Mysql","Cbase","LVS","Redis");	
function Myselect(){
  sel.options = 0;
  var i;
  i = 0;
  for (var key in resType) {
    sel.options[i] = new Option(resType[key],key);
    i++;
  }
}

function test() {
  url = "/index.php?r=dcmd-resource/get-resource";
  var options=$("#sel option:selected");
  $.get(url,{type:options.val()},function(data){
    $('#resource-view').html(data);
  });
}

function show_modal(){
  $('#addModal').modal('show');
}

function create(){
  sel.options = 0;
  var i;
  i = 0;
  for (var key in resType) {
    type_select.options[i] = new Option(resType[key],key);
    i++;
  }
}

function create_form(){
  url = "/index.php?r=dcmd-resource/add-resource";
  var options=$("#type_select option:selected");
  $.get(url,{type:options.val()},function(data){
    $('#add_form').html(data);
  });
}

</script>

<script src="./jquery-2.1.1.min.js">
  <?php 
    if(!empty($show_div)) 
     echo "document.getElementById('". $show_div."-l').click()";
  ?>
</script>
<script>
  $(document).on('mouseover','.sourceID',function(e){
    tip="<p class='tip'><font size='2'>DETAIL</font></p>";
    $(".sourceID").append(tip);
    $(".tip").css({"top":(e.pageY-30)+"px","left":(e.pageX-35)+"px","position":"absolute", "background": "gray", "box-shadow": "-2px -2px 0 -1px #c4c4c4", "box-shadow": "0 2px 8px rgba(0,0,0,.3)", "color": "white"}).show("fast");
  });
  $(document).on('mouseleave','.sourceID',function(e){
    $(".tip").remove();
  }); 
</script>


