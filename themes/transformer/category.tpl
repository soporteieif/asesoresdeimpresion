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
{include file="$tpl_dir./errors.tpl"}
{if isset($category)}
	{if $category->id AND $category->active}

        {if $display_category_title}
        <h1 class="heading page-heading{if (isset($subcategories) && !$products) || (isset($subcategories) && $products) || !isset($subcategories) && $products} product-listing{/if}"><span class="cat-name">{$category->name|escape:'html':'UTF-8'}{if isset($categoryNameComplement)}&nbsp;{$categoryNameComplement|escape:'html':'UTF-8'}{/if}</span></h1>
        {/if}

        {if isset($HOOK_CATEGORY_HEADER) && $HOOK_CATEGORY_HEADER}{$HOOK_CATEGORY_HEADER}{/if}

        {if $scenes || ($display_category_desc && $category->description) || ($display_category_image && $category->id_image)}
			<div class="content_scene_cat mar_b1">
                {if $scenes}
                    <!-- Scenes -->
                    {include file="$tpl_dir./scenes.tpl" scenes=$scenes}
                {else}
                    <!-- Category image -->
                    {if $display_category_image && $category->id_image}
                    <div class="align_center mar_b1">
                        <img src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')|escape:'html':'UTF-8'}" alt="{$category->name|escape:'html':'UTF-8'}" title="{$category->name|escape:'html':'UTF-8'}" id="categoryImage" class="img-responsive" width="{$categorySize.width}" height="{$categorySize.height}" />
                    </div>
                    {/if}
                {/if}
            
                {if $display_category_desc && $category->description}
                    <div class="cat_desc">
                    {if (!isset($sttheme.display_cate_desc_full) || !$sttheme.display_cate_desc_full) && strlen($category->description) > 120}
                        {if isset($description_short)}
                        <div id="category_description_short">{$description_short}</div>
                        {else}
                        <div id="category_description_short" style="height:1.5em;overflow:hidden;">{$category->description}</div>
                        {/if}
                        <div id="category_description_full" style="display:none">{$category->description}</div>
                        <a href="{$link->getCategoryLink($category->id_category, $category->link_rewrite)|escape:'html':'UTF-8'}" rel="nofollow" class="lnk_more go">{l s='More'}</a>
                    {else}
                        <div id="category_description_full">{$category->description}</div>
                    {/if}
                    </div>
                {/if}
            </div>
		{/if}

        {if $display_subcategory && isset($subcategories)}
        <!-- Subcategories -->
        <div id="subcategories">
            <h4 class="heading hidden">{l s='Subcategories'}</h4>
            <ul class="inline_list {if $display_subcategory==1 || $display_subcategory==3} subcate_grid_view row {else} subcate_list_view {/if}">
            {foreach from=$subcategories item=subcategory name=subcategories}
                <li class="clearfix {if $display_subcategory==1 || $display_subcategory==3} col-lg-{(12/$sttheme.categories_per_lg)|replace:'.':'-'} col-md-{(12/$sttheme.categories_per_md)|replace:'.':'-'} col-sm-{(12/$sttheme.categories_per_sm)|replace:'.':'-'} col-xs-{(12/$sttheme.categories_per_xs)|replace:'.':'-'} col-xxs-{(12/$sttheme.categories_per_xxs)|replace:'.':'-'}  {if $smarty.foreach.subcategories.iteration%$sttheme.categories_per_lg == 1} first-item-of-desktop-line{/if}{if $smarty.foreach.subcategories.iteration%$sttheme.categories_per_md == 1} first-item-of-line{/if}{if $smarty.foreach.subcategories.iteration%$sttheme.categories_per_sm == 1} first-item-of-tablet-line{/if}{if $smarty.foreach.subcategories.iteration%$sttheme.categories_per_xs == 1} first-item-of-mobile-line{/if}{if $smarty.foreach.subcategories.iteration%$sttheme.categories_per_xxs == 1} first-item-of-portrait-line{/if} {/if}">
                    <a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" title="{$subcategory.name|escape:'html':'UTF-8'}" class="img">
                        {if $subcategory.id_image}
                            <img class="replace-2x" src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'medium_default')|escape:'html':'UTF-8'}" alt="{$subcategory.name|escape:'html':'UTF-8'}" width="{$mediumSize.width}" height="{$mediumSize.height}" />
                        {else}
                            <img src="{$img_cat_dir}{$lang_iso}-default-medium_default.jpg" alt="{$subcategory.name|escape:'html':'UTF-8'}" width="{$mediumSize.width}" height="{$mediumSize.height}" />
                        {/if}
                    </a>
                    <h5><a class="subcategory-name" href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" title="{$subcategory.name|escape:'html':'UTF-8'}">{if $display_subcategory==2 || $display_subcategory==3}{$subcategory.name|escape:'html':'UTF-8'}{else}{$subcategory.name|truncate:25:'...'|escape:'html':'UTF-8'}{/if}</a></h5>
                    {if $subcategory.description}
                        <div class="subcat_desc">{$subcategory.description}</div>
                    {/if}
                </li>
            {/foreach}
            </ul>
        </div>
        {/if}

        {if !isset($subcategories) && $nb_products == 0}
           <p class="alert alert-warning category_no_products">{l s='There are no products in  this category'}</p>
           {hook h='displayAnywhere' location="5" mod='steasycontent' caller='steasycontent'}
        {/if}

		{if $products}
			<div class="content_sortPagiBar">
                <div class="top-pagination-content clearfix">
                    {include file="$tpl_dir./pagination.tpl"}
                </div>
            	<div class="sortPagiBar clearfix">
            		{include file="./product-sort.tpl"}
                	{include file="./nbr-product-page.tpl"}
				</div>
			</div>

			{include file="./product-list.tpl" products=$products}
            
			<div class="content_sortPagiBar">
                <div class="sortPagiBar sortPagiBarBottom clearfix">
                    {include file="./product-sort.tpl"}
                    {include file="./nbr-product-page.tpl"}
                </div>
				<div class="bottom-pagination-content clearfix">
                    {include file="./pagination.tpl" paginationId='bottom'}
				</div>
			</div>
		{/if}
        {if isset($HOOK_CATEGORY_FOOTER) && $HOOK_CATEGORY_FOOTER}{$HOOK_CATEGORY_FOOTER}{/if}
	{elseif $category->id}
		<p class="alert alert-warning">{l s='This category is currently unavailable.'}</p>
	{/if}
{/if}