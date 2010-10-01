<?php 
class Site_Oauth extends Site
{
	public function authorize()
	{
		$this->data['scopes'][] = array('scope'=>'last_location', 'description'=>'%name can see my exact last location');
		$this->data['scopes'][] = array('scope'=>'geonote', 'description'=>'%name can leave me Geonotes');
		$this->data['application_name'] = get('client_id');
		$this->data['requester_name'] = get('client_id');
	}
}
?>