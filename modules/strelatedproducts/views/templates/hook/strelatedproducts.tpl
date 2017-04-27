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

<!-- MODULE Related Products -->
{capture name="column_slider"}{if isset($column_slider) && $column_slider}_column{/if}{/capture}
<section id="related-products_block_center{$smarty.capture.column_slider}" class="block products_block {if !isset($column_slider) || !$column_slider} section {/if} {if $hide_mob} hidden-xs {/if}">
	<h4 class="title_block"><span>{l s='Related Products' mod='strelatedproducts'}</span></h4>
    <script type="text/javascript">
    //<![CDATA[
    var related_itemslider_options{$smarty.capture.column_slider};
    //]]>
    </script>
	{if isset($products) AND $products}
    <div id="related-itemslider{$smarty.capture.column_slider}" class="flexslider">
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
        related_itemslider_options{/literal}{$smarty.capture.column_slider}{literal} = {
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
    		controlsContainer: "#related-itemslider{/literal}{$smarty.capture.column_slider}{literal} .nav_top_right",
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
        $('#related-itemslider{/literal}{$smarty.capture.column_slider}{literal} .sliderwrap').flexslider(related_itemslider_options{/literal}{$smarty.capture.column_slider}{literal});

        var related_flexslider_rs{/literal}{$smarty.capture.column_slider}{literal};
        $(window).resize(function(){
            clearTimeout(related_flexslider_rs{/literal}{$smarty.capture.column_slider}{literal});
            var rand_s = parseInt(Math.random()*200 + 300);
            related_flexslider_rs{/literal}{$smarty.capture.column_slider}{literal} = setTimeout(function() {
                {/literal}{if isset($column_slider) && $column_slider}{literal}
                var flexSliderSize = getFlexSliderSize({'lg':1,'md':1,'sm':1,'xs':1,'xxs':1});
                {/literal}{else}{literal}
                var flexSliderSize = getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}});
                {/literal}{/if}{literal}
                var flexslide_object = $('#related-itemslider{/literal}{$smarty.capture.column_slider}{literal} .sliderwrap').data('flexslider');
                if(flexSliderSize && flexslide_object != null )
                    flexslide_object.setVars({'minItems': flexSliderSize, 'maxItems': flexSliderSize});
        	}, rand_s);
        });
    });
    {/literal} 
    //]]>
    </script>
	{else}
		<p class="warning">{l s='No related products' mod='strelatedproducts'}</p>
	{/if}
</section>
<!-- /MODULE Related Products -->