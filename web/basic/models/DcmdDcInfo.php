<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * This is the model class for table "dcmd_node".
 *
 */
class DcmdDcInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_dc_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country', 'area', 'dc'], 'required'],
            [['country', 'area', 'dc'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dc_id' => 'Dc Id',
            'country' => 'Country',
            'area' => 'Area',
            'dc' => 'Dc',
        ];
    }
}
