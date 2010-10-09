<?php 
class Site_Map extends Site
{
	protected $force_login = FALSE;
	
	public function index($username)
	{
		// Right now, users can only see their own profile. Redirect if they're looking at someone else's page.
		if($_SESSION['username'] != $username)
			$this->redirect('/' . $_SESSION['username']);
				
		$profile = $this->api->request('account/profile');
		$last = $this->api->request('location/last');
		
		$this->data['last'] = $last;
		
		$this->data['name'] = $profile->name;
		$this->data['username'] = $username;
		$this->data['bio'] = $profile->bio;
		$this->data['website'] = $profile->website;
		
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
	
}
?>