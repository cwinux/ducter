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
use app\models\DcmdNetwork;
use app\models\DcmdNetworkSearch;
use app\models\DcmdIdcNetwork;
use app\models\DcmdIdcNetworkSearch;
use app\models\DcmdAppNetwork;
use app\models\DcmdAppNetworkSearch;
use app\models\DcmdAppSegmentSearch;
use app\models\DcmdPrivate;
use app\models\DcmdApp;
use app\models\DcmdService;
use app\models\DcmdOprCmdSearch;
use app\models\DcmdOprCmdRepeatExecSearch;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * DcmdNodeController implements the CRUD actions for DcmdNode model.
 */
class DcmdAppNetworkController extends Controller
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
        $searchModel = new DcmdAppSegmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        ///获取服务池列表
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUnuseNode()
    {

        $searchModel = new DcmdNodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
        ///获取服务池列表
        $query = DcmdNodeGroup::find()->orderBy('ngroup_name')->asArray()->all();
        $dcmd_node_group = array();
        foreach($query as $item) $dcmd_node_group[$item['ngroup_id']] = $item['ngroup_name'];
        return $this->render('unuse-node', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dcmd_node_group' => $dcmd_node_group,
        ]);
    }
    /**
     * Displays a single DcmdNode model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->idcModel($id);
        $queryParams = array('r' => 'dcmd-network/view', 'idc_id' => $id);
        $searchModel = new DcmdAppNetworkSearch();
        $dataProvider = $searchModel->search($queryParams, true);

    //    $ips = $this->getIps($model->segment);
        return $this->render('view', [
            'model' => $this->idcModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIps($id)
    {
        $model = $this->appModel($id);
        $ips = $this->actionGetips($model->host_segment);
        return $this->render('ips', [
            'model' => $this->appModel($id),
            'ips' => $ips,
        ]);
    }

    public function actionGetips($segment)
    {
      $seg = explode("/",$segment);
      $ret_msg = '<table id="table" class="table table-striped table-bordered"><tbody>';
      $ret_msg .= "<th>Ip</th><th>State</th><th>Type</th>";
      $query = DcmdPrivate::find()->orderBy('vm_ip')->asArray()->all();
      if ($query){
        $IPS = array_column($query,'vm_ip');
        foreach($IPS as $ip){
          if ($this->netMatch($ip, $seg[0], $seg[1])){
            $ret_msg .= "<tr><td>$ip</td><td>使用中</td><td>虚拟机ip</td></tr>";
          }
        }
      }
      $ret_msg .= "</tbody></table>";
      return $ret_msg;
    }

    private function netMatch($ip, $network, $mask)
    {
      $mask1 = 32 - $mask;
      return ((ip2long($ip) >> $mask1) == (ip2long($network) >> $mask1));
    }

    public function actionUnuseIps($segment)
    {
      list($ip, $m) = explode("/", $segment);
      $ipd = ip2long($ip);
      $blp1 = decbin(ip2long($ip));
      $blp = str_repeat("0",32-strlen($blp1)).$blp1;
      $dlp = long2ip(bindec($blp));
      $bSub = $this->mask2bin($m);
      $slp = $blp & $bSub;
      $elp = $blp | $this->revBin($bSub);
      $result = $this->checkIps(bindec($slp), bindec($elp));
//      $result = bindec($elp);
//      return strlen($blp);
      return $result;
    }

    private function mask2bin($n)
    {
      $n = intval($n);
      if($n < 0 || $n > 32) die('error submask'); 
      return str_repeat( "1", $n).str_repeat( "0",32-$n); 
    }

    private function revBin($s)
     {
       $p = array('0','1','2');
       $r = array('2','0','1');
       return str_replace($p,$r,$s);
     }

    private function checkIps($start,$end)
    {
      $ret_msg = '<table id="table" class="table table-striped table-bordered"><tbody>';
      $ret_msg .= "<th>Ip</th><th>State</th><th>Type</th>";
      $IPS=array();
      $query = DcmdPrivate::find()->orderBy('vm_ip')->asArray()->all();
      if ($query){
        $IPS = array_column($query,'vm_ip');
        $ip2l = array_map("ip2long",$IPS);
      }
      for($x=$start;$x<$end;$x++)
      {
        if(!in_array($x,$ip2l))
        {
          $ip = long2ip($x);
          $ret_msg .= "<tr><td>$ip</td><td>未使用</td><td>虚拟机网段</td></tr>";
        }
      }
      $ret_msg .= "</tbody></table>";
      return $ret_msg;
    }

    public function actionAddsegment($idc_id,$app_name,$host_segment,$vm_segment,$gateway,$vlan)
    {
      $model = new DcmdAppNetwork();
      $model->idc_id = $idc_id;
      $model->app_name = $app_name;
      $model->host_segment = $host_segment;
      $model->vm_segment = $vm_segment;
      $model->gateway = $gateway;
      $model->vlan = $vlan;
      if($model->save())
        $data = '{"data":"sucess"}';
      else
        $data = '{"data":"failed"}';
      return $data;
    }

    public function actionCreate()
    {
        $model = new DcmdIdcNetwork();
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
     * Updates an existing DcmdNode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->idcModel($id);

        if(Yii::$app->request->post()) {
          if ($model->load(Yii::$app->request->post())) {
            $model->save();
            $this->oprlog(2,"update network:".$model->id);
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

    public function actionSubnetUpdate($id)
    {
        $model = $this->appModel($id);

        if(Yii::$app->request->post()) {
          if ($model->load(Yii::$app->request->post())) {
            $model->save();
            $this->oprlog(2,"update subnet:".$model->id);
            Yii::$app->getSession()->setFlash('success', "修改成功");
            return $this->redirect(['view', 'id' => $model->idc_id]);
          }
         $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "修改失败:".$err_str);
        }
        return $this->render('subnet-update', [
             'model' => $model,
        ]);
    }
    /**
     * Deletes an existing DcmdNode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $ngroup_id=NULL)
    {
        $model = $this->idcModel($id);
        $this->oprlog(3,"delete network info:".$model->id);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');

        return $this->redirect(['index']);
    }

    public function actionSubnetDelete($id)
    {
        $model = $this->appModel($id);
        $this->oprlog(3,"delete subnet:".$model->id);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');

        return $this->redirect(['view', 'id' => $model->idc_id]);
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
        $model = $this->idcModel($id);
        $this->oprlog(3, "delete network info:".$model->name);
        $suc_msg .=$model->name.':删除成功<br>';
        $model->delete();
      }
      if($suc_msg != "") Yii::$app->getSession()->setFlash('success', $suc_msg);
      if($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['index']);
    }

    public function actionSubnetDeleteAll()
    {

      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $suc_msg = "";
      $err_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->appModel($id);
        $this->oprlog(3, "delete subnet network:".$model->id);
        $suc_msg .=$model->id.':删除成功<br>';
        $model->delete();
      }
      if($suc_msg != "") Yii::$app->getSession()->setFlash('success', $suc_msg);
      if($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['view', 'id' => $model->idc_id]);
    }

    public function actionConvert()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择设备!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      if(count($select) < 1) {
        Yii::$app->getSession()->setFlash('error', '未选择设备!');
        return $this->redirect(['index']);
      } 
      ///设备池
      $query = DcmdNodeGroup::find()->asArray()->all();
      $node_group = array();
      foreach($query as $item) $node_group[$item['ngroup_id']] = $item['ngroup_name'];
      ///获取需要变更的ip
      $ips_info = array();
      $ids="";
      foreach($select as $k=>$id){
        $model = $this->findModel($id);
        array_push($ips_info, array('id'=>$id, 'ip'=>$model->ip));
        $ids.=$id.";";
      } 
      return $this->render('select_group', ['ips_info'=>$ips_info, 'ids'=>$ids, 'node_group'=>$node_group]);
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
        if (($model = DcmdNetwork::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function appModel($id)
    {
        if (($model = DcmdappNetwork::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function idcModel($id)
    {
        if (($model = DcmdIdcNetwork::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
