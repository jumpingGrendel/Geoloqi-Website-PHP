<?php 
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<div class="settings-wrap"><div class="settings">

<div class="header">Connections</div>
<table>
	<tr>
		<td>
			<div class="app_icon"><img src="<?=$image_root?>apps/twitter-geoloqi.png" width="64" height="64" /></div>
		</td>
		<td>
			<div class="label">Twitter</div>
			<div class="description">
				Connect your Twitter account to log in via Twitter, update your Geoloqi location via Twitter, or share your location via Twitter.
			</div>
		</td>
		<td>
			<input type="button" class="submit" value="Connect" />
		</td>
	</tr>
	<tr class="coming-soon">
		<td>
			<div class="app_icon"><img src="<?=$image_root?>apps/facebook-geoloqi.png" width="64" height="64" /></div>
		</td>
		<td>
			<div class="label">Facebook</div>
			<div class="description">
				Connect your Facebook account to log in via Twitter, update your Geoloqi location via Facebook, or share your location via Facebook.
			</div>
		</td>
		<td>
			<input type="button" class="submit" value="Connect" />
		</td>
	</tr>
	<tr class="coming-soon">
		<td>
			<div class="app_icon"><img src="<?=$image_root?>apps/foursquare-geoloqi.png" width="64" height="64" /></div>
		</td>
		<td>
			<div class="label">Foursquare</div>
			<div class="description">
				Connect your Twitter account to log in via Twitter, update your Geoloqi location via Twitter, or share your location via Twitter.
			</div>
		</td>
		<td>
			<input type="button" class="submit" value="Connect" />
		</td>
	</tr>
	<tr class="coming-soon">
		<td>
			<div class="app_icon"><img src="<?=$image_root?>apps/gowalla-geoloqi.png" width="64" height="64" /></div>
		</td>
		<td>
			<div class="label">Gowalla</div>
			<div class="description">
				Connect your Twitter account to log in via Twitter, update your Geoloqi location via Twitter, or share your location via Twitter.
			</div>
		</td>
		<td>
			<input type="button" class="submit" value="Connect" />
		</td>
	</tr>
	<tr class="coming-soon">
		<td>
			<div class="app_icon"><img src="<?=$image_root?>apps/google-latitude-geoloqi.png" width="64" height="64" /></div>
		</td>
		<td>
			<div class="label">Latitude</div>
			<div class="description">
				Connect your Twitter account to log in via Twitter, update your Geoloqi location via Twitter, or share your location via Twitter.
			</div>
		</td>
		<td>
			<input type="button" class="submit" value="Connect" />
		</td>
	</tr>
</table>

<div class="header">Geolocation Services</div>
<table>
	<tr>
		<td>
			<div class="app_icon"><img src="<?=$image_root?>apps/instamapper-geoloqi.png" width="64" height="64" /></div>
		</td>
		<td>
			<div class="label">Instamapper API Key</div>
			<div class="description">
				You can connect a Boost Mobile phone or any device that runs Instamapper to Geoloqi.com by entering your Instamapper key.
			</div>
		</td>
		<td>
			<input type="text" id="" value="" class="text" />
			<input type="button" class="submit" value="Save" />
		</td>
	</tr>
	<tr class="coming-soon">
		<td>
			<div class="app_icon"><img src="<?=$image_root?>apps/trackr-geoloqi.png" width="64" height="64" /></div>
		</td>
		<td>
			<div class="label">Trackr! Username</div>
			<div class="description">
				You can import your location data from Trackr! into Geoloqi by entering your username. You will also need to make your location public on Trackr.eu. 
			</div>
		</td>
		<td>
			<input type="text" id="" value="" class="text" />
			<input type="button" class="submit" value="Save" />
		</td>
	</tr>
</table>

<div class="header">Applications</div>
<table class="applications">
	<tr>
		<td>
			<div class="app_icon"><img src="<?=$image_root?>apps/icecondor-geoloqi.png" width="64" height="64" /></div>
		</td>
		<td>
			<div class="label">IceCondor</div>
			<div class="author">by <a href="http://donpark.org/">Don Park</a></div>
			<div class="description">
				IceCondor is continuous location tracking. Publish your location on the web. Also follows people and events in real-time from multiple services. See demonstration video at http://icecondor.com
			</div>
			<div class="info">
				<a href="">Remove</a> &#x00B7; <span class="connected">connected on August 12 6:05pm</span> &#x00B7; <span class="scopes">last location, geonotes, sharing</span>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div class="application-1">Don't see an app here that does what you want?</div>
			<div class="application-2">Why not <a href="http://geoloqi.com/developers">build one?</a></div>
		</td>
	</tr>
</table>



</div></div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>