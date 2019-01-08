<?php

namespace app\controllers;

use Yii;
use app\models\DcmdNodeGroup;
use app\models\DcmdNode;
use app\models\DcmdAuditLog;
use app\models\DcmdServicePoolAudit;
use app\models\DcmdServicePoolAuditSearch;
use app\models\DcmdApp;
use app\models\DcmdUserGroup;
use app\models\DcmdFault;
use app\models\DcmdFaultSearch;
use app\models\DcmdService;
use app\models\DcmdServicePool;
use app\models\DcmdDcInfo;
use yii\data\ActiveDataProvider;
use app\models\DcmdNodeSearch;
use app\models\DcmdNodeGroupSearch;
use app\models\DcmdPrivate;
use app\models\DcmdServicePoolNode;
use app\models\DcmdServicePoolNodeSearch;
use app\models\DcmdOprLog;
use app\models\DcmdFaultReport;
use app\models\DcmdOprCmdSearch;
use app\models\DcmdOprCmdRepeatExecSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdServicePoolNodeController implements the CRUD actions for DcmdServicePoolNode model.
 */
class DcmdSearchController extends Controller
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
      $msg = '</tbody></table>';
      return $this->render('index', [
          'model' => $msg,
      ]);
    }

    public function actionNetSearch($network)
    {
      $query = DcmdNode::find()->asArray()->all();
      $ips = array_column($query, "ip");
      $networks = explode("\n",$network);
      $results = array();
      foreach($networks as $k=>$v) {
        $net = explode("/",$v);
        $mask = 32 - (int)$net[1];
        foreach($ips as $k=>$ip) {
          $ip = trim($ip);
          if((ip2long($ip) >> $mask) == (ip2long($net[0]) >> $mask)) {
            $model = DcmdServicePoolNode::findOne(["ip" => $ip]);
            if($model) {
              $app = DcmdApp::findOne(["app_id" => $model->app_id]);
              if($app)
                $app_name = $app->app_name;
            }
            else {
              $app_name = "";
            }
            $result = array("net"=>$v,"ip"=>$ip,"app"=>$app_name);
            array_push($results, $result);
          }
        } 
      }
      return json_encode($results);
    }

    public function actionVmSearch($type,$keyword)
    {
      $keys = explode("\n",$keyword);
      $key_string = implode('","',$keys);
      if ($type=="ip"){
        $sql = 'select vm_ip,vm_uuid,host_ip,flavor_name,os,business,module,contacts,state from dcmd_vm_assign where host_ip in ("'.$key_string.'") or vm_ip in ("'.$key_string.'") order by host_ip';
      }
      else {
        $sql = 'select vm_ip,vm_uuid,host_ip,flavor_name,os,business,module,contacts,state from dcmd_vm_assign where host_ip in (select ip from dcmd_node where sid in ("'.$key_string.'")) or vm_uuid in ("'.$key_string.'") order by host_ip';
      }
      $query = DcmdPrivate::findBySql($sql)->asArray()->all();
      return json_encode($query);
    }

    public function actionVmSearchs($type,$keyword)
    {
      $keys = explode("\n",$keyword);
      $key_string = implode('","',$keys);
      if ($type=="ip"){
        $sql = 'select vm_ip,vm_uuid,host_ip,flavor_name,os,business,module,contacts,state from dcmd_vm_assign where vm_ip in ("'.$key_string.'") order by host_ip';
      }
      else {
        $sql = 'select vm_ip,vm_uuid,host_ip,flavor_name,os,business,module,contacts,state from dcmd_vm_assign where host_ip in (select ip from dcmd_node where sid in ("'.$key_string.'")) order by host_ip';
      }
      $query = DcmdPrivate::findBySql($sql)->asArray()->all();
      return json_encode($query);
    }

}
