<?php 
$this->head[] = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'map.js"></script>';
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'map-history.js"></script>';

if($enable_geonotes)
	$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'geonote.js"></script>';

include($this->theme_file('layouts/header_bar.php'));
?>
<script type="text/javascript">
<?php 

echo 'var thinning = ' . $thinning . ";\n";
echo 'var self_map = ' . ($self_map ? 1 : 0) . ";\n";
echo 'var username = "' . $username . '";' . "\n";
echo 'var share_token = "' . $share_token . '";' . "\n";
echo 'var share_expires = ' . ($share_info ? strtotime($share_info->date_to) . '000' : 'false') . ";\n";
echo 'var rough = ' . json_encode($rough) . ';' . "\n";
echo 'var last = ' . json_encode($last) . ';' . "\n";
echo 'var public_geonotes = ' . ($public_geonotes ? 1 : 0) . ';' . "\n";
echo 'var public_location = ' . ($public_location ? 1 : 0) . ';' . "\n";

?>
</script>
<table cellspacing="0" cellpadding="0" id="map-page">
	<tr>
		<td id="sidebar">
			<div id="sidebar-logo"><a id="loqisaur-logo" href="/settings"></a></div>
			
			<div id="profile-info" class="round sidebar-panel">
				<div class="name"><?=$name?></div>
				<div class="line bio"><?=$bio?></div>
				<table>
					<tr>
						<td width="50">
							<?= ($profile_image ? '<div class="pic"><img src="' . $profile_image . '" width="48" height="48" /></div>' : '') ?>
						</td>
						<td>
							<div class="username"><?=($has_custom_username ? $username : '')?></div>
							<div class="line website"><a href="<?=$website?>"><?=str_replace('http://', '', $website)?></a></div>
						</td>
					</tr>
					<tr>
						<td colspan="2"><table><tr>
							<td width="80">
								<div class="last-lat"></div>
								<div class="last-lng"></div>
							</td>
							<td style="text-align: right;">
								<div class="last-time"><div class="relative"></div><div class="absolute"></div></div>
								<div class="last-speed"></div>
							</td>
						</tr></table></td>
					</tr>
				</table>
			</div>
