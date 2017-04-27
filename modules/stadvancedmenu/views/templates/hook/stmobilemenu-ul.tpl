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
<!-- MODULE st advancedmenu -->
{if !isset($is_mega_menu_column)}
	{assign var='is_mega_menu_column' value=0}
{/if}
{if isset($stmenu)}
<ul class="mo_advanced_mu_level_0">
	{foreach $stmenu as $mm}
		{if $mm.hide_on_mobile == 1 && !$is_mega_menu_column}{continue}{/if}
		<li class="mo_advanced_ml_level_0 mo_advanced_ml_column">
			<a id="st_mo_advanced_ma_{$mm.id_st_advanced_menu}" href="{if $mm.m_link}{$mm.m_link|escape:'html':'UTF-8'}{else}javascript:;{/if}" class="mo_advanced_ma_level_0" {if !$adv_menu_title} title="{$mm.m_title|escape:'html':'UTF-8'}"{/if}{if $mm.nofollow} rel="nofollow"{/if}{if $mm.new_window} target="_blank"{/if}>{if $mm.m_icon}{$mm.m_icon}{else}{if $mm.icon_class}<i class="{$mm.icon_class}"></i>{/if}{$mm.m_name|escape:'html':'UTF-8'}{/if}{if $mm.cate_label}<span class="cate_label">{$mm.cate_label}</span>{/if}</a>
			{if isset($mm.column) && count($mm.column)}
				<span class="opener">&nbsp;</span>
				{foreach $mm.column as $column}
					{if $column.hide_on_mobile == 1 && !$is_mega_menu_column}{continue}{/if}
					{if isset($column.children) && count($column.children)}
						{foreach $column.children as $block}
							{if $block.hide_on_mobile == 1 && !$is_mega_menu_column}{continue}{/if}
							{if $block.item_t==1}
								{if $block.subtype==2  && isset($block.children)}
									<ul class="mo_advanced_mu_level_1 mo_advanced_sub_ul">
										<li class="mo_advanced_ml_level_1 mo_advanced_sub_li">
											<a id="st_mo_advanced_ma_{$block.id_st_advanced_menu}" href="{$block.children.link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$block.children.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if} class="mo_advanced_ma_level_1 mo_advanced_sub_a">{$block.children.name|escape:'html':'UTF-8'}{if $block.cate_label}<span class="cate_label">{$block.cate_label}</span>{/if}</a>
											{if isset($block.children.children) && is_array($block.children.children) && count($block.children.children)}
												<span class="opener">&nbsp;</span>
												<ul class="mo_advanced_mu_level_2 mo_advanced_sub_ul">
												{foreach $block.children.children as $product}
												<li class="mo_advanced_ml_level_2 mo_advanced_sub_li"><a href="{$product.link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$product.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if} class="mo_advanced_ma_level_2 mo_advanced_sub_a">{$product.name|truncate:45:'...'|escape:'html':'UTF-8'}</a></li>
												{/foreach}
												</ul>	
											{/if}
										</li>
									</ul>	
								{elseif $block.subtype==0  && isset($block.children.children) && count($block.children.children)}
									{foreach $block.children.children as $menu}
										<ul class="mo_advanced_mu_level_1 mo_advanced_sub_ul">
											<li class="mo_advanced_ml_level_1 mo_advanced_sub_li">
												<a href="{$menu.link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$menu.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if} class="mo_advanced_ma_level_1 mo_advanced_sub_a">{$menu.name|escape:'html':'UTF-8'}</a>
												{if isset($menu.children) && is_array($menu.children) && count($menu.children)}
													{include file="./stadvancedmenu-category.tpl" nofollow=$block.nofollow new_window=$block.new_window menus=$menu.children m_level=2 ismobilemenu=true}
												{/if}
											</li>
										</ul>	
									{/foreach}
								{elseif $block.subtype==1 || $block.subtype==3}
									<ul class="mo_advanced_mu_level_1 mo_advanced_sub_ul">
										<li class="mo_advanced_ml_level_1 mo_advanced_sub_li">
											<a  id="st_mo_advanced_ma_{$block.id_st_advanced_menu}" href="{$block.children.link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$block.children.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if} class="mo_advanced_ma_level_1 mo_advanced_sub_a">{$block.children.name|escape:'html':'UTF-8'}{if $block.cate_label}<span class="cate_label">{$block.cate_label}</span>{/if}</a>
    										{if isset($block.children.children) && count($block.children.children)}
												{include file="./stadvancedmenu-category.tpl" nofollow=$block.nofollow new_window=$block.new_window menus=$block.children.children m_level=2 ismobilemenu=true}
											{/if}
										</li>
									</ul>	
								{/if}
							{elseif $block.item_t==2 && isset($block.children) && count($block.children)}
								<div id="st_advanced_menu_block_{$block.id_st_advanced_menu}" class="stmobileadvancedmenu_column">
								{foreach $block.children as $product}
									<div class="mo_advanced_pro_div">
										<a class="product_img_link"	href="{$link->getProductLink($product->id, $product->link_rewrite, $product->category)|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$product->name|escape:'html':'UTF-8'}"{/if} itemprop="url"{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}>
											<img class="replace-2x img-responsive menu_pro_img" src="{$link->getImageLink($product->link_rewrite, $product->id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product->legend)}{$product->legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" {if !$adv_menu_title} title="{if !empty($product->legend)}{$product->legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}"{/if} {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />
										</a>
										<a class="product-name" href="{$link->getProductLink($product->id, $product->link_rewrite, $product->category)|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$product->name|escape:'html':'UTF-8'}"{/if} itemprop="url"{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}>
											{$product->name|truncate:45:'...'|escape:'html':'UTF-8'}
										</a>
									</div>
								{/foreach}
								</div>
							{elseif $block.item_t==3 && isset($block.children) && count($block.children)}
								{if isset($block.subtype) && $block.subtype}
									{foreach $block.children as $brand}
    									<ul class="mo_advanced_mu_level_1 mo_advanced_sub_ul">
											<li class="mo_advanced_ml_level_1 mo_advanced_sub_li">
												<a href="{$link->getmanufacturerLink($brand.id_manufacturer, $brand.link_rewrite)}" {if !$adv_menu_title} title="{$brand.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if} class="mo_advanced_ma_level_1 mo_advanced_sub_a">{$brand.name|escape:'html':'UTF-8'}</a>
											</li>
										</ul>	
									{/foreach}
								{else}
									<div id="st_advanced_menu_block_{$block.id_st_advanced_menu}" class="stmobileadvancedmenu_column">
										{foreach $block.children as $brand}
	    									<div class="mo_advanced_brand_div">
												<a href="{$link->getmanufacturerLink($brand.id_manufacturer, $brand.link_rewrite)}" {if !$adv_menu_title} title="{$brand.name|escape:html:'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if} class="st_advanced_menu_brand">
								                    <img src="{$img_manu_dir}{$brand.id_manufacturer|escape:'htmlall':'UTF-8'}-manufacturer_default.jpg" alt="{$brand.name|escape:html:'UTF-8'}" width="{$manufacturerSize.width}" height="{$manufacturerSize.height}" class="replace-2x img-responsive" />
								                </a>
											</div>
										{/foreach}
									</div>
								{/if}
							{elseif $block.item_t==4}
								<ul class="mo_advanced_mu_level_1 mo_advanced_sub_ul">
									<li class="mo_advanced_ml_level_1 mo_advanced_sub_li">
										<a  id="st_mo_advanced_ma_{$block.id_st_advanced_menu}" href="{if $block.m_link}{$block.m_link|escape:'html':'UTF-8'}{else}javascript:;{/if}" {if !$adv_menu_title} title="{$block.m_title|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if} class="mo_advanced_ma_level_1 mo_advanced_sub_a {if !$block.m_link} advanced_ma_span{/if}">{if $block.icon_class}<i class="{$block.icon_class}"></i>{/if}{$block.m_name|escape:'html':'UTF-8'}{if $block.cate_label}<span class="cate_label">{$block.cate_label}</span>{/if}</a>
										{if isset($block.children) && is_array($block.children) && count($block.children)}
											{foreach $block.children as $menu}
												{if $menu.hide_on_mobile == 1 && !$is_mega_menu_column}{continue}{/if}
												<span class="opener">&nbsp;</span>
												<ul class="mo_advanced_mu_level_2 mo_advanced_sub_ul">
												{include file="./stadvancedmenu-link.tpl" nofollow=$block.nofollow new_window=$block.new_window menus=$menu m_level=2 ismobilemenu=true}
												</ul>
											{/foreach}
										{/if}
									</li>
								</ul>	
							{elseif $block.item_t==5 && $block.html}
								<div id="st_advanced_menu_block_{$block.id_st_advanced_menu}" class="stmobileadvancedmenu_column style_content">
									{$block.html}
								</div>
							{/if}
						{/foreach}
					{/if}
				{/foreach}
			{/if}
		</li>
	{/foreach}
</ul>
{/if}
<!-- /MODULE st advancedmenu -->