<?php
use yii\helpers\Html;

$bundle = \app\modules\site\assets\Asset::register($this);

$this->registerJs('
    var opened = false;

    $(".sidebar-toggle").click(function(e){
        if(opened == false) {
            opened = true;
            $(".sidebar").css("margin-left", -300).css("display", "block").animate({marginLeft:0});
            $(this).html("<i class=\"fa fa-close\"></i>").animate({marginLeft:300});
        } else {
            opened = false;
            $(".sidebar").animate({marginLeft:-300}).css("margin-left", 0);
            $(this).animate({marginLeft:0}).html("<i class=\"fa fa-bars\"></i>");
        }
    });

    $(".sidebar li a").click(function(e){
        e.preventDefault();

        ul = $(this).parent().find("ul");

        if(ul.hasClass("opened")) {
            ul.removeClass("opened").css("display", "none");
        } else {
            ul.addClass("opened").css("display", "block");
        }
    });

    $(".sidebar li#sidebar-view").click(function(){
        window.location.href = $(this).data("url");
    });
');
?>

<?php if($virtualServers) {?>
<div class="sidebar-toggle"><i class="fa fa-list"></i></div>
<div class="sidebar">
    <ul>
        <?php foreach($virtualServers as $vps) {?>
        <li><a href="javascript:void()"><?php echo Html::encode($vps->ip ? $vps->ip->ip : 'NONE');?><i class="fa fa-chevron-right"></i></a>
            <ul>
                <li style="width:100%!important;" id="sidebar-view" data-url="<?php echo Yii::$app->urlManager->createUrl(['/site/vps/index', 'id' => $vps->id]);?>"><a href="javascript:void()"><img src="<?php echo $bundle->baseUrl;?>/img/info.png" width="20"> View <?php echo Html::encode($vps->ip ? $vps->ip->ip : 'NONE');?></a></li>
                <li class="vps-start" data-id="<?php echo $vps->id;?>"><a href="javascript:void()"><img src="<?php echo $bundle->baseUrl;?>/img/start.png" width="20"> Start</a></li>
                <li class="vps-stop" data-id="<?php echo $vps->id;?>"><a href="javascript:void()"><img src="<?php echo $bundle->baseUrl;?>/img/stop.png" width="20"> Stop</a></li>
                <li class="vps-restart" data-id="<?php echo $vps->id;?>"><a href="javascript:void()"><img src="<?php echo $bundle->baseUrl;?>/img/restart.png" width="20"> Restart</a></li>
            </ul>
        </li>
        <?php }?>
    </ul>
</div>
<?php }?>
