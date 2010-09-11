<?php 
$this->head[] = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'map.js"></script>';

if($enable_geonotes)
	$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'geonote.js"></script>';

include($this->theme_file('layouts/header_bar.php'));
?>
<script type="text/javascript">
<?php 
if($last)
{
	echo "\t" . 'last = ' . json_encode($last) . ';' . "\n";
}
?>
$(function(){
	if(last){
		receive_location(last);
	}
});
</script>
<table cellspacing="0" cellpadding="0" id="map-page">
	<tr>
		<td id="sidebar">
			<div id="sidebar-logo"><div id="geoloqi-logo"></div></div>
			
			<div id="profile-info" class="round sidebar-panel">
				<div class="name"><?=$name?></div>
				<div class="line website"><a href="<?=$website?>"><?=substr(str_replace('http://', '', $website), 0, 12)?></a></div>
				<div class="line bio"><span class="label">Bio</span> <?=$bio?></div>
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
		</td>
		<td id="map-container">
			<div id="map"></div>
		</td>
	</tr>
	<tr>
		<td colspan="2" height="23" style="padding: 0;"><div id="map-footer"><?php include($this->theme_file('layouts/footer_bar.php')); ?></div></td>
	</tr>
</table>
