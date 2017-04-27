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
	{if !isset($granditem)}{assign var='granditem' value=0}{/if}
	{if isset($ismobilemenu)}<span class="opener">&nbsp;</span>{/if}
	<ul class="{if isset($ismobilemenu)}mo_advanced_sub_ul mo_{/if}advanced_mu_level_{$m_level} p_granditem_{if $m_level>2}{$granditem}{else}1{/if}">
	{foreach $menus as $menu}
		{assign var='has_children' value=(isset($menu.children) && is_array($menu.children) && count($menu.children))}
		<li class="{if isset($ismobilemenu)}mo_advanced_sub_li mo_{/if}advanced_ml_level_{$m_level} granditem_{$granditem} p_granditem_{if $m_level>2}{$granditem}{else}1{/if}">
			<a href="{$menu.link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$menu.name|escape:'html':'UTF-8'}"{/if}{if $nofollow} rel="nofollow"{/if}{if $new_window} target="_blank"{/if} class="{if isset($ismobilemenu)}mo_advanced_sub_a mo_{/if}advanced_ma_level_{$m_level} advanced_ma_item {if $has_children} has_children {/if}">{$menu.name|escape:'html':'UTF-8'}{if $has_children && !isset($ismobilemenu) && (!isset($granditem) || !$granditem)}<span class="is_parent_icon"><b class="is_parent_icon_h"></b><b class="is_parent_icon_v"></b></span>{/if}</a>
		{if $has_children}
			{include file="./stadvancedmenu-category.tpl" menus=$menu.children granditem=$granditem m_level=($m_level+1)}
		{/if}
		</li>
	{/foreach}
	</ul>
{/if}