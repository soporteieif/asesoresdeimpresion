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
{if isset($sttheme)}
<script type="text/javascript">
// <![CDATA[
	var st_responsive = {$sttheme.responsive};
	var st_responsive_max = {$sttheme.responsive_max};
	var st_addtocart_animation = {if isset($sttheme['addtocart_animation']) && $sttheme['addtocart_animation']}{$sttheme.addtocart_animation}{else}0{/if};
	var st_sticky_menu = {if isset($sttheme['sticky_menu']) && $sttheme['sticky_menu']}{$sttheme['sticky_menu']}{else}0{/if};
	var st_sticky_adv = {if isset($sttheme['sticky_adv']) && $sttheme['sticky_adv']}{$sttheme['sticky_adv']}{else}0{/if};
	var st_sticky_mobile_header = {if isset($sttheme['sticky_mobile_header']) && $sttheme['sticky_mobile_header']}{$sttheme['sticky_mobile_header']}{else}0{/if};
    var st_is_rtl = {if isset($sttheme['is_rtl']) && $sttheme['is_rtl']}true{else}false{/if};
    var zoom_type = {if isset($sttheme['zoom_type'])}{$sttheme['zoom_type']}{else}2{/if};
    var st_retina = {if isset($sttheme['retina']) && $sttheme['retina']}true{else}false{/if};
    var st_sticky_mobile_header_height = {if isset($sttheme['sticky_mobile_header_height']) && $sttheme['sticky_mobile_header_height']}{$sttheme['sticky_mobile_header_height']}{else}60{/if};
//]]>
</script>
{if isset($sttheme.version_switching) && $sttheme.version_switching==1}
<style type="text/css">{literal}#body_wrapper{min-width:992px;margin-right:auto;margin-left:auto;}{/literal}</style>
{/if}
{/if}
