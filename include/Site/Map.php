<?php 
require_once('Regex.php');

class Site_Map extends Site
{
	protected $force_login = FALSE;
	
	public function index($username)
	{
		// Redirect 'me' to the current logged-in user
		if($username == 'me' || $username == 'map')
		{
			if(session('username'))
				redirect('/' . session('username'));
			else
				redirect('/');
			die();
		}

		$last = FALSE;
		$rough = FALSE;
		$this->data['share_info'] = FALSE;
		
		// If there is a shared link token present, then make the API calls using the share endpoints
		if(get('key'))
		{
			$linkInfo = $this->api->request('share/info?geoloqi_token=' . get('key'), FALSE, TRUE);

			if(k($linkInfo, 'error') == 'user_not_found')
				$this->error(HTTP_NOT_FOUND, 'Not Found', 'Sorry, the user "' . $username . '" doesn\'t exist!');
			
			// TODO: Make a nicer error message when a link has expireed
			if(k($linkInfo, 'error') == 'invalid_token')
				$this->error(HTTP_OK, 'Expired', 'The shared link has expired!', 'expired_link');

			$profile = $linkInfo->profile;
			$this->data['share_info'] = $linkInfo->share;
			
			$last = $this->api->request('share/last?geoloqi_token=' . get('key'), FALSE, TRUE);
		}
		else
		{
			// If the user is logged in and they're looking at their own map, make the call with their access token 
			if(array_key_exists('username', $_SESSION) && session('username') == $username)
			{
				$profile = $this->api->request('account/profile?username=' . $username);
				$last = $this->api->request('location/last?username=' . $username);
			}
			else
			{
				// Attempt to make the API call with no auth tokens
				$profile = $this->api->request('account/profile?username=' . $username, FALSE, TRUE);
				
				if(k($profile, 'error') == 'user_not_found')
					$this->error(HTTP_NOT_FOUND, 'Not Found', 'Sorry, the user "' . $username . '" doesn\'t exist!');
				
				if(k($profile, 'error'))
					$this->error(HTTP_SERVER_ERROR, $profile->error, 'There was an error');
				
				// If the user's location is public, fetch the exact location
				if($profile->public_location)
					$last = $this->api->request('location/last?username=' . $username, FALSE, TRUE);
				// Otherwise, try to get their rough location
				elseif($profile->public_geonotes)
					$rough = $this->api->request('location/rough?username=' . $username, FALSE, TRUE);
				// If geonotes are not enabled, and their location isn't public, there is nothing we can do
				else
					$this->error(HTTP_FORBIDDEN, 'Private Profile', 'Sorry, this user is not sharing their location publicly.');
			}
		}

		// If there was some error retrieving the user's profile or location (such as non-public location or invalid username), throw an error and stop
		if(k($profile, 'error') != NULL)
			$this->error(HTTP_NOT_FOUND, $profile->error, $profile->error_description);

		// Commented these out to allow the error message to pass through to the javascript
		#if($rough && k($rough, 'error') != NULL)
		#	$rough = FALSE;
		#	$this->error(HTTP_NOT_FOUND, $rough->error, $rough->error_description);

		#if($last && k($last, 'error') != NULL)
		#	$last = FALSE;
		#	$this->error(HTTP_NOT_FOUND, $last->error, $last->error_description);

		$this->data['last'] = $last;
		$this->data['rough'] = $rough;
		
		$this->data['name'] = $profile->name;
		$this->data['username'] = $profile->username;
		$this->data['bio'] = $profile->bio;
		$this->data['phone'] = Regex_Phone::getFormatted(k($profile, 'phone'));
		$this->data['phone_digits'] = Regex_Phone::getDigits(k($profile, 'phone'));
		$this->data['website'] = $profile->website;
		$this->data['profile_image'] = ($profile->profile_image ?: '/themes/standard/assets/images/profile-blank.png');
		$this->data['is_anonymous'] = $profile->is_anonymous;
		$this->data['has_custom_username'] = $profile->has_custom_username;
		
		// whether the user is looking at their own map
		$this->data['self_map'] = get('key') == FALSE && $username == session('username');
		
		if($this->data['self_map'])
			$this->data['geonote_to'] = 'you';
		else
			$this->data['geonote_to'] = ($profile->name ?: $profile->username);

		$this->data['geonote_from'] = session('geonote_from') ?: (session('username') ? session('user_profile')->email : '');
			
		$this->data['thinning'] = 0;
		if($this->data['last'])
		{
			//echo "\t" . 'last = ' . json_encode($last) . ';' . "\n";
			// Set the 'thinning' value based on their rate_limit
			if(k($this->data['last'], 'raw'))
			{
				// If they're only tracking every 30 seconds or less, don't thin the data, otherwise set the thinning to 3
				if(k($this->data['last']->raw, 'tracking_limit'))
					$this->data['thinning'] = ($this->data['last']->raw->tracking_limit >= 30 ? '0' : '3');
				elseif(k($this->data['last']->raw, 'rate_limit'))
					$this->data['thinning'] = ($this->data['last']->raw->rate_limit >= 30 ? '0' : '3');
			}
		}
				
		if(get('key'))
			$this->data['share_token'] = get('key');
		else
			$this->data['share_token'] = '';
		
		$this->data['public_geonotes'] = $profile->public_geonotes;
		$this->data['public_location'] = $profile->public_location;
		$this->data['enable_geonotes'] = ($this->data['self_map'] || $profile->public_geonotes);
		$this->data['enable_geonote_confirmation'] = (!$this->data['self_map'] && $profile->public_geonote_email);
		$this->data['default_share_expiration'] = ($this->data['self_map'] ? $_SESSION['user_privacy']->default_share_expiration : 0);
	}

