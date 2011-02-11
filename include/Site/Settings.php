<?php 
require_once('Grammar.php');
require_once('InstamapperClient.php');
require_once('Geonames.php');

class Site_Settings extends Site
{
	public function index()
	{
		
	}
	
	public function profile()
	{
		$this->data['profile_username'] = $this->user->username;
		$this->data['profile_email'] = $this->user->email;
		$this->data['profile_name'] = $this->user->name;
		$this->data['profile_bio'] = $this->user->bio;
		$this->data['profile_phone'] = $this->user->phone;
		$this->data['profile_website'] = $this->user->website;
		$this->data['profile_timezone'] = $this->user->timezone;
		$this->data['is_anonymous'] = $this->user->is_anonymous;
		$this->data['has_custom_username'] = $this->user->has_custom_username;
		$this->data['has_push_token'] = $this->user->has_push_token;
		$this->data['has_password'] = $_SESSION['user_privacy']->has_password;
		$this->get_last_location();
	}
	
	public function profile_ajax()
	{
		$response = $this->api->request('account/profile', array(
			'name' => post('name'),
			'bio' => post('bio'),
			'email' => post('email'),
			'phone' => post('phone'),
			'website' => post('website'),
			'timezone' => post('timezone')
		));
		$_SESSION['user_profile'] = $this->api->request('account/profile');
		return $response;
	}
	
	public function privacy()
	{
		$this->data['public_location'] = $_SESSION['user_privacy']->public_location;
		$this->data['public_geonotes'] = $_SESSION['user_privacy']->public_geonotes;
		$this->data['public_geonote_email'] = $_SESSION['user_privacy']->public_geonote_email;
		$this->data['email_geonotes'] = $_SESSION['user_privacy']->email_geonotes;
		$this->data['default_share_expiration'] = $_SESSION['user_privacy']->default_share_expiration;
		$this->get_last_location();
	}
	
	public function privacy_ajax()
	{
		$response = $this->api->request('account/privacy', array(
			'public_location' => post('public_location'),
			'public_geonotes' => post('public_geonotes'),
			'public_geonote_email' => post('public_geonote_email'),
			'email_geonotes' => post('email_geonotes'),
			'default_share_expiration' => post('default_share_expiration')
		));
		$_SESSION['user_privacy'] = $this->api->request('account/privacy');
		return $response;
	}
	
	public function password_ajax()
	{
		$response = $this->api->request('account/password', array(
			'current_password' => post('current_password'),
			'new_password_1' => post('new_password_1'),
			'new_password_2' => post('new_password_2')
		));
		$_SESSION['user_privacy'] = $this->api->request('account/privacy');
		return $response;
	}
	
	public function connections()
	{
		$response = $this->api->request('account/connections');
		$this->data['connections'] = $response;
		
		$response = $this->api->request('account/permanent_token');
		$this->data['permanent_token'] = k($response, 'access_token');
		
		$this->data['instamapper_devicekey'] = $_SESSION['user_profile']->instamapper_devicekey;
	}
	
	public function connections_ajax()
	{
		// Log in to Instamapper and make a new device
		$instamapper = new InstamapperClient();
		$keys = $instamapper->add_device($this->user->username);
		
		if($keys == FALSE)
			return array('error'=>'Error creating Instamapper device. Please contact us to help solve this.');
		
		// Set the instamapper key in the profile
		$response = $this->api->request('account/profile', array(
			'instamapper_key' => $keys['api_key'],
			'instamapper_devicekey' => $keys['device_key']
		));
		// Re-fetch the user's profile and store it in the session
		$_SESSION['user_profile'] = $this->api->request('account/profile');

		return array('device_key' => $keys['device_key']);
	}
	
	public function get_permanent_token_ajax()
	{
		return $this->api->request('account/permanent_token', array());
	}
	
