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
		$this->data['profile_website'] = $this->user->website;
		$this->data['profile_timezone'] = $this->user->timezone;
	}
	
	public function profile_ajax()
	{
		$response = $this->api->request('account/profile', array(
			'name' => post('name'),
			'bio' => post('bio'),
			'email' => post('email'),
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
		
		
	}
	
	public function share()
	{
		
		
	}
}
?>