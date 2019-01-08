<?php

namespace app\controllers;

use Yii;
use app\models\DcmdNodeGroup;
use app\models\DcmdNode;
use app\models\DcmdAuditLog;
use app\models\DcmdServicePoolAudit;
use app\models\DcmdServicePoolAuditSearch;
use app\models\DcmdUserGroup;
use app\models\DcmdFault;
use app\models\DcmdFaultSearch;
use app\models\DcmdService;
use app\models\DcmdServicePool;
use yii\data\ActiveDataProvider;
use app\models\DcmdNodeSearch;
use app\models\DcmdNodeGroupSearch;
use app\models\DcmdPrivate;
use app\models\DcmdServicePoolNode;
use app\models\DcmdServicePoolNodeSearch;
use app\models\DcmdOprLog;
use app\models\DcmdFaultReport;
use app\models\DcmdOprCmdSearch;
use app\models\DcmdOprCmdRepeatExecSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdServicePoolNodeController implements the CRUD actions for DcmdServicePoolNode model.
 */
class DcmdFaultController extends Controller
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
        $searchModel = new DcmdFaultSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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

    public function actionFaultReport()
    {
      $n = time() - 86400 * date('N', time());
      $last_thid = date('Y-m-d', $n - 86400 * 4 );
      $date=date('Y-m-d');  //当前日期
      $first=1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
      $w=date('w',strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
      $now_start=date('Y-m-d',strtotime("$date -".($w ? $w - $first : 6).' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
      $now_end=date('Y-m-d',strtotime("$now_start +6 days"));  //本周结束日期
      $last_start=date('Y-m-d',strtotime("$now_start - 5 days"));  //上周开始>日期 

      $msg = '<table id="table" class="table table-striped table-bordered"><tbody>';
      $msg .= '<tr><th rowspan="2"></th><th rowspan="2">故障标题</th><th rowspan="2">发生区域</th><th colspan="2">影响客户</th><th rowspan="2">发生日期</th><th rowspan="2">Case编号</th><th rowspan="2">故障级别</th><th rowspan="2">故障发现途径</th><th rowspan="2">故障现象</th><th colspan="5">影响时长</th><th>原因</th><th rowspan="2">原因分类</th><th rowspan="2">责任部门/模块</th><th rowspan="2">责任人</th><th rowspan="2">处理过程</th><th rowspan="2">改进措施</th></tr>';
      $msg .= '<tr><th>客户类型</th><th>客户名称</th><th>影响时长</th><th>故障开始时间</th><th>故障发现时间点</th><th>开始处理时间点</th><th>服务恢复时间点</th><th>根本原因</th></tr>';
      $query = DcmdFaultReport::find()->where([">=", 'create_time', $last_start])->orderBy('fault_id')->asArray()->all();
      if($query) {
        foreach($query as $k=>$v) {
          $msg .= '<tr><td><input type="checkbox" name="selection[]" value="'.$v["fault_id"].'"></input></td><td onclick=test(this) value="'.$v["fault_id"].'"  style="color:blue">'.$v["fault_title"].'</td><td>'.$v["fault_area"].'</td><td>'.$v["custom_type"].'</td><td>'.$v["custom_name"].'</td><td>'.$v["fault_date"].'</td><td>'.$v["case_num"].'</td><td>'.$v["fault_level"].'</td><td>'.$v["find_way"].'</td><td>'.$v["fault_show"].'</td><td>'.$v["sustained_time"].'</td><td>'.$v["start_time"].'</td><td>'.$v["discover_time"].'</td><td>'.$v["handle_time"].'</td><td>'.$v["recover_time"].'</td><td>'.$v["reason"].'</td><td>'.$v["reason_type"].'</td><td>'.$v["response_model"].'</td><td>'.$v["response_person"].'</td><td>'.$v["handle_process"].'</td><td>'.$v["improve"].'</td></tr>';
        }
      }
      $msg .= '</tbody></table>';
      return $this->render('fault-report', [
        'msg' => $msg,
      ]);
    }

    public function actionGetFault($fault_id)
    {
      $msg = '<table id="table" class="table table-striped table-bordered"><tbody>';
      $model = $this->reportModel($fault_id);
     /* $msg .= '<tr><th rowspan="2">故障标题</th><th rowspan="2">发生区域</th><th colspan="2">影响客户</th><th rowspan="2">发生日期</th><th rowspan="2">Case编号</th><th rowspan="2">故障级级别</th><th rowspan="2">故障发现途径</th><th rowspan="2">故障现象</th><th colspan="5">影响时长</th><th>原因</th><th rowspan="2">原因分类</th><th rowspan="2">责任部门/模块</th><th rowspan="2">责任人</th><th rowspan="2">处理过程</th><th rowspan="2">改进措施</th></tr>';
      $msg .= '<tr><th>客户类型</th><th>客户名称</th><th>影响时长</th><th>故障开始时间</th><th>故障发现时间点</th><th>开始处理时间点</th><th>服务恢复时间点</th><th>根本原因</th></tr>';
      $model = $this->reportModel($fault_id);
      $msg .= '<tr><td>'.$model->fault_title.'</td><td>'.$model->fault_area.'</td><td>'.$model->custom_type.'</td><td>'.$model->custom_name.'</td><td>'.$model->fault_date.'</td><td>'.$model->case_num.'</td><td>'.$model->fault_level.'</td><td>'.$model->find_way.'</td><td>'.$model->fault_show.'</td><td>'.$model->sustained_time.'</td><td>'.$model->start_time.'</td><td>'.$model->handle_time.'</td><td>'.$model->recover_time.'</td><td>'.$model->reason.'</td><td>'.$model->discover_time.'</td><td>'.$model->reason_type.'</td><td>'.$model->response_model.'</td><td>'.$model->response_person.'</td><td>'.$model->handle_process.'</td><td>'.$model->improve.'</td></tr>';*/
      $msg .= '<tr><th>故障标题</th><td><input type="text" name="fault_title"  value="'.$model->fault_title.'"/></td><th>发生区域</th><td><input type="text" name="fault_area" value="'.$model->fault_area.'"/></td></tr><tr><th>影响客户类型</th><td><input type="text" name="custom_type"  value="'.$model->custom_type.'"/></td><th>影响客户名称</th><td><input type="text" name="custom_name" value="'.$model->custom_name.'"/></td></tr><tr><th>发生日期</th><td><input type="text" name="fault_date"  value="'.$model->fault_date.'"/></td><th>Case编号</th><td><input type="text" name="case_num" value="'.$model->case_num.'"/></td></tr><tr><th>故障级别</th><td><input type="text" name="fault_level"  value="'.$model->fault_level.'"/></td><th>故障发现途径</th><td><input type="text" name="find_way" value="'.$model->find_way.'"/></td></tr><tr><th>故障现象</th><td><input type="text" name="fault_show"  value="'.$model->fault_show.'"/></td><th>影响时长</th><td><input type="text" name="sustained_time" value="'.$model->sustained_time.'"/></td></tr><tr><th>故障开始时间</th><td><input type="text" name="start_time"  value="'.$model->start_time.'"/></td><th>故障发现时间点</th><td><input type="text" name="discover_time" value="'.$model->discover_time.'"/></td></tr><tr><th>开始处理时间</th><td><input type="text" name="handle_time"  value="'.$model->handle_time.'"/></td><th>服务恢复时间</th><td><input type="text" name="recover_time" value="'.$model->recover_time.'"/></td></tr><tr><th>原因</th><td><input type="text" name="reason"  value="'.$model->reason.'"/></td><th>原因分类</th><td><input type="text" name="reason_type" value="'.$model->reason_type.'"/></td></tr><tr><th>责任部门</th><td><input type="text" name="response_model"  value="'.$model->response_model.'"/></td><th>责任人</th><td><input type="text" name="response_person" value="'.$model->response_person.'"/></td></tr><tr><th>处理过程</th><td><input type="text" name="handle_process"  value="'.$model->handle_process.'"/></td><th>改进措施</th><td><input type="text" name="improve" value="'.$model->improve.'"/></td></tr>';
      $msg .= '</tbody></table>';
      return $msg.$model->fault_status;
    }

    public function actionUpdateFault($fault_id,$fault_title,$fault_area,$custom_type,$custom_name,$fault_date,$case_num,$fault_level,$find_way,$fault_show,$sustained_time,$start_time,$discover_time,$handle_time,$recover_time,$reason,$reason_type,$response_model,$response_person,$handle_process,$improve,$fault_status)
    {
      $model = $this->reportModel($fault_id);
      $model->fault_title = $fault_title;
      $model->fault_area = $fault_area;
      $model->custom_type = $custom_type;
      $model->custom_name= $custom_name;
      $model->fault_date= $fault_date;
      $model->case_num= $case_num;
      $model->fault_level= $fault_level;
      $model->find_way= $find_way;
      $model->fault_show= $fault_show;
      $model->sustained_time= $sustained_time;
      $model->start_time= $start_time;
      $model->discover_time= $discover_time;
      $model->handle_time= $handle_time;
      $model->recover_time= $recover_time;
      $model->reason= $reason;
      $model->reason_type= $reason_type;
      $model->response_model= $response_model;
      $model->response_person= $response_person;
      $model->handle_process= $handle_process;
      $model->improve= $improve;
      $model->fault_status = $fault_status;
      $model->create_time = date("Y-m-d H:i:s");
      if($model->save())
        $data = '{"data":"sucess"}';
      else
        $data = '{"data":"failed"}';
      return $data;
    }

    public function actionAddFault($fault_title,$fault_area,$custom_type,$custom_name,$fault_date,$case_num,$fault_level,$find_way,$fault_show,$sustained_time,$start_time,$discover_time,$handle_time,$recover_time,$reason,$reason_type,$response_model,$response_person,$handle_process,$improve,$fault_status)
    {
      $model = new DcmdFaultReport();
      $model->fault_title = $fault_title;
      $model->fault_area = $fault_area;
      $model->custom_type = $custom_type;
      $model->custom_name= $custom_name;
      $model->fault_date= $fault_date;
      $model->case_num= $case_num;
      $model->fault_level= $fault_level;
      $model->find_way= $find_way;
      $model->fault_show= $fault_show;
      $model->sustained_time= $sustained_time;
      $model->start_time= $start_time;
      $model->discover_time= $discover_time;
      $model->handle_time= $handle_time;
      $model->recover_time= $recover_time;
      $model->reason= $reason;
      $model->reason_type= $reason_type;
      $model->response_model= $response_model;
      $model->response_person= $response_person;
      $model->handle_process= $handle_process;
      $model->improve= $improve;
      $model->create_time = date("Y-m-d H:i:s");
      $model->fault_status = $fault_status;
      if($model->save())
        $data = '{"data":"sucess"}';
      else
        $data = '{"data":"failed"}';
      return $data;
    }

    public function actionHostInfo($host_ip)
    {
      $model = DcmdPrivate::find()->andWhere(["host_ip"=>$host_ip, "state"=>"1"])->asArray()->all();
      $model1 = DcmdPrivate::find()->andWhere(["vm_ip"=>$host_ip, "state"=>"1"])->asArray()->all();
      $query = $model+$model1;
      $custom = array();
      $customs = "";
      if($query) $custom = array_column($query,"module");
      if($custom) {
        $custom_array = array_count_values($custom);
        foreach($custom_array as $k=>$v)
          $customs .= $k.$v."台"." "; 
      }
      $output_array = array();
      $url = "http://ump.letv.cn/api/cmdb/server/search?token=e4aa3d6e3cef178ed2b8ab8df56c0592&ip=".$host_ip;
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
      $location = $output_array['cmdb_idc_name'];
      $data = '{"custom":"'.$customs.'",'.'"location":'.'"'.$location.'"}';
      return $data;
    }

    public function actionFaultSearch($title,$process)
    {
      $n = time() - 86400 * date('N', time());
      $last_thid = date('Y-m-d', $n - 86400 * 4 );
      $date=date('Y-m-d');  //当前日期
      $first=1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
      $w=date('w',strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
      $now_start=date('Y-m-d',strtotime("$date -".($w ? $w - $first : 6).' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
      $now_end=date('Y-m-d',strtotime("$now_start +6 days"));  //本周结束日期
      $last_start=date('Y-m-d',strtotime("$now_start - 5 days"));  //上周开始>日期 
    //  $msg = '<div style="padding-left:108px; width:auto;  overflow:hidden; background:#f00;" id="tableDiv_title" >';
    //  $msg .= '<table border="0" cellspacing="0" cellpadding="0">';
      $msg = '<table id="table" class="table table-striped table-bordered"><tbody>';
      $msg .= '<tr><th rowspan="2"></th><th rowspan="2">故障标题</th><th rowspan="2">发生区域</th><th colspan="2">影响客户</th><th rowspan="2">发生日期</th><th rowspan="2">Case编号</th><th rowspan="2">故障级别</th><th rowspan="2">故障发现途径</th><th rowspan="2">故障现象</th><th colspan="5">影响时长</th><th>原因</th><th rowspan="2">原因分类</th><th rowspan="2">责任部门/模块</th><th rowspan="2">责任人</th><th rowspan="2">处理过程</th><th rowspan="2">改进措施</th></tr>';
      $msg .= '<tr><th>客户类型</th><th>客户名称</th><th>影响时长</th><th>故障开始时间</th><th>故障发现时间点</th><th>开始处理时间点</th><th>服务恢复时间点</th><th>根本原因</th></tr>';
      $query = DcmdFaultReport::find()->where([">=", 'create_time', $last_start])->orderBy('fault_id')->asArray()->all();
      if($query) {
        foreach($query as $k=>$v) {
          if($title && (strpos($v["fault_title"],$title) === false)) continue;
          if($process != "ALL" &&  $v["fault_status"] != $process) continue;
          $msg .= '<tr><td><input type="checkbox" name="selection[]" value="'.$v["fault_id"].'"></input></td><td onclick=test(this) value="'.$v["fault_id"].'"  style="color:blue">'.$v["fault_title"].'</td><td>'.$v["fault_area"].'</td><td>'.$v["custom_type"].'</td><td>'.$v["custom_name"].'</td><td>'.$v["fault_date"].'</td><td>'.$v["case_num"].'</td><td>'.$v["fault_level"].'</td><td>'.$v["find_way"].'</td><td>'.$v["fault_show"].'</td><td>'.$v["sustained_time"].'</td><td>'.$v["start_time"].'</td><td>'.$v["discover_time"].'</td><td>'.$v["handle_time"].'</td><td>'.$v["recover_time"].'</td><td>'.$v["reason"].'</td><td>'.$v["reason_type"].'</td><td>'.$v["response_model"].'</td><td>'.$v["response_person"].'</td><td>'.$v["handle_process"].'</td><td>'.$v["improve"].'</td></tr>';
        }
      }
      $msg .= '</tbody></table>';
      print_r($msg);
    }

    /**
     * Creates a new DcmdServicePoolNode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DcmdFault();
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['index']);
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
      return $this->redirect(['index']);;
   }

   public function actionReject() {
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
       }
       $model = DcmdGroup::findOne(['gname'=>'audit']);
          ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['gid']]);
        if($model==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(['index']);
        }

        $query = DcmdServicePoolAudit::findAll($select);
        $success_msg = "请求已驳回!";
        foreach($query as $item) {
          $id = $item->id;
          DcmdServicePoolAudit::findOne($id)->delete();
          $success_msg .= $item['ip']." ";
        }
      
      if($success_msg != "请求已驳回!")Yii::$app->getSession()->setFlash('success', $success_msg);
      return $this->redirect(['index']);;
   }

    /**
     * Updates an existing DcmdServicePoolNode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->fault_id]);
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
        Yii::$app->getSession()->setFlash('error', '未选择!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $suc_msg = "";
      $err_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->findModel($id);
        $this->oprlog(3, "delete image:".$model->fault_id);
        $suc_msg .=$model->fault_id.':删除成功<br>';
        $model->delete();
      }
      if($suc_msg != "") Yii::$app->getSession()->setFlash('success', $suc_msg);
      if($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['index']);

    }

    public function actionReportDeleteAll()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择!');
        return $this->redirect(['fault-report']);
      }
      $select = Yii::$app->request->post()['selection'];
      $suc_msg = "";
      $err_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->reportModel($id);
        $this->oprlog(3, "delete image:".$model->fault_id);
        $suc_msg .=$model->fault_id.':删除成功<br>';
        $model->delete();
      }
      if($suc_msg != "") Yii::$app->getSession()->setFlash('success', $suc_msg);
      if($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['fault-report']);

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
    protected function findModel($id)
    {
        if (($model = DcmdFault::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function reportModel($id)
    {
      if (($model = DcmdFaultReport::findOne($id)) !== null) {
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
