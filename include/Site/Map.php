<?php 
class Site_Map extends Site
{
	protected $force_login = FALSE;
	
	public function index($username)
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

		// If there was some error retrieving the user's profile or location (such as non-public location or invalid username), throw an error and stop
		if(k($profile, 'error') == 'user_not_found')
			$this->error(HTTP_NOT_FOUND, 'Not Found', 'Sorry, the user ' . $username . ' doesn\'t exist!');
		elseif(k($profile, 'error') != NULL)
			$this->error(HTTP_NOT_FOUND, $profile->error, $profile->error_description);
		
		if(k($last, 'error') == 'forbidden')
			$this->error(HTTP_FORBIDDEN, 'Private Profile', 'Sorry, this user is not sharing their location publicly.');
		elseif(k($last, 'error') != NULL)
			$this->error(HTTP_NOT_FOUND, $last->error, $last->error_description);
			
		$this->data['last'] = $last;
		
		$this->data['name'] = $profile->name;
		$this->data['username'] = $username;
		$this->data['bio'] = $profile->bio;
		$this->data['website'] = $profile->website;
		$this->data['self_map'] = $username == session('username'); // whether the user is looking at their own map
		
		$this->data['enable_geonotes'] = ($this->data['self_map'] || $profile->public_geonotes);
	}

	public function create_geonote_ajax()
	{
		$response = $this->api->request('geonote/create', array(
			'latitude' => post('lat'),
			'longitude' => post('lng'),
			'radius' => post('radius'),
			'text' => post('text')
		));
		return $response;
	}
	
	public function history_ajax()
	{
		$params = array();
		$params['sort'] = 'desc';
		
		foreach(array('after', 'count', 'thinning') as $p)
			if(get($p))
				$params[$p] =  get($p);

		$response = $this->api->request('location/history', $params);
		return $response;
	}
	
	public function last_ajax()
	{
		$response = $this->api->request('location/last?username=' . get('username'), FALSE, TRUE); //!array_key_exists('username', $_SESSION));
		return $response;
	}
}
?>