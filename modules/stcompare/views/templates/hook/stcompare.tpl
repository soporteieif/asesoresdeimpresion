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
<!-- MODULE st compare -->
{if $comparator_max_item}
<section id="rightbar_compare" class="rightbar_wrap">
    <a id="rightbar-product_compare" class="rightbar_tri icon_wrap" href="{$link->getPageLink('products-comparison')|escape:'html'}" title="{l s="Compare Products" mod='stcompare'}">
        <i class="icon-ajust icon-0x icon_btn"></i>
        <span class="icon_text">{l s='Compare' mod='stcompare'}</span>
        <span class="compare_quantity amount_circle {if !count($compared_products)} simple_hidden {/if}{if count($compared_products) > 9} dozens {/if}">{count($compared_products)}</span>
    </a>
</section>
{/if}
<!-- /MODULE st compare -->