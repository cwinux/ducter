<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdApp;

/**
 * DcmdAppSearch represents the model behind the search form about `app\models\DcmdApp`.
 */
class DcmdCbsNodeStatusSearch extends DcmdCbsAppNode
{
    /**
     * @inheritdoc
     */
    public $app_name;
    public function rules()
    {
        return [
            [['app_id', 'nid', 'state'], 'integer'],
            [['app_name', 'ip', 'ctime','status'], 'safe'],
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
        $app_con = ['and',['!=','status','Up'],['=','dcmd_cbs_app.state',1]];
        $query = DcmdCbsAppNode::find()->where($app_con)->orderBy('ip');
        $query->joinWith(['dcmdCbsApp']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ctime' => $this->ctime,
            'dcmd_cbs_app.state' => 1,
        ]);

        $query->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'dcmd_cbs_app.app_name', $this->app_name])
            ->andFilterWhere(['like', 'dcmd_cbs_app.state', 1])
            ->andFilterWhere(['like', 'dcmd_cbs_app.app_id', $this->app_id])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
