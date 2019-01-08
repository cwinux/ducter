<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * This is the model class for table "dcmd_node".
 *
 */
class DcmdVmTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_vm_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vm_ip', 'bill_id', 'apply_user', 'ctime'], 'required'],
            [['state', 'order_id', 'step'], 'integer'],
            [['start_time', 'end_time', 'ctime'], 'safe'],
            [['vm_ip', 'task_type'], 'string', 'max' => 16],
            [['uuid', 'bill_id'], 'string', 'max' => 128],
            [['apply_user', 'step_name', 'state_name'], 'string', 'max' => 32],
            [['source_ip', 'os'], 'string', 'max' => 64],
            [['errmsg'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_id' => 'Task Id',
            'uuid' => 'Uuid',
            'bill_id' => 'Bill Id',
            'order_id' => 'Order Id',
            'vm_ip' => 'Vm Ip',
            'task_type' => 'Task Type',
            'step' => 'Step',
            'step_name' => 'Step Name',
            'os' => 'Os',
            'state' => 'State',
            'state_name' => 'State Name',
            'apply_user' => 'Apply User',
            'start_time' => 'Start Time',
            'ctime' => 'Ctime',
            'end_time' => 'End Time',
            'source_ip' => 'Source Ip',
            'errmsg' => 'Errmsg',
        ];
    }
}
