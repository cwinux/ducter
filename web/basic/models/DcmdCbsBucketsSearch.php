<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdServicePool;

/**
 * DcmdServicePoolSearch represents the model behind the search form about `app\models\DcmdServicePool`.
 */
class DcmdCbsBucketsSearch extends DcmdCbsBuckets
{
    /**
     * @inheritdoc
     */
    public $Ops_sec;
    public $Hit_ratio;
    public $RAM_Used;
    public $Item_Count;
    public $bussiness;
    public $contracts;
    public $Quto;
    public function rules()
    {
        return [
            [['uid', 'app_id'], 'integer'],
            [['bucket_name', 'contracts', 'bussiness', 'ctime', 'utime','type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        ///应用组用户只可查看所在组的应用
        $con = "";
        $query = DcmdCbsBuckets::find();
        $query->joinWith(['dcmdCbsBucketsStats']);
        $query->joinWith(['dcmdCbsBussiness']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        $dataProvider->setSort([
        'attributes' => [
            'Ops_sec' => [
                'asc' => ['dcmd_cbs_buckets_stats.Ops_sec' => SORT_ASC],
                'desc' => ['dcmd_cbs_buckets_stats.Ops_sec' => SORT_DESC],
                'label' => 'Ops Sec'
            ],
            'bucket_name' => [
                'asc' => ['dcmd_cbs_buckets.bucket_name' => SORT_ASC],
                'desc' => ['dcmd_cbs_buckets.bucket_name' => SORT_DESC],
                'label' => 'Bucket Name'
            ],
            'RAM_Used' => [
                'asc' => ['dcmd_cbs_buckets_stats.RAM_Used' => SORT_ASC],
                'desc' => ['dcmd_cbs_buckets_stats.RAM_Used' => SORT_DESC],
                'label' => 'Ram Used'
            ],
            'bussiness' => [
                'asc' => ['dcmd_cbs_bussiness.bussiness' => SORT_ASC],
                'desc' => ['dcmd_cbs_bussiness.bussiness' => SORT_DESC],
                'label' => 'Bussiness'
            ],
            'Item_Count' => [
                'asc' => ['dcmd_cbs_buckets_stats.Item_Count' => SORT_ASC],
                'desc' => ['dcmd_cbs_buckets_stats.Item_Count' => SORT_DESC],
                'label' => 'Item Count'
            ],
            'Quto' => [
                'asc' => ['dcmd_cbs_buckets_stats.Quto' => SORT_ASC],
                'desc' => ['dcmd_cbs_buckets_stats.Quto' => SORT_DESC],
                'label' => 'Quto'
            ],
        ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'app_id' => $this->app_id,
            'uid' => $this->uid,
        ]);

        $query->andFilterWhere(['like', 'bucket_name', $this->bucket_name])
              ->andFilterWhere(['like', 'dcmdCbsBucketsStats.Ops_sec', $this->Ops_sec])
              ->andFilterWhere(['like', 'dcmd_cbs_bussiness.bussiness', $this->bussiness])
              ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
