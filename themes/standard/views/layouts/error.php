<?php 
include($this->theme_file('layouts/site_header.php'));
?>

<div style="padding: 10px; color: #900; font-size: 22pt;">Error: <?=$error?></div>

<div style="padding: 10px; color: #600; font-size: 12pt;"><?=$error_description?></div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>