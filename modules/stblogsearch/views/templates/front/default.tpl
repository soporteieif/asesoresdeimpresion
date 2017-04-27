{*
* 2007-2012 PrestaShop
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
*  @copyright  2007-2012 PrestaShop SA
*  @version  Release: $Revision: 17677 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{include file="$tpl_dir./errors.tpl"}
<div class="clearfix">
<h1 class="page-heading pull-left">{l s='Search for' mod='stblogsearch'}&nbsp;<span class="lighter">"{$search_tag}"</span></h1>
</div>
<div class="alert alert-warning">
	{include file="./count.tpl"}
</div>
{if $nb_products > 0}		
    <div id="viewmode" class="">
    {if $category_layouts==2}
	     {include file="$module_dir/stblog/views/templates/front/blogs-list-medium.tpl" blogs=$blogs}
    {elseif $category_layouts==3}
	     {include file="$module_dir/stblog/views/templates/front/blogs-list-grid.tpl" blogs=$blogs}
    {else}
	     {include file="$module_dir/stblog/views/templates/front/blogs-list-large.tpl" blogs=$blogs}
    {/if}
    </div>
	
	<div class="content_sortPagiBar">
        <div class="paginationBar paginationBarBottom clearfix">
		    {include file="./pagination.tpl"}
        </div>
	</div>
{/if}