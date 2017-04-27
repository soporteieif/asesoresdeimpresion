<div id="ajax-message-ok" class="conf ajax-message alert alert-success" style="display: none">
	<span class="message"></span>
</div>
<div id="ajax-message-ko" class="error ajax-message alert alert-danger" style="display: none">
	<span class="message"></span>
</div>
<div class="panel">
	<h3><i class="icon-cogs"></i> {l s='Configuration' mod='sthoverimage'}</h3>
    {if ($show_error)}
    <div id="progress-warning" class="error alert alert-danger">
		{l s='This module was not installed properly. Make sure the override folder and override sub-folders have write permissions (also known as CHMOD 755 or CHMOD 777), and then try reinstalling.' mod='sthoverimage'}
    </div>
    {/if}
	<div id="progress-warning" class="alert alert-warning" style="display: none">
		{l s='In progress. Please do not leave this page' mod='sthoverimage'}
	</div>
	<div class="row">
		<p>
			<a id="ajax_auto_set_hover_image" class="btn btn-default" href="{$ps_base_uri}">{l s='Build hover image' mod='sthoverimage'}</a>
		</p>
	</div>
	<div class="row">
		<div class="alert alert-info">
            {l s='Set a image(generally the 2nd one) as the default hover image, if hover image already exists, the product will be skipped.' mod='sthoverimage'}
		</div>
	</div>
    <div class="row">
		<p>
            <a id="ajax_clean_hover_image" class="btn btn-default" href="{$ps_base_uri}">{l s='Remove hover image' mod='sthoverimage'}</a>
		</p>
	</div>
</div>

<script type="text/javascript">
	var translations = new Array();
	translations['in_progress']  = '{l s='(in progress)' js=1 mod='sthoverimage'}';
	translations['in_progress_count'] = '{l s='(in progress, %s products have been built)' js=1 mod='sthoverimage'}';
	translations['hover_image_finished']  = '{l s='Finished' js=1 mod='sthoverimage'}';
	translations['hover_image_failed'] = '{l s='Failed' js=1 mod='sthoverimage'}';
</script>