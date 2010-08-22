<?php 
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<div>Profile</div>

<table>
	<tr>
		<td>Name</td>
		<td><input type="text" name="name" id="profile_name" value="" /></td>
	</tr>
	<tr>
		<td>Email</td>
		<td><input type="text" name="email" id="profile_email" value="<?=$profile_email?>" /></td>
	</tr>
	<tr>
		<td>Bio</td>
		<td><textarea cols="30" rows="6" name="bio" id="profile_bio"></textarea></td>
	</tr>
</table>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>