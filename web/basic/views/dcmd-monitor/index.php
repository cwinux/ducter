<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '报警信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="form1" name="form1" method="post" action="">
   <div style="float:left">
     报警级别下限(P1最高，P4最低):
     <input type="radio" name="myRadio" value="1" onclick="saveRadioValue()">P1
     <input type="radio" name="myRadio" value="2" onclick="saveRadioValue()">P2
     <input type="radio" name="myRadio" value="3" onclick="saveRadioValue()">P3
     <input type="radio" name="myRadio" value="4" onclick="saveRadioValue()">P4
   </div>
   <p style="color:#F00;float:right"><?php echo "当前时间:".date("Y-m-d H:i:s");?>
</form> 
<html>
  <head>
     <meta http-equiv="refresh" content="10" method="post">
  </head>
  <body onload="setRadio('alarmlevel')">
    <div>
        <script type="text/javascript">
            function saveRadioValue()
            {
                var rad = document.getElementsByName("myRadio");
                var radval = 1;
                for(var i=0;i<rad.length;i++)
                {
                   if(rad[i].checked)
                         var radval = rad[i].value;
                }
                var cookiename = 'alarmlevel';
                writeCookie(cookiename, radval);
                window.location.reload(); 
                
            }
            function writeCookie(name, value, hours) {
                var expire = "";
                hours = hours || 100;
                if (hours != null) {
                    expire = new Date((new Date()).getTime() + hours * 3600000);
                    expire = "; expires=" + expire.toGMTString();
                }
                document.cookie = name + "=" + escape(value) + expire;
            }
            function readCookie(name) {
                var cookieValue = "";
                var search = name + "=";
                if (document.cookie.length > 0) {
                    offset = document.cookie.indexOf(search);
                    if (offset != -1) {
                        offset += search.length;
                        end = document.cookie.indexOf(";", offset);
                        if (end == -1) end = document.cookie.length;
                        cookieValue = unescape(document.cookie.substring(offset, end))
                    }
                }
                var rad = document.getElementsByName("myRadio");
                return cookieValue;
            }
            function setRadio(name) {
                var cookieValue = 1;
                var search = name + "=";
                if (document.cookie.length > 0) {
                    offset = document.cookie.indexOf(search);
                    if (offset != -1) {
                        offset += search.length;
                        end = document.cookie.indexOf(";", offset);
                        if (end == -1) end = document.cookie.length;
                        cookieValue = unescape(document.cookie.substring(offset, end))
                    }
                }
                var rad = document.getElementsByName("myRadio");
                rad[cookieValue - 1].checked = true;
                return cookieValue;
            }
        </script>
    </div>
  <table class="table table-striped table-bordered">
    <tr>
        <td>集群名称</td>
        <td>主机名称</td>
        <td>报警级别</td>
        <td>报警内容</td>
        <td>报警时间</td>
    </tr>
    <tbody>
    <?php
         foreach($reslut as $alarm) {
           if (empty($_COOKIE['alarmlevel']))
             $level = 4;
           else $level = $_COOKIE['alarmlevel'];
           if ($alarm['priority'] <= $level) {
    ?>
    <tr>
        <td>
             <?php
                   echo $alarm['cluster'];
             ?>
        </td>
        <td>
             <?php 
                   echo $alarm['endpoint'];
             ?>
        </td>
        <td>
             <?php
                   echo "[P".$alarm['priority']." #".$alarm['currentStep']."/".$alarm['maxStep']."]";
             ?>
        </td>
        <td>
             <?php
                   $pos = strpos($alarm['counter'],'/');
                   echo substr($alarm['counter'],$pos+1)." ".$alarm['func']."  ".$alarm['leftValue'].$alarm['operator'].$alarm['rightValue'];
             ?>
        </td>
        <td>
             <?php
                   echo date('Y-m-d H:i:s',$alarm['timestamp']);
             ?>
        </td>
    </tr>
    <?php
             }
         }
    ?>
   </tbody>
  </table>
  </body>
</html>
