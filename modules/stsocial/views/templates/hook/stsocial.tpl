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
<!-- MODULE st social  -->
<section id="social_networking_block" class="block col-sm-12 col-md-{if $social_wide_on_footer}{$social_wide_on_footer}{else}3{/if}">
	<div class="title_block"><div class="title_block_name">{l s='Get Social' mod='stsocial'}</div><a href="javascript:;" class="opener dlm">&nbsp;</a></div>
    <div class="footer_block_content">
	<ul class="stsocial_list clearfix li_fl">
		{include file="./stsocial-items.tpl"}
	</ul>
    </div>
</section>