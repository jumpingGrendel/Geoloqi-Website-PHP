<div id="settings-menu">
	<a href="/settings/profile"<?=$this->method == 'profile' ? ' class="selected"' : ''?>>Profile</a>
	<a href="/settings/privacy"<?=$this->method == 'privacy' ? ' class="selected"' : ''?>>Privacy</a>
	<a href="/settings/connections"<?=$this->method == 'connections' ? ' class="selected"' : ''?>>Connections</a>
	<a href="/settings/share"<?=$this->method == 'share' ? ' class="selected"' : ''?>>Shared Links</a>
<?php if(GEOLOQI_ENABLE_LAYERS){ ?>
	<a href="/settings/layer"<?=$this->method == 'layer' ? ' class="selected"' : ''?>>Layers</a>
<?php } ?>
	<a href="/settings/app_tour"<?=$this->method == 'app_tour' ? ' class="selected"' : ''?>>App Tour</a>
	<div style="clear:both;"></div>
</div>