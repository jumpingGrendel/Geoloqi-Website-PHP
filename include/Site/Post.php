<?php
require_once('EpiCurl.php');
require_once('EpiOAuth.php');
require_once('EpiTwitter.php');

class Site_Post extends Site 
{
	protected $force_login = FALSE;
	
	public function twitter_connect()
	{
		if(get('oauth_token'))
		{
			// The first visit from the mobile app will include an oauth_token from Geoloqi
			if(get('oauth_token') && !get('oauth_verifier'))
				$_SESSION['oauth_token'] = get('oauth_token');

			$twitter = new EpiTwitter(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);

			// oauth_verifier is set when returning from Twitter
			if(get('oauth_verifier'))
			{
				// Returned from the Twitter approval screen
	
				$twitter->setToken(get('oauth_token'));
	
				try
				{
					$token = $twitter->getAccessToken(array('oauth_verifier'=>get('oauth_verifier')));
				
					if($token->oauth_token && $token->oauth_token_secret)
					{
						// User successfully authenticated to Twitter.
						// Store the Twitter stuff in the database and tell the user they're done!

						$response = $this->api->request('connect/twitter', array(
							'twitter_token' => $token->oauth_token,
							'twitter_secret' => $token->oauth_token_secret
						));
						
						if(property_exists($response, 'username'))
						{
							$this->data['username'] = $response->username;
						}
						else
						{
							$this->error(HTTP_SERVER_ERROR, $response->error, k($response, 'error_description'));
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
					$auth_url = $twitter->getAuthenticateUrl(null, array('oauth_callback' => 'http://' . $_SERVER['SERVER_NAME'] . '/post/twitter_connect'));
				}
				catch(EpiOAuthException $e)
				{
					$this->error(HTTP_SERVER_ERROR, 'Twitter Error', 'Unable to get the authentication URL from Twitter');
				}
				header('Location: ' . $auth_url);
				die();
			}
		}
		else if(get('debug'))
		{
		
		}
		else
		{
			$this->error(HTTP_FORBIDDEN, 'access_denied', 'You must visit this page from within the app!'); 
		}
	}
}