<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdImage;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdServiceScriptApplySearch extends DcmdServiceScriptApply
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opr_uid','svr_id','app_id','is_apply'], 'integer'],
            [['script_md5','script'], 'safe'],
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
        $con_str = "";
      //  if(Yii::$app->user->getIdentity()->sa != 1)
      //  {
          $app_con = "svr_gid in (0";
          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
          $app_con .= " or sa_gid in (0";
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
          $query = DcmdApp::find()->where($app_con)->asArray()->all();
          $con_str = "app_id in (0";
          foreach($query as $item) $con_str .= ",".$item['app_id'];
          $con_str .=")";
      //  }
        $query = DcmdServiceScriptApply::find()->where($con_str)->orderBy('app_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'is_apply' => $this->is_apply,
        ]);

        $query->andFilterWhere(['like', 'app_id', $this->app_id]);
           
        return $dataProvider;
    }
}
