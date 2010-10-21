<?php 
class Site_Map extends Site
{
	protected $force_login = FALSE;
	
	public function index($username)
	{
		// Redirect 'me' to the current logged-in user
		if($username == 'me')
		{
			if(session('username'))
				header('Location: /' . session('username'));
			else
				header('Location: /');
			die();
		}

		// If there is a shared link token present, then make the API calls using the share endpoints
		if(get('key'))
		{
			$profile = $this->api->request('share/profile?geoloqi_token=' . get('key'), FALSE, TRUE);
			$last = $this->api->request('share/last?geoloqi_token=' . get('key'), FALSE, TRUE);

			// TODO: Make a nicer error message when a link has expireed
			if(k($profile, 'error') == 'invalid_token')
				$this->error(HTTP_OK, 'Expired', 'The shared link has expired!');
		}
		else
		{
			// If the user is logged in, make the call with their access token 
			if(array_key_exists('username', $_SESSION))
			{
				$profile = $this->api->request('account/profile?username=' . $username);
				$last = $this->api->request('location/last?username=' . $username);
			}
			else
			{
				// Attempt to make the API call with no user tokens. This will only succeed if 
				// the requested user's account is set to public
				$profile = $this->api->request('account/profile?username=' . $username, FALSE, TRUE);
				$last = $this->api->request('location/last?username=' . $username, FALSE, TRUE);
			}
		}

		// If there was some error retrieving the user's profile or location (such as non-public location or invalid username), throw an error and stop
		if(k($profile, 'error') == 'user_not_found')
			$this->error(HTTP_NOT_FOUND, 'Not Found', 'Sorry, the user "' . $username . '" doesn\'t exist!');
		elseif(k($last, 'error') == 'no_recent_location')
			$last = FALSE;
		elseif(k($profile, 'error') != NULL)
			$this->error(HTTP_NOT_FOUND, $profile->error, $profile->error_description);
		
		if(k($last, 'error') == 'forbidden')
			$this->error(HTTP_FORBIDDEN, 'Private Profile', 'Sorry, this user is not sharing their location publicly.');
		elseif(k($last, 'error') != NULL)
			$this->error(HTTP_NOT_FOUND, $last->error, $last->error_description);
			
		$this->data['last'] = $last;

		$this->data['name'] = $profile->name;
		$this->data['username'] = $profile->username;
		$this->data['bio'] = $profile->bio;
		$this->data['website'] = $profile->website;
		
		// whether the user is looking at their own map
		$this->data['self_map'] = $username == session('username');
		
		if(get('key'))
			$this->data['share_token'] = get('key');
		else
			$this->data['share_token'] = '';
		
		$this->data['enable_geonotes'] = ($this->data['self_map'] || $profile->public_geonotes);
		$this->data['default_share_expiration'] = ($this->data['self_map'] ? $_SESSION['user_privacy']->default_share_expiration : 0);
	}

	public function create_geonote_ajax()
	{
		$response = $this->api->request('geonote/create?username=' . get('username'), array(
			'latitude' => post('lat'),
			'longitude' => post('lng'),
			'radius' => post('radius'),
			'text' => post('text')
		), !array_key_exists('username', $_SESSION));
		return $response;
	}
	
	public function history_ajax()
	{
		$params = array();
		$params['sort'] = 'desc';
		
		foreach(array('after', 'count', 'thinning') as $p)
			if(get($p))
				$params[$p] =  get($p);

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

		return $this->api->request('link/create', $data);
	}
}
?>