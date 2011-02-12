<?php 
class Site_Oauth extends Site
{
	protected $force_login = FALSE;

	public function authorize()
	{
		if($this->post)
		{
			$params = array('accept', 'response_type', 'client_id', 'redirect_uri', 'state', 'scope');
			$data = array();
			foreach($params as $p)
				$data[$p] = post($p);

			if(post('accept') == 'Log In')
			{
				// The user is not logged in and they clicked the "Log In" button which means they want to approve the connection
				// Since they're not logged in, we need to log them in first. For now, we're just doing Twitter logins, so redirect there.
				
				// Store this URL in the session so they'll be redirected here after logging in with Twitter
				$_SESSION['redirect_after_login'] = '/oauth/complete_authorization';
				
				// Store the POST variables from the OAuth request so we can get back to them after coming back from Twitter
				$_SESSION['oauth_connection_data'] = $data;

				// Redirect to the Twitter connection URL which will automatically redirect to twitter.com's auth
				redirect('/connect/twitter');
			}
			else
			{
				// They have to be logged in at this point
				$this->redirect_if_not_logged_in();
	
				// Pass the logged-in user's username to the API
				$data['username'] = $_SESSION['username'];
	
				// Alias the client_id parameter to client, since the API treats "client_id" as the OAuth client
				$data['client'] = $data['client_id'];
				unset($data['client_id']);
				
				$response = $this->api->request('oauth/finish_client_authorization', $data, TRUE);
			}
		}
		else
		{
			$client = $this->api->request('oauth/client_information?client=' . get('client_id'));

			if(k($client, 'error'))
				$this->error(HTTP_NOT_FOUND, 'Application Not Found', 'The application "' . get('client_id') . '" was not found.');
			
			$params = array('response_type', 'client_id', 'redirect_uri', 'state', 'scope');
			$this->data['auth_params'] = array();
			foreach($params as $p)
				if(get($p))
					$this->data['auth_params'][$p] = get($p);

			$this->data['auth_params'] = $this->api->request('oauth/get_authorize_params?' . http_build_query($_GET, '', '&'));

			// TODO: when implementing scopes, only show the scopes here that are requested by the app
			$this->data['scopes'][] = array('scope'=>'last_location', 'description'=>'see my exact last location');
			$this->data['scopes'][] = array('scope'=>'geonote', 'description'=>'leave me Geonotes');
			#$this->data['scopes'][] = array('scope'=>'layer', 'description'=>'create layers in my account');
			$this->data['scopes'][] = array('scope'=>'subscribe', 'description'=>'subscribe me to layers');
			#$this->data['scopes'][] = array('scope'=>'update_profile', 'description'=>'update my profile information');
			#$this->data['scopes'][] = array('scope'=>'location_history', 'description'=>'read my entire location history');
			#$this->data['scopes'][] = array('scope'=>'share', 'description'=>'create shared links to access my location');
			$this->data['application_name'] = $client->application_name;
			$this->data['requester_name'] = $client->requester_name;
		}
	}
	
	public function complete_authorization()
	{
		if(!session('oauth_connection_data'))
			$this->error(HTTP_SERVER_ERROR, 'missing_oauth_data', 'Something went wrong during authorization. Please start over.');

		// They have to be logged in at this point to have gotten here
		$this->redirect_if_not_logged_in();

		$data = $_SESSION['oauth_connection_data'];
		unset($_SESSION['oauth_connection_data']);

		$data['accept'] = 'Allow';
		
		// Pass the logged-in user's username to the API
		$data['username'] = $_SESSION['username'];

		// Alias the client_id parameter to client, since the API treats "client_id" as the OAuth client
		$data['client'] = $data['client_id'];
		unset($data['client_id']);
		
		$response = $this->api->request('oauth/finish_client_authorization', $data, TRUE);
	}
}
?>