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
<!-- Featured categories -->
{if $aw_display || (isset($fc_slider) && $fc_slider)}
<div id="fc_slider_block_container_{$hook_hash}" class="fc_slider_block_container block {if $hide_mob} hidden-xs {/if}">
{if isset($homeverybottom) && $homeverybottom}<div class="wide_container"><div class="container">{/if}
<section id="fc_slider_block_{$hook_hash}" class="fc_slider_block block products_block section {if $hide_mob} hidden-xs {/if} {if isset($display_as_grid) && $display_as_grid} display_as_grid {/if}">
    <h3 class="title_block"><span>{l s='Featured categories' mod='stfeaturedcategoriesslider'}</span></h3>
    <script type="text/javascript">
    //<![CDATA[
    var fc_slider_itemslider_options{$hook_hash};
    //]]>
    </script>
    {if isset($fc_slider) && is_array($fc_slider) && count($fc_slider)}
        {if !isset($display_as_grid) || !$display_as_grid}
        <div id="fc_itemslider-{$hook_hash}" class="fc_itemslider flexslider">
            <div class="{if isset($direction_nav) && $direction_nav}nav_left_right{else}nav_top_right{/if}"></div>
            <div class="sliderwrap products_slider">
                <ul class="slides">
                {foreach $fc_slider as $category}
                    <li class="ajax_block_product {if $category@first}first_item{elseif $category@last}last_item{else}item{/if}">
                        <div class="pro_outer_box">
                            <div class="pro_first_box">
                                <a href="{$link->getCategoryLink($category.id_category, $category.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$category.name|escape:'htmlall':'UTF-8'}">
                                {if $category.id_image}
                                    <img src="{$link->getCatImageLink($category.link_rewrite, $category.id_image, 'home_default')|escape:'html'}" alt="{$category.name|escape:'htmlall':'UTF-8'}" width="{$homeSize.width}" height="{$homeSize.height}" class="replace-2x img-responsive" />
                                {else}
                                    <img src="{$img_cat_dir}{$lang_iso}-default-home_default.jpg" alt="" width="{$homeSize.width}" height="{$homeSize.height}" class="replace-2x img-responsive" />
                                {/if}
                                </a>
                            </div>
                            <div class="pro_second_box">
                                <p class="s_title_block"><a href="{$link->getCategoryLink($category.id_category, $category.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$category.name|escape:'htmlall':'UTF-8'}">{$category.name|truncate:35:'...'|escape:'htmlall':'UTF-8'}</a></p>
                            </div>
                        </div>
                    </li>
                {/foreach}
                </ul>
            </div>
        </div>
        <script type="text/javascript">
        //<![CDATA[
        {literal}
        jQuery(function($) {
            fc_itemslider_options{/literal}{$hook_hash}{literal} = {
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
                controlsContainer: "#fc_itemslider-{/literal}{$hook_hash} {if isset($direction_nav) && $direction_nav}.nav_left_right{else}.nav_top_right{/if}{literal}",
                itemWidth: 260,
                minItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
                maxItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
                move: {/literal}{$slider_move}{literal},
                prevText: '<i class="icon-left-open-3"></i>',
                nextText: '<i class="icon-right-open-3"></i>',
                productSlider:true,
                allowOneSlide:false
            };
            $('#fc_itemslider-{/literal}{$hook_hash}{literal} .sliderwrap').flexslider(fc_itemslider_options{/literal}{$hook_hash}{literal});
            
            var fc_itemslider_rs{/literal}{$hook_hash}{literal};
            $(window).resize(function(){
                clearTimeout(fc_itemslider_rs{/literal}{$hook_hash}{literal});
                var rand_s = parseInt(Math.random()*200 + 300);
                fc_itemslider_rs{/literal}{$hook_hash}{literal} = setTimeout(function() {
                    var flexSliderSize = getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}});
                    var flexslide_object = $('#fc_itemslider-{/literal}{$hook_hash}{literal} .sliderwrap').data('flexslider');
                    if(flexSliderSize && flexslide_object != null )
                        flexslide_object.setVars({'minItems': flexSliderSize, 'maxItems': flexSliderSize});
                }, rand_s);
            });
        });
        {/literal} 
        //]]>
        </script>
        {else}
            <ul class="fc_slider_list row">
            {foreach $fc_slider as $category}
                <li class="col-lg-{(12/$pro_per_lg)|replace:'.':'-'} col-md-{(12/$pro_per_md)|replace:'.':'-'} col-sm-{(12/$pro_per_sm)|replace:'.':'-'} col-xs-{(12/$pro_per_xs)|replace:'.':'-'} col-xxs-{(12/$pro_per_xxs)|replace:'.':'-'}  {if $category@iteration%$pro_per_lg == 1} first-item-of-desktop-line{/if}{if $category@iteration%$pro_per_md == 1} first-item-of-line{/if}{if $category@iteration%$pro_per_sm == 1} first-item-of-tablet-line{/if}{if $category@iteration%$pro_per_xs == 1} first-item-of-mobile-line{/if}{if $category@iteration%$pro_per_xxs == 1} first-item-of-portrait-line{/if}">
                    {if $display_as_grid==2}
                    <a href="{$link->getCategoryLink($category.id_category, $category.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$category.name|escape:'htmlall':'UTF-8'}" class="fc_slider_image">
                    {if $category.id_image}
                        <img src="{$link->getCatImageLink($category.link_rewrite, $category.id_image, 'medium_default')|escape:'html'}" alt="{$category.name|escape:'htmlall':'UTF-8'}" width="{$mediumSize.width}" height="{$mediumSize.height}" class="replace-2x img-responsive" />
                    {else}
                        <img src="{$img_cat_dir}{$lang_iso}-default-medium_default.jpg" alt="" width="{$mediumSize.width}" height="{$mediumSize.height}" class="replace-2x img-responsive" />
                    {/if}
                    </a>
                    {/if}
                    <div class="fc_slider_name">
                        <p class="s_title_block"><a href="{$link->getCategoryLink($category.id_category, $category.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$category.name|escape:'htmlall':'UTF-8'}">{$category.name|truncate:35:'...'|escape:'htmlall':'UTF-8'}</a></p>
                    </div>
                </li>
            {/foreach}
            </ul>
        {/if}
    {else}
        <p class="warning">{l s='No featured categories' mod='stfeaturedcategoriesslider'}</p>
    {/if}
</section>
{if isset($homeverybottom) && $homeverybottom}</div></div>{/if}
</div>
{if $has_background_img && $speed}
<script type="text/javascript">
//<![CDATA[
{literal}
jQuery(function($) {
    $('#fc_slider_block_container_{/literal}{$hook_hash}{literal}').parallax("50%", {/literal}{$speed|floatval}{literal});
});
{/literal} 
//]]>
</script>
{/if}
{/if}
<!--/ Featured categories -->