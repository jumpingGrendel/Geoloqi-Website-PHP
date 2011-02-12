<?php 
class GeoloqiAPI
{
	private $_clientID;
	private $_clientSecret;
	
	public function __construct($clientID, $clientSecret)
	{
		$this->_clientID = $clientID;
		$this->_clientSecret = $clientSecret;
	}
	
	protected function error($type)
	{
		// The server rejected our tokens, nothing we can do here. Log the user out and redirect to the home page.
		session_destroy();
		header('Location: /error?code=oauth_rejected_token_' . $type);
		die();
	} 
	
	/**
	 * Make a request to the Geoloqi API. If $doClientAuth is TRUE, then the request is made with only
	 * the client credentials and no access token. This can only be used to access API methods that don't
	 * require user-based access.
	 */
	public function request($method, $post=FALSE, $doClientAuth=FALSE)
	{
		ob_start();
		echo '<pre>';
		
		$ch = curl_init();
		
		$httpHeader = array();

		// TODO: Change this timezone to the logged-in user's timezone
		$httpHeader[] = 'Timezone: ' . date('c') . ';;America/Los_Angeles';
		
		if(substr($method, 0, 5) == 'oauth' || substr($method, 0, 4) == 'user' || $doClientAuth)
		{
			$client = array('client_id' => $this->_clientID, 'client_secret' => $this->_clientSecret);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, implode(':', $client));
			$baseURL = GEOLOQI_API_BASEURL_SECURE;
		}
		else
		{
			if(session('oauth_token'))
			{
				// Pass the OAuth token in the HTTP headers
				$httpHeader[] = 'Authorization: OAuth ' . $_SESSION['oauth_token'];
			}
			//else
				// We don't have an access token in the session, try without the token
				//$this->error();
					
			$baseURL = GEOLOQI_API_BASEURL;
		}
		
		curl_setopt($ch, CURLOPT_URL, $baseURL . $method);
	
		if(is_array($post))
		{
			$post = http_build_query($post, '', '&');
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		elseif(is_string($post))
		{
			$httpHeader[] = 'Content-Type: application/json';
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
		
		$response = curl_exec($ch);
	
		echo '<div style="background-color: #ffd; padding: 5px 0;">';
		echo "<b>REQUEST HEADERS:</b>\n";
		echo trim(curl_getinfo($ch, CURLINFO_HEADER_OUT)) . "\n\n";
		if($post)
		{
			echo "<b>REQUEST BODY:</b>\n";
			echo (is_array($post) ? http_build_query($post) : $post) . "\n\n";
		}
		echo '</div>';
		
		echo "<b>RESPONSE HEADERS:</b>\n";
		echo $response . "\n\n";
				
		$headers = array();
		$lines = explode("\n", $response);
		$endHeaders = FALSE;
		$newlines = 0;
		while($endHeaders == FALSE && count($lines) > 0)
		{
			$line = array_shift($lines);
			
			if(trim($line) == '')
			{
				$newlines++;
				if($newlines == 1)
					$endHeaders = TRUE;
				continue;
			}
			
			$line = explode(': ', $line);
			if(count($line) == 2)
			{
				list($k, $v) = $line;
				$headers[trim($k)] = trim($v);
			}
		}

		$body = implode("\n", $lines);
		
		$data = json_decode($body);

		echo "<b>JSON RESPONSE:</b>\n";
		
		if(is_object($data) && property_exists($data, 'debug_output'))
		{
			echo '<pre style="background-color:#eee; padding: 5px;">' . $data->debug_output . '</pre>';
			unset($data->debug_output);
		}	
		pa($data);
			
		if(array_key_exists('WWW-Authenticate', $headers))
		{
			if(preg_match('/error=\'expired_token\'/', $headers['WWW-Authenticate']))
			{
				// If the token expired, use the refresh token to get a new access token
				$response = $this->request('oauth/token', array(
					'grant_type' => 'refresh_token',
					'refresh_token' => $_SESSION['refresh_token']
				));

				if(property_exists($response, 'access_token'))
				{
					// Store the tokens in the session
					$_SESSION['oauth_token'] = $response->access_token;
					$_SESSION['refresh_token'] = $response->refresh_token;
			
					#echo "<b>SESSION</b>\n";
					#pa($_SESSION);
					
					ob_end_clean();
					
					// Try the original request again
					return $this->request($method, $post);
				}
				else
					// The server rejected the refresh token
					$this->error('refresh');
			}
			else
			{
				// The server rejected the request, but not because of an expired token. There's nothing more we can do
				$this->error('unknown');
			}
		}
		echo "\n";

		echo '</pre>';
		$this->log(ob_get_clean());

		// If the API responded with a 30x redirect header, redirect the browser
		// This will probably only happen on 3rd party OAuth flows
		if(k($headers, 'Location'))
			redirect($headers['Location']);
		
		if($data === null)
		{
			// If the API response could not be parsed as JSON, throw a hard error here and stop the script immediately
			$GLOBALS['controller']->error(HTTP_SERVER_ERROR, 'Bad API Response', ($body == '' ? '[empty response]' : '<div style="font-size:9pt; font-family: courier new;">' . trim($body) . '</pre>'));
			return $response;
		}
		else
			return $data;
	}
	
	protected function log($msg)
	{
		static $fp = FALSE;
		if(DEBUG_MODE)
		{
			if($fp == FALSE)
				$fp = fopen('api-log.htm', 'w');
			fwrite($fp, $msg . "\n");
		}
	}
}
?>