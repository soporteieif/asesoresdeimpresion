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
    <dl id="multilink_{$link_group.id_st_multi_link_group}" class="stlinkgroups_top {if $link_group.location==9}pull-left{elseif $link_group.location==1}pull-right{/if} dropdown_wrap {if $link_group@first}first-item{/if} {if $link_group.hide_on_mobile} hide_on_mobile {/if} top_bar_item">
        <dt class="dropdown_tri">
        {if $link_group.url}
            <a href="{$link_group.url|escape:'html'}" title="{$link_group.name|escape:'htmlall':'UTF-8'}" class="dropdown_tri_inner" {if isset($link_group.nofollow) && $link_group.nofollow} rel="nofollow" {/if} {if isset($link_group.new_window) && $link_group.new_window} target="_blank" {/if}>
        {else}
            <div class="dropdown_tri_inner">
        {/if}
        {$link_group.name|escape:'htmlall':'UTF-8'}
        {if is_array($link_group['links']) && count($link_group['links'])}<b></b>{/if}
        {if $link_group.url}
            </a>
        {else}
            </div>
        {/if}
        </dt>
        <dd class="dropdown_list dropdown_right">
        <ul class="{if isset($link_group.link_align) && $link_group.link_align} text-center {/if}">
        {if $link_group['links']}
		{foreach $link_group['links'] as $link}
			<li>
        		<a href="{$link.url|escape:'html'}" title="{$link.title|escape:html:'UTF-8'}" {if isset($link.nofollow) && $link.nofollow} rel="nofollow" {/if} {if $link.new_window} target="_blank" {/if}>
                    {$link.label|escape:html:'UTF-8'}
        		</a>
			</li>
		{/foreach}
		{/if}
		</ul>
        </dd>
    </dl>
{/foreach}
<!-- /Block stlinkgroups top module -->