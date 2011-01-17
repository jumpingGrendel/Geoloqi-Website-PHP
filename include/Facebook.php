<?php

class Facebook
{
	public $accessToken;
	private $_appID;
	private $_appSecret;
	private $_baseRedirect;
		
	public function __construct($appID, $appSecret, $baseRedirect=FALSE)
	{
		$this->_appID = $appID;
		$this->_appSecret = $appSecret;
		$this->_baseRedirect = $baseRedirect;
	}

	public function authorizeURL($scopes=array(), $display='')
	{
		$params = array(
			'client_id' => $this->_appID,
			'redirect_uri' => $this->_buildRedirectURI(),
			'scope' => implode(',', $scopes)
		);
		if($display)
			$params['display'] = $display;
			
		return 'https://graph.facebook.com/oauth/authorize?' . http_build_query($params);
	}

	public function isCallback()
	{
		return array_key_exists('oauth_callback', $_GET);
	}

	public function callback()
	{
		$params = array(
			'client_id' => $this->_appID,
			'redirect_uri' => $this->_buildRedirectURI(),
			'client_secret' => $this->_appSecret,
			'code' => $_GET['code']
		);
	
		$token = $this->_request('https://graph.facebook.com/oauth/access_token?' . http_build_query($params));
	
		parse_str($token, $response);
	
		if(array_key_exists('access_token', $response))
		{
			$this->accessToken = $response['access_token'];
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function _request($url, $post=FALSE)
	{
		return file_get_contents($url);
	}

	public function graph($method, $params=array())
	{
		$url = 'https://graph.facebook.com/' . $method . '?access_token=' . $this->accessToken;
	
		$json = @file_get_contents($url);
		if($json)
			return json_decode($json);
		else
			return FALSE;
	}
	
	private function _buildRedirectURI()
	{
		return $this->_baseRedirect . '?oauth_callback';
	}

}