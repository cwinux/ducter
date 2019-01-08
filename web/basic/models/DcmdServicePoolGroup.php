<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdServicePoolGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service_pool_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'svr_id', 'pool_group'], 'required'],
            [['app_id', 'svr_id','opr_uid'],'safe'],
            [['ctime'],'safe'],
            [['pool_group'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'app_id' => 'App Id',
            'svr_id' => 'Svr Id',
            'pool_group' => 'Pool Group',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }

    public function getAppName($id)
    {
      $model = DcmdApp::findOne($id);
      if($model) return $model->app_name;
      return "";
    }

    public function getSvrName($id)
    {
      $model = DcmdService::findOne($id);
      if($model) return $model->svr_name;
      return "";
    }

}
