<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdImage;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdResSlbSearch extends DcmdResSlb
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['res_order','is_public','comp_id','state','opr_uid'], 'integer'],
            [['res_id','contact','I2','I3','I4','I5','res_name','name','comment','ctime','stime'], 'safe'],
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
        $con_str = "";
        $query = DcmdResSlb::find()->where($con_str)->orderBy('res_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'is_public' => $this->is_public,
            'state' => $this->state,
        ]);

        $query->andFilterWhere(['like', 'res_name', $this->res_name])
            ->andFilterWhere(['like', 'res_id', $this->res_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'comp_id', $this->comp_id])
            ->andFilterWhere(['like', 'contact', $this->contact]);
           
        return $dataProvider;
    }
}
