<?php 
include($this->theme_file('layouts/site_header.php'));
?>

<div style="padding: 20px; text-align: center;" class="settings"><div class="in">
<?php
if(isset($error))
{
	echo '<p>' . $error_description . '</p>';
	pa($api_response);
	pa($_SESSION);
}
elseif(isset($confirmation))
{
?>
	<p style="font-size: 1.2em; font-weight: bold;">You have unsubscribed</p>
	<p>You will stop receiving emails from us. If you would like to receive emails again,<br />
		<a href="/connect/twitter">log in</a> and update your notification settings.</p>
<?php
}
else
{
?>
	<p style="font-size: 1.2em; font-weight: bold;">Want to stop getting emails?</p>
	<p>If you have a Geoloqi.com account, please <a href="/connect/twitter">log in</a> here and update your notification settings!</p>
	<p>If you don't have a Geoloqi.com account, enter your email address<br />below and we won't send you any more emails.</p>

	<form action="/account/unsubscribe" method="post">
		<p><input type="text" id="unsubscribe_email" name="unsubscribe_email" style="width: 220px;" title="email address" value="<?=$email?>" /></p>
		<p><input type="submit" value="Submit" id="unsubscribe_submit" class="submit" /></p>
	</form>
<?php 
}
?>
</div></div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>
