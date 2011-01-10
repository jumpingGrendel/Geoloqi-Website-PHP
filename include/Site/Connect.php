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

			try
			{
				$token = $twitter->getAccessToken(array('oauth_verifier'=>get('oauth_verifier')));
			
				if($token->oauth_token && $token->oauth_token_secret)
				{
					// User successfully authenticated to Twitter.

					if(session('username'))
					{
						// If the user is logged in, set the twitter credentials and username on their account.
						
						$response = $this->api->request('connect/twitter', array(
							'twitter_token' => $token->oauth_token,
							'twitter_secret' => $token->oauth_token_secret
						));
						
						if(property_exists($response, 'username'))
						{
							$profile = $this->api->request('account/profile');
							$_SESSION['user_profile'] = $profile;
							$_SESSION['username'] = $profile->username;
						
							header('Location: /settings/profile');
							die();
						}
						else
						{
							$this->error(HTTP_SERVER_ERROR, $response->error, k($response, 'error_description'));
						}
					}
					else
					{
						// If the user is not logged in, make a request to the API for Geoloqi access tokens using the Twitter tokens.
						// At this point, if the API doesn't have a record of this user, it will auto-create an account.
						
						// This looks very similar to the Site/Account.php/login() code
						$response = $this->api->request('oauth/token', array(
							'grant_type' => 'twitter',
							'twitter_token' => $token->oauth_token,
							'twitter_secret' => $token->oauth_token_secret
						));
		
						if(property_exists($response, 'error'))
						{
							$this->error(HTTP_SERVER_ERROR, $response->error, k($response, 'error_description'));
						}
						else
						{
							$_SESSION['oauth_token'] = $response->access_token;
							$_SESSION['refresh_token'] = $response->refresh_token;
							$_SESSION['twitter_auth'] = TRUE;
							
							$this->did_log_in();
							
							$this->data['error'] = FALSE;
							
							$this->redirect_after_login();
						}
						$this->data['api_response'] = $response;
					}
				}
				else
					$this->error(HTTP_SERVER_ERROR, 'Twitter Error', 'No tokens were provided in the response from Twitter.');
			}
			catch(EpiOAuthException $e)
			{
				$this->error(HTTP_SERVER_ERROR, 'Twitter Error', 'Unable to get an access token. Probably your request token has expired. Try logging in again.');
			}
		}
		else
		{
			// Authorize URL makes the user approve the app every time, authenticate redirects seamlessly after the initial connection is made
			try
			{
				$auth_url = $twitter->getAuthenticateUrl(null, array('oauth_callback' => 'http://' . $_SERVER['SERVER_NAME'] . '/connect/twitter'));
			}
			catch(EpiOAuthException $e)
			{
				$this->error(HTTP_SERVER_ERROR, 'Twitter Error', 'Unable to get the authentication URL from Twitter');
			}
			header('Location: ' . $auth_url);
			die();
		}
	}	
}
?>