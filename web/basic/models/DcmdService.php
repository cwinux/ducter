<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_service".
 *
 * @property integer $svr_id
 * @property string $svr_name
 * @property string $svr_alias
 * @property string $svr_path
 * @property string $run_user
 * @property integer $app_id
 * @property integer $owner
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svr_name', 'svr_alias', 'svr_path', 'run_user', 'app_id', 'owner', 'node_multi_pool', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['app_id', 'owner', 'opr_uid', 'node_multi_pool','svr_mem','svr_cpu','svr_net','svr_io'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['svr_name'],  'match', 'pattern'=>'/^[a-zA-Z0-9_-]+$/', 'message'=>'只可包含[a-z,A-Z,0-9,_,-]字符'],
            [['svr_name', 'svr_alias', 'svr_path'], 'string', 'max' => 128],
            [['run_user'], 'match', 'pattern'=>'/^[a-zA-Z0-9_-]+$/', 'message'=>'只可包含[a-z,A-Z,0-9,_]字符'],
            [['run_user'], 'string', 'max' => 16],
            [['service_tree','image_name'], 'string', 'max' => 256],
            [['comment'], 'string', 'max' => 512],
            [['app_id', 'svr_name'], 'unique', 'targetAttribute' => ['app_id', 'svr_name'], 'message' => 'The combination of Svr Name and App ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'svr_id' => 'Svr ID',
            'svr_name' => 'Svr Name',
            'svr_alias' => 'Svr Alias',
            'svr_path' => 'Svr Path',
            'run_user' => 'Run User',
            'service_tree' => 'Service Tree',
            'app_id' => 'App ID',
            'owner' => 'Owner',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
            'svr_name' => 'Svr Name',
            'svr_cpu' => 'Svr Cpu',
            'svr_net' => 'Svr Net',
            'image_name' => 'Image Name',
        ];
    }
   public function getAppName($app_id) {
     $ret = DcmdApp::findOne($app_id);
     if ($ret) return $ret['app_name'];
     return "";
   }
  public function getAppAlias($app_id) {
     $ret = DcmdApp::findOne($app_id);
     if ($ret) return $ret['app_alias'];
     else return "";
  }
  public function getUserName($uid) {
    $ret = DcmdUser::findOne($uid);
    if($ret) return $ret['username'];
    return "";
  }
  public function convert($n){
    if($n == 1) return "是";
    return "否";
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
        if ($query->is_deploy) return $item['task_name'];
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
