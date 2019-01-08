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
class DcmdServicePoolPort extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service_pool_port';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svr_id', 'port_name'], 'required'],
            [['svr_pool_id', 'svr_id', 'port', 'mapped_port','opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['port_name'], 'string', 'max' => 32]
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
            'svr_pool_id' => 'Svr Pool Id',
            'svr_id' => 'Svr Id',
            'port' => 'Port',
            'mapped_port' => 'Mapped Port',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
