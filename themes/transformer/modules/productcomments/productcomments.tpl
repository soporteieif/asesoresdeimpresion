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
* Do not edit or add to this file if you wish to upgrade PrestaShop to newersend_friend_form_content
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- Fancybox -->
<div style="display:none;">
	<div id="new_comment_form">
		<form id="id_new_comment_form" action="#">
			<p class="block-heading">
				{l s='Write a review' mod='productcomments'}
			</p>
				{if isset($product) && $product}
					<div class="product clearfix">
						<img src="{$productcomment_cover_image}" height="{$mediumSize.height}" width="{$mediumSize.width}" alt="{$product->name|escape:'html':'UTF-8'}" class="hidden-xs" />
						<h5 class="product_name">{$product->name}</h5>
						<div class="product_desc">
							{$product->description_short}
						</div>
					</div>
				{/if}
				<div class="new_comment_form_content">
					<h4>{l s='Write a review' mod='productcomments'}</h4>
					<div id="new_comment_form_error" class="error mar_b1" style="display:none;">
						<ul></ul>
					</div>
					{if $criterions|@count > 0}
						<ul id="criterions_list">
						{foreach from=$criterions item='criterion'}
							<li>
								<label>{$criterion.name|escape:'html':'UTF-8'}</label>
								<div class="star_content">
									<input class="star not_uniform" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="1" />
									<input class="star not_uniform" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="2" />
									<input class="star not_uniform" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="3" />
									<input class="star not_uniform" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="4" />
									<input class="star not_uniform" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="5" checked="checked" />
								</div>
								<div class="clearfix"></div>
							</li>
						{/foreach}
						</ul>
					{/if}
					<label for="comment_title">
						{l s='Title:' mod='productcomments'}<sup class="required">*</sup>
					</label>
					<input id="comment_title" name="title" type="text" value=""/>
					<label for="content">
						{l s='Your review:' mod='productcomments'}<sup class="required">*</sup>
					</label>
					<textarea id="content" name="content"></textarea>
					{if $allow_guests == true && !$is_logged}
						<label>
							{l s='Your name:' mod='productcomments'}<sup class="required">*</sup>
						</label>
						<input id="commentCustomerName" name="customer_name" type="text" value=""/>
					{/if}
					<div id="new_comment_form_footer">
						<input id="id_product_comment_send" name="id_product" type="hidden" value='{$id_product_comment_form}' />
						<p class="fl required"><sup>*</sup> {l s='Required fields' mod='productcomments'}</p>
						<p class="fr">
							<button id="submitNewMessage" name="submitMessage" type="submit" class="btn btn-default">
								<span>{l s='Submit' mod='productcomments'}</span>
							</button>&nbsp;
							{l s='or' mod='productcomments'}&nbsp;
							<a class="closefb" href="#">
								{l s='Cancel' mod='productcomments'}
							</a>
						</p>
						<div class="clearfix"></div>
					</div> <!-- #new_comment_form_footer -->
				</div>
		</form><!-- /end new_comment_form_content -->
	</div>
</div>
<!-- End fancybox -->

