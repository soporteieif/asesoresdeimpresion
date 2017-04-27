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
<!-- AddThis Button BEGIN -->
<div class="addthis_button_wrap mar_b1 clearfix">
<script type="text/javascript">
var addthis_config = {
      ui_language: "{$lang_iso}" 
} 
</script>
{if $addthis_style eq 1}
	<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
		{if count($addthis_customizing)}
			{foreach $addthis_customizing AS $value}
			<a class="addthis_button_{$value}" {if is_array($addthis_extra_attr) && count($addthis_extra_attr) && key_exists($value, $addthis_extra_attr)}{$addthis_extra_attr.$value}{/if}></a>
			{/foreach}
			{if $addthis_show_more eq 1}
			<a class="addthis_button_compact"></a>
			<a class="addthis_counter addthis_bubble_style"></a>
			{/if}
		{else}
		<a class="addthis_button_preferred_1"></a>
		<a class="addthis_button_preferred_2"></a>
		<a class="addthis_button_preferred_3"></a>
		<a class="addthis_button_preferred_4"></a>
		<a class="addthis_button_compact"></a>
		<a class="addthis_counter addthis_bubble_style"></a>
		{/if}
	</div>
	{if $addthis_pubid}<script type="text/javascript">{literal}var addthis_config = {"data_track_addressbar":true};{/literal}</script>{/if}
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid={if $addthis_pubid}{$addthis_pubid}{else}xa-516bd7ed3463ec2b{/if}"></script>
{else if $addthis_style eq 2}
	<div class="addthis_toolbox addthis_default_style">
	{if count($addthis_customizing)}
	{foreach $addthis_customizing AS $value}
	<a class="addthis_button_{$value}" {if is_array($addthis_extra_attr) && count($addthis_extra_attr) && key_exists($value, $addthis_extra_attr)}{$addthis_extra_attr.$value}{/if}></a>
	{/foreach}
	{if $addthis_show_more eq 1}
	<a class="addthis_button_compact"></a>
	<a class="addthis_counter addthis_bubble_style"></a>
	{/if}
	{else}
	<a class="addthis_button_preferred_1"></a>
	<a class="addthis_button_preferred_2"></a>
	<a class="addthis_button_preferred_3"></a>
	<a class="addthis_button_preferred_4"></a>
	<a class="addthis_button_compact"></a>
	<a class="addthis_counter addthis_bubble_style"></a>
	{/if}
	</div>
	{if $addthis_pubid}<script type="text/javascript">{literal}var addthis_config = {"data_track_addressbar":true};{/literal}</script>{/if}
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid={if $addthis_pubid}{$addthis_pubid}{else}xa-516bd80c570f2e87{/if}"></script>
{else if $addthis_style eq 3}
<a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid={if $addthis_pubid}{$addthis_pubid}{else}xa-516bd81b706fd972{/if}"><img src="//s7.addthis.com/static/btn/v2/lg-share-{$lang_iso}.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a>
{if $addthis_pubid}<script type="text/javascript">{literal}var addthis_config = {"data_track_addressbar":true};{/literal}</script>{/if}
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid={if $addthis_pubid}{$addthis_pubid}{else}xa-516bd81b706fd972{/if}"></script>
{else}
	<div class="addthis_toolbox addthis_default_style">
	{if count($addthis_style_one)}
		{foreach $addthis_style_one AS $value}
			{if $value eq 'facebook_like'}
			<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
			{elseif $value eq 'facebook_share'}
			<a class="addthis_button_facebook_share" fb:share:layout="button_count"></a>
			{elseif $value eq 'google_plusone'}
			<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
			{elseif $value eq 'google_plusone_badge'}
			<a class="addthis_button_google_plusone_badge" g:plusone:size="small"></a>
			{elseif $value eq 'pinterest_share'}
			<a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal" pi:pinit:url="http://www.addthis.com/features/pinterest" pi:pinit:media="http://www.addthis.com/cms-content/images/features/pinterest-lg.png"></a>
			{else}
			<a class="addthis_button_{$value}" {if is_array($addthis_extra_attr) && count($addthis_extra_attr) && key_exists($value, $addthis_extra_attr)}{$addthis_extra_attr.$value}{/if}></a>
			{/if}
		{/foreach}
		{if $addthis_show_more eq 1}
		<a class="addthis_counter addthis_pill_style"></a>
		{/if}
	{else}
		<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
		<a class="addthis_button_tweet"></a>
		<a class="addthis_button_pinterest_pinit"></a>
		<a class="addthis_counter addthis_pill_style"></a>
		{/if}
		</div>
		{if $addthis_pubid}<script type="text/javascript">{literal}var addthis_config = {"data_track_addressbar":true};{/literal}</script>{/if}
		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid={if $addthis_pubid}{$addthis_pubid}{else}xa-516bd96831c30839{/if}"></script>
	{/if}
</div>
<!-- AddThis Button END -->