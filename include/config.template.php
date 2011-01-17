<?php 
define('DEBUG_MODE', TRUE);

/**
 * Client ID, secret, and URL for communicating with the API
 */
define('GEOLOQI_CLIENT_ID', 'client_id');
define('GEOLOQI_CLIENT_SECRET', '12345678123456781234567812345678');
define('GEOLOQI_API_BASEURL', 'http://api.geoloqi.dev/1/');
define('GEOLOQI_API_BASEURL_SECURE', 'https://api.geoloqi.dev/1/');

define('WEBSITE_URL', 'http://geoloqi.com');
define('WEBSITE_SHORTURL', 'http://loqi.me'); // Set to FALSE to disable using short links

define('GEOLOQI_GA_ID', FALSE);

/**
 * Features in development
 */
define('GEOLOQI_ENABLE_LAYERS', FALSE);
define('GEOLOQI_ENABLE_SHARED_LIST', FALSE);
define('GEOLOQI_ENABLE_SHARED_SEND', FALSE);
define('GEOLOQI_ENABLE_MAPOPTIONS', FALSE);

/**
 * Enter your instamapper account here so the website can add devices to it
 */
define('INSTAMAPPER_USERNAME', '');
define('INSTAMAPPER_PASSWORD', '');
define('INSTAMAPPER_COOKIEFILE', '');

/**
 * Twitter client information
 * Go to http://dev.twitter.com to set up your application
 */
define('TWITTER_CONSUMER_KEY', '');
define('TWITTER_CONSUMER_SECRET', '');

/** 
 * Facebook app information
 * See http://developers.facebook.com for more information
 */
define('FACEBOOK_APP_ID', '');
define('FACEBOOK_APP_SECRET', '');

// Memcached settings. The API will work fine without memcached but will perform significantly better with it enabled.
define('MEMCACHE_ENABLED', FALSE);
// Prefix all memcached keys with this string
define('MEMCACHE_KEY_PREFIX', 'geoloqi_web');
$MEMCACHE_SERVERS = array();
$MEMCACHE_SERVERS[] = array(
	'host' => 'localhost',
	'port' => '11211'
);

?>