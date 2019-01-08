<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdNodeGroup;

/**
 * DcmdNodeGroupSearch represents the model behind the search form about `app\models\DcmdNodeGroup`.
 */
class DcmdNodeGroupSearch extends DcmdNodeGroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ngroup_id', 'gid', 'opr_uid'], 'integer'],
            [['ngroup_name', 'ngroup_alias','gid', 'utime', 'ctime', 'opr_uid', 'comment'], 'safe'],
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
        $ngroup_id = "";
        if(Yii::$app->user->getIdentity()->sa != 1)
        {
          $gid = "select gid from dcmd_user_group where uid =".Yii::$app->user->getId();
          $sql = "select ngroup_id from dcmd_node_group where gid in (".$gid.")";
          $ngroup_sql = DcmdNodeGroup::findBySql($sql)->asArray()->all();
          $ngroup_id = "ngroup_id in (0";
          if($ngroup_sql) foreach($ngroup_sql as $item) $ngroup_id .= ",".$item['ngroup_id'];
          $ngroup_id .= ")";
        }
//        $query = DcmdNodeGroup::findBySql($sql)->orderBy('ngroup_name');
        $query = DcmdNodeGroup::find()->where($ngroup_id)->orderBy("ngroup_name");
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'ngroup_id' => $this->ngroup_id,
            'gid' => $this->gid,
        ]);
        $query->andFilterWhere(['like', 'ngroup_name', $this->ngroup_name])
            ->andFilterWhere(['like', 'ngroup_alias', $this->ngroup_alias]);
        return $dataProvider;
    }
}
