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
{assign var='slide_lr_column' value=Configuration::get('STSN_SLIDE_LR_COLUMN')}
<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<html{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}>
	<head>
		<meta charset="utf-8" />
		<title>{$meta_title|escape:'html':'UTF-8'}</title>
		{if isset($meta_description) AND $meta_description}
			<meta name="description" content="{$meta_description|escape:'html':'UTF-8'}" />
		{/if}
		{if isset($meta_keywords) AND $meta_keywords}
			<meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}" />
		{/if}
		<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />
		{if isset($sttheme.responsive) && $sttheme.responsive && (!$sttheme.enabled_version_swithing || $sttheme.version_switching==0)}
		<meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
        {/if}
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
		<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />
		{if isset($sttheme.icon_iphone_57) && $sttheme.icon_iphone_57}
        <link rel="apple-touch-icon" sizes="57x57" href="{$sttheme.icon_iphone_57}" />
        {/if}
        {if isset($sttheme.icon_iphone_72) && $sttheme.icon_iphone_72}
        <link rel="apple-touch-icon" sizes="72x72" href="{$sttheme.icon_iphone_72}" />
        {/if}
        {if isset($sttheme.icon_iphone_114) && $sttheme.icon_iphone_114}
        <link rel="apple-touch-icon" sizes="114x114" href="{$sttheme.icon_iphone_114}" />
        {/if}
        {if isset($sttheme.icon_iphone_144) && $sttheme.icon_iphone_144}
        <link rel="apple-touch-icon" sizes="144x144" href="{$sttheme.icon_iphone_144}" />
        {/if}
		{if isset($css_files)}
			{foreach from=$css_files key=css_uri item=media}
				{if $css_uri == 'lteIE9'}
					<!--[if lte IE 9]>
					{foreach from=$css_files[$css_uri] key=css_uriie9 item=mediaie9}
					<link rel="stylesheet" href="{$css_uriie9|escape:'html':'UTF-8'}" type="text/css" media="{$mediaie9|escape:'html':'UTF-8'}" />
					{/foreach}
					<![endif]-->
				{else}
					<link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}" />
				{/if}
			{/foreach}
		{/if}
		{if isset($sttheme.custom_css) && $sttheme.custom_css}
			<link href="{$sttheme.custom_css}" rel="stylesheet" type="text/css" media="{$sttheme.custom_css_media}" />
		{/if}
		{if isset($js_defer) && !$js_defer && isset($js_files) && isset($js_def)}
			{$js_def}
			{foreach from=$js_files item=js_uri}
			<script type="text/javascript" src="{$js_uri|escape:'html':'UTF-8'}"></script>
			{/foreach}
		{/if}
		{if isset($sttheme.custom_js) && $sttheme.custom_js}
			<script type="text/javascript" src="{$sttheme.custom_js}"></script>
		{/if}
		{$HOOK_HEADER}
		{if isset($sttheme.head_code) && $sttheme.head_code}{$sttheme.head_code}{/if}
	</head>
	{assign var='use_mobile_header' value=Configuration::get('STSN_USE_MOBILE_HEADER')}
	<body{if isset($page_name)} id="{$page_name|escape:'html':'UTF-8'}"{/if} class="{if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}{if $hide_left_column} hide-left-column{else} show-left-column{/if}{if $hide_right_column} hide-right-column{else} show-right-column{/if}{if isset($content_only) && $content_only} content_only{/if} lang_{$lang_iso} 
	{foreach $languages as $language}
		{if $language.iso_code == $lang_iso && $language.is_rtl}
			is_rtl
		{/if}
	{/foreach}
	{if $sttheme.is_mobile_device} mobile_device {if $use_mobile_header==1} use_mobile_header {/if}{/if}{if $slide_lr_column} slide_lr_column {/if}
	{if $use_mobile_header==2} use_mobile_header {/if}
	">
	{if !isset($content_only) || !$content_only}
		{if isset($restricted_country_mode) && $restricted_country_mode}
			<div id="restricted-country">
				<p>{l s='You cannot place a new order from your country.'}{if isset($geolocation_country) && $geolocation_country} <span class="bold">{$geolocation_country|escape:'html':'UTF-8'}</span>{/if}</p>
			</div>
		{/if}
		<!--[if lt IE 9]>
		<p class="alert alert-warning">Please upgrade to Internet Explorer version 9 or download Firefox, Opera, Safari or Chrome.</p>
		<![endif]-->
		<div id="body_wrapper">
			{if isset($sttheme.boxstyle) && $sttheme.boxstyle==2}<div id="page_wrapper">{/if}
			<header id="page_header" class="{if $sttheme.transparent_header} transparent_header {/if} {if Configuration::get('STSN_TRANSPARENT_MOBILE_HEADER')} transparent-mobile-header {/if}">
				{capture name="displayBanner"}{hook h="displayBanner"}{/capture}
				{if isset($smarty.capture.displayBanner) && $smarty.capture.displayBanner|trim}
				<div class="banner">
					{$smarty.capture.displayBanner}
				</div>
				{/if}
				{capture name="displayNav"}{hook h="displayNav"}{/capture}
				{if isset($smarty.capture.displayNav) && $smarty.capture.displayNav|trim}
				<div id="top_bar" class="nav">
					<div class="container">
						<div class="row">
							<nav class="clearfix">{$smarty.capture.displayNav}</nav>
						</div>
					</div>
				</div>
				{/if}
