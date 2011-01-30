<!DOCTYPE html>
<html>
<head>
	<title><?=$title?></title>
	<link rel="stylesheet" href="<?=$theme_root?>styles.css" type="text/css" />
	<meta name="description" content="A secure, real-time mobile and web platform for location sharing." />
	<meta name="og:description" content="A secure, real-time mobile and web platform for location sharing." />
	<meta name="og:title" content="Geoloqi" />
	<script type="text/javascript" src="<?=$theme_root?>jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?=$theme_root?>geoloqi.js"></script>
<?php 
	foreach($this->head as $h)
		echo "\t", $h, "\n";
?>
</head>
<body>
