<?php
class Site_Layer extends Site
{
	protected $force_login = FALSE;
	
	/**
	 * Only used by the mobile app vhost
	 */
	public function description($id)
	{
		if(get('oauth_token') || session('oauth_token'))
		{
			// This logs the person in to the app's vhost
			if(get('oauth_token'))
				$this->log_in_from_token();

			$response = $this->api->request('layer/info/' . $id . '?count_valid_places=1');

			if($response->type == 'autocheckin')
			{
				// Set a session variable to tell our connect script to redirect here after authenticating with Foursquare
				$this->data['foursquare_connect_redirect'] = https() . $_SERVER['SERVER_NAME'] . '/layer/description/' . $id;
			}
		}
		else
		{
			$response = $this->api->request('layer/info/' . $id . '?count_valid_places=1', FALSE, TRUE);
		}
		$this->data['layer'] = $response;
		$this->data['description'] = (property_exists($response, 'description') ? $response->description : '');
	}
}
?>