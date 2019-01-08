<?php

namespace app\controllers;

use Yii;
use app\models\DcmdUser;
use app\models\DcmdUserGroup;
use app\models\DcmdDepartment;
use app\models\DcmdUserSearch;
use app\models\DcmdGroupSearch;
use app\models\DcmdCompany;
use app\models\DcmdCompanySearch;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdUserController implements the CRUD actions for DcmdUser model.
 */
class DcmdCompanyController extends Controller
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
     * Lists all DcmdUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/index'));
        }
        $searchModel = new DcmdCompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
