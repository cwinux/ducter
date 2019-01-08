<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdServiceCi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service_ci';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'ci_url', 'svr_id'], 'required'],
            [['svr_id', 'app_id','opr_uid'], 'integer'],
            [['ci_type'], 'string', 'max' => 32],
            [['ci_user','ci_passwd'], 'string', 'max' => 64],
            [['ctime', 'utime'], 'safe'],
            [['ci_jenkins_url','comment', 'ci_url'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_id' => 'App Id',
            'ci_user' => 'Ci User',
            'ci_passwd' => 'Ci Passwd',
            'svr_id' => 'Svr Id',
            'opr_uid' => 'Opr Uid',
            'ci_type' => 'Ci Type',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'comment' => 'Comment',
            'ci_url' => 'Ci Url',
            'ci_jenkins_url' => 'Ci Jenkins Url',
        ];
    }
}
