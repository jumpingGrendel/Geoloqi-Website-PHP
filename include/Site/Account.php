<?php 
class Site_Account extends Site
{
	public function login()
	{
		if($this->post)
		{
			$response = $this->api->request('oauth/token', array(
				'grant_type' => 'password',
				'username' => post('username'),
				'password' => post('password')
			));
	
			if(property_exists($response, 'error'))
			{
				$this->data['error'] = TRUE;
				$this->data['error_description'] = (property_exists($response, 'error_description') ? $response->error_description : $response->error);
			}
			else
			{
				$this->data['error'] = FALSE;
				$_SESSION['oauth_token'] = $response->access_token;
				$_SESSION['refresh_token'] = $response->refresh_token;
				
				$response = $this->api->request('account/profile');
				$_SESSION['user_profile'] = json_encode($response);
				$_SESSION['username'] = $response->username;
				$this->data['username'] = $_SESSION['username'];
			}
			$this->data['api_response'] = $response;
		}
	}
}
?>