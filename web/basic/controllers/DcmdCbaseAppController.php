<?php

namespace app\controllers;

use Yii;
use app\models\DcmdApp;
use app\models\DcmdNode;
use app\models\DcmdDepartment;
use app\models\DcmdService;
use app\models\DcmdDcInfo;
use app\models\DcmdDcInfoSearch;
use app\models\DcmdNodeGroupInfo;
use app\models\DcmdPrivate;
use app\models\DcmdServicePool;
use app\models\DcmdServicePoolNode;
use app\models\DcmdUserGroup;
use app\models\DcmdIdcNetwork;
use app\models\DcmdServiceSearch;
use app\models\DcmdServiceViewSearch;
use app\models\DcmdGroup;
use app\models\DcmdAppSearch;
use app\models\DcmdCbsApp;
use app\models\DcmdCbsBucketsSearch;
use app\models\DcmdCbasePoolSearch;
use app\models\DcmdCbsAppSearch;
use app\models\DcmdAppSegment;
use app\models\DcmdAppSegmentSearch;
use app\models\DcmdAppArchDiagram;
use app\models\DcmdAppArchDiagramSearch;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
/**
 * DcmdAppController implements the CRUD actions for DcmdApp model.
 */
class DcmdCbaseAppController extends Controller
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
     * Lists all DcmdApp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = DcmdGroup::find()->asArray()->all();
        $sys = array();
        $svr = array();
        foreach($query as $item) {
          if($item['gtype'] == 1) $sys[$item['gid']] = $item['gname'];
          else $svr[$item['gid']] = $item['gname'];
        }
        $query = DcmdDepartment::find()->asArray()->all();
        $depart = array();
        foreach($query as $item) $depart[$item['depart_id']] = $item['depart_name'];
        $searchModel = new DcmdCbsAppSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sys' => $sys,
            'svr' => $svr,
            'depart' => $depart,
        ]);
    }

    /**
     * Displays a single DcmdApp model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new DcmdCbasePoolSearch();
        $con = array();
    /*    $con['DcmdSearch'] = array('app_id'=>$id);
        if(array_key_exists('DcmdServiceSearch', Yii::$app->request->queryParams))
          $con = array_merge($con, Yii::$app->request->queryParams);*/
        $con['DcmdCbasePoolSearch']['app_id'] = $id;
        $dataProvider = $searchModel->search($con);
        $bucketsModel = new DcmdCbsBucketsSearch();
        $con1['DcmdCbsBucketsSearch']['app_id'] = $id;
        $bucketsProvider = $bucketsModel->search($con1);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'bucketsModel' => $bucketsModel,
            'bucketsProvider' => $bucketsProvider,
        ]);
    }

    public function actionCreateNetwork($app_id)
    {
        $model = new DcmdAppSegment();
        $segment = DcmdApp::findOne($app_id);
       /* $dc = DcmdDcInfo::find()->andWhere(["dc" => $segment->dc])->asArray()->all();

        $network = DcmdIdcNetwork::find()->andWhere(["idc" => $dc[0]["dc_id"]])->asArray()->all();
        $arr = array_column($network, "segment");  
        $data = array();
        foreach($arr as $k=>$v) {
          $value = explode(".", $v);
          array_push($data, $value[0].".".$value[1]);
        }
        $nets = array_unique($data);
        if(count($nets) > 1){
          Yii::$app->getSession()->setFlash('error', "IDC中存在多个不同的B段!");
        }else {
          $net = $nets[0];
        }*/
        if($model->load(Yii::$app->request->post())) {
            $model->app_id = $app_id;
            $model->save();
            $this->oprlog(1, "add app's network:".$model->app_id);
            Yii::$app->getSession()->setFlash('success', '添加成功!');
            return $this->redirect(['view', 'id' => $app_id,]);
        }
        return $this->render('create-network', [
             'model' => $model,
             'app_id' => $app_id,
             'net' => $segment->net,
        ]);
    }

    /**
     * Creates a new DcmdApp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DcmdCbsApp();

		$depart = $this->getDepart();
		$country = DcmdDcInfo::findBySql('SELECT country FROM dcmd_dc_info')->asArray()->all();
                $country_array = array();
                foreach($country as $item) {
                  $country_array[$item['country']] = $item['country'];
                }
	        $country_array = array_unique($country_array);
		if (Yii::$app->request->post()) {
		  $model->utime = date('Y-m-d H:i:s');
		  $model->ctime = $model->utime;

          if ($model->load(Yii::$app->request->post())) {
            $model->utime = date('Y-m-d H:i:s');
            $model->ctime = $model->utime;
            $model->save();
            $this->oprlog(1, "insert app:".$model->app_name);
            Yii::$app->getSession()->setFlash('success', '添加成功!');
            return $this->redirect(['view', 'id' => $model->app_id]);
          }

          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
        }
        return $this->render('create', [
             'model' => $model,
             'depart' => $depart,
             'country' => $country_array,
        ]);
    }
    public function actionGetAppSvr($id) {
      $arr = array();
      ///获取应用命令
      $dcmd_app = $this->findModel($id);
      $arr['name'] = $dcmd_app->app_name;
      $arr['children'] = array();
      ///获取应用
      $query = DcmdService::find()->andWhere(['app_id'=>$id])->asArray()->all();
      foreach($query as $item) {
        $svr = array();
        $svr['name'] = $item['svr_name'];
        $svr['children'] = array();
        ///获取应用池子
        $pool = DcmdServicePool::find()->andWhere(['svr_id'=>$item['svr_id']])->asArray()->all();
        foreach($pool as $p) {
          $svrp = array();
          $svrp['name'] = $p['svr_pool'];
          $svrp['children'] = array();
          ///获取节点
          $node = DcmdServicePoolNode::find()->andWhere(['svr_pool_id'=>$p['svr_pool_id']])->asArray()->all();
          foreach($node as $n) {
            array_push($svrp['children'], array('name'=>$n['ip']));
          } 
          array_push($svr['children'], $svrp);
        }
        array_push($arr['children'], $svr);
      } 
      return json_encode($arr);
    }
    /**
     * Updates an existing DcmdApp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $depart = $this->getDepart();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          if ($model->load(Yii::$app->request->post())) {
            $model->save();
            $this->oprlog(2, "update cbase app:".$model->app_name);
            Yii::$app->getSession()->setFlash('success', '修改成功!');        
            return $this->redirect(['view', 'id' => $model->app_id,]);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str); 
        }
        $country = DcmdDcInfo::findBySql('SELECT * FROM dcmd_dc_info')->asArray()->all();
        $country_array = array();
        foreach($country as $item) {
          $country_array[$item['dc_id']] = $item['dc'];
        }
        $country_array = array_unique($country_array);        
        return $this->render('update', [
             'model' => $model,
             'depart' => $depart,
             'country' => $country_array,
        ]);
    }

    /**
     * Deletes an existing DcmdApp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['index']);
    }
    public function actionDeleteAll()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择产品!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $suc_msg = "";
      $err_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->findModel($id);
        $suc_msg .=$model->app_name.':删除成功<br>';
        $this->oprlog(3, "delete app:".$model->app_name);
        $model->delete();
      }
      if($suc_msg) Yii::$app->getSession()->setFlash('success', $suc_msg);
      if($err_msg) Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['index']);
    }

    public function actionDeleteNetwork($id)
    {
        $network = $this->findNetwork($id);
        $model = $this->findModel($network['app_id']);
        ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/view', 'id'=>$network['app_id']));
        }
        $this->oprlog(3, "delete app's network:".$model->app_name);
        $network->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(array('dcmd-app/view', 'id'=>$network['app_id']));
    }

    public function actionGetHosts($app_id)
    {
      $ids = "(".$app_id.")";
      $sql = 'select * from dcmd_node where ip in (select ip from dcmd_service_pool_node where app_id in '.$ids.')';
      $query = DcmdNode::findBySql($sql)->asArray()->all();
      $data = json_encode($query);
      return $data;
    }

    public function actionGetVms($app_id)
    {
      $ids = "(".$app_id.")";
      $sql = 'select * from dcmd_vm_assign where app_name in (select app_name from dcmd_app where app_id in '.$ids.')';
      $query = DcmdPrivate::findBySql($sql)->asArray()->all();
      $data = json_encode($query);
      return $data;
    }

    public function actionGetarea($country)
    {
      $area = "";
      $ret = DcmdDcInfo::find()->andWhere(['country'=>$country])->asArray()->all();
      $area = array();
      if ($ret)
        $area = array_column($ret,'area');
        array_unshift($area,"");
        $area = array_unique($area);
        $area = json_encode($area);
      return $area;
    }

    public function actionGetdc($country,$area)
    {
      $dc = "";
      $ret = DcmdDcInfo::find()->andWhere(['country'=>$country,'area'=>$area])->asArray()->all();
      $dc = array();
      if ($ret) {
        foreach($ret as $item) {
          $dc[$item['dc_id']] = $item['dc'];
        }
        $dc = json_encode($dc); 
      }
      return $dc;
    }

    public function actionGetSegments($net)
    {
      $model = DcmdAppSegment::find()->asArray()->all();
      $segment = array();
      $data = "";
      if($model){
        $segment = array_column($model,'network');
        $result = $this->findSegment($net, $segment);
        $data = json_encode($result);
      }
      return $data;
    }
   
    private function findSegment($net, $segment) 
    {
      $result = array();
      $a = explode(".",$net);
      $net2 = $a[0].".".$a[1];
      $final = "";
      foreach($segment as $v) {
        if(strstr($v,"/")){
          $b = explode(".", $v);
          $seg = $b[0].".".$b[1];
          $sub = 24 - ((int)(explode("/", $v)[1]));
          if($net2 == $seg) {
            for($i=0;$i<pow(2,$sub);$i++) {
              $final = $b[0].".".$b[1].".".((int)$b[2] + $i).".".(explode("/",$b[3])[0])."/24";
              array_push($result, $final);
            }
          }
        }
      }
      return $result;
    }  

    ///删除应用对应的图片
    private function deleteDiagram($id){
      $query = DcmdAppArchDiagram::find()->andWhere(['app_id'=>$id])->asArray()->all();
      foreach($query as $item) {
        ///删除文件
        $base_path = dirname(__DIR__)."/web/app_image/app_";
        $img_path = $base_path.$item['arch_name'].'_'.$id.'.jpg';
        if(file_exists($img_path)) unlink($img_path);
      }
      DcmdAppArchDiagram::deleteAll(['app_id'=>$id]);
    }
    /**
     * Finds the DcmdApp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdApp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdCbsApp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findNetwork($id)
    {
        if (($model = DcmdAppSegment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getDepart() {
      $ret = DcmdDepartment::find()->asArray()->all();
      $depart = array();
      foreach($ret as $item) {
       $depart[$item['depart_id']] = $item['depart_name'];
      }
      return $depart;
   }
   protected function getUserGroup() {
     $ret = DcmdGroup::find()->asArray()->all();
     $user_group = array();
     $user_group['sys'] = array();
     $user_group['svr'] = array();
     foreach($ret as $item) {
      if($item['gtype'] == 1)
       $user_group['sys'][$item['gid']] = $item['gname'];
      else
       $user_group['svr'][$item['gid']] = $item['gname']; 
     }
     return $user_group;
  }
  public function userGroupName($gid) {
    $ret = DcmdUserGroup::findOne($gid);
    if($ret) return $ret['gname'];
    return "";
  }
  public function department($depart_id) {
   $ret = DcmdDepartment::findOne($depart_id);
   if ($ret) return $ret['depart_name'];
   return "";
  }
  private function oprlog($opr_type, $sql) {
    $opr_log = new DcmdOprLog();
    $opr_log->log_table = "dcmd_app";          
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
