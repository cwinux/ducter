<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdServiceCiJob extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service_ci_job';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pkg_version','ci_url', 'ci_id', 'app_id','svr_id'], 'required'],
            [['ci_id','app_id','svr_id','opr_uid'],'integer'],
            [['ci_type','ci_job','state','pkg_md5'],'string', 'max' => 32],
            [['source_xml'], 'string', 'max' => 64],
            [['source_branch','source_sha1','pkg_version','source_commit_id'],'string', 'max' => 128],
            [['ci_url'],'string','max'=>512],
            [['utime','ctime'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ci_id' => 'Ci Id',
            'app_id' => 'App Id',
            'svr_id' => 'Svr Id',
            'state' => 'State',
            'opr_uid' => 'Opr Uid',
            'ci_type' => 'Ci Type',
            'ci_job' => 'Ci Job',
            'pkg_md5' => 'Pkg Md5',
            'source_xml' => 'Source Xml',
            'source_branch' => 'Source Branch',
            'source_sha1' => 'Source Sha1',
            'pkg_version' => 'Pkg Version',
            'source_commit_id' => 'Source Commit Id',
            'ci_url' => 'Ci Url',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
        ];
    }
  
    public function getUser($uid) {
      $model = DcmdUser::findOne($uid);
      if($model) return $model->username;
      return "";
    }

}
