<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdTask;

/**
 * DcmdTaskSearch represents the model behind the search form about `app\models\DcmdTask`.
 */
class DcmdTaskSearch extends DcmdTask
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'depend_task_id', 'app_id', 'svr_id', 'update_env', 'update_tag', 'state', 'freeze', 'valid', 'pause', 'concurrent_rate', 'timeout', 'auto', 'process', 'opr_uid'], 'integer'],
            [['task_name', 'task_cmd', 'depend_task_name', 'app_name', 'svr_name', 'svr_path', 'tag', 'err_msg', 'task_arg', 'comment', 'utime', 'ctime','tag_md5','tag_passwd'], 'safe'],
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
        ///非admin用户，可查看所属业务组内的任务
        $svr_con = "";
        if(Yii::$app->user->getIdentity()->admin != 1)
        {
          $app_con = "svr_gid in (0";
          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
          $query = DcmdApp::find()->where($app_con)->asArray()->all();
          $app_con = "app_id in (0";
          foreach($query as $item) $app_con .=",".$item['app_id'];
          $app_con .=")";
          $query = DcmdService::find()->where($app_con)->asArray()->all();
          $svr_con = "svr_id in (0";
          foreach($query as $item) $svr_con .=",".$item['svr_id'];
          $svr_con .=")";
        }else { ///管理员只能查看同一系统组的产品
          $app_con = "sa_gid in (0";
          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
          $query = DcmdApp::find()->where($app_con)->asArray()->all();
          $app_con = "app_id in (0";
          foreach($query as $item) $app_con .=",".$item['app_id'];
          $app_con .=")";
          $query = DcmdService::find()->where($app_con)->asArray()->all();
          $svr_con = "svr_id in (0";
          foreach($query as $item) $svr_con .=",".$item['svr_id'];
          $svr_con .=")";
        }       
        $query = DcmdTask::find()->where($svr_con)->orderBy('task_id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'task_id' => $this->task_id,
            'depend_task_id' => $this->depend_task_id,
            'app_id' => $this->app_id,
            'svr_id' => $this->svr_id,
            'update_env' => $this->update_env,
            'update_tag' => $this->update_tag,
            'state' => $this->state,
            'freeze' => $this->freeze,
            'valid' => $this->valid,
            'pause' => $this->pause,
            'concurrent_rate' => $this->concurrent_rate,
            'timeout' => $this->timeout,
            'auto' => $this->auto,
            'process' => $this->process,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'task_name', $this->task_name])
            ->andFilterWhere(['like', 'task_cmd', $this->task_cmd])
            ->andFilterWhere(['like', 'depend_task_name', $this->depend_task_name])
            ->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'svr_name', $this->svr_name])
            ->andFilterWhere(['like', 'svr_path', $this->svr_path])
            ->andFilterWhere(['like', 'tag', $this->tag])
            ->andFilterWhere(['like', 'err_msg', $this->err_msg])
            ->andFilterWhere(['like', 'task_arg', $this->task_arg])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
