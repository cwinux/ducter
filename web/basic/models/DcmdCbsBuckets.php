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
class DcmdCbsBuckets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_cbs_buckets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'bucket_name'], 'required'],
            [['uid','app_id'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['type'], 'string', 'max' => 32],
            [['bucket_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bid' => 'Bid',
            'uid' => 'Uid',
            'bucket_name' => 'Bucket Name',
            'utime' => 'Utime', 
            'ctime' => 'Ctime', 
            'type' => 'Type',
            'app_id' => 'App Id'
        ];
    }

    public function getDcmdCbsBucketsStats(){  
        return $this->hasOne(DcmdCbsBucketsStats::className(),['bid'=>'bid']); // 这里怎么写，请看文档和结合你的实际表结构,这里是用member的mid去关联memdesc的mid  
    } 
    public function getDcmdCbsBussiness(){
        return $this->hasOne(DcmdCbsBussiness::className(),['uid'=>'uid']); // 这里怎么写，请看文档和结合你的实际表结构,这里是用member的mid去关联memdesc的mid  
    }

    public function getUser($id)
    {
      $ret = DcmdCbsBussiness::findOne($id);
      if($ret) return $ret['bussiness'];
      else return ""; 
    } 

    public function getApp($id)
    {
      $ret = DcmdCbsApp::findOne($id);
      if($ret) return $ret['app_name'];
      else return "";
    }
}
