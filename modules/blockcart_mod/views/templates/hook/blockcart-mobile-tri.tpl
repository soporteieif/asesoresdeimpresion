{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!-- MODULE Block cart -->
<a id="shopping_cart_mobile" href="javascript:;" title="{l s='View my shopping cart' mod='blockcart_mod'}" rel="nofollow" class="shopping_cart mobile_bar_tri">
	<i class="icon-basket icon-1x icon_btn"></i>
	<span class="mobile_bar_tri_text">{l s='Cart' mod='blockcart_mod'}</span>
	<span class="ajax_cart_quantity amount_circle {if $cart_qties > 9} dozens {/if} constantly_show">{$cart_qties}</span>
</a>
<!-- /MODULE Block cart -->