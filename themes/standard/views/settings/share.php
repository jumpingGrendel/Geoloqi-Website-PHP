<?php 
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'settings-sharing.js"></script>';
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<div class="settings-wrap"><div class="settings tabular">
<?php 
if(GEOLOQI_ENABLE_SHARED_LIST)
{
?>
	<div>
		<table style="border-spacing: 0px; margin: 0; width: 100%">
			<thead>
				<tr>
					<th colspan="4">
						<input type="button" class="submit" value="New Link" style="float: right" />
					</th>
				</tr>
			</thead>
			<tbody>
			<tr style="background-color: #CCC">
				<td colspan="3" class="section-header">Active Links</td>
			</tr>
			<?php
				foreach($active_links as $link) 
				{
					echo '<tr>';
						echo '<td>';
							echo '<a href="' . $link->url . '">' . str_replace('http://', '', $link->url) . '</a><br />';
							echo ($link->share_with ? 'Shared with: ' . $link->share_with : '');
						echo '</td>';
						echo '<td>' . $link->range . '<br />' . $link->expires . '</td>';
						echo '<td><input type="button" class="submit small stop-sharing" value="Stop Sharing" /><input type="hidden" class="token" value="' . $link->token . '" /></td>';
					echo '</tr>';					
				}
			?>
			<tr style="background-color: #CCC">
				<td colspan="3" class="section-header">Expired Links</td>
			</tr>
			<?php
				foreach($expired_links as $link) 
				{
					echo '<tr>';
						echo '<td>';
							echo '<a href="' . $link->url . '">' . str_replace('http://', '', $link->url) . '</a><br />';
							echo ($link->share_with ? 'Shared with: ' . $link->share_with : '');
						echo '</td>';
						echo '<td>' . $link->range . '<br />' . $link->expires . '</td>';
						echo '<td></td>';
					echo '</tr>';					
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