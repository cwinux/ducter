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
class DcmdVmOplog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_vm_op_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vm_ip', 'action',  'state'], 'required'],
            [['state'], 'integer'],
            [['action', 'vm_ip'], 'string'],
            [['apply_user'], 'string', 'max' => 32],
            [['start_time', 'end_time'], 'safe'],
            [['source_ip'], 'string', 'max' => 64],
            [['uuid'], 'string', 'max' => 128],
            [['errmsg'], 'string', 'max' =>256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vm_ip' => 'Vm Ip',
            'action' => 'Action',
            'state' => 'State',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'apply_user' => 'Apply User',
            'source_ip' => 'Source Ip',
            'errmsg' => 'Errmsg',
            'uuid' => 'Uuid',
        ];
    }
}
