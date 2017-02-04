<?php
use yii\helpers\Html;

$bundle = \app\modules\site\assets\Asset::register($this);

Yii::$app->setting->title .= ' - control virtual server';

$this->registerCssFile($bundle->baseUrl . '/js/plugins/morris/morris.css');
$this->registerJsFile($bundle->baseUrl . '/js/raphael.js', ['depends' => '\app\modules\site\assets\Asset']);
$this->registerJsFile($bundle->baseUrl . '/js/plugins/morris/morris.js', ['depends' => '\app\modules\site\assets\Asset']);

$this->registerJs("
    var vpsId = " . $vps->id . ";

    $(document).ready(function () {
    \"use strict\";
    
});

    $.ajax({
        url:baseUrl + '/site/vps/bandwidth',
        type:'POST',
        dataType:'JSON',
        data:{vpsId:vpsId},
        success:function(data){
            Morris.Line({
                element: 'chart',
                data: data,
                xkey: 'date',
                ykeys: ['total'],
                labels: ['Bandwidth KB'],
                smooth:false,
                lineWidth:2,
                lineColors: ['#189C7E'],
            });
        }
    });
");

?>

<style type="text/css">
.view-vps-table{
    box-shadow:none;
}
</style>
<?= Yii::$app->session->set('username', $vps->server->username); ?>
<div class="content" style="margin-top:4%">
    <div class="container">
        <div class="table-responsive">
            <table class="table table-bordered view-vps-table">
                <thead>
                    <th>IP</th>
                    <th>Server</th>
                    <th>Operating system</th>
                    <th>RAM</th>
                    <th>CPU Cores</th>
                    <th>CPU Mhz</th>
                    <th>Hard</th>
                    <th>Bandwidth</th>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo Html::encode(isset($vps->ip->ip)?$vps->ip->ip:'');?></td>
                        <td><?php echo Html::encode(isset($vps->server)?$vps->server->name:'');?></td>
                        <td><?php echo Html::encode(isset($vps->os->name)?$vps->os->name:'');?></td>
                        <td><?php
                            //var_dump($vps);exit;
                            if($vps->plan_type==VpsPlansTypeDefault) {
                                echo $vps->plan->ram;
                            }
                            else {
                                echo $vps->vps_ram;
                            }
                            ?>
                        <td>
                            <?php
                            //var_dump($vps);exit;
                            if($vps->plan_type==VpsPlansTypeDefault) {
                                echo $vps->plan->cpu_core;
                            }
                            else {
                                echo $vps->vps_cpu_core;
                            }
                            ?> Core</td>
                        <td><?php
                            //var_dump($vps);exit;
                            if($vps->plan_type==VpsPlansTypeDefault) {
                                echo $vps->plan->cpu_mhz;
                            }
                            else {
                                echo $vps->vps_cpu_mhz;
                            }
                            ?> MHZ</td>
                        <td><?php
                            //var_dump($vps);exit;
                            if($vps->plan_type==VpsPlansTypeDefault) {
                                echo $vps->plan->hard;
                            }
                            else {
                                echo $vps->vps_hard;
                            }
                            ?> GB</td>
                        <td>
                            <?php
                            if($vps->plan_type==VpsPlansTypeDefault)
                                echo number_format($used_bandwidth/1024/1024/1024, 3) .' /'. number_format($vps->plan->band_width/1024, 3);
                            else
                                echo number_format($used_bandwidth/1024/1024/1024, 3) .' / '. number_format($vps->vps_band_width/1024, 3);?>
                            GB
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered view-vps-table text-center">
                <tbody>
                    <tr>
                        <td><a href="javascript:void()" class="vps-change-os" data-id="<?php echo $vps->id;?>"><img src="<?php echo $bundle->baseUrl;?>/img/install.png" width="30"> <br>Change Operating System</a></td>
                        <td><a href="javascript:void()" class="vps-restart" data-id="<?php echo $vps->id;?>"><img src="<?php echo $bundle->baseUrl;?>/img/restart.png" width="30"> <br>Restart</a></td>
                        <td><a href="javascript:void()" class="vps-stop" data-id="<?php echo $vps->id;?>"><img src="<?php echo $bundle->baseUrl;?>/img/stop.png" width="30"> <br> Stop</a></td>
                        <td><a href="javascript:void()" class="vps-start" data-id="<?php echo $vps->id;?>"><img src="<?php echo $bundle->baseUrl;?>/img/start.png" width="30"> <br> Start</a></td>
                        <td><a href="javascript:void()" class="vps-status" data-id="<?php echo $vps->id;?>"><img src="<?php echo $bundle->baseUrl;?>/img/status.png" width="30"> <br> VPS Status</a></td>
                        <td><a href="javascript:void()" class="vps-monitor" data-id="<?php echo $vps->id;?>"><img src="<?php echo $bundle->baseUrl;?>/img/monitor.png" width="40"> <br> Monitor</a></td>
                        <!--<td><a href="javascript:void()"><img src="<?php echo $bundle->baseUrl;?>/img/console.png" width="30"> <br> Console</a></td>-->
                        <td><a href="javascript:void()" class="vps-action-log" data-id="<?php echo $vps->id;?>"><img src="<?php echo $bundle->baseUrl;?>/img/log.png" width="30"> <br> Action Logs</a></td>
                    </tr>
                </tbody>
            </table>

        </div>
        <div id="chart" style="float:left;width:100%;height:250px;"></div>
    </div>
</div>
