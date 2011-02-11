<?php
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'settings-connections.js"></script>';
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
		<td width="140">
			<?php 
				if($_SESSION['user_profile']->twitter == '')
					echo '<a href="/connect/twitter" class="btn connect-button" id="connect_twitter"><span>Connect</span></a>';
				else
					echo 'Connected: <div class="connection-username"><a href="http://twitter.com/' . $_SESSION['user_profile']->twitter . '">@' . $_SESSION['user_profile']->twitter . '</a></div>';
			?>
		</td>
	</tr>
	<tr>
		<td>
			<div class="app_icon"><img src="<?=$image_root?>apps/foursquare-geoloqi.png" width="64" height="64" /></div>
		</td>
		<td>
			<div class="label">Foursquare</div>
			<div class="description">
				Connect your Foursquare account to automatically check in on Foursquare when you are nearby your favorite places.
			</div>
		</td>
		<td>
			<?php
				if(k($_SESSION['user_profile'], 'foursquare_id') == '')
					echo '<a href="/connect/foursquare" class="btn connect-button" id="connect_foursquare"><span>Connect</span></a>';
				else
					echo 'Connected: <div class="connection-username"><a href="http://foursquare.com/user/' . $_SESSION['user_profile']->foursquare_id . '">View Profile</a></div>';
			?>
		</td>
	</tr>
	<?php
	if(k($_SESSION['user_profile'], 'facebook_id'))
	{
	?>
	<tr>
		<td>
			<div class="app_icon"><img src="<?=$image_root?>apps/facebook-geoloqi.png" width="64" height="64" /></div>
		</td>
		<td>
			<div class="label">Facebook</div>
			<div class="description">
				Connect your Facebook account to share your location with your Facebook friends.
			</div>
		</td>
		<td>
			<?php
				if(k($_SESSION['user_profile'], 'facebook_id') == '')
					echo '<a href="/connect/facebook" class="btn connect-button" id="connect_facebook"><span>Connect</span></a>';
				else
					echo 'Connected: <div class="connection-username"><a href="http://facebook.com/profile.php?id=' . $_SESSION['user_profile']->facebook_id . '">View Profile</a></div>';
			?>
		</td>
	</tr>
	<?php
	}
	?>
	<tr class="coming-soon">
		<td>
			<div class="app_icon"><img src="<?=$image_root?>apps/gowalla-geoloqi.png" width="64" height="64" /></div>
		</td>
		<td>
			<div class="label">Gowalla</div>
			<div class="description">
				<b>Coming soon!</b> Connect your Gowalla account to automatically check in on Gowalla when you are nearby your favorite places.
			</div>
		</td>
		<td>
			<!-- <input type="button" class="submit" value="Connect" /> -->
		</td>
	</tr>
	<!-- 
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
	-->
	
</table>

<div class="header">Geolocation Services</div>
<table>
	<tr>
		<td>
			<div class="app_icon"><img src="<?=$image_root?>apps/instamapper-geoloqi.png" width="64" height="64" /></div>
		</td>
		<td>
			<div class="label">Instamapper Device Key</div>
			<div class="description">
				You can connect any phone that runs Instamapper to Geoloqi.com by creating an Instamapper device key. Once you create the key here, enter it into Instamapper on your phone.
			</div>
		</td>
		<td>
<?php
				echo '<input type="text" id="instamapper_devicekey" value="' . $instamapper_devicekey . '" class="text" style="' . ($instamapper_devicekey ? '' : 'display:none;') . '" />';
				echo '<input type="button" class="submit" value="Create" id="instamapper_create" style="' . ($instamapper_devicekey ? 'display:none;' : '') . '" />';
?>
		</td>
	</tr>
</table>

<div class="header">Applications</div>
<table class="applications">
<?php 
	foreach($connections as $c):
		$connected_date = new DateTime($c->date_approved);
		$connected_date->setTimeZone(new DateTimeZone($this->user->timezone));
?>
	<tr>
		<td>
			<!-- <div class="app_icon"><img src="<?=$image_root?>apps/geoloqi.png" width="64" height="64" /></div> -->
		</td>
		<td>
			<div class="label"><?=$c->application_name?></div>
			<div class="author">by <a href="<?=$c->application_url?>"><?=$c->requester_name?></a></div>
			<div class="description">
				<?=$c->application_description?>
			</div>
			<div class="info">
				<!-- <a href="">Remove</a> &#x00B7; --><span class="connected">connected on <?=$connected_date->format('F j, Y g:ia')?></span><!-- &#x00B7; <span class="scopes">last location, geonotes, sharing</span>-->
			</div>
		</td>
	</tr>
<?php 
	endforeach;
?>
	<tr>
		<td colspan="2">
			<div class="application-1">Don't see an app here that does what you want?</div>
			<div class="application-2">Why not <a href="http://geoloqi.org/API">build one?</a></div>
		</td>
	</tr>
</table>


<div class="header">Developers</div>
<table class="applications">
	<tr>
		<td width="64">
			<div class="app_icon" style="width: 64px; height: 64px;"></div>
		</td>
		<td>
			<div class="label">OAuth 2 Access Token</div>
			<div class="description">
				You can request a permanent access token for your account so you can start building apps right away! This access token will never expire, and has full access to your account, so guard it carefully! Only use it over https.
				<br /><br />
<?php 
				if($permanent_token)
				{
					echo '<input type="text" class="text" id="permanent_access_token" value="' . $permanent_token . '" style="width: 500px;" />';	
				}
				else
				{
					echo '<input type="button" class="submit" value="Get Access Token" id="request_access_token" />';
					echo '<input type="text" class="text" id="permanent_access_token" style="display: none; width: 400px;" />';	
				}
?>
			</div>
		</td>
	</tr>
</table>



</div></div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>