<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdPrivate;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdPrivateSearch extends DcmdPrivate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['vm_uuid', 'utime', 'cpu', 'memory', 'disk', 'borrow', 'state', 'app_name', 'business', 'module', 'contacts', 'bill_id', 'flavor_name', 'os', 'order_id', 'host_ip', 'vm_ip', 'vm_pub_ip'], 'safe'],
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
     /*   $con_str = "";
        if($unuse) {
          $q = DcmdServicePoolNode::find()->asArray()->all();
          $con_str = " ip not in(0";
          foreach($q as $item) $con_str .=",'".$item['ip']."'";
          $con_str .=")";
  
        }*/
        $query = DcmdPrivate::find();
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

        $query->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'host_ip', $this->host_ip])
            ->andFilterWhere(['like', 'os', $this->os])
            ->andFilterWhere(['like', 'flavor_name', $this->flavor_name])
            ->andFilterWhere(['like', 'vm_uuid', $this->vm_uuid])
            ->andFilterWhere(['like', 'vm_ip', $this->vm_ip])
            ->andFilterWhere(['like', 'vm_pub_ip', $this->vm_pub_ip])
            ->andFilterWhere(['like', 'business', $this->business])
            ->andFilterWhere(['like', 'module', $this->module])
            ->andFilterWhere(['like', 'contacts', $this->contacts])
            ->andFilterWhere(['=', 'state', $this->state]);

        return $dataProvider;
    }
}
