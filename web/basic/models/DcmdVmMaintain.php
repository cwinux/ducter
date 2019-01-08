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
class DcmdVmMaintain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_vm_maintain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcmd_info', 'online_info'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'dcmd_info' => 'Dcmd Info',
            'online_info' => 'Online Info',
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
      return $ip;
    }

    public function getNodeIDByIP($ip) {
      $query = DcmdNodeGroup::findOne(['manage_ip' => $ip]);
      if ($query) return $query->ngroup_id;
      return $ip;
    }


    public function getGinfo($id) {
      $query = DcmdNodeGroupInfo::findOne($id);
      if ($query) return $query->name;
      return "";
    }
}
