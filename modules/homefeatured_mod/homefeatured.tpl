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

<!-- MODULE Home Featured Products -->
{assign var='discount_percentage' value=Configuration::get('STSN_DISCOUNT_PERCENTAGE')}
{assign var='st_display_add_to_cart' value=Configuration::get('STSN_DISPLAY_ADD_TO_CART')}
{assign var='use_view_more_instead' value=Configuration::get('STSN_USE_VIEW_MORE_INSTEAD')}
{assign var='pro_quantity_input' value=Configuration::get('STSN_PRO_QUANTITY_INPUT')}
<div id="featured-products_block_center_container_{$hook_hash}" class="featured-products_block_center_container block">
{if isset($homeverybottom) && $homeverybottom}<div class="wide_container"><div class="container">{/if}
<section id="featured-products_block_center_{$hook_hash}" class="products_block section">
	<h4 class="title_block mar_b1"><span>{l s='Featured Products' mod='homefeatured_mod'}</span></h4>
	{if isset($products) AND $products}
    <ul id="featured_itemlist_{$hook_hash}" class="pro_itemlist row">
        {foreach $products as $product}
		<li class="ajax_block_product col-lg-{(12/$pro_per_lg)|replace:'.':'-'} col-md-{(12/$pro_per_md)|replace:'.':'-'} col-sm-{(12/$pro_per_sm)|replace:'.':'-'} col-xs-{(12/$pro_per_xs)|replace:'.':'-'} col-xxs-{(12/$pro_per_xxs)|replace:'.':'-'}  {if $product@iteration%$pro_per_lg == 1} first-item-of-desktop-line{/if}{if $product@iteration%$pro_per_md == 1} first-item-of-line{/if}{if $product@iteration%$pro_per_sm == 1} first-item-of-tablet-line{/if}{if $product@iteration%$pro_per_xs == 1} first-item-of-mobile-line{/if}{if $product@iteration%$pro_per_xxs == 1} first-item-of-portrait-line{/if}">
			<div class="itemlist_left">
                <a class="product_image" href="{$product.link|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}"><img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'medium_default')}" height="{$mediumSize.height}" width="{$mediumSize.width}" alt="{$product.name|escape:html:'UTF-8'}" /></a>
            </div>
            <div class="itemlist_right">
    			<p class="s_title_block"><a href="{$product.link|escape:'html'}" title="{$product.name|escape:'html':'UTF-8'}">{$product.name|truncate:40:'...'|escape:'html':'UTF-8'}</a></p>
                {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                    <div class="price_container mar_b10">
                        <span class="price">
                        {if !$priceDisplay}{convertPrice price=$product.price}
                        {else}
                        {convertPrice price=$product.price_tax_exc}
                        {/if}
                        </span>
                        {if isset($product.reduction) && $product.reduction}
                            <span class="old_price">{convertPrice price=$product.price_without_reduction}</span>
                            {if isset($discount_percentage) && $discount_percentage}
                            <span class="sale_percentage">
                                <i class="icon-tag"></i>-{if $product.specific_prices.reduction_type=='percentage'}{$product.specific_prices.reduction*100|floatval}%{elseif $product.specific_prices.reduction_type=='amount'}{convertPrice price=$product.price_without_reduction-$product.price|floatval}{/if}
                            </span>
                            {/if}
                        {/if}
                        {hook h="displayProductPriceBlock" product=$product type="price"}
                    </div>
                {else}
                    <!--<div style="height:21px;"></div>-->
                {/if}  
                {if $st_display_add_to_cart!=3}
                <div class="itemlist_action">
                    {if isset($use_view_more_instead) && $use_view_more_instead==1}
                        <a class="button exclusive view_button" href="{$product.link|escape:'html'}" title="{l s='View' mod='homefeatured_mod'}" rel="nofollow"><i class="icon-eye-2 icon_btn icon-1x icon-mar-lr2"></i><span>{l s='View' mod='homefeatured_mod'}</span></a>
                    {else}
                        {if ($product.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.available_for_order AND !isset($restricted_country_mode) AND $product.minimal_quantity <= 1 AND $product.customizable != 2 AND !$PS_CATALOG_MODE}          
    						{if ($product.quantity > 0 OR $product.allow_oosp)}
                            {if $pro_quantity_input}
                            <div class="s_quantity_wanted">
                                <span class="s_quantity_input_wrap clearfix">
                                    <a href="#" class="s_product_quantity_down">-</a>
                                    <input type="text" min="1" name="qty" class="s_product_quantity_{$product.id_product}" value="{if $product.minimal_quantity > 1}{$product.minimal_quantity}{else}1{/if}" />
                                    <a href="#" class="s_product_quantity_up">+</a>
                                </span>
                            </div>
                            {/if}
    						<a class="exclusive ajax_add_to_cart_button" href="{$link->getPageLink('cart')|escape:'html'}?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" rel="nofollow" title="{l s='Add to Cart' mod='homefeatured_mod'}" data-id-product="{$product.id_product|intval}" ><i class="icon-basket icon_btn icon-1x icon-mar-lr2"></i><span>{l s='Add to Cart' mod='homefeatured_mod'}</span></a>
    						{else}
                            <a class="button exclusive view_button" href="{$product.link|escape:'html'}" title="{l s='View' mod='homefeatured_mod'}" rel="nofollow"><i class="icon-eye-2 icon_btn icon-1x icon-mar-lr2"></i><span>{l s='View' mod='homefeatured_mod'}</span></a>
    						{/if}
    					{else}
    						<!--<div style="height:23px;"></div>-->
                        {/if}
                    {/if}
                </div>
                {/if}
            </div>
        </li>
        {/foreach}
	</ul>
	{else}
		<p class="warning">{l s='No featured products' mod='homefeatured_mod'}</p>
	{/if}
</section>
{if isset($homeverybottom) && $homeverybottom}</div></div>{/if}
</div>
{if $has_background_img && $speed}
<script type="text/javascript">
//<![CDATA[
{literal}
jQuery(function($) {
    $('#featured-products_block_center_container_{/literal}{$hook_hash}{literal}').parallax("50%", {/literal}{$speed|floatval}{literal});
});
{/literal} 
//]]>
</script>
{/if}
<!-- /MODULE Home Featured Products -->