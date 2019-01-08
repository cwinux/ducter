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
            [['svr_pool_id', 'svr_id', 'nid', 'app_id', 'ip', 'opr_uid', 'action', 'audit_uid'], 'required'],
            [['svr_pool_id', 'svr_id', 'nid', 'app_id', 'opr_uid', 'audit_uid'], 'integer'],
            [['ip', 'action'], 'string'],
            [['audit_time', 'ctime'], 'safe']
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
            'audit_time' => 'Audit Time',
            'opr_uid' => 'Opr Uid',
            'action' => 'Action',
            'audit_uid' => 'Audit Uid',
        ];
    }
}
