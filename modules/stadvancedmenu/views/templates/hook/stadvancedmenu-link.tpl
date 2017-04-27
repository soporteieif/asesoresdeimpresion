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
{if is_array($menus) && count($menus)}
	{assign var='granditem' value=0}
	{if isset($menu.granditem) && $menu.granditem}{$granditem=1}{/if}
	<li class="{if isset($ismobilemenu)}mo_advanced_sub_li mo_{/if}advanced_ml_level_{$m_level} granditem_{$granditem} p_granditem_{if isset($p_granditem)}{$p_granditem}{else}1{/if}">
	{if $menus.item_t==5}
		<div id="st_advanced_menu_block_{$menus.id_st_advanced_menu}">
			{$menus.html}
		</div>
	{else}
		{assign var='has_children' value=(isset($menu.children) && is_array($menu.children) && count($menu.children))}
		<a id="st_advanced_ma_{$menus.id_st_advanced_menu}" href="{$menus.m_link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$menus.m_title|escape:'html':'UTF-8'}"{/if}{if $menus.nofollow} rel="nofollow"{/if}{if $menus.new_window} target="_blank"{/if} class="{if isset($ismobilemenu)}mo_advanced_sub_a mo_{/if}advanced_ma_level_{$m_level} advanced_ma_item {if $has_children} has_children {/if}">{if $menus.icon_class}<i class="{$menus.icon_class}"></i>{/if}{$menus.m_name|escape:'html':'UTF-8'}{if $has_children && !isset($ismobilemenu) && (!isset($granditem) || !$granditem)}<span class="is_parent_icon"><b class="is_parent_icon_h"></b><b class="is_parent_icon_v"></b></span>{/if}{if $menus.cate_label}<span class="cate_label">{$menus.cate_label}</span>{/if}</a>
		{if $has_children}
			{if isset($ismobilemenu)}<span class="opener">&nbsp;</span>{/if}
			<ul class="{if isset($ismobilemenu)}mo_advanced_sub_ul mo_{/if}advanced_mu_level_{$m_level+1} p_granditem_{$granditem}">
			{foreach $menus.children as $menu}
				{if isset($ismobilemenu) && $menu.hide_on_mobile == 1}{continue}{/if}
				{include file="./stadvancedmenu-link.tpl" menus=$menu m_level=($m_level+1) p_granditem=$granditem}
			{/foreach}
			</ul>
		{/if}
	{/if}
	</li>
{/if}