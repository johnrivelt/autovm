<?php 
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
?>

<!-- content -->
<div class="content">     
    <div class="col-md-12">
        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/ip/delete'));?>
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/server/index');?>" class="btn btn-info"><i class="fa fa-plus"></i>Create new</a>
        <button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i>Delete selected items</button>
        <br><br><hr>
        <?php 

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax'=>true,
                'columns' => [
                    [
                        'label' => 'Select',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<label class="checkbox"><input type="checkbox" name="data[]" value="' . $model->id . '"><span></span></label>';
                        }
                    ],
                    [
                        'attribute' => 'server_id',
                        'label' => 'Server',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->server->name;
                        }
                    ],
                    'ip',
                    'gateway',
                    'netmask',
                    [
                        'label' => 'edit',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return "<input type='text' class='mac' value='".$model->mac_address."' data=".$model->id." >";
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
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/ip/edit', 'id' => $model->id]) . '"><i class="fa fa-edit"></i></a>';
                        }
                    ],
                ],
                'export'=>false
            ]);

        ?>
        <?php echo Html::endForm();?>
    </div>
</div>
<script>
    $(function() {
    $( ".mac" ).change(function() {
        var value=$(this).val();
        var id=$(this).attr('data');
        $.post( "<?=\yii\helpers\Url::to(['ip/index']) ?>", { id: id, value: value })
            .done(function( data ) {
                if(data==1)
                {
                    new simpleAlert({title:"Action Status", content:"data saved"});
                }
            });
    });
    });
</script>
<!-- END content -->