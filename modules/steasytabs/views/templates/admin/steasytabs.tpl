{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div id="st_easytabs" class="panel product-tab">
<input type="hidden" name="submitted_tabs[]" value="ModuleSteasytabs" />
<h3>{l s='Product Extra Tabs' mod='steasytabs'}</h3>
{include file="controllers/products/multishop/check_fields.tpl" product_tab="ModuleSteasytabs"}
    <div class="form-group">
		<label class="control-label col-lg-3">
            {include file="controllers/products/multishop/checkbox.tpl" field="easytabs_title" type="default" multilang="true"}
			<span class="label-tooltip" data-toggle="tooltip"
				title="{l s='Invalid characters: <>;=#{}'}">
				{l s='Title:' mod='steasytabs'}
			</span>
        </label>
        <div class="col-lg-8">
			{include file="controllers/products/input_text_lang.tpl"
				languages=$languages
				input_name='easytabs_title'
				input_value=$steasytabs->title
				maxchar=70
			}
		</div>
    </div>
    <div class="form-group">        
		<label class="control-label col-lg-3">
			{include file="controllers/products/multishop/checkbox.tpl" field="easytabs_content" type="tinymce" multilang="true"}
            <span class="label-tooltip" data-toggle="tooltip"
				title="{l s='Invalid characters: <>;=#{}'}">
			{l s='Content:' mod='steasytabs'}
            </span>
		</label>
        <div class="col-lg-9">
			{include
				file="controllers/products/textarea_lang.tpl"
				languages=$languages
				input_name='easytabs_content'
				class="easytabs_autoload_rte"
				input_value=$steasytabs->content}
		</div>
    </div>
   	<div class="form-group">
		<label class="control-label col-lg-3">
            {include file="controllers/products/multishop/checkbox.tpl" field="easytabs_active" type="radio" onclick=""}
		{l s='Active:' mod='steasytabs'}
        </label>
		<div class="col-lg-9 ">
    		<span class="switch prestashop-switch fixed-width-lg">
    		<input type="radio" {if $steasytabs->active}checked="checked"{/if} value="1" id="easytabs_active_on" name="easytabs_active">
    		<label for="easytabs_active_on">{l s='Yes' mod='steasytabs'}</label>
    		<input type="radio" {if !$steasytabs->active}checked="checked"{/if} value="0" id="easytabs_active_off" name="easytabs_active">
    		<label for="easytabs_active_off">{l s='No' mod='steasytabs'}</label>
    		<a class="slide-button btn"></a>
    		</span>												
		</div>
	</div>
    <div class="panel-footer">
		<a href="{$link->getAdminLink('AdminProducts')}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel'}</a>
		<button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save'}</button>
		<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay'}</button>
	</div>
</div>
<script type="text/javascript">
    if(!display_multishop_checkboxes)
    {
        ad = '{$ad}';
        iso = '{$iso_tiny_mce}';
    }
    hideOtherLanguage({$default_form_language});
</script>
{block name="autoload_tinyMCE"}
<script type="text/javascript">
    {literal}
     tabs_manager.onLoad("ModuleSteasytabs", function(){
        tinySetup({
			editor_selector :"easytabs_autoload_rte"
		});
    });    
    {/literal}
</script>
{/block}
<script type="text/javascript">
{literal}
$(document).ready(function($){ 

    ProductMultishop.checkAllModuleSteasytabs = function()
    {
         $.each(languages, function(k, v)
        	{
        		ProductMultishop.checkField($('input[name=\'multishop_check[easytabs_title]['+v.id_lang+']\']').prop('checked'), 'easytabs_title_'+v.id_lang);
        		ProductMultishop.checkField($('input[name=\'multishop_check[easytabs_content]['+v.id_lang+']\']').prop('checked'), 'easytabs_content_'+v.id_lang, 'tinymce');
        	});
        ProductMultishop.checkField($('input[name=\'multishop_check[easytabs_active]\']').prop('checked'), 'easytabs_active');   
    }

	if(display_multishop_checkboxes)
    {
        ProductMultishop.checkAllModuleSteasytabs();
    }
});
{/literal}
</script>
