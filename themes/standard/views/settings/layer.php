<?php 
include($this->theme_file('layouts/site_header.php'));
include($this->theme_file('settings/menu.php'));
?>

<div class="settings-wrap"><div class="settings settings-columns">
<?php 
if(GEOLOQI_ENABLE_LAYERS)
{
?>
	<div class="round" style="float:left;"><ul><li><a href="#">Remove</a></li></ul></div><div class="round" style="float:right"><ul><li><a href="#">Add a New Layer</a></li></ul></div>

<div style="clear:both;">

	<table style="border-spacing:0px; width:100%;">
		<tr style="text-align:left; background-color:#CCC;">
			<th style="width:40px; text-align:center"><input type="checkbox" name="" value="" /></th>
			<th style="width:50%">Your Layers</th>
			<th></th>
			<th style="width:100px;"></th>
		</tr>
		<? foreach ($this->data['user_layers'] as $layer): ?>
		<tr style="vertical-align:top;height:50px;">
			<?=($layer->type == 'geonote')?'<td>':'<td style="text-align:center;"><input type="checkbox" name="" value="" />'?></td>
			<td class="left">
				<div class="label"><a href="#"><?=$layer->name?></a></div>
				<div class="description" style="font-size:.8em;">
					<?=$layer->description?>
				</div>
			</td>
			<td class="right">
				<div class="description">
					<select name="user_layer[<?=$layer->id?>]" class="field">
						<option value="0" <?=($layer->public==0)?'selected="selected"':''?>>Private</option>
						<option value="1" <?=($layer->public==1)?'selected="selected"':''?>>Public (<?=($layer->user_id == $this->users->id)?'editable':'non editable' ?>)</option>
					</select>
					<input type="submit" value="Save" class="submit" id="btn_save" />
				</div>
			</td>
			<td>
				<a href="#">Activated</a>
			</td>
		</tr>
		<? endforeach; ?>
		<tr style="text-align:left; background-color:#CCC;">
			<th style="width:40px; text-align:center"><input type="checkbox" name="" value="" /></th>
			<th style="width:50%">Subscriptions</th>
			<th></th>
			<th style="width:100px;"></th>
		</tr>
		<tr style="vertical-align:top">
			<td style="text-align:center;"><input type="checkbox" name="" value="" /></td>
			<td class="left">
				<div class="label"><a href="#">History Layer</a></div>
				<div class="description" style="font-size:.8em;">
					Description goes here.
				</div>
			</td>
			<td class="right">
				<div class="description">
					<select><option>Private</option></select><input type="submit" value="Save" class="submit" id="btn_save" />
				</div>
			</td>
			<td>
				<a href="#">Activated</a>
			</td>
		</tr>
		<tr style="text-align:left; background-color:#CCC;">
			<th style="width:40px; text-align:center"><input type="checkbox" name="" value="" /></th>
			<th style="width:50%">New Layers Near You</th>
			<th></th>
			<th style="width:100px;"></th>
		</tr>
		<tr style="vertical-align:top;">
			<td></td>
			<td class="left">
				<div class="label"><a href="#">Geonotes</a></div>
				<div class="description" style="font-size:.8em;">
					Description goes here.
				</div>
			</td>
			<td class="right">
				<div class="description">
					<select><option>Private</option></select><input type="submit" value="Save" class="submit" id="btn_save" />
				</div>
			</td>
			<td>
				<a href="#">Activated</a>
			</td>
		</tr>	
		<tr style="text-align:left; background-color:#CCC;">
			<th style="width:40px; text-align:center"><input type="checkbox" name="" value="" /></th>
			<th style="width:50%">Featured Layers</th>
			<th></th>
			<th style="width:100px;"></th>
		</tr>
		<tr style="vertical-align:top">
			<td></td>
			<td class="left">
				<div class="label"><a href="#">Geonotes</a></div>
				<div class="description" style="font-size:.8em;">
					Description goes here.
				</div>
			</td>
			<td class="right">
				<div class="description">
					<select><option>Private</option></select><input type="submit" value="Save" class="submit" id="btn_save" />
				</div>
			</td>
			<td>
				<a href="#">Activated</a>
			</td>
		</tr>
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