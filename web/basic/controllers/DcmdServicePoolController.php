<?php

namespace app\controllers;

use Yii;
use app\models\DcmdServicePool;
use app\models\DcmdServicePoolSearch;
use app\models\DcmdServicePoolNode;
use app\models\DcmdApp;
use app\models\DcmdNode;
use app\models\DcmdUser;
use app\models\DcmdAppConfVersion;
use app\models\DcmdAppConfVersionSearch;
use app\models\DcmdUserGroup;
use app\models\DcmdService;
use app\models\DcmdAppResSearch;
use app\models\DcmdAppRes;
use app\models\DcmdServicePoolGroup;
use app\models\DcmdServicePoolNodeSearch;
use app\models\DcmdServicePoolAudit;
use app\models\DcmdServicePoolPortSearch;
use app\models\DcmdServicePoolPort;
use app\models\DcmdServicePort;
use app\models\DcmdServicePoolNodePort;
use app\models\DcmdServicePoolNodePortSearch;
use app\models\DcmdServicePoolAttr;
use app\models\DcmdServicePoolAttrDef;
use app\models\DcmdOprLog;
use app\models\DcmdOprCmdSearch;
use app\models\DcmdOprCmdRepeatExecSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
/**
 * DcmdServicePoolController implements the CRUD actions for DcmdServicePool model.
 */
