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
{include file="$tpl_dir./errors.tpl"}
{if $errors|@count == 0}
	{if !isset($priceDisplayPrecision)}
		{assign var='priceDisplayPrecision' value=2}
	{/if}
	{if !$priceDisplay || $priceDisplay == 2}
		{assign var='productPrice' value=$product->getPrice(true, $smarty.const.NULL, 6)}
		{assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
	{elseif $priceDisplay == 1}
		{assign var='productPrice' value=$product->getPrice(false, $smarty.const.NULL, 6)}
		{assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(true, $smarty.const.NULL)}
	{/if}

	{assign var='zoom_type' value=Configuration::get('STSN_ZOOM_TYPE')}
	{assign var='product_tabs' value=Configuration::get('STSN_PRODUCT_TABS')}
	{assign var='discount_percentage' value=Configuration::get('STSN_DISCOUNT_PERCENTAGE')}
	{assign var='enable_google_rich_snippets' value=(!isset($sttheme.google_rich_snippets) || (isset($sttheme.google_rich_snippets) && $sttheme.google_rich_snippets))}

	{assign var='new_sticker' value=Configuration::get('STSN_NEW_STYLE')}
	{assign var='sale_sticker' value=Configuration::get('STSN_SALE_STYLE')}

	{assign var='display_pro_condition' value=Configuration::get('STSN_DISPLAY_PRO_CONDITION')}
	{assign var='display_pro_reference' value=Configuration::get('STSN_DISPLAY_PRO_REFERENCE')}

	{assign var='pro_thumbnails' value=Configuration::get('STSN_PRO_THUMBNAILS')}
	
	{capture name="big_default_width"}{getWidthSize type='big_default'}{/capture}
	{capture name="big_default_height"}{getHeightSize type='big_default'}{/capture}

    {assign var='pro_show_print_btn' value=Configuration::get('STSN_PRO_SHOW_PRINT_BTN')}
	<div{if $enable_google_rich_snippets} itemscope itemtype="https://schema.org/Product"{/if}>
	{if $enable_google_rich_snippets}<meta itemprop="url" content="{$link->getProductLink($product)}">{/if}
	<div class="primary_block row">
		{if isset($adminActionDisplay) && $adminActionDisplay}
			<div id="admin-action" class="container">
				<p class="alert alert-info">{l s='This product is not visible to your customers.'}
					<input type="hidden" id="admin-action-product-id" value="{$product->id}" />
					<a id="publish_button" class="btn btn-default button button-small" href="#">
						<span>{l s='Publish'}</span>
					</a>
					<a id="lnk_view" class="btn btn-default button button-small" href="#">
						<span>{l s='Back'}</span>
					</a>
				</p>
				<p id="admin-action-result"></p>
			</div>
		{/if}
		{if isset($confirmation) && $confirmation}
			<p class="confirmation">
				{$confirmation}
			</p>
		{/if}
		<!-- left infos-->
		<div class="pb-left-column col-xs-12 {if isset($sttheme.product_big_image) && $sttheme.product_big_image} col-sm-6 col-md-6 {else} col-sm-4 col-md-4 {/if}">
			<!-- product img-->
			<div id="image-block" class="clearfix">
				{capture name="sale_reduction"}
		            {if $new_sticker!=2 && $product->new}<span class="new"><i>{l s='New'}</i></span>{/if}
		            {if $sale_sticker!=2 && $product->show_price AND !$PS_CATALOG_MODE AND $product->on_sale}<span class="on_sale"><i>{l s='Sale'}</i></span>{/if}
		            {if $product->show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE && isset($discount_percentage) && $discount_percentage>1}
						<div id="reduction_percent" {if $productPriceWithoutReduction <= 0 || !$product->specificPrice || $product->specificPrice.reduction_type != 'percentage'} style="display:none;"{/if}>
							<span class="sale_percentage_sticker img-circle" id="reduction_percent_display">
								{if $product->specificPrice && $product->specificPrice.reduction_type == 'percentage'}{$product->specificPrice.reduction*100}%{if $discount_percentage==2}<br/>{else} {/if}{l s='Off'}{/if}
							</span>
						</div>
						<div id="reduction_amount" {if $productPriceWithoutReduction <= 0 || !$product->specificPrice || $product->specificPrice.reduction_type != 'amount' || $product->specificPrice.reduction|floatval ==0} style="display:none"{/if}>
							<span class="sale_percentage_sticker img-circle" id="reduction_amount_display" >
							{if $product->specificPrice && $product->specificPrice.reduction_type == 'amount' && $product->specificPrice.reduction|floatval !=0}
								{l s='Save'}{if $discount_percentage==2}<br/>{else} {/if}{convertPrice price=$productPriceWithoutReduction-$productPrice|floatval}
							{/if}
							</span>
						</div>
					{/if}
		        {/capture}
				{if $have_image}
					<span id="view_full_size">
						{if $zoom_type<2 && $have_image && !$content_only}
							<a class="jqzoom" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" rel="gal1" href="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'thickbox_default')|escape:'html':'UTF-8'}" class="replace-2x">
								<img id="jqzoom_bigpic" {if $enable_google_rich_snippets}itemprop="image"{/if} src="{if isset($sttheme.product_big_image) && $sttheme.product_big_image}{$link->getImageLink($product->link_rewrite, $cover.id_image, 'big_default')|escape:'html':'UTF-8'}{else}{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_default')|escape:'html':'UTF-8'}{/if}" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}"{if isset($sttheme.product_big_image) && $sttheme.product_big_image} width="{$smarty.capture.big_default_width}" height="{$smarty.capture.big_default_height}"{else} width="{$largeSize.width}" height="{$largeSize.height}"{/if} class="replace-2x" />
							</a>
							{$smarty.capture.sale_reduction}
						{else}
							<img id="bigpic" {if $enable_google_rich_snippets}itemprop="image"{/if} src="{if isset($sttheme.product_big_image) && $sttheme.product_big_image}{$link->getImageLink($product->link_rewrite, $cover.id_image, 'big_default')|escape:'html':'UTF-8'}{else}{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_default')|escape:'html':'UTF-8'}{/if}" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}"{if isset($sttheme.product_big_image) && $sttheme.product_big_image} width="{$smarty.capture.big_default_width}" height="{$smarty.capture.big_default_height}"{else} width="{$largeSize.width}" height="{$largeSize.height}"{/if} class="replace-2x"/>
							{if !$content_only && $zoom_type==2}
								<a href="javascript:;" class="span_link no-print icon_wrap" title="{l s='View larger'}"><i class="icon-search-1 icon-large"></i></a>
							{/if}
							{$smarty.capture.sale_reduction}
						{/if}
					</span>
				{else}
					<span id="view_full_size">
						<img {if $enable_google_rich_snippets}itemprop="image"{/if} src="{if isset($sttheme.product_big_image) && $sttheme.product_big_image}{$img_prod_dir}{$lang_iso}-default-big_default.jpg{else}{$img_prod_dir}{$lang_iso}-default-large_default.jpg{/if}" id="bigpic" alt="" title="{$product->name|escape:'html':'UTF-8'}"{if isset($sttheme.product_big_image) && $sttheme.product_big_image} width="{$smarty.capture.big_default_width}" height="{$smarty.capture.big_default_height}"{else} width="{$largeSize.width}" height="{$largeSize.height}"{/if}/>
						{if !$content_only}
							<a href="javascript:;" class="span_link no-print icon_wrap visible-md visible-lg" title="{l s='View larger'}"><i class="icon-search-1 icon-large"></i></a>
						{/if}
						{$smarty.capture.sale_reduction}
					</span>
				{/if}
			</div> <!-- end image-block -->
			{if isset($images) && count($images) > 0}
				<!-- thumbnails -->
				<div id="views_block" class="clearfix {if isset($images) && count($images) < 2}hidden{/if} {if $pro_thumbnails} pro_thumbnails{/if}">
					{if isset($images) && count($images)}<span class="view_scroll_spacer"><a id="view_scroll_left" class="" title="{l s='Other views'} {l s='Previous'}" href="javascript:;"><i class="icon-left-open-1"></i></a></span>{/if}
					<div id="thumbs_list">
