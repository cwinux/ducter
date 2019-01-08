<?php

namespace app\controllers;

use Yii;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\DcmdService;
use app\models\DcmdServicePool;
use app\models\DcmdServiceSearch;
use app\models\DcmdServicePoolSearch;
use app\models\DcmdAppPkgVersionSearch;
use app\models\DcmdApp;
use app\models\DcmdUser;
use app\models\DcmdUserGroup;
use app\models\DcmdAppPkgUploadSearch;
use app\models\DcmdAppUploadHistory;
use app\models\DcmdServicePoolGroupSearch;
use app\models\DcmdServicePoolGroup;
use app\models\DcmdAppUploadHistorySearch;
use app\models\DcmdAppPkgUpload;
use app\models\DcmdServiceArchDiagramSearch;
use app\models\DcmdServiceCiSearch;
use app\models\DcmdServiceCi;
use app\models\DcmdServiceCiJob;
use app\models\DcmdServiceCiJobSearch;
use app\models\DcmdServiceArchDiagram;
use app\models\DcmdServicePoolNode;
use app\models\DcmdServicePort;
use app\models\DcmdServicePortSearch;
use app\models\DcmdServicePoolNodePort;
use app\models\DcmdServicePoolPort;
use app\models\DcmdAppResSearch;
use app\models\DcmdAppRes;
use app\models\DcmdServiceScript;
use app\models\DcmdServiceScriptApply;
use app\models\DcmdOprLog;
use app\models\DcmdTaskTemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
/**
 * DcmdServiceController implements the CRUD actions for DcmdService model.
 */