<?php 
		if($share_info)
		{
?>		
			<div class="round sidebar-panel" id="sidebar_shareinfo">
				<div class="panel-content">
					<?php
					if($share_info->description)
					{ 
						echo '<div class="says">' . $username . ' says:</div>';
						echo '<div class="shared_message">' . $share_info->description . '</div>';
					}
					?>
					<div class="share_countdown">
						<span class="relative"></span>
						<span class="absolute"><?=($share_info->date_to ? date('n/d/Y g:ia', strtotime($share_info->date_to)) : 'This link will never expire')?></span>
					</div>
				</div>
			</div>
<?php	
		}

		if($enable_geonotes)
		{
?>
			<div class="round sidebar-panel" id="sidebar_geonote">
				<div class="panel-title">Leave a Geonote</div>
				<div class="panel-content" style="display: none;">
					<div id="geonote_prompt" style="margin-top: 10px;">
					<div class="small">Leave a short note that will be sent to <?=$geonote_to?> at a specific location.</div>
<?php 
						if($enable_geonote_confirmation)
						{
?>
						<input type="text" id="geonote_email" class="text" title="Your Email (optional)" value="<?=$geonote_from?>" /><br />
<?php							
						}
?>					
						<textarea id="geonote_text" maxlen="140"></textarea>
						<input type="button" id="geonote_create" value="Create" class="submit" disabled="disabled" />
						<div style="font-size: 9pt;"><table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
									<input type="radio" class="radius_size" name="radius_size" id="radius_size_120" value="120" /> Block<br />
									<input type="radio" class="radius_size" name="radius_size" id="radius_size_400" value="400" /> Area
								</td>
								<td>
									<input type="radio" class="radius_size" name="radius_size" id="radius_size_1200" value="1200" /> Neighborhood<br />
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
		
		// If the user is looking at their own map, give them more options
		if($self_map)
		{
?>
		<div class="round sidebar-panel" id="sidebar_sharelink">
			<div class="panel-title">Share Link</div>
			<div class="panel-content" style="display: none;">

				<div class="small" style="text-align: right"><a href="/settings/share">See all links</a></div>
				<table style="width: 200px;">
<?php 
				if(GEOLOQI_ENABLE_SHARED_SEND)
				{
?>
				<tr>
					<td colspan="2">
						Share with:<br />
						<div class="small">Enter email addresses or mobile phone numbers, separated by commas.</div>
						<textarea id="share_with"></textarea>
					</td>
				</tr>
<?php 
				}
?>
				<tr>
					<td colspan="2">
						<div class="small">Description:</div>
						<input type="text" id="share_description" />
					</td>
				</tr>
				<tr>
					<td>
						<div class="small">Expire in:</div>
						<select id="share_expiration">
						<?php
						$dt = array(
							'10'=>'10 minutes',
							'20'=>'20 minutes', 
							'30'=>'30 minutes', 
							'60'=>'1 hour', 
							'120'=>'2 hours', 
							'480'=>'8 hours',
							'1440'=>'24 hours',
							'5760'=>'4 days',
							'10080'=>'7 days',
							'0'=>'no time limit');
						foreach ($dt as $k=>$t) {
							if ($k == $default_share_expiration) {
								echo '<option value="'.$k.'" selected>'.$t.'</option>';
							} else {
								echo '<option value="'.$k.'">'.$t.'</option>';
							}
						}
						?>
						</select>
					</td>
					<td style="text-align: right; vertical-align: bottom;">
						<input type="button" value="Create" class="submit" id="share_btn" />
					</td>
				</tr>
				</table>

			</div>
		</div><!-- share link -->
<?php 
			if(GEOLOQI_ENABLE_MAPOPTIONS)
			{
?>
		<div class="round sidebar-panel" id="sidebar_mapoptions">
			<div class="panel-title">History <span class="small help" style="float: right; display: none;"><a href="http://geoloqi.com/help/156/what-is-history-and-how-does-it-work" target="_blank">What's this?</a></span></div>
			<div class="panel-content" style="display: none;">
				<div id="history_loading"><div style="height: 16px; width: 16px;"></div></div>
				<table class="history_params">
					<tr>
						<th>Points</th>
						<td><input id="history_count" type="text" size="10" value="200" title="Number of points to return" /></td>
					</tr>
					<tr>
						<th>Accuracy</th>
						<td><input id="history_accuracy" type="text" size="10" value="300" title="Ignore points less accurate than this, in meters" /></td>
					</tr>
					<tr>
						<th>From</th>
						<td><input id="history_from" type="text" size="10" value="<?=date('Y-m-d', strtotime('-7 days'))?>" placeholder="yyyy-mm-dd" title="yyyy-mm-dd" /></td>
					</tr>
					<tr>
						<th>To</th>
						<td><input id="history_to" type="text" size="10" value="<?=date('Y-m-d', strtotime('+1 day'))?>" placeholder="yyyy-mm-dd" title="yyyy-mm-dd" /></td>
					</tr>
					<tr>
						<th>Time From</th>
						<td><input id="history_time_from" type="text" size="10" value="" placeholder="hh:mm" title="hh:mm" /></td>
					</tr>
					<tr>
						<th>Time To</th>
						<td><input id="history_time_to" type="text" size="10" value="" placeholder="hh:mm" title="hh:mm" /></td>
					</tr>
					<tr>
						<th>Thinning</th>
						<td><input id="history_thinning" type="text" size="10" value="<?=$thinning?>" title="Return only every nth point" /></td>
					</tr>
				</table>
	
				<table class="history_params">
					<tr>
						<td class="header" colspan="2">Map Options <span class="help small"><a href="http://code.google.com/apis/maps/documentation/javascript/reference.html" target="_blank">Google Maps API</a></span></td>
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
		</div><!-- map options -->
<?php 
			}

		} // end if user is looking at their own map
?>
		</td>
		<td id="map-container">
			<div id="map"></div>
<?php 
			// If the user is looking at their own map, and there is no recent location, show a friendly error
			if($self_map && ($last == FALSE || k($last, 'error')))
			{
				echo '<div id="map-disabled"><div class="message">';
					echo '<p>Oops! Looks like you haven\'t started tracking yet! Did you:</p>
						<ul>
							<li>Download the <a href="http://geoloqi.com/blog/app-download">Geoloqi app</a>?</li>
							<li>or <a href="http://geoloqi.com/blog/2010/08/how-do-i-get-my-instamapper-device-and-api-key/">enter your Instamapper device key</a>?</li>
							<li>Walk around the block?</li>
							<li>Check out the <a href="http://geoloqi.com/help/">FAQ</a> for more questions</li>
						</ul>';
				echo '</div></div>';
			}
			// When looking at someone else's map, a number of errors can occur
			if(!$self_map)
			{
				// If they are sharing their location publicly and there was an error, or if they allow public geonotes and there was an error 
				if(k($last, 'error') == 'no_recent_location' || k($rough, 'error') == 'no_recent_location')
				{
					echo '<div id="map-disabled" style=""><div class="message">';
						echo 'This user hasn\'t logged any points yet.';
					echo '</div></div>';
				}
				elseif(($e=k($last, 'error_description')) || ($e=k($rough, 'error_description')))
				{
					// This should catch unexpected errors
					echo '<div id="map-disabled" style=""><div class="message">';
						echo 'Oops! ' . $e;
					echo '</div></div>';
				}
			}
?>
		</td>
	</tr>
	<tr>
		<td colspan="2" height="23" style="padding: 0;"><div id="map-footer"><?php include($this->theme_file('layouts/footer_bar.php')); ?></div></td>
	</tr>
</table>

<div id="hiddenMap" style="display:none;"></div>