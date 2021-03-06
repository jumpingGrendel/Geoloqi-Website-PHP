<?php
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'settings-privacy.js"></script>';
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<table cellpadding="0" cellspacing="0" border="0"><tr>
<td valign="top">

<div class="settings-wrap"><div class="settings settings-stacked">

<table>
	<tr>
		<td colspan="2">
			<div class="label">Show public location</div>
			<input type="checkbox" id="public_location" class="checkbox" <?= $public_location ? 'checked' : '' ?> />
			<div class="description">
				Warning: This will make your exact position available on your map and to third-party applications.
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div class="label">Allow public Geonotes</div>
			<input type="checkbox" id="public_geonotes" class="checkbox" <?= $public_geonotes ? 'checked' : '' ?> />
			<div class="description">
				Check this if you want to receive Geonotes from people when they visit your map page.
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div class="label">Allow email confirmation of public Geonotes</div>
			<input type="checkbox" id="public_geonote_email" class="checkbox" <?= $public_geonote_email ? 'checked' : '' ?> />
			<div class="description">
				Do you want to let people receive an email confirmation when you pick up their Geonote?
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div class="label">Default link expiration</div>
			<select id="default_share_expiration">
			<?php
			$dt = array('10'=>'10 minutes', '30'=>'30 minutes', '60'=>'1 hour', '120'=>'2 hours', '480'=>'8 hours', '0'=>'never');
			foreach ($dt as $k=>$t) {
				if ($k == $default_share_expiration) {
					echo '<option value="'.$k.'" selected>'.$t.'</option>';
				} else {
					echo '<option value="'.$k.'">'.$t.'</option>';
				}
			}
			?>
			</select>
			<div class="description">
				When sending a time-sensitive link from the website or mobile app, this is the default expiration.
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<a name="email"></a>
			<div class="label">Send me an email when</div>
			<input type="checkbox" id="email_geonotes" class="checkbox" <?= $email_geonotes ? 'checked' : '' ?> />
			<div class="description">I pick up a Geonote</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" value="Save" class="btn" id="btn_save" />
		</td>
	</tr>
</table>
</div></div>

</td>
<td width="340" id="right_panel">
	<?php include($this->theme_file('layouts/right_panel.php')); ?>
</td>

</tr></table>


<?php 
include($this->theme_file('layouts/site_footer.php'));
?>