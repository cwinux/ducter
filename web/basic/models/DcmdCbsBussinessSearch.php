<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdService;

/**
 * DcmdServiceSearch represents the model behind the search form about `app\models\DcmdService`.
 */
class DcmdCbsBussinessSearch extends DcmdCbsBussiness
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bussiness', 'contracts'], 'safe'],
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
        ///应用组用户只可查看所在组的应用
        $con = "";
        $query = DcmdCbsBussiness::find()->where($con)->orderBy('bussiness');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'uid' => $this->uid,
        ]);

        $query->andFilterWhere(['like', 'bussiness', $this->bussiness])
            ->andFilterWhere(['like', 'contracts', $this->contracts]);

        return $dataProvider;
    }
}
