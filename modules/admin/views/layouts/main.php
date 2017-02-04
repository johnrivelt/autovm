<?php
use yii\helpers\Html;

$bundle = \app\modules\admin\assets\Asset::register($this);

$this->beginPage();
?>
<!DOCTYPE HTML>
<html lang="fa">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php echo Html::csrfMetaTags();?>
        
        <title><?php echo Html::encode(Yii::$app->setting->title);?> - Administrator</title>
                
        <link rel="shortcut icon" href="<?php echo $bundle->baseUrl;?>/img/favicon.png">
        <link href="<?=Yii::getAlias('@web')?>/strength-meter/css/strength-meter.min.css" media="all" rel="stylesheet" type="text/css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script src="<?=Yii::getAlias('@web')?>/strength-meter/js/strength-meter.min.js" type="text/javascript"></script>
        <?php $this->head();?>
    </head>
    <body>
        <!-- navigation -->
        <div class="col-md-2 no-padding">
            <div class="navigation">
                <h3>Administrator</h3>
                <ul class="nav">
                    <?php $c = Yii::$app->controller->id; $a = Yii::$app->controller->action->id;?>
                    <li<?php echo ($c == 'default' && $a == 'index' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin');?>"><i class="fa fa-laptop"></i>Dashboard</a></li>
                    <li<?php echo ($c == 'user' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/user/index');?>"><i class="fa fa-smile-o"></i>Users</a></li>
                    <li<?php echo ($c == 'plan' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/plan/index');?>"><i class="fa fa-plus-square-o"></i>Plans</a></li>
                    <li<?php echo ($c == 'ip' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/ip/index');?>"><i class="fa fa-clone"></i>Ip addresses</a></li>
                    <li<?php echo ($c == 'server' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/server/index');?>"><i class="fa fa-recycle"></i>Dedicated servers</a></li>
                    <li<?php echo ($c == 'datastore' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/datastore/index');?>"><i class="fa fa-clone"></i>Datastores</a></li>
                    <li<?php echo ($c == 'os' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/os/index');?>"><i class="fa fa-circle-thin"></i>Operation systems</a></li>
                    <li<?php echo ($c == 'vps' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/vps/index');?>"><i class="fa fa-tv"></i>Virtual servers</a></li>
                    <li<?php echo ($c == 'default' && $a == 'login' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/default/login');?>"><i class="fa fa-copy"></i>Login histories</a></li>
                    <li<?php echo ($c == 'api' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/api/index');?>"><i class="fa fa-list"></i>Apis</a></li>
                    <li<?php echo ($c == 'default' && $a == 'setting' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/default/setting');?>"><i class="fa fa-cogs"></i>Settings</a></li>
                </ul>
            </div>
        </div>
        <!-- END navigation -->
        
        <div class="col-md-10 no-padding">
            <!-- header -->
            <div class="navbar navbar-default">
                <div class="navbar-header">
                    <a href="#" class="navbar-brand"></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="#"><i class="fa fa-envelope-o"></i> <span class="badge badge-green">10</span></a></li>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl('/admin');?>">Dashboard</a></li>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl('/site');?>">Client</a></li>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/default/login');?>">Login History</a></li>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/default/setting');?>">Settings</a></li>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl('/site/default/logout');?>">Logout</a></li>
                    </ul>
                </div>
            </div>
            <!-- END header -->
            
            <?php $this->beginBody();?>
                <?php echo $content;?>
            <?php $this->endBody();?>
            <!-- footer -->
            <div class="footer">
                <div class="col-md-12">
                    <p>Designed and programed by autovm, all rights reserved</p>
                </div>
            </div>
            <!-- END footer -->
        </div>
    </body>
</html>
<?php $this->endPage();?>