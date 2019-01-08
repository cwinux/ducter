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
class DcmdCbsBussiness extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_cbs_bussiness';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bussiness', 'contracts'], 'required'],
            [['bussiness'], 'string', 'max' => 128],
            [['contracts'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'bussiness' => 'Bussiness',
            'contracts' => 'Contracts',
        ];
    }
}
