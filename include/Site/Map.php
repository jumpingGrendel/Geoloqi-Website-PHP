<?php 
class Site_Map extends Site
{
	public function index($username)
	{
		$this->data['username'] = $username;
	}
}
?>