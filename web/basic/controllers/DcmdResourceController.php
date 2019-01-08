<?php

namespace app\controllers;

use Yii;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\DcmdService;
use app\models\DcmdServicePool;
use app\models\DcmdServiceSearch;
use app\models\DcmdServicePoolSearch;
use app\models\DcmdApp;
use app\models\DcmdUserGroup;
use app\models\DcmdServiceArchDiagramSearch;
use app\models\DcmdServiceArchDiagram;
use app\models\DcmdServicePoolNode;
use app\models\DcmdResType;
use app\models\DcmdResTypeSearch;
use app\models\DcmdResDns;
use app\models\DcmdResDnsSearch;
use app\models\DcmdResLvs;
use app\models\DcmdResLvsSearch;
use app\models\DcmdResSlb;
use app\models\DcmdResSlbSearch;
use app\models\DcmdResCbase;
use app\models\DcmdResCbaseSearch;
use app\models\DcmdResRedis;
use app\models\DcmdResRedisSearch;
use app\models\DcmdResMq;
use app\models\DcmdCompany;
use app\models\DcmdResMqSearch;
use app\models\DcmdResGluster;
use app\models\DcmdResGlusterSearch;
use app\models\DcmdResMysql;
use app\models\DcmdResMysqlSearch;
use app\models\DcmdResMongo;
use app\models\DcmdResMongoSearch;
use app\models\DcmdResOracle;
use app\models\DcmdResOracleSearch;
use app\models\DcmdResMcluster;
use app\models\DcmdResMclusterSearch;
use app\models\DcmdAppRes;
use app\models\DcmdAppResSearch;
use app\models\DcmdResColumn;
use app\models\DcmdOprLog;
use app\models\DcmdTaskTemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
/**
 * DcmdServiceController implements the CRUD actions for DcmdService model.
 */
class DcmdResourceController extends Controller
{
    public $enableCsrfValidation = false;
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all DcmdService models.
     * @return mixed
     */
    public function actionIndex()
    {
        ///应用足用户只可查看所在组的应用
        $app_con = "";
        if(Yii::$app->user->getIdentity()->admin != 1)
        {
          $app_con = "svr_gid in (0";
          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
        }
//        $query = DcmdResDns::find()->where($app_con)->orderBy('res_name')->asArray()->all();
//        $app = array();
//        foreach($query as $item) $app[$item['app_id']] = $item['app_name'];


        $mqlModel = new DcmdResMysqlSearch;
        $mqlProvider = $mqlModel->search(Yii::$app->request->queryParams);

        $mngModel = new DcmdResMongoSearch;
        $mngProvider = $mngModel->search(Yii::$app->request->queryParams);

        $orcModel = new DcmdResOracleSearch;
        $orcProvider = $orcModel->search(Yii::$app->request->queryParams);

        $mclModel = new DcmdResMclusterSearch;
        $mclProvider = $mclModel->search(Yii::$app->request->queryParams);
        
        $resType = array();
        $res_Type = DcmdResType::find()->asArray()->all();
        if($res_Type) {
          foreach($res_Type as $item) $resType[$item['res_type']] = $item['res_type'];
        }
        $resType = json_encode($resType);
        return $this->render('index', [
            'mclModel' => $mclModel,
            'mclProvider' => $mclProvider,
            'resType' => $resType,
        ]);
    }

