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
<!-- St Blog block categories -->
<div id="st_blog_block_categories" class="block">
	<p class="title_block">{l s='Blog categories' mod='stblogblockcategory'}</p>
	<div class="block_content categories_tree_block">
		<ul class="tree {if $isDhtml}dhtml{/if}">
        {foreach $categories as $category}
			{if $category@last}
				{include file='./category-tree-branch.tpl' node=$category last='true'}
			{else}
				{include file='./category-tree-branch.tpl' node=$category}
			{/if}
		{/foreach}
		</ul>
		{* Javascript moved here to fix bug #PSCFI-151 *}
		<script type="text/javascript">
		// <![CDATA[
			// we hide the tree only if JavaScript is activated
			$('#st_blog_block_categories ul.dhtml').hide();
		// ]]>
		</script>
	</div>
</div>
<!-- /St Blog block categories  -->