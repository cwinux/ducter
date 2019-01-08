<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_opr_log".
 *
 * @property integer $id
 * @property string $log_table
 * @property integer $opr_type
 * @property string $sql_statement
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdAuditLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_audit_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svr_pool_id', 'svr_id', 'nid', 'app_id', 'ip', 'utime', 'ctime', 'opr_uid', 'action'], 'required'],
            [['svr_pool_id', 'svr_id', 'nid', 'app_id', 'opr_uid'], 'integer'],
            [['ip', 'action'], 'string'],
            [['utime', 'ctime'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'svr_pool_id' => 'Svr Pool Id',
            'svr_id' => 'Svr Id',
            'nid' => 'Nid',
            'app_id' => 'App Id',
            'ip' => 'Ip',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
            'action' => 'Action',
        ];
    }
}
