<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdIdc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_idc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idc'], 'required'],
            [['isp', 'address'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'idc' => 'Idc',
            'isp' => 'Isp',
            'address' => 'Address',
        ];
    }
}
