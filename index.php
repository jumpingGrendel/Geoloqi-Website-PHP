<?php 
ini_set('error_reporting', E_ALL);

// Start buffering any output so we capture error messages and can output them in a nice format with the proper HTTP headers
ob_start();

/**
 * Check for the required config.php file 
 */
if(!file_exists(dirname(__FILE__) . '/include/config.php'))
{
	die('Setup not complete: Copy config.template.php to config.php and modify the configuration settings to match your environment.');
}

session_start();

require_once('functions.php');
require_once('config.php');
require_once('GeoloqiAPI.php');
require_once('Site.php');

$controllerName = get('controller');
$method = get('method');
$value = get('value');

if(get('mode') == 'ajax')
	$method .= '_ajax';

switch($controllerName)
{
	case 'about':
	case 'connect':
	case 'map':
	case 'settings':
	case 'account':
	case 'home':
	case 'help':
	case 'oauth':
	case 'post':
	case 'layer':
	case 'error':
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
	ob_start();
	echo 'Internal Error:<br />';
	echo '<p>' . $e->getMessage() . '</p>';
	if(DEBUG_MODE)
	{
		echo '<div style="font-size:8pt; font-family: Courier New, courier, fixed-width; white-space: pre-wrap; word-wrap: break-word;">';
			print_r($e);
		echo '</div>';
	}
	$msg = ob_get_clean();
	$controller->error(HTTP_SERVER_ERROR, 'Uncaught Exception', $msg);
}

/**
 * In production mode, this function sends a redirect header and quits. In debug mode, displays a 
 * web link to manually step through any redirections that are taking place.
 */
function redirect($url, $reason='')
{
	if(DEBUG_MODE)
	{
		echo '<a href="' . $url . '">' . $url . '</a>';
		if($reason)
			echo '<p>' . $reason . '</p>';
			
		$backtrace = debug_backtrace();
		if(is_array($backtrace) && array_key_exists(0, $backtrace))
		{
			$base = dirname(__FILE__);
			echo '<p>' . $_SERVER['SERVER_NAME'] . '<br />';
			echo $_SERVER['REQUEST_URI'] . '</p>';
			echo '<p>' . str_replace($base, '', $backtrace[0]['file']) . ' on line ' . $backtrace[0]['line'] . '</p>';
		} 
	}
	else
		header('Location: ' . $url);
	die();
}

?>