class DcmdServicePoolController extends Controller
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
        $params = array();
        if(array_key_exists('DcmdServicePoolSearch', Yii::$app->request->queryParams)){
          $params['DcmdServicePoolSearch'] = Yii::$app->request->queryParams['DcmdServicePoolSearch'];
          if($params['DcmdServicePoolSearch']['app_id'] == "") {
            $params['DcmdServicePoolSearch']['svr_id'] = "";
          }
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
        foreach($query as $item) $app[$item['app_id']] = $item['app_name'];
        if(array_key_exists('DcmdServicePoolSearch',$params)) {
           if(!array_key_exists($params['DcmdServicePoolSearch']['app_id'], $app))
              $params['DcmdServicePoolSearch']['svr_id'] = "";
        }
        $svr = array();
        if(array_key_exists('DcmdServicePoolSearch', $params) &&
           array_key_exists('app_id' ,  $params['DcmdServicePoolSearch']) && $params['DcmdServicePoolSearch']['app_id'] !="") {
           $query = DcmdService::find()->andWhere(['app_id'=>$params['DcmdServicePoolSearch']['app_id']])->asArray()->all();
          if($query) {
            foreach($query as $item) $svr[$item['svr_id']] = $item['svr_name'];
          }
        }else {
          $query = DcmdService::find()->andWhere("")->asArray()->all();
          if($query) {
            foreach($query as $item) $svr[$item['svr_id']] = $item['svr_name'];
          }
        }
        if(array_key_exists('DcmdServicePoolSearch',$params)) {
           if(!array_key_exists($params['DcmdServicePoolSearch']['svr_id'], $svr))
              $params['DcmdServicePoolSearch']['svr_id'] = "";
        }
        $searchModel = new DcmdServicePoolSearch();
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'app' => $app,
            'svr' => $svr,
        ]);
    }

    /**
     * Displays a single DcmdServicePool model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new DcmdServicePoolNodeSearch();
        $con = array();
        $con['DcmdServicePoolNodeSearch'] = array('svr_pool_id'=>$id);
        if(array_key_exists('DcmdServicePoolNodeSearch', Yii::$app->request->queryParams))
           $con = array_merge($con, Yii::$app->request->queryParams);
        $con['DcmdServicePoolNodeSearch']['svr_pool_id'] = $id;
        $dataProvider = $searchModel->search($con, false);
        $show_div = "dcmd-service-pool-node";
        if(array_key_exists("show_div", Yii::$app->request->queryParams))
          $show_div = Yii::$app->request->queryParams['show_div'];
        ///获取属性
        $self_attr = DcmdServicePoolAttr::find()->andWhere(['svr_pool_id'=>$id])->asArray()->all();
        $def_attr = DcmdServicePoolAttrDef::find()->asArray()->all();
        $attr_str = '<div id="w1" class="grid-view">
          <table class="table table-striped table-bordered"><thead>
          <tr><th>属性名</th><th>值</th><th>操作</th></tr>
          </thead><tbody>';
        $attr = array();
        foreach($self_attr as $item) {
          $attr_str .= '<tr><td>'.$item['attr_name'].'</td><td>'.$item['attr_value'].'</td><td><a href="/ducter/index.php?r=dcmd-service-pool-attr/update&id='.$item['id'].'&svr_pool_id='.$id.'">修改</a></td></tr>';
          $attr[$item['attr_name']] = $item['attr_name'];
        }
        foreach($def_attr as $item) {
          if(array_key_exists($item['attr_name'], $attr)) continue;
          $attr_str .= '<tr><td>'.$item['attr_name'].'</td><td>'.$item['def_value'].'</td><td><a href="/ducter/index.php?r=dcmd-service-pool-attr/update&id=0&attr_id='.$item['attr_id'].'&svr_pool_id='.$id.'">修改</a></td></tr>';
        }
        $attr_str .= "</tbody></table></div>";
        
        $svr_pool = DcmdServicePool::findOne($id);
        $con_res = array();
        $con_res['DcmdAppResSearch'] = array('svr_pool_id'=>$id);
        if(array_key_exists('DcmdAppResSearch', Yii::$app->request->queryParams))
          $con_res = array_merge($con_res, Yii::$app->request->queryParams);
        $con_res['DcmdAppResSearch']['svr_pool_id'] = $id;
        $con_res['DcmdAppResSearch']['app_id'] = $svr_pool->app_id;
        $con_res['DcmdAppResSearch']['svr_id'] = $svr_pool->svr_id;
        $resSearch = new DcmdAppResSearch();
        $resProvider = $resSearch->search($con_res);
      
        $conf_sea = array();
        $conf_sea['DcmdAppConfVersionSearch']['svr_pool_id'] = $id;
        $confSearch = new DcmdAppConfVersionSearch();
        $conf_version = $confSearch->search($conf_sea);

        $pool = $this->findModel($id);
        $app = DcmdApp::findOne($pool->app_id);

        $poolportSearch = new DcmdServicePoolPortSearch();
        $pool_port = array();
        $pool_port['DcmdServicePoolPortSearch']['svr_pool_id'] = $id;
        $poolportProvider = $poolportSearch->search($pool_port);

        return $this->render('view', [
            'svr_pool_id' => $id,
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'resProvider' => $resProvider,
            'resSearch' => $resSearch,
	    'poolportProvider' => $poolportProvider,
            'show_div' => $show_div,
            'attr_str' => $attr_str,
            'conf_version' => $conf_version,
            'is_self' => $app->is_self,
        ]);
    }

    public function actionSelectServicePool($app_id,$svr_id)
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
          return $this->redirect(array('dcmd-app/index'));
        }
        $svr_pool = array();
        $service = DcmdService::find()->andWhere(['app_id'=>$app_id])->asArray()->all();
        if($service) {
          foreach($service as $item) {
            $pools = DcmdServicePool::find()->andWhere(['svr_id'=>$item['svr_id']])->asArray()->all();
            $result = array();
            $result['svr'] = $item['svr_name'];
            $result['pool'] = array();
            if($pools) {
              foreach($pools as $pool) {
                array_push($result['pool'],$pool['svr_pool']);
              }
            }
            array_push($svr_pool,$result);
          }
        }
        return $this->render('select_service_pool', [
         'service_pool' => $svr_pool,
         'app_id' => $app_id,
         'svr_id' => $svr_id,
        ]);
        

    }

    public function actionSelectServicePoolNode()
    {
        $app_id = Yii::$app->request->post()['app_id'];
        $svr_id = Yii::$app->request->post()['svr_id'];
        $temp = DcmdApp::findOne($app_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        $query_svr=NULL;
        if($temp->is_self) {
          $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        }
        if($query==NULL && $query_svr==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/index'));
        }
        $selected = Yii::$app->request->post()['SvrPoolName'];
        $pool_name = explode(":",trim($selected));
        $sel_svr = DcmdService::findOne(['app_id'=>$app_id,'svr_name'=>$pool_name[0]]);
        $sel_svr_pool = DcmdServicePool::findOne(['svr_id'=>$sel_svr->svr_id,'svr_pool'=>$pool_name[1]]);
        $svr_pool_node = array();
        $pool_node = DcmdServicePoolNode::find()->andWhere(['svr_id'=>$sel_svr->svr_id,'svr_pool_id'=>$sel_svr_pool->svr_pool_id])->asArray()->all();
        if($pool_node) {
          foreach($pool_node as $item) {
            array_push($svr_pool_node,$item['ip']);
          }
        }
        return $this->render('select_service_pool_node', [
         'service_pool_node' => $svr_pool_node,
         'app_id' => $app_id, 
         'svr_id' => $svr_id,
         'select_svr_pool' => $sel_svr_pool->svr_pool_id,
         'create_pool_name' => Yii::$app->request->post()['svr_pool_name'],
        ]); 
            
           
    } 

    public function actionCopyServicePool()
    {
              ///仅仅用户与该应用在同一个系统组才可以操作
        $app_id = Yii::$app->request->post()['app_id'];
        $temp = DcmdApp::findOne($app_id);
        $query_svr=NULL;
        if($temp->is_self) { 
          $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        }
    
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query_svr==NULL && $query==NULL && Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        $success_msg = "提交审批成功设备:";
        if (Yii::$app->request->post()) {
          $create_pool = new DcmdServicePool();
          $select_svr_pool = DcmdServicePool::findOne(Yii::$app->request->post()['select_svr_pool']);
          $create_pool->svr_pool = Yii::$app->request->post()['create_pool_name'];
          $create_pool->svr_id = Yii::$app->request->post()['svr_id'];
          $create_pool->app_id = Yii::$app->request->post()['app_id'];
          $create_pool->pool_group = "";
          $create_pool->tag = "";
          $create_pool->tag_md5 = "";
          $create_pool->tag_task_id = 0;
          $create_pool->repo = $select_svr_pool->repo;
          $create_pool->env_ver = "";
          $create_pool->env_md5 = "";
          $create_pool->env_passwd = "";
          $create_pool->image_url = "";
          $create_pool->image_user = "";
          $create_pool->image_passwd = "";
          $create_pool->image_name = "";
          $create_pool->comment = "";
          $create_pool->utime = date('Y-m-d H:i:s');
          $create_pool->ctime = date('Y-m-d H:i:s');
          $create_pool->opr_uid = Yii::$app->user->getId();
          $service = DcmdService::findOne(Yii::$app->request->post()['svr_id']);
          $tr = Yii::$app->db->beginTransaction();
          $err_msg = "已经有池子使用设备:";
          try {
            if ($create_pool->save()){
              foreach(Yii::$app->request->post() as $k=>$v) {
                if (substr($k, 0, 11) != 'SvrPoolNode') { 
                  continue;
                } 
               if($service->node_multi_pool == 0) { 
                  $temp = DcmdServicePoolNode::findOne(['ip' => $v]);
                  if($temp) {
                    $err_msg .= $v." ";
                    continue;
                  }
               }
               $nid = DcmdNode::findOne(['ip'=>$v]);
               $server_pool_audit = new DcmdServicePoolAudit();
               $server_pool_audit->svr_pool_id = $create_pool->svr_pool_id;
               $server_pool_audit->svr_id = Yii::$app->request->post()['svr_id'];
               $server_pool_audit->app_id = Yii::$app->request->post()['app_id'];
               $server_pool_audit->nid = $nid->nid;
               $server_pool_audit->ip = $v;
               $server_pool_audit->ctime = date('Y-m-d H:i:s');
               $server_pool_audit->action = "add";
               $server_pool_audit->opr_uid = Yii::$app->user->getId();
               $server_pool_audit->save();
               $this->oprlog(1,"add ip:".$v);
               $success_msg .= $v." ";
              }
            }
            Yii::$app->getSession()->setFlash('success',$success_msg);
            Yii::$app->getSession()->setFlash('error',$err_msg);
            $tr->commit();
            return $this->redirect(array('dcmd-service-pool/view', 'id' => $create_pool->svr_pool_id));
        } catch (Exception $e) {
          $tr->roolback();
          return $this->redirect(array('dcmd-service/view', 'id' => Yii::$app->request->post()['svr_id']));
        }
            
     }
        
    }

    /**
     * Creates a new DcmdServicePool model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($app_id, $svr_id)
    {
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($app_id);
        $query_svr=NULL;
        if($temp->is_self) {
          $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        }

        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query_svr==NULL && $query==NULL && Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        $model = new DcmdServicePool();
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
          $tr = Yii::$app->db->beginTransaction();
          try {
            $model->utime = date('Y-m-d H:i:s');
            $model->ctime = $model->utime;
            $model->opr_uid = Yii::$app->user->getId();
     //       $model->tag = "null";
     //       $model->tag_task_id = -1;
            $model->save();
            $service_port = DcmdServicePort::find()->andWhere(['svr_id'=>$svr_id])->asArray()->all();
            if($service_port) {
              foreach($service_port as $item) {
                $pool_model = new DcmdServicePoolPort();
                $pool_model->svr_pool_id = $model->svr_pool_id;
                $pool_model->svr_id = $svr_id;
                $pool_model->port_name = $item['port_name'];
                $pool_model->port = $item['def_port'];
                $pool_model->mapped_port = 0;
                $pool_model->utime = date('Y-m-d H:i:s');
                $pool_model->ctime = date('Y-m-d H:i:s');
                $pool_model->opr_uid = Yii::$app->user->getId();
                $pool_model->save();
              }
            }
            $tr->commit();
            $this->oprlog(1,"insert service pool:".$model->svr_pool);
            Yii::$app->getSession()->setFlash('success', "添加成功");
            return $this->redirect(['view', 'id' => $model->svr_pool_id]);
          } catch (Exception $e) {
            $tr->rollBack();
            $err_str = "";
            foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
            Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
            return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
          }
        }
        $pool_group = array();
        $pool_g = DcmdServicePoolGroup::find()->andWhere(['app_id'=>$app_id,'svr_id'=>$svr_id])->asArray()->all();
        if($pool_g) {
          foreach($pool_g as $item) $pool_group[$item['pool_group']] = $item['pool_group'];
        }
        $model = new DcmdServicePool();
        $model->app_id = $app_id;
        $model->svr_id = $svr_id;
        return $this->render('create', [
              'model' => $model,
              'pool_group' => $pool_group,
        ]);
        
    }

    public function actionCreateServicePoolPort($app_id,$svr_id,$svr_pool_id)
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
        $model = new DcmdServicePoolPort();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          $model->svr_id = $svr_id;
          $model->svr_pool_id = $svr_pool_id;
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->oprlog(1, "insert service pool port:".$model->svr_pool_id);
            Yii::$app->getSession()->setFlash('success', '添加成功!');
            return $this->redirect(array('dcmd-service-pool/view', 'id' => $model->svr_pool_id));
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
        }
//        $model->app_id = $app_id;
        return $this->render('create-service-pool-port', [
            'model' => $model,
        ]);
    }

    public function actionNodePort($svr_pool_id,$ip)
    {
        $port_svr = array();
        $port_svr['DcmdServicePoolNodePortSearch']['svr_pool_id'] = $svr_pool_id;
        $port_svr['DcmdServicePoolNodePortSearch']['ip'] = $ip;
        $SvrPortSearch = new DcmdServicePoolNodePortSearch();
        $dataProvider = $SvrPortSearch->search($port_svr);
//        $nodeportSearch = new DcmdServicePoolNodePortSearch();
        $model = DcmdServicePoolNode::findOne(['svr_pool_id'=>$svr_pool_id,'ip'=>$ip]);
      
        return $this->render('node-port', [
            'dataProvider' => $dataProvider,
            'ip' => $ip,
            'model' => $model,
        ]);
    }

    public function actionAddNodePort($svr_id,$svr_pool_id,$ip)
    {
        $model = new DcmdServicePoolNodePort();
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
          $model->ctime = date('Y-m-d H:i:s');
          $model->svr_id = $svr_id;
          $model->svr_pool_id = $svr_pool_id;
          $model->ip = $ip;
          $model->opr_uid = Yii::$app->user->getId();
          if($model->save()) {
            $this->oprlog(1,"insert ip port:".$ip);
            Yii::$app->getSession()->setFlash('success', "添加成功");
            return $this->redirect(['node-port','ip'=>$ip,'svr_pool_id'=>$svr_pool_id]);
          }
          else {
            Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
            return $this->redirect(['node-port','ip'=>$ip,'svr_pool_id'=>$svr_pool_id]);
          }
        }
        $model->ip = $ip;
        return $this->render('add-node-port', [
              'model' => $model,
        ]);
       
    }

    public function actionCreateConf($app_id, $svr_id,$svr_pool_id)
    {
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($app_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        if($query==NULL && $query_svr==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service-pool/view', 'id'=>$svr_pool_id));
        }
        $user = DcmdUser::findOne(['uid'=>Yii::$app->user->getId()]);
        $model = new DcmdAppConfVersion();
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
          $model->ctime = date('Y-m-d H:i:s');
          $model->app_id = $app_id;
          $model->svr_id = $svr_id;
          $model->svr_pool_id = $svr_pool_id;
          $model->username = $user->username;
          if($model->save()) {
            $this->oprlog(1,"insert service pool:".$model->svr_pool_id);
            Yii::$app->getSession()->setFlash('success', "添加成功");
            return $this->redirect(['view', 'id' => $svr_pool_id,'show_div'=>'dcmd-conf-version']);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
          return $this->redirect(array('dcmd-service-pool/view', 'id'=>$svr_pool_id,'show_div'=>'dcmd-conf-version'));
        }
        $model = new DcmdAppConfVersion();
        $model->app_id = $app_id;
        $model->svr_id = $svr_id;
        $model->svr_pool_id = $svr_pool_id;
        return $this->render('create-conf', [
              'model' => $model,
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
        $query_svr=NULL;
        if($temp->is_self) {
          $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        }

        if($query_svr==NULL && $query==NULL && Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service-pool/view', 'id'=>$id, 'show_div'=>'dcmd-service-pool'));
        }
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
          $model->load(Yii::$app->request->post());
          $model->svr_mem = Yii::$app->request->post()['DcmdServicePool']['svr_mem'];
          $model->svr_io = Yii::$app->request->post()['DcmdServicePool']['svr_io'];
          $model->svr_cpu = Yii::$app->request->post()['DcmdServicePool']['svr_cpu'];
          $model->svr_net = Yii::$app->request->post()['DcmdServicePool']['svr_net'];
          if (Yii::$app->request->post()['DcmdServicePool']['svr_net'] == "mage:tag") {
            $model->image_name = "";
          }
          $version = Yii::$app->request->post()['DcmdServicePool']['env_ver'];
          $conf_version = DcmdAppConfVersion::findOne(['svr_pool_id'=>$id,'version'=>$version]);
          if($conf_version) {
            $model->env_md5 = $conf_version->md5;
            $model->env_passwd = $conf_version->passwd;
          }
          if($model->save()) {
            Yii::$app->getSession()->setFlash('error',NULL);
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            $this->oprlog(2,"update service pool:".$model->svr_pool);
            return $this->redirect(['view', 'id' => $model->svr_pool_id, 'show_div'=>'dcmd-service-pool']);  
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "修改失败:".$err_str);
        }
        
        $version_model = DcmdAppConfVersion::find()->andWhere(['svr_pool_id'=>$id])->asArray()->all();
        $version = array();
        if($version_model) {
          foreach($version_model as $item) $version[$item['version']] = $item['version'];
        }
        $pool_group = array();
        $pool_g = DcmdServicePoolGroup::find()->andWhere(['app_id'=>$model['app_id'],'svr_id'=>$model['svr_id']])->asArray()->all();
        if($pool_g) {
          foreach($pool_g as $item) $pool_group[$item['pool_group']] = $item['pool_group'];
        }
        return $this->render('update', [
             'model' => $model,
             'version' => $version,
             'pool_group' => $pool_group,
        ]);
    }

    public function actionUpdatePoolPort($svr_pool_id,$id)
    {
      $model = DcmdServicePoolPort::findOne($id);
      if (Yii::$app->request->post()) {
          if(Yii::$app->request->post()['DcmdServicePoolPort']['mapped_port'] == $model->mapped_port) {
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            return $this->redirect(['view', 'id' => $model->svr_pool_id, 'show_div'=>'dcmd-service-pool']);
          }
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
          $model->load(Yii::$app->request->post());
          $pool_node = DcmdServicePoolNode::find()->andWhere(['svr_pool_id'=>$svr_pool_id])->asArray()->all();
          $tr = Yii::$app->db->beginTransaction();
          try {
            $model->save();
            if($pool_node) {
              foreach($pool_node as $item) {
                $node_port = new DcmdServicePoolNodePort();
                $node_port->ip = $item['ip'];
                $node_port->port_name = $model->port_name;
                $node_port->port = $model->mapped_port;
                $node_port->svr_id = $model->svr_id;
                $node_port->svr_pool_id = $svr_pool_id;
                $node_port->ctime = date('Y-m-d H:i:s');
                $node_port->opr_uid = Yii::$app->user->getId();
                $node_port->save();
              }
            }
            $tr->commit(); 
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            $this->oprlog(2,"update service pool port:".$model->id);
            return $this->redirect(['view', 'id' => $model->svr_pool_id, 'show_div'=>'dcmd-service-pool']);
          } catch (Exception $e) {
            
            $tr->rollBack();
            Yii::$app->getSession()->setFlash('error', '修改失败!');
            return $this->redirect(['view', 'id' => $model->svr_pool_id, 'show_div'=>'dcmd-service-pool']);
          }
      }
      return $this->render('update-pool-port', [
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
        if($query==NULL && Yii::$app->user->getIdentity()->sa != 1) {
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
        if($query==NULL && Yii::$app->user->getIdentity()->sa != 1) {
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
      $ret_msg = "删除以下ip:";
      $tm =  date('Y-m-d H:i:s');
      foreach($select as $k=>$id) {
        $model = $this->nodeModel($id);
        $svr_pool_id = $model->svr_pool_id; 
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL && Yii::$app->user->getIdentity()->sa != 1) {
           $ret_msg .="没有权限删除:".$model->ip." ";
           continue;
        }
        $ret_msg .=$model->ip." ";
        $model->delete();
      }
      Yii::$app->getSession()->setFlash('success', $ret_msg);
      return $this->redirect(['dcmd-service-pool/view', 'id'=>$svr_pool_id, 'show_div'=>'dcmd-service-pool-node']);
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

    public function actionGetversion($version,$svr_pool_id) {
      $query = DcmdAppConfVersion::findOne(['version'=>$version,'svr_pool_id'=>$svr_pool_id]);
      if($query) {
        return '{"md5":"'.$query->md5.'","passwd":"'.$query->passwd.'"}';
      } else {
        return '{"md5":"","passwd":""}';
      }
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
        if (($model = DcmdServicePool::findOne($id)) !== null) {
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
