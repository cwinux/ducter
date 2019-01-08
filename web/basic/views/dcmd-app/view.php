<link href="/ducter/css/tabs.css" rel="stylesheet">
<script src="/ducter/d3/d3.min.js"></script>
<style>

.node {
  cursor: pointer;
}

.node circle {
  fill: #fff;
  stroke: steelblue;
  stroke-width: 1.5px;
}

.node text {
  font: 15px sans-serif;
}

.link {
  fill: none;
  stroke: #ccc;
  stroke-width: 1.5px;
}

</style>
<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdApp */

$this->title = $model->app_name;
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => ['index']];
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
<SCRIPT LANGUAGE="JavaScript">
<!--  
  var d = function(o)  {
    return document.getElementById(o);
  }
 
  function showDiv(parm){
    d('dcmd-app-info').style.display = 'none';    
    d('dcmd-app-img').style.display='none'; 
    d(parm).style.display = '';    
    
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }
//-->
</SCRIPT>
<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-app-info-l" onclick="showDiv('dcmd-app-info');this.className='codeDemomouseOnMenu'">产品信息</li>
</ul>

<div class="dcmd-app-view" id="dcmd-app-info">
<div class="dcmd-app-view" id="dcmd-app" style="background:#f1f1f1;padding:10px;margin-top:10px">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            array('attribute'=>'app_name', 'label'=>'产品名称'),
            array('attribute'=>'app_alias', 'label'=>'产品别名'),
            array('attribute'=>'app_type', 'label'=>'产品类型'),
            array('attribute'=>'comp_id', 'label'=>'所属公司','value'=>$model->getComp($model['comp_id'])),
            array('attribute'=>'sa_gid','label'=>'系统组', 'value'=>$model->userGroupName($model['sa_gid'])),
            array('attribute'=>'svr_gid', 'label'=>'业务组', 'value'=>$model->userGroupName($model['svr_gid'])),
            array('attribute'=>'depart_id', 'label'=>'部门', 'value'=>$model->department($model['depart_id'])),
            array('attribute'=>'is_self', 'label'=>'业务组可操作', 'value'=>$model['is_self'] ? '是':'否'),
            array('attribute'=>'comment', 'label'=>'说明', 'value'=>$model->comment($model['comment']), 'format'=>'html'),
        ],
    ]) ?>
    <p>
    <?= Html::a('更新', ['update', 'id' => $model->app_id], ['class' => 'btn btn-primary', ($model->is_self || Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
<p></p>
<div class="dcmd-service-view" id="dcmd-service" >
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'svr_name', 'label'=>'服务名称', 'enableSorting'=>false,'content'=>function($model, $key, $index, $column) { return Html::a($model['svr_name'], Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]),$options = ['target' => '_blank']);}),
            array('attribute'=>'svr_alias', 'label'=>'服务别名', 'enableSorting'=>false, 'content'=>function($model, $key, $index, $column) { return Html::a($model['svr_alias'], Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]), $options = ['target' => '_blank']);}),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-service/delete', 'id'=>$model['svr_id'], 'app_id'=>$model['app_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1 || $model->is_self) ? true : false ],
        ],
    ]); ?>
    <p>
       <?= Html::a('添加', ['dcmd-service/create', 'app_id' => $model->app_id], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $model->is_self) ? "" : "style"=>"display:none"]) ?>
       <?= Html::a('cp from', ['dcmd-service/select-service', 'app_id' => $model->app_id], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $model->is_self) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
   <div  style="background:#f1f1f1;padding:10px;margin-top:10px">
   <p style="font-size:15px;color:#0066CC">组件信息</p>
    <?= GridView::widget([
        'dataProvider' => $resProvider,
        'filterModel' => $resSearch,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'res_id','label'=>'资源ID','content' => function($model, $key, $index, $column) { return Html::a('<font class="sourceID">'.$model['res_id'], Url::to(['dcmd-resource/view', 'res_id'=>$model['res_id'], 'res_type'=>$model['res_type']]));}),
            array('attribute'=>'res_name', 'label'=>'资源名称', 'filter'=>false, 'enableSorting'=>false,),
            array('attribute'=>'svr_id', 'label'=>'服务名称', 'filter'=>false, 'enableSorting'=>false,'content' => function($model, $key, $index, $column) { return $model->getService($model['svr_id']);}),
            array('attribute'=>'svr_pool_id', 'label'=>'服务池名称', 'filter'=>false, 'enableSorting'=>false,'content' => function($model, $key, $index, $column) { return $model->getPool($model['svr_pool_id']);}),
            array('attribute'=>'res_type', 'label'=>'资源类型', 'filter'=>false, 'enableSorting'=>false,),
            array('attribute'=>'is_own', 'label'=>'是否所有者', 'filter'=>false, 'enableSorting'=>false,'content' => function($model, $key, $index, $column) { return $model['is_own'] ? '是':'否';}),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{update}{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-app-resource/del-res', 'id'=>$model['id']]);else return Url::to(['dcmd-app-resource/update', 'id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1 || $model->is_self) ? true : false ],
        ],
    ]); ?>
    <p>
       <?= Html::a('添加', ['dcmd-resource/create', 'app_id' => $model->app_id], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 || $model->is_self) ? "" : "style"=>"display:none"]) ?>
    </p>
   </div>

   <div>
    <?= GridView::widget([
        'dataProvider' => $atgProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'ngroup_id', 'label'=>'设备池', 'filter'=>false, 'enableSorting'=>false,'content' => function($model, $key, $index, $column) { return Html::a($model->getGroup($model['ngroup_id']), Url::to(['dcmd-node-group/view', 'id'=>$model['ngroup_id']]));}),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-app/del-ngroup', 'id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->sa == 1) ? true : false ],
        ],
    ]); ?>
    <p>
       <?= Html::a('添加', ['dcmd-app/add-group', 'app_id' => $model->app_id], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
   </div>

</div>
<div style="display:none; background:#f1f1f1;" id="dcmd-app-img"></div>
<script src="/ducter/d3/draw.js"></script>
<script>
var margin = {top: 5, right: 100, bottom: 10, left: 100},
    width = 960 - margin.right - margin.left,
    height = 800 - margin.top - margin.bottom;

var i = 0,
    duration = 750,
    root;

var tree = d3.layout.tree()
    .size([height, width]);

var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });

var svg = d3.select("#dcmd-app-img").append("svg")
    .attr("width", width + margin.right + margin.left)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

d3.json("index.php?r=dcmd-app/get-app-svr&id=<?php echo $model->app_id ?>", function(error, flare) {
  root = flare;
  root.x0 = height / 2;
  root.y0 = 0;

  function collapse(d) {
    if (d.children) {
      d._children = d.children;
      d._children.forEach(collapse);
      d.children = null;
    }
  }

  root.children.forEach(collapse);
  update(root);
});

d3.select(self.frameElement).style("height", "800px");

</script>
<script src="./jquery-2.1.1.min.js"></script>
<script>
  $(document).on('mouseover','.sourceID',function(e){
      tip="<p class='tip'><font size='2'>点击查看详情</font></p>";
      $(".sourceID").append(tip);
      $(".tip").css({"top":(e.pageY-30)+"px","left":(e.pageX-35)+"px","position":"absolute", "background": "gray", "box-shadow": "-2px -2px 0 -1px #c4c4c4", "box-shadow": "0 2px 8px rgba(0,0,0,.3)", "color": "white"}).show("fast");
  });
  $(document).on('mouseleave','.sourceID',function(e){
    $(".tip").remove();
  }); 
</script>
