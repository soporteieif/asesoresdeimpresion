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

<!-- MODULE special slider -->
{if $aw_display || (isset($products) && $products)}
<section id="special_products_footer_{$hook_hash}" class="special_products_footer block col-sm-12 col-md-3">
    <div class="title_block"><div class="title_block_name">{l s='Specials' mod='stspecialslider'}</div><a href="javascript:;" class="opener dlm">&nbsp;</a></div>
    <div class="footer_block_content">
    {if is_array($products) && $products|count}
    <ul class="pro_column_list">
        {foreach $products as $product}
        <li class="clearfix">
            <div class="pro_column_left">
            <a href="{$product.link|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}">
			<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'thumb_default')}" alt="{$product.name|escape:html:'UTF-8'}" height="{$thumbSize.height}" width="{$thumbSize.width}" />
			</a>
            </div>
			<div class="pro_column_right">
				<h4 class="s_title_block nohidden"><a href="{$product.link|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}">{$product.name|truncate:50:'...'|escape:html:'UTF-8'}</a></h4>
                {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                    <span class="price">
                    {if !$priceDisplay}{convertPrice price=$product.price}
                    {else}
                    {convertPrice price=$product.price_tax_exc}
                    {/if}
                    </span>
                    {if isset($product.reduction) && $product.reduction}<span class="old_price">{convertPrice price=$product.price_without_reduction}</span>{/if}
                    {hook h="displayProductPriceBlock" product=$product type="price"}
                {/if}
            </div>
        </li>
        {/foreach}
    </ul>
    {else}
		<p class="warning">{l s='No Special products' mod='stspecialslider'}</p>
    {/if}
    </div>
</section>
{/if}
<!-- /MODULE special slider  -->