<ul id="thumbs_list_frame">
{if isset($images)}
{foreach from=$images item=image name=thumbnails}
{assign var=imageIds value="`$product->id`-`$image.id_image`"}
{if !empty($image.legend)}
{assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
{else}
{assign var=imageTitle value=$product->name|escape:'html':'UTF-8'}
{/if}<li id="thumbnail_{$image.id_image}"{if $smarty.foreach.thumbnails.last} class="last"{/if}>
	<a{if $zoom_type<2 && $have_image && !$content_only} href="javascript:void(0);" rel="{literal}{{/literal}gallery: 'gal1', smallimage: '{if isset($sttheme.product_big_image) && $sttheme.product_big_image}{$link->getImageLink($product->link_rewrite, $imageIds, 'big_default')|escape:'html':'UTF-8'}{else}{$link->getImageLink($product->link_rewrite, $imageIds, 'large_default')|escape:'html':'UTF-8'}{/if}',largeimage: '{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html':'UTF-8'}'{literal}}{/literal}" class="{if $image.id_image == $cover.id_image} zoomThumbActive{/if} replace-2x" {elseif $zoom_type==2 && !$content_only} href="{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html':'UTF-8'}"	data-fancybox-group="other-views" class="fancybox{if $image.id_image == $cover.id_image} shown{/if} replace-2x"{else} href="javascript:void(0);" data-bigpic="{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html':'UTF-8'}" rel="nofollow" {/if} title="{$imageTitle}">

	<img class="replace-2x img-responsive" id="thumb_{$image.id_image}" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'medium_default')|escape:'html':'UTF-8'}" alt="{$imageTitle}" title="{$imageTitle}" height="{$mediumSize.height}" width="{$mediumSize.width}" {if $enable_google_rich_snippets}itemprop="image"{/if} />
