<?php 
$this->head[] = '<script type="text/javascript" src="' . $theme_root . 'settings-layers.js"></script>';
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<div class="settings-wrap"><div class="settings settings-columns">
<?php 
if(GEOLOQI_ENABLE_LAYERS)
{
?>
	<table id="settings-layers" style="border-spacing:0px; width:800px;">
		<tr>
			<td colspan="2"><input type="button" class="submit" value="Remove" /></td>
			<td colspan="2" style="text-align:right"><input type="button" class="submit" value="Add a New Layer" /></td>
		</tr>
		<tr style="text-align:left; background-color:#CCC;">
			<th style="width:40px; text-align:center"><input type="checkbox" name="" value="" oncheck="$('your_layers').checked" /></th>
			<th>Your Layers</th>
			<th></th>
			<th style="width:100px;"></th>
		</tr>
		<? foreach ($this->data['user_layers'] as $layer): ?>
		<tr style="vertical-align:top;height:50px;">
			<?=($layer->type == 'geonote')?'<td>':'<td style="text-align:center;"><input type="checkbox" name="your_layers[]" value="1" />'?></td>
			<td class="left">
				<div class="label"><a href="#"><?=$layer->name?></a></div>
				<div class="description" style="font-size:.8em;">
					<?=$layer->description?>
				</div>
			</td>
			<td class="right">
				<div class="description">
					<select name="user_layer[<?=$layer->id?>]" class="field" style="width:150px;">
						<option value="0" <?=($layer->public==0)?'selected="selected"':''?>>Private</option>
						<option value="1" <?=($layer->public==1)?'selected="selected"':''?>>Public (<?=($layer->user_id == $this->users->id)?'editable':'non editable' ?>)</option>
					</select>
					<input type="button" value="Save" class="submit save-privacy" />
				</div>
			</td>
			<td>
				<input type="button" class="submit" value="Activated" />
			</td>
		</tr>
		<? endforeach; ?>
		<tr style="text-align:left; background-color:#CCC;">
			<th style="width:40px; text-align:center"><input type="checkbox" name="" value="" /></th>
			<th style="width:50%">Subscriptions</th>
			<th></th>
			<th style="width:100px;"></th>
		</tr>
		<? foreach ($this->data['layer_subscriptions'] as $layer): ?>
		<tr style="vertical-align:top;height:50px;">
			<td style="text-align:center;"><input type="checkbox" name="" value="" /></td>
			<td class="left">
				<div class="label"><a href="#"><?=$layer->name?></a></div>
				<div class="description" style="font-size:.8em;">
					<?=$layer->description?>
				</div>
			</td>
			<td class="right">
				<div class="description">
					<select name="user_layer[<?=$layer->id?>]" class="field" style="width:150px">
						<option value="0" <?=($layer->public==0)?'selected="selected"':''?>>Private</option>
						<option value="1" <?=($layer->public==1)?'selected="selected"':''?>>Public (<?=($layer->user_id == $this->users->id)?'editable':'non editable' ?>)</option>
					</select>
					<input type="submit" value="Save" class="submit" id="btn_save" />
				</div>
			</td>
			<td>
				<input type="button" class="submit" value="Activated" />
			</td>
		</tr>
		<? endforeach; ?>
		<tr style="text-align:left; background-color:#CCC;">
			<th style="width:40px; text-align:center"><input type="checkbox" name="" value="" /></th>
			<th style="width:50%">New Layers Near You</th>
			<th></th>
			<th style="width:100px;"></th>
		</tr>
		<? foreach ($this->data['layers_near_you'] as $layer): ?>
		<tr style="vertical-align:top;height:50px;">
			<td style="text-align:center;"><input type="checkbox" name="" value="" /></td>
			<td class="left">
				<div class="label"><a href="#"><?=$layer->name?></a></div>
				<div class="description" style="font-size:.8em;">
					<?=$layer->description?>
				</div>
			</td>
			<td class="right">
				<div class="description">
					<select name="user_layer[<?=$layer->id?>]" class="field" style="width:150px">
						<option value="0" <?=($layer->public==0)?'selected="selected"':''?>>Private</option>
						<option value="1" <?=($layer->public==1)?'selected="selected"':''?>>Public (<?=($layer->user_id == $this->users->id)?'editable':'non editable' ?>)</option>
					</select>
					<input type="submit" value="Save" class="submit" id="btn_save" />
				</div>
			</td>
			<td>
				<input type="button" class="submit" value="Activated" />
			</td>
		</tr>
		<? endforeach; ?>
		<tr style="text-align:left; background-color:#CCC;">
			<th style="width:40px; text-align:center"><input type="checkbox" name="" value="" /></th>
			<th style="width:50%">Featured Layers</th>
			<th></th>
			<th style="width:100px;"></th>
		</tr>
		<? foreach ($this->data['featured_layers'] as $layer): ?>
		<tr style="vertical-align:top;height:50px;">
			<td style="text-align:center;"><input type="checkbox" name="" value="" /></td>
			<td class="left">
				<div class="label"><a href="#"><?=$layer->name?></a></div>
				<div class="description" style="font-size:.8em;">
					<?=$layer->description?>
				</div>
			</td>
			<td class="right">
				<div class="description">
					<select name="user_layer[<?=$layer->id?>]" class="field" style="width:150px">
						<option value="0" <?=($layer->public==0)?'selected="selected"':''?>>Private</option>
						<option value="1" <?=($layer->public==1)?'selected="selected"':''?>>Public (<?=($layer->user_id == $this->users->id)?'editable':'non editable' ?>)</option>
					</select>
					<input type="submit" value="Save" class="submit" id="btn_save" />
				</div>
			</td>
			<td>
				<input type="button" class="submit" value="Activated" />
			</td>
		</tr>
		<? endforeach; ?>
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