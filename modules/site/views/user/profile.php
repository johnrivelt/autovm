<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

Yii::$app->setting->title .= ' - profile informations';

$template = '{input}{error}';

?>
<div class="content">
    <div class="container">
        <div class="col-md-4 col-md-offset-4">
            <div class="title">
                <h3>Profile <p>You can change your profile informations</p></h3>
            </div>
            <?php echo \app\widgets\Alert::widget();?>
            <?php $form = ActiveForm::begin(['fieldConfig' => ['template' => $template]]);?>
                <?php echo $form->field($model, 'first_name')->textInput(['placeholder' => 'First Name']);?>
                <?php echo $form->field($model, 'last_name')->textInput(['placeholder' => 'Last Name']);?>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            <?php ActiveForm::end();?>
        </div>
    </div>
</div>