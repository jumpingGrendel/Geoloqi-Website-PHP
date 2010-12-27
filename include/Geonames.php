<?php
require_once('Site.php');

class Geonames
{
	protected static function _queryURL($url, $mcKey=FALSE)
	{
		if(MEMCACHE_ENABLED && ($data=mc()->get($mcKey)) != FALSE)
			$json = $data;
		else
		{
			$json = file_get_contents($url);

			if(!$json)
				return FALSE;

			$json = json_decode($json);

			if(MEMCACHE_ENABLED)
				mc()->set($mcKey, $json, 3600);
		}

		return $json;
	} 

	public static function getNearestIntersection($lat, $lng)
	{
		$url = 'http://ws.geonames.org/findNearestIntersectionJSON?formatted=true&lat=' . $lat . '&lng=' . $lng . '&style=full';
		$mcKey = Site::mcKey(get_called_class(), 'intersection/' . $lat . ',' . $lng);

		$json = self::_queryURL($url, $mcKey);

		$intersection = $json->intersection->street1 . ' & ' . preg_replace('/^(SE|SW|NE|NW|N|E|S|W) /', '', $json->intersection->street2);
		
		return $intersection;
	}

	public static function getNearestIntersectionWithCity($lat, $lng)
	{
		$url = 'http://ws.geonames.org/findNearestIntersectionJSON?formatted=true&lat=' . $lat . '&lng=' . $lng . '&style=full';
		$mcKey = Site::mcKey(get_called_class(), 'intersection:' . $lat . ',' . $lng);

		$json = self::_queryURL($url, $mcKey);

		$intersection = k($json->intersection, 'street1') . ' & ' 
			. preg_replace('/^(SE|SW|NE|NW|N|E|S|W) /', '', k($json->intersection, 'street2'))
			. ', ' . k($json->intersection, 'placename') . ', ' . k($json->intersection, 'adminName1');
				
		return $intersection;
	}
}
?>