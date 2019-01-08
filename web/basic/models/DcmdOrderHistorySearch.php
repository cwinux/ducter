<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdNode;
use app\models\DcmdAudit;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdOrderHistorySearch extends DcmdOrderHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'state'], 'integer'],
            [['bill_id', 'action', 'args', 'callback', 'errmsg', 'apply_user', 'apply_time', 'ctime', 'utime'], 'safe'],
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
        $query = DcmdOrderHistory::find()->where($con_str)->orderBy('apply_time');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'order_id' => $this->order_id,
            'ctime' => $this->ctime,
            'utime' => $this->utime,
        ]);

        $query->andFilterWhere(['like', 'bill_id', $this->bill_id])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'args', $this->args])
            ->andFilterWhere(['like', 'callback', $this->callback])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'errmsg', $this->errmsg])
            ->andFilterWhere(['like', 'apply_user', $this->apply_user])
            ->andFilterWhere(['like', 'apply_time', $this->apply_time]);

        return $dataProvider;
    }
}