Actualización 1
				{assign var='sticky_mobile_header' value=Configuration::get('STSN_STICKY_MOBILE_HEADER')}
	            <section id="mobile_bar" class="animated fast">
	            	<div class="container">
	                	<div id="mobile_bar_container" class="{if $sticky_mobile_header%2==0} mobile_bar_center_layout{else} mobile_bar_left_layout{/if}">
	                		{if $sticky_mobile_header%2==0}
	                		<div id="mobile_bar_left">
	                			<div id="mobile_bar_left_inner">{if isset($HOOK_MOBILE_MENU) && $HOOK_MOBILE_MENU|trim}{$HOOK_MOBILE_MENU}{else}{hook h='displayMobileMenu'}{/if}</div>
	                		</div>
	                		{/if}
	                		<div id="mobile_bar_center">
	                			<a id="mobile_header_logo" href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
									<img class="logo replace-2x" src="{$logo_url}" {if isset($sttheme.retina_logo) && $sttheme.retina_logo} data-2x="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`stthemeeditor/`$sttheme.retina_logo|escape:'html':'UTF-8'`")}"{/if} alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($sttheme.st_logo_image_width) && $sttheme.st_logo_image_width} width="{$sttheme.st_logo_image_width}"{/if}{if isset($sttheme.st_logo_image_height) && $sttheme.st_logo_image_height} height="{$sttheme.st_logo_image_height}"{/if}/>
								</a>	                			
	                		</div>
	                		<div id="mobile_bar_right">
	                			<div id="mobile_bar_right_inner">{if isset($HOOK_MOBILE_BAR) && $HOOK_MOBILE_BAR|trim}{$HOOK_MOBILE_BAR}{else}{hook h='displayMobileBar'}{/if}</div>
	                		</div>
	                	</div>
	                </div>
	            </section>

				{if isset($sttheme.logo_position) && $sttheme.logo_position}
				    {assign var="logo_left_center" value=1}
				{else}
				    {assign var="logo_left_center" value=0}
				{/if}
				<section id="header" class="{if $logo_left_center} logo_center {/if} animated fast">
				    <div class="wide_container">
					    <div class="container header_container">
					        <div class="row">
					            {if $logo_left_center}
								<div id="header_left" class="col-sm-12 col-md-{(12-$sttheme.logo_width)/2|intval} posi_rel">
					                <div id="header_left_inner" class="clearfix">{if isset($HOOK_TOP_LEFT) && $HOOK_TOP_LEFT|trim}{$HOOK_TOP_LEFT}{else}{hook h='displayTopLeft'}{/if}</div>
					            </div>
					            {/if}
					            <div id="logo_wrapper" class="col-sm-12 col-md-{$sttheme.logo_width}">
					            <div id="header_logo_inner">
								<a id="header_logo" href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
									<img class="logo replace-2x" src="{$logo_url}" {if isset($sttheme.retina_logo) && $sttheme.retina_logo} data-2x="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`stthemeeditor/`$sttheme.retina_logo|escape:'html':'UTF-8'`")}"{/if} alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($sttheme.st_logo_image_width) && $sttheme.st_logo_image_width} width="{$sttheme.st_logo_image_width}"{/if}{if isset($sttheme.st_logo_image_height) && $sttheme.st_logo_image_height} height="{$sttheme.st_logo_image_height}"{/if}/>
								</a>
					            </div>
					            </div>
								<div id="header_right" class="col-sm-12 {if $logo_left_center} col-md-{(12-$sttheme.logo_width)/2|ceil} {else} col-md-{12-$sttheme.logo_width} {/if} text-right">
					                <div id="header_right_inner" class="clearfix">{$HOOK_TOP}</div>
					            </div>
					        </div>
					    </div>
				    </div>
				</section>
					            
	            {if !isset($HOOK_MAIN_EMNU_WIDGET)}
					{capture name="displayMainMenuWidget"}{hook h="displayMainMenuWidget"}{/capture}
				{/if}
				{assign var='has_widgets' value=0}
				{if (isset($HOOK_MAIN_EMNU_WIDGET) && $HOOK_MAIN_EMNU_WIDGET|trim) || (isset($smarty.capture.displayMainMenuWidget) && $smarty.capture.displayMainMenuWidget|trim)}{$has_widgets=1}{/if}
				{assign var='megamenu_width' value=Configuration::get('STSN_ADV_MEGAMENU_WIDTH')}
	            {if (isset($HOOK_TOP_SECONDARY) && $HOOK_TOP_SECONDARY) || $has_widgets}
	            <section id="top_extra" class="main_menu_has_widgets_{$has_widgets}">
	            	{if !isset($megamenu_width) || !$megamenu_width}<div class="wide_container boxed_advancedmenu">{/if}
					<div id="st_advanced_menu_container" class="animated fast">
						<div class="container">
			            	<div class="container_inner clearfix">
				            	{if $has_widgets}
				            		<div id="main_menu_widgets" class="clearfix">
					            	{if isset($HOOK_MAIN_EMNU_WIDGET)}{$HOOK_MAIN_EMNU_WIDGET}{/if}
					            	{if isset($smarty.capture.displayMainMenuWidget)}{$smarty.capture.displayMainMenuWidget}{/if}
				            		</div>
				            	{/if}
				            	{$HOOK_TOP_SECONDARY}
							</div>
						</div>
					</div>
					{if !isset($megamenu_width) || !$megamenu_width}</div>{/if} 
				</section>
	            {/if}

				<!-- Breadcrumb -->         
	            {if $page_name != 'index' 
	            && $page_name != 'pagenotfound'
	            && $page_name != 'module-stblog-default'
	            }
	            <div id="breadcrumb_wrapper" class="{if isset($sttheme.breadcrumb_width) && $sttheme.breadcrumb_width} wide_container {/if}"><div class="container"><div class="row">
	                <div class="col-xs-12 col-sm-12 col-md-12 clearfix">
	                	{include file="$tpl_dir./breadcrumb.tpl"}
	                </div>
	            </div></div></div>
	            {/if}
				<!--/ Breadcrumb -->
			</header>

			<div class="main_content_area">
			<!-- Main slideshow -->
            {if $page_name == 'index'}
	            {hook h='displayAnywhere' function="displayMainSlide" mod='revsliderprestashop' caller='revsliderprestashop'}
            {/if}
            {if $page_name == 'module-stblog-default'}
	            {hook h='displayAnywhere' function="displayBlogMainSlide" mod='revsliderprestashop' caller='revsliderprestashop'}
            {/if}
			<!--/ Main slideshow -->
            {hook h="displayFullWidthTop"}
            {hook h="displayFullWidthTop2"}
			<div class="columns-container wide_container">
				<div id="columns" class="container">
					{capture name="displayTopColumn"}{hook h="displayTopColumn"}{/capture}
					{if isset($smarty.capture.displayTopColumn) && $smarty.capture.displayTopColumn|trim}
					<div id="slider_row" class="row">
						<div id="top_column" class="center_column clearfix col-xs-12 col-sm-12 col-md-12">{$smarty.capture.displayTopColumn}</div>
					</div>
					{/if}
					<div class="row">
						{if isset($left_column_size) && !empty($left_column_size)}
						<div id="left_column" class="column {if $slide_lr_column} col-xxs-8 col-xs-6{else} col-xs-12{/if} col-sm-{$left_column_size|intval} col-md-{$left_column_size|intval}">{$HOOK_LEFT_COLUMN}</div>
						{/if}
						{if isset($left_column_size) && isset($right_column_size)}{assign var='cols' value=(12 - $left_column_size - $right_column_size)}{else}{assign var='cols' value=12}{/if}
						<div id="center_column" class="center_column col-xs-12 col-sm-{$cols|intval} col-md-{$cols|intval}">
	{/if}