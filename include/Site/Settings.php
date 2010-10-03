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
}
?>