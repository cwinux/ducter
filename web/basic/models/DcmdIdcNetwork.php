<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdIdcNetwork extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_idc_network';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['segment', 'idc'], 'required'],
            [['vlan'], 'safe'],
            [['segment', 'type'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'segment' => 'Segment',
            'idc' => 'Idc',
            'type' => 'Type',
            'vlan' => 'Vlan',
        ];
    }

    public function getIdc($id)
    {
      $query = DcmdDcInfo::findOne($id);
      if ($query) return $query->dc;
      return "";
    }

    public function getNet()
    {
      $query = DcmdIdcNetwork::find()->asArray()->all();
      $data = array();
      if($query) {
        foreach($query as $k=>$v) {
          $data[$v["segment"]] = $v["vlan"];
        }
        return json_encode($data);
      }else {
        return ""; 
      }
    }

    public function getName()
    {
      $query = DcmdApp::find()->asArray()->all();
      $data = array();
      if($query) {
        foreach($query as $k=>$v) {
          $data[$v["app_id"]] = $v["app_name"];
        }
        return json_encode($data);
      }else {
        return "";
      }
    }
}
