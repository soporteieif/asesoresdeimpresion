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

<!-- Block stlinkgroups footer module -->
{foreach $link_groups as $link_group}
<section id="multilink_{$link_group.id_st_multi_link_group}" class="stlinkgroups_links_footer_bottom {if $link_group.hide_on_mobile} hidden-xs {/if}">
    <ul class="li_fl clearfix custom_links_list">
    {if $link_group['links']}
    {foreach $link_group['links'] as $link}
    	<li>
    		<a href="{$link.url|escape:'html'}" title="{$link.title|escape:'htmlall':'UTF-8'}" {if isset($link.nofollow) && $link.nofollow} rel="nofollow" {/if} {if $link.new_window} target="_blank" {/if}>
                {$link.label|escape:'htmlall':'UTF-8'}
    		</a>
    	</li>
    {/foreach}
    {/if}
    </ul>
</section>
{/foreach}
<!-- /Block stlinkgroups footer module -->