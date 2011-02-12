<?php 
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'settings-sharing.js"></script>';
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<div class="settings-wrap"><div class="settings tabular">
		<table style="border-spacing: 0px; margin: 0; width: 100%">
			<thead>
				<tr>
					<th colspan="4">
						<div style="text-align: right">Create a new link from <a href="/me">your map</a>!</div>
						<!-- <input type="button" class="submit" value="New Link" style="float: right" /> -->
					</th>
				</tr>
			</thead>
			<tbody>
			<tr style="background-color: #CCC">
				<td colspan="3"><div class="section-header">Active Links</div></td>
			</tr>
			<?php
				foreach($active_links as $link) 
				{
					echo '<tr>';
						echo '<td>';
							echo ($link->description ? $link->description . '<br />' : '');
							echo '<a href="' . $link->url . '">' . str_replace('http://', '', ($link->short_url ? $link->short_url : $link->url)) . '</a><br />';
							echo ($link->share_with ? 'Shared with: ' . $link->share_with : '');
						echo '</td>';
						echo '<td>' . $link->range . '<br />' . $link->expires . '</td>';
						echo '<td><input type="button" class="submit small stop-sharing" value="Stop Sharing" /><input type="hidden" class="token" value="' . $link->token . '" /></td>';
					echo '</tr>';					
				}
				if(count($active_links) == 0)
				{
					echo '<tr>';
						echo '<td colspan="3">There are currently no active shared links</td>';
					echo '</tr>';
				}
			?>
			<tr style="background-color: #CCC">
				<td colspan="3" class="section-header">Inactive Links</td>
			</tr>
			<?php
				foreach($expired_links as $link) 
				{
					echo '<tr>';
						echo '<td>';
							echo ($link->description ? $link->description . '<br />' : '');
							echo str_replace('http://', '', ($link->short_url ? $link->short_url : $link->url)) . '<br />';
							echo ($link->share_with ? 'Shared with: ' . $link->share_with : '');
						echo '</td>';
						echo '<td>' . $link->range . '<br />' . $link->expires . '</td>';
						if(($link->date_to == '')
							|| (strtotime($link->date_from) < time() && strtotime($link->date_to) > time()))
							echo '<td><input type="button" class="submit small start-sharing" value="Activate" /><input type="hidden" class="token" value="' . $link->token . '" /></td>';
					echo '</tr>';					
				}
				if(count($expired_links) == 0)
				{
					echo '<tr>';
						echo '<td colspan="3">There are currently no expired shared links</td>';
					echo '</tr>';
				}
			?>
			</tbody>
		</table>
</div></div>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>