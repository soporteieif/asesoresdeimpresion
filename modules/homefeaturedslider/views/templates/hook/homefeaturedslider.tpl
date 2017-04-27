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

<!-- MODULE Featured Products slider -->
{capture name="column_slider"}{if isset($column_slider) && $column_slider}_column{/if}{/capture}
<div id="featured_products_sldier_block_center_container_{$hook_hash}" class="featured_products_sldier_block_center_container {if $hide_mob} hidden-xs {/if}{if $countdown_on} s_countdown_block {/if} block">
{if isset($homeverybottom) && $homeverybottom}<div class="wide_container"><div class="container">{/if}
<section id="featured_products_sldier_block_center_{$hook_hash}{$smarty.capture.column_slider}" class="featured_products_sldier_block_center{$smarty.capture.column_slider} products_block section {if isset($display_as_grid) && $display_as_grid} display_as_grid {/if}">
	<h4 class="title_block mar_b1"><span>{l s='Featured Products' mod='homefeaturedslider'}</span></h4>
    <script type="text/javascript">
    //<![CDATA[
    var featured_itemslider_options{$hook_hash}{$smarty.capture.column_slider};
    //]]>
    </script>
	{if isset($products) AND $products}
    {if !isset($display_as_grid) || !$display_as_grid || (isset($display_as_grid) && $display_as_grid && isset($column_slider) && $column_slider)}
    <div id="featured-itemslider-{$hook_hash}{$smarty.capture.column_slider}" class="featured-itemslider{$smarty.capture.column_slider} flexslider">
    	{if isset($column_slider) && $column_slider}
    	{include file="$tpl_dir./product-slider-list.tpl"}
        {else}
    	{include file="$tpl_dir./product-slider.tpl"}
        {/if}
	</div>
    
    <script type="text/javascript">
    //<![CDATA[
    {literal}
    jQuery(function($) {        
        featured_itemslider_options{/literal}{$hook_hash}{$smarty.capture.column_slider}{literal} = {
    		easing: "{/literal}{$slider_easing}{literal}",
            useCSS: false,
    		slideshow: {/literal}{$slider_slideshow}{literal},
            slideshowSpeed: {/literal}{$slider_s_speed}{literal},
    		animationSpeed: {/literal}{$slider_a_speed}{literal},
    		pauseOnHover: {/literal}{$slider_pause_on_hover}{literal},
            direction: "horizontal",
            animation: "slide",
            animationLoop: {/literal}{$slider_loop}{literal},
    		controlNav: false,
    		controlsContainer: "#featured-itemslider-{/literal}{$hook_hash}{$smarty.capture.column_slider} {if isset($direction_nav) && $direction_nav}.nav_left_right{else}.nav_top_right{/if}{literal}",
    		itemWidth: 260,
            {/literal}{if isset($column_slider) && $column_slider}{literal}
            minItems: getFlexSliderSize({'lg':1,'md':1,'sm':1,'xs':1,'xxs':1}),
            maxItems: getFlexSliderSize({'lg':1,'md':1,'sm':1,'xs':1,'xxs':1}),
            {/literal}{else}{literal}
            minItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
            maxItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
            {/literal}{/if}{literal}
    		move: {/literal}{$slider_move}{literal},
            prevText: '<i class="icon-left-open-3"></i>',
            nextText: '<i class="icon-right-open-3"></i>',
            productSlider:true,
            allowOneSlide:false
        };
        $('#featured-itemslider-{/literal}{$hook_hash}{$smarty.capture.column_slider}{literal} .sliderwrap').flexslider(featured_itemslider_options{/literal}{$hook_hash}{$smarty.capture.column_slider}{literal});
                
        var featured_flexslider_rs{/literal}{$hook_hash}{$smarty.capture.column_slider}{literal};
        $(window).resize(function(){
            clearTimeout(featured_flexslider_rs{/literal}{$hook_hash}{$smarty.capture.column_slider}{literal});
            var rand_s = parseInt(Math.random()*200 + 300);
            featured_flexslider_rs{/literal}{$hook_hash}{$smarty.capture.column_slider}{literal} = setTimeout(function() {
                {/literal}{if isset($column_slider) && $column_slider}{literal}
                var flexSliderSize = getFlexSliderSize({'lg':1,'md':1,'sm':1,'xs':1,'xxs':1});
                {/literal}{else}{literal}
                var flexSliderSize = getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}});
                {/literal}{/if}{literal}
                var flexslide_object = $('#featured-itemslider-{/literal}{$hook_hash}{$smarty.capture.column_slider}{literal} .sliderwrap').data('flexslider');
                if(flexSliderSize && flexslide_object != null )
                    flexslide_object.setVars({'minItems': flexSliderSize, 'maxItems': flexSliderSize});
        	}, rand_s);
        });        
    });
    {/literal} 
    //]]>
    </script>
    {elseif $display_as_grid==2}
        {include file="$tpl_dir./product-list-simple.tpl" products=$products for_f='featured' id='homefeatured_grid'}
    {else}
        {include file="$tpl_dir./product-list.tpl" products=$products class='homefeatured_grid' for_f='featured' id='homefeatured_grid'}
    {/if}
	{else}
		<p class="warning">{l s='No featrued products' mod='homefeaturedslider'}</p>
	{/if}
</section>
{if isset($homeverybottom) && $homeverybottom}</div></div>{/if}
</div>
{if !$smarty.capture.column_slider && $has_background_img && $speed}
<script type="text/javascript">
//<![CDATA[
{literal}
jQuery(function($) {
    $('#featured_products_sldier_block_center_container_{/literal}{$hook_hash}{literal}').parallax("50%", {/literal}{$speed|floatval}{literal});
});
{/literal} 
//]]>
</script>
{/if}
<!-- /MODULE Featured Products slider -->