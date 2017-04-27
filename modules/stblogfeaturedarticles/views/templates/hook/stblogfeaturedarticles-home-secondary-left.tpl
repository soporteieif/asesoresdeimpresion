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
<section id="st_blog_featured_article_{$hook_hash}" class="block section">
	<h4 class="title_block"><span>{l s='Featured articles ' mod='stblogfeaturedarticles'}</span></h4>
    {if is_array($blogs) && $blogs|count}
	<ul class="row blog_row_list">
	{foreach $blogs as $blog}
        <li class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {if $blog@iteration%2 == 1} first-item-of-desktop-line{/if} first-item-of-portrait-line">
            <div class="blog_image">
                <a href="{$blog.link|escape:'html'}" title="{$blog.name|escape:'htmlall':'UTF-8'}">
                <img src="{$blog.cover.links.small}" alt="{$blog.name|escape:'htmlall':'UTF-8'}" width="{$imageSize[1]['small'][0]}" height="{$imageSize[1]['small'][1]}" class="hover_effect" />
                </a>
            </div>
            <h3 class="s_title_block"><a href="{$blog.link|escape:'html'}" title="{$blog.name|escape:'htmlall':'UTF-8'}">{$blog.name|escape:'htmlall':'UTF-8'|truncate:70:'...'}</a></h3>
            {if $blog.content_short}<p class="blok_blog_short_content">{$blog.content_short|strip_tags:'UTF-8'|truncate:120:'...'}<a href="{$blog.link|escape:'html'}" title="{l s='Read More' mod='stblogfeaturedarticles'}" class="go">{l s='Read More' mod='stblogfeaturedarticles'}</a></p>{/if}
            <div class="blog_info">
                <span class="date-add">{dateFormat date=$blog.date_add full=0}</span>
                <span class="blog-categories">
                    {foreach $blog.categories as $category}
                        <a href="{$link->getModuleLink('stblog','category',['blog_id_category'=>$category.id_st_blog_category,'rewrite'=>$category.link_rewrite])|escape:'html'}" title="{$category.name|escape:'htmlall':'UTF-8'}">{$category.name|truncate:30:'...'|escape:'htmlall':'UTF-8'}</a>{if !$category@last},{/if}
                    {/foreach}
                </span>
                {hook h='displayAnywhere' function="getCommentNumber" id_blog=$blog.id_st_blog link_rewrite=$blog.link_rewrite mod='stblogcomments' caller='stblogcomments'}
                {if $display_viewcount}<span><i class="icon-eye-2 icon-mar-lr2"></i>{$blog.counter}</span>{/if}
            </div>
        </li>
    {/foreach}
    </ul>
    {else}
        <p class="warning">{l s='No featured articles' mod='stblogfeaturedarticles'}</p>
    {/if}
</section>
<!-- /St Blog featured articles  -->