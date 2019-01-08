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
use app\models\DcmdOprLog;
use app\models\DcmdNodeGroupInfo;
use app\models\DcmdTaskNode;
use app\models\DcmdPrivate;
use app\models\DcmdTask;
use app\models\DcmdApp;
use app\models\DcmdUser;
use app\models\DcmdHostVm;
use app\models\DcmdHostVmSearch;
use app\models\DcmdVmOplog;
use app\models\DcmdPrivateSearch;
use app\models\DcmdVmInvalidSearch;
use app\models\DcmdVmInvalid;
use app\models\DcmdOprCmdRepeatExecSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * DcmdNodeController implements the CRUD actions for DcmdNode model.
 */
class DcmdPrivateController extends Controller
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
        $searchModel = new DcmdPrivateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        ///获取服务池列表
        //$query = DcmdNodeGroup::find()->orderBy('ngroup_name')->asArray()->all();
        //$dcmd_node_group = array();
        //foreach($query as $item) $dcmd_node_group[$item['ngroup_id']] = $item['ngroup_name'];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOperate()
    {
        $searchModel = new DcmdHostVmSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        ///获取服务池列表
        //$query = DcmdNodeGroup::find()->orderBy('ngroup_name')->asArray()->all();
        //$dcmd_node_group = array();
        //foreach($query as $item) $dcmd_node_group[$item['ngroup_id']] = $item['ngroup_name'];
        return $this->render('operate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionInvalid()
    {
        $searchModel = new DcmdVmInvalidSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        ///获取服务池列表
        //$query = DcmdNodeGroup::find()->orderBy('ngroup_name')->asArray()->all();
        //$dcmd_node_group = array();
        //foreach($query as $item) $dcmd_node_group[$item['ngroup_id']] = $item['ngroup_name'];
        return $this->render('invalid', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public  function actionOprvm($id,$action) 
    {
       $url = "10.11.145.45:9000/vm/".$action."/".$id;
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_HEADER, 0);
       $output = curl_exec($ch);
       curl_close($ch);
       $data = '{"data": "sucess"}';
    //   $output_array = json_decode($output,true);
 //      $data1 = array("data"=>"active");
       if($action !='status') {
         $data1 = $output;
         $data1 = json_decode($data1,true);
      //   $data1 = array('state' => 1, 'msg' => '');  
         $this->vmoplog($id,$data1,$action);
       }
       $data = $output; 
       return $data;
    }

    private function vmoplog($id,$data,$action)
    {
      $oplog = new DcmdVmOplog();
      $model = DcmdPrivate::findOne(['vm_uuid'=>$id]);
      $oplog->vm_ip = $model->vm_ip;
      $oplog->action = $action;
      $oplog->state = $data['state'];
      $oplog->start_time = date('Y-m-d H:i:s');
      $oplog->end_time = date('Y-m-d H:i:s');
      $oplog->apply_user = DcmdUser::findOne(['uid' => Yii::$app->user->getId()])->username;
      $oplog->source_ip = "0.0.0.0";
      $oplog->errmsg = $data['msg'];
      $oplog->uuid = $id;
      $oplog->save();
    } 
 
    public function actionBu()
    {
        $searchModel = new DcmdPrivateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        ///获取服务池列表
        //$query = DcmdNodeGroup::find()->orderBy('ngroup_name')->asArray()->all();
        //$dcmd_node_group = array();
        //foreach($query as $item) $dcmd_node_group[$item['ngroup_id']] = $item['ngroup_name'];
        return $this->render('bu', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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

    public function actionOpview($id)
    {
        return $this->render('opview', [
            'model' => $this->viewModel($id),
        ]);
    }

    public function actionInvalidview($id)
    {
        return $this->render('invalidview', [
            'model' => $this->invalidModel($id),
        ]);
    }

    /**
     * Updates an existing DcmdNode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
       // $status = ["未使用","使用","待下线","可回收"];
        $model = $this->findModel($id);
        if(Yii::$app->request->post()) {
          if ($model->load(Yii::$app->request->post())) {
        //    $model->status = $status[Yii::$app->request->post()['DcmdPrivate']['status']];
            $model->save();
            $this->oprlog(2,"update node:".$model->vm_uuid);
            Yii::$app->getSession()->setFlash('success', "修改成功");
            return $this->redirect(['view', 'id' => $model->id]);
          } 
         $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "修改失败:".$err_str);
        }
        return $this->render('update', [
             'model' => $model,
        ]);
    }

   public function actionUsed() {
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
        $query = DcmdPrivate::findAll($select);
        $success_msg = "操作成功!";
        $err_msg = "操作失败!";
        foreach($select as $k=>$id) {
          $model = $this->findModel($id);
          $model->state=1;
          $model->save();
          $this->oprlog(3, "update node status:".$model->vm_uuid.$model->state);     
        }
        
     }
     Yii::$app->getSession()->setFlash('success', '修改成功!');
     return $this->redirect(['index']);
   }

    public function actionGetVms($id)
    {
      $ids = "(".$id.")";
      $sql = 'select * from dcmd_vm_assign where id in '.$ids;
      $query = DcmdPrivate::findBySql($sql)->asArray()->all();
      foreach($query as $k=>$v) {
        $host_sn = $this->actionGetSn($v["host_ip"]);
        $v["host_sn"] = (string)$host_sn;
        $query[$k] = $v;
      }
      $data = json_encode($query);
      return $data;
    }

    private function actionGetSn($ip)
    {
       $output_array = array();
       $url = "http://ump.letv.cn/api/cmdb/server/search?token=e4aa3d6e3cef178ed2b8ab8df56c0592&exact_ip=".$ip;
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_HEADER, 0);
       $output = curl_exec($ch);
       curl_close($ch);
       $output_array = json_decode($output,true);
       //foreach($output_array as $otp)
       $output_array = $output_array['data']['data'][0];
       $sn = $output_array['sn'];
       return $sn;
   }

   public function actionUnuse() {
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
        $query = DcmdPrivate::findAll($select);
        $success_msg = "操作成功!";
        $err_msg = "操作失败!";
        foreach($select as $k=>$id) {
          $model = $this->findModel($id);
          $model->state=0;
          $model->save();
          $this->oprlog(3, "update node status:".$model->vm_uuid.$model->state);
        }
        
     }
     Yii::$app->getSession()->setFlash('success', '修改成功!');
     return $this->redirect(['index']);
   }
   public function actionOffline() {
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
        $query = DcmdPrivate::findAll($select);
        $success_msg = "操作成功!";
        $err_msg = "操作失败!";
        foreach($select as $k=>$id) {
          $model = $this->findModel($id);
          $model->state=e;
          $model->save();
          $this->oprlog(3, "update node status:".$model->vm_uuid.$model->state);
        }
        
     }
     Yii::$app->getSession()->setFlash('success', '修改成功!');
     return $this->redirect(['index']);
   }
   public function actionReuse() {
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
        $query = DcmdPrivate::findAll($select);
        $success_msg = "操作成功!";
        $err_msg = "操作失败!";
        foreach($select as $k=>$id) {
          $model = $this->findModel($id);
          $model->status=2;
          $model->save();
          $this->oprlog(3, "update node status:".$model->vm_uuid.$model->state);
        }
        
     }
     Yii::$app->getSession()->setFlash('success', '修改成功!');
     return $this->redirect(['index']);
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
        if (($model = DcmdPrivate::findOne($id)) !== null) {
            return $model;
        } else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function viewModel($id)
    {
        if (($model = DcmdHostVm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function invalidModel($id)
    {
        if (($model = DcmdVmInvalid::findOne($id)) !== null) {
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
