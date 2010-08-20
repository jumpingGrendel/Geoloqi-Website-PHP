<?php 
$this->head[] = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'map.js"></script>';
?>

<div id="sidebar">
	<?=$username?>
</div>
<div id="map"></div>

<div id="map-footer"><?php include($this->theme_file('layouts/footer_bar.php')); ?></div>
