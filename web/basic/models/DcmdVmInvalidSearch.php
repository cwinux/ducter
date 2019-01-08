<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdPrivate;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdVmInvalidSearch extends DcmdVmInvalid
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['vm_uuid', 'utime', 'cpu', 'memory', 'disk', 'state', 'app_name', 'flavor_name', 'image_name', 'host_ip', 'host_name'], 'safe'],
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
        $query = DcmdVmInvalid::find()->orderBy("host_ip");
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
            ->andFilterWhere(['like', 'vm_uuid', $this->vm_uuid]);

        return $dataProvider;
    }
}
