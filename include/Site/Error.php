<?php 
class Site_Error extends Site 
{
	protected $force_login = FALSE;
	
	public function index()
	{
		switch(get('code'))
		{
			case 'oauth_rejected_token_refresh':
				$this->data['error'] = k($_GET, 'error', 'Bad OAuth Refresh Tokens');
				$this->data['error_description'] = 'The API rejected the refresh token. Try logging in again.';
				break;
			case 'oauth_rejected_token_access':
				$this->data['error'] = k($_GET, 'error', 'Login timed out');
				$this->data['error_description'] = 'Login timed out. Please go back and try again.';
				break;
			case 'oauth_rejected_token_unknown':
				$this->data['error'] = k($_GET, 'error', 'Unknown OAuth Error');
				$this->data['error_description'] = 'The API rejected the website\'s OAuth request.';
				break;
			default:
				$this->data['error'] = k($_GET, 'error', 'Unknown Error');
				$this->data['error_description'] = '';
				break;
		}

		$this->data['error_code'] = get('code');
		$this->data['debug_output'] = '';
	}
}
?>