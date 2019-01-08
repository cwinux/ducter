<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php
use yii\grid\GridView;
use yii\helpers\Url;
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
<form id="w0" action="/index.php?r=dcmd-network/subnet-delete-all" method="post">
<div class="dcmd-network-view" id="dcmd-network">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idc:text:IDC',
            'segment:text:网段',
            'type:text:网络类型',
            'comment:text:备注',
        ],
    ]) ?>
</div>
<div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'table table-striped table-bordered', 'id'=>'testTable'],
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'app_name', 'label'=>'集群名称','enableSorting'=>false),
            array('attribute'=>'host_segment', 'label'=>'宿主机ip段', 'enableSorting'=>false),
            array('attribute'=>'vm_segment', 'label'=>'虚拟机ip段', 'enableSorting'=>false),
            array('attribute'=>'gateway', 'label'=>'网关', 'enableSorting'=>false),
            array('attribute'=>'vlan', 'label'=>'vlan', 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn', 'urlCreator'=>function($action, $model, $key, $index) {if ("update" == $action) return Url::to(['dcmd-network/ips','id'=>$model['id']]);else if("delete" == $action) return Url::to(['dcmd-network/subnet-update','id'=>$model['id']]);else return Url::to(['dcmd-network/subnet-delete','id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
    <p>
        <input type="button" class="btn btn-sucess" ="display:none" style="background:#00BB00" value="添加行" onclick="addRow();"/>
        <input type="button" class="btn btn-sucess" ="display:none" style="background:#00BB00" value="保存" onclick="saveRow();"/>
        <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
    </p>
</div>
<script type="text/javascript">
  var rowCount = 0;
  var colCount = 2;
  function addRow(){
    rowCount++;
    var rowTemplate = '<tr class="tr_add"><td><input type="checkbox"/></td><td class="cl1" name="cl1"><input type="text"/></td><td class="cl1" name="cl2"><input type="text"/></td><td class="cl1" name="cl3"><input type="text"/></td><td class="cl1" name="cl4"><input type="text"/></td><td class="cl1" name="cl5"><input type="text"/></td></tr>';
    $('#testTable tbody').append(rowTemplate);
  }
  function saveRow(){
    url="/index.php?r=dcmd-network/addsegment";
    $("#testTable tr").each(function(){
      var tdArr = $(this).children();
      if($(this).hasClass("tr_add")){
        var app_name = tdArr.eq(1).find("input").val();
        var host_segment = tdArr.eq(2).find("input").val();
        var vm_segment = tdArr.eq(3).find("input").val();
        var gateway = tdArr.eq(4).find("input").val();
        var vlan = tdArr.eq(5).find("input").val();
        var id = (document.title).split(":");
        var idc_id = id[1];
        $.get(url,{idc_id:idc_id,app_name:app_name,host_segment:host_segment,vm_segment:vm_segment,gateway:gateway,vlan:vlan},function(data){
          data = $.parseJSON(data);
          alert(data.data);
          window.location.reload();
        });
      }
    });
  }
</script>