	public function create_geonote_ajax()
	{
		$_SESSION['geonote_from'] = session('email') ?: post('email');
		$response = $this->api->request('geonote/create?username=' . get('username'), array(
			'latitude' => post('lat'),
			'longitude' => post('lng'),
			'radius' => post('radius'),
			'text' => post('text'),
			'email' => post('email')
		), !array_key_exists('username', $_SESSION));
		return $response;
	}
	
	public function history_ajax()
	{
		$params = array();
		$params['sort'] = 'desc';
		
		if(post('sw'))
			$params['geometry'] = 'rectangle';
		
		foreach(array('sw', 'ne', 'date_from', 'date_to', 'time_from', 'time_to', 'accuracy', 'count', 'thinning') as $p)
		{
			if(get($p))
			{
				switch($p)
				{
					case 'time_from':
					case 'time_to':
						$params[$p] = get($p) . $this->user->timezone_offset;
						break;
					default:
						$params[$p] = get($p);
						break;
				}
			}
		}
		
		return $this->api->request('location/history', $params);
	}
	
	public function last_ajax()
	{
		if(get('token'))
			$response = $this->api->request('share/last?geoloqi_token=' . get('token'), FALSE, TRUE);
		else
			$response = $this->api->request('location/last?username=' . get('username'), FALSE, !array_key_exists('username', $_SESSION));
		return $response;
	}
	
	public function share_link_ajax()
	{
		$data['date_from'] = time();
		if(post('share_expiration'))
			$data['date_to'] = strtotime('+' . post('share_expiration') . ' minutes');
		$data['description'] = post('share_description');

		$response = $this->api->request('link/create', $data);

		ob_start();
?>
		<div class="share_popup">
			<div class="caption">Link created!</div>
			<input type="text" value="<?=$response->shortlink?>" style="width: 160px;" /><br />
			<div class="tweet_box">
<?php 
			if($this->user->twitter == '')
			{
				echo '<a href="/settings/connections">Connect your Twitter account</a> to send this link as a tweet!';
			}
			else
			{
?>
				<div class="tweet_this">Tweet this:</div>
				<textarea class="tweet_text">Heading out! Track me on @geoloqi: <?=$response->shortlink?></textarea><br />
				<input type="button" value="Close" class="btn_close" />
				<input type="button" value="Tweet" class="btn_tweet" />
				<div class="tweet_count">140</div>
<?php 
			}
?>
			</div>
		</div>
		<script type="text/javascript">
			$(".tweet_text").unbind("keyup").bind("keyup", function(){
				var remaining = 140 - $(this).val().length;
				$(".tweet_count").text(remaining);
				if(remaining > 12){
					$(".btn_tweet, .tweet_count").removeClass("warning").removeClass("disabled").addClass("ok");
					$(".btn_tweet").removeAttr("disabled");
				}else if(remaining >= 0){
					$(".btn_tweet, .tweet_count").removeClass("disabled").removeClass("ok").addClass("warning");
					$(".btn_tweet").removeAttr("disabled");
				}else{
					$(".btn_tweet, .tweet_count").removeClass("ok").removeClass("warning").addClass("disabled");
					$(".btn_tweet").attr("disabled", "disabled");
				}
			}).keyup();
			$(".btn_close").click(gb_hide);
			$(".btn_tweet").click(function(){
				var tweet_text = $(".tweet_text").val();
				gb_update("Tweeting...");
				$.post("/map/share_tweet.ajax",{
					tweet: tweet_text
				}, function(data){
					gb_hide();
				}, "json");
			});
		</script>
<?php	
		$response->html = ob_get_clean();
		return $response;
	}
	
	public function share_tweet_ajax()
	{
		$response = $this->api->request('link/tweet', array('text'=>post('tweet')));
		return $response;
	}
}
?>