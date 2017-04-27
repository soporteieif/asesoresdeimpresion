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
    {assign var='length_of_article_name' value=Configuration::get('ST_LENGTH_OF_ARTICLE_NAME')}
	<!-- Products list -->
	<ul class="blog_list_large clearfix">
	{foreach $blogs as $blog}
		<li class="block_blog {if $blog@first}first_item{elseif $blog@last}last_item{/if} clearfix">
            {if $blog.type==1 && $blog['covered']}
                <div class="blog_image"><a href="{$blog.link|escape:'html'}" rel="bookmark" title="{$blog.name|escape:'html':'UTF-8'}"><img src="{$blog.cover.links.large}" alt="{$blog.name|escape:'html':'UTF-8'}" width="{$imageSize[1]['large'][0]}" height="{$imageSize[1]['large'][1]}" class="hover_effect" /></a></div>
            {/if}
            
            {if $blog.type==2 && isset($blog['galleries']) && $blog['galleries']|count}
                <div class="blog_gallery">
                <div class="blog_flexslider flexslider">
                    <ul class="slides">
                        {foreach $blog['galleries'] as $gallery}
                        <li>
                          <a href="{$blog.link|escape:'html'}" rel="bookmark" title="{$blog.name|escape:'html':'UTF-8'}"><img src="{$gallery.links.large}" alt="{$blog.name|escape:'html':'UTF-8'}" width="{$imageSize[1]['large'][0]}" height="{$imageSize[1]['large'][1]}" class="hover_effect" /></a>
                        </li>
                        {/foreach}
                    </ul>
                </div>
                </div>
            {elseif $blog.type==2 && $blog['covered']}
                <div class="blog_image"><a href="{$blog.link|escape:'html'}" rel="bookmark" title="{$blog.name|escape:'html':'UTF-8'}"><img src="{$blog.cover.links.large}" alt="{$blog.name|escape:'html':'UTF-8'}" width="{$imageSize[1]['large'][0]}" height="{$imageSize[1]['large'][1]}" class="hover_effect" /></a></div>
            {/if}
            
            {if $blog.type==3 && $blog.video}
                <div class="blog_video"><div class="full_video">{$blog.video}</div></div>
            {elseif $blog.type==3 && $blog['covered']}
                <a href="{$blog.link|escape:'html'}" rel="bookmark" title="{$blog.name|escape:'html':'UTF-8'}"><div class="blog_image"><img src="{$blog.cover.links.large}" alt="{$blog.name|escape:'html':'UTF-8'}" width="{$imageSize[1]['large'][0]}" height="{$imageSize[1]['large'][1]}" class="hover_effect" /></div></a>
            {/if}
            <div>
                <h3 class="s_title_block{if $length_of_article_name} nohidden{/if}"><a href="{$blog.link|escape:'html'}" title="{$blog.name|escape:'html':'UTF-8'}">{if $length_of_article_name == 1}{$blog.name|escape:'html':'UTF-8'}{else}{$blog.name|escape:'html':'UTF-8'|truncate:70:'...'}{/if}</a></h3>
                {if $blog.content_short}<p class="blok_blog_short_content">{$blog.content_short|strip_tags:'UTF-8'}<a href="{$blog.link|escape:'html'}" title="{l s='Read More' mod='stblog'}" class="go">{l s='Read More' mod='stblog'}</a></p>{/if}
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