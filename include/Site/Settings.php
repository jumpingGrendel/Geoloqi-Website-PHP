<?php 
class Site_Settings extends Site
{
	public function index()
	{
		
	}
	
	public function profile()
	{
		$this->data['profile_username'] = $this->user->username;
		$this->data['profile_email'] = $this->user->email;
		$this->data['profile_name'] = $this->user->name;
		$this->data['profile_bio'] = $this->user->bio;
		$this->data['profile_phone'] = $this->user->phone;
		$this->data['profile_website'] = $this->user->website;
		$this->data['profile_timezone'] = $this->user->timezone;
	}
	
	public function profile_ajax()
	{
		$response = $this->api->request('account/profile', array(
			'name' => post('name'),
			'bio' => post('bio'),
			'email' => post('email'),
			'phone' => post('phone'),
			'website' => post('website'),
			'timezone' => post('timezone')
		));
		$_SESSION['user_profile'] = $this->api->request('account/profile');
		return $response;
	}
	
	public function privacy()
	{
		$this->data['public_location'] = $_SESSION['user_privacy']->public_location;
		$this->data['public_geonotes'] = $_SESSION['user_privacy']->public_geonotes;
		$this->data['public_geonote_email'] = $_SESSION['user_privacy']->public_geonote_email;
		$this->data['default_share_expiration'] = $_SESSION['user_privacy']->default_share_expiration;
		$this->data['has_password'] = $_SESSION['user_privacy']->has_password;
	}
	
	public function privacy_ajax()
	{
		$response = $this->api->request('account/privacy', array(
			'public_location' => post('public_location'),
			'public_geonotes' => post('public_geonotes'),
			'public_geonote_email' => post('public_geonote_email'),
			'default_share_expiration' => post('default_share_expiration')
		));
		$_SESSION['user_privacy'] = $this->api->request('account/privacy');
		return $response;
	}
	
	public function password_ajax()
	{
		return $this->api->request('account/password', array(
			'current_password' => post('current_password'),
			'new_password_1' => post('new_password_1'),
			'new_password_2' => post('new_password_2')
		));
	}
	
	public function connections()
	{
		$response = $this->api->request('account/permanent_token');
		$this->data['instamapper_key'] = $_SESSION['user_profile']->instamapper_key;
		$this->data['permanent_token'] = $response->access_token;
	}
	
	public function connections_ajax()
	{
		$response = $this->api->request('account/profile', array(
			'instamapper_key' => post('instamapper_key')
		));
		$_SESSION['user_profile'] = $this->api->request('account/profile');
		return $response;
	}
	
	public function get_permanent_token_ajax()
	{
		return $this->api->request('account/permanent_token', array());
	}
	
	public function share()
	{
		
		
	}

	public function layer()
	{
		$this->data['user_layers'] = $this->api->request('layer/list', array());		
	}
	
}
?>