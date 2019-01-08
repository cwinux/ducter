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
class DcmdServicePoolNodePort extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service_pool_node_port';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'port'], 'required'],
            [['port', 'svr_id', 'svr_pool_id', 'opr_uid'], 'integer'],
            [['ctime'], 'safe'],
            [['ip'],'string','max' => 16],
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
            'port' => 'Port',
            'svr_pool_id' => 'Svr Pool Id',
            'port' => 'Port',
            'ip' => 'Ip',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
