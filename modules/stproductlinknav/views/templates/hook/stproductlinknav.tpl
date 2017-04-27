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
<!-- MODULE St Product Link Nav  -->
<section class="product_link_nav rightbar_wrap">
    {assign var='product_link' value=$link->getProductLink($nav_product.id_product, $nav_product.link_rewrite, $nav_product.category, $nav_product.ean13)} 
    <a id="rightbar-product_link_nav_{$nav}" class="rightbar_tri icon_wrap" href="{$product_link|escape:'html'}" title="{if $nav=='prev'}{l s='Previous product' mod='stproductlinknav'}{/if}{if $nav=='next'}{l s='Next product' mod='stproductlinknav'}{/if}"><i class="icon-{if $nav=='prev'}left{/if}{if $nav=='next'}right{/if} icon-0x"></i><span class="icon_text">{if $nav=='prev'}{l s='Prev' mod='stproductlinknav'}{/if}{if $nav=='next'}{l s='Next' mod='stproductlinknav'}{/if}</span></a>
    <div class="rightbar_content">
        <a href="{$product_link|escape:'html'}" title="{$nav_product.name|escape:html:'UTF-8'}" rel="nofollow"><img src="{$link->getImageLink($nav_product.link_rewrite, $nav_product.id_product|cat:'-'|cat:$nav_product.id_image, 'medium_default')}" alt="{$nav_product.name|escape:html:'UTF-8'}" width="{$mediumSize.width}" height="{$mediumSize.height}" class="img-polaroid product_link_nav_preview" /></a>
    </div>
</section>
<!-- /MODULE St Product Link Nav -->