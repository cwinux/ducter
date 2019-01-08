<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdApp;

/**
 * DcmdAppSearch represents the model behind the search form about `app\models\DcmdApp`.
 */
class DcmdCbsAppSearch extends DcmdCbsApp
{
    /**
     * @inheritdoc
     */
    public $dc;
    public function rules()
    {
        return [
            [['idc_id', 'state','server_num'], 'integer'],
            [['dc', 'app_name', 'user', 'passwd', 'description', 'utime', 'ctime','ram_total','ram_alocate','ram_used'], 'safe'],
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
        $query = DcmdCbsApp::find()->where($app_con)->orderBy('app_name');
        $query->joinWith(['dcmdDcInfo']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'app_id' => $this->app_id,
            'state' => $this->state,
        ]);

        $query->andFilterWhere(['like', 'app_name', $this->app_name])
              ->andFilterWhere(['like', 'dcmd_dc_info.dc', $this->dc])
              ->andFilterWhere(['like', 'app_id', $this->app_id])
            ->andFilterWhere(['like', 'state', $this->state]);

        return $dataProvider;
    }
}