<div id="idTab5" class="product_accordion block_hidden_only_for_screen">
    <div class="product_accordion_title">
    	<a href="javascript:;" class="opener dlm">&nbsp;</a>
        <div class="product_accordion_name">{l s='Comments' mod='productcomments'}({$nbComments})</div>
    </div>
	<div id="product_comments_block_tab" class="pa_content">	
		{if $comments}
			{foreach from=$comments item=comment}
				{if $comment.content}
				<div class="comment row" itemprop="review" itemscope itemtype="https://schema.org/Review">
					<div class="comment_author col-xs-12 col-sm-3 col-md-3">
						<div class="star_content clearfix"  itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
							{section name="i" start=0 loop=5 step=1}
								{if $comment.grade le $smarty.section.i.index}
									<div class="star"></div>
								{else}
									<div class="star star_on"></div>
								{/if}
							{/section}
							<meta itemprop="worstRating" content = "0" />
							<meta itemprop="ratingValue" content = "{$comment.grade|escape:'html':'UTF-8'}" />
            				<meta itemprop="bestRating" content = "5" />
						</div>
						<div class="comment_author_infos">
							<strong itemprop="author">{$comment.customer_name|escape:'html':'UTF-8'}</strong>
							<meta itemprop="datePublished" content="{$comment.date_add|escape:'html':'UTF-8'|substr:0:10}" />
							<em>{dateFormat date=$comment.date_add|escape:'html':'UTF-8' full=0}</em>
						</div>
					</div> <!-- .comment_author -->
					
					<div class="comment_details col-xs-12 col-sm-6 col-md-6">
						<div class="title_block" itemprop="name" >{$comment.title}</div>
						<p itemprop="reviewBody">{$comment.content|escape:'html':'UTF-8'|nl2br}</p>
					</div><!-- .comment_details -->
					<ul class="comment_actions col-xs-12 col-sm-3 col-md-3">
						<li>
							{l s='Was this comment useful to you?' mod='productcomments'}
						</li>
						<li>
							<a href="javascript:;" class="{if $logged} logged {/if}{if !$logged || (isset($comment.customer_advice) && !$comment.customer_advice)} usefulness_btn {else} useful_done {/if}" data-is-usefull="1" data-id-product-comment="{$comment.id_product_comment}" title="{l s='Yes' mod='productcomments'}"><i class="icon-thumbs-up-1"></i>(<span>{$comment.total_useful}</span>)</a>
                        	<a href="javascript:;" class="{if $logged} logged {/if}{if !$logged || (isset($comment.customer_advice) && !$comment.customer_advice)} usefulness_btn {else} useful_done {/if}" data-is-usefull="0" data-id-product-comment="{$comment.id_product_comment}" title="{l s='No' mod='productcomments'}"><i class="icon-thumbs-down-1"></i>(<span>{$comment.total_advice-$comment.total_useful}</span>)</a>
						</li>
						{if $logged && !$comment.customer_report}
						<li>
							<span class="report_btn" data-id-product-comment="{$comment.id_product_comment}">
								{l s='Report abuse' mod='productcomments'}
							</span>
						</li>
						{/if}
					</ul>
				</div> <!-- .comment -->
				{/if}
			{/foreach}
			{if (!$too_early AND ($is_logged OR $allow_guests))}
			<p class="align_center">
				<a id="new_comment_tab_btn" class="open-comment-form" href="#new_comment_form">
					{l s='Write your review!' mod='productcomments'}
				</a>
			</p>
			{/if}
		{else}
			{if (!$too_early AND ($is_logged OR $allow_guests))}
			<p class="align_center">
				<a id="new_comment_tab_btn" class="open-comment-form" href="#new_comment_form">
					{l s='Be the first to write your review!' mod='productcomments'}
				</a>
			</p>
			{else}
			<p class="align_center">{l s='No customer reviews for the moment.' mod='productcomments'}</p>
			{/if}
		{/if}
	</div> <!-- #product_comments_block_tab -->
</div>

{strip}
{addJsDef productcomments_controller_url=$productcomments_controller_url|@addcslashes:'\''}
{addJsDef moderation_active=$moderation_active|boolval}
{addJsDef productcomments_url_rewrite=$productcomments_url_rewriting_activated|boolval}
{addJsDef secure_key=$secure_key}

{addJsDefL name=confirm_report_message}{l s='Are you sure that you want to report this comment?' mod='productcomments' js=1}{/addJsDefL}
{addJsDefL name=productcomment_added}{l s='Your comment has been added!' mod='productcomments' js=1}{/addJsDefL}
{addJsDefL name=productcomment_added_moderation}{l s='Your comment has been added and will be available once approved by a moderator.' mod='productcomments' js=1}{/addJsDefL}
{addJsDefL name=productcomment_title}{l s='New comment' mod='productcomments' js=1}{/addJsDefL}
{addJsDefL name=productcomment_ok}{l s='OK' mod='productcomments' js=1}{/addJsDefL}
{addJsDefL name=comment_actions_login_first}{l s='Please login first!' mod='productcomments' js=1}{/addJsDefL}
{addJsDefL name=comment_actions_failure}{l s='An error occurred. Maybe a network problem or you already set.' mod='productcomments' js=1}{/addJsDefL}
{addJsDefL name=comment_success_msg}{l s='Success! Thank you!' mod='productcomments' js=1}{/addJsDefL}
{/strip}