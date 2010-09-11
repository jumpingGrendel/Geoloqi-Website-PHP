<?php 
ini_set('error_reporting', E_ALL);
define('DEBUG_MODE', TRUE);

// Start buffering any output so we capture error messages and can output them in a nice format with the proper HTTP headers
ob_start();

set_include_path(dirname(__FILE__) . '/include' . PATH_SEPARATOR . dirname(__FILE__) . '/themes' . PATH_SEPARATOR . get_include_path());

session_start();

include('config.php');
include('GeoloqiAPI.php');
include('Site.php');

$controllerName = get('controller');
$method = get('method');
$value = get('value');

if(get('mode') == 'ajax')
	$method .= '_ajax';

switch($controllerName)
{
	case 'map':
	case 'settings':
	case 'account':
	case 'home':
		require_once('Site/' . ucfirst($controllerName) . '.php');
		$className = 'Site_' . ucfirst($controllerName);
		$controller = new $className();
		break;
	default:
		$controller = new Site();
}

// Verify session data, etc
$controller->auth();

$controller->init($controllerName, $method, get('mode') == 'ajax');

try
{
	// Run the specified method of the class, or die with a 404 error
	if(is_callable(array($controller, $method)))
		$controller->render($method, $value);
	else
		$controller->error(HTTP_NOT_FOUND, 'method_not_found', 'Undefined method: ' . trim($method, '_'));
	
	if($controller->responded == FALSE)
		$controller->error(HTTP_SERVER_ERROR, 'no_output', 'Method "' . trim($method, '_') . '" returned no output');
}
catch(Exception $e)
{
	$controller->error(HTTP_SERVER_ERROR, 'exception', 'Internal error: ' . $e->getMessage());
}


/**
 * Helper functions for fetching data from GET/POST/REQUEST
 */
function get($key)
{
	if(array_key_exists($key, $_GET))
		return $_GET[$key];
	else
		return FALSE;
}
/**
 * Returns the value from POST. When the content-type header is set to application/json, the API controller
 * has already read in the raw post data and set $_POST to an object after decoding the post data from JSON.
 */
function post($key, $val=NULL)
{
	if(is_object($_POST))
	{
		if($val == NULL)
			if(property_exists($_POST, $key))
				return $_POST->{$key};
			else
				return FALSE;
		else
			$_POST->{$key} = $val;
	}
	else
	{
		if($val == NULL)
			if(array_key_exists($key, $_POST))
				return $_POST[$key];
			else
				return FALSE;
		else
			$_POST[$key] = $val;
	}
}
function request($key)
{
	if(array_key_exists($key, $_REQUEST))
		return $_REQUEST[$key];
	else
		return FALSE;
}
function session($key)
{
	if(array_key_exists($key, $_SESSION))
		return $_SESSION[$key];
	else
		return FALSE;
}

/**
 * Returns a handle to the DB object
 */
function db()
{
	static $db;
	if(!isset($db))
	{
		try {
			$db = new PDO(PDO_DSN, PDO_USER, PDO_PASS);
		} catch (PDOException $e) {
			header('HTTP/1.1 500 Server Error');
			die(json_encode(array('error'=>'database_error', 'error_description'=>'Connection failed: ' . $e->getMessage())));
		}
	}
	return $db;
}

/**
 * For HTML formatting arrays for debugging
 */
function pa($a)
{
	echo '<pre>';
	print_r($a);
	echo '</pre>';
}

?>