class DcmdServiceController extends Controller
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
     * Lists all DcmdService models.
     * @return mixed
     */
    public function actionIndex()
    {
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
        foreach($query as $item) $app[$item['app_id']] = $item['app_name'];


        $searchModel = new DcmdServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'app' => $app,
        ]);
    }

    /**
     * Displays a single DcmdService model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        ///$query = DcmdServicePool::find()->andWhere(['svr_id'=>$id]);
        $searchModel = new DcmdServicePoolSearch();
        $con = array();
        $con['DcmdServicePoolSearch'] = array('svr_id' => $id);
        if(array_key_exists('DcmdServicePoolSearch', Yii::$app->request->queryParams))
          $con = array_merge($con,Yii::$app->request->queryParams);
        $con['DcmdServicePoolSearch']['svr_id'] = $id;
        $dataProvider = $searchModel->search($con);
        $service = DcmdService::findOne($id);

        $con_res = array();
        $con_res['DcmdAppResSearch'] = array('svr_id'=>$id);
        if(array_key_exists('DcmdAppResSearch', Yii::$app->request->queryParams))
          $con_res = array_merge($con_res, Yii::$app->request->queryParams);
        $con_res['DcmdAppResSearch']['svr_id'] = $id;
        $con_res['DcmdAppResSearch']['app_id'] = $service->app_id;
        $resSearch = new DcmdAppResSearch();
        $resProvider = $resSearch->search($con_res);       

        $con_upload = array();
        $con_upload['DcmdAppPkgUploadSearch'] = array('svr_id'=>$id);
        if(array_key_exists('DcmdAppPkgUploadSearch', Yii::$app->request->queryParams))
          $con_upload = array_merge($con_upload, Yii::$app->request->queryParams);
        $con_upload['DcmdAppPkgUploadSearch']['svr_id'] = $id;
        $con_upload['DcmdAppPkgUploadSearch']['is_accept'] = 0;
        $uploadSearch = new DcmdAppPkgUploadSearch();
        $uploadProvider = $uploadSearch->search($con_upload); 

        $con_group = array();
        $con_group['DcmdServicePoolGroupSearch'] = array('svr_id'=>$id);
        $groupSearch = new DcmdServicePoolGroupSearch();
        $groupProvider = $groupSearch->search($con_group);

        $upload_pkg = array();
        $upload_pkg['DcmdAppPkgVersionSearch']['svr_id'] = $id;
        $pkgSearch = new DcmdAppPkgVersionSearch();
        $pkgProvider = $pkgSearch->search($upload_pkg);

	$port_svr = array();
        $port_svr['DcmdServicePortSearch']['svr_id'] = $id;
	$SvrPortSearch = new DcmdServicePortSearch();
        $SvrPortProvider = $SvrPortSearch->search($port_svr);

        $filemodel = new UploadForm();
        $filePath = $this->fileExists(Yii::$app->basePath.'/uploads/');
        if (Yii::$app->request->isPost) {
            $filemodel->file = UploadedFile::getInstance($filemodel, 'file');

            if ($filemodel->file && $filemodel->validate()) {
                $filemodel->file->saveAs('uploads/' . $filemodel->file->baseName . '.' . $filemodel->file->extension);
            }
        }

        ///获取任务模版列表
        $tmpt_searchModel = new DcmdTaskTemplateSearch();
        $params["DcmdTaskTemplateSearch"]["svr_id"] = $id;
        $taskTemptDataProvider = $tmpt_searchModel->search($params, 1000);

        $pool_group = array();
        $pool_g = DcmdServicePoolGroup::find()->andWhere(['app_id'=>$service->app_id,'svr_id'=>$service->svr_id])->asArray()->all();
        if($pool_g) {
          foreach($pool_g as $item) $pool_group[$item['pool_group']] = $item['pool_group'];
        }

        $ci_search = array();
        $ci_search['DcmdServiceCiSearch']['svr_id'] = $id;
        $ciSearch = new DcmdServiceCiSearch();
        $ciProvider = $ciSearch->search($ci_search);

        $ci_job_search = array();
        $ci_job_search['DcmdServiceCiJobSearch']['svr_id'] = $id;
        $ciJobSearch = new DcmdServiceCiJobSearch();
        $ciJobProvider = $ciJobSearch->search($ci_job_search);

        $ret_msg = '<table class="table table-striped table-bordered"><tbody>';
        $ret_msg .= "<tr><td>服务池组</td><td>已保存脚本</td><td>审批中脚本</td><td>当前脚本</td></tr>";
        $service_script = DcmdServiceScriptApply::find()->andWhere(['svr_id'=>$id])->asArray()->all();
        $script_con = "svr_id=".$id;
        if($service_script) {
          $script_con .= " and pool_group not in ('0'";
          foreach($service_script as $k=>$v) {
            $script_con .= ",'".$v['pool_group']."'";
            $online_script = DcmdServiceScript::findOne(['svr_id'=>$id,'pool_group'=>$v['pool_group']]);
            if($v['is_apply']==0) {
              $ret_msg .="<tr><td>".$v['pool_group']."</td><td><a href='index.php?r=dcmd-service/load-script&id=".$v['id']."' target=_blank>查看</a></td><td></td>";
            }else {
              $ret_msg .="<tr><td>".$v['pool_group']."</td><td></td><td><a href='index.php?r=dcmd-service/load-submit&id=".$v['id']."' target=_blank>查看</a></td>";
            }
            if($online_script) {
              $ret_msg .= "<td><a href='index.php?r=dcmd-service/load-script&id=".$online_script->id."' target=_blank>查看</a></td>";
            }else {
              $ret_msg .= "<td></td>";
            }
            $ret_msg .= "</tr>";
          }
          $script_con .= ")";
        }
        $online_script = DcmdServiceScript::find()->where($script_con)->asArray()->all();
        if($online_script) {
          foreach($online_script as $k=>$v) {
            $ret_msg .="<tr><td>".$v['pool_group']."</td><td></td><td></td><td><a href='index.php?r=dcmd-service/load-online-script&id=".$v['id']."' target=_blank>查看</a></td><tr>";
          }
        }
        $ret_msg .= "</table>";
      
        $show_div = "dcmd-service";
        if(array_key_exists("show_div", Yii::$app->request->queryParams))
          $show_div = Yii::$app->request->queryParams['show_div'];

        $serv = $this->findModel($id);
        $app = DcmdApp::findOne($serv->app_id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'resProvider' => $resProvider,
            'resSearch' => $resSearch,
            'uploadSearch' => $uploadSearch,
            'uploadProvider' => $uploadProvider,
            'pkgProvider' => $pkgProvider,
            'groupProvider' => $groupProvider,
            'groupSearch' => $groupSearch,
            'taskTemptDataProvider' => $taskTemptDataProvider,
            'filemodel' => $filemodel,
            'pool_group' => $pool_group,
            'ciProvider' => $ciProvider,
            'ciJobProvider' => $ciJobProvider,
            'show_div' => $show_div,
            'ret_msg' => $ret_msg,
            'is_self' => $app->is_self,
            'svrportProvider' => $SvrPortProvider,
        ]);
    }

    /**
     * Creates a new DcmdService model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($app_id)
    {
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($app_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        $query_svr=NULL;
        if($temp->is_self) {
          $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        }
        if($query==NULL && $query_svr==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/view', 'id'=>$app_id));
        }
        $model = new DcmdService();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->res_id = 0;
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          $model->owner = $model->opr_uid;
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->oprlog(1, "insert service:".$model->svr_name);
            Yii::$app->getSession()->setFlash('success', '添加成功!'); 
            return $this->redirect(['view', 'id' => $model->svr_id]);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
        }
        $model->app_id = $app_id;
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionSelectService($app_id)
    {
        $temp = DcmdApp::findOne($app_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        $query_svr=NULL;
        if($temp->is_self) {
          $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        }
        if($query==NULL && $query_svr==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/view', 'id'=>$app_id));
        }
        $svr_array = array();
        $app_svr = DcmdService::find()->andWhere(['app_id'=>$app_id])->asArray()->all();
        foreach($app_svr as $svr) {
          array_push($svr_array,$svr['svr_name']);
        }
        return $this->render('select_service', [
         'service' => $svr_array,
         'app_id' => $app_id,
        ]);

    }

    public function actionCopyService()
    {
        $temp = DcmdApp::findOne(Yii::$app->request->post()['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        $query_svr=NULL;
        if($temp->is_self) {
          $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        }
        if($query==NULL && $query_svr==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        if (Yii::$app->request->post()) {
          $tr = Yii::$app->db->beginTransaction();
          try {
            $service = DcmdService::findOne(['svr_name' => Yii::$app->request->post()['SvrName'],'app_id'=>Yii::$app->request->post()['app_id']]);
            $service_model = new DcmdService();
            $service_model->svr_name = Yii::$app->request->post()['svr_name'];
            $service_model->service_tree = $service->service_tree;
            $service_model->svr_path = $service->svr_path;
            $service_model->run_user = $service->run_user;
            $service_model->app_id = Yii::$app->request->post()['app_id'];
            $service_model->node_multi_pool = $service->node_multi_pool;
            $service_model->owner = $service->owner;
            $service_model->utime = date('Y-m-d H:i:s');
            $service_model->ctime = date('Y-m-d H:i:s');
            $service_model->opr_uid = Yii::$app->user->getId();
            $service_model->svr_alias = Yii::$app->request->post()['svr_name'];
            $service_model->res_id = 0;
            $service_model->script_md5 = "";
            $service_model->node_multi_pool = 1;
            $service_model->svr_mem = $service->svr_mem;
            $service_model->svr_cpu = $service->svr_cpu;
            $service_model->svr_net = $service->svr_net;
            $service_model->svr_io = $service->svr_io;
            $service_model->image_name = $service->image_name;
            $service_model->comment = $service->comment;

            if ($service_model->save()) {
              $tr->commit();
              $this->oprlog(1, "insert service".$service_model->svr_id);
              Yii::$app->getSession()->setFlash('success', '添加成功!');
              return $this->redirect(array('dcmd-service/view', 'id' => $service_model->svr_id));
            }
          } catch (Exception $e) {
            $tr->rollBack();
            Yii::$app->getSession()->setFlash('error', "添加失败!");
          }
        }
    }

    public function actionCreateServicePort($app_id,$svr_id)
    {
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($app_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        $query_svr=NULL;
        if($temp->is_self) {
          $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        }
        if($query==NULL && $query_svr==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        $model = new DcmdServicePort();
        if (Yii::$app->request->post()) {
          $tr = Yii::$app->db->beginTransaction();
          try {
            $service_pool = DcmdServicePool::find()->andWhere(['svr_id'=>$svr_id])->asArray()->all();
            if($service_pool) {
              foreach($service_pool as $item) {
                $pool_model = new DcmdServicePoolPort();
                $pool_model->svr_pool_id = $item['svr_pool_id'];
                $pool_model->svr_id = $item['svr_id'];
                $pool_model->port_name = Yii::$app->request->post()['DcmdServicePort']['port_name'];
                $pool_model->port = Yii::$app->request->post()['DcmdServicePort']['def_port'];
                $pool_model->mapped_port = 0;
                $pool_model->utime = date('Y-m-d H:i:s');
                $pool_model->ctime = date('Y-m-d H:i:s');
                $pool_model->opr_uid = Yii::$app->user->getId();
                $pool_model->save();
              }
            }
            $model->utime = date('Y-m-d H:i:s');
            $model->ctime = $model->utime;
            $model->opr_uid = Yii::$app->user->getId();
            $model->svr_id = $svr_id;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
              $tr->commit();
              $this->oprlog(1, "insert service port:".$model->svr_id);
              Yii::$app->getSession()->setFlash('success', '添加成功!');
              return $this->redirect(array('dcmd-service/view', 'id' => $model->svr_id));
            }
         } catch (Exception $e) { 
            $err_str = "";
            $tr->rollBack();
            foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
            Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
          }
        }
//        $model->app_id = $app_id;
        return $this->render('create-service-port', [
            'model' => $model,
        ]);
    }

    public function actionCreatePoolGroup($app_id,$svr_id)
    {
        $model = new DcmdServicePoolGroup();
        if(Yii::$app->request->post()) {
          $model->ctime = date('Y-m-d H:i:s'); 
          $model->opr_uid = Yii::$app->user->getId();
          if($model->load(Yii::$app->request->post())) {
            $model->app_id = $app_id;
            $model->svr_id = $svr_id;
            if($model->save()) {
              $this->oprlog(1, "insert service pool group:".$model->pool_group);
              Yii::$app->getSession()->setFlash('success', '添加成功!');
              return $this->redirect(['view', 'id' => $model->svr_id]);
            }
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
        }
        $model->app_id = $app_id;
        $model->svr_id = $svr_id;
        return $this->render('create-pool-group', [
            'model' => $model,
        ]);
    }

    public function actionCreatePkg($app_id,$svr_id)
    {
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($app_id);
        $app_name = $temp->app_name;
        $service = DcmdService::findOne($svr_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        if($query==NULL && $query_svr==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        $model = new DcmdAppPkgVersionSearch();
        $user = DcmdUser::findOne(Yii::$app->user->getId());
        if (Yii::$app->request->post()) {
          if ($model->load(Yii::$app->request->post())){
            $model->ctime = date('Y-m-d H:i:s');
            $model->username = $user->username;
            $model->app_id = $app_id;
            $model->svr_id = $svr_id;
            if( $model->save()) {
              $this->oprlog(1, "insert pkg:".$model->version);
              Yii::$app->getSession()->setFlash('success', '添加成功!');
              return $this->redirect(['view', 'id' => $svr_id]);
            }
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
        }
        $pool = DcmdServicePool::find()->andWhere(['app_id'=>$app_id,'svr_id'=>$svr_id])->asArray()->all();
        $svr_pool = array();
        foreach($pool as $item) {
          $svr_pool[$item['svr_pool_id']] = $item['svr_pool'];
        }
        return $this->render('create-pkg', [
            'model' => $model,
        ]);
    }

    public function actionCreateCi($app_id,$svr_id)
    {
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($app_id);
        $app_name = $temp->app_name;
        $service = DcmdService::findOne($svr_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        if($query==NULL && $query_svr==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        $model = DcmdServiceCi::findOne(['app_id'=>$app_id,'svr_id'=>$svr_id]);
        if($model) {
        if (Yii::$app->request->post()) {
          if ($model->load(Yii::$app->request->post())){
            $model->ctime = date('Y-m-d H:i:s');
            $model->utime = $model->ctime;
            $model->opr_uid = Yii::$app->user->getId();
            $model->app_id = $app_id;
            $model->ci_user = Yii::$app->request->post()['DcmdServiceCi']['ci_user'];
            $model->ci_passwd = Yii::$app->request->post()['DcmdServiceCi']['ci_passwd'];
            $model->ci_jenkins_url = Yii::$app->request->post()['DcmdServiceCi']['ci_jenkins_url'];
            $model->svr_id = $svr_id;
            if( $model->save()) {
              $this->oprlog(1, "insert ci info:".$model->ci_url);
              Yii::$app->getSession()->setFlash('success', '更新成功!');
              return $this->redirect(['view', 'id' => $svr_id,'show_div'=>'dcmd-compile']);
            }
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "更新失败:".$err_str);
        }
        }else {
          $model = new DcmdServiceCiSearch();
          if (Yii::$app->request->post()) {
          if ($model->load(Yii::$app->request->post())){
            $model->ctime = date('Y-m-d H:i:s');
            $model->utime = $model->ctime;
            $model->opr_uid = Yii::$app->user->getId();
            $model->ci_user = Yii::$app->request->post()['DcmdServiceCiSearch']['ci_user'];
            $model->ci_passwd = Yii::$app->request->post()['DcmdServiceCiSearch']['ci_passwd'];
            $model->ci_jenkins_url = Yii::$app->request->post()['DcmdServiceCiSearch']['ci_jenkins_url'];
            $model->app_id = $app_id;
            $model->svr_id = $svr_id;
            $model->comment = Yii::$app->request->post()['DcmdServiceCiSearch']['comment'];
            if( $model->save()) {
              $this->oprlog(1, "insert ci info:".$model->ci_url);
              Yii::$app->getSession()->setFlash('success', '添加成功!');
              return $this->redirect(['view', 'id' => $svr_id,'show_div'=>'dcmd-compile']);
            }
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
        }
        }
        return $this->render('create-ci', [
            'model' => $model,
        ]);
    }

    public function actionDeleteCi($ci_id,$app_id,$svr_id)
    {
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($app_id);
        $app_name = $temp->app_name;
        $service = DcmdService::findOne($svr_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        if($query==NULL && $query_svr==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        $model = DcmdServiceCi::findOne($ci_id);
        if( $model->delete()) {
          $this->oprlog(1, "delete ci info:".$model->ci_url);
          Yii::$app->getSession()->setFlash('success', '删除成功!');
          return $this->redirect(['view', 'id' => $svr_id]);
        }
        $err_str = "";
        foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
        Yii::$app->getSession()->setFlash('error', "删除失败:".$err_str);
        return $this->render('view', ['id' => $svr_id]);
    }

    public function actionDeleteServicePort($svr_port_id)
    {
        $model = DcmdServicePort::findOne($svr_port_id);
        $tr = Yii::$app->db->beginTransaction();
        try {
          $model->delete();
          $pool_port = DcmdServicePoolPort::deleteAll(['svr_id'=>$model['svr_id'],'port_name'=>$model['port_name'],'port'=>$model['def_port']]);
          $pool_node_port = DcmdServicePoolNodePort::deleteAll(['svr_id'=>$model['svr_id'],'port_name'=>$model['port_name']]);
          $tr->commit();
          $this->oprlog(1, "delete service port:".$model->svr_port_id);
          Yii::$app->getSession()->setFlash('success', '删除成功!');
          return $this->redirect(['view', 'id' => $model['svr_id']]);
        } catch (Exception $e) {
          $tr->rollBack();
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "删除失败:".$err_str);
          return $this->render('view', ['id' => $model['svr_id']]);
       }
    }

    public function actionCreateCiJob($svr_id)
    {
        ///仅仅用户与该应用在同一个系统组才可以操作
        $ci_info = DcmdServiceCi::findOne(['svr_id'=>$svr_id]);
        if(!$ci_info) {
          Yii::$app->getSession()->setFlash('error', "请先配置CI信息!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        $app_id = $ci_info->app_id;
        $ci_id = $ci_info->ci_id;
        $temp = DcmdApp::findOne($app_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        $query_svr=NULL;
        if($temp->is_self) {
          $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        }
        if($query==NULL && $query_svr==NULL) {
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        $model = new DcmdServiceCiJob();
        if (Yii::$app->request->post()) {
//          $token = "guanhongbiao:abc123456";
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $output_array = array();
          $ci_jenkins_url = $ci_info->ci_jenkins_url;
          $ci_url = $ci_info->ci_url;
          $token = $ci_info->ci_user.':'.$ci_info->ci_passwd;
          $url = $ci_url."lastBuild/api/json?pretty=true";
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_USERPWD, $token);
       //curl_setopt($ch, CURLOPT_POST, 1);
       //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          $output = curl_exec($ch);
          curl_close($ch);
          $output_array = json_decode($output,true);
       //foreach($output_array as $otp)
          $last_id = $output_array['id'];

          $curmb = array();
//          $demain = explode('/job',$ci_url);
//          $url = $demain[0];
//          $demain = explode('/',$ci_url);
//          $url = $demain[0]."//".$demain[1].$demain[2];
//          $url = $url."/crumbIssuer/api/json?pretty=true";
          $url = $ci_jenkins_url."crumbIssuer/api/json?pretty=true";
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_USERPWD, $token);
          $output = curl_exec($ch);
          curl_close($ch);
          if($output) $curmb = json_decode($output,true);

          $url = $ci_url.'buildWithParameters';
          $GIT_BRANCH = Yii::$app->request->post()['DcmdServiceCiJob']['source_branch'];
          $GIT_SHA1 = Yii::$app->request->post()['DcmdServiceCiJob']['source_sha1'];
          $GIT_XML = Yii::$app->request->post()['DcmdServiceCiJob']['source_xml'];
          $uuid = $this->create_uuid();
          $pkg_version = Yii::$app->request->post()['DcmdServiceCiJob']['pkg_version'];
          $user = DcmdUser::findOne(Yii::$app->user->getId());
          $USER = $user->username;
          $app = DcmdApp::findOne($app_id);
          $APP = $app->app_name;
          $svr = DcmdService::findOne($svr_id);
          $SVR = $svr->svr_name;
          $post_data = 'APOLLO_GIT_BRANCH='.$GIT_BRANCH.'&APOLLO_GIT_SHA1='.$GIT_SHA1.'&APOLLO_GIT_XML='.$GIT_XML.'&APOLLO_JOB_ID='.$uuid.'&APOLLO_PKG_VERSION='.$pkg_version.'&APOLLO_USER='.$USER.'&APOLLO_APP='.$APP.'&APOLLO_SVR='.$SVR;
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
      //$header = array('Jenkins-Crumb:3ddf401693a438c7914e290ccbcd015a');
          if($curmb) {
            $header = ['Jenkins-Crumb:'.$curmb['crumb']];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
          }
      
          curl_setopt($ch, CURLOPT_HEADER, 1); //返回response头部信息
          curl_setopt($ch, CURLINFO_HEADER_OUT, true); //TRUE 时追踪句柄的请求字符串，从 PHP 5.1.3 开始可用。这个很关键，就是允许你查看请求header
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($ch, CURLOPT_USERPWD, $token);
          curl_exec($ch);
          curl_close($ch);  

//          sleep(10);
//          $ci_url = $ci_info->ci_url;
//          $url = $ci_url."lastBuild/api/json?pretty=true";
//          $ch = curl_init();
//          curl_setopt($ch, CURLOPT_URL, $url);
//          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//          curl_setopt($ch, CURLOPT_HEADER, 0);
//          curl_setopt($ch, CURLOPT_USERPWD, $token);
//          $output = curl_exec($ch);
//          curl_close($ch);
//          $output_array = json_decode($output,true);
//       //foreach($output_array as $otp)
//          $after_last_id = $output_array['id'];

          $job_id = "";
          $state = "undo";
          for($j=0;$j<20;$j++) {
            $ci_url = $ci_info->ci_url;
            $url = $ci_url."lastBuild/api/json?pretty=true";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_USERPWD, $token);
            $output = curl_exec($ch);
            curl_close($ch);
            $output_array = json_decode($output,true);
         //foreach($output_array as $otp)
            $after_last_id = $output_array['id'];

            for($i=$last_id;$i<=$after_last_id;$i++) {
              $ci_url = $ci_info->ci_url;
              $url = $ci_url.$i."/api/json?pretty=true";
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($ch, CURLOPT_HEADER, 0);
              curl_setopt($ch, CURLOPT_USERPWD, $token);
           //curl_setopt($ch, CURLOPT_POST, 1);
           //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
              $output = curl_exec($ch);
              curl_close($ch);
              $output_array = json_decode($output,true);
          //    if(array_walk_recursive($output_array, 'recur',$uuid)) {$job_id = $output_array['id'];break;}
  //            print_r($output_array);
              if($this->recur($uuid, $output_array)) {
                $job_id = $output_array['id'];
                if($output_array['result']) {$state = $output_array['result'];}
                break;
              }
            }
            if($job_id) break;
            sleep(1);
          }
          if(!$job_id) {
            Yii::$app->getSession()->setFlash('error', "添加失败");
            return $this->redirect(['view', 'id' => $svr_id,'show_div'=>'dcmd-compile']);
          }


          $model->opr_uid = Yii::$app->user->getId();
          $model->app_id = $app_id;
          $model->svr_id = $svr_id;
          $model->ci_job = $job_id;
          $model->ci_type = $ci_info->ci_type;
          $model->ci_url = $ci_info->ci_url;
          $model->pkg_md5 = "";
          $model->source_commit_id = "";
          $model->state = $state;
          $model->ci_id = $ci_info->ci_id;
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->oprlog(1, "insert ci job:".$model->id."uid".Yii::$app->user->getId());
            Yii::$app->getSession()->setFlash('success', '添加成功!');
            return $this->redirect(['view', 'id' => $model->svr_id,'show_div'=>'dcmd-compile']);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
          return $this->redirect(['view', 'id' => $model->svr_id,'show_div'=>'dcmd-compile']); 
        }
        $model->app_id = $app_id;
        return $this->render('create-ci-job', [
            'model' => $model,
        ]);
    }

    function erecur($value,$key,$uuid) {
      if($value==$uuid) {return True;}
      else return False;
    }

    function recur($uuid, $array){
      $data = [];
      array_walk_recursive($array, function ($v, $k) use ($uuid,&$data) {
        if ($v==$uuid) {
            array_push($data, $v);
        }
    });
    
    return $data;
   }

    public function create_uuid($prefix = ""){    //可以指定前缀
      $str = md5(uniqid(mt_rand(), true));   
      $uuid  = substr($str,0,8) . '-';   
      $uuid .= substr($str,8,4) . '-';   
      $uuid .= substr($str,12,4) . '-';   
      $uuid .= substr($str,16,4) . '-';   
      $uuid .= substr($str,20,12);   
      return $prefix . $uuid;
   }

    /**
     * Updates an existing DcmdService model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query_svr=NULL;
        if($temp->is_self) {
          $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        }

        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query_svr==NULL && $query==NULL && Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$id));
        }
        if (Yii::$app->request->post()) {
          ///判断节点多池子
          if(Yii::$app->request->post()['DcmdService']['node_multi_pool'] == 0){ ///节点不可为多池子
            $ret = DcmdServicePoolNode::find()->andWhere(['svr_id'=>$id])->asArray()->all();
            $ips = array();
            foreach($ret as $item) {
              if(array_key_exists($item['ip'], $ips)) {
                Yii::$app->getSession()->setFlash('error', "多个池子存在该IP:".$item['ip']);
                return $this->redirect(['dcmd-service/view', 'id' => $id]); 
             }
             $ips[$item['ip']] = 1;
             }
          }
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->oprlog(2, "modify service:".$model->svr_name);
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            return $this->redirect(['view', 'id' => $model->svr_id]);
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
     * Deletes an existing DcmdService model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $app_id=NULL)
    {
      $model = $this->findModel($id);
      ///仅仅用户与该应用在同一个系统组才可以操作
      $temp = DcmdApp::findOne($model['app_id']);
      $query_svr=NULL;
      if($temp->is_self) {
        $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
      }
      $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
      if($query==NULL && $query_svr==NULL && Yii::$app->user->getIdentity()->sa != 1) {
        Yii::$app->getSession()->setFlash('success', NULL);
        Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
        return $this->redirect(array('dcmd-app/view', 'id'=>$model['app_id']));
      }
      $node = DcmdServicePool::find()->where(['svr_id' => $id])->one();
      if($node) {
        Yii::$app->getSession()->setFlash('error', '服务池子不为空,不可删除!');
      }else {
        $this->oprlog(3, "delete svrvice:".$model->svr_name);
        $this->deleteDiagram($id);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
      }
      if ($app_id) {
        return $this->redirect(array('dcmd-app/view', 'id'=>$app_id));
      }
      return $this->redirect(['index']);
    }

    public function actionDeletePoolGroup($id, $app_id=NULL)
    {
      $model = DcmdServicePoolGroup::findOne($id);
      ///仅仅用户与该应用在同一个系统组才可以操作
      $temp = DcmdApp::findOne($model['app_id']);
      $query_svr=NULL;
      if($temp->is_self) {
        $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
      }
      $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
      if($query==NULL && $query_svr==NULL && Yii::$app->user->getIdentity()->sa != 1) {
        Yii::$app->getSession()->setFlash('success', NULL);
        Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
        return $this->redirect(array('dcmd-service/view', 'id'=>$model['svr_id']));
      }
      $node = DcmdServicePool::find()->where(['svr_id' => $id])->one();
      $this->oprlog(3, "delete svrvice pool group:".$model->pool_group);
      if($model->delete()) {
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(array('dcmd-service/view', 'id'=>$model['svr_id']));
      }else {
        Yii::$app->getSession()->setFlash('error', '删除失败!');
        return $this->redirect(array('dcmd-service/view', 'id'=>$model['svr_id']));
      }
    }



    public function actionDeleteAll()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择服务!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $suc_msg = "";
      $err_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->findModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL && Yii::$app->user->getIdentity()->sa != 1) {
           $err_msg .=$model->svr_name.":没有权限删除<br>";
           continue;
        }
        $node = DcmdServicePool::find()->where(['svr_id' => $id])->one();
        if($node) {
          $err_msg .= $model->svr_name.':服务池子不为空,不可删除<br>';
          continue;
        }else { 
          $this->oprlog(3, "delete svrvice:".$model->svr_name);
          $this->deleteDiagram($id);
          $model->delete();
          $suc_msg .=$model->svr_name.':删除成功<br>';
        }
      }
      if($suc_msg != "") Yii::$app->getSession()->setFlash('success', $suc_msg);
      if($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['index']);

    }

   public function actionSelectServicePool($app_id, $svr_id)
   {
       $model = DcmdApp::findOne($app_id);
       ///判断用户所属的系统组是否和该应用相同
       if(!array_key_exists('selection', Yii::$app->request->post())) {
         Yii::$app->getSession()->setFlash('error', '未选择代码!');
         return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
       }
       $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['sa_gid']]);
       $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['svr_gid']]);
       if($query==NULL && $query_svr==NULL && Yii::$app->user->getIdentity()->sa != 1) {
         Yii::$app->getSession()->setFlash('success', NULL);
         Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
         return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
       }
       $param = array();
       $param['DcmdServicePoolSearch']['svr_id'] = $svr_id;
       $searchModel = new DcmdServicePoolSearch();
       $dataProvider = $searchModel->search($param);
       return $this->render('select_service_pool', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
          'app_id' => $app_id,
          'svr_id' => $svr_id,
       ]);
   }

    public function actionLoadContent()
    {
      $task_cmd = Yii::$app->request->post()["task_cmd"];
      $query = DcmdCenter::findOne(['master'=>1]);
      $retcontent = array("md5"=>"",);

      if ($query) {
          list($ip, $port) = explode(':', $query["host"]);
          $reply = getTaskScriptInfo($ip, $port, $task_cmd);
          if ($reply->getState() == 0) {
     //       $this->saveScript($task_cmd, $reply->getScript());
            $retContent["result"] = str_replace("\n", "<br/>",$reply->getScript());
            $retContent["md5"] = $reply->getMd5();
          }else{
            $retContent["result"] =  str_replace("\n", "<br/>",$reply->getErr());
          }
      }else {
        $retContent["result"]="Not found master center.";
      }
      echo json_encode($retContent);
      exit;
    }


   public function actionShowNodeList($app_id, $svr_id) {
        #$app_id = Yii::$app->request->post()["app_id"];
        #$svr_id = Yii::$app->request->post()["svr_id"];
        #$svr_pool_id = Yii::$app->request->post()["svr_pool_id"];
        $model = DcmdApp::findOne($app_id);
        ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['sa_gid']]);
        $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['svr_gid']]);
        if($query==NULL && $query_svr==NULL && Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        $node_group = array();
        $exist_nid = array();
        if (array_key_exists("selection", Yii::$app->request->post())) {
          $node_group = Yii::$app->request->post()["selection"];
        }
        $ngroups = "svr_pool_id in (0";
        foreach($node_group as $key=>$value) {
          $ngroups = $ngroups.",".$value;
        }
        $ngroups = $ngroups.")";
        $query = DcmdServicePoolNode::find()->where($ngroups)->orderBy('svr_pool_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 0],
        ]);
        return $this->render('show-node-list', [
          #'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
          'app_id' => $app_id,
          'svr_id' => $svr_id,
       ]);

   }

   public function actionAcceptFile($app_id,$svr_id) {
        $model = DcmdApp::findOne($app_id);
        ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['sa_gid']]);
        $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['svr_gid']]);
        if($query==NULL && $query_svr==NULL && Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        if (!array_key_exists("selection", Yii::$app->request->post())) {
          Yii::$app->getSession()->setFlash('error', '未选择!');
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        $select = Yii::$app->request->post()["selection"];
        $user_name = DcmdUser::findOne(['uid'=>Yii::$app->user->getId()]);
        foreach($select as $key=>$id) {
          $pkg = DcmdAppPkgUpload::findOne($id);
          $pkg->is_accept = 1;
          $pkg->accept_time = date('Y-m-d H:i:s');
          $pkg->accept_username = $user_name->username;
          //$pkg->svr_pool = "pool";
          if($pkg->save()) Yii::$app->getSession()->setFlash('success', "接收成功!");
          else Yii::$app->getSession()->setFlash('success', "接收失败!");
        }
        return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
   }

   public function actionRejectFile($app_id,$svr_id) {
        $model = DcmdApp::findOne($app_id);
        ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['sa_gid']]);
        $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['svr_gid']]);
        if($query==NULL && $query_svr==NULL && Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        if (!array_key_exists("selection", Yii::$app->request->post())) {
          Yii::$app->getSession()->setFlash('error', '未选择!');
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        $select = Yii::$app->request->post()["selection"];
        $user_name = DcmdUser::findOne(['uid'=>Yii::$app->user->getId()]);
        foreach($select as $key=>$id) {
          $pkg = DcmdAppPkgUpload::findOne($id);
          $pkg->is_accept = 2;
          $pkg->accept_time = date('Y-m-d H:i:s');
          $pkg->accept_username = $user_name->username;
          $pkg->save();
        }
        Yii::$app->getSession()->setFlash('success', "驳回成功!");
        return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
   }

    ///删除应用对应的图片
    private function deleteDiagram($id){
      $query = DcmdServiceArchDiagram::find()->andWhere(['svr_id'=>$id])->asArray()->all();
      foreach($query as $item) {
        ///删除文件
        $base_path = dirname(__DIR__)."/web/app_image/svr_";
        $img_path = $base_path.$item['arch_name'].'_'.$id.'.jpg';
        if(file_exists($img_path)) unlink($img_path);
      }
      DcmdServiceArchDiagram::deleteAll(['svr_id'=>$id]);
    }

    /**
     * Finds the DcmdService model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdService::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
   private function oprlog($opr_type, $sql) {
     $opr_log = new DcmdOprLog();
     $opr_log->log_table = "dcmd_service";          
     $opr_log->opr_type = $opr_type;
     $opr_log->sql_statement = $sql;
     $opr_log->ctime = date('Y-m-d H:i:s');
     $opr_log->opr_uid = Yii::$app->user->getId();
     $opr_log->save();
   }

    public function actionUpload($app_id,$svr_id)
    {
        $model = new UploadForm();
        $pool_group = array();
        $pool_g = DcmdServicePoolGroup::find()->andWhere(['app_id'=>$app_id,'svr_id'=>$svr_id])->asArray()->all();
        if($pool_g) {
          foreach($pool_g as $item) $pool_group[$item['pool_group']] = $item['pool_group'];
        }
        $filePath = $this->fileExists(Yii::$app->basePath.'/uploads/');
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file && $model->validate()) {                
                if($model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension)) {
                    $myfile = fopen('uploads/' . $model->file->baseName . '.' . $model->file->extension, "r");
                    if($myfile) {
                      $content = fread($myfile,filesize('uploads/' . $model->file->baseName . '.' . $model->file->extension));
                      $script = new DcmdServiceScriptApply();
                      $script->app_id = $app_id;
                      $script->svr_id = $svr_id;    
                      $script->is_apply = 0;
                      $script->pool_group = Yii::$app->request->post()['UploadForm']["pool_group"];
                      $script->script_md5 = md5_file('uploads/' . $model->file->baseName . '.' . $model->file->extension);
                      $script->script = $content;
                      $script->ctime = date('Y-m-d H:i:s');
                      $script->opr_uid = Yii::$app->user->getId();
                      if($script->save()) {
                        unlink('uploads/' . $model->file->baseName . '.' . $model->file->extension);
                        return $this->redirect(array('dcmd-service/load-script', 'id'=>$script->id));
                      }
                      else {
                        unlink('uploads/' . $model->file->baseName . '.' . $model->file->extension);
                      }
                    }
                };
            }
        }

        return $this->render('upload', ['model' => $model,'pool_group'=>$pool_group]);
    }

    public function actionLoadScripts()
    {
      $app_id = Yii::$app->request->post()["app_id"];
      $svr_id = Yii::$app->request->post()["svr_id"];
      $pool_group = Yii::$app->request->post()["pool_group"];
      $query = DcmdServiceScriptApply::findOne(['app_id'=>$app_id,'svr_id'=>$svr_id,'is_apply'=>0,'pool_group'=>$pool_group]);

      if ($query) {
            $retContent["result"] = str_replace(" ","&nbsp;",str_replace("\n", "<br/>",$query->script));
      }else {
        $retContent["result"]="Not script found.";
      }
      echo json_encode($retContent);
      exit;
    }

    public function actionLoadScript($id)
    {
      $query = DcmdServiceScriptApply::findOne($id);
      $onlineScript = DcmdServiceScript::findOne(['svr_id'=>$query->svr_id,'pool_group'=>$query->pool_group]);
      if ($query) {
            $result = str_replace(" ","&nbsp;",str_replace("\n", "<br/>",$query->script));
            $id = $query->id;
      }else {
        $result="Not script found.";
        $id = "";
      }
     
      if ($onlineScript) {
        $result_online = str_replace(" ","&nbsp;",str_replace("\n", "<br/>",$onlineScript->script));
      }else {
        $result_online="Not script found.";
      }

      return $this->render('saved-script-content', [
        'result' => $result,
        'result_online' => $result_online,
        'id' => $id,
      ]);
    }

    public function actionLoadOnlineScript($id)
    {
      $query = DcmdServiceScript::findOne($id);
      if ($query) {
            $result = str_replace(" ","&nbsp;",str_replace("\n", "<br/>",$query->script));
      }else {
        $result="Not script found.";
      }
      return $this->render('script-content', [
        'result' => $result,
      ]);
    }

    public function actionLoadSubmits()
    {
      $app_id = Yii::$app->request->post()["app_id"];
      $svr_id = Yii::$app->request->post()["svr_id"];
      $pool_group = Yii::$app->request->post()["pool_group"];
      $query = DcmdServiceScriptApply::findOne(['app_id'=>$app_id,'svr_id'=>$svr_id,'is_apply'=>1,'pool_group'=>$pool_group]);

      if ($query) {
            $retContent["result"] = str_replace(" ","&nbsp;",str_replace("\n", "<br/>",$query->script));
      }else {
        $retContent["result"]="Not script found.";
      }
      echo json_encode($retContent);
      exit;
    }

    public function actionLoadSubmit($id)
    {
      $query = DcmdServiceScriptApply::findOne($id);

      if ($query) {
            $result = str_replace(" ","&nbsp;",str_replace("\n", "<br/>",$query->script));
      }else {
        $result="Not script found.";
      }
      return $this->render('script-content', [
        'result' => $result,
      ]);
    }

    public function actionLoadOnlineScripts()
    {
      $app_id = Yii::$app->request->post()["app_id"];
      $svr_id = Yii::$app->request->post()["svr_id"];
      $pool_group = Yii::$app->request->post()["pool_group"];
      $query = DcmdServiceScript::findOne(['app_id'=>$app_id,'svr_id'=>$svr_id,'pool_group'=>$pool_group]);

      if ($query) {
            $retContent["result"] = str_replace(" ","&nbsp;",str_replace("\n", "<br/>",$query->script));
      }else {
        $retContent["result"]="Not script found.";
      }
      echo json_encode($retContent);
      exit;
    }



    public function actionSubmitAudit($id) 
    {
      $model = DcmdServiceScriptApply::findOne($id); 
      if($model) {
        $model->is_apply = 1;
        if($model->save()) {
          $data = "提交成功!";
        }
        else {
          $data = "提交失败!";
        }
      }else {
        $data = "没有未提交脚本!";
      }
      return $data;
    }

    public function actionGetPoolGroup($svr_id) 
    {
      $ret = DcmdServicePoolGroup::find()->andWhere(['svr_id'=>$svr_id])->asArray()->all();
      $result = '<option value="">---服务池组---</option>';
      if($ret) {
        foreach($ret as $item) {
          $result .= '<option value="'.$item['pool_group'].'">'.$item['pool_group'].'</option>';
        }
      }
      return $result;
    }

    public function actionDeleteDraft($id)
    {
      $model = DcmdServiceScriptApply::findOne($id);
      if($model) {
        if($model->delete()) {
          $data = "删除成功!";
        }
        else {
          $data = "删除失败!";
        }
      }else {
        $data = "没有未提交脚本!";
      }
      return $data;
    }

public function fileExists($uploadpath)
{
    if(!file_exists($uploadpath)){
        mkdir($uploadpath);
    }
    return $uploadpath;
}

}
