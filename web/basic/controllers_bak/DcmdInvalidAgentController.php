<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\DcmdCenter;

include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * DcmdNodeController implements the CRUD actions for DcmdNode model.
 */
class DcmdInvalidAgentController extends Controller
{
    /**
     * Lists all DcmdNode models.
     * @return mixed
     */
    public function actionIndex()
    {
      
        $query = DcmdCenter::findOne(['master'=>1]);
        $agent = array();
        if ($query) {
          list($ip, $port) = explode(':', $query["host"]);
          $invalidAgent = getInvalidAgent($ip, $port);
          if ($invalidAgent->getState() == 0) {
            $agentInfo = $invalidAgent->getAgentinfo();
            foreach($agentInfo as $item)  array_push($agent, array('report_ip'=>$item->getReportedIp(), 'conn_ip'=>$item->getConnectedIp(), 'version'=>$item->getVersion()));
          }
        } 
        return $this->render('index', [
            'ips' => $agent,
        ]);
    }

}
