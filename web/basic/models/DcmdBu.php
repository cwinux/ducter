<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * This is the model class for table "dcmd_bu".
 *
 */
class DcmdBu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_bu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bu'], 'required'],
            [['bu'], 'string', 'max' => 128],
            [['bu'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'bu' => 'Bu',
        ];
    }
    public function getBuName($id) {
      $query = DcmdBu::findOne($id);
      if ($query) return $query->bu;
      return "";
    }

}
