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
class DcmdAppRejectHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_app_reject_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_accept', 'accept_time', 'accept_username'], 'required'],
            [['app_id', 'svr_id', 'svr_pool_id', 'is_reupload','is_accept'], 'integer'],
            [['accept_time', 'upload_time'], 'safe'],
            [['upload_type', 'upload_host'], 'string', 'max' => 16],
            [['app_name','svr_name','svr_pool','upload_username','accept_username','version'], 'string', 'max' => 128],
            [['md5','passwd'], 'string', 'max' => 64],
            [['src_path','pkg_file'], 'string', 'max' => 256]
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
            'svr_pool_id' => 'Svr Pool Id',
            'is_reupload' => 'Is Reupload',
            'is_accept' => 'Is Accept',
            'accept_time' => 'Accept Time',
            'upload_time' => 'Upload Time',
            'upload_host' => 'Upload Host',
            'app_name' => 'App Name',
            'svr_name' => 'Svr Name',
            'svr_pool' => 'Svr Pool',
            'upload_username' => 'Upload Username',
            'accept_username' => 'Accept Username',
            'version' => 'Version',
            'md5' => 'Md5',
            'pkg_file' => 'Pkg File',
            'src_path' => 'Src Path',
            'passwd' => 'Passwd',
        ];
    }
}
