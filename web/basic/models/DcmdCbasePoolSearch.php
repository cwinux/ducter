<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdServicePool;

/**
 * DcmdServicePoolSearch represents the model behind the search form about `app\models\DcmdServicePool`.
 */
class DcmdCbasePoolSearch extends DcmdCbasePool
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id'], 'integer'],
            [['pool_name'], 'safe'],
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
        $svr_pool_con = "";
        $query = DcmdCbasePool::find()->where($svr_pool_con)->orderBy('pool_name');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'app_id' => $this->app_id,
        ]);

        $query->andFilterWhere(['like', 'pool_name', $this->pool_name]);

        return $dataProvider;
    }
}
