{*
* 2007-2012 PrestaShop
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
*  @copyright  2007-2012 PrestaShop SA
*  @version  Release: $Revision: 17677 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if isset($blogs)}
    {assign var='blog_grid_per_lg' value=Configuration::get('STSN_BLOG_GRID_PER_LG_0')}
    {assign var='blog_grid_per_md' value=Configuration::get('STSN_BLOG_GRID_PER_MD_0')}
    {assign var='blog_grid_per_sm' value=Configuration::get('STSN_BLOG_GRID_PER_SM_0')}
    {assign var='blog_grid_per_xs' value=Configuration::get('STSN_BLOG_GRID_PER_XS_0')}
    {assign var='blog_grid_per_xxs' value=Configuration::get('STSN_BLOG_GRID_PER_XXS_0')}
    {assign var='length_of_article_name' value=Configuration::get('ST_LENGTH_OF_ARTICLE_NAME')}
	<!-- Products list -->
	<ul class="blog_list_grid blog_list clearfix">
	{foreach $blogs as $blog}
        {math equation="(total%perLine)" total=$blog@total perLine=$blog_grid_per_lg assign=totModuloDesktop}
        {math equation="(total%perLine)" total=$blog@total perLine=$blog_grid_per_md assign=totModulo}
        {math equation="(total%perLine)" total=$blog@total perLine=$blog_grid_per_sm assign=totModuloTablet}
        {math equation="(total%perLine)" total=$blog@total perLine=$blog_grid_per_xs assign=totModuloMobile}
        {math equation="(total%perLine)" total=$blog@total perLine=$blog_grid_per_xxs assign=totModuloPortrait}
        {if $totModuloDesktop == 0}{assign var='totModuloDesktop' value=$blog_grid_per_lg}{/if}
        {if $totModulo == 0}{assign var='totModulo' value=$blog_grid_per_md}{/if}
        {if $totModuloTablet == 0}{assign var='totModuloTablet' value=$blog_grid_per_sm}{/if}
        {if $totModuloMobile == 0}{assign var='totModuloMobile' value=$blog_grid_per_xs}{/if}
        {if $totModuloPortrait == 0}{assign var='totModuloPortrait' value=$blog_grid_per_xxs}{/if}
        <li class="block_blog col-lg-{(12/$blog_grid_per_lg)|replace:'.':'-'} col-md-{(12/$blog_grid_per_md)|replace:'.':'-'} col-sm-{(12/$blog_grid_per_sm)|replace:'.':'-'} col-xs-{(12/$blog_grid_per_xs)|replace:'.':'-'} col-xxs-{(12/$blog_grid_per_xxs)|replace:'.':'-'} 
        {if $blog@iteration%$blog_grid_per_lg == 0} last-item-of-desktop-line{elseif $blog@iteration%$blog_grid_per_lg == 1} first-item-of-desktop-line{/if}{if $blog@iteration > ($blog@total - $totModuloDesktop)} last-desktop-line{/if}{if $blog@index < $blog_grid_per_lg} first-desktop-line{/if}
        {if $blog@iteration%$blog_grid_per_md == 0} last-in-line{elseif $blog@iteration%$blog_grid_per_md == 1} first-in-line{/if}{if $blog@iteration > ($blog@total - $totModulo)} last-line{/if}{if $blog@index < $blog_grid_per_md} first-line{/if}
        {if $blog@iteration%$blog_grid_per_sm == 0} last-item-of-tablet-line{elseif $blog@iteration%$blog_grid_per_sm == 1} first-item-of-tablet-line{/if}{if $blog@iteration > ($blog@total - $totModuloTablet)} last-tablet-line{/if}{if $blog@index < $blog_grid_per_sm} first-tablet-line{/if}
        {if $blog@iteration%$blog_grid_per_xs == 0} last-item-of-mobile-line{elseif $blog@iteration%$blog_grid_per_xs == 1} first-item-of-mobile-line{/if}{if $blog@iteration > ($blog@total - $totModuloMobile)} last-mobile-line{/if}{if $blog@index < $blog_grid_per_xs} first-mobile-line{/if}
        {if $blog@iteration%$blog_grid_per_xxs == 0} last-item-of-portrait-line{elseif $blog@iteration%$blog_grid_per_xxs == 1} first-item-of-portrait-line{/if}{if $blog@iteration > ($blog@total - $totModuloPortrait)} last-portrait-line{/if}{if $blog@index < $blog_grid_per_xxs} first-portrait-line{/if}">
            {if $blog.type==1}
                <div class="blog_image"><a href="{$blog.link|escape:'html'}" rel="bookmark" title="{$blog.name|escape:'html':'UTF-8'}"><img src="{$blog.cover.links.medium}" alt="{$blog.name|escape:'html':'UTF-8'}" width="{$imageSize[1]['medium'][0]}" height="{$imageSize[1]['medium'][1]}" class="hover_effect" /></a></div>
            {/if}
            
            {if $blog.type==2 && isset($blog['galleries']) && $blog['galleries']|count}
                <div class="blog_gallery">
                <div class="blog_flexslider flexslider">
                    <ul class="slides">
                        {foreach $blog['galleries'] as $gallery}
                        <li>
                          <a href="{$blog.link|escape:'html'}" rel="bookmark" title="{$blog.name|escape:'html':'UTF-8'}"><img src="{$gallery.links.medium}" alt="{$blog.name|escape:'html':'UTF-8'}" width="{$imageSize[1]['medium'][0]}" height="{$imageSize[1]['medium'][1]}" class="hover_effect" /></a>
                        </li>
                        {/foreach}
                    </ul>
                </div>
                </div>
            {elseif $blog.type==2}
                <div class="blog_image"><a href="{$blog.link|escape:'html'}" rel="bookmark" title="{$blog.name|escape:'html':'UTF-8'}"><img src="{$blog.cover.links.medium}" alt="{$blog.name|escape:'html':'UTF-8'}" width="{$imageSize[1]['medium'][0]}" height="{$imageSize[1]['medium'][1]}" class="hover_effect" /></a></div>
            {/if}
            
            {if $blog.type==3 && $blog.video}
                <div class="blog_video"><div class="full_video">{$blog.video}</div></div>
            {elseif $blog.type==3}
                <div class="blog_image"><a href="{$blog.link|escape:'html'}" rel="bookmark" title="{$blog.name|escape:'html':'UTF-8'}"><img src="{$blog.cover.links.medium}" alt="{$blog.name|escape:'html':'UTF-8'}" width="{$imageSize[1]['medium'][0]}" height="{$imageSize[1]['medium'][1]}" class="hover_effect" /></a></div>
            {/if}
            <div>
                <h3 class="s_title_block{if $length_of_article_name} nohidden{/if}"><a href="{$blog.link|escape:'html'}" title="{$blog.name|escape:'html':'UTF-8'}">{if $length_of_article_name == 1}{$blog.name|escape:'html':'UTF-8'}{else}{$blog.name|escape:'html':'UTF-8'|truncate:70:'...'}{/if}</a></h3>
                {if $blog.content_short}<p class="blok_blog_short_content">{$blog.content_short|strip_tags:'UTF-8'|truncate:200:'...'}<a href="{$blog.link|escape:'html'}" title="{l s='Read More' mod='stblog'}" class="go">{l s='Read More' mod='stblog'}</a></p>{/if}
                <div class="blog_info">
                    <span class="date-add">{dateFormat date=$blog.date_add full=0}</span>
                    <span class="blog-categories">
                        {foreach $blog.categories as $category}
                            <a href="{$link->getModuleLink('stblog','category',['blog_id_category'=>$category.id_st_blog_category,'rewrite'=>$category.link_rewrite])|escape:'html'}" title="{$category.name|escape:'html':'UTF-8'}">{$category.name|truncate:30:'...'|escape:'html':'UTF-8'}</a>{if !$category@last},{/if}
                        {/foreach}
                    </span>
                    {hook h='displayAnywhere' function="getCommentNumber" id_blog=$blog.id_st_blog link_rewrite=$blog.link_rewrite mod='stblogcomments' caller='stblogcomments'}
                    {if $display_viewcount}<span><i class="icon-eye-2 icon-mar-lr2"></i>{$blog.counter}</span>{/if}
                </div>
            </div>
		</li>
	{/foreach}
	</ul>
	<!-- /Products list -->
{/if}