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
class DcmdWorkDiarySearch extends DcmdWorkDiary
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['diary_id', 'cost_time'], 'integer'],
            [['type', 'description', 'process', 'date', 'jira_add'], 'safe'],
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
        $query = DcmdWorkDiary::find()->where($con_str)->orderBy(['diary_id'=>SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'diary_id' => $this->diary_id,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'jira_add', $this->jira_add])
            ->andFilterWhere(['like', 'process', $this->process]);

        return $dataProvider;
    }
}
