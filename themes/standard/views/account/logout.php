<?php 
$this->head[] = '<link rel="stylesheet" type="text/css" href="' . $theme_root . 'authorize.css"></script>';
?>
<div id="authorize-page">

<div id="loqisaur-logo"></div>

<div id="authorize-wrap">
<div id="authorize" class="round"><div class="in">

	<div style="text-align: center; padding-bottom: 20px;">
		<h2>Sign completely out of Geoloqi?</h2>
		
		<p style="width: 400px; margin: 20px auto; color: #6b1b05;">Since you signed in with Twitter, you'll have to sign out of Twitter to completely sign out of Geoloqi.</p>

		<input type="submit" name="accept" class="btn btn-ok" value="Sign Out of Twitter" onclick="window.location='http://twitter.com/logout'" />
	</div>

</div></div><!-- in, authorize -->
</div><!-- authorize-wrap -->

</div><!-- authorize-page -->
