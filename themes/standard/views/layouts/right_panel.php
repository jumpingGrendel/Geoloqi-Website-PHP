<div style="padding: 10px;">
	<?php
	if($last_location && !property_exists($last_location, 'error'))
	{
		$position = $last_location->location->position;
		
		$staticmap = 'http://maps.google.com/maps/api/staticmap?center=' . $position->latitude . ',+' . $position->longitude . '&size=320x240&sensor=false&markers=color:blue|size:small|' . $position->latitude . ',+' . $position->longitude;
?>
		<a href="/<?=$username?>"><img src="<?=$staticmap?>" width="320" height="240" style="border:1px #7EC3DE solid;" /></a>

		<table style="width: 320px;">
		<tr>
			<td>
				<div style="font-size: 8pt; float: right;">
					<?php
					$date = new DateTime($last_location->date, new DateTimeZone('UTC'));
					echo $date->format('M j, Y g:ia');
					?>
				</div>
				<div style="color: #179bef; font-weight: bold;">
					<?=round($position->latitude, 4) . ', ' . round($position->longitude, 4)?>
				</div>
				<div id="nearest_intersection"></div>
				<div id="last_location" style="display:none;"><?=$position->latitude . ',' . $position->longitude?></div>
			</td>
		</tr>
		</table>
<?php
	}
	else
	{
		?>
			<img src="<?=$theme_root?>images/no-tracked-points.png" width="320" height="240" />
		
			<h2 style="text-align:center;">Download the app!</h2>
			<table>
			<tr>
				<td><!-- <a href=""> --><img src="<?=$theme_root?>images/geoloqi_available_on_the_app_store.png" width="150" /><!-- </a> --></td>
				<td style="font-size: 8pt; padding-left: 15px;"><b>Coming Soon!</b> Available for iPhone 3GS and iPhone 4 only.</td>
			</tr>
			<tr>
				<td><br /></td>
			</tr>
			<tr>
				<td>
					<a href="/help/76/how-do-i-use-geoloqi-with-my-palm-phone" style="margin: 8px;"><img src="<?=$theme_root?>images/palm-icon-small.png" /></a>
					<a href="/help/74/how-do-i-use-geoloqi-with-my-android-phone" style="margin: 8px;"><img src="<?=$theme_root?>images/android-logo-40px.png" width="47" /></a>
					<br />
					<br />
					<a href="/help/78/how-do-i-use-geoloqi-with-my-blackberry" style="margin: 8px;"><img src="<?=$theme_root?>images/blackberry-logo-small.png" /></a>
					<a href="/help/80/how-do-i-use-geoloqi-with-my-boost-mobile-phone" style="margin: 8px;"><img src="<?=$theme_root?>images/boost-mobile-icon-small.png" /></a>
				</td>
				<td style="font-size: 8pt; padding-left: 15px;">
					The Android and Palm versions are currently in development.<br />
					<br />
					In the meantime, you can download <a href="/help/category/apps">Instamapper</a> and create a device key on your <a href="/settings/connections">Connections</a> tab.
				</td>
			</tr>
			</table>
		<?php
	}
	?>
				
	<br /><br />
	<div class="tipoftheday">
		Tip: To save battery, don't leave the tracker on all day. Turn it off when you get inside, and turn it back on when you start moving again.
	</div>
</div>