    public function actionGetResource($type) {
        $res_type = DcmdResType::findOne(['res_type'=>$type]);
        $res_column = DcmdResColumn::find()->andWhere(['res_type'=>$type,'display_list'=>1])->asArray()->all();
        $sql = 'select * from '.$res_type['res_table'].' order by res_name';
        $connection=Yii::$app->db;
        $command=$connection->createCommand($sql);
        $result = '<table class="table table-striped table-bordered"><tbody>';
        $result .= '<tr><th></th><th>资源名称</th><th>公司名称</th><th>联系人</th><th>状态</th>';
        if($res_column) {
          foreach($res_column as $column) $result .= '<th>'.$column['display_name'].'</th>';
        }
        $result .= '</tr>';
        $query = $command->queryAll();
        $result_td = '';
        if($query) {
          foreach($query as $item) {
            $company = DcmdCompany::findOne($item['comp_id']);
            $company_name = "";
            if ($company) $company_name = $company['comp_name'];
            $state = $item['state'] == 1 ? "在线":"下线";
            $result_td .= '<tr><td><input name="selection[]" value="'.$item['id'].'" type="checkbox"></td><td><a href="/index.php?r=dcmd-resource/detail&id='.$item['id'].'&res_type='.$type.'">'.$item['res_name'].'</a></td><td>'.$company_name.'</td><td>'.$item['contact'].'</td><td>'.$state.'</td>';
            if($res_column) {
              foreach($res_column as $column) {
                $result_td .= '<td>'.$item[$column['colum_name']].'</td>';
              }
            }
            $result_td .= '</tr>';
          }
        }
        $result = $result.$result_td;
        $result .= '</tbody></table>';
        return $result;
    } 

    public function actionAddResource($type) {
      $res_type = DcmdResType::findOne(['res_type'=>$type]);
      $table_string = $res_type['res_table'];
      $result = '<tr><td><div class="form-group"><label class="control-label">'."资源名称".'</label></td>';
      $result .= '<td><input id="'.$table_string.'-res_name" style="margin-left:20px;width:300px" name="'.$table_string.'-res_name" maxlength="128" enable="true" type="text"></div></td><tr>';
      $result .= '<tr><td><div class="form-group"><label class="control-label">'."公司名称".'</label></td>';
      $result .= '<td><select id="'.$table_string.'-comp_id" style="margin-left:20px;width:300px" name="'.$table_string.'-comp_id" maxlength="128" enable="true" type="text">';
      $company = DcmdCompany::find()->andWhere("")->asArray()->all();
      if($company) {
        foreach($company as $item) {
          $result .= '<option value="'.$item['comp_id'].'">'.$item['comp_name'].'</option>';
        }
      }
      $result .= '</select><div class="help-block"></div></div></td></tr>';
//      $result .= '<td><input id="'.$table_string.'-comp_id" style="margin-left:20px;width:300px" name="'.$table_string.'-comp_id" maxlength="128" enable="true" type="text"><div class="help-block"></div></div></td></tr>';
      $result .= '<tr><td><div class="form-group"><label class="control-label">'."联系人".'</label></td>';
      $result .= '<td><input id="'.$table_string.'-contact" style="margin-left:20px;width:300px" name="'.$table_string.'-contact" maxlength="128" enable="true" type="text"><div class="help-block"></div></div></td></tr>';
      $result .= '<tr><td><div class="form-group"><label class="control-label">'."状态".'</label></td>';
      $result .= '<td><select id="'.$table_string.'-state" style="margin-left:20px;width:300px" name="'.$table_string.'-state" maxlength="128" enable="true" type="text"><div class="help-block"><option value="1">在线</option><option value="0">下线</option></select></div></div></td></tr>';
      $result .= '<tr><td><div class="form-group"><label class="control-label">'."停用时间".'</label></td>';
      $result .= '<td><input id="'.$table_string.'-stime" style="margin-left:20px;width:300px" name="'.$table_string.'-stime" maxlength="128" enable="true" type="text" value="0000-00-00 00:00:00"><div class="help-block"></div></div></td></tr>';
      $res_column = DcmdResColumn::find()->andWhere(['res_type'=>$type])->orderBy('display_order')->asArray()->all();
      if($res_column) {
        foreach($res_column as $item){
          $result .= '<tr><td><div class="form-group"><label class="control-label">'.$item['display_name'].'</label></td>';
          $result .= '<td><input id="'.$table_string.'-'.$item['colum_name'].'" style="margin-left:20px;width:300px" name="'.$table_string.'-'.$item['colum_name'].'" maxlength="128" enable="true" type="text"><div class="help-block"></div></div></td></tr>';
        }
      }
      return $result;
    }


