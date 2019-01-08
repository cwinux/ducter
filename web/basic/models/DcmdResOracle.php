<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdResOracle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_res_oracle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['res_name'], 'required'],
            [['res_id','contact'], 'string', 'max' => 32],
            [['cluster','cluster_ip','schema_name'], 'string', 'max' => 64],
            [['res_name'], 'string', 'max' => 255],
            [['comment'], 'string', 'max' => 512],
            [['res_order','is_public','comp_id','state','opr_uid'], 'integer'], 
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
            'cluster_ip' => 'Cluster Ip',
            'schema_name' => 'Schema Name',
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
