<?php 
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
?>
<!-- content -->
<div class="content">     
    <div class="col-md-12">
    	<?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/plan/delete'));?>
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/plan/create');?>" class="btn btn-info"><i class="fa fa-plus"></i>Create new</a>
        <button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i>Delete selected items</button>
        <br><br><hr>
        <?php 
        Pjax::begin(['id' => 'pjax', 'enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'label' => 'Select',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<label class="checkbox"><input type="checkbox" name="data[]" value="' . $model->id . '"><span></span></label>';
                        }
                    ],
                    'id',
                    'name',
                    'ram',
                    'cpu_mhz',
                    'cpu_core',
                    'hard',
                    [
                        'attribute' => 'band_width',
                        'label' => 'BandWidth (MB)',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->band_width);
                        }
                    ],
                    [
                        'attribute' => 'is_public',
                        'label' => 'Is Public',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->getIsPublic() ? '<b class="text-success">Yes</b>' : '<b class="text-danger">No</b>');
                        }
                    ],
                    [
                        'label' => 'edit',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/plan/edit', 'id' => $model->id]) . '"><i class="fa fa-edit"></i></a>';
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