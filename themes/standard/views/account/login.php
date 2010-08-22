<?php 
include($this->theme_file('layouts/site_header.php'));
?>

<div>
<?php
if($error)
{
	echo '<p>' . $error_description . '</p>';
	pa($api_response);
	pa($_SESSION);
}
else
{
	echo '<p>Logged in as: ' . $username . '</p>';
	echo '<p><a href="/' . $username . '">your map</a></p>';
}
?>
</div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>
