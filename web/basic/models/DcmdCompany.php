<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_user".
 *
 * @property integer $uid
 * @property string $username
 * @property string $passwd
 * @property integer $sa
 * @property integer $admin
 * @property integer $depart_id
 * @property string $tel
 * @property string $email
 * @property integer $state
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comp_name'], 'required'],
            [['opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['comp_name'], 'string', 'max' => 64],
            [['comment'], 'string', 'max' => 512],
            [['comp_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comp_name' => 'Comp Name',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
