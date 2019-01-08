<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdApp;

/**
 * DcmdAppSearch represents the model behind the search form about `app\models\DcmdApp`.
 */
class DcmdAppResSearch extends DcmdAppRes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id','svr_id','svr_pool_id','is_own','opr_uid'], 'integer'],
            [['ctime','res_type','res_id','res_name'], 'safe'],
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
        ///应用足用户只可查看所在组的应用
        $app_con = "";
#        if(Yii::$app->user->getIdentity()->admin != 1)
#        {
#          $app_con = "svr_gid in (0";
#          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
#          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
#          $app_con .= ")";
#        }
        $query = DcmdAppRes::find()->where($app_con)->orderBy('res_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'app_id' => $this->app_id,
            'svr_id' => $this->svr_id,
            'svr_pool_id' => $this->svr_pool_id,
        ]);

        $query->andFilterWhere(['like', 'res_name', $this->res_name])
            ->andFilterWhere(['like', 'res_id', $this->res_id])
            ->andFilterWhere(['like', 'res_type', $this->res_type]);

        return $dataProvider;
    }
}
