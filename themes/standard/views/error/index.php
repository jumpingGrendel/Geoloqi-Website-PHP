<?php 
include($this->theme_file('layouts/site_header.php'));
?>

<div style="text-align: center">
	
	<div style="padding: 10px; color: #900; font-size: 26pt;">Sorry, there was an error!</div>
	
	<div style="">
		<img src="<?=$theme_root?>images/loqisaur-error.png" />
	</div>
	
	<div style="padding: 10px; color: #900; font-size: 14pt;"><?=$error_code . ': ' . $error?></div>
	
	<div style="padding-bottom: 10px; color: #600; font-size: 10pt;"><?=$error_description?></div>
	
	<div style="padding: 10px; color: #600; font-size: 10pt;"><?=$debug_output?></div>

</div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>