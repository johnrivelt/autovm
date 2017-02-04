<?php use yii\helpers\Html;?>
<!-- content -->
<div class="content">
    <div class="col-md-4">
        <div class="stat-box stat-box-yellow">
            <h3>total virtual servers</h3>
            <span><?php echo $stats->totalVps;?></span>
            <i class="fa fa-laptop"></i>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="stat-box stat-box-blue">
            <h3>total users</h3>
            <span><?php echo $stats->totalUsers;?></span>
            <i class="fa fa-smile-o"></i>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="stat-box stat-box-green">
            <h3>used bandwidth</h3>
            <span><?php echo number_format($stats->bandwidth);?> KB</span>
            <i class="fa fa-clone"></i>
        </div>
    </div>
                    
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <th>ID</th>
                <th>Virtual server</th>
                <th>Action</th>
                <th>Description</th>
                <th>Created at</th>
            </thead>
            <tbody>
            <?php foreach($stats->vpsActions as $action) {?>
                <tr>
                    <td><?php echo $action->id;?></td>
                    <td><?php echo Html::encode($action->vps->ip ? $action->vps->ip->ip : ' NO IP');?></td>
                    <td>
                    <?php if($action->getIsStart()) {?>
                        Start
                    <?php } elseif ($action->getIsStop()) {?>
                        Stop
                    <?php } elseif ($action->getIsRestart()) {?>
                        Restart
                    <?php } elseif ($action->getIsInstall()) {?>
                        Install
                    <?php }?>
                    </td>
                    <td><?php echo ($action->description ? Html::encode($action->description) : 'NONE');?></td>
                    <td><?php echo date('d M Y - H:i', $action->created_at);?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
<!-- END content -->