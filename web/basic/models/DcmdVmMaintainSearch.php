<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdBusiness;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdVmMaintainSearch extends DcmdVmMaintain
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['dcmd_info', 'online_info'], 'safe'],
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
        $query = DcmdVmMaintain::find()->orderBy("dcmd_info");
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'dcmd_info' => $this->dcmd_info,
            'online_info' => $this->online_info,
        ]);

        $query->andFilterWhere(['like', 'dcmd_info', $this->dcmd_info])
            ->andFilterWhere(['like', 'online_info', $this->online_info]);

        return $dataProvider;
    }
}
