<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdNode;
use app\models\DcmdNodeGroup;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdNodeSearch extends DcmdNode
{
    /**
     * @inheritdoc
     */
    public $ngroup_name;
    public function rules()
    {
        return [
            [['nid', 'ngroup_id', 'opr_uid'], 'integer'],
            [['ip', 'host', 'sid', 'ngroup_name', 'did', 'bend_ip', 'public_ip', 'comment', 'utime', 'ctime'], 'safe'],
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
        if($unuse) {
          $q = DcmdServicePoolNode::find()->asArray()->all();
          $con_str = " ip not in(0";
          foreach($q as $item) $con_str .=",'".$item['ip']."'";
          $con_str .=")";
  
        }
        if(Yii::$app->user->getIdentity()->sa != 1)
        {
          $gid = "select gid from dcmd_user_group where uid =".Yii::$app->user->getId();
          $gid = "select gid from dcmd_user_group where uid =".Yii::$app->user->getId();
//          $sql = "select ngroup_id from dcmd_node_group where ngroup_id in(select ngroup_id from dcmd_app_node_group where app_id in (select app_id from dcmd_app where sa_gid in (".$gid.") or svr_gid in (".$gid."))) order by ngroup_name";
          $sql = "select ngroup_id from dcmd_node_group where gid in(select gid from dcmd_user_group where uid =".Yii::$app->user->getId().") order by ngroup_name";
          $ngroup = DcmdNodeGroup::findBySql($sql)->asArray()->all();
          if(!$unuse) {
            $con_str = "dcmd_node.ngroup_id in(0";
            foreach($ngroup as $item) $con_str .=",'".$item['ngroup_id']."'";
            $con_str .=")";
          }
        }
        $query = DcmdNode::find()->where($con_str)->orderBy('ip');
        $query->joinWith(['dcmd_node_group']);
        $dataProvider = new ActiveDataProvider([
        /*    'ngroup_name' => [
                'asc' => ['dcmd_node_group.ngroup_name' => SORT_ASC],
                'desc' => ['dcmd_node_group.ngroup_name' => SORT_DESC],
                'label' => 'Ngroup Name'
            ],*/
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'nid' => $this->nid,
            'dcmd_node.ngroup_id' => $this->ngroup_id,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'host', $this->host])
            ->andFilterWhere(['like', 'sid', $this->sid])
            ->andFilterWhere(['like', 'did', $this->did])
            ->andFilterWhere(['like', 'bend_ip', $this->bend_ip])
            ->andFilterWhere(['like', 'dcmd_node_group.ngroup_name', $this->ngroup_name])
            ->andFilterWhere(['like', 'public_ip', $this->public_ip]);
           
        return $dataProvider;
    }
}
