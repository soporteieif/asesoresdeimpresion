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
<!-- Block currencies module -->
<ul id="currencies_block_mobile_menu" class="mo_advanced_mu_level_0 st_side_item">
	<li class="mo_advanced_ml_level_0 mo_advanced_ml_column">
		<a href="javascript:;" rel="nofollow" class="mo_advanced_ma_level_0 advanced_ma_span">{if $display_sign!=1}{$blockcurrencies_sign}&nbsp;{/if}{if $display_sign!=2}{foreach from=$currencies key=k item=f_currency}{if $cookie->id_currency == $f_currency.id_currency}{$f_currency.iso_code}{/if}{/foreach}{/if}</a>
		{if count($currencies) > 1}
		<span class="opener">&nbsp;</span>
        <ul class="mo_advanced_mu_level_1 mo_advanced_sub_ul">
			{foreach from=$currencies key=k item=f_currency}
            {if $cookie->id_currency != $f_currency.id_currency}
				<li class="mo_advanced_ml_level_1 mo_advanced_sub_li">
					<a href="javascript:setCurrency({$f_currency.id_currency});" class="mo_advanced_ma_level_1 mo_advanced_sub_a" title="{$f_currency.name}" rel="nofollow">{if $display_sign!=1}{$f_currency.sign}&nbsp;{/if}{if $display_sign!=2}{$f_currency.iso_code}{/if}</a>
				</li>
            {/if}
			{/foreach}
		</ul>
		{/if}
	</li>
</ul>
<!-- /Block currencies module -->