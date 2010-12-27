<div style="width: 340px; border: 1px #ccc solid; float: right; margin-right: 20px;">
<div style="padding: 10px;">
	<?php
		if($last_location && !property_exists($last_location, 'error'))
		{
			$position = $last_location->location->position;
			
			$staticmap = 'http://maps.google.com/maps/api/staticmap?center=' . $position->latitude . ',+' . $position->longitude . '&size=320x240&sensor=false&markers=color:blue|size:small|' . $position->latitude . ',+' . $position->longitude;
			#echo '<img src="/themes/standard/assets/images/profile-blank.png" width="320" height="240" />';
			echo '<img src="' . $staticmap . '" width="320" height="240" />';
			
		
		}
		else
		{
			?>
			
				It looks like you haven't started tracking yet!
			
				
			
			
			<?php
		}
	?>
</div>
</div>