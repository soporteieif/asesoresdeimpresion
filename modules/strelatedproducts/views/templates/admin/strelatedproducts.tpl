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
<div id="st_related_products" class="panel product-tab">
<input type="hidden" name="submitted_tabs[]" value="ModuleStrelatedproducts" />
<h3>{l s='Related products' mod='strelatedproducts'}</h3>
{if $nbr_by_tags}
<div class="alert alert-info" style="display:block;min-height:0;">
    {if $nbr_by_tags==1}
	{l s='%d product is connected via tags.' sprintf=$nbr_by_tags  mod='strelatedproducts'}
    {else}
	{l s='%d products are connected via tags.' sprintf=$nbr_by_tags  mod='strelatedproducts'}
    {/if}
</div>
{/if}
    <div class="form-group">
        <label class="control-label col-lg-3">
            <span>{l s='Related product:' mod='strelatedproducts'}</span>
        </label>
        <div class="col-lg-8">
            <input type="hidden" name="inputRelatedProducts" id="inputRelatedProducts" value="{foreach from=$st_related_products item=relatedProduct}{$relatedProduct.id_product}-{/foreach}" />
			<input type="hidden" name="nameRelatedProducts" id="nameRelatedProducts" value="{foreach from=$st_related_products item=relatedProduct}{$relatedProduct.name|escape:'htmlall':'UTF-8'}¤{/foreach}" />
            
			<div id="ajax_choose_product">
				<p class="help-block">
					<input type="text" value="" id="st_related_product_autocomplete_input" />
					{l s='Begin typing the first letters of the product name, then select the product from the drop-down list.' mod='strelatedproducts'}
				</p>
				<p class="help-block">{l s='(Do not forget to save the product afterward)' mod='strelatedproducts'}</p>
			</div>
			<div id="divRelatedProducts">
				{* @todo : donot use 3 foreach, but assign var *}
                {if isset($st_related_products) && count($st_related_products)}
                {foreach from=$st_related_products item=relatedProduct}
					{$relatedProduct.name|escape:'htmlall':'UTF-8'}{if !empty($relatedProduct.reference)}{$relatedProduct.reference}{/if}
					<span class="delRelatedProduct" name="{$relatedProduct.id_product}" style="cursor: pointer;">
						<img src="../img/admin/delete.gif" class="middle" alt="" />
					</span><br />
				{/foreach}
                {/if}
			</div>
        </div>
    </div>
    <div class="panel-footer">
		<a href="{$link->getAdminLink('AdminProducts')}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel'}</a>
		<button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save'}</button>
		<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay'}</button>
	</div>
</div>
<script type="text/javascript">
{literal}
var product_tabs_ModuleStrelatedproducts = new function(){
	var self = this;

	this.initRelatedProductsAutocomplete = function (){
		$('#st_related_product_autocomplete_input')
			.autocomplete('ajax_products_list.php', {
				minChars: 1,
				autoFill: true,
				max:20,
				matchContains: true,
				mustMatch:true,
				scroll:false,
				cacheLength:0,
				formatItem: function(item) {
					return item[1]+' - '+item[0];
				}
			}).result(self.addRelatedProduct);
		$('#st_related_product_autocomplete_input').setOptions({
			extraParams: {
				excludeIds : self.getRelatedProductsIds()
			}
		});
	};

	this.getRelatedProductsIds = function()
	{
		if (!$('#inputRelatedProducts').val())
			return '-1';
		var ids = id_product + ',';
		ids += $('#inputRelatedProducts').val().replace(/\-/g,',').replace(/\,$/,'');
		ids = ids.replace(/\,$/,'');

		return ids;
	};

	this.addRelatedProduct = function(event, data, formatted)
	{
		if (data == null)
			return false;
		var productId = data[1];
		var productName = data[0];

		var $divRelatedProducts = $('#divRelatedProducts');
		var $inputRelatedProducts = $('#inputRelatedProducts');
		var $nameRelatedProducts = $('#nameRelatedProducts');

		/* delete product from select + add product line to the div, input_name, input_ids elements */
		$divRelatedProducts.html($divRelatedProducts.html() + productName + ' <span class="delRelatedProduct" name="' + productId + '" style="cursor: pointer;"><img src="../img/admin/delete.gif" /></span><br />');
		$nameRelatedProducts.val($nameRelatedProducts.val() + productName + '¤');
		$inputRelatedProducts.val($inputRelatedProducts.val() + productId + '-');
		$('#st_related_product_autocomplete_input').val('');
		$('#st_related_product_autocomplete_input').setOptions({
			extraParams: {excludeIds : self.getRelatedProductsIds()}
		});
	};

	this.delRelatedProduct = function(id)
	{
		var div = getE('divRelatedProducts');
		var input = getE('inputRelatedProducts');
		var name = getE('nameRelatedProducts');

		// Cut hidden fields in array
		var inputCut = input.value.split('-');
		var nameCut = name.value.split('¤');

		if (inputCut.length != nameCut.length)
			return jAlert('Bad size');

		// Reset all hidden fields
		input.value = '';
		name.value = '';
		div.innerHTML = '';
		for (i in inputCut)
		{
			// If empty, error, next
			if (!inputCut[i] || !nameCut[i])
				continue ;

			// Add to hidden fields no selected products OR add to select field selected product
			if (inputCut[i] != id)
			{
				input.value += inputCut[i] + '-';
				name.value += nameCut[i] + '¤';
				div.innerHTML += nameCut[i] + ' <span class="delRelatedProduct" name="' + inputCut[i] + '" style="cursor: pointer;"><img src="../img/admin/delete.gif" /></span><br />';
			}
            /*
			else
				$('#selectRelatedProducts').append('<option selected="selected" value="' + inputCut[i] + '-' + nameCut[i] + '">' + inputCut[i] + ' - ' + nameCut[i] + '</option>');
            */
		}

		$('#st_related_product_autocomplete_input').setOptions({
			extraParams: {excludeIds : self.getRelatedProductsIds()}
		});
	};
	this.onReady = function(){
		self.initRelatedProductsAutocomplete();
		$('#divRelatedProducts').delegate('.delRelatedProduct', 'click', function(){
			self.delRelatedProduct($(this).attr('name'));
		});
	};
}
jQuery(document).ready(function($){
    product_tabs_ModuleStrelatedproducts.onReady();
});
{/literal}
</script>
