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
class DcmdDcInfoSearch extends DcmdDcInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dc_id'], 'integer'],
            [['country', 'area', 'dc'], 'safe'],
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
        $query = DcmdDcInfo::find()->where($con_str)->orderBy('country');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
           
        }
        $query->andFilterWhere([
            'dc_id' => $this->dc_id,
        ]);

        $query->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'dc', $this->dc]);

        return $dataProvider;
    }
}
