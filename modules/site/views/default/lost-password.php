<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

Yii::$app->setting->title .= ' - lost password';

$template = '{input}{error}';

?>
<div class="content">
    <div class="container">
        <div class="col-md-4 col-md-offset-4">
            <div class="title">
                <h3>Lost Password <p>Forgot your password ? do not worry about that</p></h3>
            </div>
            <?php echo \app\widgets\Alert::widget();?>
            <?php $form = ActiveForm::begin(['fieldConfig' => ['template' => $template]]);?>
                <?php echo $form->field($model, 'email')->textInput(['placeholder' => 'Email']);?>
                <?php echo $form->field($model, 'verifyCode')->textInput(['placeholder' => 'Verify Code'])->widget(\yii\captcha\Captcha::className(), ['template' => '<div class="row"><div class="col-lg-8">{input}</div><div class="col-md-3">{image}</div></div>', 'captchaAction' => '/site/default/captcha'])->label(false); ?>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Get Password</button>
                </div>
            <?php ActiveForm::end();?>
        </div>
    </div>
</div>

