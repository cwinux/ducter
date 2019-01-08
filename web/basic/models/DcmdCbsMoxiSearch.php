<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdApp;

/**
 * DcmdAppSearch represents the model behind the search form about `app\models\DcmdApp`.
 */
class DcmdCbsMoxiSearch extends DcmdCbsMoxi
{
    /**
     * @inheritdoc
     */
    public $app_name;
    public function rules()
    {
        return [
            [['app_id'], 'integer'],
            [['app_name', 'moxi_ip', 'bucket','business','contacts','module'], 'safe'],
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
        ///应用足用户只可查看所在组的应用
        $app_con = "";
        $query = DcmdCbsMoxi::find()->where($app_con)->orderBy('bucket');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'app_id' => $this->app_id,
        ]);

        $query->andFilterWhere(['like', 'bucket', $this->bucket])
            ->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'business', $this->business])
            ->andFilterWhere(['like', 'contacts', $this->contacts])
            ->andFilterWhere(['like', 'module', $this->module])
            ->andFilterWhere(['like', 'moxi_ip', $this->moxi_ip]);

        return $dataProvider;
    }
}
