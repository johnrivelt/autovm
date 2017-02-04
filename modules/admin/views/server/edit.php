<?php use yii\widgets\ActiveForm;?>
<!-- content -->
<div class="content">     
    <div class="col-md-6">
        <?php echo \app\widgets\Alert::widget();?>
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($model, 'name');?>
            <?php echo $form->field($model, 'ip');?>
            <?php echo $form->field($model, 'port');?>
            <?php echo $form->field($model, 'username');?>
            <?php echo $form->field($model, 'password')->passwordInput();?>
			<?php echo $form->field($model, 'license');?>

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