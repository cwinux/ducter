<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_app".
 *
 * @property integer $app_id
 * @property string $app_name
 * @property string $app_alias
 * @property integer $sa_gid
 * @property integer $svr_gid
 * @property integer $depart_id
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdCbsNodeStats extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_cbs_node_stats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'ip', 'utime'], 'required'],
            [['app_id'], 'integer'],
            [['utime'], 'safe'],
            [['ip'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'app_id' => 'App ID',
            'ip' => 'Ip',
            'utime' => 'Utime', 
        ];
    }
}
