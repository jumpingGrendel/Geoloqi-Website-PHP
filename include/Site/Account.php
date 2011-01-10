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
				
				$this->did_log_in();
			}
			$this->data['api_response'] = $response;

			if($this->data['error'] == FALSE)
				$this->redirect_after_login();
		}
		else
		{
			$this->redirect_if_already_logged_in();
		}
	}
	
	public function logout()
	{
		session_destroy();
		if(!session('twitter_auth'))
			header('Location: /');
	}
	
	public function setup()
	{
		if($this->post)
		{
			// Make API call
			$response = $this->api->request('user/setup', array(
				'key' => post('key'),
				'password1' => post('password1'),
				'password2' => post('password2'),
				'phone' => post('phone')
			), TRUE);

			if(property_exists($response, 'error'))
			{
				$this->data['error'] = $response->error;
				$this->data['error_description'] = (property_exists($response, 'error_description') ? $response->error_description : $response->error);
				$this->data['api_response'] = $response;
			}
			else
			{
				$username = $response->username;
				
				$response = $this->api->request('oauth/token', array(
					'grant_type' => 'password',
					'username' => $username,
					'password' => post('password1')
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
	
					$this->did_log_in();
				}
				$this->data['api_response'] = $response;
	
				if($this->data['error'] == FALSE)
					$this->redirect_after_login();
			}
		}
		else
		{
			$this->data['key'] = get('value');
		}
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