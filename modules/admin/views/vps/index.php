<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
?>
<!-- content -->
<div class="content">     
    <div class="col-md-12">
        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/vps/delete'));?>
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/user/index');?>" class="btn btn-info"><i class="fa fa-plus"></i>Create new</a>
        <button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i>Delete selected items</button>
        <br><br><hr>
        <?php 
        Pjax::begin(['id' => 'pjax', 'enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'label' => 'Select',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<label class="checkbox"><input type="checkbox" name="data[]" value="' . $model->id . '"><span></span></label>';
                        }
                    ],
                    [
                        'label' => 'Server',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/server/edit', 'id' => isset($model->server->id)?$model->server->id:'']) . '">' . Html::encode(isset($model->server->name)?$model->server->name:'') . '</a>';
                        }
                    ],
                    [
                        'attribute' => 'ip',
                        'label' => 'Ip Address',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->ip ? Html::encode($model->ip->ip) : ' No IP');
                        }
                    ],
                    [
                        'attribute' => 'email',
                        'label' => 'User Email',
                        'value' => 'user.primaryEmail.email',
                    ],
                    [
                        'label' => 'Operation System',
                        'value' => 'os.name',
                    ],
                    [
                        'label' => 'plan',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if(isset($model->plan->id))
                                return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/plan/edit', 'id' => $model->plan->id]) . '">' . Html::encode($model->plan->name) . '</a>';
                            else
                                return '';
                        }
                    ],
                    [
                        'label' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->getIsActive() ? '<b class="text-success"> Active</b>' : '<b class="text-danger"> Inactive</b>');
                        }
                    ],
                    [
                        'label' => 'edit',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/vps/edit', 'id' => $model->id]) . '"><i class="fa fa-edit"></i></a>';
                        }
                    ],
                    [
                        'label' => 'view',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/vps/view', 'id' => $model->id]) . '"><i class="fa fa-search"></i></a>';
                        }
                    ],
                ],
            ]);
        Pjax::end();
        ?>
        <?php echo Html::endForm();?>
    </div>
</div>
<!-- END content -->