<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdImage;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdResMysqlSearch extends DcmdResMysql
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['res_order','is_public','comp_id','state','opr_uid'], 'integer'],
            [['res_id','contact','port','db','res_name','server','comment','ctime','stime'], 'safe'],
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
        $query = DcmdResMysql::find()->where($con_str)->orderBy('res_id');
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
            ->andFilterWhere(['like', 'server', $this->server])
            ->andFilterWhere(['like', 'port', $this->port])
            ->andFilterWhere(['like', 'db', $this->db])
            ->andFilterWhere(['like', 'comp_id', $this->comp_id])
            ->andFilterWhere(['like', 'contact', $this->contact]);
           
        return $dataProvider;
    }
}
