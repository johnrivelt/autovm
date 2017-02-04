<?php
use yii\helpers\Html;

$this->registerJs('
function passwordStrength(password)
{
	var desc = new Array();
	desc[0] = "Very Weak";
	desc[1] = "Weak";
	desc[2] = "Better";
	desc[3] = "Medium";
	desc[4] = "Strong";
	desc[5] = "Strongest";
	var score   = 0;
	//if password bigger than 6 give 1 point
	if (password.length >= 6) score++;
	//if password has both lower and uppercase characters give 1 point	
	if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;
	//if password has at least one number give 1 point
	if (password.match(/\d+/)) score++;
	//if password has at least one special caracther give 1 point
	if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )	score++;
	//if password bigger than 12 give another 1 point
	if (password.length >= 8) score++;
	return desc[score];	 
}
function randomPassword(length) {
    var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYX1234567890";
    var pass = "";
    for (var x = 0; x < length; x++) {
        var i = Math.floor(Math.random() * chars.length);
        pass += chars.charAt(i);
    }
    return pass;
}
(function ($) {
    $.toggleShowPassword = function (options) {
        var settings = $.extend({
            field: "#password",
            control: "#toggle_show_password",
        }, options);

        var control = $(settings.control);
        var field = $(settings.field)

        control.bind(\'click\', function () {
            if (control.is(\':checked\')) {
                field.attr(\'type\', \'text\');
            } else {
                field.attr(\'type\', \'password\');
            }
        })
    };
}(jQuery));

$(document).ready(function() {
$(".strength").keyup(function(e){
if($(this).val().match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ){
alert("Only use [a-z],[A-Z],[0-9]!");
$(this).val("");
}
 $(".pwstrength_viewport").html(passwordStrength($(this).val()));
});
$(".btn-red").click(function(e){
 $(".strength").val(randomPassword(10));
 $(".pwstrength_viewport").html(passwordStrength($(".strength").val()));
});
$.toggleShowPassword({
    field: ".strength",
    control: "#showpassword"
});
    $("#install").click(function(e){
        var password = $("#password").val();

        if(password == "") {
            new simpleAlert({title:"Error", content:"Please try again and enter password"});
            return false;
        }

        var vpsId = ' . $vpsId . ';
        var osId = $("input[name=osId]:checked").val();

        if(osId == "" || osId == undefined) {
            new simpleAlert({title:"Error", content:"Please try again and select an operation system"});
            return false;
        }

        $.ajax({
            type:"POST",
            dataType:"JSON",
            url:"' . Yii::$app->urlManager->createUrl('/site/vps/install') . '",
            data:{password:password, vpsId:vpsId, osId:osId},
            success:function(data){
                if(data.status != 0) {
                    new simpleAlert({title:"Action Status", content:"Your selected operation system was successfuly installed <br /><br /><br /> Username:'.Yii::$app->session->get('username').'<br /> Password:"+data.status});
                }
               else if(data.status == 2) {
                    new simpleAlert({title:"Action Status", content:"Your password is not Valid"});
                }
                else {
                    new simpleAlert({title:"Action Status", content:"There is an error, please try again"});
                }
            },
            beforeSend:function() {
                new simpleAlert({title:"Installing", content:"Please wait, we are installing your selected operation system"});
            }
        });
    });
});
');

?>

<style type="text/css">
    .select-os-table {
        box-shadow: none !important;
        border: 0 !important;
    }

    .select-os-table td {
        border: 0 !important;
    }
</style>

<table class="table select-os-table">
    <tbody>
    <tr>
        <td>
            <div class="row">
                <?php foreach ($operationSystems as $os) { ?>
                    <div class="col-md-6">
                        <label class="checkbox"><input type="radio" name="osId" value="<?php echo $os->id; ?>">
                            <span></span> <?php echo Html::encode($os->name); ?></label>
                    </div>
                <?php } ?>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <input type="password" name="password" id="password" class="form-control strength" placeholder="Password">
            <input id="showpassword" type="checkbox"/>Show password
            <span><small>use [A-Z, a-z, 0-9]</small></span>
            <button class="label btn-red">Random</button>
            <div class="pwstrength_viewport"></div>

        </td>
    </tr>
    <tr>
        <td>
            <button type="button" id="install" class="btn btn-success">Install</button>
        </td>
    </tr>
    </tbody>
</table>