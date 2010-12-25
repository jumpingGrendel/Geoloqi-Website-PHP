<?php 
$this->head[] = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
$this->head[] = '<script type="text/javascript" src="/themes/standard/assets/map.js"></script>';
?>
<script type="text/javascript">
<?php 

echo 'var thinning = ' . $thinning . ";\n";
echo 'var self_map = ' . ($self_map ? 1 : 0) . ";\n";
echo 'var username = "' . $username . '";' . "\n";
echo 'var share_token = "' . $share_token . '";' . "\n";
echo 'var rough = ' . json_encode($rough) . ';' . "\n";
echo 'var last = ' . json_encode($last) . ';' . "\n";
echo 'var public_geonotes = ' . ($public_geonotes ? 1 : 0) . ';' . "\n";
echo 'var public_location = ' . ($public_location ? 1 : 0) . ';' . "\n";

?>
</script>

<div id="map-header">
	<div id="geoloqi-header"><div class="logo"></div></div>
	<div id="map-profile">
		<?php if($phone_digits) { ?>
		<div class="phones">
			<div class="right"><a href="sms:<?=$phone_digits?>"><img src="<?=$theme_root?>images/08-chat.png" width="24" height="22" /></a></div>
			<div class="right"><a href="tel:<?=$phone_digits?>"><img src="<?=$theme_root?>images/75-phone.png" width="24" height="24" /></a></div>
		</div>
		<?php } ?>
		<div class="profile_info">
			<img src="<?=$profile_image?>" width="32" height="32" />
			<span class="username"><?=$username?></span>
		</div>
	</div>
</div>

<div id="map"></div>

<div id="map-footer">
	<div>Download <a href="http://geoloqi.com">Geoloqi</a> for your phone!</div>
</div>
