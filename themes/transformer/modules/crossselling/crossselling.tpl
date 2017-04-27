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
{if isset($orderProducts) && count($orderProducts)}
{capture name="home_default_width"}{getWidthSize type='home_default'}{/capture}
{capture name="home_default_height"}{getHeightSize type='home_default'}{/capture}
{assign var='new_sticker' value=Configuration::get('STSN_NEW_STYLE')}
{assign var='sale_sticker' value=Configuration::get('STSN_SALE_STYLE')}
{assign var='st_display_add_to_cart' value=Configuration::get('STSN_DISPLAY_ADD_TO_CART')}
{assign var='use_view_more_instead' value=Configuration::get('STSN_USE_VIEW_MORE_INSTEAD')}
{assign var='flyout_buttons' value=Configuration::get('STSN_FLYOUT_BUTTONS')}
{assign var='pro_img_hover_scale' value=Configuration::get('STSN_PRO_IMG_HOVER_SCALE')}
<section id="crossselling-products_block_center" class="block products_block section">
    <h4 class="title_block">
        <span>
        {if $page_name == 'product'}
            {l s='Customers who bought this product also bought:' mod='crossselling'}
        {else}
            {l s='We recommend' mod='crossselling'}
        {/if}
        </span>
    </h4>
	<div id="crossselling-itemslider" class="flexslider">
        <div class="nav_top_right"></div>
        <div class="sliderwrap products_slider">
            <ul class="slides">
                {foreach $orderProducts as $product}
                    <li class="ajax_block_product {if $product@first}first_item{elseif $product@last}last_item{else}item{/if}">
                        {capture name="new_on_sale"}
                            {if $new_sticker!=2 && isset($product.new) && $product.new == 1}<span class="new"><i>{l s='New' mod='crossselling'}</i></span>{/if}{if $sale_sticker!=2 && isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}<span class="on_sale"><i>{l s='Sale' mod='crossselling'}</i></span>{/if}
                        {/capture}
                        {assign var="fly_i" value=0}
                        {capture name="pro_a_cart"}
                            {if isset($use_view_more_instead) && $use_view_more_instead==1}
                                 <a class="view_button btn btn-default" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='View more' mod='crossselling'}" rel="nofollow"><div><i class="icon-eye-2 icon-0x icon_btn icon-mar-lr2"></i><span>{l s='View more' mod='crossselling'}</span></div></a>
                            {else}
                                {if !$PS_CATALOG_MODE && ($product.allow_oosp || $product.quantity > 0)}
                                    <a class="ajax_add_to_cart_button btn btn-default btn_primary" href="{$link->getPageLink('cart', true, NULL, "qty=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}&amp;add")|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='crossselling'}" data-id-product="{$product.id_product|intval}"><div><i class="icon-basket icon-0x icon_btn icon-mar-lr2"></i><span>{l s='Add to cart' mod='crossselling'}</span></div></a>
                                    {if isset($use_view_more_instead) && $use_view_more_instead==2}
                                        <a class="view_button btn btn-default" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='View more' mod='crossselling'}" rel="nofollow"><div><i class="icon-eye-2 icon-0x icon_btn icon-mar-lr2"></i><span>{l s='View more' mod='crossselling'}</span></div></a>
                                        {if !$st_display_add_to_cart}{assign var="fly_i" value=$fly_i+1}{/if}
                                    {/if}
                                {/if}
                            {/if}
                        {/capture}
                        <div class="pro_outer_box">
                        <div class="pro_first_box {if $flyout_buttons}hover_fly_static{/if}" itemprop="isRelatedTo" itemscope itemtype="https://schema.org/Product">
                            <a href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:html:'UTF-8'}" class="product_image{if $pro_img_hover_scale} pro_img_hover_scale{/if}"><img itemprop="image" src="{$product.image}" alt="{$product.name|escape:html:'UTF-8'}" class="replace-2x img-responsive front-image" width="{$smarty.capture.home_default_width}" height="{$smarty.capture.home_default_height}" />{$smarty.capture.new_on_sale}</a>
                            {if !$st_display_add_to_cart && trim($smarty.capture.pro_a_cart)}{assign var="fly_i" value=$fly_i+1}{/if}
                            <div class="hover_fly fly_{$fly_i} clearfix">
                                {if !$st_display_add_to_cart}{$smarty.capture.pro_a_cart}{/if}
                            </div>
                        </div>
                        <div class="pro_second_box">
                        {if isset($sttheme.length_of_product_name) && $sttheme.length_of_product_name==1}
                            {assign var="length_of_product_name" value=70}
                        {else}
                            {assign var="length_of_product_name" value=35}
                        {/if}
                        <p itemprop="name" class="s_title_block {if isset($sttheme.length_of_product_name) && $sttheme.length_of_product_name} nohidden {/if}"><a itemprop="url" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'htmlall':'UTF-8'}">{if isset($sttheme.length_of_product_name) && $sttheme.length_of_product_name==2}{$product.name|escape:'htmlall':'UTF-8'}{else}{$product.name|escape:'htmlall':'UTF-8'|truncate:$length_of_product_name:'...'}{/if}</a></p>
                        {if $crossDisplayPrice AND $product.show_price == 1 AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                            <div class="price_container">
                                <span class="price">{convertPrice price=$product.displayed_price}</span>
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
                {/foreach}
            </ul>
        </div>
    </div>
    {hook h='displayAnywhere' function="getCarouselJavascript" identify='crossselling' mod='stthemeeditor' caller='stthemeeditor'}
</section>
{/if}
