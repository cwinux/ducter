<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-pool-form">

    <?php $form = ActiveForm::begin(['options' => ['name' => 'service_pool'],]); ?>

    <?= $form->field($model, 'svr_pool')->textInput(['maxlength' => 128, $model->svr_pool ? "disabled" : "" => true])->label('服务池子名称*') ?>

    <?= $form->field($model, 'pool_group')->dropDownList($pool_group,['prompt'=>""])->label('服务池组') ?>

    <?= $form->field($model, 'svr_id')->textInput(["style"=>"display:none"])->label('服务',["style"=>"display:none"]) ?>

    <?= $form->field($model, 'app_id')->textInput(["style"=>"display:none"])->label('产品',["style"=>"display:none"]) ?>

    <?= $form->field($model, 'repo')->textInput(['maxlength' => 512])->label('版本库地址*') ?>

    <?= $form->field($model, 'env_ver')->dropDownList($version,['prompt'=>"",'onchange'=>'verchange()'])->label('环境版本') ?>

    <?= $form->field($model, 'env_md5')->textInput(['maxlength' => 64])->label('环境md5') ?>

    <?= $form->field($model, 'env_passwd')->textInput(['maxlength' => 128])->label('环境passwd') ?>

    <?= $form->field($model, 'image_url')->textInput(['maxlength' => 256])->label('镜像地址') ?>

    <?= $form->field($model, 'image_name')->textInput(['maxlength' => 64,'placeholder'=>'image:tag'])->label('镜像名称') ?>

    <?= $form->field($model, 'image_user')->textInput(['maxlength' => 64])->label('harbor用户名') ?>

    <?= $form->field($model, 'image_passwd')->passwordInput()->label('密码') ?>

    <?= $form->field($model, 'svr_mem')->textInput(['maxlength' => 16])->label('容器内存/m') ?>

    <?= $form->field($model, 'svr_cpu')->textInput(['maxlength' => 16])->label('容器CPU') ?>

    <?= $form->field($model, 'svr_net')->textInput(['maxlength' => 16])->label('容器Net/m') ?>

    <?= $form->field($model, 'svr_io')->textInput(['maxlength' => 16])->label('容器IO') ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
  function verchange(){
    var options=$("#dcmdservicepool-env_ver option:selected");
    var svr_pool_id = <?php echo $model['svr_pool_id'];?>;
    var envmd5=$("#dcmdservicepool-env_md5");
    var envpass = $("#dcmdservicepool-env_passwd");
    url = "/index.php?r=dcmd-service-pool/getversion";
    $.get(url,{version:options.val(),svr_pool_id:svr_pool_id},function(data){
      data = $.parseJSON(data);
//      alert(data);
      $('#dcmdservicepool-env_md5').val(data['md5']);
      $('#dcmdservicepool-env_passwd').val(data['passwd']);
    });
  }
</script>
