<?php 
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<div class="settings-wrap"><div class="settings settings-stacked">
<table>
	<tr>
		<td colspan="2">
			<div class="label">Show public location</div>
			<input type="checkbox" name="public_location" class="checkbox" />
			<div class="description">
				Warning: This will make your exact position available on your map and through the API.
			</div>
		</td>
	</tr>
	<tr class="coming-soon">
		<td colspan="2">
			<div class="label">Allow public Geonotes</div>
			<input type="checkbox" name="public_location" class="checkbox" />
			<div class="description">
				Check this if you want to receive Geonotes from people when they visit your map page.
			</div>
		</td>
	</tr>
	<tr class="coming-soon">
		<td colspan="2">
			<div class="label">Allow email confirmation of public Geonotes</div>
			<input type="checkbox" name="public_location" class="checkbox" />
			<div class="description">
				Do you want to let people receive an email confirmation when you pick up their Geonote?
			</div>
		</td>
	</tr>
	<tr class="coming-soon">
		<td colspan="2">
			<div class="label">Default link expiration</div>
			<select>
				<option>10 minutes</option>
				<option>20 minutes</option>
				<option>30 minutes</option>
				<option>1 hour</option>
				<option>8 hours</option>
				<option>never</option>
			</select>
			<div class="description">
				When sending a time-sensitive link from the website or mobile app, this is the default expiration.
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" value="Save" class="submit" />
		</td>
	</tr>
</table>
</div></div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>