<?php
require_once('Site/Exception.php');
require_once('Model.php');

define('HTTP_OK', 200);
define('HTTP_CREATED', 201);
define('HTTP_FOUND', 302);
define('HTTP_SEE_OTHER', 303);
define('HTTP_NOT_MODIFIED', 304);
define('HTTP_BAD_REQUEST', 400);
define('HTTP_FORBIDDEN', 403);
define('HTTP_NOT_FOUND', 404);
define('HTTP_CONFLICT', 409);
define('HTTP_SERVER_ERROR', 500);
define('HTTP_NOT_IMPLEMENTED', 501);

class Site
{
	protected $user;

	/**
	 * The controller methods set variables in here which can be accessed by the views
	 */
	protected $data = array();
	
	/**
	 * Tags that go in the <head> section
	 */
	protected $head = array();
	
	/**
	 * The folder in "themes" to load all the html from
	 */
	protected $theme;
	
	/**
	 * The Geoloqi API object
	 */
	protected $api;
	
	/**
	 * Whether the method was accessed using GET or POST
	 */
	protected $post = FALSE;
	
	/**
	 * Store the names of the controller and method called
	 */
	protected $controller;
	protected $method;
	protected $is_ajax;
	
	/**
	 * Whether this page is only visible when the visitor is logged in
	 */
	protected $force_login = TRUE;
	
	/**
	 * Set by the respond() function when it's called from one of the methods.
	 * Intended to be used as a failsafe to ensure some output is written to the browser.
	 */
	public $responded = FALSE;
	
	public function __construct()
	{
		$this->post = strtolower($_SERVER['REQUEST_METHOD']) == 'post';
	}
	
	/**
	 * Authenticate the client
	 */
	public function auth()
	{
		$this->api = new GeoloqiAPI(GEOLOQI_CLIENT_ID, GEOLOQI_CLIENT_SECRET);

		
		
		return TRUE;
	}
	
	public function init($controller, $method, $is_ajax)
	{
		$this->controller = $controller;
		$this->method = $method;
		$this->is_ajax = $is_ajax;
		
		if(substr($_SERVER['SERVER_NAME'], 0, 2) == 'm.')
			$this->theme = 'mobile';
		else
			$this->theme = 'standard';

		// Some variables are required by the header and footer.
		// This also sets default values for variables that can be customized by the individual pages. 
		$this->data['title'] = 'Geoloqi';
		$this->data['theme_root'] = '/themes/' . $this->theme . '/assets/';
		$this->data['image_root'] = $this->data['theme_root'] . '/images/';
		$this->data['logged_in'] = array_key_exists('username', $_SESSION); // note: this does not guarantee the access token is still valid
		if($this->data['logged_in'])
		{
			$this->user = $_SESSION['user_profile'];
			$this->data['username'] = $_SESSION['username'];
		}
		if($this->force_login)
			$this->redirect_if_not_logged_in();
	}
	
	protected function redirect_if_not_logged_in()
	{
		if(!$this->data['logged_in'])
		{
			header('Location: /');
			die();
		}
	}
	
	/**
	 * The user just logged in, redirect them to the appropriate place depending on the state of their account
	 */
	protected function redirect_after_login()
	{
		if($_SESSION['user_profile']->phone == '')
			header('Location: /settings/profile');
		else
			header('Location: /' . $_SESSION['username']);
		die();
	}
	
	protected function theme_file($filename)
	{
		return $this->theme . '/views/' . $filename;
	}
	
	public function render($method, $params=FALSE)
	{
		$this->responded = TRUE;

		if(!$this->is_ajax)
		{
			// Call the method of the controller which will set a bunch of variables in $this->data
			$this->{$method}($params);
	
			extract($this->data);
	
			// Load the view which will output html and also set some headers in $this->head
			ob_start();
			include($this->theme_file($this->controller . '/' . $this->method . '.php'));
			$html = ob_get_clean();
	
			// Now that $this->head has been populated by the view, the header layout will be able to use it
			include($this->theme_file('layouts/header.php'));
			echo $html;
			include($this->theme_file('layouts/footer.php'));
		}
		else
		{
			// For AJAX calls, the method of the controller will return an object for the JSON response
			header('Content-type: text/javascript');
			echo json_encode($this->{$method}($params));
		}
	}

