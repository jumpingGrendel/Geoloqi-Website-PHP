<?php 
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<div class="settings-wrap"><div class="settings">
<?php 
if(GEOLOQI_ENABLE_SHARED_LIST)
{
?>
	<div>
		<table style="border-spacing: 0px; margin: 0; width: 100%">
			<thead>
				<tr>
					<th colspan="4">
						<input type="button" class="submit" value="Remove" style="float: left" />
						<input type="button" class="submit" value="Add a New Link" style="float: right" />
					</th>
				</tr>
			</thead>
			<tbody>
			<tr style="background-color: #CCC">
				<td><input type="checkbox" name="" value="" /></td>
				<td colspan="3">Active Links</td>
			</tr>
			<?php
				foreach ($links as $link) {
					echo '<tr><td><input type="checkbox" name="" value="" /></td><td>Shared with: ';
					for ($i=0; $i<count($link[0]); $i++) {
						if ($i == count($link[0])-1) {
							echo $link[0][$i];
						} else {
							echo $link[0][$i].", ";
						}
					}
					echo '</td>';
					echo '<td>Expires at: '.$link[1].'</td><td>'.$link[2].' minutes</td></tr>';
				}
			?>
			</tbody>
		</table>
	</div>
<?php 
}
else
{
?>
	<div style="padding:20px;">Coming soon...</div>
<?php 
}
?>
</div></div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>