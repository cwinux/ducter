<?php

namespace app\controllers;

use Yii;
use app\models\DcmdUser;
use app\models\DcmdUserGroup;
use app\models\DcmdDepartment;
use app\models\DcmdApp;
use app\models\DcmdUserSearch;
use app\models\DcmdGroupSearch;
use app\models\DcmdAppRes;
use app\models\DcmdService;
use app\models\DcmdServicePool;
use app\models\DcmdAppResSearch;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdUserController implements the CRUD actions for DcmdUser model.
 */
class DcmdAppResourceController extends Controller
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
     * Lists all DcmdUser models.
     * @return mixed
     */
    public function actionIndex()
    {
//        if(Yii::$app->user->getIdentity()->admin != 1) {
//          Yii::$app->getSession()->setFlash('success', NULL);
//          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
//          return $this->redirect(array('index'));
//        }
        $searchModel = new DcmdAppResSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelRes($id)
    {
      $model = DcmdAppRes::findOne($id);
      $temp = DcmdApp::findOne($model->app_id);
      $query_svr=NULL;
      if($temp->is_self) {
        $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
      }
      $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
      if($query==NULL && $query_svr==NULL) {
        Yii::$app->getSession()->setFlash('success', NULL);
        Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
        return $this->redirect(array('dcmd-app/view', 'id'=>$model->app_id));
      }
      if ($model) {
        $app_id = $model->app_id;
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(array('dcmd-app/view', 'id'=>$app_id));
      }
    }

    public function actionUpdate($id)
    {
        $model = DcmdAppRes::findOne($id);
        $app_id = $model->app_id;
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model->app_id);
        $query_svr=NULL;
        if($temp->is_self) { 
          $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        }
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL && $query_svr==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/view', 'id'=>$app_id));
        }
        $ser_query = DcmdService::find()->andWhere(['app_id'=>$app_id])->asArray()->all();
        $ser_array = array();
        foreach($ser_query as $item) $ser_array[$item['svr_id']] = $item['svr_name'];
        $ser_pool_query = DcmdServicePool::find()->andWhere(['app_id'=>$app_id])->asArray()->all();
        $ser_pool_array = array();
        foreach($ser_pool_query as $item) $ser_pool_array[$item['svr_pool_id']] = $item['svr_pool'];

        if (Yii::$app->request->post()) {
          $model->load(Yii::$app->request->post());
          $model->app_id = $app_id;
          $model->ctime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
          if ($model->save()) {
            $this->oprlog(2, "modify app resource".$model->res_id);
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            return $this->redirect(['dcmd-app/view', 'id' => $app_id]);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "修改失败:".$err_str);
        }
        return $this->render('update', [
           'model' => $model,
            'ser_array' => $ser_array,
            'ser_pool_array' => $ser_pool_array,
        ]);
    }

   private function oprlog($opr_type, $sql) {
     $opr_log = new DcmdOprLog();
     $opr_log->log_table = "dcmd_app_res";
     $opr_log->opr_type = $opr_type;
     $opr_log->sql_statement = $sql;
     $opr_log->ctime = date('Y-m-d H:i:s');
     $opr_log->opr_uid = Yii::$app->user->getId();
     $opr_log->save();
   }

}