    public function actionResourceSubmit(){
      $m = Yii::$app->request->post();
      foreach($m as $k=>$v) {
        if($k=='sel') continue;
        $table_name=$k;
      }
      $table = explode('-',$k);
      $table = $table[0];
      $sql = 'insert into '.$table.'(';
      $sql_value = '(';
      foreach($m as $k=>$v) {
        if($k=='sel') continue;
        $k = explode('-',$k);
        $sql .= ($k[1].',');
        $sql_value .= ('"'.$v.'"'.',');
      }
      $sql .= 'ctime,opr_uid)';
      $sql_value .= ('"'.date('Y-m-d h:m:s').'",'.Yii::$app->user->getId().')');
      $sql = $sql." values".$sql_value;
      
//      $sql_column = 'select COLUMN_NAME from information_schema.COLUMNS where table_name ="'.$table.'"';
      $connection=Yii::$app->db;
      $command=$connection->createCommand($sql);
      try {
        $command->execute();
      } catch (Exception $e) {
        Yii::$app->getSession()->setFlash('error', "添加失败");
        return $this->redirect(['define-column']); 
      }
      Yii::$app->getSession()->setFlash('success', "添加成功");
      return $this->redirect(['index']);
    }

    public function actionResourceDelete()
    {
      $select = $_POST['selected'];
      $type = $_POST['type'];
      $items = '';
      if ($select) {
        foreach($select as $k=>$v) {
          $items .= ($v.',');
        }
        $items = ('('.$items.'0)');
      }
      $res_type = DcmdResType::findOne(['res_type'=>$type]);
      $table_string = $res_type['res_table'];
      $sql = 'delete from '.$table_string.' where id in'.$items;
      $connection=Yii::$app->db;
      $command=$connection->createCommand($sql);
      try {
        $command->execute();
      } catch (Exception $e) {
        $data = "fail";
        return $data;
      }
      $data = "success";
      return $data;
    }

    public function actionDetail($id,$res_type) 
    {
      $type = DcmdResType::findOne(['res_type'=>$res_type]);
      $table_string = $type['res_table'];
      $sql = 'select * from '.$table_string.' where id='.$id;
      $connection=Yii::$app->db;
      $command=$connection->createCommand($sql);
      $query = $command->queryAll();
      $res_column = DcmdResColumn::find()->andWhere(['res_type'=>$res_type])->asArray()->all();
      $company = DcmdCompany::findOne($query[0]['comp_id']);
      $company_name = "";
      if ($company) $company_name = $company['comp_name'];
      $state = $query[0]['state'] == 1 ? "在线":"下线";
      $result = '<table class="table table-striped table-bordered"><tbody>';
      $result .= '<tr><td>资源名称</td><td>'.$query[0]['res_name'].'</td><tr>';
      $result .= '<tr><td>状态</td><td>'.$state.'</td><tr>';
      $result .= '<tr><td>公司名称</td><td>'.$company_name.'</td><tr>';
      $result .= '<tr><td>联系人</td><td>'.$query[0]['contact'].'</td><tr>';
      foreach($res_column as $item) {
        $result .= '<tr><td>'.$item['display_name'].'</td><td>'.$query[0][$item['colum_name']].'</td><tr>';
      }
      $result .= '</tbody></table>';
      return $this->render('view', [
        'result' => $result,
        'id' => $query[0]['id'],
        'type' => $res_type,
      ]);
      
    }

    public function actionDefineColumn()
    {
      $model = new DcmdResColumn();
      $res_type = DcmdResType::find()->asArray()->all();
      $type = array();
      foreach($res_type as $item) $type[$item['res_type']] = $item['res_type'];
      if (Yii::$app->request->post()) {
        $model->load(Yii::$app->request->post());
        $type_selected = $model->res_type;
        $res_table = DcmdResType::findOne(['res_type'=>$type_selected]);
        $model->res_table = $res_table->res_table;
        $model->ctime = date('Y-m-d h:m:s');
        $model->opr_uid = Yii::$app->user->getId();
        if($model->save()) {
          Yii::$app->getSession()->setFlash('success', '添加成功!');
          return $this->redirect(['define-column']);
        }
        else {
          Yii::$app->getSession()->setFlash('error', '添加失败!');
        }
      }
      return $this->render('define-column', [
        'model' => $model,
        'type' => $type,
      ]);
    }

