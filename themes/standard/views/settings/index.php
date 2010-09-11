<?php 
header('Location: /settings/profile');
die();

include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<div>Settings</div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>