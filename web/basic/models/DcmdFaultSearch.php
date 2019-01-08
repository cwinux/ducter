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
class DcmdFaultSearch extends DcmdFault
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_confirm', 'fault_id'], 'integer'],
            [['app_name', 'host_ip', 'host_fault', 'vm_uuid', 'vm_ip', 'vm_fault', 'start_time', 'confirm_time', 'erase_time', 'confirm_user', 'erase_user'], 'safe'],
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
        $query = DcmdFault::find()->where($con_str)->orderBy('fault_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'fault_id' => $this->fault_id,
        ]);

        $query->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'host_ip', $this->host_ip])
            ->andFilterWhere(['like', 'vm_ip', $this->vm_ip]);

        return $dataProvider;
    }
}
