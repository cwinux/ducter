<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdResColumn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_res_column';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['res_table'], 'required'],
            [['res_type'], 'string', 'max' => 32],
            [['res_table','colum_name','display_name'], 'string', 'max' => 64],
            [['ctime'], 'safe'],
            [['opr_uid','display_order','display_list'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'res_type' => 'Res Type',
            'res_table' => 'Res Table',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
            'colum_name' => 'Colum Name',
            'display_name' => 'Display Name',
            'display_order' => 'Display Order',
            'display_list' => 'Display List',
        ];
    }
}
