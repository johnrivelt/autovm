<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
?>
<!-- content -->
<div class="content">     
    <div class="col-md-12">
        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/user/delete'));?>
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/user/create');?>" class="btn btn-info"><i class="fa fa-plus"></i>Create new</a>
        <button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i>Delete selected items</button>
        <br><br><hr>
        <?php 
        Pjax::begin(['id' => 'pjax', 'enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label' => 'Select',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<label class="checkbox"><input type="checkbox" name="data[]" value="' . $model->id . '"><span></span></label>';
                        }
                    ],
                    'first_name',
                    'last_name',
                    [
                        'attribute' => 'email',
                        'value' => 'primaryEmail.email'
                    ],
                    [
                        'label' => 'Action',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' .Yii::$app->urlManager->createUrl(['/admin/vps/create', 'id' => $model->id]) . '">Create vps</a>';
                        }
                    ],
                    [
                        'label' => 'Virtual Servers',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/user/vps', 'id' => $model->id]) . '"><i class="fa fa-tv"></i></a>';
                        }
                    ],
                    [
                        'label' => 'edit',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/user/edit', 'id' => $model->id]) . '"><i class="fa fa-edit"></i></a>';
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