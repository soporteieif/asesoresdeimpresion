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

<!-- MODULE Product categories slider -->
{capture name="column_slider"}{if isset($column_slider) && $column_slider}_column{/if}{/capture}
{if isset($product_categories) && count($product_categories)}
    {foreach $product_categories as $p_c}
        {if isset($homeverybottom) && $homeverybottom}<div id="product_categories_slider_container_{$p_c.id_category}" class="wide_container {if $hide_mob} hidden-xs {/if} block"><div class="container">{/if}
        <section id="product_categories_slider_{$p_c.id_category}" class="product_categories_slider_block{$smarty.capture.column_slider}{if !isset($homeverybottom) || !$homeverybottom} block{/if} products_block section {if $hide_mob} hidden-xs {/if} {if $countdown_on} s_countdown_block{/if} {if isset($display_as_grid) && $display_as_grid} display_as_grid {/if}">
            <h4 class="title_block mar_b1">
                <a href="{$link->getCategoryLink($p_c.id_category, $p_c.link_rewrite)|escape:'html'}" title="{$p_c.name|escape:'html':'UTF-8'}">{$p_c.name|escape:'html':'UTF-8'}</a>
            </h4>            
	        {if isset($p_c.products) AND $p_c.products}
            {if !isset($display_as_grid) || !$display_as_grid || (isset($display_as_grid) && $display_as_grid && isset($column_slider) && $column_slider)}
            <div id="product_categories-itemslider-{$hook_hash}{$smarty.capture.column_slider}_{$p_c.id_category}" class="flexslider product_categories-itemslider{$smarty.capture.column_slider}">
                {if isset($column_slider) && $column_slider}
            	{include file="$tpl_dir./product-slider-list.tpl" products=$p_c.products }
                {else}
            	{include file="$tpl_dir./product-slider.tpl" products=$p_c.products direction_nav=$p_c.direction_nav}
                {/if}
        	</div>
            <script type="text/javascript">
            //<![CDATA[
            {literal}
            jQuery(function($) {

                {/literal}{if !$smarty.capture.column_slider && isset($p_c.speed) && $p_c.speed && ( (isset($p_c.bg_img) && $p_c.bg_img) || (isset($p_c.bg_pattern) && $p_c.bg_pattern) )}{literal}
                $('#product_categories_slider_{/literal}{if isset($homeverybottom) && $homeverybottom}container_{/if}{$p_c.id_category}{literal}').parallax("50%", {/literal}{$p_c.speed|floatval}{literal});
                {/literal}{/if}{literal}

                $('#product_categories-itemslider-{/literal}{$hook_hash}{$smarty.capture.column_slider}{literal}_{/literal}{$p_c.id_category}{literal} .sliderwrap').flexslider({
            		easing: "{/literal}{$pro_cate_easing}{literal}",
                    useCSS: false,
            		slideshow: {/literal}{$pro_cate_slideshow}{literal},
                    slideshowSpeed: {/literal}{$pro_cate_s_speed}{literal},
            		animationSpeed: {/literal}{$pro_cate_a_speed}{literal},
            		pauseOnHover: {/literal}{$pro_cate_pause_on_hover}{literal},
                    direction: "horizontal",
                    animation: "slide",
            		animationLoop: {/literal}{$pro_cate_loop}{literal},
            		controlNav: false,
            		controlsContainer: "#product_categories-itemslider-{/literal}{$hook_hash}{$smarty.capture.column_slider}_{$p_c.id_category} {if $p_c.direction_nav}.nav_left_right{else}.nav_top_right{/if}{literal}",
    		        itemWidth: 280,
                    {/literal}{if isset($column_slider) && $column_slider}{literal}
                    minItems: getFlexSliderSize({'lg':1,'md':1,'sm':1,'xs':1,'xxs':1}),
                    maxItems: getFlexSliderSize({'lg':1,'md':1,'sm':1,'xs':1,'xxs':1}),
                    {/literal}{else}{literal}
                    minItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
                    maxItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
                    {/literal}{/if}{literal}
    		        move: {/literal}{$pro_cate_move}{literal},
                    prevText: '<i class="icon-left-open-3"></i>',
                    nextText: '<i class="icon-right-open-3"></i>',
                    productSlider:true,
                    allowOneSlide:false
                });

                var product_categories_{/literal}{$p_c.id_category}{literal}_flexslider_rs{/literal}{$hook_hash}{$smarty.capture.column_slider}{literal};
                $(window).resize(function(){
                    clearTimeout(product_categories_{/literal}{$p_c.id_category}{literal}_flexslider_rs{/literal}{$hook_hash}{$smarty.capture.column_slider}{literal});
                    var rand_s = parseInt(Math.random()*200 + 300);
                    product_categories_{/literal}{$p_c.id_category}{literal}_flexslider_rs{/literal}{$hook_hash}{$smarty.capture.column_slider}{literal} = setTimeout(function() {
                        {/literal}{if isset($column_slider) && $column_slider}{literal}
                        var flexSliderSize = getFlexSliderSize({'lg':1,'md':1,'sm':1,'xs':1,'xxs':1});
                        {/literal}{else}{literal}
                        var flexSliderSize = getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}});
                        {/literal}{/if}{literal}
                        var flexslide_object = $('#product_categories-itemslider-{/literal}{$hook_hash}{$smarty.capture.column_slider}{literal}_{/literal}{$p_c.id_category}{literal} .sliderwrap').data('flexslider');
                        if(flexSliderSize && flexslide_object != null )
                            flexslide_object.setVars({'minItems': flexSliderSize, 'maxItems': flexSliderSize});
                	}, rand_s);
                });
            });
            {/literal} 
            //]]>
            </script>
            {elseif $display_as_grid==2}
                {include file="$tpl_dir./product-list-simple.tpl" products=$p_c.products for_f='pro_cate' id='stproductcategoriesslider_grid'}
            {else}
                {include file="$tpl_dir./product-list.tpl" products=$p_c.products class='stproductcategoriesslider_grid' for_f='pro_cate' id='stproductcategoriesslider_grid'}
            {/if}
        	{else}
        		<p class="warning">{l s='No products' mod='stproductcategoriesslider'}</p>
        	{/if}
        </section>
        {if isset($homeverybottom) && $homeverybottom}</div></div>{/if}
    {/foreach}
{/if}
<!-- /MODULE Product categories slider -->