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
{capture name="home_default_width"}{getWidthSize type='home_default'}{/capture}
{capture name="home_default_height"}{getHeightSize type='home_default'}{/capture}
{assign var='st_yotpo_sart' value=Configuration::get('STSN_YOTPO_SART')}
{assign var='st_yotpoAppkey' value=Configuration::get('yotpo_app_key')}
{capture name="st_yotpoDomain"}{hook h='displayAnywhere' function="getYotpoDomain" mod='stthemeeditor' caller='stthemeeditor'}{/capture}
{capture name="st_yotpoLanguage"}{hook h='displayAnywhere' function="getYotpoLanguage" mod='stthemeeditor' caller='stthemeeditor'}{/capture}
{assign var='new_sticker' value=Configuration::get('STSN_NEW_STYLE')}
{assign var='sale_sticker' value=Configuration::get('STSN_SALE_STYLE')}
{assign var='discount_percentage' value=Configuration::get('STSN_DISCOUNT_PERCENTAGE')}
{assign var='st_display_add_to_cart' value=Configuration::get('STSN_DISPLAY_ADD_TO_CART')}
{assign var='use_view_more_instead' value=Configuration::get('STSN_USE_VIEW_MORE_INSTEAD')}
{assign var='flyout_wishlist' value=Configuration::get('STSN_FLYOUT_WISHLIST')}
{assign var='flyout_quickview' value=Configuration::get('STSN_FLYOUT_QUICKVIEW')}
{assign var='flyout_comparison' value=Configuration::get('STSN_FLYOUT_COMPARISON')}    
{assign var='flyout_buttons' value=Configuration::get('STSN_FLYOUT_BUTTONS')}
{assign var='countdown_active' value=Configuration::get('ST_COUNTDOWN_ACTIVE')}
{assign var='countdown_style' value=Configuration::get('ST_COUNTDOWN_STYLE')}
{assign var='countdown_v_alignment' value=Configuration::get('ST_COUNTDOWN_V_ALIGNMENT')}
{assign var='countdown_title_aw_display' value=Configuration::get('ST_COUNTDOWN_TITLE_AW_DISPLAY')}
{assign var='pro_img_hover_scale' value=Configuration::get('STSN_PRO_IMG_HOVER_SCALE')}
{assign var='pro_quantity_input' value=Configuration::get('STSN_PRO_QUANTITY_INPUT')}
<div class="{if isset($direction_nav) && $direction_nav}nav_left_right{else}nav_top_right{/if}"></div>
<div class="sliderwrap products_slider">
    <ul class="slides">
	{foreach $products as $product}
		<li class="ajax_block_product {if $product@first}first_item{elseif $product@last}last_item{else}item{/if}">
            {capture name="new_on_sale"}
                {if $new_sticker!=2 && isset($product.new) && $product.new == 1}<span class="new"><i>{l s='New'}</i></span>{/if}{if $sale_sticker!=2 && isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}<span class="on_sale"><i>{l s='Sale'}</i></span>{/if}{if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0 && isset($sttheme.discount_percentage) && $sttheme.discount_percentage==2}<span class="sale_percentage_sticker img-circle">{if $product.specific_prices && $product.specific_prices.reduction_type=='percentage'}{($product.specific_prices.reduction*100)|round:2}%<br />{l s='Off'}{elseif $product.specific_prices && $product.specific_prices.reduction_type=='amount' && $product.specific_prices.reduction|floatval !=0}{l s='Save'}<br />{if !$priceDisplay}{convertPrice price=$product.price_without_reduction-$product.price|floatval}{else}{convertPrice price=$product.price_without_reduction-$product.price_tax_exc|floatval}{/if}{/if}</span>{/if}{/if}
            {/capture}
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
            {assign var='pro_image' value=$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')} 
            <div class="pro_outer_box">
            <div class="pro_first_box {if isset($sttheme.flyout_buttons) && $sttheme.flyout_buttons}hover_fly_static{/if} ">
                <a href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:html:'UTF-8'}" class="product_image{if $pro_img_hover_scale} pro_img_hover_scale{/if}"><img src="{$pro_image}" width="{$smarty.capture.home_default_width}" height="{$smarty.capture.home_default_height}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" class="replace-2x img-responsive front-image" />{if isset($product['hover_image']) && $product['hover_image']}{$product.hover_image}{/if}{$smarty.capture.new_on_sale}</a>
                {assign var="fly_i" value=0}
                {assign var="has_add_to_cart" value=0}
                {capture name="pro_a_cart"}
                    {if isset($use_view_more_instead) && $use_view_more_instead==1}
                         <a class="view_button btn btn-default" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='View more'}" rel="nofollow"><div><i class="icon-eye-2 icon-0x icon_btn icon-mar-lr2"></i><span>{l s='View more'}</span></div></a>
                    {else}
                        {if ($product.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.available_for_order AND !isset($restricted_country_mode) AND $product.minimal_quantity <= 1 AND $product.customizable != 2 AND !$PS_CATALOG_MODE}          
                            {if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
    						  <a class="ajax_add_to_cart_button btn btn-default btn_primary" href="{$link->getPageLink('cart')|escape:'html'}?qty=1&amp;id_product={$product.id_product}{if isset($static_token)}&amp;token={$static_token}{/if}&amp;add" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}"><div><i class="icon-basket icon-0x icon_btn icon-mar-lr2"></i><span>{l s='Add to cart'}</span></div></a>
                              {assign var="has_add_to_cart" value=1}
                              {if isset($use_view_more_instead) && $use_view_more_instead==2}
                                <a class="view_button btn btn-default" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='View more'}" rel="nofollow"><div><i class="icon-eye-2 icon-0x icon_btn icon-mar-lr2"></i><span>{l s='View more'}</span></div></a>
                                {if !$st_display_add_to_cart}{assign var="fly_i" value=$fly_i+1}{/if}
                              {/if}
    						{else}
                                <a class="view_button btn btn-default" href="{$product.link|escape:'html'}" title="{l s='View'}" rel="nofollow"><div><i class="icon-eye-2 icon-0x icon_btn icon-mar-lr2"></i><span>{if (isset($product.customization_required) && $product.customization_required)}{l s='Customize'}{else}{l s='View'}{/if}</span></div></a>
    						{/if}
    					{else}
                        {/if}
					{/if}
                {/capture}
                {capture name="pro_a_compare"}
                    {if !$flyout_comparison && isset($comparator_max_item) && $comparator_max_item}
                        <a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}" rel="nofollow" data-product-cover="{$link->getImageLink($product.link_rewrite, $product.id_image, 'thumb_default')|escape:'html':'UTF-8'}" data-product-name="{$product.name|escape:'html':'UTF-8'}" title="{l s='Add to compare'}"><div><i class="icon-ajust icon-0x icon_btn icon-mar-lr2"></i><span>{l s='Add to compare'}</span></div></a>
        			{/if}
                {/capture}
                {capture name="pro_quick_view"}
                    {if !$flyout_quickview && isset($quick_view) && $quick_view}
                        <a class="quick-view" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}" title="{l s='Quick view'}"><div><i class="icon-search-1 icon-0x icon_btn icon-mar-lr2"></i><span>{l s='Quick view'}</span></div></a>
                    {/if}
                {/capture}
                {if !$st_display_add_to_cart && trim($smarty.capture.pro_a_cart)}{assign var="fly_i" value=$fly_i+1}{/if}
                {if trim($smarty.capture.pro_a_compare)}{assign var="fly_i" value=$fly_i+1}{/if}
                {if trim($smarty.capture.pro_quick_view)}{assign var="fly_i" value=$fly_i+1}{/if}
                {if !$flyout_wishlist && isset($product['pro_a_wishlist']) && $product['pro_a_wishlist']}{assign var="fly_i" value=$fly_i+1}{/if}
                
                <div class="hover_fly fly_{$fly_i} clearfix">
                    {if !$st_display_add_to_cart}{$smarty.capture.pro_a_cart}{/if}
                    {$smarty.capture.pro_quick_view}
                    {$smarty.capture.pro_a_compare}
                    {if !$flyout_wishlist && isset($product['pro_a_wishlist']) && $product['pro_a_wishlist']}{$product.pro_a_wishlist}{/if} 
                </div>
                {if $countdown_v_alignment!=2 && isset($smarty.capture.pro_count_down)}{$smarty.capture.pro_count_down}{/if}
            </div>
            <div class="pro_second_box">
            {if isset($sttheme.length_of_product_name) && $sttheme.length_of_product_name==1}
                {assign var="length_of_product_name" value=70}
            {else}
                {assign var="length_of_product_name" value=35}
            {/if}
			<p class="s_title_block {if isset($sttheme.length_of_product_name) && $sttheme.length_of_product_name} nohidden {/if}"><a href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">{if isset($sttheme.length_of_product_name) && $sttheme.length_of_product_name==2}{$product.name|escape:'html':'UTF-8'}{else}{$product.name|truncate:$length_of_product_name:'...'|escape:'html':'UTF-8'}{/if}</a></p>
            {hook h='displayProductListReviews' product=$product}
            {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                <div class="price_container">
                    {hook h="displayProductPriceBlock" product=$product type="before_price"}
                    <span class="price">
                    {if !$priceDisplay}{convertPrice price=$product.price}
                    {else}
                    {convertPrice price=$product.price_tax_exc}
                    {/if}
                    </span>
                    {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                        {hook h="displayProductPriceBlock" product=$product type="old_price"}
                        <span class="old_price">{convertPrice price=$product.price_without_reduction}</span>
                        {if isset($sttheme.discount_percentage) && $sttheme.discount_percentage==1}
                        <span class="sale_percentage">
                            <i class="icon-tag"></i>-{if $product.specific_prices && $product.specific_prices.reduction_type=='percentage'}{($product.specific_prices.reduction*100)|round:2}%{elseif $product.specific_prices && $product.specific_prices.reduction_type=='amount' && $product.specific_prices.reduction|floatval !=0}{if !$priceDisplay}{convertPrice price=$product.price_without_reduction-$product.price|floatval}{else}{convertPrice price=$product.price_without_reduction-$product.price_tax_exc|floatval}{/if}{/if}
                        </span>
                        {/if}
                    {/if}
                    {hook h="displayProductPriceBlock" product=$product type="price"}
                    {hook h="displayProductPriceBlock" product=$product type="unit_price"}
                    {hook h="displayProductPriceBlock" product=$product type='after_price'}
                </div>
            {else}
                <!--<div style="height:21px;"></div>-->
            {/if}
            {if $countdown_v_alignment==2 && isset($smarty.capture.pro_count_down)}{$smarty.capture.pro_count_down}{/if}
            {hook h="displayProductPriceBlock" product=$product type="weight"}
            {if isset($display_sd) && $display_sd}<div class="product-desc">{if $display_sd==2}{$product.description_short}{else}{$product.description_short|strip_tags:'UTF-8'|truncate:120:'...'}{/if}</div>{/if}
            {if isset($product['pro_rating_average']) && $product['pro_rating_average']}{$product.pro_rating_average}{/if}   
            {if $st_yotpo_sart && $st_yotpoAppkey && $smarty.capture.st_yotpoDomain && $smarty.capture.st_yotpoLanguage}
                <div class="yotpo bottomLine"
                data-appkey="{$st_yotpoAppkey}"
                data-domain="{$smarty.capture.st_yotpoDomain}"
                data-product-id="{$product.id_product}"
                data-product-models=""
                data-name="{$product.name|escape:'html':'UTF-8'}"
                data-url="{$product.link|escape:'html':'UTF-8'}"
                data-image-url="{$pro_image|escape:'html':'UTF-8'}"
                data-description="{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}"
                data-lang="{$smarty.capture.st_yotpoLanguage|escape:'html':'UTF-8'}"
                data-bread-crumbs="">
                </div>
            {/if}
            {if $st_display_add_to_cart==1 || $st_display_add_to_cart==2 || (!$PS_CATALOG_MODE && $pro_quantity_input && $has_add_to_cart)}
            <div class="act_box {if $st_display_add_to_cart==1} display_when_hover {elseif $st_display_add_to_cart==2} display_normal {elseif !$st_display_add_to_cart && !$PS_CATALOG_MODE && $pro_quantity_input && $has_add_to_cart} display_when_hover {/if}">
                {if !$PS_CATALOG_MODE && $pro_quantity_input && $has_add_to_cart}
                <div class="s_quantity_wanted">
                    <span class="s_quantity_input_wrap clearfix">
                        <a href="#" class="s_product_quantity_down">-</a>
                        <input type="text" min="1" name="qty" class="s_product_quantity_{$product.id_product}" value="{if $product.minimal_quantity > 1}{$product.minimal_quantity}{else}1{/if}" />
                        <a href="#" class="s_product_quantity_up">+</a>
                    </span>
                </div>
                {/if}
                {if $st_display_add_to_cart==1 || $st_display_add_to_cart==2}
                {$smarty.capture.pro_a_cart}
                {/if}
            </div>
            {/if}
            </div>
            </div>
		</li>
	{/foreach}
	</ul>
</div>