<?php 
$this->head[] = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'map.js"></script>';
?>

<div id="map-header">
	<?=$username?>
</div>

<div id="map"></div>
