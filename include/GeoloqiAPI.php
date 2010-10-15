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
	
	protected function error()
	{
		// The server rejected our tokens, nothing we can do here. Log the user out and redirect to the home page.
		session_destroy();
		header('Location: /');
		die();
	} 
	
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
			else
				// We don't have an access token in the session, bye!
				$this->error();
					
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
		while($endHeaders == FALSE && count($lines) > 0)
		{
			$line = array_shift($lines);
			if(substr($line, 0, 1) == '{' || substr($line, 0, 1) == '[')
			{
				$endHeaders = TRUE;
				array_unshift($lines, $line);
			}
			else
			{
				$line = explode(': ', $line);
				if(count($line) == 2)
				{
					list($k, $v) = $line;
					$headers[trim($k)] = trim($v);
				}
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
					$this->error();
			}
			else
			{
				// The server rejected the request, not because of an expired token. There's nothing more we can do
				$this->error();
			}
		}
		echo "\n";

		echo '</pre>';
		$this->log(ob_get_clean());
		
		return $data;
	}
	
	protected function log($msg)
	{
		static $fp = FALSE;
		if($fp == FALSE)
			$fp = fopen('api-log.htm', 'w');
		fwrite($fp, $msg . "\n");
	}
}
?>