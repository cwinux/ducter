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
class DcmdNodeGroupInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_node_group_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'required'],
            [['type', 'name'], 'string', 'max' => 128],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'name',
            'type' => 'Type',
        ];
    }
/*   public function getDepartment($depart_id) {
    $ret = DcmdDepartment::findOne($depart_id);
    if($ret) return $ret['depart_name'];
    else return "";
   }
   public function convert($num) {
     if($num == 1) return "是";
     else if($num == 0) return "否";
     return "";
  }*/
}
