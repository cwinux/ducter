<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * This is the model class for table "dcmd_node".
 *
 */
class DcmdAlarmHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_vm_alarm_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fault_id'], 'integer'],
            [['ctime', 'send_time', 'content'], 'safe'],
            [['host_ip', 'vm_ip'], 'string', 'max' => 16],
            [['app_name'], 'string', 'max' => 128],
            [['email'], 'string', 'max' => 512],
            [['sms'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fault_id' => 'Fault Id',
            'app_name' => 'App Name',
            'host_ip' => 'Host Ip',
            'vm_ip' => 'Vm Ip',
            'content' => 'Content',
            'email' => 'Email',
            'sms' => 'Sms',
            'ctime' => 'Ctime',
            'send_time' => 'Send Time',
        ];
    }

    public function getAppIDByName($app_name)
    {
      $query = DcmdApp::findOne(['app_name' => $app_name]);
      if ($query) return $query->app_id;
      return " ";
    }

    public function getNodeIDByIP($host_ip)
    {
      $query = DcmdNode::findOne(['ip' => $host_ip]);
      if ($query) return $query->nid;
      return " ";
    }

    public function getVmIDByIP($vm_ip)
    {
      $query = DcmdPrivate::findOne(['vm_ip' => $vm_ip]);
      if ($query) return $query->id;
      return " ";
    }
}
