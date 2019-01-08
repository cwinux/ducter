<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * This is the model class for table "dcmd_node".
 *
 */
class DcmdFaultReport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_fault_report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fault_title', 'fault_area', 'fault_date', 'fault_level', 'find_way', 'fault_show', 'sustained_time', 'start_time', 'handle_time', 'recover_time', 'reason', 'discover_time', 'reason_type', 'response_model', 'response_person', 'handle_process', 'improve','fault_status'], 'required'],
            [['fault_level'], 'string', 'max' => 2],
            [['custom_type', 'find_way', 'sustained_time', 'fault_status'], 'string', 'max' => 16],
            [['fault_show'], 'string', 'max' => 128],
            [['start_time', 'handle_time', 'recover_time', 'discover_time', 'reason_type', 'response_model', 'response_person'], 'string', 'max' => 32],

            [['fault_title', 'fault_area', 'fault_date', 'case_num', 'reason', 'improve'], 'string','max' => 64],
            [['custom_name'], 'string', 'max' => 255],
            [['handle_process'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fault_id' => 'Fault Id',
            'fault_title' => 'Fault Title',
            'fault_area' => 'Fault Area',
            'custom_type' => 'Custom Type',
            'custom_name' => 'Custom Name',
            'fault_date' => 'Fault Date',
            'case_num' => 'Case Num',
            'fault_level' => 'Fault Level',
            'find_way' => 'Find Way',
            'fault_show' => 'Fault Show',
            'sustained_time' => 'Sustained Time',
            'start_time' => 'Start Time',
            'handle_time' => 'Handle Time',
            'recover_time' => 'Recover Time',
            'reason' => 'Reason',
            'discover_time' => 'Discover Time',
            'reason_type' => 'Reason Type',
            'response_model' => 'Response Model',
            'response_person' => 'Response Person',
            'handle_process' => 'Handle Process',
            'improve' => 'Improve',
            'fault_status' => 'Fault Status',
        ];
    }
}