	public function share()
	{
		$links = $this->api->request('link/list');
		$this->data['active_links'] = array();
		$this->data['expired_links'] = array();
		
		foreach($links as $link)
		{
			$category = $link->currently_active ? 'active_links' : 'expired_links';
			
			$data = $link;
			
			if(strtotime($link->date_to) < time())
				$data->expires = 'Inactive ' . (strtotime($link->date_to) > time() - 60000 ? Grammar::timeAgoInWords($link->date_to) : '');
			else
				$data->expires = 'Expires in ' . (strtotime($link->date_to) > time() - 60000 ? Grammar::timeAgoInWords($link->date_to, 'n/j/Y', 'now', TRUE) : '');
			
			// TODO: Handle the case where both a date range and time range are specified
			// i.e. 6pm - 9pm from 10/1 through 10/30
			
			if($link->date_from && $link->date_to)
			{
				$from = new DateTime($link->date_from, new DateTimeZone('UTC'));
				$from->setTimeZone(new DateTimeZone($this->user->timezone));
				$to = new DateTime($link->date_to, new DateTimeZone('UTC'));
				$to->setTimeZone(new DateTimeZone($this->user->timezone));
				
				$fromDatePart = $from->format('n/j/Y');
				$toDatePart = $to->format('n/j/Y');
				$fromTimePart = $from->format('g:ia');
				$toTimePart = $to->format('g:ia');
				$fromYearPart = ($from->format('Y') == date('Y') ? '' : '/Y');
				$toYearPart = ($to->format('Y') == date('Y') ? '' : '/Y');
				
				if($fromDatePart == $toDatePart)
					$toFormatted = $toTimePart;
				else
					$toFormatted = $to->format('n/j' . $toYearPart . ' g:ia');
				
				$data->range = $from->format('n/j' . $fromYearPart . ' g:ia') . ' to ' . $toFormatted;
			}
			elseif($link->date_to == '' && $link->time_from == '' && $link->time_to == '')
			{
				// Never expires!
				if($link->date_from == '')
				{
					$data->range = '';
				}
				else
				{
					$from = new DateTime($link->date_from, new DateTimeZone('UTC'));
					$from->setTimeZone(new DateTimeZone($this->user->timezone));
					$fromTimePart = $from->format('g:ia');
					$fromYearPart = ($from->format('Y') == date('Y') ? '' : '/Y');
					$data->range = 'since ' . $from->format('n/j' . $fromYearPart . ' g:ia');
				}
				$data->expires = 'Never expires';
			}
			else
			{
				$from = new DateTime('2000-01-01T' . $link->time_from, new DateTimeZone('UTC'));
				$from->setTimeZone(new DateTimeZone($this->user->timezone));
				$to = new DateTime('2000-01-01T' . $link->time_to, new DateTimeZone('UTC'));
				$to->setTimeZone(new DateTimeZone($this->user->timezone));
				$dateTo = new DateTime($link->date_to, new DateTimeZone('UTC'));
				$dateTo->setTimeZone(new DateTimeZone($this->user->timezone));
				$toYearPart = ($dateTo->format('Y') == date('Y') ? '' : '/Y');
				
				$data->range = $from->format('g:ia') . ' to ' . $to->format('g:ia');
				
				// Links that were time-based that were manually expired will have a date_to set but no date_from set
				if($link->date_to)
					$data->range .= ' until ' . $dateTo->format('n/j' . $toYearPart);
				else
				{
					$data->range .= ' every day';
					$data->expires = 'Inactive';
				}
			}
			$data->date_from = $link->date_from;
			$data->date_to = $link->date_to;
			$data->url = WEBSITE_URL . '/' . $this->user->username . '/' . $link->token;
			$data->short_url = (WEBSITE_SHORTURL ? WEBSITE_SHORTURL . '/' . $link->token : FALSE);
			$this->data[$category][] = $data;
		}
	}

	public function share_ajax()
	{
		if(post('action') == 'expire')
		{
			$result = $this->api->request('link/expire', array('token'=>post('token')));
			if(k($result, 'result') == 'ok')
				return array('result'=>'ok', 'deleted'=>post('token'));
			else
				return $result;
		}
		else
			return array('result'=>'null');
	}
	
	public function layer()
	{
		$this->data['user_id'] = session('user_profile')->user_id;
		$this->data['user_layers'] = $this->api->request('layer/list');
		$this->data['layer_subscriptions'] = $this->api->request('layer/subscriptions');
		$this->data['layers_near_you'] = $this->api->request('layer/near_you');
		$this->data['featured_layers'] = $this->api->request('layer/featured');
	}

	public function layer_ajax()
	{
		if(post('action') == 'save-privacy')
		{
			$result = $this->api->request('', array('token'=>post('token')));
			if(k($result, 'result') == 'ok')
				return array('result'=>'ok', 'deleted'=>post('token'));
			else
				return $result;
		}
		else
			return array('result'=>'null');
	}
	
	protected function get_last_location()
	{
		$last = $this->api->request('location/last');
		$this->data['last_location'] = $last;
	}
	
	public function nearest_intersection_ajax()
	{
		$coords = explode(',', request('coords'));

		if(count($coords) != 2)
			return array('error' => 'Invalid Input');
		
		$text = Geonames::getNearestIntersectionWithCity(trim($coords[0]), trim($coords[1]));
		if($text)
			return array('name' => $text);
		else
			return array('error' => 'GeoNames Error');
	}
	
	public function app_tour()
	{
	
	}
}
?>