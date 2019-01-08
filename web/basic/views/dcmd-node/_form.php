<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-node-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => 32, "$disabled"=>"true", 'onblur' => "javascript:getAgentHostName()"])->label('服务器IP') ?>

    <?= $form->field($model, 'ngroup_id')->dropDownList($node_group)->label('设备池子') ?>

    <?= $form->field($model, 'host')->textInput(['maxlength' => 128])->label('主机名') ?>

    <?= $form->field($model, 'public_ip')->textInput(['maxlength' => 128])->label('公网IP') ?>

    <?= $form->field($model, 'bend_ip')->textInput(['maxlength' => 128])->label('带外IP') ?>

    <?= $form->field($model, 'did')->textInput(['maxlength' => 128])->label('设备序列号') ?>

    <?= $form->field($model, 'sid')->textInput(['maxlength' => 128])->label('资产序列号') ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
var getAgentHostName = function () {
  ip=$('#dcmdnode-ip').val();
  $.post("?r=dcmd-node/get-agent-hostname", { "ip":ip }, function (data, status) {
    status == "success" ? function () {
      var dataO = jQuery.parseJSON(data); 
      $('#dcmdnode-host').val(dataO.hostname);
    } () : "";
  }, "text");
};
</script>

