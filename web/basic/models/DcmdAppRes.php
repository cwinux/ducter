<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_app".
 *
 * @property integer $app_id
 * @property string $app_name
 * @property string $app_alias
 * @property integer $sa_gid
 * @property integer $svr_gid
 * @property integer $depart_id
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdAppRes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_app_res';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id','res_id'], 'required'],
            [['svr_id','svr_pool_id','is_own','opr_uid'], 'integer'],
            [['app_id','ctime'], 'safe'],
            [['res_type','res_id'], 'string', 'max' => 32],
            [['res_name'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_id' => 'App ID',
            'svr_id' => 'Svr Id',
            'svr_pool_id' => 'Svr pool Id',
            'is_own' => 'Is Own',
            'res_name' => 'Res Name',
            'res_id' => 'Res ID',
            'res_type' => 'Res Type',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
    public function userGroupName($gid) {
      $ret = DcmdGroup::findOne($gid);
      if($ret) return $ret['gname'];
      else return "";
    }
   public function department($depart_id) {
     $ret = DcmdDepartment::findOne($depart_id);
     if($ret) return $ret['depart_name'];
     else return "";
   }
   public function comment($comment) {
     return str_replace("\n","<br>", $comment);
   }
   public function getAppName($app_id) {
     $ret = DcmdApp::findOne($app_id);
     if($ret) return $ret['app_name'];
     else return "";
   }
   public function getService($svr_id) {
     $ret = DcmdService::findOne($svr_id);
     if($ret) return $ret['svr_name'];
     else return "";
   }
   public function getPool($svr_pool_id) {
     $ret = DcmdServicePool::findOne($svr_pool_id);
     if($ret) return $ret['svr_pool'];
     else return "";
   }
}
