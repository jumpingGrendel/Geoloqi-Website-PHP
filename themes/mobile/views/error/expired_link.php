<?php 
include($this->theme_file('layouts/site_header.php'));
?>

<div style="padding: 10px; color: #222; font-size: 22pt; text-align: center;">
	<img src="<?=$theme_root?>images/glyphish-timer.png" width="17" height="20"> 
	Time's Up!
</div>

<div style="padding: 10px; color: #333; font-size: 12pt; text-align: center;">
	<div style="margin-bottom: 20px; font-size: 14pt;">This user is no longer sharing their location.</div>
	
	<div style="margin-bottom: 10px;">Get Geoloqi on your iPhone!</div>
	<a href="http://search.itunes.apple.com/WebObjects/MZContentLink.woa/wa/link?path=app/geoloqi"><img src="<?=$theme_root?>images/geoloqi_available_on_the_app_store.png" width="150" /></a>
</div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>