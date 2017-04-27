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
<!-- MODULE st easy content -->
{if $easy_content|@count > 0}
    {foreach $easy_content as $ec}
    <section id="easycontent_{$ec.id_st_easy_content}" class="{if $ec.hide_on_mobile == 1}hidden-sm{elseif $ec.hide_on_mobile == 2}visible-sm visible-sm-block{/if} easycontent col-sm-12 col-md-{if $ec.span}{$ec.span}{else}3{/if} block">
        {if $ec.title}
        <div class="title_block">
            {if $ec.url}<a href="{$ec.url|escape}" class="title_block_name" title="{$ec.title|escape:html:'UTF-8'}">{else}<div class="title_block_name">{/if}
            {$ec.title|escape:html:'UTF-8'}
            {if $ec.url}</a>{else}</div>{/if}
            <a href="javascript:;" class="opener dlm">&nbsp;</a>
        </div>
        {/if}
    	<div class="easycontent footer_block_content {if !$ec.title}keep_open{/if}  {if $ec.text_align==2} text-center {elseif $ec.text_align==3} text-right {/if} {if $ec.width} center_width_{$ec.width} {/if}">
            {$ec.text|stripslashes}
    	</div>
    </section>
    {/foreach}
{/if}
<!-- MODULE st easy content -->