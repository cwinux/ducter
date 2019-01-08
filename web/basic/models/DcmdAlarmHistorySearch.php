<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdAlarmHistory;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdAlarmHistorySearch extends DcmdAlarmHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alarm_id', 'fault_id'], 'integer'],
            [['app_name', 'host_ip', 'vm_ip'], 'safe'],
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
        $query = DcmdAlarmHistory::find()->where($con_str)->orderBy('ctime');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'alarm_id' => $this->fault_id,
        ]);

        $query->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'host_ip', $this->host_ip])
            ->andFilterWhere(['like', 'vm_ip', $this->vm_ip])
            ->andFilterWhere(['like', 'fault_id', $this->fault_id]);

        return $dataProvider;
    }
}
