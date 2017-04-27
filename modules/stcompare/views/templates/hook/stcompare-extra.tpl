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

{if isset($comparator_max_item) && $comparator_max_item}
    <p class="buttons_bottom_block no-print">
    	<a class="add_to_compare" href="{$product_link|escape:'html':'UTF-8'}" data-id-product="{$product->id}" rel="nofollow" title="{l s='Add to compare' mod='stcompare'}" data-product-name="{$product->name|escape:'htmlall':'UTF-8'}" data-product-cover="{$link->getImageLink($product->link_rewrite, $product_cover, 'thumb_default')}" ><i class="icon-ajust icon-0x icon_btn icon-mar-lr2"></i><span>{l s='Add to compare' mod='stcompare'}</span></a>
    </p>
{/if}