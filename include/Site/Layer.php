<?php
class Site_Layer extends Site
{
	protected $force_login = FALSE;
	
	public function description($id)
	{
		if(get('oauth_token'))
		{
			$_SESSION['oauth_token'] = get('oauth_token');
			$response = $this->api->request('layer/info/' . $id . '?count_valid_places=1');
		}
		else
		{
			$response = $this->api->request('layer/info/' . $id . '?count_valid_places=1', FALSE, TRUE);
		}
		$this->data['layer'] = $response;
	}
}
?>