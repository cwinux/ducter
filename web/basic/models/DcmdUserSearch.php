<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdUser;

/**
 * DcmdUserSearch represents the model behind the search form about `app\models\DcmdUser`.
 */
class DcmdUserSearch extends DcmdUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'sa', 'admin', 'depart_id', 'state', 'opr_uid'], 'integer'],
            [['username', 'passwd', 'tel', 'email', 'comment', 'utime', 'ctime'], 'safe'],
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
        if(Yii::$app->user->getIdentity()->sa != 1 && $qstr==NULL) {
          $qstr = "uid in (0";
          $show_user1 = DcmdUser::find()->andWhere(['opr_uid'=>Yii::$app->user->getId()])->asArray()->all();
          $show_user2 = DcmdUserGroup::find()->andWhere(['uid'=>Yii::$app->user->getId(),'is_leader'=>1])->asArray()->all();
          if($show_user2) {
            $gqstr = "gid in (0";
            foreach($show_user2 as $item) {
              $gqstr .= ",".$item['gid'];
            }
            $gqstr .= ")";
          }
          $uid_gid = DcmdUserGroup::find()->andWhere($gqstr)->asArray()->all();
          if($uid_gid) {
            foreach($uid_gid as $item) {
              $qstr .= ",".$item['uid'];
            }
          }
          if($show_user1) {
            foreach($show_user1 as $item) {
              $qstr .= ",".$item['uid'];
            }
          }
          $qstr .= ")";
        }
       //   $uid = Yii::$app->user->getId();
        //  $qstr = "";
        if($qstr) $query = DcmdUser::find()->andWhere($qstr)->orderBy("username");
       // }
        else $query = DcmdUser::find()->orderBy("username");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'uid' => $this->uid,
            'sa' => $this->sa,
            'admin' => $this->admin,
            'depart_id' => $this->depart_id,
            'state' => $this->state,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'passwd', $this->passwd])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'comment', $this->comment]);
        return $dataProvider;
    }
}
