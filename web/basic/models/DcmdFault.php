<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * This is the model class for table "dcmd_node".
 *
 */
class DcmdFault extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_vm_fault';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_name', 'start_time', 'is_confirm', 'confirm_time', 'erase_time', 'confirm_user', 'erase_user'], 'required'],
            [['is_confirm'], 'integer'],
            [['confirm_time', 'erase_time'], 'safe'],
            [['host_ip', 'host_fault', 'vm_ip', 'vm_fault'], 'string', 'max' => 16],
            [['app_name', 'vm_uuid'], 'string', 'max' => 128],
            [['confirm_user', 'erase_user'], 'string', 'max' => 32],
            [['comment'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fault_id' => 'Fault Id',
            'app_name' => 'App Name',
            'host_ip' => 'Host Ip',
            'host_fault' => 'Host Fault',
            'vm_uuid' => 'Vm Uuid',
            'vm_ip' => 'Vm Ip',
            'vm_Fault' => 'Vm Fault',
            'start_time' => 'Start Time',
            'is_confirm' => 'Is Confirm',
            'confirm_time' => 'Confirm Time',
            'erase_time' => 'Erase Time',
            'confirm_user' => 'Confirm User',
            'erase_user' => 'Erase User',
        ];
    }
}
