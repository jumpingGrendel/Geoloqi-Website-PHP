<?php 
include($this->theme_file('layouts/site_header.php'));
?>

<div style="text-align: center">
	
	<div style="padding: 10px; color: #222; font-size: 26pt;">
		<img src="<?=$theme_root?>images/glyphish-timer.png" width="34" height="40"> 
		Time's Up!
	</div>
	
	<div style="padding: 10px; color: #333; font-size: 14pt; width: 260px; margin: 0 auto;">This user is no longer sharing their location.</div>

	<div style="width: 300px; margin: 40px auto; padding: 20px; background-color: #ddd;" class="round">
		<div style="font-size: 13pt; margin-bottom: 10px;">Get Geoloqi on your iPhone!</div>
		<a href="http://itunes.apple.com/us/app/geoloqi/id415603875"><img src="<?=$theme_root?>images/geoloqi_available_on_the_app_store.png" width="150" /></a>
	</div>

	<div style="margin-top: 40px;">&nbsp;</div>
</div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>