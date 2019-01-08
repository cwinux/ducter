<?php

namespace app\controllers;

use Yii;
use app\models\DcmdNodeGroup;
use app\models\DcmdNode;
use app\models\DcmdAuditLog;
use app\models\DcmdPrivate;
use app\models\DcmdServicePoolAuditSearch;
use app\models\DcmdUserGroup;
use app\models\DcmdOrder;
use app\models\DcmdApp;
use app\models\DcmdOrderSearch;
use app\models\DcmdService;
use app\models\DcmdServicePool;
use yii\data\ActiveDataProvider;
use app\models\DcmdVmOrderReply;
use app\models\DcmdNodeGroupSearch;
use app\models\DcmdServicePoolNode;
use app\models\DcmdServicePoolNodeSearch;
use app\models\DcmdOprLog;
use app\models\DcmdOrderHistory;
use app\models\DcmdVmTask;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdServicePoolNodeController implements the CRUD actions for DcmdServicePoolNode model.
 */
class DcmdOrderController extends Controller
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
     * Lists all DcmdServicePoolNode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdOrderSearch();
       // $dataProvider = $searchModel->search(['manual' => 1]);
        $con_str ='';
        $query = DcmdOrder::find()->where(['manual' => 1])->orderBy('apply_time');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider1 = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

     //   $dataProvider1 = $searchModel->search(['manual' => 1]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider1' => $dataProvider1,
        ]);
    }

    /**
     * Displays a single DcmdServicePoolNode model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionManualView($id)
    {
        return $this->render('manual-view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionExecute($order_id)
    {
        $group = "select app_id, app_name from dcmd_app";
        $query = DcmdApp::findBySql($group)->all();
        $app = array(""=>"");
        if($query) {
         foreach($query as $item) $app[$item->app_name] = $item->app_name;
        }
         
        $model = $this->findModel($order_id);
     /*   if ($model->action == "vm_delete"){
          return $this->redirect(['execute', 'order_id' => $ord_id]);
        }*/
        $args = $model->args;
        $output_array = json_decode($args,true);
        $cluster = $output_array['clusters'];
//        print_r($cluster);
   //     print_r($cluster_array);
        $content = "";
        foreach($cluster as $v) {
          $content .= '<div style="overflow:scroll; overflow-x:hidden;">';
          $content .= '<table type="table" class="table table-striped table-bordered" id="'.$v['app_name'].'_'.$v['flavor_name'].'" value="'.$v['count'].'"><tbody>';
          $content .= '<tr><th></th><th>集群名称:'.$v['app_name'].'</th><th>申请个数:'.$v['count'].'</th><th>虚机规格:'.$v['flavor_name'].'</th></tr>';
          $content .= '<tr><th><class="select-on-check-all" name="selection_all" value="1"></th><th>内网IP</th><th>宿主机IP</th><th>虚拟机规格</th></tr>';
          $content .= $this->actionGetVms($v['app_name'], $v['flavor_name']);
        }
