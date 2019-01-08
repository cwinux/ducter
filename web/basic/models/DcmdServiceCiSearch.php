<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdImage;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdServiceCiSearch extends DcmdServiceCi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svr_id', 'ci_id', 'app_id','opr_uid'], 'integer'],
            [['ci_type','ci_url'], 'safe'],
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
        $query = DcmdServiceCi::find()->where($con_str)->orderBy('ctime');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'ci_id' => $this->ci_id,
            'app_id' => $this->app_id,
            'svr_id' => $this->svr_id,
        ]);

        $query->andFilterWhere(['like', 'ci_type', $this->ci_type])
            ->andFilterWhere(['like', 'ci_url', $this->ci_url]);
           
        return $dataProvider;
    }
}
