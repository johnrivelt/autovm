<?php use yii\helpers\Html;?>
<style type="text/css">
.table td{text-align:left;padding-left:10px!important;}
</style>
<!-- content -->
<div class="content">     
    <div class="col-md-6 pull-left">
		<table class="table table-bordered">

				<tr>
					<td width="150">ID</td><td><?php echo $vps->id;?></td>
				</tr>
				<tr>
					<td>Server</td><td><a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/server/edit', 'id' => isset($vps->server->id)?$vps->server->id:'']);?>"><?php echo Html::encode(isset($vps->server->name)?$vps->server->name:'');?></a></td>
				</tr>
				<tr>
					<td>Ip</td><td><?php echo Html::encode(isset($vps->ip->ip)?$vps->ip->ip:'');?></td>
				</tr>
				<tr>
					<td>Operation System</td><td><?php echo Html::encode(isset($vps->os->name)?$vps->os->name:'');?></td>
				</tr>
				<tr>
					<td>Plan</td><td><a href="<?php echo isset($vps->plan)? Yii::$app->urlManager->createUrl(['/admin/plan/edit', 'id' => $vps->plan->id]):'' ; ?>"><?php echo isset($vps->plan)?Html::encode($vps->plan->name):'';?></a></td>
				</tr>
				<tr>
					<td>Logs</td><td><a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/log', 'id' => $vps->id]);?>"><i class="fa fa-search"></i></a></td>
				</tr>
				<tr>
					<td>Status</td><td><?php echo ($vps->getIsActive() ? ' Active' : ' Inactive');?></td>
				</tr>
				<tr>
					<td>Created At</td><td><?php echo date('d M Y - H:i', $vps->created_at);?></td>
				</tr>
				<tr>
					<td>Updated At</td><td><?php echo date('d M Y - H:i', $vps->updated_at);?></td>
				</tr>
			</body>
		</table>
    </div>
	<div class="col-md-6 pull-left">
		<div style="margin-top:30px;">
			<p><?php
    if($vps->plan_type==VpsPlansTypeDefault)
     echo number_format($used_bandwidth/1024/1024/1024, 3) .' / '. number_format($vps->plan->band_width/1024, 3);
    else
     echo number_format($used_bandwidth/1024/1024/1024, 3) .' /'. number_format($vps->vps_band_width/1024, 3);
    ?>


    GB</p>
			<a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/reset-bandwidth', 'id' => $vps->id]);?>" class="btn btn-success">Reset bandwidth</a>
		</div>
	</div>
</div>
<!-- END content -->