//        print_r($content);
        return $this->render('execute', [
            'model' => $this->findModel($order_id),
            'app' => $app,
            'content' => $content,
        ]);
    }


    public function actionGetVms($app_name, $flavor_name) {
      #return '<tr data-key="15"><td><input type="checkbox" name="selection[]" checked value="15"></td><td>ducter_agent_qcloud1111</td><td>r1.1</td></tr>';
      $query = DcmdPrivate::find()->andWhere(['app_name'=>$app_name, 'flavor_name'=>$flavor_name])->orderBy("host_ip")->all();
      $content = "";
      foreach($query as $item) {
        $content .= '<tr><td><input type="checkbox" name="selection[]" value="'.$item->id.'"></td><td>'.$item->vm_ip."</td><td>".$item->host_ip.'</td><td>'.$item->flavor_name.'</td></tr>';
      }
      $content .= "</tbody></table>";
      $content .= "</div>";
      return $content;
    }

    public function actionHandle() {
      $Ids = !empty($_POST['Ids']) ? $_POST['Ids'] : false;
      $ord_id = $_POST['Tit'];
      $stripIds = preg_replace('/[\"\[\]]/', '', $Ids);
      $IdsToArr = explode(',', $stripIds);
      $flavors = array();
      $order = DcmdOrder::findone($ord_id);
      $args = $order->args;
      $args = json_decode($args,true);
      foreach($IdsToArr as $id){
        $model = DcmdPrivate::findOne($id);
        $host_ip = $model->host_ip;
        $vm_uuid = $model->vm_uuid;
        $vm_ip = $model->vm_ip;
        if (!$this->cmdbcheck($vm_ip)){
          Yii::$app->getSession()->setFlash('error', "cmdb中存在vm".$vm_ip."信息");
          return $this->redirect(['execute', 'order_id' => $ord_id]);
        }
        $flavor_name = $model->flavor_name;
        $vms = array("host_ip" => $host_ip, "vm_uuid" => $vm_uuid, "vm_ip" => $vm_ip);
       // $vms = json_encode($vms);
        $flavor = array("vms" => $vms, "flavor_name"=>$flavor_name);
      //  $flavor = json_encode($flavor);
        array_push($flavors,$flavor);
      }
//      $flavors = json_encode($flavors);
      $business = array_key_exists("business", $args) ? $args['business'] : "";
      $module = array_key_exists("module", $args) ? $args['module'] : "";
      $contacts = array_key_exists("contacts", $args) ? $args['contacts'] : "";
      $flavors = array("flavors" => $flavors, "bill_id" => $order->bill_id, "app_name" => $model->app_name, "business" => $business, "module" => $module, "contacts" => $contacts);
 //     $flavors = json_encode($flavors);
      $result = array("data" => $flavors, "state" => "0", "errmsg" => "");
      $data = json_encode($result);
      if($this->repord($ord_id,$data, 0) && $this->addtask($order,$IdsToArr)){
        $data = '{"result":"sucess"}';
      }
      else {
        $data = '{"result":"failed"}';
      }
      print_r($data);
#      return $data;
#      return $this->redirect(['index']);
}

    public function actionReject($id){
      $data = "{}";
      if($this->repord($id,$data,1)){
        $data = '{"data":"sucess"}';
      }
      else{
        $data = '{"data":"failed"}';
      }
      return $data;
}

    private function cmdbcheck($ip){
      $output_array = array();
      $url = "http://ump.letv.cn/api/cmdb/server/search?token=e4aa3d6e3cef178ed2b8ab8df56c0592&ip=".$ip;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      $output = curl_exec($ch);
      curl_close($ch);
      $output_array = json_decode($output,true);
      $total_array = $output_array['data'];
      $total = $total_array['total'];
      if ($total == 0){
        return true;
      }
      else{
        return false;
      }
}

    private function repord($ord_id, $data, $state) {
      $reply = new DcmdVmOrderReply();
//      $data = json_decode($data);
      $order = DcmdOrder::findone($ord_id);
      $reply->order_id = $ord_id;
      $reply->bill_id = $order->bill_id;
      $reply->action = $order->action;
      $reply->result = $data;
      $reply->callback = $order->callback;
      $reply->state = $state;
      $reply->count = 0;
      $reply->errmsg = "";
      $reply->ctime = date('Y-m-d H:i:s');
      $reply->utime = date('Y-m-d H:i:s');
      if($reply->save() && $this->delord($ord_id)){
        return true;
      }
      else{
        return false;
      }
}

    private function addtask($order,$IdsToArr){
      $task = new DcmdVmTask();
      foreach($IdsToArr as $id){
        $model = DcmdPrivate::findOne($id);
        $task->uuid = $model->vm_uuid;
        $task->vm_ip = $model->vm_ip;
        $task->os = $model->os;
        $task->bill_id = $order->bill_id;
        $task->order_id = $order->order_id;
        $task->task_type = $order->action;
        $task->step = 0;
        $task->step_name = "";
        $task->apply_user = $order->apply_user;
        $task->ctime = date('Y-m-d H:i:s');
        $task->state = 0;
        $task->state_name = "";
        $task->start_time = date('Y-m-d H:i:s');
        $task->end_time = date('Y-m-d H:i:s');
        $task->source_ip = "";
        $task->errmsg = "";
        if($task->save()){
          return true;
        }
        else{
          return false;
        }
      }
}

    private function delord($ord_id) {
      $order = DcmdOrder::findone($ord_id);
      $history = new DcmdOrderHistory();
      $history->bill_id = $order->bill_id;
      $history->action = $order->action;
      $history->args = $order->args;
      $history->callback = $order->callback;
      $history->state = $order->state;
      $history->errmsg = $order->errmsg;
      $history->apply_user = $order->apply_user;
      $history->apply_time = $order->apply_time;
      $history->ctime = $order->ctime;
      $history->utime = date('Y-m-d H:i:s');
      $history->manual = $order->manual;
      if($history->save()){
        return true;
      }
      else{
        return false;
      }
      
}

    /**
     * Creates a new DcmdServicePoolNode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    private function actionCreate()
    {
        $model = new DcmdServicePoolAudit();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Select node group model.
     */
   public function actionSelectNodeGroup($app_id, $svr_id, $svr_pool_id) 
   {
        $model = DcmdApp::findOne($app_id);
        ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service-pool/view', 'id'=>$svr_pool_id));
        }
       $searchModel = new DcmdNodeGroupSearch();
       $dataProvider = $searchModel->search(array());
       return $this->render('select_node_group', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
          'app_id' => $app_id,
          'svr_id' => $svr_id,
          'svr_pool_id' => $svr_pool_id,
       ]);
   }

   public function actionShowNodeList($app_id, $svr_id, $svr_pool_id) {
        #$app_id = Yii::$app->request->post()["app_id"];
        #$svr_id = Yii::$app->request->post()["svr_id"];
        #$svr_pool_id = Yii::$app->request->post()["svr_pool_id"];
        $model = DcmdApp::findOne($app_id);
        ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service-pool/view', 'id'=>$svr_pool_id, 'show_div'=>'dcmd-service-pool-node'));
        }
        $node_group = array();
        $exist_nid = array();
        if (array_key_exists("selection", Yii::$app->request->post())) { 
          $node_group = Yii::$app->request->post()["selection"];
          $pool_node = DcmdServicePoolNode::find()->where(["svr_pool_id"=>$svr_pool_id])->asArray()->all();
          foreach($pool_node as $item) array_push($exist_nid, $item["nid"]);
        }
        $ngroups = "ngroup_id in (0";
        foreach($node_group as $key=>$value) $ngroups = $ngroups.",".$value;
        $ngroups = $ngroups.")";
        $ngroups = $ngroups." and nid not in (0";
        foreach($exist_nid as $k=>$v) $ngroups = $ngroups.",".$v;
        $ngroups = $ngroups.")";
        $query = DcmdNode::find()->where($ngroups)->orderBy('ip');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 0],
        ]);
        $searchModel = new DcmdNodeSearch();
        return $this->render('show-node-list', [
          #'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
          'app_id' => $app_id,
          'svr_id' => $svr_id,
          'svr_pool_id' => $svr_pool_id,
       ]);
        
   }
  
   public function actionAddNode() {
     $app_id = Yii::$app->request->post()["app_id"];
     Yii::$app->getSession()->setFlash('success', "dd");
     $svr_id = Yii::$app->request->post()["svr_id"];
     $svr_pool_id = Yii::$app->request->post()["svr_pool_id"];
     $model = DcmdApp::findOne($app_id);
     ///判断用户所属的系统组是否和该应用相同
     $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['sa_gid']]);
     if($query==NULL) {
       Yii::$app->getSession()->setFlash('success', NULL);
       Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
       return $this->redirect(array('dcmd-service-pool/view', 'id'=>$svr_pool_id, 'show_div'=>'dcmd-service-pool-node'));
     }
     $service = DcmdService::findOne($svr_id);
     $success_msg = "";
     $err_msg = "";
     if (array_key_exists("selection", Yii::$app->request->post())){
       $success_msg = "成功添加以下设备:";
       $nid_str = "nid in (0";
       foreach(Yii::$app->request->post()["selection"] as $k=>$v)
         $nid_str .= ",".$v;
       $nid_str .= ")";
       $query = DcmdNode::find()->where($nid_str)->asArray()->all();
       $tm =  date('Y-m-d H:i:s');
       foreach($query as $item) {
         if($service->node_multi_pool == 0) { ///不可重复节点
           $temp = DcmdServicePoolNode::findOne(['svr_id' => $svr_id, 'ip' => $item['ip']]);
           if($temp) {
             $err_msg .= "已经有池子使用该节点:".$item['ip']." ";
             continue;
           }
         }
         $server_pool_audit = new DcmdServicePoolAudit();
         $server_pool_audit->svr_pool_id = $svr_pool_id;
         $server_pool_audit->svr_id = $svr_id;
         $server_pool_audit->app_id = $app_id;
         $server_pool_audit->nid = $item['nid'];
         $server_pool_audit->ip = $item['ip'];
         $server_pool_audit->utime = $tm;
         $server_pool_audit->ctime = $tm;
         $server_pool_audit->opr_uid = Yii::$app->user->getId();
         $server_pool_audit->save();
         $this->oprlog(1,"add ip:".$item['ip']);
         $success_msg .= $item['ip']." ";
       } 
     }
     if($success_msg != "")Yii::$app->getSession()->setFlash('success', $success_msg);
     if($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
     return $this->redirect(array('dcmd-service-pool/view','id'=>$svr_pool_id, 'show_div'=>'dcmd-service-pool-node'));;
   }

   public function actionPass() {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择设备!');
        return $this->redirect(['index']);
      }
      if(array_key_exists('selection', Yii::$app->request->post())) {
        $select = Yii::$app->request->post()['selection'];
        if(count($select) < 1) {
          Yii::$app->getSession()->setFlash('error', '未选择设备!');
          return $this->redirect(['index']);
        }
        $query = DcmdServicePoolAudit::findAll($select);
        $success_msg = "操作成功!";
        $err_msg = "操作失败!";
        foreach($query as $item) {
          $id = $item->id;
          $ip = $item->ip;
          $nid = $item->nid;
          $app_id = $item->app_id;
          $svr_id = $item->svr_id;
          $svr_pool_id = $item->svr_pool_id;
          $action = $item->action;
          $opr_uid = $item->opr_uid;
//          $model = DcmdApp::findOne($app_id);
          $model = DcmdGroup::findOne(['gname'=>'audit']);
          ///判断用户所属的系统组是否和该应用相同
          $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['gid']]);
          if($model==NULL) {
            Yii::$app->getSession()->setFlash('success', NULL);
            Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
            return $this->redirect(['index']);
          }
          $service = DcmdService::findOne($svr_id);
          $tm =  date('Y-m-d H:i:s');
          if($action == "add" && $service->node_multi_pool == 0) { ///不可重复节点
             $temp = DcmdServicePoolNode::findOne(['svr_id' => $svr_id, 'ip' => $ip]);
             if($temp) {
               $err_msg .= "已经有池子使用该节点:".$item['ip']." ";
               continue;
             }
          }
          if ($action == "add") { 
            $server_pool_Node = new DcmdServicePoolNode();
            $server_pool_Node->svr_pool_id = $svr_pool_id;
            $server_pool_Node->svr_id = $svr_id;
            $server_pool_Node->app_id = $app_id;
            $server_pool_Node->nid = $nid;
            $server_pool_Node->ip = $ip;
            $server_pool_Node->utime = $tm;
            $server_pool_Node->ctime = $tm;
            $server_pool_Node->opr_uid = $opr_uid;
            $server_pool_Node->save();
            DcmdServicePoolAudit::findOne($id)->delete();
            $this->oprlog(1,"add ip:".$ip);
            $success_msg .= $item['ip']." ";
            }
          else {
            $DcmdServicePoolNode = DcmdServicePoolNode::findOne(['svr_pool_id' => $svr_pool_id, 'svr_id' => $svr_id, 'nid' => $nid, 'app_id' => $app_id, 'ip' => $ip]);
            if ($DcmdServicePoolNode) {
              $DcmdServicePoolNode->delete();
              $this->oprlog(1,"delete ip:".$ip);
            }
            DcmdServicePoolAudit::findOne($id)->delete();
            $success_msg .= $item['ip']." "; 
            }
          
          $pool_audit_log = new DcmdAuditLog();
          $pool_audit_log->svr_pool_id = $svr_pool_id;
          $pool_audit_log->svr_id = $svr_id;
          $pool_audit_log->app_id = $app_id;
          $pool_audit_log->nid = $nid;
          $pool_audit_log->ip = $ip;
          $pool_audit_log->utime = $tm;
          $pool_audit_log->ctime = $tm;
          $pool_audit_log->opr_uid = $opr_uid;
          $pool_audit_log->action = $action;
          $pool_audit_log->save();

          }
      }
      if($success_msg != "操作成功!")Yii::$app->getSession()->setFlash('success', $success_msg);
      if($err_msg != "操作失败!") Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['index']);
   }

    /**
     * Updates an existing DcmdServicePoolNode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    private function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $temp = DcmdApp::findOne($app_id);
        ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service-pool/view', 'id'=>$svr_pool_id, 'show_div'=>'dcmd-service-pool-node'));
         }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdServicePoolNode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $svr_pool_id=NULL)
    {
        $model = $this->findModel($id);
        $temp = DcmdApp::findOne($model['app_id']);
        ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service-pool/view', 'id'=>$model['svr_pool_id'], 'show_div'=>'dcmd-service-pool-node'));
        }
        $this->oprlog(3,"delete ip:".$model->ip);
        $model->delete();

        Yii::$app->getSession()->setFlash('success', '删除成功!');
        if($svr_pool_id) return $this->redirect(['dcmd-service-pool/view', 'id'=>$svr_pool_id, 'show_div'=>'dcmd-service-pool-node']);
        return $this->redirect(['index']);
    }

    public function actionDeleteAll()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择服务!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $ret_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->findModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
           $ret_msg .="没有权限删除:".$model->ip." ";
           continue;
        }
        $model->delete();
        $ret_msg .='删除成功:'.$model->ip." ";
      }
      Yii::$app->getSession()->setFlash('success', $ret_msg);
      return $this->redirect(['index']);
    }
    public function actionOpr() {
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
        $query = DcmdServicePoolNode::findAll($select);
        $hv = array();
        foreach($query as $item) {
         if(in_array($item->ip, $hv)) continue;
         $ips .= $item->ip.";";
         array_push($hv, $item->ip);
        }
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

    public function actionRepeatOpr() {
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
        $query = DcmdServicePoolNode::findAll($select);
        $hv = array();
        foreach($query as $item) {
         if(in_array($item->ip, $hv)) continue;
         $ips .= $item->ip.";";
         array_push($hv, $item->ip);
        }
      }else $ips = Yii::$app->request->post()['ips'];
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
    /**
     * Finds the DcmdServicePoolNode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdServicePoolNode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($order_id)
    {
        if (($model = DcmdOrder::findOne($order_id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    private function oprlog($opr_type, $sql) {
      $opr_log = new DcmdOprLog();
      $opr_log->log_table = "dcmd_service_pool_node";          
      $opr_log->opr_type = $opr_type;
      $opr_log->sql_statement = $sql;
      $opr_log->ctime = date('Y-m-d H:i:s');
      $opr_log->opr_uid = Yii::$app->user->getId();
      $opr_log->save();
    }

}
