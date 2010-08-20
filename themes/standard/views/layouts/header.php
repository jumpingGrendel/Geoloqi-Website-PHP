<!DOCTYPE html>
<html>
<head>
	<title><?=$title?></title>
	<link rel="stylesheet" href="<?=$theme_root?>styles.css" type="text/css" />
	<meta name="description" content="An open source website and mobile app for securely sharing location data with GPS and SMS" />
	<script type="text/javascript" src="<?=$theme_root?>jquery-1.4.2.min.js"></script>
<?php 
	foreach($this->head as $h)
		echo "\t", $h, "\n";
?>
</head>
<body>
