<?php use yii\widgets\ActiveForm;?>
<!-- content -->
<div class="content">     
    <div class="col-md-6">
        <?php echo \app\widgets\Alert::widget();?>
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($model, 'name');?>
            <?php echo $form->field($model, 'ram');?>
            <?php echo $form->field($model, 'cpu_mhz');?>
            <?php echo $form->field($model, 'cpu_core');?>
            <?php echo $form->field($model, 'hard');?>
            <?php echo $form->field($model, 'band_width');?>
            <?php echo $form->field($model, 'is_public')->dropDownList(\app\models\Plan::getPublicYesNo());?>

            <div class="margin-top-10"></div>
            <div class="margin-top-10"></div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-success">Submit form</button>
                <button type="reset" class="btn btn-danger">Reset form</button>
            </div>
        <?php ActiveForm::end();?>
    </div>
</div>
<!-- END content -->