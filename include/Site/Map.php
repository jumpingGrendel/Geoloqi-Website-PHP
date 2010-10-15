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

		$this->data['last'] = $last;
		
		$this->data['name'] = $profile->name;
		$this->data['username'] = $username;
		$this->data['bio'] = $profile->bio;
		$this->data['website'] = $profile->website;
		$this->data['self_map'] = $username == $_SESSION['username']; // whether the user is looking at their own map
		
		// TODO: Configure this based on the user's privacy settings
		$this->data['enable_geonotes'] = TRUE;
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
		$response = $this->api->request('location/last?username=' . get('username'), FALSE, !array_key_exists('username', $_SESSION));
		return $response;
	}
}
?>