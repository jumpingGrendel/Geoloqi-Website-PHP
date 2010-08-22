<div id="header-bar" class="round"><div class="inner">
<?php
if($logged_in)
{
?>
<ul>
	<li><a href="/">Home</a></li>
	<li><a href="/<?=$username?>">Profile</a></li>
	<li><a href="/settings">Settings</a></li>
	<li><a href="/help">Help</a></li>
	<li><a href="/account/logout">Sign Out</a></li>
</ul>
<?php
}
else
{
?>
	<div class="signed-out">Have an account? <a href="/account/login">Sign in</a></div>
<?php
}
?>
	<div style="clear:both;"></div>
</div></div>