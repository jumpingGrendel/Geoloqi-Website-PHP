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
?>

<div style="float: right; margin-top: 30px; margin-right: 80px;">
	<a href="/connect/twitter"><img src="<?=$theme_root?>images/log-in-with-twitter-button.png" /></a>
</div>

<form action="/account/login" method="post">
<div class="settings">
<table class="form">
	<tr>
		<td><div class="label">Username</div></td>
		<td><input type="text" name="username" id="username" class="field" /></td>
	</tr>
	<tr>
		<td><div class="label">Password</div></td>
		<td><input type="password" name="password" id="password" class="field" /></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" class="submit" value="Log In" /></td>
	</tr>
</table>
</div>
</form>

<?php
}

include($this->theme_file('layouts/site_footer.php'));
?>