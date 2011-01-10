<?php 
$this->head[] = '<link rel="stylesheet" type="text/css" href="' . $theme_root . 'authorize.css"></script>';
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'passwordStrengthMeter.js"></script>';
?>
<div id="authorize-page">

<div id="loqisaur-logo"></div>

<div id="authorize-wrap">
<div id="authorize" class="round"><div class="in">

	<div style="padding-bottom: 20px;" class="settings settings-columns">

<?php if(isset($error) && $error) { ?>

	<div style="margin: 10px;" class="error-message"><?=$error_description?></div>

	<?php if($error == 'key_not_found') { ?>
		<div style="margin: 10px;">You might have already confirmed your email address! Try <a href="/account/login">logging in</a>.</div>
	<?php } ?>
	
	<?php include($this->theme_file('layouts/error_contact.php')); ?>

<?php } else { ?>		
		<div style="text-align: center; width: 400px; margin: 20px auto;">
			<h2>Account Setup</h2>
			Thanks for signing up with Geoloqi!<br /><br />
			To finish setting up your account, please create a password so you can log back in on your phone if you need to.
		</div>
		
		<form action="/account/setup" method="post">
		<table style="width: 400px; margin: 0 auto;"><tbody>
		<tr>
			<td class="left"><div class="label small">Create a Password</div></td>
			<td class="right">
				<input id="password1" name="password1" type="password" class="text" />
				<div id="password1-response"></div>
			</td>
		</tr>
		<tr>
			<td class="left"><div class="label small">Confirm Password</div></td>
			<td class="right">
				<input id="password2" name="password2" type="password" class="text" />
				<div id="password2-response"></div>
			</td>
		</tr>
		<tr>
			<td class="left"><div class="label small">Phone Number</div></td>
			<td><input id="phone" name="phone" type="text" class="text" /></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" name="accept" class="btn btn-ok" value="Finish" id="submitBtn" />
			</td>
		</tr>
		</tbody></table>
		<input type="hidden" name="key" value="<?=$key?>" />
		</form>
<?php } ?>

	</div><!-- settings -->

</div></div><!-- in, authorize -->
</div><!-- authorize-wrap -->

</div><!-- authorize-page -->

<script type="text/javascript">
$(function(){
	$("#password1").bind("keyup", function(){
		var strength = passwordStrength($("#password1").val(), "");
		$("#password1").css({borderColor: scoreToColor(strength.score)});
		$("#password1-response").text(strength.msg);
	});
	
	$("#password2").bind("keyup", function(){
		if( $("#password1").val() == $("#password2").val() && $("#password2").val() != "" ){
			$("#password2").css({borderColor: "#009500"});
			$("#password2-response").text("");
		}else{
			$("#password2").css({borderColor: "#C30000"});
			$("#password2-response").text("Passwords don't match");
		}
	});
});
function scoreToColor(score){
	if(score < 20){
		return "#C30000";
	}else if(score < 30) {
		return "#F07800";
	}else if(score < 40) {
		return "#FAC500";
	}else if(score < 50) {
		return "#F3E700";
	}else if(score < 60) {
		return "#D7E800";
	}else if(score < 70) {
		return "#D7E800";
	}else if(score < 80) {
		return "#4AAE00";
	}else if(score < 90) {
		return "#4AAE00";
	}else{
		return "#009600";
	}
}
</script>