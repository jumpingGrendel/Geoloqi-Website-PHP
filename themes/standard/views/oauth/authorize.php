<?php 
$this->head[] = '<link rel="stylesheet" type="text/css" href="' . $theme_root . 'authorize.css"></script>';
?>
<div id="authorize-page">

<div id="geoloqi-logo"></div>

<div id="authorize-wrap">
<div id="authorize" class="round"><div class="in">

	<h2>An application would like to connect to your account</h2>

	<p>The application <?=$application_name?> by <?=$requester_name?> would like to connect to your Geoloqi account.</p>

	<ul>
	<?php 
	foreach($scopes as $scope)
		echo '<li><input type="checkbox" name="scope[]" value="' . $scope['scope'] . '" />' . str_replace('%name', $application_name, $scope['description']) . '</li>';
	?>
	</ul>
	

	<div style="text-align: center;">
		<h2>Allow <?=$application_name?> access?</h2>
	</div>
	<div style="text-align: center;">
		<input type="submit" name="deny" class="btn btn-cancel" value="Deny" />
		<input type="submit" name="allow" class="btn btn-ok" value="Allow" />
	</div>

</div></div>
</div>

</div>