</a>
</li>{/foreach}
{/if}
</ul>
					</div> <!-- end thumbs_list -->
					{if isset($images) && count($images)}<a id="view_scroll_right" title="{l s='Other views'} {l s='Next'}" href="javascript:;"><i class="icon-right-open-1"></i></a>{/if}
				</div> <!-- end views-block -->
				<!-- end thumbnails -->
			{/if}
			{if isset($images) && count($images) > 1}
				<p class="resetimg clear no-print">
					<span id="wrapResetImages" style="display: none;">
						<a href="{$link->getProductLink($product)|escape:'html':'UTF-8'}" data-id="resetImages">
							<i class="icon-picture-2"></i>
							{l s='Display all pictures'}
						</a>
					</span>
				</p>
			{/if}

			{if !$content_only}
				<!-- usefull links-->
				<ul id="usefull_link_block" class="clearfix no-print">
					{if $HOOK_EXTRA_LEFT}{$HOOK_EXTRA_LEFT}{/if}
					{if $pro_show_print_btn}
					<li class="print">
						<a href="javascript:print();">
							{l s='Print'}
						</a>
					</li>
					{/if}
				</ul>
			{/if}

		</div> <!-- end pb-left-column -->
		<!-- end left infos-->

		{capture name="product_tabs"}
		{if (isset($product) && $product->description) || (isset($features) && $features) || (isset($HOOK_PRODUCT_TAB) && $HOOK_PRODUCT_TAB) || (isset($attachments) && $attachments) || isset($product) && $product->customizable}
		<div id="more_info_block" class="mar_b2">
			<ul id="more_info_tabs" class="idTabs common_tabs li_fl clearfix">
				{if isset($product) && $product->description}<li><a id="more_info_tab_more_info" href="#idTab1">{l s='More info'}</a></li>{/if}
			    {if isset($sttheme.display_pro_tags) && $sttheme.display_pro_tags==1 && isset($product->tags[$cookie->id_lang]) && count($product->tags[$cookie->id_lang])}<li><a id="more_info_tab_tags" href="#idTab211">{l s='Tags'}</a></li>{/if}
				{if isset($features) && $features}<li><a id="more_info_tab_data_sheet" href="#idTab2">{l s='Data sheet'}</a></li>{/if}
				{if isset($attachments) && $attachments}<li><a id="more_info_tab_attachments" href="#idTab9">{l s='Download'}</a></li>{/if}
				{if isset($product) && $product->customizable}<li><a href="#idTab10">{l s='Product customization'}</a></li>{/if}
				{$HOOK_PRODUCT_TAB}
			</ul>

			<div id="more_info_sheets" class="sheets align_justify">
				{if isset($product) && $product->description}
				<div id="idTab1" class="rte product_accordion open">
					<!-- full description -->
			        <div class="product_accordion_title">
			        	<a href="javascript:;" class="opener dlm">&nbsp;</a>
			            <div class="product_accordion_name">{l s='More info'}</div>
			        </div>
			        <div class="pa_content">
			            <div class="rte">{$product->description}</div>
			            {if isset($sttheme.display_pro_tags) && $sttheme.display_pro_tags==2 && isset($product->tags[$cookie->id_lang]) && count($product->tags[$cookie->id_lang])}
			            <div id="tag_box_bottom_of_desc" class="clearfix">
			                <span>{l s='Tags:'}</span>
			                {foreach $product->tags[$cookie->id_lang] as $tag}
			                    <a href="{$link->getPageLink('search', true, NULL, "tag={$tag|urlencode}")|escape:'html'}" title="{l s='More about'} {$tag|escape:html:'UTF-8'}" target="_top">{$tag|escape:html:'UTF-8'}</a>{if !$tag@last}{/if}
			                {/foreach}
			            </div>
			            {/if}
			        </div>
			    </div>
				{/if}

				{if isset($sttheme.display_pro_tags) && $sttheme.display_pro_tags==1 && isset($product->tags[$cookie->id_lang]) && count($product->tags[$cookie->id_lang])}
				    <div id="idTab211" class="product_accordion block_hidden_only_for_screen">
						<!-- product's features -->
				        <div class="product_accordion_title">
				        	<a href="javascript:;" class="opener dlm">&nbsp;</a>
				            <div class="product_accordion_name">{l s='Tags'}</div>
				        </div>
				        <div class="pa_content">
				        {foreach $product->tags[$cookie->id_lang] as $tag}
				            <a href="{$link->getPageLink('search', true, NULL, "tag={$tag|urlencode}")|escape:'html'}" title="{l s='More about'} {$tag|escape:html:'UTF-8'}" target="_top">{$tag|escape:html:'UTF-8'}</a>{if !$tag@last}, {/if}
				        {/foreach}
				        </div>
				    </div>
				{/if}

				{if isset($features) && $features}
				    <div id="idTab2" class="product_accordion block_hidden_only_for_screen">
						<!-- product's features -->
				        <div class="product_accordion_title">
				        	<a href="javascript:;" class="opener dlm">&nbsp;</a>
				            <div class="product_accordion_name">{l s='Data sheet'}</div>
				        </div>
						<div class="pa_content">
							<table class="table-data-sheet table-bordered">
								{foreach from=$features item=feature}
								<tr class="{cycle values="odd,even"}">
									{if isset($feature.value)}
									<td>{$feature.name|escape:'html':'UTF-8'}</td>
									<td>{$feature.value|escape:'html':'UTF-8'}</td>
									{/if}
								</tr>
								{/foreach}
							</table>
						</div>
				    </div>
				{/if}

				{if isset($attachments) && $attachments}
				    <div id="idTab9" class="product_accordion block_hidden_only_for_screen">
				        <div class="product_accordion_title">
				        	<a href="javascript:;" class="opener dlm">&nbsp;</a>
				            <div class="product_accordion_name">{l s='Download'}</div>
				        </div>
						<div class="pa_content">
							{foreach from=$attachments item=attachment name=attachements}
								{if $smarty.foreach.attachements.iteration %3 == 1}<div class="row">{/if}
									<div class="{if $product_tabs}col-lg-12{else}col-lg-4{/if}">
										<h4><a href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">{$attachment.name|escape:'html':'UTF-8'}</a></h4>
										<p class="text-muted">{$attachment.description|escape:'html':'UTF-8'}</p>
										<a class="btn btn-default btn-block" href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">
											<i class="icon-download"></i>
											{l s="Download"} ({Tools::formatBytes($attachment.file_size, 2)})
										</a>
										<hr />
									</div>
								{if $smarty.foreach.attachements.iteration %3 == 0 || $smarty.foreach.attachements.last}</div>{/if}
							{/foreach}
						</div>
				    </div>
				{/if}

			{if isset($product) && $product->customizable}
			<!--Customization -->
			<div id="idTab10" class="customization_block product_accordion block_hidden_only_for_screen">
	            <div class="product_accordion_title">
	            	<a href="javascript:;" class="opener dlm">&nbsp;</a>
	                <div class="product_accordion_name">{l s='Product customization'}</div>
	            </div>
	            <div class="pa_content">
				<!-- Customizable products -->
				<form method="post" action="{$customizationFormTarget}" enctype="multipart/form-data" id="customizationForm" class="clearfix">
					<p class="infoCustomizable">
						{l s='After saving your customized product, remember to add it to your cart.'}
						{if $product->uploadable_files}
						<br />
						{l s='Allowed file formats are: GIF, JPG, PNG'}{/if}
					</p>
					{if $product->uploadable_files|intval}
						<div class="customizableProductsFile">
							<h5 class="product-heading-h5">{l s='Pictures'}</h5>
							<ul id="uploadable_files" class="clearfix">
								{counter start=0 assign='customizationField'}
								{foreach from=$customizationFields item='field' name='customizationFields'}
									{if $field.type == 0}
										<li class="customizationUploadLine{if $field.required} required{/if}">{assign var='key' value='pictures_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
											{if isset($pictures.$key)}
												<div class="customizationUploadBrowse">
													<img src="{$pic_dir}{$pictures.$key}_small" alt="" />
														<a href="{$link->getProductDeletePictureLink($product, $field.id_customization_field)|escape:'html':'UTF-8'}" title="{l s='Delete'}" >
															<img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" class="customization_delete_icon" width="11" height="13" />
														</a>
												</div>
											{/if}
											<div class="customizationUploadBrowse form-group">
												<label class="customizationUploadBrowseDescription">
													{if !empty($field.name)}
														{$field.name}
													{else}
														{l s='Please select an image file from your computer'}
													{/if}
													{if $field.required}<sup>*</sup>{/if}
												</label>
												<input type="file" name="file{$field.id_customization_field}" id="img{$customizationField}" class="form-control customization_block_input {if isset($pictures.$key)}filled{/if}" />
											</div>
										</li>
										{counter}
									{/if}
								{/foreach}
							</ul>
						</div>
					{/if}
					{if $product->text_fields|intval}
						<div class="customizableProductsText">
							<h5 class="product-heading-h5">{l s='Text'}</h5>
							<ul id="text_fields">
							{counter start=0 assign='customizationField'}
							{foreach from=$customizationFields item='field' name='customizationFields'}
								{if $field.type == 1}
									<li class="customizationUploadLine{if $field.required} required{/if}">
										<label for ="textField{$customizationField}">
											{assign var='key' value='textFields_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
											{if !empty($field.name)}
												{$field.name}
											{/if}
											{if $field.required}<sup>*</sup>{/if}
										</label>
										<textarea name="textField{$field.id_customization_field}" class="form-control customization_block_input" id="textField{$customizationField}" rows="3" cols="20">{strip}
											{if isset($textFields.$key)}
												{$textFields.$key|stripslashes}
											{/if}
										{/strip}</textarea>
									</li>
									{counter}
								{/if}
							{/foreach}
							</ul>
						</div>
					{/if}
					<p id="customizedDatas">
						<input type="hidden" name="quantityBackup" id="quantityBackup" value="" />
						<input type="hidden" name="submitCustomizedDatas" value="1" />
						<button class="btn btn-default" name="saveCustomization">
							<span>{l s='Save'}</span>
						</button>
						<span id="ajax-loader" class="unvisible">
							<img src="{$img_ps_dir}loader.gif" alt="loader" />
						</span>
					</p>
				</form>
				<p class="clear required"><sup>*</sup> {l s='required fields'}</p>
				</div>
			</div>
			<!--end Customization -->
			{/if}

			{if isset($HOOK_PRODUCT_TAB_CONTENT) && $HOOK_PRODUCT_TAB_CONTENT}{$HOOK_PRODUCT_TAB_CONTENT}{/if}
			</div>
		</div>
		{/if}
		{/capture}

		<!-- center infos -->
		<div class="pb-center-column col-xs-12 {if isset($sttheme.product_big_image) && $sttheme.product_big_image} col-sm-6 col-md-6 {else} {if isset($HOOK_PRODUCT_SECONDARY_COLUMN) && !$content_only} col-sm-5 col-md-5 {else} col-sm-8 col-md-8 {/if} {/if}">	
			<h1 {if $enable_google_rich_snippets}itemprop="name"{/if} class="product_main_name">{$product->name|escape:'html':'UTF-8'}</h1>
			{if ($show_brand_logo==2 || $show_brand_logo==3) && $product_manufacturer->id_manufacturer}
	            <a id="product_manufacturer_logo" {if $enable_google_rich_snippets} itemprop="brand" itemscope="" itemtype="https://schema.org/Organization" {/if} href="{$link->getmanufacturerLink($product_manufacturer->id_manufacturer, $product_manufacturer->link_rewrite)}" title="{l s='All products of this manufacturer'}" target="_top">
	                {if $enable_google_rich_snippets}<meta itemprop="name" content="{$product_manufacturer->name}" />{/if}
	                {if $show_brand_logo==2}<img {if $enable_google_rich_snippets} itemprop="image" {/if} alt="{$product_manufacturer->name|escape:'html':'UTF-8'}" class="replace-2x" src="{$img_manu_dir}{$product_manufacturer->id_manufacturer}-manufacturer_default.jpg" />{else}{$product_manufacturer->name|escape:'html':'UTF-8'}{/if}
	            </a>
	        {/if}
			{if $product->description_short || $packItems|@count > 0}
				<div id="short_description_block">
					{if $product->description_short}
						<div id="short_description_content" class="rte align_justify" {if $enable_google_rich_snippets}itemprop="description"{/if}>{$product->description_short}</div>
					{/if}

					{if $product->description}
						<p class="buttons_bottom_block">
							<a href="javascript:{ldelim}{rdelim}" class="button">
								{l s='More details'}
							</a>
						</p>
					{/if}
					<!--{if $packItems|@count > 0}
						<div class="short_description_pack">
						<h3>{l s='Pack content'}</h3>
							{foreach from=$packItems item=packItem}
							
							<div class="pack_content">
								{$packItem.pack_quantity} x <a href="{$link->getProductLink($packItem.id_product, $packItem.link_rewrite, $packItem.category)|escape:'html':'UTF-8'}">{$packItem.name|escape:'html':'UTF-8'}</a>
								<p>{$packItem.description_short}</p>
							</div>
							{/foreach}
						</div>
					{/if}-->
				</div> <!-- end short_description_block -->
			{/if}

			<div class="product_info_box">
				{if $product->online_only}
				<span class="online_only sm_lable">{l s='Online only'}</span>
				{/if}
				{if $product->specificPrice && $product->specificPrice.reduction && $productPriceWithoutReduction > $productPrice}
					<span class="discount sm_lable">{l s='Reduced price!'}</span>
				{/if}
				<div class="{if !$display_pro_reference} unvisible {/if} product_info_wrap" {if empty($product->reference) || !$product->reference} style="display: none;"{/if} id="product_reference">
					<span class="editable sm_lable" {if $enable_google_rich_snippets}itemprop="sku"{if !empty($product->reference) && $product->reference} content="{$product->reference}"{/if}{/if}>{if !isset($groups)}{$product->reference|escape:'html':'UTF-8'}{/if}</span>
				</div>
				{if !$product->is_virtual && $product->condition}
				<div class="{if !$display_pro_condition} unvisible {/if} product_info_wrap" id="product_condition">
					{if $product->condition == 'new'}
						<link itemprop="itemCondition" href="https://schema.org/NewCondition"/>
						<span class="editable sm_lable">{l s='New product'}</span>
					{elseif $product->condition == 'used'}
						<link itemprop="itemCondition" href="https://schema.org/UsedCondition"/>
						<span class="editable sm_lable">{l s='Used'}</span>
					{elseif $product->condition == 'refurbished'}
						<link itemprop="itemCondition" href="https://schema.org/RefurbishedCondition"/>
						<span class="editable sm_lable">{l s='Refurbished'}</span>
					{/if}
				</div>
				{/if}
			</div>

			{if ($product->show_price && !isset($restricted_country_mode)) || isset($groups) || $product->reference || (isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS)}
			<!-- add to cart form-->
			<form id="buy_block"{if $PS_CATALOG_MODE && !isset($groups) && $product->quantity > 0} class="hidden"{/if} action="{$link->getPageLink('cart')|escape:'html':'UTF-8'}" method="post">
				<!-- hidden datas -->
				<p class="hidden">
					<input type="hidden" name="token" value="{$static_token}" />
					<input type="hidden" name="id_product" value="{$product->id|intval}" id="product_page_product_id" />
					<input type="hidden" name="add" value="1" />
					<input type="hidden" name="id_product_attribute" id="idCombination" value="" />
				</p>
				<div class="box-info-product">
					{if Configuration::get('ST_COUNTDOWN_ACTIVE') && $product->show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
						{if $product->specificPrice && $product->specificPrice.reduction && $productPriceWithoutReduction > $productPrice}
		                    {if ($smarty.now|date_format:'%Y-%m-%d %H:%M:%S' >= $product->specificPrice.from && $smarty.now|date_format:'%Y-%m-%d %H:%M:%S' < $product->specificPrice.to)}
		                    <div class="countdown_outer_box">
			                    <div class="countdown_heading">{l s='Limited time offer:'}</div>
			                    <div class="countdown_box">
			                    	<i class="icon-clock"></i><span class="countdown_pro c_countdown_timer" data-countdown="{$product->specificPrice.to|date_format:'%Y/%m/%d %H:%M:%S'}" data-id-product="{$product->id|intval}"></span>
			                    </div>
		                    </div>
		                    {elseif ($product->specificPrice.to == '0000-00-00 00:00:00') && ($product->specificPrice.from == '0000-00-00 00:00:00') && Configuration::get('ST_COUNTDOWN_TITLE_AW_DISPLAY')}
		                    <div class="countdown_outer_box countdown_pro_perm" data-id-product="{$product->id|intval}">
		                    	<div class="countdown_box">
		                    		<i class="icon-clock"></i><span>{l s='Limited special offer'}</span>
		                    	</div>
	                    	</div>
		                    {/if}
						{/if}
					{/if}
					<div class="content_prices clearfix">
						{if $product->show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
							<!-- prices -->
							<div class="price_box clearfix">
								<p class="our_price_display fl" {if $enable_google_rich_snippets}itemprop="offers" itemscope itemtype="https://schema.org/Offer"{/if}>{strip}
									{if $enable_google_rich_snippets}{if $product->quantity > 0}<link itemprop="availability" href="https://schema.org/InStock"/>{/if}{/if}
									{if $priceDisplay >= 0 && $priceDisplay <= 2}
										<span id="our_price_display" {if $enable_google_rich_snippets}itemprop="price"{/if} content="{$productPrice}">{convertPrice price=$productPrice|floatval}</span>
				    					{if $tax_enabled  && ((isset($display_tax_label) && $display_tax_label == 1) OR !isset($display_tax_label)) && (isset($sttheme.display_tax_label) && $sttheme.display_tax_label)}
				    						<span class="product_tax_label">{if $priceDisplay == 1}{l s='tax excl.'}{else}{l s='tax incl.'}{/if}</span>
				    					{/if}
										{if $enable_google_rich_snippets}<meta itemprop="priceCurrency" content="{$currency->iso_code}" />{/if}
									{/if}
								{/strip}</p>
								{if $priceDisplay == 2}
									<span id="pretaxe_price" class="fl">{strip}
										<span id="pretaxe_price_display">{convertPrice price=$product->getPrice(false, $smarty.const.NULL)}</span>
										{l s='tax excl.'}
									{/strip}</span>
								{/if}
								<p id="old_price" class="{if (!$product->specificPrice || !$product->specificPrice.reduction)} hidden{/if} fl">{strip}
									{if $priceDisplay >= 0 && $priceDisplay <= 2}
										{hook h="displayProductPriceBlock" product=$product type="old_price"}
										<span id="old_price_display">{if $productPriceWithoutReduction > $productPrice}{convertPrice price=$productPriceWithoutReduction|floatval}{/if}</span>
										{if $productPriceWithoutReduction > $productPrice && $tax_enabled  && ((isset($display_tax_label) && $display_tax_label == 1) OR !isset($display_tax_label)) && (isset($sttheme.display_tax_label) && $sttheme.display_tax_label)}
				    						<span class="product_tax_label">{if $priceDisplay == 1}{l s='tax excl.'}{else}{l s='tax incl.'}{/if}</span>
				    					{/if}
									{/if}
								{/strip}</p>
								{if !isset($discount_percentage) || (isset($discount_percentage) && $discount_percentage<2)}
								<p id="reduction_percent" {if $productPriceWithoutReduction <= 0 || !$product->specificPrice || $product->specificPrice.reduction_type != 'percentage'} style="display:none;"{/if} class="fl">{strip}
									<span id="reduction_percent_display" class="sale_percentage">
										{if $product->specificPrice && $product->specificPrice.reduction_type == 'percentage'}-{$product->specificPrice.reduction*100}%{/if}
									</span>
								{/strip}</p>
								<p id="reduction_amount" {if $productPriceWithoutReduction <= 0 || !$product->specificPrice || $product->specificPrice.reduction_type != 'amount' || $product->specificPrice.reduction|floatval ==0} style="display:none"{/if} class="fl">{strip}
									<span id="reduction_amount_display"  class="sale_percentage">
									{if $product->specificPrice && $product->specificPrice.reduction_type == 'amount' && $product->specificPrice.reduction|intval !=0}
										-{convertPrice price=$productPriceWithoutReduction|floatval-$productPrice|floatval}
									{/if}
									</span>
								{/strip}</p>
								{/if}
							</div> <!-- end prices -->
							{if $packItems|@count && $productPrice < $product->getNoPackPrice()}
								<div class="pack_price mar_t4">{l s='Instead of'} <span style="text-decoration: line-through;">{convertPrice price=$product->getNoPackPrice()}</span></div>
							{/if}
							{if $product->ecotax != 0}
								<div class="price-ecotax mar_t4">{l s='Including'} <span id="ecotax_price_display">{if $priceDisplay == 2}{$ecotax_tax_exc|convertAndFormatPrice}{else}{$ecotax_tax_inc|convertAndFormatPrice}{/if}</span> {l s='for green tax'}
									{if $product->specificPrice && $product->specificPrice.reduction}
									<br />{l s='(not impacted by the discount)'}
									{/if}
								</div>
							{/if}
							{if !empty($product->unity) && $product->unit_price_ratio > 0.000000}
								{math equation="pprice / punit_price" pprice=$productPrice  punit_price=$product->unit_price_ratio assign=unit_price}
								<div class="unit-price mar_t4"><span id="unit_price_display">{convertPrice price=$unit_price}</span> {l s='per'} {$product->unity|escape:'html':'UTF-8'}</div>
								{hook h="displayProductPriceBlock" product=$product type="unit_price"}
							{/if}
						{/if} {*close if for show price*}
						{hook h="displayProductPriceBlock" product=$product type="price"}
						{hook h="displayProductPriceBlock" product=$product type="weight" hook_origin='product_sheet'}
						{hook h="displayProductPriceBlock" product=$product type="after_price"}
					</div> <!-- end content_prices -->
					<div class="product_attributes clearfix">
						{if isset($groups)}
							<!-- attributes -->
							<div id="attributes">
								<div class="clearfix"></div>
								{foreach from=$groups key=id_attribute_group item=group}
									{if $group.attributes|@count}
										<fieldset class="attribute_fieldset">
											<label class="attribute_label" {if $group.group_type != 'color' && $group.group_type != 'radio'}for="group_{$id_attribute_group|intval}"{/if}>{$group.name|escape:'html':'UTF-8'}&nbsp;</label>
											{assign var="groupName" value="group_$id_attribute_group"}
											<div class="attribute_list">
												{if ($group.group_type == 'select')}
													<select name="{$groupName}" id="group_{$id_attribute_group|intval}" class="form-control attribute_select no-print">
														{foreach from=$group.attributes key=id_attribute item=group_attribute}
															<option value="{$id_attribute|intval}"{if (isset($smarty.get.$groupName) && $smarty.get.$groupName|intval == $id_attribute) || $group.default == $id_attribute} selected="selected"{/if} title="{$group_attribute|escape:'html':'UTF-8'}">{$group_attribute|escape:'html':'UTF-8'}</option>
														{/foreach}
													</select>
												{elseif ($group.group_type == 'color')}
													<ul id="color_to_pick_list" class="clearfix">
														{assign var="default_colorpicker" value=""}
														{foreach from=$group.attributes key=id_attribute item=group_attribute}
															{assign var='img_color_exists' value=file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}
															<li{if $group.default == $id_attribute} class="selected"{/if}>
																<a href="{$link->getProductLink($product)|escape:'html':'UTF-8'}" id="color_{$id_attribute|intval}" name="{if isset($colors.$id_attribute.value)}{$colors.$id_attribute.name|escape:'html':'UTF-8'}{/if}" class="color_pick{if ($group.default == $id_attribute)} selected{/if}"{if !$img_color_exists && isset($colors.$id_attribute.value) && $colors.$id_attribute.value} style="background: {$colors.$id_attribute.value|escape:'html':'UTF-8'};"{/if} title="{if isset($colors.$id_attribute.value)}{$colors.$id_attribute.name|escape:'html':'UTF-8'}{/if}">
																	{if $img_color_exists}
																		<img src="{$img_col_dir}{$id_attribute|intval}.jpg" alt="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" title="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" width="20" height="20" />
																	{/if}
																</a>
															</li>
															{if ($group.default == $id_attribute)}
																{$default_colorpicker = $id_attribute}
															{/if}
														{/foreach}
													</ul>
													<input type="hidden" class="color_pick_hidden" name="{$groupName|escape:'html':'UTF-8'}" value="{$default_colorpicker|intval}" />
												{elseif ($group.group_type == 'radio')}
													<ul class="attribute_radio_list">
														{foreach from=$group.attributes key=id_attribute item=group_attribute}
															<li>
																<input type="radio" class="attribute_radio" name="{$groupName|escape:'html':'UTF-8'}" value="{$id_attribute}" {if ($group.default == $id_attribute)} checked="checked"{/if} />
																<span class="radio_label">{$group_attribute|escape:'html':'UTF-8'}</span>
															</li>
														{/foreach}
													</ul>
												{/if}
											</div> <!-- end attribute_list -->
										</fieldset>
									{/if}
								{/foreach}
							</div> <!-- end attributes -->
						{/if}



						{if ($display_qties == 1 && !$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && $product->available_for_order)}
							<!-- number of item in stock -->
							<div id="pQuantityAvailable"{if $product->quantity <= 0} style="display: none;"{/if}>
								<span id="quantityAvailable">{$product->quantity|intval}</span>
								<span {if $product->quantity > 1} style="display: none;"{/if} id="quantityAvailableTxt">{l s='Item'}</span>
								<span {if $product->quantity == 1} style="display: none;"{/if} id="quantityAvailableTxtMultiple">{l s='Items'}</span>
							</div>
						{/if}
						<!-- availability or doesntExist -->
						<div id="availability_statut"{if !$PS_STOCK_MANAGEMENT || ($product->quantity <= 0 && !$product->available_later && $allow_oosp) || ($product->quantity > 0 && !$product->available_now) || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
							{*<span id="availability_label">{l s='Availability:'}</span>*}
							<span id="availability_value" class="{if $product->quantity <= 0 && !$allow_oosp} st-label-danger{elseif $product->quantity <= 0} st-label-warning{else} st-label-success{/if}">{if $product->quantity <= 0}{if $PS_STOCK_MANAGEMENT && $allow_oosp}{$product->available_later}{else}{l s='This product is no longer in stock'}{/if}{elseif $PS_STOCK_MANAGEMENT}{$product->available_now}{/if}</span>
						</div>
						{if $PS_STOCK_MANAGEMENT}
							{if !$product->is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
							<div class="warning_inline mar_t4" id="last_quantities"{if ($product->quantity > $last_qties || $product->quantity <= 0) || $allow_oosp || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none"{/if} >{l s='Warning: Last items in stock!'}</div>
						{/if}
						<div id="availability_date"{if ($product->quantity > 0) || !$product->available_for_order || $PS_CATALOG_MODE || !isset($product->available_date) || $product->available_date < $smarty.now|date_format:'%Y-%m-%d'} style="display: none;"{/if}>
							<span id="availability_date_label">{l s='Availability date:'}</span>
							<span id="availability_date_value">{if Validate::isDate($product->available_date)}{dateFormat date=$product->available_date full=false}{/if}</span>
						</div>
						<!-- Out of stock hook -->
						<div id="oosHook"{if $product->quantity > 0} style="display: none;"{/if}>
							{$HOOK_PRODUCT_OOS}
						</div>
						

					</div> <!-- end product_attributes -->

					<div class="box-cart-bottom">
						<!-- quantity wanted -->
						<div class="qt_cart_box clearfix {if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || (isset($restricted_country_mode) && $restricted_country_mode) || $PS_CATALOG_MODE} hidden {/if} ">
							{if !$PS_CATALOG_MODE}
							<p id="quantity_wanted_p"{if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
								<span class="quantity_input_wrap clearfix">
									<a href="#" data-field-qty="qty" class="product_quantity_down">-</a>
									<input type="text" min="1" name="qty" id="quantity_wanted" class="text" value="{if isset($quantityBackup)}{$quantityBackup|intval}{else}{if $product->minimal_quantity > 1}{$product->minimal_quantity}{else}1{/if}{/if}" />
									<a href="#" data-field-qty="qty" class="product_quantity_up">+</a>
								</span>
							</p>
							{/if}

							<div id="add_to_cart_wrap" class="{if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || (isset($restricted_country_mode) && $restricted_country_mode) || $PS_CATALOG_MODE} unvisible {/if}">
								<p id="add_to_cart" class="buttons_bottom_block no-print">
									<button type="submit" name="Submit" class="btn btn-default btn_primary exclusive">
										<span>{if $content_only && (isset($product->customization_required) && $product->customization_required)}{l s='Customize'}{else}{l s='Add to cart'}{/if}</span>
									</button>
								</p>
							</div>
						</div>
						<!-- minimal quantity wanted -->
						<p id="minimal_quantity_wanted_p"{if $product->minimal_quantity <= 1 || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
							{l s='The minimum purchase order quantity for the product is'} <b id="minimal_quantity_label">{$product->minimal_quantity}</b>
						</p>

						{if isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS}{$HOOK_PRODUCT_ACTIONS}{/if}
					</div> <!-- end box-cart-bottom -->
				</div> <!-- end box-info-product -->
			</form>
			{/if}

			{if isset($HOOK_EXTRA_RIGHT) && $HOOK_EXTRA_RIGHT}{$HOOK_EXTRA_RIGHT}{/if}

			{if $product_tabs}<div id="right_more_info_block">{$smarty.capture.product_tabs}</div>{/if}
		</div>
		<!-- end center infos-->
		<!-- pb-right-column-->
		{if !$content_only && !(isset($sttheme.product_big_image) && $sttheme.product_big_image)}
			{if isset($HOOK_PRODUCT_SECONDARY_COLUMN)}
			<div class="pb-right-column col-xs-12 col-sm-3 col-md-3">
				{if $show_brand_logo==1 && $product_manufacturer->id_manufacturer}
		            <a id="product_manufacturer_logo" {if $enable_google_rich_snippets} itemprop="brand" itemscope="" itemtype="https://schema.org/Organization" {/if} href="{$link->getmanufacturerLink($product_manufacturer->id_manufacturer, $product_manufacturer->link_rewrite)}" title="{l s='All products of this manufacturer'}" target="_top">
		                {if $enable_google_rich_snippets}<meta itemprop="name" content="{$product_manufacturer->name}" />{/if}
		                <img {if $enable_google_rich_snippets} itemprop="image" {/if} alt="{$product_manufacturer->name}" class="replace-2x" src="{$img_manu_dir}{$product_manufacturer->id_manufacturer}-manufacturer_default.jpg" />
		            </a>
		        {/if}
				{$HOOK_PRODUCT_SECONDARY_COLUMN}
			</div> 
			{/if}
		{/if}
		<!-- end pb-right-column-->
	</div> <!-- end primary_block -->
	{if !$content_only}
		{if (isset($quantity_discounts) && count($quantity_discounts) > 0)}
			<!-- quantity discount -->
			<section class="block section">
				<h3 class="title_block"><span>{l s='Volume discounts'}</span></h3>
				<div id="quantityDiscount">
					<table class="std table-product-discounts">
						<thead>
							<tr>
								<th>{l s='Quantity'}</th>
								<th>{if $display_discount_price}{l s='Price'}{else}{l s='Discount'}{/if}</th>
								<th>{l s='You Save'}</th>
							</tr>
						</thead>
						<tbody>
							{foreach from=$quantity_discounts item='quantity_discount' name='quantity_discounts'}
							{if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
								{$realDiscountPrice=$productPriceWithoutReduction|floatval-$quantity_discount.real_value|floatval}
							{else}
								{$realDiscountPrice=$productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction)|floatval}
							{/if}
							<tr id="quantityDiscount_{$quantity_discount.id_product_attribute}" class="quantityDiscount_{$quantity_discount.id_product_attribute}" data-real-discount-value="{convertPrice price = $realDiscountPrice}" data-discount-type="{$quantity_discount.reduction_type}" data-discount="{$quantity_discount.real_value|floatval}" data-discount-quantity="{$quantity_discount.quantity|intval}">
							
								<td>
									{$quantity_discount.quantity|intval}
								</td>
								<td>
									{if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
										{if $display_discount_price}
											{if $quantity_discount.reduction_tax == 0 && !$quantity_discount.price}
												{convertPrice price = $productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction_with_tax)|floatval}
											{else}
												{convertPrice price=$productPriceWithoutReduction|floatval-$quantity_discount.real_value|floatval}
											{/if}
										{else}
											{convertPrice price=$quantity_discount.real_value|floatval}
										{/if}
									{else}
										{if $display_discount_price}
											{if $quantity_discount.reduction_tax == 0}
												{convertPrice price = $productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction_with_tax)|floatval}
											{else}
												{convertPrice price = $productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction)|floatval}
											{/if}
										{else}
											{$quantity_discount.real_value|floatval}%
										{/if}
									{/if}
								</td>
								<td>
									<span>{l s='Up to'}</span>
									{if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
										{$discountPrice=$productPriceWithoutReduction|floatval-$quantity_discount.real_value|floatval}
									{else}
										{$discountPrice=$productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction)|floatval}
									{/if}
									{$discountPrice=$discountPrice * $quantity_discount.quantity}
									{$qtyProductPrice=$productPriceWithoutReduction|floatval * $quantity_discount.quantity}
									{convertPrice price=$qtyProductPrice - $discountPrice}
								</td>
							</tr>
						{/foreach}
						</tbody>
					</table>
				</div>
			</section>
		{/if}
		
		{if isset($packItems) && $packItems|@count > 0}
		<section id="blockpack" class="block section">
			<h4 class="title_block"><span>{l s='Pack content'}</span></h4>
			{include file="$tpl_dir./product-list.tpl" products=$packItems for_f='packitems'}
		</section>
		{/if}

		<!--end HOOK_PRODUCT_TAB -->
		{if isset($accessories) && $accessories}
		{assign var='flyout_buttons' value=Configuration::get('STSN_FLYOUT_BUTTONS')}
		{assign var='st_display_add_to_cart' value=Configuration::get('STSN_DISPLAY_ADD_TO_CART')}
		{assign var='use_view_more_instead' value=Configuration::get('STSN_USE_VIEW_MORE_INSTEAD')}
		{assign var='flyout_wishlist' value=Configuration::get('STSN_FLYOUT_WISHLIST')}
		{assign var='flyout_quickview' value=Configuration::get('STSN_FLYOUT_QUICKVIEW')}
		{assign var='flyout_comparison' value=Configuration::get('STSN_FLYOUT_COMPARISON')}  
		{assign var='pro_img_hover_scale' value=Configuration::get('STSN_PRO_IMG_HOVER_SCALE')}
			<!--Accessories -->
			<section id="accessories_block" class="products_block block section">
		    	<h4 class="title_block"><span>{l s='Accessories'}</span></h4>
		        <div id="accessories-itemslider" class="flexslider">    
		            <div class="nav_top_right"></div>
		            <div class="sliderwrap products_slider">
					<ul class="slides">
						{foreach from=$accessories item=accessory name=accessories_list}
							{if ($accessory.allow_oosp || $accessory.quantity_all_versions > 0 || $accessory.quantity > 0) && $accessory.available_for_order && !isset($restricted_country_mode)}
								{assign var='accessoryLink' value=$link->getProductLink($accessory.id_product, $accessory.link_rewrite, $accessory.category)}
								{capture name="new_on_sale"}
					                {if $new_sticker!=2 && isset($accessory.new) && $accessory.new == 1}<span class="new"><i>{l s='New'}</i></span>{/if}{if $sale_sticker!=2 && isset($accessory.on_sale) && $accessory.on_sale && isset($accessory.show_price) && $accessory.show_price && !$PS_CATALOG_MODE}<span class="on_sale"><i>{l s='Sale'}</i></span>{/if}
					            {/capture}
								<li class="ajax_block_product product_accessories_description {if $smarty.foreach.accessories_list.first} first_item{elseif $smarty.foreach.accessories_list.last} last_item{else} item{/if}">
									<div class="pro_outer_box">
			                        <div class="pro_first_box {if isset($sttheme.flyout_buttons) && $sttheme.flyout_buttons}hover_fly_static{/if}">
			                            <a href="{$accessoryLink|escape:'html':'UTF-8'}" title="{$accessory.legend|escape:'html':'UTF-8'}" class="product-image product_image{if $pro_img_hover_scale} pro_img_hover_scale{/if}">
											<img class="replace-2x img-responsive front-image" src="{$link->getImageLink($accessory.link_rewrite, $accessory.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$accessory.legend|escape:'html':'UTF-8'}" title="{$accessory.legend|escape:'html':'UTF-8'}" width="{$homeSize.width}" height="{$homeSize.height}" />
											{hook h='displayAnywhere' function='getHoverImage' id_product=$accessory.id_product product_link_rewrite=$accessory.link_rewrite home_default_height=$homeSize.height home_default_width=$homeSize.width product_name=$accessory.name mod='sthoverimage' caller='sthoverimage'}{$smarty.capture.new_on_sale}

											{if (!$PS_CATALOG_MODE AND ((isset($accessory.show_price) && $accessory.show_price) || (isset($accessory.available_for_order) && $accessory.available_for_order)))}
												{if isset($accessory.show_price) && $accessory.show_price && !isset($restricted_country_mode)}
													{if $discount_percentage==2 && isset($accessory.specific_prices) && $accessory.specific_prices && isset($accessory.specific_prices.reduction) && $accessory.specific_prices.reduction > 0}
							                            {if $accessory.specific_prices && $accessory.specific_prices.reduction_type=='percentage'}
							                            	<span class="sale_percentage_sticker img-circle">
														        {($accessory.specific_prices.reduction*100)|round:2}%<br />{l s='Off'}
															</span>
							                            {elseif $accessory.specific_prices && $accessory.specific_prices.reduction_type=='amount' && $accessory.specific_prices.reduction|floatval !=0}
							                            	<span class="sale_percentage_sticker img-circle">
							                            		{l s='Save'}<br />{convertPrice price=$accessory.price_without_reduction-$accessory.price|floatval}
							                            	</span>
							                            {/if}
							                        {/if}
						                        {/if}
						                    {/if}
										</a>
						                {assign var="fly_i" value=0}
										{capture name="pro_a_cart"}
											{if isset($use_view_more_instead) && $use_view_more_instead==1}
						                        <a class="view_button btn btn-default" href="{$accessoryLink|escape:'html':'UTF-8'}" title="{l s='View more'}" rel="nofollow"><div><i class="icon-eye-2 icon-0x icon_btn icon-mar-lr2"></i><span>{l s='View more'}</span></div></a>
						                    {else}
												{if !$PS_CATALOG_MODE && ($accessory.allow_oosp || $accessory.quantity > 0) && isset($add_prod_display) && $add_prod_display == 1}
											  		<a class="ajax_add_to_cart_button btn btn-default btn_primary" href="{$link->getPageLink('cart', true, NULL, "qty=1&amp;id_product={$accessory.id_product|intval}&amp;token={$static_token}&amp;add")|escape:'html':'UTF-8'}" data-id-product="{$accessory.id_product|intval}" title="{l s='Add to cart'}" rel="nofollow"><div><i class="icon-basket icon-0x icon-mar-lr2"></i><span>{l s='Add to cart'}</span></div></a>
											  		{if isset($use_view_more_instead) && $use_view_more_instead==2}
						                                <a class="view_button btn btn-default" href="{$accessoryLink|escape:'html':'UTF-8'}" title="{l s='View more'}" rel="nofollow"><div><i class="icon-eye-2 icon-0x icon_btn icon-mar-lr2"></i><span>{l s='View more'}</span></div></a>
						                                {if !$st_display_add_to_cart}{assign var="fly_i" value=$fly_i+1}{/if}
						                            {/if}
												{else}
						                            <a class="view_button btn btn-default" href="{$accessoryLink|escape:'html':'UTF-8'}" title="{l s='View'}" rel="nofollow"><div><i class="icon-eye-2 icon-0x icon-mar-lr2"></i><span>{l s='View'}</span></div></a>
												{/if}
											{/if}
						                {/capture}
						                {capture name="pro_a_compare"}
                							{if !$flyout_comparison && isset($comparator_max_item) && $comparator_max_item}
						                        <a class="add_to_compare" href="{$accessoryLink|escape:'html':'UTF-8'}" data-id-product="{$accessory.id_product}" rel="nofollow" data-product-cover="{$link->getImageLink($accessory.link_rewrite, $accessory.id_image, 'thumb_default')|escape:'html':'UTF-8'}" data-product-name="{$accessory.name|escape:'html':'UTF-8'}"><div><i class="icon-ajust icon-0x icon-mar-lr2"></i><span>{l s='Add to compare'}</span></div></a>
						        			{/if}
						                {/capture}
			                            {capture name="pro_a_wishlist"}
			                                {if !$flyout_wishlist}{hook h='displayAnywhere' function="getAddToWhishlistButton" id_product=$accessory.id_product show_icon=0 mod='stthemeeditor' caller='stthemeeditor'}{/if}
			                            {/capture}
						                {capture name="pro_quick_view"}
						                    {if !$flyout_quickview && isset($quick_view) && $quick_view}
						                        <a class="quick-view" href="{$accessoryLink|escape:'html':'UTF-8'}" rel="{$accessoryLink|escape:'html':'UTF-8'}"><div><i class="icon-search-1 icon-0x icon-mar-lr2"></i><span>{l s='Quick view'}</span></div></a>
						                    {/if}
						                {/capture}
						                {if !$st_display_add_to_cart && trim($smarty.capture.pro_a_cart)}{assign var="fly_i" value=$fly_i+1}{/if}
						                {if trim($smarty.capture.pro_a_compare)}{assign var="fly_i" value=$fly_i+1}{/if}
                            			{if trim($smarty.capture.pro_a_wishlist)}{assign var="fly_i" value=$fly_i+1}{/if}
                            			{if trim($smarty.capture.pro_quick_view)}{assign var="fly_i" value=$fly_i+1}{/if}
						                <div class="hover_fly fly_{$fly_i} clearfix">
						                    {if !$st_display_add_to_cart}{$smarty.capture.pro_a_cart}{/if}
						                    {$smarty.capture.pro_quick_view}
						                    {$smarty.capture.pro_a_compare}
                                			{$smarty.capture.pro_a_wishlist} 
						                </div>
						            </div>
						            <div class="pro_second_box">
						            {if isset($sttheme.length_of_product_name) && $sttheme.length_of_product_name==1}
						                {assign var="length_of_product_name" value=70}
						            {else}
						                {assign var="length_of_product_name" value=35}
						            {/if}
									<p itemprop="name" class="s_title_block {if isset($sttheme.length_of_product_name) && $sttheme.length_of_product_name} nohidden {/if}"><a href="{$accessoryLink|escape:'html':'UTF-8'}" title="{$accessory.name|escape:'html':'UTF-8'}">{if isset($sttheme.length_of_product_name) && $sttheme.length_of_product_name==2}{$accessory.name|escape:'html':'UTF-8'}{else}{$accessory.name|truncate:$length_of_product_name:'...'|escape:'html':'UTF-8'}{/if}</a></p>
									{if ((isset($accessory.show_price) && $accessory.show_price) || (isset($accessory.available_for_order) && $accessory.available_for_order)) && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
						                <div class="price_container">
						                    <span class="price product-price">
						                    	{if $priceDisplay != 1}
													{displayWtPrice p=$accessory.price}
												{else}
													{displayWtPrice p=$accessory.price_tax_exc}
												{/if}
												{hook h="displayProductPriceBlock" product=$accessory type="price"}
						                    </span>
						                    {hook h="displayProductPriceBlock" product=$accessory type="after_price"}
						                    {if isset($accessory.specific_prices) && $accessory.specific_prices && isset($accessory.specific_prices.reduction) && $accessory.specific_prices.reduction > 0}
						                    <span class="old-price product-price">{displayWtPrice p=$accessory.price_without_reduction}</span>
						                    {/if}
						                    {if $discount_percentage==1}
						                        {if $accessory.specific_prices && $accessory.specific_prices.reduction_type=='percentage'}
						                        	<span class="sale_percentage">
													    <i class="icon-tag"></i>-{($accessory.specific_prices.reduction*100)|round:2}%
													</span>
						                        {elseif $accessory.specific_prices && $accessory.specific_prices.reduction_type=='amount' && $accessory.specific_prices.reduction|floatval !=0}
						                        	{if !$priceDisplay}
						                        	<span class="sale_percentage">
													    <i class="icon-tag"></i>-{convertPrice price=$accessory.price_without_reduction-$accessory.price|floatval}
													</span>
						                        	{else}
						                        	<span class="sale_percentage">
													    <i class="icon-tag"></i>-{convertPrice price=$accessory.price_without_reduction-$accessory.price_tax_exc|floatval}
													</span>
						                        	{/if}
						                        {/if}
					                        {/if}
					                        {hook h="displayProductPriceBlock" product=$accessory type="price"}
						                </div>
						            {/if}
						            {if $st_display_add_to_cart==1 || $st_display_add_to_cart==2}
							        <div class="act_box {if $st_display_add_to_cart==1} display_when_hover {elseif $st_display_add_to_cart==2} display_normal {/if}">
							            {$smarty.capture.pro_a_cart}
							        </div>
							        {/if}
						            </div>
						            </div>
								</li>
							{/if}
						{/foreach}
					</ul>
				    </div>
				 </div>
		         {hook h='displayAnywhere' function="getCarouselJavascript" identify='accessories' mod='stthemeeditor' caller='stthemeeditor'}
			</section>
			<!--end Accessories -->
		{/if}
		<!-- description & features -->

		{if !$product_tabs}<div id="bottom_more_info_block" class="mar_b2">{$smarty.capture.product_tabs}</div>{/if}
		
		{if isset($HOOK_PRODUCT_FOOTER) && $HOOK_PRODUCT_FOOTER}{$HOOK_PRODUCT_FOOTER}{/if}
	{/if}
</div><!-- itemscope product wrapper -->
{strip}
{if isset($smarty.get.ad) && $smarty.get.ad}
	{addJsDefL name=ad}{$base_dir|cat:$smarty.get.ad|escape:'html':'UTF-8'}{/addJsDefL}
{/if}
{if isset($smarty.get.adtoken) && $smarty.get.adtoken}
	{addJsDefL name=adtoken}{$smarty.get.adtoken|escape:'html':'UTF-8'}{/addJsDefL}
{/if}
{addJsDef allowBuyWhenOutOfStock=$allow_oosp|boolval}
{addJsDef availableNowValue=$product->available_now|escape:'quotes':'UTF-8'}
{addJsDef availableLaterValue=$product->available_later|escape:'quotes':'UTF-8'}
{addJsDef attribute_anchor_separator=$attribute_anchor_separator|escape:'quotes':'UTF-8'}
{addJsDef attributesCombinations=$attributesCombinations}
{addJsDef currentDate=$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}
{if isset($combinations) && $combinations}
	{addJsDef combinations=$combinations}
	{addJsDef combinationsFromController=$combinations}
	{addJsDef displayDiscountPrice=$display_discount_price}
	{addJsDefL name='upToTxt'}{l s='Up to' js=1}{/addJsDefL}
{/if}
{if isset($combinationImages) && $combinationImages}
	{addJsDef combinationImages=$combinationImages}
{/if}
{if isset($id_customization)}
	{addJsDef customizationId=$id_customization}
{else}
	{addJsDef customizationId=null}
{/if}
{addJsDef customizationFields=$customizationFields}
{addJsDef default_eco_tax=$product->ecotax|floatval}
{addJsDef displayPrice=$priceDisplay|intval}
{addJsDef ecotaxTax_rate=$ecotaxTax_rate|floatval}
{if isset($cover.id_image_only)}
	{addJsDef idDefaultImage=$cover.id_image_only|intval}
{else}
	{addJsDef idDefaultImage=0}
{/if}
{addJsDef img_ps_dir=$img_ps_dir}
{addJsDef img_prod_dir=$img_prod_dir}
{addJsDef id_product=$product->id|intval}
{addJsDef jqZoomEnabled=$jqZoomEnabled|boolval}
{addJsDef maxQuantityToAllowDisplayOfLastQuantityMessage=$last_qties|intval}
{addJsDef minimalQuantity=$product->minimal_quantity|intval}
{addJsDef noTaxForThisProduct=$no_tax|boolval}
{if isset($customer_group_without_tax)}
	{addJsDef customerGroupWithoutTax=$customer_group_without_tax|boolval}
{elseif isset($sttheme.customer_group_without_tax)}
	{addJsDef customerGroupWithoutTax=$sttheme.customer_group_without_tax|boolval}
{else}
	{addJsDef customerGroupWithoutTax=false}
{/if}
{if isset($group_reduction)}
	{addJsDef groupReduction=$group_reduction|floatval}
{else}
	{addJsDef groupReduction=false}
{/if}
{addJsDef oosHookJsCodeFunctions=Array()}
{addJsDef productHasAttributes=isset($groups)|boolval}
{addJsDef productPriceTaxExcluded=($product->getPriceWithoutReduct(true)|default:'null' - $product->ecotax)|floatval}
{addJsDef productPriceTaxIncluded=($product->getPriceWithoutReduct(false)|default:'null' - $product->ecotax * (1 + $ecotaxTax_rate / 100))|floatval}
{addJsDef productBasePriceTaxExcluded=($product->getPrice(false, null, 6, null, false, false) - $product->ecotax)|floatval}
{addJsDef productBasePriceTaxExcl=($product->getPrice(false, null, 6, null, false, false)|floatval)}
{addJsDef productBasePriceTaxIncl=($product->getPrice(true, null, 6, null, false, false)|floatval)}
{addJsDef productReference=$product->reference|escape:'html':'UTF-8'}
{addJsDef productAvailableForOrder=$product->available_for_order|boolval}
{addJsDef productPriceWithoutReduction=$productPriceWithoutReduction|floatval}
{addJsDef productPrice=$productPrice|floatval}
{addJsDef productUnitPriceRatio=$product->unit_price_ratio|floatval}
{addJsDef productShowPrice=(!$PS_CATALOG_MODE && $product->show_price)|boolval}
{addJsDef PS_CATALOG_MODE=$PS_CATALOG_MODE}
{if $product->specificPrice && $product->specificPrice|@count}
	{addJsDef product_specific_price=$product->specificPrice}
{else}
	{addJsDef product_specific_price=array()}
{/if}
{if $display_qties == 1 && $product->quantity}
	{addJsDef quantityAvailable=$product->quantity}
{else}
	{addJsDef quantityAvailable=0}
{/if}
{addJsDef quantitiesDisplayAllowed=$display_qties|boolval}
{if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'percentage'}
	{addJsDef reduction_percent=($product->specificPrice.reduction*100)|round:2}
{else}
	{addJsDef reduction_percent=0}
{/if}
{if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'amount'}
	{addJsDef reduction_price=$product->specificPrice.reduction|floatval}
{else}
	{addJsDef reduction_price=0}
{/if}
{if $product->specificPrice && $product->specificPrice.price}
	{addJsDef specific_price=$product->specificPrice.price|floatval}
{else}
	{addJsDef specific_price=0}
{/if}
{addJsDef specific_currency=($product->specificPrice && $product->specificPrice.id_currency)|boolval} {* TODO: remove if always false *}
{addJsDef stock_management=$PS_STOCK_MANAGEMENT|intval}
{addJsDef taxRate=$tax_rate|floatval}
{addJsDef discount_percentage=$discount_percentage|floatval}
{addJsDefL name=doesntExist}{l s='This combination does not exist for this product. Please select another combination.' js=1}{/addJsDefL}
{addJsDefL name=doesntExistNoMore}{l s='This product is no longer in stock' js=1}{/addJsDefL}
{addJsDefL name=doesntExistNoMoreBut}{l s='with those attributes but is available with others.' js=1}{/addJsDefL}
{addJsDefL name=fieldRequired}{l s='Please fill in all the required fields before saving your customization.' js=1}{/addJsDefL}
{addJsDefL name=uploading_in_progress}{l s='Uploading in progress, please be patient.' js=1}{/addJsDefL}
{addJsDefL name=reduction_save}{l s='Save' js=1}{/addJsDefL}
{addJsDefL name=reduction_off}{l s='Off' js=1}{/addJsDefL}
{addJsDefL name='product_fileDefaultHtml'}{l s='No file selected' js=1}{/addJsDefL}
{addJsDefL name='product_fileButtonHtml'}{l s='Choose File' js=1}{/addJsDefL}
{if isset($sttheme.product_big_image) && $sttheme.product_big_image}
	{addJsDef product_big_image=true}
	{addJsDef product_main_image_width=$smarty.capture.big_default_width}
	{addJsDef product_main_image_height=$smarty.capture.big_default_height}
{else}
	{addJsDef product_big_image=false}
	{addJsDef product_main_image_width=$largeSize.width}
	{addJsDef product_main_image_height=$largeSize.height}
{/if}
{addJsDef pro_thumbnails=$pro_thumbnails|boolval}
{/strip}
{/if}
