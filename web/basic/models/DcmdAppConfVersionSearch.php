<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdImage;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdAppConfVersionSearch extends DcmdAppConfVersion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'svr_id', 'svr_pool_id'], 'integer'],
            [['ctime','md5','version', 'username'], 'safe'],
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
        $query = DcmdAppConfVersion::find()->where($con_str)->orderBy('ctime');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'svr_pool_id' => $this->svr_pool_id,
            'app_id' => $this->app_id,
            'svr_id' => $this->svr_id,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
              ->andFilterWhere(['like', 'svr_pool_id', $this->svr_pool_id])
            ->andFilterWhere(['like', 'md5', $this->md5]);
           
        return $dataProvider;
    }
}
