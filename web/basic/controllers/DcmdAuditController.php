<?php

namespace app\controllers;

use Yii;
use app\models\DcmdNodeGroup;
use app\models\DcmdNode;
use app\models\DcmdAuditLog;
use app\models\DcmdServicePoolAudit;
use app\models\DcmdServicePoolAuditSearch;
use app\models\DcmdUserGroup;
use app\models\DcmdGroup;
use app\models\DcmdApp;
use app\models\DcmdServiceScript;
use app\models\DcmdService;
use app\models\DcmdServicePool;
use yii\data\ActiveDataProvider;
use app\models\DcmdNodeSearch;
use app\models\DcmdNodeGroupSearch;
use app\models\DcmdServiceScriptApply;
use app\models\DcmdServiceScriptApplySearch;
use app\models\DcmdServicePoolPort;
use app\models\DcmdServicePoolNode;
use app\models\DcmdServicePoolNodePort;
use app\models\DcmdServicePoolNodeSearch;
use app\models\DcmdOprLog;
use app\models\DcmdOprCmdSearch;
use app\models\DcmdOprCmdRepeatExecSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdServicePoolNodeController implements the CRUD actions for DcmdServicePoolNode model.
 */
class DcmdAuditController extends Controller
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
        if(Yii::$app->user->getIdentity()->sa != 1 && Yii::$app->user->getIdentity()->is_leader != 1) {
//          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/index'));
        }
        $params = array();
        if(array_key_exists('DcmdServicePoolNodeSearch', Yii::$app->request->queryParams)){
          $params['DcmdServicePoolNodeSearch'] = Yii::$app->request->queryParams['DcmdServicePoolNodeSearch'];
          if($params['DcmdServicePoolNodeSearch']['app_id'] == "") {
            $params['DcmdServicePoolNodeSearch']['svr_id'] = "";
          }
          if($params['DcmdServicePoolNodeSearch']['svr_id'] == "")
           $params['DcmdServicePoolNodeSearch']['svr_pool_id'] = "";
        }

        ///应用足用户只可查看所在组的应用
        $app_con = "";
        if(Yii::$app->user->getIdentity()->admin != 1)
        {
          $app_con = "svr_gid in (0";
          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
        }
        $query = DcmdApp::find()->where($app_con)->orderBy('app_name')->asArray()->all();
        $app = array();
        foreach($query as $item) $app[$item['app_id']] = $item['app_alias'];
        $svr = array();
        if(array_key_exists('DcmdServicePoolNodeSearch', $params) &&
           array_key_exists('app_id' ,  $params['DcmdServicePoolNodeSearch'])) {
           $query = DcmdService::find()->andWhere(['app_id'=>$params['DcmdServicePoolNodeSearch']['app_id']])->asArray()->all();
          if($query) {
            foreach($query as $item) $svr[$item['svr_id']] = $item['svr_name'];
          }
          if(!array_key_exists( $params['DcmdServicePoolNodeSearch']['svr_id'], $svr))
            $params['DcmdServicePoolNodeSearch']['svr_id'] = "";
        }

        $svr_pool = array();
        if(array_key_exists('DcmdServicePoolNodeSearch', $params) &&
           array_key_exists('svr_id' ,  $params['DcmdServicePoolNodeSearch']) &&
           array_key_exists($params['DcmdServicePoolNodeSearch']['svr_id'], $svr)) {
           $query = DcmdServicePool::find()->andWhere(['svr_id'=>$params['DcmdServicePoolNodeSearch']['svr_id']])->asArray()->all();
          if($query) {
            foreach($query as $item) $svr_pool[$item['svr_pool_id']] = $item['svr_pool'];
          }
        }
        if(array_key_exists('DcmdServicePoolNodeSearch', $params)){
          if(!array_key_exists($params['DcmdServicePoolNodeSearch']['svr_pool_id'], $svr_pool))
            $params['DcmdServicePoolNodeSearch']['svr_pool_id'] = "";
        }

        $searchModel = new DcmdServicePoolAuditSearch();
        $dataProvider = $searchModel->search($params);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'app' => $app,
            'svr' => $svr,
            'svr_pool' => $svr_pool,
        ]);
    }

    public function actionServiceScript()
    {
        if(Yii::$app->user->getIdentity()->sa != 1 && Yii::$app->user->getIdentity()->is_leader != 1) {
//          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/index'));
        }
        $searchModel = new DcmdServiceScriptApplySearch();
        $param = array();
        $param['DcmdServiceScriptApplySearch']['is_apply'] = 1;
        $dataProvider = $searchModel->search($param);
        return $this->render('service-script', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLoadContent($id)
    {
        if(Yii::$app->user->getIdentity()->sa != 1 && Yii::$app->user->getIdentity()->is_leader != 1) {
//          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/index'));
        }
      $query = DcmdServiceScriptApply::findOne($id);

      if ($query) {
            $result = str_replace(" ","&nbsp;",str_replace("\n", "<br/>",$query->script));
      }else {
        $result="Not script found.";
      }
      return $this->render('script-content', [
        'result' => $result,
      ]);
     // echo json_encode($retContent);
     // exit;
    }

    public function actionPassScript()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择!');
        return $this->redirect(['service-script']);
      }
      if(array_key_exists('selection', Yii::$app->request->post())) {
        $select = Yii::$app->request->post()['selection'];
        if(count($select) < 1) {
          Yii::$app->getSession()->setFlash('error', '未选择!');
          return $this->redirect(['service-script']);
        }
        $query = DcmdServiceScriptApply::findAll($select);
        $success_msg = "操作成功!";
        $err_msg = "操作失败!";
        foreach($query as $item) {
          $model = new DcmdServiceScript();
          $model->svr_id = $item->svr_id;
          $model->app_id = $item->app_id;
          $model->pool_group = $item->pool_group;
          $model->script_md5 = $item->script_md5;
          $model->script = $item->script;
          $model->opr_uid = $item->opr_uid;
          $model->approve_uid = Yii::$app->user->getId();
          $model->ctime = $item->ctime;
          $exits = DcmdServiceScript::findOne(['app_id'=>$item->app_id,'svr_id'=>$item->svr_id,'pool_group'=>$item->pool_group]);
          if($exits) $exits->delete();
          $model->approve_time = date('Y-m-d H:i:s');
          if($model->save() && $item->delete()) {
            $this->oprlog(1,"pass script:".$model->app_id.$model->svr_id);
            $success_msg .= $item->id;
          }else {
            $err_msg .= $item->id;
          }
        }
      }
      return $this->redirect(['service-script']);
    }

    public function actionRejectScript()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择!');
        return $this->redirect(['service-script']);
      }
      if(array_key_exists('selection', Yii::$app->request->post())) {
        $select = Yii::$app->request->post()['selection'];
        if(count($select) < 1) {
          Yii::$app->getSession()->setFlash('error', '未选择!');
          return $this->redirect(['service-script']);
        }
        $query = DcmdServiceScriptApply::findAll($select);
        $success_msg = "操作成功!";
        $err_msg = "操作失败!";
        foreach($query as $item) {
          if($item->delete()) {
            $this->oprlog(1,"reject script:".$item->app_id.$item->svr_id);
            $success_msg .= $item->id;
          }else {
            $err_msg .= $item->id;
          }
        }
      }
      return $this->redirect(['service-script']);
    }

    /**
     * Displays a single DcmdServicePoolNode model.
     * @param integer $id
     * @return mixed
     */
    private function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
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
          $model = DcmdApp::findOne($app_id);