    /**
      
     * @param integer $id
     * @return mixed
     */
    public function actionView($res_id,$res_type)
    {
      $type = DcmdResType::findOne(['res_type'=>$res_type]);
      $table_string = $type['res_table'];
      $sql = 'select * from '.$table_string.' where res_id="'.$res_id.'"';
      $connection=Yii::$app->db;
      $command=$connection->createCommand($sql);
      $query = $command->queryAll();
      $res_column = DcmdResColumn::find()->andWhere(['res_type'=>$res_type])->asArray()->all();
      $company = DcmdCompany::findOne($query[0]['comp_id']);
      $company_name = "";
      if ($company) $company_name = $company['comp_name'];
      $result = '<table class="table table-striped table-bordered"><tbody>';
      $result .= '<tr><td>资源名称</td><td>'.$query[0]['res_name'].'</td><tr>';
      $result .= '<tr><td>公司名称</td><td>'.$company_name.'</td><tr>';
      $result .= '<tr><td>联系人</td><td>'.$query[0]['contact'].'</td><tr>';
      foreach($res_column as $item) {
        $result .= '<tr><td>'.$item['display_name'].'</td><td>'.$query[0][$item['colum_name']].'</td><tr>';
      }
      $result .= '</tbody></table>';
      return $this->render('view', [
        'result' => $result,
        'id' => $query[0]['id'],
        'type' => $res_type,
      ]);
      # return $result;
    }

    private function getCompany($comp_id)
    {
      $ret = DcmdCompany::findOne($comp_id);
      if($ret) return $ret['comp_name'];
      else return "";
    }

