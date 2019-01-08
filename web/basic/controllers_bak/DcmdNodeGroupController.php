<?php

namespace app\controllers;

use Yii;
use app\models\DcmdGroup;
use app\models\DcmdUserGroup;
use app\models\DcmdNode;
use app\models\DcmdNodeSearch;
use app\models\DcmdNodeGroup;
use app\models\DcmdNodeGroupInfo;
use app\models\DcmdNodeGroupSearch;
use app\models\DcmdNodeGroupAttr;
use app\models\DcmdNodeGroupAttrDef;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
require(dirname(__FILE__)."/../common/dcmd_util_func.php");
/**
 * DcmdNodeGroupController implements the CRUD actions for DcmdNodeGroup model.
 */
class DcmdNodeGroupController extends Controller
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
     * Lists all DcmdNodeGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdNodeGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $ret = DcmdGroup::findBySql("select gid,gname from dcmd_group where gtype=1 order by gname")->asArray()->all();
        $groupId = array();
        foreach($ret as $gid) {
         $groupId[$gid['gid']] = $gid['gname']; 
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'groupId' => $groupId,
        ]);
    }

    /**
     * Displays a single DcmdNodeGroup model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new DcmdNodeSearch();
        $searchModel->ngroup_id = $id;
        $params = Yii::$app->request->queryParams;
        $params["DcmdNodeSearch"]["ngroup_id"] = $id; 
        $params["DcmdNodeSearch"]["rack"] = "";
        $dataProvider = $searchModel->search($params);
        $show_div = "dcmd-node-group";
        if(array_key_exists('show_div', $params)) $show_div = $params['show_div'];
        if(array_key_exists("DcmdNodeSearch", Yii::$app->request->queryParams))
          $show_div = 'dcmd-node';
        ///获取属性
        $self_attr = DcmdNodeGroupAttr::find()->andWhere(['ngroup_id'=>$id])->asArray()->all();
        $def_attr = DcmdNodeGroupAttrDef::find()->asArray()->all();
        $attr_str = '<div id="w1" class="grid-view">
          <table class="table table-striped table-bordered"><thead>
          <tr><th>属性名</th><th>值</th><th>操作</th></tr>
          </thead><tbody>';
        $attr = array();
        foreach($self_attr as $item) {
          $attr_str .= '<tr><td>'.$item['attr_name'].'</td><td>'.$item['attr_value'].'</td><td><a href="/ducter/index.php?r=dcmd-node-group-attr/update&id='.$item['id'].'&ngroup_id='.$id.'">修改</a></td></tr>';
          $attr[$item['attr_name']] = $item['attr_name'];
        }
        foreach($def_attr as $item) {
          if(array_key_exists($item['attr_name'], $attr)) continue;
          $attr_str .= '<tr><td>'.$item['attr_name'].'</td><td>'.$item['def_value'].'</td><td><a href="/ducter/index.php?r=dcmd-node-group-attr/update&id=0&attr_id='.$item['attr_id'].'&ngroup_id='.$id.'">修改</a></td></tr>';
        }
        $attr_str .= "</tbody></table></div>"; 
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'attr_str' => $attr_str,
            'ngroup_id' => $id,
            'show_div' => $show_div,
        ]);
    }

    /**
     * Creates a new DcmdNodeGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!!");
          return $this->redirect(array('index'));
        }
        $model = new DcmdNodeGroup();
        $sys_group = $this->getGroups();
        $query = DcmdUserGroup::find()->andWhere(['uid'=>Yii::$app->user->getId()])->asArray()->all();
        $location_query = DcmdNodeGroupInfo::find()->andWhere(['type'=>'区域'])->asArray()->all();
        $operator_query = DcmdNodeGroupInfo::find()->andWhere(['type'=>"运营商"])->asArray()->all();
        $type_query = DcmdNodeGroupInfo::find()->andWhere(['type'=>"类别"])->asArray()->all();
        $room_query = DcmdNodeGroupInfo::find()->andWhere(['type'=>"机房"])->asArray()->all();
        $groups = array();
        $location = array();
        $operator = array();
        $type = array();
        $room = array();
        foreach($query as $item) {
          $groups[$item['gid']] = $sys_group[$item['gid']];
        }
        foreach($location_query as $item) {
          $location[$item['id']] = $item['name'];
        }
        foreach($operator_query as $item) {
          $operator[$item['id']] = $item['name'];
        }
        foreach($type_query as $item) {
          $type[$item['id']] = $item['name'];
        }
        foreach($room_query as $item) {
          $room[$item['id']] = $item['name'];
        }
        
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
        } 
        if ($model->load(Yii::$app->request->post())) {
            $model->location = $this->getGinfo(Yii::$app->request->post()['DcmdNodeGroup']['location']);
            $model->gtype = $this->getGinfo(Yii::$app->request->post()['DcmdNodeGroup']['gtype']);
            $model->operators = $this->getGinfo(Yii::$app->request->post()['DcmdNodeGroup']['operators']);
            $model->mach_room = $this->getGinfo(Yii::$app->request->post()['DcmdNodeGroup']['mach_room']);
            $model->save();
            return $this->redirect(['view', 'id' => $model->ngroup_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'groups' => $groups,
                'location' => $location,
                'operator' => $operator,
                'type' => $type,
                'room' => $room,
            ]);
        }
    }

    /**
     * Updates an existing DcmdNodeGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        ///判断用户是否和该设备池子属于一个系统组
        $model = $this->findModel($id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->goBack();///redirect(array('index'));
        }
        $groups = $this->getGroups();
        $location_query = DcmdNodeGroupInfo::find()->andWhere(['type'=>'区域'])->asArray()->all();
        $operator_query = DcmdNodeGroupInfo::find()->andWhere(['type'=>"运营商"])->asArray()->all();
        $type_query = DcmdNodeGroupInfo::find()->andWhere(['type'=>"类别"])->asArray()->all();
        $room_query = DcmdNodeGroupInfo::find()->andWhere(['type'=>"机房"])->asArray()->all();
        $location = array();
        $operator = array();
        $type = array();
        $room = array();
        foreach($location_query as $item) {
          $location[$item['id']] = $item['name'];
        }
        foreach($operator_query as $item) {
          $operator[$item['id']] = $item['name'];
        }
        foreach($type_query as $item) {
          $type[$item['id']] = $item['name'];
        }
        foreach($room_query as $item) {
          $room[$item['id']] = $item['name'];
        }

        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->location = $this->getGinfo(Yii::$app->request->post()['DcmdNodeGroup']['location']);
            $model->gtype = $this->getGinfo(Yii::$app->request->post()['DcmdNodeGroup']['gtype']);
            $model->operators = $this->getGinfo(Yii::$app->request->post()['DcmdNodeGroup']['operators']);
            $model->mach_room = $this->getGinfo(Yii::$app->request->post()['DcmdNodeGroup']['mach_room']);
            $model->save();
            return $this->redirect(['view', 'id' => $model->ngroup_id, 'show_div'=>'dcmd-node-group']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'groups' => $groups,
                'location' => $location,
                'operator' => $operator,
                'type' => $type,
                'room' => $room,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdNodeGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        ///判断用户是否和该设备池子属于一个系统组
        $model = $this->findModel($id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['gid']]);
        if($query == NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        $node = DcmdNode::find()->where(['ngroup_id' => $id])->one();
        if($node) {
          Yii::$app->getSession()->setFlash('error', '设备池子不为空,不可删除!');
        }else {
          ///删除组属性
          DcmdNodeGroupAttr::deleteAll(['ngroup_id'=>$id]);
          $this->findModel($id)->delete();
          Yii::$app->getSession()->setFlash('success', '删除成功!');
        }
        return $this->redirect(['index']);
    }
    public function actionDeleteAll() 
    {
        if(Yii::$app->user->getIdentity()->admin != 1 ){
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        if(!array_key_exists('selection', Yii::$app->request->post())) {
          Yii::$app->getSession()->setFlash('error', '未选择设备组!');
          return $this->redirect(['index']);
        }
        $select = Yii::$app->request->post()['selection'];
        $success_msg = "";
        $err_msg = "";
        foreach($select as $k=>$v) {
          $model = $this->findModel($v);
          ///判断用户是否和该设备池子属于一个系统组
          $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['gid']]);
          if($query == NULL) {
            $err_msg .=$model['ngroup_name'].":没有权限删除"."<br>";
            continue;
          }
          ///判断设备池是否为空
          $node = DcmdNode::find()->where(['ngroup_id' => $v])->one();
          if($node) {
            $err_msg .=$model['ngroup_name'].':设备池子不为空,不可删除'."<br>";
            continue;
          }
          DcmdNodeGroupAttr::deleteAll(['ngroup_id'=>$model->ngroup_id]);
          $model->delete();
          $success_msg .= $model['ngroup_name'].":删除成功"."<br>";
        }
        if($success_msg != "") Yii::$app->getSession()->setFlash('success', $success_msg);
        if ($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
        return $this->redirect(['index']);

    }
    public function actionImportNode($ngroup_id=0)
    {
       $model = $this->findModel($ngroup_id);
       if (Yii::$app->request->post()) {
         //var_dump($_FILES); ;
         ///var_dump(Yii::$app->request->post());exit;
         $err = "未提交数据文件!";
         if($_FILES['nfile']['error'] == 0 && $_FILES['nfile']['name'] != '') {
           $fname = $_FILES['nfile']['tmp_name'];
           if(file_exists($fname)) {
             $name = $_FILES['nfile']['name'];
             $prex = substr($name, strpos($name, "."));
             if(strcasecmp($prex, ".txt") == 0 || strcasecmp($prex, ".txt") == 0) { ///excel格式
               $err = $this->importNodeTxt($fname,$ngroup_id);
             }else{ 
               $err = "未知后缀名$prex!";
             }
           } 
         }
         Yii::$app->getSession()->setFlash('error', $err);
       }
       return $this->render('import_node', [
            'model' => $model,
        ]);
 
       
    }
    private function importNodeTxt($fname, $ngroup_id) {
      $fd = fopen($fname, "r");
      $msg = "";
      if($fd) {
        while(!feof($fd)) {
          $line = fgets($fd);
          $ar = explode(" ",$line);
          if(count($ar) != 21) $msg .= "<font color=red>$line: 格式非法!</font><br>";
          else {
            $node = new DcmdNode();
            $node->ip = $ar[0];
            $node->ngroup_id = $ngroup_id;
            $node->host = $ar[1];
            $node->sid = $ar[2];
            $node->did = $ar[3];
            $node->os_type = $ar[4];
            $node->os_ver = $ar[5];
            $node->bend_ip = $ar[6];
            $node->public_ip = $ar[7];
            $node->mach_room = $ar[8];
            $node->rack = $ar[9];
            $node->seat = $ar[10];
            $node->online_time = $ar[11];
            $node->server_brand = $ar[12];
            $node->server_model = $ar[13];
            $node->cpu = $ar[14];
            $node->memory = $ar[15];
            $node->disk = $ar[16];
            $node->purchase_time = $ar[17];
            $node->maintain_time = $ar[18];
            $node->maintain_fac = $ar[19];
            $node->comment = $ar[20];
            $node->utime = date('Y-m-d H:i:s');
            $node->ctime = date('Y-m-d H:i:s');
            $node->opr_uid = Yii::$app->user->getId();
            if($node->save()) $msg .= "$line: 添加成功!<br>";
            else {
              $err_str = "";
              foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
              $msg .="<font color=red>$line :添加失败: $err_str</font><br>";
            }
          }
        }
      }else {
        $msg = "打开文件失败";
      }
      return $msg;
    }
   /**
     * Finds the DcmdNodeGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdNodeGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdNodeGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     *Get gid-gname
     */
    protected function getGroups() {
        $ret = DcmdGroup::findBySql("select gid, gname from dcmd_group where gtype=1")->asArray()->all();
        $groupId = array();
        foreach($ret as $gid) {
         $groupId[$gid['gid']] = $gid['gname'];
        }
        return $groupId;
    }

    protected function getGinfo($id) {
        $query = DcmdNodeGroupInfo::findOne($id);
        if ($query) return $query->name;
        return "";
    }
}
