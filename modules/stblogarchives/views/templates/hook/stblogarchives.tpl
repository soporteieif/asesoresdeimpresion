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
<!-- St Blog block archives -->
{*
{l s='January'  mod='stblogarchives'}
{l s='February' mod='stblogarchives'}
{l s='March'    mod='stblogarchives'}
{l s='April'    mod='stblogarchives'}
{l s='May'      mod='stblogarchives'}
{l s='June'     mod='stblogarchives'}
{l s='July'     mod='stblogarchives'}
{l s='August'   mod='stblogarchives'}
{l s='September' mod='stblogarchives'}
{l s='October'  mod='stblogarchives'}
{l s='November' mod='stblogarchives'}
{l s='December' mod='stblogarchives'}
*}
<div id="st_blog_block_archives" class="block">
	<p class="title_block">{l s='Blog archives' mod='stblogarchives'}</p>
	<div class="block_content categories_tree_block">
		<ul class="tree dhtml">
        {foreach $archives as $archive}
            <li class="{if $archive@last} last {/if}"><a {if $current_year == $archive.Y}class="selected"{/if} href="{$link->getModuleLink('stblogarchives','default',['m'=>$archive.Y])|escape:'html'}" title="{$archive.Y}">{$archive.Y}</a>
                {if $archive.child && $archive.child|count}
    			<ul>
                {foreach $archive.child as $ar}
                    <li class="{if $archive@last} last {/if}"><a href="{$link->getModuleLink('stblogarchives','default',['m'=>$ar.Ym])|escape:'html'}" title="{l s=$ar.M mod='stblogarchives'}">{l s=$ar.M mod='stblogarchives'}</a></li>
                {/foreach}
                </ul>
                {/if}
            </li>
		{/foreach}
		</ul>
		{* Javascript moved here to fix bug #PSCFI-151 *}
		<script type="text/javascript">
		// <![CDATA[
			// we hide the tree only if JavaScript is activated
			$('#st_blog_block_archives ul.dhtml').hide();
		// ]]>
		</script>
	</div>
</div>
<!-- /St Blog block archives  -->