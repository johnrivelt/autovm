<?php use yii\widgets\ActiveForm;?>
<!-- content -->
<div class="content">     
    <div class="col-md-6">
        <?php echo \app\widgets\Alert::widget();?>
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($model, 'ip')->label('From :');?>
            <?php echo $form->field($model, 'to')->label('To :');?>
            <?php echo $form->field($model, 'gateway');?>
            <?php echo $form->field($model, 'netmask');?>
            <?php echo $form->field($model, 'mac_address');?>
            <?php echo $form->field($model, 'is_public')->dropDownList(\app\models\Ip::getPublicYesNo());?>

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