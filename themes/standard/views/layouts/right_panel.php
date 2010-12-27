<div style="padding: 10px;">
	<?php
		if($last_location && !property_exists($last_location, 'error'))
		{
			$position = $last_location->location->position;
			
			$staticmap = 'http://maps.google.com/maps/api/staticmap?center=' . $position->latitude . ',+' . $position->longitude . '&size=320x240&sensor=false&markers=color:blue|size:small|' . $position->latitude . ',+' . $position->longitude;
			echo '<a href="/' . $username . '"><img src="' . $staticmap . '" width="320" height="240" /></a>';
			
		
		
		}
		else
		{
			?>
			
				It looks like you haven't started tracking yet!
			
				<h2>Download the app!</h2>
				<a href=""><img src="<?=$theme_root?>images/geoloqi_available_on_the_app_store.png" width="150" /></a>
				
				
				<div class="tipoftheday">
					Tip: To save battery, don't leave the tracker on all day. Turn it off when you get inside.
				</div>
			
			<?php
		}
	?>
</div>