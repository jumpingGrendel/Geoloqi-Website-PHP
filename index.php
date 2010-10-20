<?php 
ini_set('error_reporting', E_ALL);
define('DEBUG_MODE', TRUE);

// Start buffering any output so we capture error messages and can output them in a nice format with the proper HTTP headers
ob_start();

set_include_path(dirname(__FILE__) . '/include' . PATH_SEPARATOR . dirname(__FILE__) . '/themes' . PATH_SEPARATOR . get_include_path());

session_start();

require_once('inc.php');
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
	case 'connect':
	case 'map':
	case 'settings':
	case 'account':
	case 'home':
	case 'oauth':
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

?>