<?php 
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'settings-layers.js"></script>';
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<?php 
if(GEOLOQI_ENABLE_LAYERS)
{
?>
<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
<td valign="top">

	<div class="settings-wrap"><div class="settings settings-columns">
		<div style="width: 57px; height: 57px; float: left; border: 1px #bbb solid; margin-left: 18px; margin-right: 10px;"><img src="<?=$layer->icon?>" width="57" height="57" /></div>
		<div style="font-size: 19pt; font-weight: bold; padding: 8px 0 18px 0;"><?=$layer->name?></div>
		<table style="border-spacing: 0px; width: 470px;">
		<?php
		if($layer->type != 'geonotes')
		{
			?>
			<tr>
				<td class="label">Status</td>
				<td class="description" style="text-align: right;">
					<a href="javascript:void(0);" id="subscribe_switch" class="<?=k($layer->subscription, 'subscribed') ? 'on' : 'off'?>">
						<img src="<?=$theme_root?>images/switch_<?=k($layer->subscription, 'subscribed') ? 'on' : 'off'?>.png" />
					</a>
					<input style="display: none;" class="layer_id" value="<?=$layer->layer_id?>" />
				</td>
			</tr>
			<?php
		}
		?>
			<tr>
				<td class="label" width="150">Description</td>
				<td class="description">
					<?=$layer->description?>
				</td>
			</tr>
			<?php
			if(k($layer, 'num_places'))
			{
			?>
			<tr>
				<td class="label">Places</td>
				<td style="vertical-align: bottom;" class="description">There <?=($layer->num_places==1?'is':'are')?> currently <?=$layer->num_places?> active place<?=($layer->num_places==1?'':'s')?> on this layer.</td>
			</tr>
			<?php
			}
			?>
		</table>
	</div></div>

</td>
<td width="340" id="right_panel" valign="top">
	<div style="padding: 10px;">
		<img src="<?=$theme_root?>images/no-layer-preview.png" width="320" height="240" />
	</div>
</td>

</tr></table>

<?php 
} 
else 
{
?>
	<div style="padding: 20px;">Coming soon...</div>
<?php 
}
?>

<?php 
include($this->theme_file('layouts/site_footer.php'));
?>