<?php

namespace app\controllers;

use Yii;
use app\models\DcmdNodeGroup;
use app\models\DcmdNode;
use app\models\DcmdUserGroup;
use app\models\DcmdApp;
use app\models\DcmdService;
use app\models\DcmdServicePool;
use app\models\DcmdServicePoolAudit;
use yii\data\ActiveDataProvider;
use app\models\DcmdNodeSearch;
use app\models\DcmdCbsApp;
use app\models\DcmdNodeGroupSearch;
use app\models\DcmdCbsAppNode;
use app\models\DcmdCbsAppNodeSearch;
use app\models\DcmdCbsMoxi;
use app\models\DcmdCbsMoxiSearch;
use app\models\DcmdServicePoolNode;
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
class DcmdCbaseMoxiController extends Controller
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
        $searchModel = new DcmdCbsMoxiSearch();
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
        $model = new DcmdServicePoolNode();

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
   public function actionSelectNodeGroup($app_id) 
   {
        $model = DcmdCbsApp::findOne($app_id);
        ///判断用户所属的系统组是否和该应用相同
       $searchModel = new DcmdNodeGroupSearch();
       $dataProvider = $searchModel->search(array());
       return $this->render('select_node_group', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
          'app_id' => $app_id,
       ]);
   }

   public function actionShowNodeList($app_id) {
        $model = DcmdCbsApp::findOne($app_id);
        $node_group = array();
        $exist_nid = array();
        if (array_key_exists("selection", Yii::$app->request->post())) { 
          $node_group = Yii::$app->request->post()["selection"];
          $pool_node = DcmdCbsAppNode::find()->where(["app_id"=>$app_id])->asArray()->all();
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
       ]);
        
   }
  
   public function actionAddNode() {
     $app_id = Yii::$app->request->post()["app_id"];
     $model = DcmdApp::findOne($app_id);
     $success_msg = "";
     $err_msg = "";
     if (array_key_exists("selection", Yii::$app->request->post())){
       $success_msg = "成功添加:";
       $nid_str = "nid in (0";
       foreach(Yii::$app->request->post()["selection"] as $k=>$v)
         $nid_str .= ",".$v;
       $nid_str .= ")";
       $query = DcmdNode::find()->where($nid_str)->asArray()->all();
       $tm =  date('Y-m-d H:i:s');
       foreach($query as $item) {
         $temp = DcmdCbsAppNode::findOne(['ip' => $item['ip']]);
         if($temp) {
           $err_msg .= "已经有池子使用该节点:".$item['ip']." ";
           continue;
         }
         $dcmd_cbase_app_node = new DcmdCbsAppNode();
         $dcmd_cbase_app_node->app_id = $app_id;
         $dcmd_cbase_app_node->nid = $item['nid'];
         $dcmd_cbase_app_node->ip = $item['ip'];
         $dcmd_cbase_app_node->state = 0;
         $dcmd_cbase_app_node->ctime = $tm;
         $dcmd_cbase_app_node->save();
         $this->oprlog(1,"add ip:".$item['ip']);
         $success_msg .= $item['ip']." ";
       } 
     }
     if($success_msg != "")Yii::$app->getSession()->setFlash('success', $success_msg);
     if($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
     return $this->redirect(array('dcmd-cbase-app/view','id'=>$app_id));
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
            return $this->redirect(['dcmd-cbase-app/view', 'id' => $model->app_id]);
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
      $this->oprlog(3,"delete cbsase pool node:".$model->ip);
      $model->delete();
      Yii::$app->getSession()->setFlash('success', '删除成功!');
      return $this->redirect(array('dcmd-cbse-app/view', 'id'=>$app_id));
    }

    public function actionDeleteAll()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择服务!');
        return $this->redirect(['index']);
      }

      $ret_msg = '';
      $select = Yii::$app->request->post()['selection'];
      foreach($select as $k=>$id) {
        $model = $this->findModel($id);
        $model->delete();
        $ret_msg .= $model->ip." ";
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
        if (($model = DcmdCbsAppNode::findOne($id)) !== null) {
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
