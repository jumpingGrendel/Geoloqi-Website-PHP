<?php 
class Site_Account extends Site
{
	protected $force_login = FALSE;
	
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
				$_SESSION['user_profile'] = $response;
				$_SESSION['username'] = $response->username;
				$this->data['username'] = $_SESSION['username'];
			}
			$this->data['api_response'] = $response;
			
			$this->redirect_after_login();
		}
	}
	
	public function logout()
	{
		session_destroy();
		$this->redirect('/');
	}
	
	public function unsubscribe()
	{
		if($this->post)
		{
			$this->api->request('user/unsubscribe', array(
				'email' => post('unsubscribe_email'),
				'referer' => session('unsubscribe_referer')
			));
			$this->data['confirmation'] = TRUE;
		}
		else
		{
			$_SESSION['unsubscribe_referer'] = k($_SERVER, 'HTTP_REFERER');
			
			if(get('key') && ($email=base64_decode(get('key'))))
				$this->data['email'] = $email;
			else
				$this->data['email'] = '';
		}
	}
}
?>