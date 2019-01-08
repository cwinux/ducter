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
class DcmdCbsMoxi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_cbs_moxi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'moxi_ip'], 'required'],
            [['bucket','moxi_ip'], 'string', 'max' => 64],
            [['app_name','business','module','contacts'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_id' => 'App Id',
            'app_name' => 'App Name',
            'bucket' => 'Bucket',
            'moxi_ip' => 'Moxi Ip',
            'contacts' => 'Contacts',
            'module' => 'Module',
            'business' => 'Business',
        ];
    }
}
