<?php

namespace app\controllers;

use Yii;
use app\models\DcmdNode;
use app\models\DcmdNodeAudit;
use app\models\DcmdServicePoolNode;
use app\models\DcmdNodeGroup;
use app\models\DcmdUserGroup;
use app\models\DcmdNodeSearch;
use app\models\DcmdCenter;
use app\models\DcmdResColumnSearch;
use app\models\DcmdResColumn;
use app\models\DcmdResType;
use app\models\DcmdOprLog;
use app\models\DcmdNodeGroupInfo;
use app\models\DcmdImage;
use app\models\DcmdImageSearch;
use app\models\DcmdApp;
use app\models\DcmdService;
use app\models\DcmdOprCmdSearch;
use app\models\DcmdOprCmdRepeatExecSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * DcmdNodeController implements the CRUD actions for DcmdNode model.
 */
class DcmdResourceColumnController extends Controller
{
    public $enableCsrfValidation = false;
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all DcmdNode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdResColumnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = DcmdResType::find()->orderBy('res_type')->asArray()->all();
        $type = array();
        if($query){
          foreach($query as $item) $type[$item['res_type']] = $item['res_type'];
        }
        ///获取服务池列表
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type,
        ]);
    }

    public function actionUnuseNode()
    {

        $searchModel = new DcmdNodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
        ///获取服务池列表
        $query = DcmdNodeGroup::find()->orderBy('ngroup_name')->asArray()->all();
        $dcmd_node_group = array();
        foreach($query as $item) $dcmd_node_group[$item['ngroup_id']] = $item['ngroup_name'];
        return $this->render('unuse-node', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dcmd_node_group' => $dcmd_node_group,
        ]);
    }
    /**
     * Displays a single DcmdNode model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewIp($ip){
      $query = DcmdNode::findOne(['ip'=>$ip]);
      return $this->render('view', ['model' => $this->findModel($query["nid"]),]);
    }
    public function actionGetRunningTask() {
        $ip = Yii::$app->request->queryParams['ip'];
        $query = DcmdCenter::findOne(['master'=>1]);
        $run_task = array();
        $ret_msg = '<table class="table table-striped table-bordered"><tbody>';
        $ret_msg .="<tr><td>服务</td><td>服务池</td><td>任务名</td><td>subtask_id</td></tr>";
        if ($query) {
          list($host, $port) = explode(':', $query["host"]);
          $reply = getRunningTask($host, $port, $ip);
          if ($reply->getState() == 0) {
            $subtaskInfo = $reply->getResult();
            foreach($subtaskInfo as $item) array_push($run_task, $item);
          }else { 
            $ret_msg .="<tr><td colspan=4><font color=red>获取失败:.".$reply->getErr()."</font></td></tr>";  
          }
        }else {
            $ret_msg .="<tr><td colspan=4><font color=red>获取Center失败</font></td></tr>";
        }
       foreach($run_task as $task)
         $ret_msg .="<tr><td>".$task->svr_name."</td><td>".$task->svr_pool."</td><td>".$task->task_cmd."</td><td>".$task->subtask_id."</td><td>".$task->subtask_id."</td></tr>";
       $ret_msg .= "</tbody></table>";
       echo $ret_msg;
       exit;
    }
  
    public function actionGetRunningOpr() {
        $ip = Yii::$app->request->queryParams['ip'];
        $query = DcmdCenter::findOne(['master'=>1]);
        $run_opr = array();
        $ret_msg = '<table class="table table-striped table-bordered"><tbody>';
        $ret_msg .="<tr><td>操作名</td><td>开始时间</td><td>运行时间</td></tr>";
        if ($query) {
          list($host, $port) = explode(':', $query["host"]);
          $reply = getRunningOpr($host, $port, $ip);
          if ($reply->getState() == 0) {
            $oprInfo = $reply->getResult();
            foreach($oprInfo as $item) array_push($opr_task, $item);
          }else { 
            $ret_msg .="<tr><td colspan=3><font color=red>获取失败:.".$reply->getErr()."</font></td></tr>";
          }
        }else {
            $ret_msg .="<tr><td colspan=3><font color=red>获取Center失败</font></td></tr>";
        }
       foreach($run_opr as $opr)
         $ret_msg .="<tr><td>".$opr->name."</td><td>".$opr->start_time."</td><td>".$opr->running_second."</td></tr>";
       $ret_msg .= "</tbody></table>";
       echo $ret_msg;
       exit;
    }

    public function actionNodeInfo() {
       $ip = Yii::$app->request->queryParams['ip'];
       $ret_msg = '<table class="table table-striped table-bordered"><tbody>';
       $output_array = array();
       $url = "http://ump.letv.cn/api/cmdb/server/search?token=e4aa3d6e3cef178ed2b8ab8df56c0592&ip=".$ip;
       //$data = array (
       // 'ip' => '10.154.21.11',
       // 'link_token' => '17a710902c1e356bc718136a3b854d63'
       //);
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_HEADER, 0);
       //curl_setopt($ch, CURLOPT_POST, 1);
       //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
       $output = curl_exec($ch);
       curl_close($ch);
       $output_array = json_decode($output,true);
       //foreach($output_array as $otp)
       $output_array = $output_array['data']['data'][0];
       $ret_msg .="<tr><td width='300'>服务器ip</td><td>".$ip."</td></tr>";   
       $ret_msg .="<tr><td width='300'>管理ip</td><td>".$output_array['manager_ip']."</td></tr>";
       $ret_msg .="<tr><td>外网ip</td><td>".$output_array['cmdb_ip_data'][0]['ipaddr']."</td></tr>"; 
       $ret_msg .="<tr><td>SN</td><td>".$output_array['sn']."</td></tr>";  
       $ret_msg .="<tr><td>idc信息</td><td>".$output_array['cmdb_idc_name']."</td></tr>";
       $ret_msg .="<tr><td>机柜</td><td>".$output_array['cmdb_cabinet_name']."</td></tr>";  
       $ret_msg .="<tr><td>品牌</td><td>".$output_array['brand_name']."</td></tr>";
       $ret_msg .="<tr><td>型号</td><td>".$output_array['hardmodel_name']."</td></tr>";
       $ret_msg .="<tr><td>使用状态</td><td>".$output_array['state']['name']."</td></tr>";
       $ret_msg .="<tr><td>联系人</td><td>".$output_array['linkman'][0]['name']."</td></tr>";  
       $ret_msg .="<tr><td>联系人电话</td><td>".$output_array['linkman'][0]['phone']."</td></tr>";
       foreach($output_array['hardware'] as $v)
           $ret_msg .="<tr><td>".$v['type']."</td><td>".$v['optionvalue']."</td></tr>";
       $ret_msg .="<tr><td>服务树</td><td>".$output_array['servicetree'][0]['parents'].'_'.$output_array['servicetree'][0]['name']."</td></tr>";
       $ret_msg .= "</tbody></table>";
       print_r($ret_msg);
    }

    public function actionVmInfo() {
       $ip = Yii::$app->request->queryParams['ip'];
       $ret_msg = '<table class="table table-striped table-bordered"><tbody>';
       $output_array = array();
       $url = "http://lingshu.letv.cn/cmdb/cmdbapi/getvminfo?link_token=ab53f5de12539c4d85db5d89048334c5&ip=".$ip;
       //$data = array (
       // 'ip' => '10.154.21.11',
       // 'link_token' => '17a710902c1e356bc718136a3b854d63'
       //);
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_HEADER, 0);
       //curl_setopt($ch, CURLOPT_POST, 1);
       //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
       $output = curl_exec($ch);
       curl_close($ch);
       $output_array = json_decode($output,true);
       $ret_msg .="<tr><td>虚拟机ip</td><td>内存信息</td><td>CPU信息</td><td>磁盘信息</td><td>所属服务树</td><td>联系人</td><td>联系人电话</td><tr>"; 
       if($output_array['code'] == '000' && $output_array['data'] != '没有查询到该宿主机下的虚拟机信息') {
         foreach($output_array['data'] as $vminfo )
           $ret_msg .="<tr><td>".$vminfo['machineip'][0]['ip']."</td><td>".$vminfo['virtualmachine'][0]['memory']."</td><td>".$vminfo['virtualmachine'][0]['cpu']."</td><td>".$vminfo['virtualmachine'][0]['hardpan']."</td><td>".$vminfo['servicetree'][0]['name']."</td><td>".$vminfo['owner'][0]['name']."</td><td>".$vminfo['owner'][0]['phone']."</td></tr>";
         print_r($ret_msg);
       }else {       
          $ret_msg .="<tr><td colspan=7><font color=red>获取失败:.".$output."</font></td></tr>"; 
          print_r($ret_msg);
      }
    }
    /**
     * Creates a new DcmdNode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $res_type = DcmdResType::find()->asArray()->all();
        $type = array();
        foreach($res_type as $item) $type[$item['res_type']] = $item['res_type'];
        $db=Yii::$app->getDb();
        $r=$db->getSchema()->getTableSchema('dcmd_res_type');
        $fields = \yii\helpers\ArrayHelper::getColumn($r->columns, 'name', false);
        $column = array();
        $model = new DcmdResColumn();
        if ($model->load(Yii::$app->request->post())) {
            $model->ctime = date('Y-m-d H:i:s');
            $model->opr_uid = Yii::$app->user->getId();
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'type' => $type,
                'column' => $column,
            ]);
        }

    }

    public function actionCreateIp($ip) {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!!");
          return $this->redirect(array('dcmd-node/index'));
        }
       $model = new DcmdNode();
       if(Yii::$app->request->post()&& $model->load(Yii::$app->request->post())) {
         $model->utime = date('Y-m-d H:i:s');
         $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
         if ($model->save()) {
           #发送消息给center
           $query = DcmdCenter::findOne(['master'=>1]);
           if($query) {
             list($host, $port) = explode(':', $query["host"]);
             agentValid($host, $port, $ip);
           }
           $this->oprlog(1,"insert node:".$ip);
           Yii::$app->getSession()->setFlash('success', "添加成功");
           return $this->redirect(['dcmd-node/view', 'id' => $model->nid]);
         }else 
            Yii::$app->getSession()->setFlash('error', '添加失败');
       }
       $model->ip = $ip;
       $query = DcmdNodeGroup::find()->asArray()->all();
       $group = array();
       foreach($query as $item) $group[$item['ngroup_id']] = $item['ngroup_name'];
       return $this->render('add', ['model' => $model, 'node_group'=>$group]);
    }
    /**
     * Updates an existing DcmdNode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->post()) {
          if ($model->load(Yii::$app->request->post())) {
            $model->save();
            $this->oprlog(2,"update res-coloumn:".$model->id);
            Yii::$app->getSession()->setFlash('success', "修改成功");
            return $this->redirect(['view', 'id' => $model->id]);
          } 
         $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "修改失败:".$err_str);
        }
        $res_type = DcmdResType::find()->asArray()->all();
        $type = array();
        foreach($res_type as $item) $type[$item['res_type']] = $item['res_type'];
        $db=Yii::$app->getDb();
        $r=$db->getSchema()->getTableSchema('dcmd_res_type');
        $fields = \yii\helpers\ArrayHelper::getColumn($r->columns, 'name', false);
        $column = array();
        $column[$model->colum_name] = $model->colum_name;
        return $this->render('update', [
             'model' => $model,
             'type' => $type,
             'column' => $column,
        ]);
    }

    public function actionGetInfo($type) 
    {
      $res_type = DcmdResType::findOne(['res_type'=>$type]);
      $table_string = $res_type['res_table'];
      $db=Yii::$app->getDb();
      $r=$db->getSchema()->getTableSchema($table_string);
      $fields = \yii\helpers\ArrayHelper::getColumn($r->columns, 'name', false);
      $column = array();
      foreach($fields as $k=>$v) {
        $exist = DcmdResColumn::findOne(['colum_name'=>$v,'res_table'=>$table_string]);
        if(!$exist) $column[$v] = $v;
      }
      $data = '{"table":"'.$table_string.'","column":'.json_encode($column).'}';
      return $data;
    }

    /**
     * Deletes an existing DcmdNode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->oprlog(3,"delete res_column:".$model->colum_name);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');

        return $this->redirect(['index']);
    }

    public function actionDeleteAll()
    {

      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $suc_msg = "";
      $err_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->findModel($id);
        $this->oprlog(3, "delete res_column:".$model->colum_name);
        $suc_msg .=$model->colum_name.':删除成功<br>';
        $model->delete();
      }
      if($suc_msg != "") Yii::$app->getSession()->setFlash('success', $suc_msg);
      if($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['index']);
    }

    public function actionConvert()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择设备!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      if(count($select) < 1) {
        Yii::$app->getSession()->setFlash('error', '未选择设备!');
        return $this->redirect(['index']);
      } 
      ///设备池
      $query = DcmdNodeGroup::find()->asArray()->all();
      $node_group = array();
      foreach($query as $item) $node_group[$item['ngroup_id']] = $item['ngroup_name'];
      ///获取需要变更的ip
      $ips_info = array();
      $ids="";
      foreach($select as $k=>$id){
        $model = $this->findModel($id);
        array_push($ips_info, array('id'=>$id, 'ip'=>$model->ip));
        $ids.=$id.";";
      } 
      return $this->render('select_group', ['ips_info'=>$ips_info, 'ids'=>$ids, 'node_group'=>$node_group]);
    }
    public function actionOpr()
    {
      if(!array_key_exists('selection', Yii::$app->request->post()) && !array_key_exists('ips', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择设备!');
        return $this->redirect(['index']);
      }
      $ips = "";
      if(array_key_exists('selection', Yii::$app->request->post())) {
        $select = Yii::$app->request->post()['selection'];
        if(count($select) < 1) {
          Yii::$app->getSession()->setFlash('error', '未选择设备!');
          return $this->redirect(['index']);
        }
        $query = DcmdNode::findAll($select);
        foreach($query as $item) $ips .= $item->ip.";";
      }else $ips = Yii::$app->request->post()['ips'];
      ///获取操作列表
      $searchModel = new DcmdOprCmdSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

      return $this->render('opr', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
          'ips' => $ips,
      ]);

    }
    public function actionRepeatOpr()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择设备!');
        return $this->redirect(['index']);
      }
      $ips = "";
      $select = Yii::$app->request->post()['selection'];
      if(count($select) < 1) {
        Yii::$app->getSession()->setFlash('error', '未选择设备!');
        return $this->redirect(['index']);
      }
      $query = DcmdNode::findAll($select);
      foreach($query as $item) $ips .= $item->ip.";";
      ///IP可替换的重复操作
      $params = array("DcmdOprCmdRepeatExecSearch"=>array("ip_mutable"=>1));
      $searchModel = new DcmdOprCmdRepeatExecSearch();
      $dataProvider = $searchModel->search($params);

      return $this->render('repeat_opr', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
          'ips' => $ips,
      ]);
    }
    public function actionChangeNodeGroup()
    {
      $ngroup_id = Yii::$app->request->post()['ngroup_id'];
      $ids = explode(";", Yii::$app->request->post()['ids']);
      if($ngroup_id == "") {
        Yii::$app->getSession()->setFlash('error', "未选择设备池!");
        return $this->redirect(['index']);
      }
      ///判断用户是否和该设备池子属于一个系统组
      $tmp = DcmdNodeGroup::findOne($ngroup_id);
      $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$tmp['gid']]);
      if($query==NULL) {
        Yii::$app->getSession()->setFlash('error', "没有权限切换到该服务池");
        return $this->redirect(['index']); 
      }
      $ret_msg ="";
      foreach($ids as $k=>$id) {
        if($id == "") continue;
        $model = $this->findModel($id);
        ///判断用户是否和该设备池子属于一个系统组
        $tmp = DcmdNodeGroup::findOne($model['ngroup_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$tmp['gid']]);
        if($query==NULL) {
          $ret_msg .="没有权限变更:".$model->ip." ";
          continue;
        }
        $model->ngroup_id = $ngroup_id;
        $model->save();
        $ret_msg .="变更成功:".$model->ip." ";
      }
      Yii::$app->getSession()->setFlash('success', $ret_msg);
      return $this->redirect(['index']);
    }
    ///获取未归档任务
    public function actionTaskList($ip)
    {
      $ret_msg = '<table class="table table-striped table-bordered"><tbody>';
      $ret_msg .= "<tr><td>任务名称</td><td>脚本名称</td><td>产品名称</td><td>创建时间</td>";
      ///获取任务列表
      $query = DcmdTaskNode::findAll(['ip'=>$ip]);
      if($query) {
        $task_id = "task_id in (0";
        foreach($query as $item) $task_id .= ",".$item->task_id; 
        $task_id .=")";
        ///获取任务信息
        $query = DcmdTask::find()->andWhere($task_id)->orderBy('task_id desc')->all();
        if($query) {
          foreach($query as $task) {
            $ret_msg .= "<tr><td><a href='index.php?r=dcmd-task-async/monitor-task&task_id=".$task->task_id."' target=_blank>".$task->task_name."</a></td>";
            $ret_msg .= "<td>".$task->task_cmd."</td>";
            $ret_msg .= "<td>".$task->app_name."</td>";
            $ret_msg .= "<td>".$task->ctime."</td>";
            $ret_msg .= "</tr>";
          }
        }
      }
      $ret_msg .= "</tbody></table>";
      echo $ret_msg;
    }
    ///获取服务器对应产品
    public function actionAppList($ip)
    {
      $ret_msg = '<table class="table table-striped table-bordered"><tbody>';
      $ret_msg .= "<tr><td>产品</td><td>产品别名</td><td>服务</td><td>服务池</td></tr>";
      $connection = Yii::$app->db;
      ///$command = $connection->createCommand('SELECT * FROM dcmd_app');
      ///$posts = $command->queryAll();
      //从服务池获取该机器对应的服务列表
      $query = DcmdServicePoolNode::findAll(['ip'=>$ip]);
      if($query) {
        $svr_id = "dcmd_service_pool.svr_id in (0";
        foreach($query as $svr_node) $svr_id .=",".$svr_node->svr_id;
        $svr_id .= ")";
        $sql = "select dcmd_app.app_name, dcmd_app.app_id, dcmd_app.app_alias, dcmd_service.svr_name, dcmd_service.svr_id, dcmd_service_pool.svr_pool , dcmd_service_pool.svr_pool_id from  dcmd_app inner join  dcmd_service on dcmd_app.app_id = dcmd_service.app_id inner join  dcmd_service_pool on dcmd_service.svr_id = dcmd_service_pool.svr_id where  ".$svr_id;
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();
        if($data) {
         foreach($data as $k=>$v) {
           $ret_msg .="<tr><td><a href='index.php?r=dcmd-app/view&id=".$v['app_id']."' target=_blank>".$v['app_name']."</a></td>";
           $ret_msg .="<td>".$v['app_alias']."</td>";
           $ret_msg .="<td><a href='index.php?r=dcmd-service/view&id=".$v['svr_id']."' target=_blank>".$v['svr_name']."</a></td>";
           $ret_msg .="<td><a href='index.php?r=dcmd-service-pool/view&id=".$v['svr_pool_id']."' target=_blank>".$v['svr_pool']."</a></td></tr>";
         }
        }
      }
      $ret_msg .= "</tbody></table>";
      echo $ret_msg;
    }
    ///获取os用户
    public function actionOsUser($ip)
    {
       $query = DcmdCenter::findOne(['master'=>1]);
       $ret_msg = '<table class="table table-striped table-bordered"><tbody>';  
       $ret_msg .="<tr><td>用户名</td></tr>";
       if($query) {
         list($host, $port) = explode(':', $query["host"]);
         $reply = execRepeatOprCmd($host, $port, "get_host_user", array("include_sys_user"=>0), array($ip));
         if ($reply->getState() == 0) {
           foreach($reply->getResult() as $agent) {
             if($agent->getState() == 0) {
               $users = explode("\n", $agent->getResult());
               foreach($users as $user) $ret_msg .= "<tr><td>".$user."</td></tr>";
             }else $ret_msg .= "<tr><td><font color=red>获取失败:".$agent->getErr()."</font></td></tr>";
           }
         }else{
           $ret_msg .= "<tr><td><font color=red>获取失败:".$reply->getErr()."</font></td></tr>";
         }
       }else {
         $ret_msg .="<tr><td><font color=red>无法获取Center!</font></td></tr>";
       }
       echo $ret_msg;
       exit;
    }
    ///获取OS信息
    public function actionOsInfo($ip)
    {
       $query = DcmdCenter::findOne(['master'=>1]);
       $ret_msg = "";
       if($query) {
         list($host, $port) = explode(':', $query["host"]);
         $reply = execRepeatOprCmd($host, $port, "os_info", array(), array($ip));
         if ($reply->getState() == 0) {
            foreach($reply->getResult() as $agent) {
               if($agent->getState() == 0) {
                 $result = explode("\n", $agent->getResult());
                 $os = array();
                 foreach($result as $k=>$v) {
                   $pos = strpos($v, ":");
                   if($pos == false) continue;
                   $col = substr($v, 0, $pos);
                   $param = substr($v,$pos+1, strlen($v)-$pos);
                   $pos = strpos($param, "=");
                   $p1 = substr($param, 0, $pos);
                   $p2 = substr($param, $pos+1, strlen($param)-$pos);
                   if(array_key_exists($col, $os) == false) $os[$col] = array();
                   $os[$col][$p1]=$p2;
                 }
                 foreach($os as $k=>$v) {
                   $ret_msg .="<p><strong>".$k."信息:</strong></p>";
                   $ret_msg .= '<table class="table table-striped table-bordered"><tbody>';
                   $ret_msg .="<tr><td width=40%>参数</td><td>值</td></tr>"; 
                   foreach($v as $a=>$b) {
                     $ret_msg .="<tr><td>".$a."</td><td>".$b."</td></tr>"; 
                   }
                   $ret_msg .="</tbody></table>";
                 }
               }else {
                 $ret_msg .="<p><font color=red>获取OS失败:";
                 $ret_msg .=str_replace("\n", "<br/>",$agent->getErr())."</font></p>";
               }
            }
         }else{
           $ret_msg .= "<p><font color=red>获取失败:".str_replace("\n", "<br/>",$reply->getErr())."</font></p>";
         }
       }else{
         $ret_msg .= "<p><font color=red>无法获取Center!</font></p>";
       }
       echo $ret_msg;
       exit;
    }
    /**
     * Finds the DcmdNode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdNode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdResColumn::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     *Get dcmd_node_group list
     */
    protected  function getDcmdNodeGroup() {
      $group = array();
      $ret = DcmdNodeGroup::findBySql("select ngroup_id, ngroup_name from dcmd_node_group")->asArray()->all();
      foreach($ret as $g) {
        $group[$g['ngroup_id']] = $g['ngroup_name'];
      }
      return $group;
    }

    ///get agent hostname
    public function actionGetAgentHostname()
    {
      $agent_ip = Yii::$app->request->post()["ip"];
      $query = DcmdCenter::findOne(['master'=>1]);
      $retcontent = array("hostname"=>"",);
      if ($query) {
          list($ip, $port) = explode(':', $query["host"]);
          $reply = getAgentHostName($ip, $port, $agent_ip);
          if ($reply->getState() == 0 && $reply->getIsExist() == true) {
            $retContent["hostname"] = $reply->getHostname();
          }
      }
      echo json_encode($retContent);
      exit;
    }

    private function oprlog($opr_type, $sql) {
     $opr_log = new DcmdOprLog();
     $opr_log->log_table = "dcmd_node";          
     $opr_log->opr_type = $opr_type;
     $opr_log->sql_statement = $sql;
     $opr_log->ctime = date('Y-m-d H:i:s');
     $opr_log->opr_uid = Yii::$app->user->getId();
     $opr_log->save();
   }

    protected function getGinfo($id) {
        $query = DcmdNodeGroupInfo::findOne($id);
        if ($query) return $query->name;
        return "";
    }
}
