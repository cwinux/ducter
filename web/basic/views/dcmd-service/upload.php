<?php
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<div style="width:200px">
<?= $form->field($model, 'pool_group')->dropDownlist($pool_group,['prompt'=>""])->label('服务池组') ?>
</div>
<?= $form->field($model, 'file')->fileInput() ?>

<button>保存</button>

<?php ActiveForm::end() ?>
