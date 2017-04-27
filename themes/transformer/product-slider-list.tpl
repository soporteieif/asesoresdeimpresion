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
{assign var='countdown_active' value=Configuration::get('ST_COUNTDOWN_ACTIVE')}
{assign var='countdown_style' value=Configuration::get('ST_COUNTDOWN_STYLE')}
{assign var='countdown_v_alignment' value=Configuration::get('ST_COUNTDOWN_V_ALIGNMENT')}
{assign var='countdown_title_aw_display' value=Configuration::get('ST_COUNTDOWN_TITLE_AW_DISPLAY')}
<div class="nav_top_right"></div>
<div class="sliderwrap products_slider">
    <ul class="slides">
	{foreach $products as $product}
        {if $product@first || $product@index is div by $slider_items}
        <li class="{if $product@first}first_item{elseif $product@last}last_item{else}item{/if}">
        {/if}
        {if !isset($display_pro_col) || !$display_pro_col}
            <div class="pro_column_box clearfix">
            <a href="{$product.link|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}" class="pro_column_left">
			<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'thumb_default')}" alt="{$product.name|escape:html:'UTF-8'}" height="{$thumbSize.height}" width="{$thumbSize.width}" class="replace-2x img-responsive" />
			</a>
			<div class="pro_column_right">
				<h4 class="s_title_block nohidden"><a href="{$product.link|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}">{$product.name|truncate:50:'...'|escape:html:'UTF-8'}</a></h4>
                {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                    <div class="mar_b6">
                    <span class="price">
                    {if !$priceDisplay}{convertPrice price=$product.price}
                    {else}
                    {convertPrice price=$product.price_tax_exc}
                    {/if}
                    </span>
                    {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                        <span class="old_price">{convertPrice price=$product.price_without_reduction}</span>
                        {if isset($discount_percentage) && $discount_percentage==1}
                        <span class="sale_percentage">
                            -{if $product.specific_prices && $product.specific_prices.reduction_type=='percentage'}{($product.specific_prices.reduction*100)|round:2}%{elseif $product.specific_prices && $product.specific_prices.reduction_type=='amount' && $product.specific_prices.reduction|floatval !=0}{if !$priceDisplay}{convertPrice price=$product.price_without_reduction-$product.price|floatval}{else}{convertPrice price=$product.price_without_reduction-$product.price_tax_exc|floatval}{/if}{/if}
                        </span>
                        {/if}
                    {/if}
                    {hook h="displayProductPriceBlock" product=$product type="price"}
                    </div>
                    {if $countdown_active}
                        {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                            {if ($smarty.now|date_format:'%Y-%m-%d %H:%M:%S' >= $product.specific_prices.from && $smarty.now|date_format:'%Y-%m-%d %H:%M:%S' < $product.specific_prices.to)}
                                <div class="countdown_outer_box">
                                    <div class="countdown_box">
                                        <i class="icon-clock"></i><span class="countdown_pro c_countdown_timer" data-countdown="{$product.specific_prices.to|date_format:'%Y/%m/%d %H:%M:%S'}" data-id-product="{$product.id_product}"></span>
                                    </div>
                                </div>
                            {elseif ($product.specific_prices.to == '0000-00-00 00:00:00') && ($product.specific_prices.from == '0000-00-00 00:00:00') && $countdown_title_aw_display}
                                <div class="countdown_outer_box countdown_pro_perm" data-id-product="{$product.id_product}">
                                    <div class="countdown_box">
                                        <i class="icon-clock"></i><span>{l s='Limited special offer'}</span>
                                    </div>
                                </div>
                            {/if}
                        {/if}
                    {/if}
                {/if}
			</div>
            </div>

        {else}
        {if $countdown_active}
            {capture name="pro_count_down"}
            {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                {if ($smarty.now|date_format:'%Y-%m-%d %H:%M:%S' >= $product.specific_prices.from && $smarty.now|date_format:'%Y-%m-%d %H:%M:%S' < $product.specific_prices.to)}
                    {if $countdown_v_alignment!=2}
                        <div class="countdown_wrap countdown_timer countdown_style_{$countdown_style|default:0} {if $countdown_v_alignment} v_middle{/if} s_countdown_timer" data-countdown="{$product.specific_prices.to|date_format:'%Y/%m/%d %H:%M:%S'}" data-id-product="{$product.id_product}"></div>
                    {else}
                        <div class="countdown_outer_box">
                            <div class="countdown_box">
                                <i class="icon-clock"></i><span class="countdown_pro c_countdown_timer" data-countdown="{$product.specific_prices.to|date_format:'%Y/%m/%d %H:%M:%S'}" data-id-product="{$product.id_product}"></span>
                            </div>
                        </div>
                    {/if}
                {elseif ($product.specific_prices.to == '0000-00-00 00:00:00') && ($product.specific_prices.from == '0000-00-00 00:00:00') && $countdown_title_aw_display}
                    {if $countdown_v_alignment!=2}
                        <div class="countdown_wrap s_countdown_perm {if $countdown_v_alignment} v_middle{/if}" data-id-product="{$product.id_product}">
                            <div class="countdown_title">{l s='Limited special offer'}</div>
                        </div>
                    {else}
                        <div class="countdown_outer_box countdown_pro_perm" data-id-product="{$product.id_product}">
                            <div class="countdown_box">
                                <i class="icon-clock"></i><span>{l s='Limited special offer'}</span>
                            </div>
                        </div>
                    {/if}
                {/if}
            {/if}{/if}
            {/capture}
        {/if}
        <div class="pro_outer_box">
        <div class="pro_first_box">
            <a href="{$product.link|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}" class="product_image">
            <img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}" alt="{$product.name|escape:html:'UTF-8'}" height="{$homeSize.height}" width="{$homeSize.width}" class="replace-2x img-responsive" />
            </a>
            {if $countdown_v_alignment!=2 && isset($smarty.capture.pro_count_down)}{$smarty.capture.pro_count_down}{/if}
        </div>
        <div class="pro_second_box">
            <p class="s_title_block nohidden"><a href="{$product.link|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}">{$product.name|truncate:50:'...'|escape:html:'UTF-8'}</a></p>
            {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                <div class="price_container">
                    <span class="price">
                    {if !$priceDisplay}{convertPrice price=$product.price}
                    {else}
                    {convertPrice price=$product.price_tax_exc}
                    {/if}
                    </span>
                    {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                        <span class="old_price">{convertPrice price=$product.price_without_reduction}</span>
                        {if isset($discount_percentage) && $discount_percentage==1}
                        <span class="sale_percentage">
                            -{if $product.specific_prices && $product.specific_prices.reduction_type=='percentage'}{($product.specific_prices.reduction*100)|round:2}%{elseif $product.specific_prices && $product.specific_prices.reduction_type=='amount' && $product.specific_prices.reduction|floatval !=0}{if !$priceDisplay}{convertPrice price=$product.price_without_reduction-$product.price|floatval}{else}{convertPrice price=$product.price_without_reduction-$product.price_tax_exc|floatval}{/if}{/if}
                        </span>
                        {/if}
                    {/if}
                    {hook h="displayProductPriceBlock" product=$product type="price"}
                </div>
            {/if}
            {if $countdown_v_alignment==2 && isset($smarty.capture.pro_count_down)}{$smarty.capture.pro_count_down}{/if}
        </div>
        </div>
        {/if}
        {if $product@last || $product@iteration is div by $slider_items}
        </li>
        {/if}
	{/foreach}
	</ul>
</div>