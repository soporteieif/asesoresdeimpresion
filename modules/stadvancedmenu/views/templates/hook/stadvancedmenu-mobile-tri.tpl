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
{assign var='menu_icon_with_text' value=Configuration::get('STSN_MENU_ICON_WITH_TEXT')}
<a id="stmobileadvancedmenu_tri" class="mobile_bar_tri{if $menu_icon_with_text && $menu_icon_with_text==1} with_text{/if}" href="javascript:;" title="{l s='Menu' mod='stadvancedmenu'}">
    <i class="icon-menu icon-1x"></i>
    <span class="mobile_bar_tri_text">{l s="Menu" mod='stadvancedmenu'}</span>
</a>