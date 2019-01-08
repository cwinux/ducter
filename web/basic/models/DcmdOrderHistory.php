<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * This is the model class for table "dcmd_node".
 *
 */
class DcmdOrderHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_vm_order_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bill_id', 'action', 'args', 'callback', 'state', 'apply_user', 'apply_time', 'ctime', 'utime'], 'required'],
            [['state'], 'integer'],
            [['apply_time', 'utime', 'ctime', 'args'], 'safe'],
            [['action'], 'string', 'max' => 16],
            [['bill_id'], 'string', 'max' => 128],
            [['apply_user'], 'string', 'max' => 32],
            [['callback'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order Id',
            'bill_id' => 'Bill Id',
            'action' => 'Action',
            'args' => 'Args',
            'callback' => 'Callback',
            'state' => 'State',
            'errmsg' => 'Errmsg',
            'apply_user' => 'Apply User',
            'apply_time' => 'Apply Time',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
        ];
    }
}
