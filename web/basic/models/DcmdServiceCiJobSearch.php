<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdImage;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdServiceCiJobSearch extends DcmdServiceCiJob
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id','svr_id','ci_id'], 'integer'],
            [['ci_type', 'ci_url', 'ci_job'], 'safe'],
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
        $query = DcmdServiceCiJob::find()->where($con_str)->orderBy('ctime DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'app_id' => $this->app_id,
            'svr_id' => $this->svr_id,
        ]);

        $query->andFilterWhere(['like', 'ci_url', $this->ci_url])
            ->andFilterWhere(['like', 'ci_job', $this->ci_job])
            ->andFilterWhere(['like', 'state', $this->state]);
           
        return $dataProvider;
    }
}
