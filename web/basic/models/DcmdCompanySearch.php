<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdUser;

/**
 * DcmdUserSearch represents the model behind the search form about `app\models\DcmdUser`.
 */
class DcmdCompanySearch extends DcmdCompany
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comp_id','opr_uid'], 'integer'],
            [['comp_name', 'comment', 'utime', 'ctime'], 'safe'],
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
    public function search($params, $qstr=NULL)
    {
        $query = DcmdCompany::find()->andWhere($qstr)->orderBy("comp_id");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'comp_id' => $this->comp_id,
        ]);

        $query->andFilterWhere(['like', 'comp_name', $this->comp_name]);
        return $dataProvider;
    }
}
