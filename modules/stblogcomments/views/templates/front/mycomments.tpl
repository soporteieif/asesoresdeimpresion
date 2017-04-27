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
<div id="stblogcomment">
	{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My account' mod='stblogcomments'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Blog comments' mod='stblogcomments'}{/capture}
	{include file="$tpl_dir./breadcrumb.tpl"}

	<h3 class="heading">{l s='Blog comments' mod='stblogcomments'}</h3>

	{include file="$tpl_dir./errors.tpl"}
    
    {if $message}
    <p class="success">{$message}</p>
    {/if}

	
	<form method="post" action="{$link->getModuleLink('stblogcomments','mycomments',array(),true)}" id="form_stblogcomments" enctype="multipart/form-data">
		<div class="clearfix mar_b1">
            <div id="avatar_left">
                <img src="{$avatar}" class="img-polaroid" alt="{l s='Avatar' mod='stblogcomments'}"" />
            </div>
			<div id="avatar_right">
                <p><label>{l s='Upload a new avatar(JPEG 80x80px):' mod='stblogcomments'}</label></p>
				<p><input type="file" id="avatar" name="avatar" class="inputTxt" size="20" /></p>
				<input type="hidden" name="token" value="{$token|escape:'htmlall':'UTF-8'}" />
                <input type="submit" name="submitAvatar" id="submitAvatar" value="{l s='Upload' mod='stblogcomments'}" class="btn btn-default" />
                {if $avatar && !preg_match('/stblogcomments|_default_/', $avatar)}<a href="{$link->getModuleLink('stblogcomments','mycomments',['act'=>'delavatar'])}" class="btn btn-default" title="{l s='Use default' mod='stblogcomments'}">{l s='Use default' mod='stblogcomments'}</a>{/if}
			</div>
		</div>
	</form>
    {if $comments}
	<h3 class="heading">{l s='My Comments' mod='stblogcomments'}</h3>
    <ul id="mycomments_list">    
        {foreach $comments as $comment}
        <li class="{if $comment@first}first_item{elseif $comment@last}last_item{/if}">
            <p>
                <span class="mar_r4">{dateFormat date=$comment.date_add full=0}</span>
                <span>{l s='On' mod='stblogcomments'}&nbsp;<a href="{$link->getModuleLink('stblog', 'article',['id_blog'=>$comment['id_st_blog'],'rewrite'=>$comment['link_rewrite']])|escape:'html'}#comments" title="{$comment.name|escape:'htmlall':'UTF-8'}">{$comment.name|escape:'htmlall':'UTF-8'|truncate:70:'...'}</a></span>
            </p>
            <div>
                {$comment.content}
            </div>
        </li>
        {/foreach}
    </ul>
    <div class="content_sortPagiBar">
        <div class="paginationBar paginationBarBottom clearfix">
		    {include file="./pagination.tpl"}
        </div>
	</div>
    {/if}
    <ul class="footer_links clearfix">
    	<li class="pull-left"><a href="{$link->getPageLink('my-account', true)|escape:'htmlall'}"><i class="icon-left icon-mar-lr2"></i>{l s='Back to Your Account' mod='stblogcomments'}</a></li>
    	<li class="pull-right"><a href="{$base_dir|escape:'htmlall'}"><i class="icon-home icon-mar-lr2"></i>{l s='Home' mod='stblogcomments'}</a></li>
    </ul>
</div>
{addJsDefL name='stblogcomments_fileDefaultHtml'}{l s='No file selected' mod='stblogcomments' js=1}{/addJsDefL}
{addJsDefL name='stblogcomments_fileButtonHtml'}{l s='Choose File' mod='stblogcomments' js=1}{/addJsDefL}