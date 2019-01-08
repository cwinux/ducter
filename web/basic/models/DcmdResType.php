<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdResType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_res_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['res_type', 'res_table'], 'required'],
            [['res_type'], 'string', 'max' => 32],
            [['res_table'], 'string', 'max' => 64],
            [['ctime'], 'safe'],
            [['opr_uid'], 'integer'],
            [['comment'], 'string', 'max' => 512]
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
            'comment' => 'Comment',
        ];
    }
}
