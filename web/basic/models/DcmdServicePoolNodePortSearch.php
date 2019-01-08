<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdService;

/**
 * DcmdServiceSearch represents the model behind the search form about `app\models\DcmdService`.
 */
class DcmdServicePoolNodePortSearch extends DcmdServicePoolNodePort
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'port_name', 'port','svr_pool_id','opr_uid'], 'integer'],
            [['ctime'], 'safe'],
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
        $port = '';
        $query = DcmdServicePoolNodePort::find()->where($port)->orderBy('port');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params))) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
              ->andFilterWhere(['like', 'svr_pool_id', $this->svr_pool_id]);

        return $dataProvider;
    }
}
