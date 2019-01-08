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
class DcmdCbsApp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_cbs_app';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_name', 'idc_id', 'state', 'user', 'passwd', 'description', 'utime', 'ctime'], 'required'],
            [['idc_id', 'state','server_num'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['app_type'], 'string', 'max' => 16],
            [['user', 'passwd','ram_total','ram_alocate','ram_used'], 'string', 'max' => 64],
            [['app_name'], 'string', 'max' => 128],
            [['description'], 'string', 'max' => 256],
            [['app_name'], 'unique'],
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
            'idc_id' => 'Idc Id',
            'state' => 'State',
            'user' => 'User',
            'app_type' => 'App Type',
            'passwd' => 'Passwd',
            'description' => 'Description', 
            'utime' => 'Utime', 
            'ctime' => 'Ctime',
            'server_num' => 'Server Num',
            'ram_total' => 'Ram Total',
            'ram_alocate' => 'Ram Alocate',
            'ram_used' => 'Ram Used',
        ];
    }

    public function getUser($id)
    {
      $ret = DcmdCbsBussiness::findOne($id);
      if($ret) return $ret['name'];
      else return ""; 
    }
 
    public function getIdc($idc_id)
    {
      $ret = DcmdDcInfo::findOne($idc_id);
      if($ret) return $ret['dc'];
      else return "";
    }

    public function getState($state)
    {
      if($state == 1) return "在线";
      else return "下线";
    }
    public function getDcmdDcInfo(){
        return $this->hasOne(DcmdDcInfo::className(),['dc_id'=>'idc_id']); // 这里怎么写，请看文档和结合你的实际表结构,这里是用member的mid去关联memdesc的mid  
    }
}
