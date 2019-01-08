<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_app".
 *
 * @property integer $app_id
 * @property string $app_name
 * @property string $app_alias
 * @property integer $sa_gid
 * @property integer $svr_gid
 * @property integer $depart_id
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdCbsBucketsStats extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_cbs_buckets_stats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bucket_app_id', 'bid', 'name', 'utime', 'Ops_sec'], 'required'],
            [['bucket_app_id', 'bid'], 'integer'],
            [['utime'], 'safe'],
            [['name','Quto','Ops_sec', 'Hit_ratio', 'RAM_Used', 'Item_Count'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'bucket_app_id' => 'App ID',
            'Quto' => 'Quto',
            'bid' => 'Bid',
            'name' => 'Name',
            'utime' => 'Utime', 
            'Item_Count' => 'Item Count',
            'Ops_sec' => 'Ops Sec',
            'Hit_ratio' => 'Hit Ratio',
            'RAM_Used' => 'RAM Used',
            'Item_Count' => 'Item Count',
        ];
    }
}
