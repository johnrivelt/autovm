<?php
use yii\helpers\Html;

Yii::$app->setting->title .= ' - virtual servers';
?>

<div class="content">
    <div class="container">
        <h3 class="title">Virtual Servers <p>List of your active virtual servers</p></h3>
        <?php echo \app\widgets\Alert::widget();?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <th>ID</th>
                    <th>Ip</th>
                    <th>Server</th>
                    <th>Operation system</th>
                    <th>Ram</th>
                    <th>Cpu cores</th>
                    <th>Cpu mhz</th>
                    <th>Hard</th>
                    <th>Bandwidth</th>
                    <th>Created at</th>
                    <th>Control</th>
                </thead>
                <tbody>
                    <?php foreach($virtualServers as $vps) {?>
                        <tr>
                            <td><?php echo $vps->id;?></td>
                            <td><?php echo Html::encode($vps->ip ? $vps->ip->ip : 'NONE');?></td>
                            <td><?php echo Html::encode(isset($vps->server->name)?$vps->server->name:'');?></td>
                            <td><?php echo Html::encode(isset($vps->os->name)?$vps->os->name:'');?></td>
                            <td><?php
                                if($vps->plan_type==VpsPlansTypeDefault)
                                    echo $vps->plan->ram;
                                else
                                    echo $vps->vps_ram;
                                ?> MB</td>
                            <td><?php
                                if($vps->plan_type==VpsPlansTypeCustom)
                                    echo $vps->vps_cpu_core;
                                else
                                    echo $vps->plan->cpu_core;
                                ?> Core</td>
                            <td><?php
                                if($vps->plan_type==VpsPlansTypeCustom)
                                    echo $vps->vps_cpu_mhz;
                                else
                                    echo $vps->plan->cpu_mhz;
                                ?> MHZ</td>
                            <td><?php
                                if($vps->plan_type==VpsPlansTypeCustom)
                                    echo $vps->vps_hard;
                                else
                                    echo $vps->plan->hard;
                                ?> GB</td>
                            <td><?php
                                if($vps->plan_type==VpsPlansTypeCustom)
                                    echo $vps->vps_band_width/1024;
                                else
                                    echo $vps->plan->band_width/1024;
                                ?> GB
                            <td><?php echo date('d M Y - H:i', $vps->created_at);?></td>
                            <td>
                                <a href="<?php echo Yii::$app->urlManager->createUrl(['/site/vps/index', 'id' => $vps->id]);?>" class="btn btn-success" style="margin-right:5px;"><i class="fa fa-search"></i></a>
                                <a href="javascript:void()" data-id="<?php echo $vps->id;?>" class="btn btn-success vps-start" style="margin-right:5px;"><i class="fa fa-play"></i></a>
                                <a href="javascript:void()" data-id="<?php echo $vps->id;?>" class="btn btn-success vps-stop"><i class="fa fa-pause"></i></a>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        <?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]);?>
    </div>
</div>
