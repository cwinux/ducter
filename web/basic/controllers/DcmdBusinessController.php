<?php

namespace app\controllers;

use Yii;
use app\models\DcmdNode;
use app\models\DcmdNodeAudit;
use app\models\DcmdServicePoolNode;
use app\models\DcmdNodeGroup;
use app\models\DcmdUserGroup;
use app\models\DcmdBusinessSearch;
use app\models\DcmdCenter;
use app\models\DcmdOprLog;
use app\models\DcmdNodeGroupInfo;
use app\models\DcmdTaskNode;
use app\models\DcmdPrivate;
use app\models\DcmdBusiness;
use app\models\DcmdApp;
use app\models\DcmdService;
use app\models\DcmdBu;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * DcmdNodeController implements the CRUD actions for DcmdNode model.
 */
class DcmdBusinessController extends Controller
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
     * Lists all DcmdBusiness models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdBusinessSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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

    public function actionCreate()
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!!");
          return $this->redirect(array('index'));
        }
        $query = DcmdPrivate::find()->select(array('business'))->distinct()->asArray()->all();
        $product = array();
        foreach ($query as $k => $v) {
          $product[$v["business"]] = $v["business"];
        }
        $query = DcmdBu::find()->select(array('bu'))->distinct()->asArray()->all();
        $bu = array();
        foreach ($query as $k => $v) {
          $bu[$v["bu"]] = $v["bu"];
        }

        $model = new DcmdBusiness();
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'product' => $product,
                'bu' => $bu,
            ]);
        }
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
            $this->oprlog(2,"update node:".$model->id);
            Yii::$app->getSession()->setFlash('success', "修改成功");
            return $this->redirect(['view', 'id' => $model->id]);
          } 
         $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "修改失败:".$err_str);
        }
        $query = DcmdPrivate::find()->select(array('business'))->distinct()->asArray()->all();
        $product = array();
        foreach ($query as $k => $v) {
          $product[$v["business"]] = $v["business"];
        }
        $query = DcmdBu::find()->select(array('bu'))->distinct()->asArray()->all();
        $bu = array();
        foreach ($query as $k => $v) {
          $bu[$v["bu"]] = $v["bu"];
        }

        return $this->render('update', [
             'model' => $model,
                'product' => $product,
                'bu' => $bu,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->oprlog(3,"delete node:".$model->bu." ".$model->product);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['index']);
    }

    public function actionDeleteAll()
    {

      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择项!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $suc_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->findModel($id);
        $this->oprlog(3, "delete node:".$model->bu." ".$model->product);
        $suc_msg .=$model->id.':删除成功<br>';
        $model->delete();
      }
      if($suc_msg != "") Yii::$app->getSession()->setFlash('success', $suc_msg);
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
        if (($model = DcmdBusiness::findOne($id)) !== null) {
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
