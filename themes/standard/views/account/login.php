<?php 
include($this->theme_file('layouts/site_header.php'));

if($this->post)
{
?>

<div style="width: 500px; text-align: center; margin: 0 auto; font-size: 16pt;">
<?php
if($error)
{
	echo '<div style="padding: 70px; color: #900;">' . $error_description . '</div>';
}
else
{
	echo '<div style="padding: 70px;">';
	echo '<p>Logged in as: ' . $username . '</p>';
	echo '<p><a href="/' . $username . '">your map</a></p>';
	echo '</div>';
}
?>
</div>

<?php
} else {

	$last_location = FALSE;
?>

<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
<td valign="top">

	<div style="float: right; margin-top: 10px; margin-right: 10px;">
		<a href="/connect/twitter"><img src="<?=$theme_root?>images/sign-in-with-twitter-l.png" /></a>
	</div>
	
	<form action="/account/login" method="post">
	<div class="settings" style="margin-top: 60px;">
	<table class="form">
		<tr>
			<td class="left"><div class="label">Email</div></td>
			<td class="right"><input type="text" name="username" id="username" class="text" /></td>
		</tr>
		<tr>
			<td class="left"><div class="label">Password</div></td>
			<td class="right"><input type="password" name="password" id="password" class="text" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" class="btn btn-ok" value="Log In" /></td>
		</tr>
	</table>
	</div>
	</form>

</td>
<td width="340" id="right_panel" valign="top">

	<h2 style="text-align:center;">Download the app!</h2>
	<table style="margin-bottom: 20px;">
	<tr>
		<td><a href="http://itunes.apple.com/us/app/geoloqi/id415603875"><img src="<?=$theme_root?>images/geoloqi_available_on_the_app_store.png" width="150" /></a></td>
		<td style="font-size: 8pt; padding-left: 15px;">Available for iPhone 3GS and iPhone 4!</td>
	</tr>
	<tr>
		<td><br /></td>
	</tr>
	<tr>
		<td>
			<a href="http://geoloqi.com/help/76/how-do-i-use-geoloqi-with-my-palm-phone" style="margin: 8px;"><img src="<?=$theme_root?>images/palm-icon-small.png" /></a>
			<a href="http://geoloqi.com/help/74/how-do-i-use-geoloqi-with-my-android-phone" style="margin: 8px;"><img src="<?=$theme_root?>images/android-logo-40px.png" width="47" /></a>
			<br />
			<br />
			<a href="http://geoloqi.com/help/78/how-do-i-use-geoloqi-with-my-blackberry" style="margin: 8px;"><img src="<?=$theme_root?>images/blackberry-logo-small.png" /></a>
			<a href="http://geoloqi.com/help/80/how-do-i-use-geoloqi-with-my-boost-mobile-phone" style="margin: 8px;"><img src="<?=$theme_root?>images/boost-mobile-icon-small.png" /></a>
		</td>
		<td style="font-size: 8pt; padding-left: 15px;">
			The Android and Palm versions are currently in development.<br />
			<br />
			In the meantime, you can download <a href="http://geoloqi.com/help/category/apps">Instamapper</a>.
		</td>
	</tr>
	</table>

</td>

</tr></table>


<?php
}

include($this->theme_file('layouts/site_footer.php'));
?>