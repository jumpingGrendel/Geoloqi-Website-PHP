<?php 
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'settings-layers.js"></script>';
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<div class="settings-wrap"><div class="settings tabular">
<?php 
if(GEOLOQI_ENABLE_LAYERS)
{
?>
	<table id="settings-layers" style="border-spacing: 0px; margin: 0; width: 100%;">

		<?php /** Your Layers **/ ?>
		<tr style="text-align:left; background-color:#CCC;">
			<td class="section-header" colspan="2">Your Layers</th>
			<td width="90"></th>
		</tr>
		<? foreach ($this->data['user_layers'] as $layer): ?>
		<tr>
			<td width="90" style="text-align: right;">
				<img src="<?= $layer->icon ?>" width="57" height="57" style="border: 1px #bbb solid;" />
			</td>
			<td>
				<div class="label"><a href="/settings/layer/<?=$layer->layer_id?>"><?=$layer->name?></a></div>
				<div class="description">
					<?=$layer->description?>
				</div>
			</td>
			<td>
				<?= $layer->subscribed ? 'Active' : 'Inactive' ?>
			</td>
		</tr>
		<? endforeach; ?>


		<?php /** Featured Layers **/ ?>
		<tr style="text-align:left; background-color:#CCC;">
			<td colspan="2" class="section-header">Featured Layers</th>
			<td style=""></th>
		</tr>
		<? foreach ($this->data['featured_layers'] as $layer): ?>
		<tr>
			<td style="text-align: right;">
				<img src="<?= $layer->icon ?>" width="57" height="57" style="border: 1px #bbb solid;" />
			</td>
			<td>
				<div class="label"><a href="/settings/layer/<?=$layer->layer_id?>"><?=$layer->name?></a></div>
				<div class="description" style="">
					<?=$layer->description?>
				</div>
			</td>
			<td>
				<?= $layer->subscribed ? 'Active' : 'Inactive' ?>
			</td>
		</tr>
		<? endforeach; ?>
	</table>
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