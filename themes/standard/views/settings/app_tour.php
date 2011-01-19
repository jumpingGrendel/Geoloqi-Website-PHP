<?php 
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'settings-sharing.js"></script>';
$this->head[] = '<link rel="stylesheet" href="' . $theme_root . 'mediawiki.css" type="text/css" />';
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<div class="settings-wrap"><div class="settings settings-columns">
	<?= $this->get_from_wiki('iPhone_App_Tour') ?>
</div></div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>