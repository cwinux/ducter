<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php
  if(Yii::$app->user->isGuest)  {
    header("Location:index.php?r=site/login");
    exit;
  }
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap" >
        <?php
            NavBar::begin([
                'brandLabel' => 'Apollo(V1.0)',
                'brandUrl' => "#",///Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left', ],
                'items' => [
                    [
                        'label' => 'IDC',
                        'items' => [
                          ['label' => '机房','url'=>'?r=dcmd-idc/index'],
                         // ['label' => '网段','url'=>'?r=dcmd-network/index'],
                         // ['label' => '网络资源', 'url'=>'?r=dcmd-net-resource/index'],
                        ],
                    ],
                    [
			'label' => '设备管理',
			'items' => [
			  ['label' => '设备池','url'=>'?r=dcmd-node-group/index'],
                          ['label' => '设备池属性', 'url'=>'?r=dcmd-node-group-attr-def/index'],
			  ['label' => '设备', 'url' => '?r=dcmd-node/index'],
                          ['label' => '未使用设备', 'url' => '?r=dcmd-node/unuse-node'],
                          ['label' => '未注册设备', 'url' => '?r=dcmd-invalid-agent/index'],
			  ['label' => '控制中心', 'url' => '?r=dcmd-center/index','visible'=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
			],
                        'visible'=>(Yii::$app->user->getIdentity()->admin != 1) ? false : true,
                    ],
                    [
                        'label' => '产品管理',
                        'items' => [
                          ['label' => '产品','url'=>'?r=dcmd-app/index'],
                          ['label' => '服务', 'url' => '?r=dcmd-service/index'],
                          ['label' => '服务池', 'url' => '?r=dcmd-service-pool/index'],
                          ['label' => '服务池设备', 'url' => '?r=dcmd-service-pool-node/index'],
                          ['label' => '产品所有设备池', 'url' => '?r=dcmd-app/app-group'],
                        //  ['label' => '服务池属性', 'url' => '?r=dcmd-service-pool-attr-def/index'],
                          '<li class="divider"></li>',
                          ['label' => '资源类型','url'=>'?r=dcmd-resource-table/index'],
                          ['label' => '资源定义','url'=>'?r=dcmd-resource-column/index'],
                          ['label' => '资源详情','url'=>'?r=dcmd-resource/index'],
                          ['label' => '资源与产品', 'url' => '?r=dcmd-app-resource/index'],
                          '<li class="divider"></li>',
                          '<li class="dropdown-header" >文件管理</li>',
                          ['label' => '已上传','url'=>'?r=dcmd-upload/index'],
                          ['label' => '已拒绝','url'=>'?r=dcmd-reject/index'],
                          ['label' => '上传错误','url'=>'?r=dcmd-upload-error/index'],
                         /* '<li class="divider"></li>',
                          '<li class="dropdown-header" >虚拟机管理</li>',
                        //  ['label' => 'VM信息','url'=>'?r=dcmd-private/index'],
                          ['label' => 'VM信息','url'=>'?r=dcmd-private/index'],
                          ['label' => 'VM操作','url'=>'?r=dcmd-private/operate'],
                          ['label' => '异常VM','url'=>'?r=dcmd-private/invalid'],
                          '<li class="divider"></li>',
                          '<li class="dropdown-header" >资源报表</li>',
                          ['label' => '资源报表','url'=>'?r=dcmd-resource-report/index'],
                          ['label' => 'VM使用状况','url'=>'?r=dcmd-vm-report/index'],
                          '<li class="divider"></li>',
                          '<li class="dropdown-header" >网段管理</li>',
                          ['label' => '网段','url'=>'?r=dcmd-network/index'],
                          '<li class="divider"></li>',
                          '<li class="dropdown-header" >镜像管理</li>',
                          ['label' => '镜像','url'=>'?r=dcmd-image/index'],
                          '<li class="divider"></li>',
                          '<li class="dropdown-header" >搜索</li>',
                          ['label' => '搜索','url'=>'?r=dcmd-search/index'],*/
                        ],
                    ],
                  /*  [
                        'label' => 'Cbase管理',
                        'items' => [
                          '<li class="dropdown-header">产品管理</li>',
                          ['label' => '产品','url'=>'?r=dcmd-cbase-app/index'],
                          ['label' => '用户','url'=>'?r=dcmd-cbase-user/index'],
                          ['label' => 'Buckets','url'=>'?r=dcmd-cbase-buckets/index'],
                          ['label' => '设备','url'=>'?r=dcmd-cbase-app-node/index'],
                          ['label' => '异常设备','url'=>'?r=dcmd-cbase-app-node/invalid'],
                          ['label' => 'Moxi','url'=>'?r=dcmd-cbase-moxi/index'],
                        ],
                    ],*/

                    [
                        'label' => '任务',
                        'items' => [
                          ['label' => '任务脚本','url'=>'?r=dcmd-task-cmd/index'],
                          ['label' => '任务模板', 'url' => '?r=dcmd-task-template/index'],
                          ['label' => '任务', 'url' => '?r=dcmd-task/index'],
                          ['label' => '历史任务', 'url' => '?r=dcmd-task-history/index'],
                        ],
                    ],
                    [
                        'label' => '操作',
                        'items' => [
                          ['label' => '操作脚本','url'=>'?r=dcmd-opr-cmd/index'],
                          ['label' => '重复操作', 'url' => '?r=dcmd-opr-cmd-repeat-exec/index'],
                        ],
                    ],
                   /* [
                        'label' => '私有云',
                        'items' => [
                          ['label' => 'VM分配','url'=>'?r=dcmd-private/index'],
                          ['label' => 'VM状态不匹配','url'=>'?r=dcmd-business/index'],
                          ['label' => 'VM信息校对','url'=>'?r=dcmd-vm-maintain/index'],
                        ],
                    ],*/
                    [
                        'label' => '审批',
                        'items' => [
                          '<li class="dropdown-header" >审批</li>',
                          ['label' => '服务池设备','url'=>'?r=dcmd-audit/index'],
                          ['label' => '服务脚本','url'=>'?r=dcmd-audit/service-script'],
                   /*       '<li class="divider"></li>',
                          '<li class="dropdown-header" >工单</li>',
                          ['label' => '工单管理','url'=>'?r=dcmd-order/index'],
                          ['label' => '工单回复','url'=>'?r=dcmd-order-reply/index'],*/
                        ],
                        'visible'=>(Yii::$app->user->getIdentity()->sa == 1 || Yii::$app->user->getIdentity()->is_leader == 1) ? true : false,
//                        'visible' => Yii::$app->user->getIdentity()->admin == 1
                    ],
                   /* [
                        'label' => '报警与故障',
                        'items' => [
                    //      ['label' => '设备池设备','url'=>'?r=dcmd-audit/index'],
                          '<li class="dropdown-header" >监控</li>',
                          ['label' => '监控报警','url'=>'?r=dcmd-monitor/index'],
                          '<li class="divider"></li>',
                          '<li class="dropdown-header" >故障</li>',
                          ['label' => '故障管理','url'=>'?r=dcmd-fault/index'],
                          ['label' => '故障报告','url'=>'?r=dcmd-fault/fault-report'],
                          '<li class="divider"></li>',
                          '<li class="dropdown-header" >二线支持</li>',
                          ['label' => '工作记录','url'=>'?r=dcmd-work-diary/index'],
                        ],
                    ],*/
                        /*                [
                        'label' => '日志管理',
                        'items' => [
                    //      ['label' => '设备池设备','url'=>'?r=dcmd-audit/index'],
                          '<li class="dropdown-header" >日志</li>',
                          ['label' => '工单','url'=>'?r=dcmd-order-history/index'],
                          ['label' => '故障','url'=>'?r=dcmd-fault-history/index'],
                          ['label' => '报警','url'=>'?r=dcmd-alarm-history/index'],
                          ['label' => 'VM操作','url'=>'?r=dcmd-operate-history/index'],
                          ['label' => '工单回复','url'=>'?r=dcmd-orderreply-history/index'],
                        ],
                    ],*/
                    [
                        'label' => '工具',
                        'items' => [
                          ['label' => '脚本','url'=>'?r=dcmd-tool/script-download'],
                          ['label' => 'agent','url'=>'?r=dcmd-tool/agent-download'],
                        ],
                    ],
                    [
                        'label' => '权限',
                        'items' => [
                          ['label' => '用户','url'=>'?r=dcmd-user/index', 'visible'=>(Yii::$app->user->getIdentity()->is_leader == 1 || Yii::$app->user->getIdentity()->sa == 1) ? true : false],
                          ['label' => '用户组', 'url' => '?r=dcmd-group/index','visible'=>(Yii::$app->user->getIdentity()->sa == 1 || Yii::$app->user->getIdentity()->is_leader == 1) ? true : false],
                          ['label' => '部门', 'url' => '?r=dcmd-department/index', 'visible'=>(Yii::$app->user->getIdentity()->sa == 1) ? true : false],
                          ['label' => '公司', 'url' => '?r=dcmd-company/index', 'visible'=>(Yii::$app->user->getIdentity()->sa == 1) ? true : false],
//                          ['label' => '修改密码', 'url' => '?r=dcmd-user/change-passwd'],
                        ],
                        'visible'=>(Yii::$app->user->getIdentity()->sa == 1 || Yii::$app->user->getIdentity()->is_leader == 1) ? true : true,
                    ],
                    Yii::$app->user->isGuest ?
                        ['label' => 'Login', 'url' => ['/site/login']] :
                        ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']],
                ],
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; LeCloud <?= date('Y') ?></p>
            <p class="pull-right">Apollo(V1.0)</p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
