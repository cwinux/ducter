<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_department".
 *
 * @property integer $depart_id
 * @property string $depart_name
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdDepartment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['depart_name',  'utime', 'ctime', 'opr_uid'], 'required'],
            [['utime', 'ctime'], 'safe'],
            [['opr_uid','comp_id'], 'integer'],
            [['depart_name'], 'string', 'max' => 64],
            [['comment'], 'string', 'max' => 512],
            [['depart_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'comment' => 'Comment',
            'comp_id' => 'Comp Id',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
  
    public function getCompany($comp_id)
    {
      $model = DcmdCompany::findOne($comp_id);
      if ($model) return $model->comp_name;
      else return "";
    }

}
