<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * This is the model class for table "dcmd_node".
 *
 */
class DcmdAppConfVersion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_app_conf_version';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'svr_id', 'svr_pool_id'], 'required'],
            [['app_id', 'svr_id', 'svr_pool_id'], 'integer'],
            [['ctime'], 'safe'],
            [['md5'], 'string', 'max' => 64],
            [['version','passwd','username'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_id' => 'App Id',
            'svr_id' => 'Svr Id',
            'svr_pool_id' => 'Svr Pool Id',
            'ctime' => 'Ctime',
            'md5' => 'Md5',
            'passwd' => 'Passwd',
            'version' => 'Version',
            'username' => 'Username',
        ];
    }
    public function getApp($app_id) 
    {
      $ret = DcmdApp::findOne($app_id);
      if ($ret) return $ret->app_name;
      else return "";
    }
    public function getService($svr_id)
    {
      $ret = DcmdService::findOne($svr_id);
      if ($ret) return $ret->svr_name;
      else return "";
    }
    public function getPool($svr_pool_id)
    {
      $ret = DcmdServicePool::findOne($svr_pool_id);
      if ($ret) return $ret->svr_pool;
      else return "";
    }
}
