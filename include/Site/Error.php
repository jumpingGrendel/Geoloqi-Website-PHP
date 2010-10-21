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
			case 'oauth_rejected_token_unknown':
				$this->data['error'] = k($_GET, 'error', 'Unknown OAuth Error');
				$this->data['error_description'] = 'The API rejected the website\'s OAuth request.';
				break;
			default:
				$this->data['error'] = k($_GET, 'error', 'Unknown Error');
				$this->data['error_description'] = '';
				break;
		}

		$this->data['debug_output'] = '';
	}
}
?>