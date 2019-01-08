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
class DcmdNodeAudit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_node_audit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'ngroup_id', 'host', 'sid', 'did', 'comment', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['ngroup_id', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['ip'], 'string', 'max' => 16],
            [['host', 'sid', 'did'], 'string', 'max' => 128],
            [['comment'], 'string', 'max' => 512],
            [['ip'], 'match', 'pattern'=>'/^(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])$/'],
            [['ip'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nid' => 'Nid',
            'ip' => 'Ip',
            'ngroup_id' => 'Ngroup ID',
            'host' => 'Host',
            'sid' => 'Sid',
            'did' => 'Did',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
    public function getAgentState($ip)
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
    }
    /**
     *Get dcmd node group name
     */
    public function getNodeGname($ngid) {
      $query = DcmdNodeGroup::findOne($ngid);
      if ($query) return $query->ngroup_name;
      return "";
    }
}
