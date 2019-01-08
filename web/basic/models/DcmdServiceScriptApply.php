<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdServiceScriptApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service_script_apply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svr_id', 'app_id'], 'required'],
            [['is_apply','opr_uid','svr_id','app_id'], 'integer'],
            [['pool_group','script_md5'], 'string', 'max' => 64],
            [['script'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'svr_id' => 'Svr Id',
            'app_id' => 'App Id',
            'pool_group' => 'Pool Group', 
            'is_apply' => 'Is Apply',
            'opr_uid' => 'Opr Uid',
            'script_md5' => 'Script Md5',
            'script' => 'Script',
        ];
    }

   public function getAppName($app_id) {
     $ret = DcmdApp::findOne($app_id);
     if ($ret) return $ret['app_name'];
     return "";
   }

   public function getSvrName($svr_id) {
     $ret = DcmdService::findOne($svr_id);
     if ($ret) return $ret['svr_name'];
     return "";
   }

   public function getUser($opr_uid) {
     $ret = DcmdUser::findOne($opr_uid);
     if ($ret) return $ret['username'];
     return "";
   }

}
