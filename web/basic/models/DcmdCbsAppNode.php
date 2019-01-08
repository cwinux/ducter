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
class DcmdCbsAppNode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_cbs_app_node';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'nid', 'state', 'ip', 'ctime'], 'required'],
            [['app_id', 'nid', 'state'], 'integer'],
            [['ctime'], 'safe'],
            [['status'], 'string', 'max' => 16],
            [['ip'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'app_id' => 'App ID',
            'app_name' => 'App Name',
            'nid' => 'Nid',
            'state' => 'State',
            'ip' => 'Ip',
            'status' => 'Status',
            'ctime' => 'Ctime', 
        ];
    }
   public function getCbsApp($app_id)
   {
     $query = DcmdCbsApp::findOne($app_id);
     if($query) return $query->app_name;
     return "";
   }
    public function getDcmdCbsApp(){
        return $this->hasOne(DcmdCbsApp::className(),['app_id'=>'app_id']); // 这里怎么写，请看文档和结合你的实际表结构,这里是用member的mid去关联memdesc的mid  
    }
}
