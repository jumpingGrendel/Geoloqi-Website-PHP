<?php 
$this->head[] = '<link rel="stylesheet" type="text/css" href="' . $theme_root . 'authorize.css"></script>';
?>
<div id="authorize-page">

<div id="geoloqi-logo"></div>

<div id="authorize-wrap">
<div id="authorize" class="round"><div class="in">

<form action="/oauth/authorize" method="post">

	<h2>An application would like to connect to your account</h2>

	<p>The application <b><?=$application_name?></b> by <?=$requester_name?> would like to connect to your Geoloqi account.</p>
	<p><b><?=$application_name?> wants to:</b></p>
	
	<ul>
	<?php 
	foreach($scopes as $scope)
		echo '<li>' . str_replace('%name', $application_name, $scope['description']) . '</li>';
	//	echo '<li><input type="checkbox" name="scope[]" value="' . $scope['scope'] . '" />' . str_replace('%name', $application_name, $scope['description']) . '</li>';
	?>
	</ul>
	
	<?php 
	if($logged_in){
	?>	
		<div style="text-align: center;">
			<h3>Allow <?=$application_name?> access?</h3>
		</div>
		<div style="text-align: center;">
			<input type="submit" name="accept" class="btn btn-cancel" value="Deny" />
			<input type="submit" name="accept" class="btn btn-ok" value="Allow" />
		</div>
	<?php 
	}else{
	?>
		<div style="text-align: center;">
			<input type="submit" name="accept" class="btn btn-ok" value="Log In" />
		</div>
	<?php 
	}
	?>

	<?php foreach($auth_params as $k=>$v) { ?>
		<input type="hidden" name="<?= $k ?>" value="<?= $v ?>" />
	<?php } ?>
</form>

</div></div>
</div>

</div>
