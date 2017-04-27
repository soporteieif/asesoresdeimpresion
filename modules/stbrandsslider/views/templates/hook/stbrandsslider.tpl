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
<!-- Block brands slider module -->
{if isset($brands) && count($brands)}
<div id="brands_slider_container_{$hook_hash}" class="brands_slider_container block">
{if isset($homeverybottom) && $homeverybottom}<div class="wide_container"><div class="container">{/if}
<section id="brands_slider_{$hook_hash}" class="brands_slider section {if isset($brandsslider_footer)} col-xs-12 {/if}">
    <h4 class="title_block"><a href="{$link->getPageLink('manufacturer')|escape:'html'}" title="{l s='Product Brands' mod='stbrandsslider'}">{l s='Product Brands' mod='stbrandsslider'}</a></h4>
    <div id="brands-itemslider-{$hook_hash}" class="brands-itemslider flexslider">
    	<div class="{if isset($direction_nav) && $direction_nav}nav_left_right{else}nav_top_right{/if}"></div>
        <div class="sliderwrap">
            <ul class="slides">
            	{foreach $brands as $brand}
                <li>
            	<a href="{$link->getmanufacturerLink($brand.id_manufacturer, $brand.link_rewrite)}" title="{$brand.name|escape:html:'UTF-8'}">
                    <img src="{$img_manu_dir}{$brand.id_manufacturer|escape:'htmlall':'UTF-8'}-manufacturer_default.jpg" alt="{$brand.name|escape:html:'UTF-8'}" width="{$manufacturerSize.width}" height="{$manufacturerSize.height}" class="replace-2x img-responsive" />
                </a>
                {assign var='show_brand_desc' value=Configuration::get('BRANDS_SLIDER_SHORT_DESC')}
                {assign var='show_brand_name' value=Configuration::get('BRANDS_SLIDER_NAME')}
                {if $show_brand_name || $show_brand_desc}
                    <div class="pro_second_box">
                    {if $show_brand_name}
                    <p class="s_title_block "><a href="{$link->getmanufacturerLink($brand.id_manufacturer, $brand.link_rewrite)}" title="{$brand.name|escape:html:'UTF-8'}">{$brand.name|escape:html:'UTF-8'}</a></p>
                    {/if}
                    {if $show_brand_desc == 1}
                    <div class="product-desc">{$brand.short_description|strip_tags:'UTF-8'|truncate:100:'...'}</div>
                    {elseif $show_brand_desc == 2}
                    <div class="product-desc">{$brand.short_description}</div>
                    {/if}
                    </div>
                {/if}
                </li>
                {/foreach}
            </ul>
        </div>
    </div>
</section>

<script type="text/javascript">
//<![CDATA[
{literal}
jQuery(function($) {
    $('#brands-itemslider-{/literal}{$hook_hash}{literal} .sliderwrap').flexslider({
		easing: "{/literal}{$brand_slider_easing}{literal}",
        useCSS: false,
		slideshow: {/literal}{$brand_slider_slideshow}{literal},
        slideshowSpeed: {/literal}{$brand_slider_s_speed}{literal},
		animationSpeed: {/literal}{$brand_slider_a_speed}{literal},
		pauseOnHover: {/literal}{$brand_slider_pause_on_hover}{literal},
        direction: "horizontal",
        animation: "slide",
		animationLoop: {/literal}{$brand_slider_loop}{literal},
		controlNav: false,
		controlsContainer: "#brands-itemslider-{/literal}{$hook_hash} {if isset($direction_nav) && $direction_nav}.nav_left_right{else}.nav_top_right{/if}{literal}",
		itemWidth: 164,
        minItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
        maxItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
    	move: {/literal}{$brand_slider_move}{literal},
        prevText: '<i class="icon-left-open-3"></i>',
        nextText: '<i class="icon-right-open-3"></i>',
        productSlider:true,
        allowOneSlide:false
    });
    var brands_flexslider_rs{/literal}{$hook_hash}{literal};
    $(window).resize(function(){
        clearTimeout(brands_flexslider_rs{/literal}{$hook_hash}{literal});
        var rand_s = parseInt(Math.random()*200 + 300);
        brands_flexslider_rs{/literal}{$hook_hash}{literal} = setTimeout(function() {
            var flexSliderSize = getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}});
            var flexslide_object = $('#brands-itemslider-{/literal}{$hook_hash}{literal} .sliderwrap').data('flexslider');
            if(flexSliderSize && flexslide_object != null )
                flexslide_object.setVars({'minItems': flexSliderSize, 'maxItems': flexSliderSize});
    	}, rand_s);
    });
});
{/literal} 
//]]>
</script>
{if isset($homeverybottom) && $homeverybottom}</div></div>{/if}
</div>
{if $has_background_img && $speed}
<script type="text/javascript">
//<![CDATA[
{literal}
jQuery(function($) {
     $('#brands_slider_container_{/literal}{$hook_hash}{literal}').parallax("50%", {/literal}{$speed|floatval}{literal});
});
{/literal} 
//]]>
</script>
{/if}
{/if}
<!-- /Block brands slider module -->