<?php 
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'settings-profile.js"></script>';
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<div class="settings-wrap"><div class="settings settings-columns">
<table>
	<tr>
		<td class="left">
			<div class="label">Username</div>
		</td>
		<td class="right">
			<?=$profile_username?>
			<div class="description">
				Your Geoloqi profile is located at <a href="http://<?=$_SERVER['SERVER_NAME'] . '/' . $profile_username?>"><?=$_SERVER['SERVER_NAME'] . '/' . $profile_username?></a>. Your username cannot be changed.
			</div>
		</td>
	</tr>
	<tr>
		<td class="left">
			<div class="label">Name</div>
		</td>
		<td class="right">
			<input type="text" name="name" id="profile_name" value="<?=$profile_name?>" class="field" />
			<div class="description">
				Your real name.
			</div>
		</td>
	</tr>
	<tr>
		<td class="left">
			<div class="label">Email</div>
		</td>
		<td class="right">
			<input type="text" name="email" id="profile_email" value="<?=$profile_email?>" class="field" />
			<div class="description">
				You'll need a valid email email address to use Geoloqi. We use your email address to get in contact with you.
			</div>
		</td>
	</tr>
	<tr>
		<td class="left">
			<div class="label">Phone</div>
		</td>
		<td class="right">
			<input type="text" name="phone" id="profile_phone" value="<?=$profile_phone?>" class="field<?=($profile_phone ? '' : ' highlight')?>" />
			<div class="description<?=($profile_phone ? '' : ' highlight')?>">
				Enter your cell phone number to receive Geonotes by SMS.
			</div>
		</td>
	</tr>
	<tr>
		<td class="left">
			<div class="label">Bio</div>
		</td>
		<td class="right">
			<textarea cols="30" rows="5" name="bio" id="profile_bio" class="field"><?=$profile_bio?></textarea>
			<div class="description">
				Fill this out if you want to. It might help people get to know you better.
			</div>
		</td>
	</tr>
	<tr>
		<td class="left">
			<div class="label">Website</div>
		</td>
		<td class="right">
			<input type="text" name="website" id="profile_website" value="<?=$profile_website?>" class="field" />
			<div class="description">
				Your website address will appear on your profile page.
			</div>
		</td>
	</tr>
	<tr>
		<td class="left">
			<div class="label">Time Zone</div>
		</td>
		<td class="right">
			<select id="profile_timezone" name="timezone" class="field">
			<?php
			$tz = array(
				'America/Boise', 
				'America/Chicago', 
				'America/Juneau',
				'America/Los_Angeles', 
				'America/New_York', 
				'America/Phoenix', 
				'Europe/Berlin',
				'Europe/Copenhagen',
				'Europe/London',
				'Europe/Madrid',
				'Europe/Oslo',
				'Europe/Paris',
				'UTC'
			);
			foreach ($tz as $z) {
				if ($z == $profile_timezone) {
					echo '<option value="'.$z.'" selected>'.$z.'</option>';
				} else {
					echo '<option value="'.$z.'">'.$z.'</option>';
				}
			}
			?>
			</select>
			<div class="description">
				Times will appear in the time zone you set here.
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" value="Save" class="submit" id="btn_save" />
		</td>
	</tr>
</table>
</div></div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>