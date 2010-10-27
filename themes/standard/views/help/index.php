<?php
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));

$topics[] = array('question'=>'Who are you guys?', 'answer'=>'We are people who like GPS.');

echo '<div class="settings-wrap"><div class="settings" style="padding: 0 20px;">';

foreach($topics as $t)
{
	echo '<h3>' . $t['question'] . '</h3>';
	echo '<p>' . $t['answer'] . '</p>';
}

echo '</div></div>';

?>