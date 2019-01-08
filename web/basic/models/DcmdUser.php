<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_user".
 *
 * @property integer $uid
 * @property string $username
 * @property string $passwd
 * @property integer $sa
 * @property integer $admin
 * @property integer $depart_id
 * @property string $tel
 * @property string $email
 * @property integer $state
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'passwd', 'sa', 'admin', 'depart_id', 'tel', 'email', 'state', 'comment', 'utime', 'ctime', 'opr_uid','comp_id'], 'required'],
            [['sa', 'admin', 'depart_id', 'state', 'opr_uid','comp_id'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['username', 'tel'], 'string', 'max' => 128],
            [['passwd', 'email'], 'string', 'max' => 64],
            [['comment'], 'string', 'max' => 512],
            [['username'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'username' => 'Username',
            'passwd' => 'Passwd',
            'sa' => 'Sa',
            'comp_id' => 'Comp Id',
            'admin' => 'Admin',
            'depart_id' => 'Depart ID',
            'tel' => 'Tel',
            'email' => 'Email',
            'state' => 'State',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
   public function getDepartment($depart_id) {
    $ret = DcmdDepartment::findOne($depart_id);
    if($ret) return $ret['depart_name'];
    else return "";
   }
   public function convert($num) {
     if($num == 1) return "是";
     else if($num == 0) return "否";
     return "";
  }

    public function getCompany($comp_id)
    {
      $model = DcmdCompany::findOne($comp_id);
      if ($model) return $model->comp_name;
      else return "";
    }
    public function getUser($opr_uid)
    {
      $model = DcmdUser::findOne($opr_uid);
      if ($model) return $model->username;
      else return "";
    }
}
