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
class DcmdAppNodeGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_app_node_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id','ngroup_id'], 'required'],
            [['opr_uid','ngroup_id'], 'integer'],
            [['ctime','app_id'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_id' => 'App Id',
            'ngroup_id' => 'Ngroup Id',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
    public function getAppName($id)
    {
      $query = DcmdApp::findOne($id);
      if ($query) return $query->app_name;
      return "";
    }
    public function getGroup($ngroup_id) {
      $query = DcmdNodeGroup::findOne($ngroup_id);
      if ($query) return $query->ngroup_name;
      return "";
    }
    public function getDcmdApp(){
        return $this->hasOne(DcmdApp::className(),['app_id'=>'app_id']); // 这里怎么写，请看文档和结合你的实际表结构,这里是用member的mid去关联memdesc的mid  
    }
    public function getDcmdNodeGroup(){
        return $this->hasOne(DcmdNodeGroup::className(),['ngroup_id'=>'ngroup_id']); // 这里怎么写，请看文档和结合你的实际表结构,这里是用member的mid去关联memdesc的mid  
    }
}
