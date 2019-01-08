<?php

namespace app\controllers;

use Yii;
use app\models\DcmdServicePool;
use app\models\DcmdServicePoolSearch;
use app\models\DcmdServicePoolNode;
use app\models\DcmdApp;
use app\models\DcmdUserGroup;
use app\models\DcmdService;
use app\models\DcmdServicePoolNodeSearch;
use app\models\DcmdServicePoolAudit;
use app\models\DcmdServicePoolAttr;
use app\models\DcmdServicePoolAttrDef;
use app\models\DcmdOprLog;
use app\models\DcmdCbsBuckets;
use app\models\DcmdCbsBucketsSearch;
use app\models\DcmdCbsBussiness;
use app\models\DcmdCbasePool;
use app\models\DcmdOprCmdSearch;
use app\models\DcmdCbsAppNodeSearch;
use app\models\DcmdOprCmdRepeatExecSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
/**
 * DcmdServicePoolController implements the CRUD actions for DcmdServicePool model.
 */
class DcmdCbaseBucketsController extends Controller
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
     * Lists all DcmdServicePool models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdCbsBucketsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdServicePool model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new DcmdCbsAppNodeSearch();
        $con = array();
    /*    $con['DcmdServicePoolNodeSearch'] = array('svr_pool_id'=>$id);
        if(array_key_exists('DcmdServicePoolNodeSearch', Yii::$app->request->queryParams))
           $con = array_merge($con, Yii::$app->request->queryParams);*/
        $poolModel = new DcmdCbasePool();
        $poolModel->findOne($id);
        $app_id = $poolModel->app_id;
        $con['DcmdCbsAppNodeSearch']['app_id'] = $app_id;
        $dataProvider = $searchModel->search($con, false);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new DcmdServicePool model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($app_id)
    {
        $model = new DcmdCbsBuckets();
        $model->app_id = $app_id;
        $model->utime = date('Y-m-d H:i:s');
        $model->ctime = date('Y-m-d H:i:s');
        $user = array();
        $query = DcmdCbsBussiness::find()->asArray()->All();
        if ($query) {
          foreach($query as $item) {
            $user[$item['uid']] = $item['bussiness'];
          }
        }
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
          if($model->save()) {
            $this->oprlog(1,"insert bucket :".$model->name);
            Yii::$app->getSession()->setFlash('success', "添加成功");
            return $this->redirect(array('dcmd-cbase-app/view', 'id'=>$app_id));
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
          return $this->redirect(array('dcmd-cbase-app/view', 'id'=>$app_id));
        } 
    //    $model = new DcmdCbasePool();
        return $this->render('create', [
              'model' => $model,
              'user' => $user,
        ]);
        
    }

    /**
     * Updates an existing DcmdServicePool model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service-pool/view', 'id'=>$id, 'show_div'=>'dcmd-service-pool'));
        }
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
          if($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('error',NULL);
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            $this->oprlog(2,"update service pool:".$model->svr_pool);
            return $this->redirect(['view', 'id' => $model->svr_pool_id, 'show_div'=>'dcmd-service-pool']);  
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "修改失败:".$err_str);
        }
        return $this->render('update', [
             'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DcmdServicePool model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $svr_id=NULL)
    {
        $model = $this->findModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$model['svr_id']));
        }
        $node = DcmdServicePoolNode::find()->where(['svr_pool_id' => $id])->one();
        if($node) {
          Yii::$app->getSession()->setFlash('error', '池子设备不为空,不可删除!');
        }else {
          ///删除服务池属性
          DcmdServicePoolAttr::deleteAll(['svr_pool_id'=>$id]);
          $this->oprlog(3,"delete service pool:".$model->svr_pool);
          $model->delete();
          Yii::$app->getSession()->setFlash('success', '删除成功!');
        }
        if ($svr_id) {
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        return $this->redirect(['index']);
    }

    public function actionDeleteAll()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择服务池!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $err_msg = "";
      $suc_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->findModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
           $err_msg .=$model->svr_pool.":没有权限删除<br>";
           continue;
        }
        $node = DcmdServicePoolNode::find()->where(['svr_pool_id' => $id])->one();
        if($node) {
          $err_msg .= $model->svr_pool.':服务池子设备不为空,不可删除<br>';
          continue;
        }else {
          ///删除服务池属性
          DcmdServicePoolAttr::deleteAll(['svr_pool_id'=>$id]);
          $model->delete();
          $suc_msg .=$model->svr_pool.':删除成功<br>';
        }
      }
      if($suc_msg != "") Yii::$app->getSession()->setFlash('success', $suc_msg);
      if($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['index']);
    }

    public function actionDeleteSelect()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择服务!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $ret_msg = "删除以下ip请求已提交审批!";
      $tm =  date('Y-m-d H:i:s');
      foreach($select as $k=>$id) {
        $model = $this->nodeModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
           $ret_msg .="没有权限删除:".$model->ip." ";
           continue;
        }
        $server_pool_audit = new DcmdServicePoolAudit();
        $server_pool_audit->svr_pool_id = $model['svr_pool_id'];
        $server_pool_audit->svr_id = $model['svr_id'];
        $server_pool_audit->app_id = $model['app_id'];

        $server_pool_audit->nid = $model['nid'];
        $server_pool_audit->ip = $model['ip'];
        $server_pool_audit->utime = $tm;
        $server_pool_audit->ctime = $tm;
        $server_pool_audit->action = "delete";
        $server_pool_audit->opr_uid = Yii::$app->user->getId();
        $server_pool_audit->save();
        $ret_msg .= $model->ip." ";
      }
      Yii::$app->getSession()->setFlash('success', $ret_msg);
      return $this->redirect(['index']);
    }

    public function actionOpr($svr_pool_id) {
      $ips = $this->getSvrPoolNode($svr_pool_id);
      if($ips == "") {
        Yii::$app->getSession()->setFlash('error', '未选择设备!');
        return $this->redirect(['index']);
      }
      ///获取操作列表
      $searchModel = new DcmdOprCmdSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

      return $this->render('opr', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
          'ips' => $ips,
      ]);
    }

    public function actionRepeatOpr($svr_pool_id) {
      $ips = $this->getSvrPoolNode($svr_pool_id);
      if($ips == "") {
        Yii::$app->getSession()->setFlash('error', '未选择设备!');
        return $this->redirect(['index']);
      }
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

    protected function getSvrPoolNode($id) {
      $query = DcmdServicePoolNode::find()->where(['svr_pool_id'=>$id])->all();
      $ips = "";
      if($query) {  
        foreach($query as $item) $ips .= $item->ip.";";
      }
      return $ips;
    }
    /**
     * Finds the DcmdServicePool model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdServicePool the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdCbasePool::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function nodeModel($id)
    {
        if (($model = DcmdServicePoolNode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

   private function oprlog($opr_type, $sql) {
     $opr_log = new DcmdOprLog();
     $opr_log->log_table = "dcmd_service_pool";          
     $opr_log->opr_type = $opr_type;
     $opr_log->sql_statement = $sql;
     $opr_log->ctime = date('Y-m-d H:i:s');
     $opr_log->opr_uid = Yii::$app->user->getId();
     $opr_log->save();
   }
}
