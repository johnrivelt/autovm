<?php
use yii\helpers\Html;

$this->beginPage();

$bundle = \app\modules\site\assets\Asset::register($this);

// website base url
$baseUrl = Yii::$app->request->baseUrl . '/index.php';
$this->registerJs("var baseUrl = \"{$baseUrl}\";", \yii\web\View::POS_END);

?>

<?php $this->beginPage();?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="nikivm">

        <title><?php echo Html::encode(Yii::$app->setting->title);?></title>

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script type="text/javascript" src="<?= \yii\helpers\Url::base() ?>/js/pwstrength.js"></script>
        <link rel="shortcut icon" href="<?php echo $bundle->baseUrl;?>/img/favicon.png">

        <?php echo Html::csrfMetaTags();?>
        <?php $this->head();?>
    </head>
    <body>
    <?php $this->beginBody();?>
        <?php if(!Yii::$app->user->isGuest) {?>
        <?php echo \app\modules\site\widgets\UserVpsList::widget();?>
        <?php }?>
        <div class="navbar navbar-inverse">
            <div class="container">
                <div class="navbar-header">
                    <a href="<?php echo Yii::$app->urlManager->createUrl('/site');?>" class="navbar-brand"><img src="<?php echo $bundle->baseUrl;?>/img/logo.png"></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <?php $controller = Yii::$app->controller->id; $action = Yii::$app->controller->action->id;?>

                        <?php if(Yii::$app->user->isGuest) {?>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl('/site');?>"><i class="fa fa-home"></i>Home</a></li>
                        <li<?php echo $controller == 'site' && $action == 'login' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/default/login']);?>"><i class="fa fa-user"></i>Login</a></li>
                        <li<?php echo $controller == 'site' && $action == 'lost-password' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['site/default/lost-password']);?>"><i class="fa fa-lock"></i>Lost Password</a></li>
                        <?php } else {?>
                        <li<?php echo $controller == 'user' && $action == 'index' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/user/index']);?>"><i class="fa fa-laptop"></i>Dashboard</a></li>
                        <li<?php echo $controller == 'email' && $action == 'index' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/email/index']);?>"><i class="fa fa-envelope-o"></i>Emails</a></li>
                        <li<?php echo $controller == 'user' && $action == 'profile' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/user/profile']);?>"><i class="fa fa-user"></i>Profile</a></li>
                        <li<?php echo $controller == 'user' && $action == 'password' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/user/password']);?>"><i class="fa fa-lock"></i>Account Password</a></li>
                        <li<?php echo $controller == 'user' && $action == 'login' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/user/login']);?>"><i class="fa fa-list"></i>Login History</a></li>
                        <?php if(Yii::$app->user->identity->getIsAdmin()) {?>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl('/admin');?>"><i class="fa fa-star-o"></i>Admin</a></li>
                        <?php }?>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/default/logout']);?>"><i class="fa fa-sign-out"></i>Logout</a></li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        </div>

        <?php echo $content;?>

        <div class="footer">
            <div class="container">
                <p>Created and designed by autovm, all rights reserved</p>
            </div>
        </div>
    <?php $this->endBody();?>
    </body>
</html>
<?php $this->endPage();?>
