<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_service".
 *
 * @property integer $svr_id
 * @property string $svr_name
 * @property string $svr_alias
 * @property string $svr_path
 * @property string $run_user
 * @property integer $app_id
 * @property integer $owner
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdServicePort extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service_port';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['port_name'], 'required'],
            [['svr_port_id', 'svr_id', 'def_port', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['port_name'], 'string', 'max' => 32],
            [['protocol'], 'string', 'max' => 16],
            [['comment'], 'string', 'max' => 512],
            [['svr_id', 'port_name'], 'unique', 'targetAttribute' => ['svr_id', 'port_name'], 'message' => 'The combination of Svr Name and Port has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'svr_id' => 'Svr ID',
            'port_name' => 'Port Name',
            'svr_port_id' => 'Svr Port Id',
            'svr_id' => 'Svr Id',
            'def_port' => 'Def Port',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
            'protocol' => 'Protocol',
        ];
    }
}
