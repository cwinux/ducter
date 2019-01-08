<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdVmOplog;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdVmOrderreplyHistorySearch extends DcmdVmOrderreplyHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bill_id', 'action', 'result', 'callback' ], 'safe'],
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
    public function search($params, $unuse=false)
    {
        $con_str = "";
        $query = DcmdVmOrderreplyHistory::find()->where($con_str)->orderBy('id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'bill_id', $this->bill_id])
              ->andFilterWhere(['like', 'action', $this->action])
              ->andFilterWhere(['like', 'result', $this->result])
              ->andFilterWhere(['like', 'callback', $this->callback]);

        return $dataProvider;
    }
}
