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
class DcmdAppPkgVersion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_app_pkg_version';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id'], 'required'],
            [['app_id', 'svr_id'], 'integer'],
            [['ctime'], 'safe'],
            [['md5'], 'string', 'max' => 64],
            [['passwd','version','username'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_id' => 'App Id',
            'svr_id' => 'Svr Id',
            'ctime' => 'Ctime',
            'version' => 'Version',
            'md5' => 'Md5',
            'username' => 'Username',
            'passwd' => 'Passwd',
        ];
    }
}
