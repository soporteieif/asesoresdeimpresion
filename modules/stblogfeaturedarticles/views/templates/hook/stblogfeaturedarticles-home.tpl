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
<!-- St Blog featured articles -->
{if $aw_display || (is_array($blogs) && $blogs|count)}
<div id="st_blog_featured_article_container_{$hook_hash}" class="st_blog_featured_article_container block {if $hide_mob} hidden-xs {/if}">
{if isset($isHomeVeryBottom) && $isHomeVeryBottom}<div class="wide_container"><div class="container">{/if}
<section id="st_blog_featured_article{$hook_hash}" class="st_blog_featured_article section">
	<h3 class="title_block"><span>{l s='Featured articles ' mod='stblogfeaturedarticles'}</span></h3>
    <script type="text/javascript">
    //<![CDATA[
    var featured_article_itemslider_options{$hook_hash};
    //]]>
    </script>
    {if is_array($blogs) && $blogs|count}
    {assign var='length_of_article_name' value=Configuration::get('ST_LENGTH_OF_ARTICLE_NAME')}
    {if !isset($display_as_grid) || !$display_as_grid}
        <div id="featured_article_itemslider-{$hook_hash}" class="featured_article_itemslider flexslider">
            <div class="{if !isset($isbloghomepage) && isset($direction_nav) && $direction_nav}nav_left_right{else}nav_top_right{/if}"></div>
            <div class="sliderwrap products_slider">
                <ul class="slides">
                {foreach $blogs as $blog}
                    <li class="block_blog {if $blog@first}first_item{elseif $blog@last}last_item{else}item{/if}">
                        <div class="blog_image">
                            <a href="{$blog.link|escape:'html'}" title="{$blog.name|escape:'htmlall':'UTF-8'}">
                            <img src="{$blog.cover.links.medium}" alt="{$blog.name|escape:'htmlall':'UTF-8'}" width="{$imageSize[1]['medium'][0]}" height="{$imageSize[1]['medium'][1]}" class="hover_effect" />
                            {if $blog.type==2}
                                <span class="icon_wrap"><i class="icon-camera-2 icon-1x"></i></span>
                            {elseif $blog.type==3}
                                <span class="icon_wrap"><i class="icon-video icon-1x"></i></span>
                            {/if}
                            </a>
                        </div>
                        <p class="s_title_block{if $length_of_article_name} nohidden{/if}"><a href="{$blog.link|escape:'html'}" title="{$blog.name|escape:'htmlall':'UTF-8'}">{if $length_of_article_name == 1}{$blog.name|escape:'html':'UTF-8'}{else}{$blog.name|escape:'html':'UTF-8'|truncate:70:'...'}{/if}</a></p>

                        {if $blog.content_short}<p class="blok_blog_short_content">{$blog.content_short|strip_tags:'UTF-8'|truncate:120:'...'}<a href="{$blog.link|escape:'html'}" title="{l s='Read More' mod='stblogfeaturedarticles'}" class="go">{l s='Read More' mod='stblogfeaturedarticles'}</a></p>{/if}
                        <div class="blog_info">
                            <span class="date-add">{dateFormat date=$blog.date_add full=0}</span>
                            {hook h='displayAnywhere' function="getCommentNumber" id_blog=$blog.id_st_blog link_rewrite=$blog.link_rewrite mod='stblogcomments' caller='stblogcomments'}
                            {if $display_viewcount}<span><i class="icon-eye-2 icon-mar-lr2"></i>{$blog.counter}</span>{/if}
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
            featured_article_itemslider_options{/literal}{$hook_hash}{literal} = {
                easing: "{/literal}{$slider_easing}{literal}",
                useCSS: false,
                slideshow: {/literal}{if $slider_slideshow===false}0{else}{$slider_slideshow}{/if}{literal},
                slideshowSpeed: {/literal}{if $slider_s_speed===false}7000{else}{$slider_s_speed}{/if}{literal},
                animationSpeed: {/literal}{if $slider_a_speed===false}400{else}{$slider_a_speed}{/if}{literal},
                pauseOnHover: {/literal}{if $slider_pause_on_hover===false}1{else}{$slider_pause_on_hover}{/if}{literal},
                direction: "horizontal",
                animation: "slide",
                animationLoop: {/literal}{if $slider_loop===false}1{else}{$slider_loop}{/if}{literal},
                controlNav: false,
                controlsContainer: "#featured_article_itemslider-{/literal}{$hook_hash} {if !isset($isbloghomepage) && isset($direction_nav) && $direction_nav}.nav_left_right{else}.nav_top_right{/if}{literal}",
                itemWidth: 260,
                {/literal}{if isset($column_slider) && $column_slider}{literal}
                minItems: getFlexSliderSize({'lg':1,'md':1,'sm':1,'xs':1,'xxs':1}),
                maxItems: getFlexSliderSize({'lg':1,'md':1,'sm':1,'xs':1,'xxs':1}),
                {/literal}{else}{literal}
                minItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
                maxItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
                {/literal}{/if}{literal}
                move: {/literal}{if $slider_move===false}0{else}{$slider_move}{/if}{literal},
                prevText: '<i class="icon-left-open-3"></i>',
                nextText: '<i class="icon-right-open-3"></i>',
                productSlider:true,
                allowOneSlide:false
            };
            $('#featured_article_itemslider-{/literal}{$hook_hash}{literal} .sliderwrap').flexslider(featured_article_itemslider_options{/literal}{$hook_hash}{literal});
            
            var featured_article_itemslider_rs{/literal}{$hook_hash}{literal};
            $(window).resize(function(){
                clearTimeout(featured_article_itemslider_rs{/literal}{$hook_hash}{literal});
                var rand_s = parseInt(Math.random()*200 + 300);
                featured_article_itemslider_rs{/literal}{$hook_hash}{literal} = setTimeout(function() {
                    {/literal}{if isset($column_slider) && $column_slider}{literal}
                    var flexSliderSize = getFlexSliderSize({'lg':1,'md':1,'sm':1,'xs':1,'xxs':1});
                    {/literal}{else}{literal}
                    var flexSliderSize = getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}});
                    {/literal}{/if}{literal}
                    var flexslide_object = $('#featured_article_itemslider-{/literal}{$hook_hash}{literal} .sliderwrap').data('flexslider');
                    if(flexSliderSize && flexslide_object != null )
                        flexslide_object.setVars({'minItems': flexSliderSize, 'maxItems': flexSliderSize});
                }, rand_s);
            });
        });
        {/literal} 
        //]]>
        </script>
    {elseif $display_as_grid==1 || $display_as_grid==3}
        <ul class="row{if $display_as_grid==1} blog_row_list{else} blog_list_grid blog_list{/if}">
        {foreach $blogs as $blog}
            <li class="block_blog col-lg-{(12/$pro_per_lg)|replace:'.':'-'} col-md-{(12/$pro_per_md)|replace:'.':'-'} col-sm-{(12/$pro_per_sm)|replace:'.':'-'} col-xs-{(12/$pro_per_xs)|replace:'.':'-'} col-xxs-{(12/$pro_per_xxs)|replace:'.':'-'}  {if $blog@iteration%$pro_per_lg == 1} first-item-of-desktop-line{/if}{if $blog@iteration%$pro_per_md == 1} first-item-of-line{/if}{if $blog@iteration%$pro_per_sm == 1} first-item-of-tablet-line{/if}{if $blog@iteration%$pro_per_xs == 1} first-item-of-mobile-line{/if}{if $blog@iteration%$pro_per_xxs == 1} first-item-of-portrait-line{/if} clearfix">
                <div class="blog_image">
                    <a href="{$blog.link|escape:'html'}" title="{$blog.name|escape:'htmlall':'UTF-8'}">
                    <img src="{$blog.cover.links.small}" alt="{$blog.name|escape:'htmlall':'UTF-8'}" width="{$imageSize[1]['small'][0]}" height="{$imageSize[1]['small'][1]}" class="hover_effect" />
                    {if $blog.type==2}
                        <span class="icon_wrap"><i class="icon-camera-2 icon-1x"></i></span>
                    {elseif $blog.type==3}
                        <span class="icon_wrap"><i class="icon-video icon-1x"></i></span>
                    {/if}                 
                    </a>
                </div>
                <p class="s_title_block{if $length_of_article_name} nohidden{/if}"><a href="{$blog.link|escape:'html'}" title="{$blog.name|escape:'htmlall':'UTF-8'}">{if $length_of_article_name == 1}{$blog.name|escape:'html':'UTF-8'}{else}{$blog.name|escape:'html':'UTF-8'|truncate:70:'...'}{/if}</a></p>
                {if $blog.content_short}<p class="blok_blog_short_content">{$blog.content_short|strip_tags:'UTF-8'|truncate:120:'...'}<a href="{$blog.link|escape:'html'}" title="{l s='Read More' mod='stblogfeaturedarticles'}" class="go">{l s='Read More' mod='stblogfeaturedarticles'}</a></p>{/if}
                <div class="blog_info">
                    <span class="date-add">{dateFormat date=$blog.date_add full=0}</span>
                    {hook h='displayAnywhere' function="getCommentNumber" id_blog=$blog.id_st_blog link_rewrite=$blog.link_rewrite mod='stblogcomments' caller='stblogcomments'}
                    {if $display_viewcount}<span><i class="icon-eye-2 icon-mar-lr2"></i>{$blog.counter}</span>{/if}
                </div>
            </li>
        {/foreach}
        </ul>
    {else}
        <ul class="blog_list_large">
        {foreach $blogs as $blog}
            <li class="block_blog {if $blog@first}first_item{elseif $blog@last}last_item{/if}">
                <div class="blog_image">
                    <a href="{$blog.link|escape:'html'}" title="{$blog.name|escape:'htmlall':'UTF-8'}">
                    <img src="{$blog.cover.links.large}" alt="{$blog.name|escape:'htmlall':'UTF-8'}" width="{$imageSize[1]['large'][0]}" height="{$imageSize[1]['large'][1]}" class="hover_effect" />
                    {if $blog.type==2}
                        <span class="icon_wrap"><i class="icon-camera-2 icon-1x"></i></span>
                    {elseif $blog.type==3}
                        <span class="icon_wrap"><i class="icon-video icon-1x"></i></span>
                    {/if}                 
                    </a>
                </div>
                <p class="s_title_block{if $length_of_article_name} nohidden{/if}"><a href="{$blog.link|escape:'html'}" title="{$blog.name|escape:'htmlall':'UTF-8'}">{if $length_of_article_name == 1}{$blog.name|escape:'html':'UTF-8'}{else}{$blog.name|escape:'html':'UTF-8'|truncate:70:'...'}{/if}</a></p>
                {if $blog.content_short}<p class="blok_blog_short_content">{$blog.content_short}<a href="{$blog.link|escape:'html'}" title="{l s='Read More' mod='stblogfeaturedarticles'}" class="go">{l s='Read More' mod='stblogfeaturedarticles'}</a></p>{/if}
                <div class="blog_info">
                    <span class="date-add">{dateFormat date=$blog.date_add full=0}</span>
                    {hook h='displayAnywhere' function="getCommentNumber" id_blog=$blog.id_st_blog link_rewrite=$blog.link_rewrite mod='stblogcomments' caller='stblogcomments'}
                    {if $display_viewcount}<span><i class="icon-eye-2 icon-mar-lr2"></i>{$blog.counter}</span>{/if}
                </div>
            </li>
        {/foreach}
        </ul>
    {/if}
    {else}
        <p class="warning">{l s='No featured articles' mod='stblogfeaturedarticles'}</p>
    {/if}
</section>
{if isset($isHomeVeryBottom) && $isHomeVeryBottom}</div></div>{/if}
</div>
{if isset($has_background_img) && $has_background_img && isset($speed) && $speed}
<script type="text/javascript">
//<![CDATA[
{literal}
jQuery(function($) {
    $('#st_blog_featured_article_container_{/literal}{$hook_hash}{literal}').parallax("50%", {/literal}{$speed|floatval}{literal});
});
{/literal} 
//]]>
</script>
{/if}
{/if}
<!-- /St Blog featured articles  -->