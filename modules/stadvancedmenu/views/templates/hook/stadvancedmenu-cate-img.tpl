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
<a href="{$menu_cate.link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$menu_cate.name|escape:'html':'UTF-8'}"{/if} class="advanced_menu_cate_img"{if $nofollow} rel="nofollow"{/if}{if $new_window} target="_blank"{/if}>
{if $menu_cate.id_image}
    <img src="{$link->getCatImageLink($menu_cate.link_rewrite, $menu_cate.id_image, 'category_default')|escape:'html'}" alt="{$menu_cate.name|escape:'html':'UTF-8'}" width="{$categorySize.width}" height="{$categorySize.height}" class="img-responsive" />
{else}
    <img src="{$img_cat_dir}default-category_default.jpg" alt="" width="{$categorySize.width}" height="{$categorySize.height}" class="img-responsive" />
{/if}
</a>