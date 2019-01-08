<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdImage;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdResColumnSearch extends DcmdResColumn
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opr_uid','display_order','display_list','id'], 'integer'],
            [['res_table','res_type','colum_name','display_name'], 'safe'],
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
        $query = DcmdResColumn::find()->where($con_str)->orderBy('res_table');
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

        $query->andFilterWhere(['like', 'res_table', $this->res_table])
            ->andFilterWhere(['like', 'res_type', $this->res_type])
            ->andFilterWhere(['like', 'colum_name', $this->colum_name])
            ->andFilterWhere(['like', 'display_name', $this->display_name]);
           
        return $dataProvider;
    }
}
