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
<!-- St Blog latest comments -->
<section id="st_blog_latest_comments" class="block">
	<h4 class="title_block"><span>{l s='Latest Comments' mod='stblogcomments'}</span></h4>
    <div class="block_content">
        {if $latest_comments && count($latest_comments)}
		<ul class="pro_column_list">
            {foreach $latest_comments as $comment}
            <li class="clearfix ">
                <div class="pro_column_left">
                <a href="{$link->getModuleLink('stblog', 'article',['id_blog'=>$comment['id_st_blog'],'rewrite'=>$comment['link_rewrite']])|escape:'html'}" title="{$comment.customer_name|escape:'htmlall':'UTF-8'}">
                    <img src="{$comment.avatar}" alt="{$comment.customer_name|escape:'htmlall':'UTF-8'}" />
    			</a>
                </div>
    			<div class="pro_column_right">
    				<h4 class="s_title_block nohidden">{$comment.customer_name|escape:'htmlall':'UTF-8'}</h4>           			      
                    {l s='on' mod='stblogcomments'} <a href="{$link->getModuleLink('stblog', 'article',['id_blog'=>$comment['id_st_blog'],'rewrite'=>$comment['link_rewrite']])|escape:'html'}" title="{$comment.name|escape:'htmlall':'UTF-8'}">{$comment.name|truncate:50:'...'|escape:html:'UTF-8'}</a>
                </div>
            </li>
            {/foreach}
        </ul>
        {else}
            {l s='No comments' mod='stblogcomments'}
        {/if}
	</div>
</section>
<!-- /St Blog latest comments  -->