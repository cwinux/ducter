<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdNetwork extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_network';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['segment', 'netmask'], 'required'],
            [['vlan'],'safe'],
            [['segment', 'netmask', 'gateway'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'segment' => 'Segment',
            'netmask' => 'Netmask',
            'vlan' => 'Vlan',
            'gateway' => 'Gateway',
        ];
    }

}
