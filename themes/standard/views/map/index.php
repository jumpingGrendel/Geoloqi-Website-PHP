<?php 
$this->head[] = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'map.js"></script>';

if($enable_geonotes)
	$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'geonote.js"></script>';

include($this->theme_file('layouts/header_bar.php'));
?>
<script type="text/javascript">

var thinning = 0;
<?php 

if($last)
{
	//echo "\t" . 'last = ' . json_encode($last) . ';' . "\n";
	// Set the 'thinning' value based on their rate_limit
	if(k($last, 'raw'))
	{
		// If they're only tracking every 30 seconds or less, don't thin the data, otherwise set the thinning to 3
		if(k($last->raw, 'tracking_limit'))
			echo "\t" . 'thinning = ' . ($last->raw->tracking_limit > 30 ? '0' : '3') . ";\n";
		elseif(k($last->raw, 'rate_limit'))
			echo "\t" . 'thinning = ' . ($last->raw->rate_limit > 30 ? '0' : '3') . ";\n";	
	}
}

echo 'var self_map = ' . ($self_map ? 1 : 0) . ";\n";
echo 'var username = "' . $username . '";' . "\n";

?>
</script>
<table cellspacing="0" cellpadding="0" id="map-page">
	<tr>
		<td id="sidebar">
			<div id="sidebar-logo"><div id="geoloqi-logo"></div></div>
			
			<div id="profile-info" class="round sidebar-panel">
				<div class="name"><?=$name?></div>
				<div class="username"><?=$username?></div>
				<div class="line website"><a href="<?=$website?>"><?=str_replace('http://', '', $website)?></a></div>
				<div class="line bio"><?=$bio?></div>
			</div>
<?php 
		if($enable_geonotes)
		{
?>
			<div class="round sidebar-panel sidebar-geonote">
				<div style="margin-bottom: 10px;"><input type="button" value="Leave a geonote!" onclick="start_geonotes()" /></div>
				<div class="small">Leave a short note that will be sent to <?=$name?> at a specific location.</div>
				<div id="geonote_info">
					<div id="geonote_prompt" style="display: none;">
						<textarea id="geonote_text" maxlen="140"></textarea>
						<input type="button" id="geonote_create" value="Create" />
						<div style="font-size: 9pt;"><table cellpadding="0" cellspacing="0">
							<tr>
								<td>
									<input type="radio" class="radius_size" name="radius_size" id="radius_size_120" value="120" /> Block
									<input type="radio" class="radius_size" name="radius_size" id="radius_size_400" value="400" /> Area<br />
								</td>
								<td>
									<input type="radio" class="radius_size" name="radius_size" id="radius_size_1200" value="1200" /> Neighborhood
									<input type="radio" class="radius_size" name="radius_size" id="radius_size_6000" value="6000" /> City
								</td>
								<td>
									<img src="<?=$image_root?>arrow_out.png" width="16" height="16" class="radius_expand" /><br />
									<img src="<?=$image_root?>arrow_in.png" width="16" height="16" class="radius_shrink" />
								</td>
							</tr>
						</table></div>
					</div>
					<div id="geonote_success" style="display: none;">Thanks!</div>
				</div>
			</div>
<?php 
		}
?>

		<!--
		<div class="round sidebar-panel">
			<div id="loading"><div style="height: 16px; text-align: right; padding: 10px;"><img src="loading.gif" height="16" width="16" style="display: none;" /></div></div>
			<table class="params">
				<tr>
					<td class="header" colspan="2">Geoloqi API <span class="help"><a href="http://geoloqi.org/API/location/history" target="_blank">help</a></span></td>
				</tr>
				<tr>
					<th>Points</th>
					<td><input id="param_count" type="text" size="10" value="200" /></td>
				</tr>
				<tr>
					<th>Accuracy</th>
					<td><input id="param_accuracy" type="text" size="10" value="300" /></td>
				</tr>
				<tr>
					<th>From</th>
					<td><input id="param_from" type="text" size="10" value="<?=date('Y-m-d', strtotime('-30 days'))?>" /></td>
				</tr>
				<tr>
					<th>To</th>
					<td><input id="param_to" type="text" size="10" value="<?=date('Y-m-d', strtotime('+1 day'))?>" /></td>
				</tr>
				<tr>
					<th>Time From</th>
					<td><input id="param_time_from" type="text" size="10" value="" /></td>
				</tr>
				<tr>
					<th>Time To</th>
					<td><input id="param_time_to" type="text" size="10" value="" /></td>
				</tr>
				<tr>
					<th>Thinning</th>
					<td><input id="param_thinning" type="text" size="10" value="" /></td>
				</tr>
			</table>
		</div>
		<div class="round sidebar-panel">
			<table class="params">
				<tr>
					<td class="header" colspan="2">Google Maps API <span class="help"><a href="http://code.google.com/apis/maps/documentation/javascript/reference.html" target="_blank">help</a></span></td>
				</tr>
				<tr>
					<th>Stroke Weight</th>
					<td><input id="stroke_weight" type="text" size="5" value="3" /></td>
				</tr>
				<tr>
					<th>Stroke Opacity</th>
					<td><input id="stroke_opacity" type="text" size="5" value="0.7" /></td>
				</tr>
				<tr>
					<th>Stroke Color</th>
					<td><input id="stroke_color" type="text" size="8" value="#000000" /></td>
				</tr>
			</table>
		</div>
		-->

		</td>
		<td id="map-container">
			<div id="map"></div>
		</td>
	</tr>
	<tr>
		<td colspan="2" height="23" style="padding: 0;"><div id="map-footer"><?php include($this->theme_file('layouts/footer_bar.php')); ?></div></td>
	</tr>
</table>
