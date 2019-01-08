<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdImage;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdServiceScriptSearch extends DcmdServiceScript
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opr_uid','svr_id','app_id','approve_uid'], 'integer'],
            [['script_md5','script','approve_time','ctime'], 'safe'],
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
        $query = DcmdServiceScript::find()->where($con_str)->orderBy('app_id');
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

        $query->andFilterWhere(['like', 'app_id', $this->app_id]);
           
        return $dataProvider;
    }
}
