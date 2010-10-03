<div id="header-bar" class="round"><div class="inner">
<?php
if($logged_in)
{
?>
	<ul>
		<li><a href="/">Home</a></li>
		<li><a href="/<?=$username?>">Map</a></li>
		<li><a href="/settings">Settings</a></li>
		<li><a href="/help">Help</a></li>
		<li><a href="/account/logout">Sign Out</a></li>
	</ul>
	<div style="clear:both"></div>
<?php
}
else
{
	// <div class="signed-out">Have an account? <a href="/account/login">Sign in</a> or <a href="/account/signup">Join Now</a></div>
?>
	<div class="signed-out">Have an account? <a href="/connect/twitter">Sign in</a> or <a href="/connect/twitter">Join Now</a></div>
<?php
}
?>
	<div style="clear:both;"></div>
</div></div>