//          $model = DcmdGroup::findOne(['gname'=>'audit']);
          ///判断用户所属的系统组是否和该应用相同
          if(Yii::$app->user->getIdentity()->sa != 1) {
            if(Yii::$app->user->getIdentity()->admin != 1) {
              $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['svr_gid']]);
            }
            else {
              $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['sa_gid']]);
            }
            if($query==NULL || $query->is_leader !=1) {
              Yii::$app->getSession()->setFlash('success', NULL);
              Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
              return $this->redirect(['index']);
            }
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
            $tr = Yii::$app->db->beginTransaction();
            try {
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
            $pool_port = DcmdServicePoolPort::find()->andWhere(['svr_pool_id'=>$svr_pool_id])->asArray()->all();
            if ($pool_port) {
              foreach($pool_port as $items) {
                if ($items['mapped_port'] != 0) {
                  $node_port = new DcmdServicePoolNodePort();
                  $node_port->ip = $ip;
                  $node_port->port_name = $items['port_name'];
                  $node_port->port = $items['mapped_port'];
                  $node_port->svr_id = $svr_id;
                  $node_port->svr_pool_id = $svr_pool_id;
                  $node_port->ctime = date('Y-m-d H:i:s');
                  $node_port->opr_uid = Yii::$app->user->getId();
                  $node_port->save();
                }
              }
            }
            DcmdServicePoolAudit::findOne($id)->delete();
            $tr->commit();
            $this->oprlog(1,"add ip:".$ip);
            $success_msg .= $ip." ";
            } catch (Exception $e) {
              $tr->rollBack();
            }
          }
          else {
            $DcmdServicePoolNode = DcmdServicePoolNode::findOne(['svr_pool_id' => $svr_pool_id, 'svr_id' => $svr_id, 'nid' => $nid, 'app_id' => $app_id, 'ip' => $ip]);
            if ($DcmdServicePoolNode) {
              $pool_node_port = DcmdServicePoolNodePort::findOne(['svr_pool_id' => $svr_pool_id,'ip'=>$ip]);
              $tr = Yii::$app->db->beginTransaction();
              try {
                if ($pool_node_port) {
                  DcmdServicePoolNodePort::deleteAll('svr_pool_id = :svr_pool_id AND ip = :ip', [':ip' => $ip, ':svr_pool_id' => $svr_pool_id]);
                }
                $DcmdServicePoolNode->delete();
                $tr->commit();
                $this->oprlog(1,"delete ip:".$ip);
              } catch (Exception $e) { 
                $tr->rollBack();
              }
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
          $pool_audit_log->audit_time = $tm;
          $pool_audit_log->ctime = $tm;
          $pool_audit_log->opr_uid = $opr_uid;
          $pool_audit_log->action = $action;
          $pool_audit_log->audit_uid = Yii::$app->user->getId();
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

      $query = DcmdServicePoolAudit::findAll($select);
      $success_msg = "请求已驳回!";
      foreach($query as $item) {
        $app_id = $item->app_id;
        $id = $item->id;
        $model = DcmdApp::findOne($app_id);
//        $model = DcmdGroup::findOne(['gname'=>'audit']);
        ///判断用户所属的系统组是否和该应用相同
        if(Yii::$app->user->getIdentity()->sa != 1) {
          $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['svr_gid']]);
          if($model==NULL || $query->is_leader !=1) {
            Yii::$app->getSession()->setFlash('success', NULL);
            Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
            return $this->redirect(['index']);
          }
        }
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
    protected function findModel($id)
    {
        if (($model = DcmdServicePoolNode::findOne($id)) !== null) {
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
