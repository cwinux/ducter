<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdService;

/**
 * DcmdServiceSearch represents the model behind the search form about `app\models\DcmdService`.
 */
class DcmdAppRejectHistorySearch extends DcmdAppRejectHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'id','svr_id', 'svr_pool_id', 'is_reupload','is_accept'], 'integer'],
            [['accept_time', 'upload_time','upload_type', 'upload_host','app_name','svr_name','svr_pool','upload_username','accept_username','version','md5','src_path','pkg_file'], 'safe'],
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
        ///应用组用户只可查看所在组的应用
        $svr_con = "";
#        if(Yii::$app->user->getIdentity()->admin != 1)
#        {
#          $app_con = "svr_gid in (0";
#          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
#          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
#          $app_con .= ")";
#          $query = DcmdApp::find()->where($app_con)->asArray()->all();
#          $svr_con = "dcmd_app.app_id in (0";
#          foreach($query as $item) $svr_con .= ",".$item['app_id'];
#          $svr_con .=")";
#        }
        $query = DcmdAppRejectHistory::find()->where($svr_con)->orderBy('upload_time');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'svr_id' => $this->svr_id,
            'app_id' => $this->app_id,
            'id' => $this->id,
            'is_accept' => $this->is_accept,
        ]);

        $query->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'upload_username', $this->upload_username]);

        return $dataProvider;
    }
}
