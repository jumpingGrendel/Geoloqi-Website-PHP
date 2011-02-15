<? $this->include_file('layouts/layer_header.php') ?>

<div class="light-bkg">
	<div><?= $description ?></div>
	
	<?php
	if(k($layer, 'type') == 'autocheckin')
	{
		// If they haven't connected their foursquare account, provide a button to do so
		if(!k(session('user_profile'), 'foursquare_id'))
		{
			$params = array();
			$params['oauth_token'] = $_SESSION['oauth_token'];
			$params['foursquare_connect_redirect'] = $foursquare_connect_redirect;
			echo '<div style="margin-top: 15px; text-align: center;">';
			echo '<a href="' . https(WEBSITE_URL) . '/connect/foursquare?' . http_build_query($params) . '">';
				echo '<img src="/images/signinwith-foursquare.png" />';
			echo '</a>';
			echo '</div>';
		}
		else
		{
			?>
			<table width="100%" style="margin-top: 12px;">
				<tr>
					<td width="48">
						<img src="/themes/standard/assets/images/apps/foursquare-geoloqi.png" width="48" />
					</td>
					<td>
						<div style="margin: 4px; font-size: 90%;">Automatically check in on Foursquare</div>
					</td>
					<td width="94">
						<a href="javascript:void(0);" id="setting-foursquare_autocheckin" class="layer-settings <?=k($layer_settings, 'foursquare_autocheckin') ? 'on' : 'off'?>">
							<img src="/images/switch_dark_<?=k($layer_settings, 'foursquare_autocheckin') ? 'on' : 'off'?>@2x.png" height="28" width="94" />
						</a>
					</td>
				</tr>
			</table>
			<input style="display: none;" id="layer_id" value="<?=$layer->layer_id?>" />
			<script type="text/javascript">
			$(function(){
				$(".layer-settings").click(function(){
					$(this).blur();
					$.post("/settings/layer.ajax", {
						id: $("#layer_id").val(),
						action: "settings",
						foursquare_autocheckin: ($("#setting-foursquare_autocheckin").hasClass("on") ? 0 : 1)
					}, function(data){
						if(data && data.layer_id){
							var img = $("#setting-foursquare_autocheckin img").attr("src");
							if(data.settings.foursquare_autocheckin == 1){
								$("#setting-foursquare_autocheckin").removeClass("off").addClass("on");
								$("#setting-foursquare_autocheckin img").attr("src", img.replace(/_off/, "_on"));
							}else{
								$("#setting-foursquare_autocheckin").removeClass("on").addClass("off");
								$("#setting-foursquare_autocheckin img").attr("src", img.replace(/_on/, "_off"));
							}
						}else{
							alert("Error: " + (data ? data.error : ""));
						}
					}, "json");
				});
			});
			</script>
			<?php
		}
	}
	
	?>
</div>

<? $this->include_file('layouts/layer_footer.php') ?>
