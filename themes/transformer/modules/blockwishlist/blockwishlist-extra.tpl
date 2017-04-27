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
<div class="buttons_bottom_block no-print">
{if $wishlists|count == 1}
	<a id="wishlist_button_nopop" href="#" onclick="WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', $('#idCombination').val(), $('#quantity_wanted').val(), this); return false;" rel="nofollow" data-pid="{$id_product|intval}"  title="{l s='Add to my wishlist' mod='blockwishlist'}" class="addToWishlist wishlistProd_{$id_product|intval}"><i class="icon-heart icon-0x icon_btn icon-mar-lr2"></i><span>{l s='Add to wishlist' mod='blockwishlist'}</span></a>
{else}
	{foreach name=wl from=$wishlists item=wishlist}
		{if $smarty.foreach.wl.first}
			<a class="addToWishlist wishlist_popover wishlistProd_{$id_product|intval}" id="wishlist_button" tabindex="0" data-pid="{$id_product|intval}" data-toggle="popover" data-trigger="focus" title="{l s='Wishlist' mod='blockwishlist'}" data-placement="bottom"><i class="icon-heart icon-0x icon_btn icon-mar-lr2"></i><span>{l s='Add to wishlist' mod='blockwishlist'}</span></a>
				<div class="hidden" id="popover-content">
					<table class="table" border="1">
						<tbody>
		{/if}
							<tr title="{$wishlist.name}" value="{$wishlist.id_wishlist}" onclick="WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', $('#idCombination').val(), $('#quantity_wanted').val(), document.getElementById('wishlist_button'), '{$wishlist.id_wishlist}');">
								<td>
									{l s='Add to %s' sprintf=[$wishlist.name] mod='blockwishlist'}
								</td>
							</tr>
		{if $smarty.foreach.wl.last}
					</tbody>
				</table>
			</div>
		{/if}
	{foreachelse}
		<a href="#" id="wishlist_button_nopop" onclick="WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', $('#idCombination').val(), $('#quantity_wanted').val(), this); return false;" rel="nofollow" data-pid="{$id_product|intval}"  title="{l s='Add to my wishlist' mod='blockwishlist'}" class="addToWishlist wishlistProd_{$id_product|intval}">
			<i class="icon-heart icon-0x icon_btn icon-mar-lr2"></i><span>{l s='Add to wishlist' mod='blockwishlist'}</span>
		</a>
	{/foreach}
{/if}
</div>