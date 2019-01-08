<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdUser;

/**
 * DcmdUserSearch represents the model behind the search form about `app\models\DcmdUser`.
 */
class DcmdAppNodeGroupSearch extends DcmdAppNodeGroup
{
    /**
     * @inheritdoc
     */
    public $app_name;
    public $ngroup_name;
    public function rules()
    {
        return [
            [['ngroup_id', 'opr_uid','app_id'], 'integer'],
            [['app_name','ctime','ngroup_name'], 'safe'],
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
    public function search($params, $qstr=NULL)
    {
        $svr_con = "";
        if(Yii::$app->user->getIdentity()->sa != 1)
        {
          $app_con = "svr_gid in (0";
          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
          $app_con .= " or sa_gid in (0";
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
          $query = DcmdApp::find()->where($app_con)->asArray()->all();
          $svr_con = "dcmd_app.app_id in (0";
          foreach($query as $item) $svr_con .= ",".$item['app_id'];
          $svr_con .=")";
        }
        $query = DcmdAppNodeGroup::find()->andWhere($svr_con)->orderBy("app_id");
        $query->joinWith(['dcmdApp']);
        $query->joinWith(['dcmdNodeGroup']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'dcmd_app.app_id' => $this->app_id,
//            'ngroup_id' => $this->ngroup_id,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'ngroup_id', $this->ngroup_id])
              ->andFilterWhere(['like', 'dcmd_node_group.ngroup_name', $this->ngroup_name])
              ->andFilterWhere(['like', 'dcmd_app.app_name', $this->app_name]);
        return $dataProvider;
    }
}
