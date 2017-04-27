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

{capture name=path}{l s='My account'}{/capture}

<h1 class="page-heading">{l s='My account'}</h1>
{if isset($account_created)}
	<p class="alert alert-success">
		{l s='Your account has been created.'}
	</p>
{/if}
<p class="info-account">{l s='Welcome to your account. Here you can manage all of your personal information and orders. '}</p>
<div class="row addresses-lists">
	<div class="col-xs-12 col-sm-6 col-lg-4">
		<ul class="myaccount-link-list">
            {if $has_customer_an_address}
            <li><a href="{$link->getPageLink('address', true)|escape:'html':'UTF-8'}" title="{l s='Add my first address'}"><span class="icon_wrap"><i class="icon-cog icon-1x"></i></span>{l s='Add my first address'}</a></li>
            {/if}
            <li><a href="{$link->getPageLink('history', true)|escape:'html':'UTF-8'}" title="{l s='Orders'}"><span class="icon_wrap"><i class="icon-basket icon-1x"></i></span>{l s='Order history and details'}</a></li>
            {if $returnAllowed}
                <li><a href="{$link->getPageLink('order-follow', true)|escape:'html':'UTF-8'}" title="{l s='Merchandise returns'}"><span class="icon_wrap"><i class="icon-left icon-1x"></i></span>{l s='My merchandise returns'}</a></li>
            {/if}
            <li><a href="{$link->getPageLink('order-slip', true)|escape:'html':'UTF-8'}" title="{l s='Credit slips'}"><span class="icon_wrap"><i class="icon-doc-text-inv icon-1x"></i></span>{l s='My credit slips'}</a></li>
            <li><a href="{$link->getPageLink('addresses', true)|escape:'html':'UTF-8'}" title="{l s='Addresses'}"><span class="icon_wrap"><i class="icon-cog icon-1x"></i></span>{l s='My addresses'}</a></li>
            <li><a href="{$link->getPageLink('identity', true)|escape:'html':'UTF-8'}" title="{l s='Information'}"><span class="icon_wrap"><i class="icon-vcard icon-1x"></i></span>{l s='My personal information'}</a></li>
{if $voucherAllowed || isset($HOOK_CUSTOMER_ACCOUNT) && $HOOK_CUSTOMER_ACCOUNT !=''}
            {if $voucherAllowed}
                <li><a href="{$link->getPageLink('discount', true)|escape:'html':'UTF-8'}" title="{l s='Vouchers'}"><span class="icon_wrap"><i class="icon-star-1 icon-1x"></i></span>{l s='My vouchers'}</a></li>
            {/if}
            {$HOOK_CUSTOMER_ACCOUNT}
{/if}
        </ul>
    </div>
</div>
