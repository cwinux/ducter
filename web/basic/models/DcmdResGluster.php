<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdResGluster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_res_gluster';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['res_name'], 'required'],
            [['res_id','contact'], 'string', 'max' => 32],
            [['cluster'], 'string', 'max' => 64],
            [['volume'], 'string', 'max' => 128],
            [['res_name'], 'string', 'max' => 255],
            [['comment'], 'string', 'max' => 512],
            [['res_order','is_public','comp_id','state','opr_uid','quota'], 'integer'], 
            [['ctime','stime'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'res_id' => 'Res Id',
            'contact' => 'Contact',
            'cluster' => 'Cluster',
            'volume' => 'Volume',
            'quota' => 'Quota',
            'comment' => 'Comment',
            'res_order' => 'Res Order',
            'is_public' => 'Is Public',
            'comp_id' => 'Comp Id',
            'state' => 'State',
            'opr_uid' => 'Opr Uid',
            'ctime' => 'Ctime',
            'stime' => 'Stime',
        ];
    }
   public function getComp($comp_id) {
     $ret = DcmdCompany::findOne($comp_id);
     if($ret) return $ret['comp_name'];
     else return "";
   }
}