	public function error($code, $error, $msg)
	{
		header('HTTP/1.1 ' . $code . ' ' . $this->_codeString($code));
		
		$this->responded = TRUE;
		
		// Capture any outputted errors now so that we can return them in the response
		$output = trim(strip_tags(str_replace('<br>', "\n", ob_get_clean())));

		$response = array('error'=>$error, 'error_description'=>$msg);
		if(DEBUG_MODE && $output)
			$response['debug_output'] = trim(strip_tags(str_replace('<br>', "\n", $output)));
			
		if($this->is_ajax)
			echo json_encode($response);
		else
		{
			$this->data['error_description'] = $response['error_description'];
			if(DEBUG_MODE && $output)
				$this->data['debug_output'] = $response['debug_output'];
			else
				$this->data['debug_output'] = '';
			extract($this->data);
			include($this->theme_file('layouts/header.php'));
			include($this->theme_file('error/index.php'));
			include($this->theme_file('layouts/footer.php'));
		}
		
		die();
	}

	public function redirect($url)
	{
		header('Location: ' . $url);
		die();
	}
	
	public function stub()
	{
		header('HTTP/1.1 ' . HTTP_NOT_IMPLEMENTED . ' Method Not Implemented');
		pa(array('error'=>'method_not_implemented', 'error_description'=>'This method is expected to exist, but is not yet implemented'));
		die();
	}
	
	/**
	 * Translate HTTP codes into their corresponding string representations
	 */
	protected function _codeString($code)
	{
		switch($code)
		{
			case HTTP_OK:
				return 'OK';
			case HTTP_CREATED:
				return 'Created';
			case HTTP_FOUND:
				return 'Found';
			case HTTP_SEE_OTHER:
				return 'See Other';
			case HTTP_NOT_MODIFIED:
				return 'Not Modified';
			case HTTP_BAD_REQUEST:
				return 'Bad Request';
			case HTTP_FORBIDDEN:
				return 'Forbidden';
			case HTTP_NOT_FOUND:
				return 'Not Found';
			case HTTP_CONFLICT:
				return 'Conflict';
			case HTTP_SERVER_ERROR:
				return 'Internal Server Error';
			case HTTP_NOT_IMPLEMENTED:
				return 'Not Implemented';
			default:
				return 'Error';
		}
	}

	/**
	 * Converts base 10 to base 60. 
	 * http://tantek.pbworks.com/NewBase60
	 * @param int $n
	 * @return string
	 */	
	protected function _ds($n)
	{
		$s = "";
		$m = "0123456789ABCDEFGHJKLMNPQRSTUVWXYZ_abcdefghijkmnopqrstuvwxyz";
		if ($n==0) 
			return 0; 

		while ($n>0) 
		{
			$d = $n % 60;
			$s = $m[$d] . $s;
			$n = ($n-$d)/60;
		}
		return $s;
	}

	/**
	 * Converts base 60 to base 10, with error checking
	 * http://tantek.pbworks.com/NewBase60
	 * @param string $s
	 * @return int
	 */
	protected function _sd($s)
	{
		$n = 0;
		for($i = 0; $i < strlen($s); $i++) // iterate from first to last char of $s
		{
			$c = ord($s[$i]); //  put current ASCII of char into $c  
			if ($c>=48 && $c<=57) { $c=$c-48; }
			else if ($c>=65 && $c<=72) { $c-=55; }
			else if ($c==73 || $c==108) { $c=1; } // typo capital I, lowercase l to 1
			else if ($c>=74 && $c<=78) { $c-=56; }
			else if ($c==79) { $c=0; } // error correct typo capital O to 0
			else if ($c>=80 && $c<=90) { $c-=57; }
			else if ($c==95) { $c=34; } // underscore
			else if ($c>=97 && $c<=107) { $c-=62; }
			else if ($c>=109 && $c<=122) { $c-=63; }
			else { $c = 0; } // treat all other noise as 0
			$n = (60 * $n) + $c;
		}
		return $n;
	}
}
?>