    /**
     * Creates a new DcmdService model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($app_id)
    {
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($app_id);
        $query_svr=NULL;
        if($temp->is_self) {
          $query_svr = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['svr_gid']]);
        }
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL && $query_svr==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/view', 'id'=>$app_id));
        }
 
        $ser_query = DcmdService::find()->andWhere(['app_id'=>$app_id])->asArray()->all();
        $ser_array = array();
        foreach($ser_query as $item) $ser_array[$item['svr_id']] = $item['svr_name'];        
        $ser_pool_query = DcmdServicePool::find()->andWhere(['app_id'=>$app_id])->asArray()->all();
        $ser_pool_array = array(); 
        foreach($ser_pool_query as $item) $ser_pool_array[$item['svr_pool_id']] = $item['svr_pool'];       

        $model = new DcmdAppRes();
        if (Yii::$app->request->post()) {
          $model->load(Yii::$app->request->post());
          $model->app_id = $app_id;
          $model->ctime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
       //   $model->res_name = "test";
          if ($model->save()) {
            $this->oprlog(1, "insert service:".$model->res_name);
            Yii::$app->getSession()->setFlash('success', '添加成功!'); 
            return $this->redirect(array('dcmd-app/view', 'id' => $model->app_id));
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
        }

        $orcModel = new DcmdResOracleSearch;
        $orcProvider = $orcModel->search(Yii::$app->request->queryParams);

        $dnsModel = new DcmdResDnsSearch();
        $dnsProvider = $dnsModel->search(Yii::$app->request->queryParams);

        $slbModel = new DcmdResSlbSearch();
        $slbProvider = $slbModel->search(Yii::$app->request->queryParams);

        $model->app_id = $app_id;


    $user = new DcmdResDnsSearch();
    // 查询总数
    $user_count = $user->find()->count();
    $data['pages'] = new Pagination(['totalCount' => $user_count]);
    // 设置每页显示多少条
   // $data['pages']->defaultPageSize = 10;
   $user_list = $user->find()->offset($data['pages']->offset)->limit($data['pages']->limit)->asArray()->all();
//   $data['pages']->params=array("tab"=>'all');


        return $this->render('create', [
            'data' => $data,
            'model' => $model,
            'ser_array' => $ser_array,
            'ser_pool_array' => $ser_pool_array,
            'orcModel' => $orcModel,
            'orcProvider' => $orcProvider,
            'dnsModel' => $dnsModel,
            'dnsProvider' => $dnsProvider,
            'slbModel' => $slbModel,
            'slbProvider' => $slbProvider,
        ]);
    }

    public function actionGetitems($class)
    {
      switch ($class)
      {
      case 'MySQL':
        $ret = DcmdResMysql::find()->asArray()->all();
        break;
      case 'Cbase':
        $ret = DcmdResCbase::find()->asArray()->all();
        break;
      case 'DNS':
        $ret = DcmdResDns::find()->asArray()->all();
        break;
      case 'LVS':
        $ret = DcmdResLvs::find()->asArray()->all();
        break;
      case 'Mq':
        $ret = DcmdResMq::find()->asArray()->all();
        break;
      case 'SLB':
        $ret = DcmdResSlb::find()->asArray()->all();
        break;
      case 'Mongo':
        $ret = DcmdResMongo::find()->asArray()->all();
        break;
      case 'Mcluster':
        $ret = DcmdResMcluster::find()->asArray()->all();
        break;
      case 'Gluster':
        $ret = DcmdResGluster::find()->asArray()->all();
        break;
      case 'Oracle':
        $ret = DcmdResOracle::find()->asArray()->all();
        break;
      default:
        $ret = array();
      }
      $result = array();
      if ($ret)
        foreach($ret as $item) {
          if($item) {
            $result[$item['res_id']] = $item['res_name'];
          }
        }
        $result = json_encode($ret);
      return $result;
    }


    /**
     * Updates an existing DcmdService model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id,$type)
    {
        $res_type = DcmdResType::findOne(['res_type'=>$type]);
        $table_string = $res_type['res_table'];
        $sql = 'select * from '.$table_string.' where id='.$id;
        $connection=Yii::$app->db;
        $command=$connection->createCommand($sql);
        $query = $command->queryAll();
        $result = '<form id="w0" name="" action="/index.php?r=dcmd-resource/update&id='.$id.'&type='.$type.'" method="post">';
        $result .= '<div class="form-group"><label class="control-label">'."资源名称".'</label>';
        $result .= '<input id="'.$table_string.'-res_name" class="form-control" name="res_name" value="'.$query[0]['res_name'].'" maxlength="128" enable="true" type="text"><div class="help-block"></div></div>';
        $result .= '<div class="form-group"><label class="control-label">'."公司名称".'</label>';
        $result .= '<select id="'.$table_string.'-comp_id" class="form-control" name="comp_id" maxlength="128" enable="true" type="text"><div class="help-block"></div></div>';
        $company = DcmdCompany::find()->andWhere("")->asArray()->all();
        if($company) {
          foreach($company as $item) {
            if($item['comp_id'] == $query[0]['comp_id']) {
              $result .= '<option value="'.$item['comp_id'].'" selected="">'.$item['comp_name'].'</option>';
            }else {
              $result .= '<option value="'.$item['comp_id'].'">'.$item['comp_name'].'</option>';
            }
          }
        }
        $result .= '</select><div class="help-block"></div></div>';
        //$result .= '<input id="'.$table_string.'-comp_id" class="form-control" name="comp_id" maxlength="128" enable="true" type="text"><div class="help-block"></div></div>';
        $result .= '<div class="form-group"><label class="control-label">'."联系人".'</label>';
        $result .= '<input id="'.$table_string.'-contact" class="form-control" name="contact" value="'.$query[0]['contact'].'" maxlength="128" enable="true" type="text"><div class="help-block"></div></div>';
        $result .= '<div class="form-group"><label class="control-label">'."状态".'</label>';
        $result .= '<select id="'.$table_string.'-state" class="form-control" name="state"  maxlength="128" enable="true" type="text">';
        if($query[0]['state'] == 0) {
          $result .= '<option value="0" selected="">下线</option><option value="1">在线</option></select><div class="help-block"></div></div>';
        }else {
          $result .= '<option value="0">下线</option><option value="1" selected="">在线</option></select><div class="help-block"></div></div>';
        }
        $result .= '<div class="form-group"><label class="control-label">'."停用时间".'</label>';
        $result .= '<input id="'.$table_string.'-stime" class="form-control" name="stime" value="'.$query[0]['stime'].'" maxlength="128" enable="true" type="text"><div class="help-block"></div></div>';
        $res_column = DcmdResColumn::find()->andWhere(['res_type'=>$type])->asArray()->all();
        if($res_column) {
          foreach($res_column as $item){
            $result .= '<div class="form-group"><label class="control-label">'.$item['display_name'].'</label>';
            $result .= '<input id="'.$table_string.'-'.$item['colum_name'].'" class="form-control" name="'.$item['colum_name'].'" value="'.$query[0][$item['colum_name']].'" maxlength="128" enable="true" type="text"><div class="help-block"></div></div>';
          }
        }
        if (Yii::$app->request->post()) {
          $sql = 'update '.$table_string.' set ';
          foreach(Yii::$app->request->post() as $k=>$v) {
            if($v) {
              $sql .=  ($k.'="'.$v.'",');
            }
          }
          $sql = substr($sql,0,-1);
          $sql .= " where id=".$id;
           // $model->app_type = $this->getGinfo(Yii::$app->request->post()['DcmdApp']['app_type']);
          $connection=Yii::$app->db;
          $command=$connection->createCommand($sql);
          if($command->execute()){
            $this->oprlog(2, "update resorce:".$id."by ".Yii::$app->user->getId());
            Yii::$app->getSession()->setFlash('success',"修改成功!");
            return $this->redirect(['detail', 'id' => $id,'res_type'=>$type]);
          }
        }
        

          
        return $this->render('update', [
           'result' => $result,
        ]);
    }

    /**
     * Deletes an existing DcmdService model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $app_id=NULL)
    {
      $model = $this->findModel($id);
      ///仅仅用户与该应用在同一个系统组才可以操作
      $temp = DcmdApp::findOne($model['app_id']);
      $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
      if($query==NULL) {
        Yii::$app->getSession()->setFlash('success', NULL);
        Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
        return $this->redirect(array('dcmd-app/view', 'id'=>$model['app_id']));
      }
      $node = DcmdServicePool::find()->where(['svr_id' => $id])->one();
      if($node) {
        Yii::$app->getSession()->setFlash('error', '服务池子不为空,不可删除!');
      }else {
        $this->oprlog(3, "delete svrvice:".$model->svr_name);
        $this->deleteDiagram($id);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
      }
      if ($app_id) {
        return $this->redirect(array('dcmd-app/view', 'id'=>$app_id));
      }
      return $this->redirect(['index']);
    }

    public function actionDeleteAll()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择服务!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $suc_msg = "";
      $err_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->findModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
           $err_msg .=$model->svr_name.":没有权限删除<br>";
           continue;
        }
        $node = DcmdServicePool::find()->where(['svr_id' => $id])->one();
        if($node) {
          $err_msg .= $model->svr_name.':服务池子不为空,不可删除<br>';
          continue;
        }else { 
          $this->oprlog(3, "delete svrvice:".$model->svr_name);
          $this->deleteDiagram($id);
          $model->delete();
          $suc_msg .=$model->svr_name.':删除成功<br>';
        }
      }
      if($suc_msg != "") Yii::$app->getSession()->setFlash('success', $suc_msg);
      if($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['index']);

    }

    ///删除应用对应的图片
    private function deleteDiagram($id){
      $query = DcmdServiceArchDiagram::find()->andWhere(['svr_id'=>$id])->asArray()->all();
      foreach($query as $item) {
        ///删除文件
        $base_path = dirname(__DIR__)."/web/app_image/svr_";
        $img_path = $base_path.$item['arch_name'].'_'.$id.'.jpg';
        if(file_exists($img_path)) unlink($img_path);
      }
      DcmdServiceArchDiagram::deleteAll(['svr_id'=>$id]);
    }

    /**
     * Finds the DcmdService model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdService::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
   private function oprlog($opr_type, $sql) {
     $opr_log = new DcmdOprLog();
     $opr_log->log_table = "dcmd_service";          
     $opr_log->opr_type = $opr_type;
     $opr_log->sql_statement = $sql;
     $opr_log->ctime = date('Y-m-d H:i:s');
     $opr_log->opr_uid = Yii::$app->user->getId();
     $opr_log->save();
   }
}
