<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdImage;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdResGlusterSearch extends DcmdResGluster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['res_order','is_public','comp_id','state','opr_uid','quota'], 'integer'],
            [['res_id','contact','cluster','res_name','volume','comment','ctime','stime'], 'safe'],
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
        $query = DcmdResGluster::find()->where($con_str)->orderBy('res_id');
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
            ->andFilterWhere(['like', 'volume', $this->volume])
            ->andFilterWhere(['like', 'cluster', $this->cluster])
            ->andFilterWhere(['like', 'comp_id', $this->comp_id])
            ->andFilterWhere(['like', 'contact', $this->contact]);
           
        return $dataProvider;
    }
}
