<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 */
class DcmdResMongo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_res_mongo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['res_name'], 'required'],
            [['res_id','contact'], 'string', 'max' => 32],
            [['port','cluster','backup_cluster'], 'string', 'max' => 64],
            [['instance'], 'string', 'max' => 128],
            [['res_name'], 'string', 'max' => 255],
            [['comment'], 'string', 'max' => 512],
            [['instance_num','backup_port','backup_instance_num','res_order','is_public','comp_id','state','opr_uid'], 'integer'], 
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
            'backup_cluster' => 'Backup Cluster',
            'instance' => 'Instance',
            'port' => 'Port',
            'instance_num' => 'Instance Num',
            'backup_port' => 'Backup Port',
            'backup_instance_num' => 'Backup Instance Num',
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
