<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * This is the model class for table "dcmd_node".
 *
 * @property integer $nid
 * @property string $ip
 * @property integer $ngroup_id
 * @property string $host
 * @property string $sid
 * @property string $did
 * @property string $os_type
 * @property string $os_ver
 * @property string $bend_ip
 * @property string $public_ip
 * @property string $mach_room
 * @property string $rack
 * @property string $seat
 * @property string $online_time
 * @property string $server_brand
 * @property string $server_model
 * @property string $cpu
 * @property string $memory
 * @property string $disk
 * @property string $purchase_time
 * @property string $maintain_time
 * @property string $maintain_fac
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdHostVm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_host_vm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vm_uuid'], 'required'],
            [['utime'], 'safe'],
            [['cpu', 'memory', 'disk'], 'integer'],
            [['app_name', 'vm_uuid', 'host_name'], 'string', 'max' => 128],
            [['flavor_name', 'image_name'], 'string', 'max' => 32],
            [['host_ip', 'vm_ip', 'state'], 'string', 'max' => 16],
            [['host_ip', 'vm_ip'], 'match', 'pattern'=>'/^(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])$/'],
            [['vm_uuid'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'vm_uuid' => 'Vm Uuid',
            'utime' => 'Utime',
            'cpu' => 'Cpu',
            'memory' => 'Memory',
            'disk' => 'Disk',
            'state' => 'State',
            'app_name' => 'App Name',
            'flavor_name' => 'Flavor Name',
            'image_name' => 'Image Name',
            'host_ip' => 'Host Ip',
            'vm_ip' => 'Vm Ip',
            'host_name' => 'Host Name',
        ];
    }
/*    public function getAgentState($ip)
    {
      $query = DcmdCenter::findOne(['master'=>1]);

      if ($query) {
         list($host, $port) = explode(':', $query["host"]);
         $agent_info = getAgentInfo($host, $port, array($ip), 1);
         if($agent_info->getState() == 0) {
          foreach($agent_info->getAgentinfo() as $agent) {
            if($agent->getState() == 3) return "连接";
            return "未连接";
          }
         }
       }
       return "未连接";
    }*/
    /**
     *Get dcmd node group name
     */
    public function getNodeID($ip) {
      $query = DcmdNode::findOne(['ip' => $ip]);
      if ($query) return $query->nid;
      return " ";
    }

    public function getState($state) {
      $arr = array("使用","未使用","待下线","可回收");
      return $arr[$state];
    }

    public function getNodeIDByIP($ip) {
      $query = DcmdApp::findOne(['app_name' => $ip]);
      if ($query) return $query->app_id;
      return $ip;
    }

    public function getClusterByIP($ip) {
      $query = DcmdApp::findOne(['app_name' => $ip]);
      if ($query) return $query->app_name;
      return "";
    }

    public function getGinfo($id) {
      $query = DcmdNodeGroupInfo::findOne($id);
      if ($query) return $query->name;
      return "";
    }
}
