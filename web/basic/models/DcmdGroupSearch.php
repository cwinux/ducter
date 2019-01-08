<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdGroup;

/**
 * DcmdGroupSearch represents the model behind the search form about `app\models\DcmdGroup`.
 */
class DcmdGroupSearch extends DcmdGroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'gtype', 'opr_uid','comp_id'], 'integer'],
            [['gname', 'comment', 'utime', 'ctime'], 'safe'],
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
    public function search($params, $gids=NULL)
    {  
        if(Yii::$app->user->getIdentity()->sa != 1 && $gids==NULL) {
          $gids = "gid in (0";
          $user_group = DcmdUserGroup::find()->andWhere(['uid'=>Yii::$app->user->getId(),'is_leader'=>1])->asArray()->all();
          if($user_group) {
            foreach($user_group as $item) $gids .= ",".$item['gid'];
          }
          $gids .= ")";
        }
        if ($gids) $query  = DcmdGroup::find()->andWhere($gids)->orderBy("gname");
        else $query = DcmdGroup::find()->orderBy("gname");
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'gid' => $this->gid,
            'gtype' => $this->gtype,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'gname', $this->gname])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
