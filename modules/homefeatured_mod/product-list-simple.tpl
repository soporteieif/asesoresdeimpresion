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
{if isset($products) AND $products}
    {assign var='discount_percentage' value=Configuration::get('STSN_DISCOUNT_PERCENTAGE')}
    {capture name="nbItemsPerLineLarge"}{hook h='displayAnywhere' function='getProductsPerRow' for_w=$for_f devices='xl' mod='stthemeeditor' caller='stthemeeditor'}{/capture}
    {capture name="nbItemsPerLineDesktop"}{hook h='displayAnywhere' function='getProductsPerRow' for_w=$for_f devices='lg' mod='stthemeeditor' caller='stthemeeditor'}{/capture}
    {capture name="nbItemsPerLine"}{hook h='displayAnywhere' function='getProductsPerRow' for_w=$for_f devices='md' mod='stthemeeditor' caller='stthemeeditor'}{/capture}
    {capture name="nbItemsPerLineTablet"}{hook h='displayAnywhere' function='getProductsPerRow' for_w=$for_f devices='sm' mod='stthemeeditor' caller='stthemeeditor'}{/capture}
    {capture name="nbItemsPerLineMobile"}{hook h='displayAnywhere' function='getProductsPerRow' for_w=$for_f devices='xs' mod='stthemeeditor' caller='stthemeeditor'}{/capture}
    {capture name="nbItemsPerLinePortrait"}{hook h='displayAnywhere' function='getProductsPerRow' for_w=$for_f devices='xxs' mod='stthemeeditor' caller='stthemeeditor'}{/capture}
    <ul class="pro_itemlist row">
    {foreach $products as $product}
        <li class="ajax_block_product col-xl-{(12/$smarty.capture.nbItemsPerLineLarge)|replace:'.':'-'}  col-lg-{(12/$smarty.capture.nbItemsPerLineDesktop)|replace:'.':'-'} col-md-{(12/$smarty.capture.nbItemsPerLine)|replace:'.':'-'} col-sm-{(12/$smarty.capture.nbItemsPerLineTablet)|replace:'.':'-'} col-xs-{(12/$smarty.capture.nbItemsPerLineMobile)|replace:'.':'-'} col-xxs-{(12/$smarty.capture.nbItemsPerLinePortrait)|replace:'.':'-'}  {if $product@iteration%$smarty.capture.nbItemsPerLineLarge == 1} first-item-of-large-line{/if} {if $product@iteration%$smarty.capture.nbItemsPerLineDesktop == 1} first-item-of-desktop-line{/if}{if $product@iteration%$smarty.capture.nbItemsPerLine == 1} first-item-of-line{/if}{if $product@iteration%$smarty.capture.nbItemsPerLineTablet == 1} first-item-of-tablet-line{/if}{if $product@iteration%$smarty.capture.nbItemsPerLineMobile == 1} first-item-of-mobile-line{/if}{if $product@iteration%$smarty.capture.nbItemsPerLinePortrait == 1} first-item-of-portrait-line{/if}">
            <div class="itemlist_left">
                <a class="product_image" href="{$product.link|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}"><img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'medium_default')}" height="{$mediumSize.height}" width="{$mediumSize.width}" alt="{$product.name|escape:html:'UTF-8'}" title="{$product.name|escape:html:'UTF-8'}" /></a>
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
                                -{if $product.specific_prices.reduction_type=='percentage'}{$product.specific_prices.reduction*100|floatval}%{elseif $product.specific_prices.reduction_type=='amount'}{convertPrice price=$product.price_without_reduction-$product.price|floatval}{/if}
                            </span>
                            {/if}
                        {/if}
                    </div>
                {else}
                    <!--<div style="height:21px;"></div>-->
                {/if}  
                <div class="itemlist_action">
                    {if ($product.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.available_for_order AND !isset($restricted_country_mode) AND $product.minimal_quantity <= 1 AND $product.customizable != 2 AND !$PS_CATALOG_MODE}          
                        {if ($product.quantity > 0 OR $product.allow_oosp)}
                        <a class="exclusive ajax_add_to_cart_button" href="{$link->getPageLink('cart')|escape:'html'}?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" rel="nofollow" title="{l s='Add to Cart'}" data-id-product="{$product.id_product|intval}" ><i class="icon-basket icon-1x icon-mar-lr2"></i><span>{l s='Add to Cart'}</span></a>
                        {else}
                        <a class="button exclusive view_button" href="{$product.link|escape:'html'}" title="{l s='View'}" rel="nofollow"><i class="icon-eye-2 icon-1x icon-mar-lr2"></i><span>{l s='View'}</span></a>
                        {/if}
                    {else}
                        <!--<div style="height:23px;"></div>-->
                    {/if}
                </div>
            </div>
        </li>
    {/foreach}
    </ul>
{else}
    <p class="warning">{l s='No featured products'}</p>
{/if}