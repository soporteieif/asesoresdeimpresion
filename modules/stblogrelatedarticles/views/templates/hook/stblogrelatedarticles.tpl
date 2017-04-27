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
<!-- St Blog recent articles -->
{if is_array($blogs) && $blogs|count}
{capture name="column_slider"}{if isset($column_slider) && $column_slider}_column{/if}{/capture}
<section id="st_blog_related_article{$smarty.capture.column_slider}" class="{if isset($column_slider) && $column_slider} column_block {/if} block section">
    <h3 class="title_block"><span>{l s='Related articles ' mod='stblogrelatedarticles'}</span></h3>
    <div id="related_article_slider{$smarty.capture.column_slider}" class="flexslider">
    {if isset($column_slider) && $column_slider}
        <div class="nav_top_right"></div>
        <div class="sliderwrap products_slider">
            <ul class="slides">
            {foreach $blogs as $blog}
            {if $blog@first || $blog@index is div by $items}
            <li class="{if $blog@first}first_item{elseif $blog@last}last_item{else}item{/if}">
            {/if}
                <div class="pro_column_box clearfix">
                    <a href="{$link->getModuleLink('stblog', 'article',['id_blog'=>$blog['id_st_blog'],'rewrite'=>$blog['link_rewrite']])|escape:'html'}" title="{$blog.name|escape:'htmlall':'UTF-8'}" class="pro_column_left">
                        <img src="{$blog.cover.links.thumb}" alt="{$blog.name|escape:'htmlall':'UTF-8'}" width="{$imageSize[1]['thumb'][0]}" height="{$imageSize[1]['thumb'][1]}" />
                    </a>
                    <div class="pro_column_right">
                        <p class="s_title_block nohidden"><a href="{$link->getModuleLink('stblog', 'article',['id_blog'=>$blog['id_st_blog'],'rewrite'=>$blog['link_rewrite']])|escape:'html'}" title="{$blog.name|escape:'htmlall':'UTF-8'}">{$blog.name|truncate:50:'...'|escape:html:'UTF-8'}</a></p><span class="date-add">{dateFormat date=$blog.date_add full=0}</span>
                    </div>
                </div>
            {if $blog@last || $blog@iteration is div by $items}
            </li>
            {/if}
            {/foreach}
            </ul>
        </div>
    {else}
        <div class="nav_top_right"></div>
        <div class="sliderwrap products_slider">
            <ul class="slides">                
            {foreach $blogs as $blog}
                <li class="block_blog {if $blog@first}first_item{elseif $blog@last}last_item{/if} ">
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
                    <p class="s_title_block"><a href="{$blog.link|escape:'html'}" title="{$blog.name|escape:'htmlall':'UTF-8'}">{$blog.name|escape:'htmlall':'UTF-8'|truncate:70:'...'}</a></p>
                    {if $blog.content_short}<p class="blok_blog_short_content">{$blog.content_short|strip_tags:'UTF-8'|truncate:120:'...'}</p>{/if}
                    <div class="blog_read_more">
                        <a href="{$blog.link|escape:'html'}" title="{$blog.name|escape:'htmlall':'UTF-8'}" class="btn btn-default">{l s='Read more' mod='stblogrelatedarticles'}</a>
                    </div>
                </li>
            {/foreach}
            </ul>
        </div>
    {/if}
    </div>

    <script type="text/javascript">
    //<![CDATA[
    {literal}
    jQuery(function($) { 
        $('#related_article_slider{/literal}{$smarty.capture.column_slider}{literal} .sliderwrap').flexslider({
            easing: "{/literal}{$easing}{literal}",
            useCSS: false,
            slideshow: {/literal}{$slideshow}{literal},
            slideshowSpeed: {/literal}{$s_speed}{literal},
            animationSpeed: {/literal}{$a_speed}{literal},
            pauseOnHover: {/literal}{$pause_on_hover}{literal},
            direction: "horizontal",
            animation: "slide",
            animationLoop: {/literal}{$loop}{literal},
            controlNav: false,
            controlsContainer: "#related_article_slider{/literal}{$smarty.capture.column_slider}{literal} .nav_top_right",
            itemWidth: 280,
            {/literal}{if isset($column_slider) && $column_slider}{literal}
            minItems: getFlexSliderSize({'lg':1,'md':1,'sm':1,'xs':1,'xxs':1}),
            maxItems: getFlexSliderSize({'lg':1,'md':1,'sm':1,'xs':1,'xxs':1}),
            {/literal}{else}{literal}
            minItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
            maxItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
            {/literal}{/if}{literal}
            move: {/literal}{$move}{literal},
            prevText: '<i class="icon-left-open-3"></i>',
            nextText: '<i class="icon-right-open-3"></i>',
            productSlider:true,
            allowOneSlide:false
        });
        var related_article_flexslider_rs;
        $(window).resize(function(){
            clearTimeout(related_article_flexslider_rs);
            var rand_s = parseInt(Math.random()*200 + 300);
            related_article_flexslider_rs = setTimeout(function() {
                {/literal}{if isset($column_slider) && $column_slider}{literal}
                var flexSliderSize = getFlexSliderSize({'lg':1,'md':1,'sm':1,'xs':1,'xxs':1});
                {/literal}{else}{literal}
                var flexSliderSize = getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}});
                {/literal}{/if}{literal}
                var flexslide_object = $('#related_article_slider{/literal}{$smarty.capture.column_slider}{literal} .sliderwrap').data('flexslider');
                if(flexSliderSize && flexslide_object != null )
                    flexslide_object.setVars({'minItems': flexSliderSize, 'maxItems': flexSliderSize});
            }, rand_s);
        });
    });
    {/literal} 
    //]]>
    </script>
</section>
{/if}
<!-- /St Blog recent articles  -->