<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * This is the model class for table "dcmd_service_pool".
 *
 * @property integer $svr_pool_id
 * @property string $svr_pool
 * @property integer $svr_id
 * @property integer $app_id
 * @property string $repo
 * @property string $env_ver
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdServicePool extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service_pool';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svr_pool', 'svr_id', 'app_id', 'repo', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['svr_id', 'app_id', 'opr_uid','tag_task_id'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['svr_pool'], 'match', 'pattern'=>'/^[a-zA-Z0-9_-]+$/', 'message'=>'只可包含[a-z,A-Z,0-9,_,-]字符'],
            [['svr_pool','env_passwd','tag'], 'string', 'max' => 128],
            [['repo', 'comment'], 'string', 'max' => 512],
            [['image_user','image_passwd','env_ver','env_md5','pool_group'], 'string', 'max' => 64],
            [['image_url','image_name'], 'string', 'max' => 256],
            [['svr_id', 'svr_pool'], 'unique', 'targetAttribute' => ['svr_id', 'svr_pool'], 'message' => 'The combination of Svr Pool and Svr ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'svr_pool_id' => 'Svr Pool ID',
            'svr_pool' => 'Svr Pool',
            'svr_id' => 'Svr ID',
            'app_id' => 'App ID',
            'tag_task_id' => 'Tag Task Id',
            'tag' => 'Tag',
            'pool_group' => 'Pool Group',
            'repo' => 'Repo',
            'env_ver' => 'Env Ver',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'env_passwd' => 'Env Passwd',
            'env_md5' => 'Env Md5',
            'image_name' => 'Image Name',
            'image_url' => 'Image Url',
            'image_user' => 'Image User',
            'image_passwd' => 'Image Passwd',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
    public function getAppName($app_id) {
      $ret = DcmdApp::findOne($app_id);
      if ($ret) return $ret['app_name'];
      else return '';
    }
   public function getServiceName($svr_id) {
     $ret = DcmdService::findOne($svr_id);
     if($ret) return $ret['svr_name'];
     else return '';
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

  public function getPool($svr_id) {
    $ret = DcmdServicePool::findOne($svr_id);
    if($ret) return $ret->svr_pool;
    else return "";
  }

  public function getTask($svr_pool_id) {
    $ret = DcmdTaskServicePool::find()->andWhere(['svr_pool_id'=>$svr_pool_id])->asArray()->all();
    if($ret) {
      foreach($ret as $item) {
        $query = DcmdTask::findOne($item['task_id']);
        if ($query && $query->is_deploy) return $query->task_name;
        else continue;
      }
    return "";
    }
  }

  public function getTaskId($svr_pool_id) {
    $ret = DcmdTaskServicePool::find()->andWhere(['svr_pool_id'=>$svr_pool_id])->asArray()->all();
    if($ret) {
      foreach($ret as $item) {
        $query = DcmdTask::findOne($item['task_id']);
        if ($query && $query->is_deploy) return $query->task_id;
        else continue;
      }
    return "";
    }
  }

    public function getDcmd_app()
    {
       return $this->hasOne(DcmdApp::className(), ['app_id' => 'app_id']); 
    }

}
