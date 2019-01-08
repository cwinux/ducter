<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * This is the model class for table "dcmd_node".
 *
 */
class DcmdVmOrderReply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_vm_order_reply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'bill_id', 'action', 'result', 'callback', 'state', 'count', 'ctime', 'utime'], 'required'],
            [['state', 'order_id', 'count'], 'integer'],
            [['utime', 'ctime', 'result'], 'safe'],
            [['action'], 'string', 'max' => 16],
            [['bill_id'], 'string', 'max' => 128],
            [['errmsg'], 'string', 'max' => 256],
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
            'result' => 'Result',
            'callback' => 'Callback',
            'state' => 'State',
            'errmsg' => 'Errmsg',
            'count' => 'Count',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
        ];
    }
}
