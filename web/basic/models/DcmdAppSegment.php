<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdAppSegment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_app_segment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['network', 'app_id'], 'required'],
            [['network', 'app_id'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'network' => 'Network',
            'app_id' => 'App Id',
        ];
    }

    public function getSegment($id)
    {
      $query = DcmdIdcNetwork::findOne($id);
      if ($query) return $query->segment;
      return "";
    }

    public function getIdc($segment)
    {
      $query = DcmdIdcNetwork::findOne(['segment' => $segment]);
      if ($query) {
        $idc = DcmdIdc::findOne($query->idc);
        if($idc) return $idc->idc;
      }
      return "";
    }

    public function getType($segment)
    {
      $query = DcmdIdcNetwork::findOne(['segment' => $segment]);
      if ($query) return $query->type;
      return "";
    }

    public function getVlan($segment)
    {
      $query = DcmdIdcNetwork::findOne(['segment' => $segment]);
      if ($query) return $query->vlan;
      return "";
    }

    public function getAppName($id)
    {
      $query = DcmdApp::findOne($id);
      if ($query) return $query->app_name;
      return "";
    }
}
