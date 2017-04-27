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
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if (!$content_only && (($nbComments == 0 && $too_early == false && ($is_logged || $allow_guests)) || ($nbComments != 0)))}
{assign var='enable_reivew_aggregate' value=(!isset($sttheme.google_rich_snippets) || (isset($sttheme.google_rich_snippets) && $sttheme.google_rich_snippets==1))}
<div id="product_comments_block_extra" class="no-print clearfix" {if $enable_reivew_aggregate}itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating"{/if}>
	{if $nbComments != 0}
		<div class="comments_note pull-left">
			<div class="star_content clearfix">
				{section name="i" start=0 loop=5 step=1}
					{if $averageTotal le $smarty.section.i.index}
						<div class="star"></div>
					{else}
						<div class="star star_on"></div>
					{/if}
				{/section}
				{if $enable_reivew_aggregate}
				<meta itemprop="worstRating" content = "0" />
				<meta itemprop="ratingValue" content = "{if isset($ratings.avg)}{$ratings.avg|round:1|escape:'html':'UTF-8'}{else}{$averageTotal|round:1|escape:'html':'UTF-8'}{/if}" />
				<meta itemprop="bestRating" content = "5" />
				{/if}
			</div>
			<a href="#idTab5" class="reviews pull-left">
				(<span {if $enable_reivew_aggregate}itemprop="reviewCount"{/if}>{$nbComments}</span>)
			</a>
		</div> <!-- .comments_note -->
	{/if}
	<div class="comments_advices  pull-left">
		{if ($too_early == false AND ($is_logged OR $allow_guests))}
			<a class="open-comment-form" href="#new_comment_form">
				{l s='Write a review' mod='productcomments'}
			</a>
		{/if}
	</div>
</div>
{/if}
<!--  /Module ProductComments -->