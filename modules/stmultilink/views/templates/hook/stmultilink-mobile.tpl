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

<!-- Block stlinkgroups top module -->
{foreach $link_groups as $link_group}
{if !$link_group.hide_on_mobile}
<ul id="multilink_mobile_{$link_group.id_st_multi_link_group}" class="mo_advanced_mu_level_0 st_side_item">
    <li class="mo_advanced_ml_level_0 mo_advanced_ml_column">
        <a href="{if $link_group.url}{$link_group.url|escape:'html'}{else}javascript:;{/if}" rel="nofollow" class="mo_advanced_ma_level_0 {if !$link_group.url}advanced_ma_span{/if}"{if isset($link_group.new_window) && $link_group.new_window} target="_blank" {/if}>
            {$link_group.name|escape:'htmlall':'UTF-8'}
        </a>
        {if is_array($link_group['links']) && count($link_group['links'])}
        <span class="opener">&nbsp;</span>
        <ul class="mo_advanced_mu_level_1 mo_advanced_sub_ul">
        {foreach $link_group['links'] as $link}
            <li class="mo_advanced_ml_level_1 mo_advanced_sub_li">
                <a href="{$link.url|escape:'html'}" title="{$link.title|escape:html:'UTF-8'}" {if isset($link.nofollow) && $link.nofollow} rel="nofollow" {/if} {if $link.new_window} target="_blank" {/if} class="mo_advanced_ma_level_1 mo_advanced_sub_a">
                    {$link.label|escape:html:'UTF-8'}
                </a>
            </li>
        {/foreach}
        </ul>
        {/if}
    </li>
</ul>
{/if}
{/foreach}
<!-- /Block stlinkgroups top module -->