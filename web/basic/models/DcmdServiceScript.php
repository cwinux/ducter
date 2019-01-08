<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdServiceScript extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service_script';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svr_id', 'app_id'], 'required'],
            [['opr_uid','svr_id','app_id','approve_uid'], 'integer'],
            [['pool_group','script_md5'], 'string', 'max' => 64],
            [['script','approve_time','ctime'],'safe']
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
            'approve_uid' => 'Approve Uid',
            'opr_uid' => 'Opr Uid',
            'script_md5' => 'Script Md5',
            'pool_group' => 'Pool Group', 
            'script' => 'Script',
            'approve_time' => 'Approve Time',
            'ctime' => 'Ctime',
        ];
    }
}
