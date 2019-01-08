<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdVmInvalid extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_host_vm_invalid';
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
            [['app_name', 'host_name', 'vm_uuid'], 'string', 'max' => 128],
            [['flavor_name', 'image_name'], 'string', 'max' => 32],
            [['state'], 'string', 'max' => 16],
            [['host_ip'], 'match', 'pattern'=>'/^(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])$/'],
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
            'host_name' => 'Host Name',
        ];
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

    public function getNodeID($ip) {
      $query = DcmdNode::findOne(['ip' => $ip]);
      if ($query) return $query->nid;
      return " ";
    }
}
