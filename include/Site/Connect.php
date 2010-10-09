<?php
require_once('EpiCurl.php');
require_once('EpiOAuth.php');
require_once('EpiTwitter.php');

class Site_Connect extends Site
{
	protected $force_login = FALSE;

	public function index()
	{
		
	}
	
	public function twitter()
	{
		$twitter = new EpiTwitter(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
		
		if(get('oauth_token'))
		{
			// Returned from the Twitter approval screen

			$twitter->setToken(get('oauth_token'));
			
			$token = $twitter->getAccessToken(array('oauth_verifier'=>get('oauth_verifier')));
			if($token->oauth_token && $token->oauth_token_secret)
			{
				// User successfully authenticated to Twitter.
				// Now make a request to the API for Geoloqi access tokens using the Twitter tokens.
				// At this point, if the API doesn't have a record of this user, it will auto-create an account.
				
				// This looks very similar to the Site/Account.php/login() code
				$response = $this->api->request('oauth/token', array(
					'grant_type' => 'twitter',
					'twitter_token' => $token->oauth_token,
					'twitter_secret' => $token->oauth_token_secret
				));

				if(property_exists($response, 'error'))
				{
					$this->data['error'] = TRUE;
					$this->data['error_description'] = (property_exists($response, 'error_description') ? $response->error_description : $response->error);
				}
				else
				{
					$_SESSION['oauth_token'] = $response->access_token;
					$_SESSION['refresh_token'] = $response->refresh_token;
					
					// TODO: This might be a redundant extra HTTP request
					$profile = $this->api->request('account/profile');
					
					$_SESSION['user_profile'] = $profile;
					$_SESSION['username'] = $profile->username;
					
					$this->data['error'] = FALSE;
					
					// TODO: What to do for brand new accounts?
					$this->redirect('/settings/profile');
				}
				$this->data['api_response'] = $response;
			}
		}
		else
		{
			// Authorize URL makes the user approve the app every time, authenticate redirects seamlessly after the initial connection is made
			$auth_url = $twitter->getAuthenticateUrl(null, array('oauth_callback' => 'http://' . $_SERVER['SERVER_NAME'] . '/connect/twitter'));
			header('Location: ' . $auth_url);
			die();
			echo '<a href="' . $auth_url . '">twitter</a>';
		}
	}	
}
?>