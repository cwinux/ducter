<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdBusiness;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdBusinessSearch extends DcmdBusiness
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['product', 'bu'], 'safe'],
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
     /*   $con_str = "";
        if($unuse) {
          $q = DcmdServicePoolNode::find()->asArray()->all();
          $con_str = " ip not in(0";
          foreach($q as $item) $con_str .=",'".$item['ip']."'";
          $con_str .=")";
  
        }*/
        $query = DcmdBusiness::find()->orderBy("bu");
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'product' => $this->product,
            'bu' => $this->bu,
        ]);

        $query->andFilterWhere(['like', 'product', $this->product])
            ->andFilterWhere(['like', 'bu', $this->bu]);

        return $dataProvider;
    }
}
