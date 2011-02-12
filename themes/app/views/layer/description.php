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
			echo '<div style="margin-top: 20px;">';
				echo '<img src="' . $theme_root . 'images/poweredbyfoursquare-icon.png" style="margin-bottom: -6px; margin-right: 4px;" />';
			echo 'Connected to Foursquare</div>';
		}
	}
	
	?>
	<?php /*pa($layer);*/ ?>
</div>

<? $this->include_file('layouts/layer_footer.php') ?>
