<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

Yii::$app->setting->title .= ' - login';

$template = '{input}{error}';

?>
<div class="content">
    <div class="container">
        <div class="col-md-4 col-md-offset-4">
            <div class="title">
                <h3>Login Form <p>Please login to your account</p></h3>
            </div>
            <?php echo \app\widgets\Alert::widget();?>
            <?php $form = ActiveForm::begin(['fieldConfig' => ['template' => $template]]);?>
                <?php echo $form->field($model, 'email')->textInput(['placeholder' => 'Email']);?>
                <?php echo $form->field($model, 'password')->passwordInput(['placeholder' => 'Password']);?>
                <div class="form-group">
                    <label class="checkbox"><input type="checkbox" name="LoginForm[rememberMe]" value="1"> <span></span> Remember me next time</label>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Login</button>
                </div>
            <?php ActiveForm::end();?>
        </div>
    </div>
</div>