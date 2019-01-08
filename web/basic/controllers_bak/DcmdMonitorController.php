<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * monitor alarm controller.
 */
class DcmdMonitorController extends Controller
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
     * Lists all monitor alarm .
     * @return mixed
     */
    public function actionIndex()
    {
/*       $monitor_url = array("http://falcon-cn-test-1.console.lecloud.com:6067/alarm", "http://falcon-cn-test-1.console.lecloud.com:6067/alarm");
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_HEADER, 0);
       $reslut = array();

       foreach ($monitor_url as $url) {
           curl_setopt($ch, CURLOPT_URL, $url);
           $output = curl_exec($ch);
           $output_array = json_decode($output,true);
           $reslut = array_merge($output_array, $reslut);
       }
       arsort($reslut);
       curl_close($ch);*/
       $reslut = array();
       $dir = "../plugin/alarm/";
       if($dh = opendir($dir)) {
         while($file = readdir($dh)) {
           if ($file!="." && $file!=".."){
             if (time()-filemtime($dir.$file) > 60) {
               $alarminfo = '[{"endpoint":"'.$file.'","hostTag":[2],"metric":"'.$file.'","counter":"","func":"get alarminfo failed","leftValue":"1","operator":"\u003e","rightValue":"0","note":"","maxStep":1,"currentStep":1,"priority":3,"status":"PROBLEM","timestamp":'.time().',"cluster":"'.$file.'"}]';
               $content = json_decode($alarminfo,true);   
               $reslut = array_merge($content, $reslut);
             }
             else {
                 if (filesize($dir.$file) !=0) {
                   $myfile = fopen($dir.$file, "r") or die("Unable to open file!");
                   $alarminfo = fread($myfile,filesize($dir.$file));
                   if ($alarminfo) {
                     $content = json_decode($alarminfo,true);
                     $reslut = array_merge($content, $reslut);
                   }
                 }
             }
           }
         }
       }
       $fieldArr = array();
       foreach ($reslut as $k => $v) {
         $fieldArr[$k] = $v["timestamp"];
       }
       $sort = true == false ? SORT_ASC : SORT_DESC;
       array_multisort($fieldArr, $sort, $reslut);
       closedir($dh);
       return $this->render('index', [
            'reslut' => $reslut,
       ]);

    }
}
