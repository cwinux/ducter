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
class DcmdApp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_app';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_name', 'app_alias', 'service_tree','sa_gid', 'svr_gid','comp_id', 'comment', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['is_self','sa_gid', 'svr_gid', 'depart_id', 'opr_uid', 'comp_id'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['app_name'], 'match', 'pattern'=>'/^[a-zA-Z0-9_-]+$/', 'message'=>'只可包含[a-z,A-Z,0-9,_,-]字符'],
            [['app_type'], 'string', 'max' => 16],
            [['app_name', 'app_alias'], 'string', 'max' => 128],
            [['service_tree'], 'string', 'max' => 256],
            [['comment'], 'string', 'max' => 512],
            [['app_name'], 'unique'],
            [['app_alias'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_id' => 'App ID',
            'app_name' => 'App Name',
            'app_alias' => 'App Alias',
            'sa_gid' => 'Sa Gid',
            'svr_gid' => 'Svr Gid',
            'service_tree' => 'Service Tree',
            'comp_id' => 'Comp Id',
            'app_type' => 'App Type',
            'depart_id' => 'Depart ID',
            'comment' => 'Comment',
            'utime' => 'Utime',
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
   public function getComp($comp_id) {
     $ret = DcmdCompany::findOne($comp_id);
     if($ret) return $ret['comp_name'];
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
