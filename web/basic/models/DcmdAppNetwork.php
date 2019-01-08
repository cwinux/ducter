<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdAppNetwork extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_app_network';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['host_segment', 'vm_segment', 'idc_id'], 'required'],
            [['vlan', 'idc_id'], 'safe'],
            [['host_segment', 'vm_segment', 'app_name', 'gateway'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'host_segment' => 'Host Segment',
            'idc_id' => 'Idc Id',
            'vm_segment' => 'Vm Segment',
            'app_name' => 'App Name',
            'gateway' => 'Gateway',
            'vlan' => 'Vlan',
        ];
    }
}
