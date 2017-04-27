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
<!-- Block languages module -->
{if !isset($languages_style) || !$languages_style}
	<dl id="languages-block-top" class="languages-block {if isset($istopbar) && $istopbar}{if isset($languages_position) && $languages_position} pull-right{else} pull-left{/if}{/if} dropdown_wrap top_bar_item">
		{foreach from=$languages key=k item=language name="languages"}
			{if $language.iso_code == $lang_iso}
				<dt class="dropdown_tri">
			        <div class="dropdown_tri_inner">
			            {if $display_flags!=1}<img src="{$img_lang_dir}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="16" height="11" class="mar_r4" />{/if}{if $display_flags!=2}{$language.name|regex_replace:"/\s\(.*\)$/":""}{/if}{if count($languages) > 1}<b></b>{/if}
			        </div>
			    </dt>
			{/if}
		{/foreach}
		{if count($languages) > 1}
		<dd class="dropdown_list">
			<ul id="first-languages" class="languages-block_ul">
				{foreach from=$languages key=k item=language name="languages"}
	        		{if $language.iso_code != $lang_iso}
					<li>
						{assign var=indice_lang value=$language.id_lang}
						{if isset($lang_rewrite_urls.$indice_lang)}
							<a href="{$lang_rewrite_urls.$indice_lang|escape:'html':'UTF-8'}" title="{$language.name|escape:'html':'UTF-8'}" rel="alternate" hreflang="{$language.iso_code|escape:'html':'UTF-8'}">
						{else}
							<a href="{$link->getLanguageLink($language.id_lang)|escape:'html':'UTF-8'}" title="{$language.name|escape:'html':'UTF-8'}" rel="alternate" hreflang="{$language.iso_code|escape:'html':'UTF-8'}">
						{/if}
						    {if $display_flags!=1}<img src="{$img_lang_dir}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="16" height="11" class="mar_r4" />{/if}{if $display_flags!=2}{$language.name|regex_replace:"/\s\(.*\)$/":""}{/if}
						</a>
					</li>
					{/if}
				{/foreach}
			</ul>
		</dd>
		{/if}
	</dl>
{else}
	{foreach from=$languages key=k item=language name="languages"}
		{if $language.iso_code != $lang_iso}
			{assign var=indice_lang value=$language.id_lang}
			{if isset($lang_rewrite_urls.$indice_lang)}
				<a href="{$lang_rewrite_urls.$indice_lang|escape:'html':'UTF-8'}" title="{$language.name|escape:'html':'UTF-8'}" rel="alternate" hreflang="{$language.iso_code|escape:'html':'UTF-8'}" class="top_bar_item header_item language_selector {if isset($istopbar) && $istopbar}{if isset($languages_position) && $languages_position} pull-right{else} pull-left{/if}{/if}">
			{else}
				<a href="{$link->getLanguageLink($language.id_lang)|escape:'html':'UTF-8'}" title="{$language.name|escape:'html':'UTF-8'}" rel="alternate" hreflang="{$language.iso_code|escape:'html':'UTF-8'}" class="top_bar_item header_item language_selector {if isset($istopbar) && $istopbar}{if isset($languages_position) && $languages_position} pull-right{else} pull-left{/if}{/if}">
			{/if}
			    {if $display_flags!=1}<img src="{$img_lang_dir}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="16" height="11" class="mar_r4" />{/if}{if $display_flags!=2}{$language.name|regex_replace:"/\s\(.*\)$/":""}{/if}
			</a>
		{else}
			<span class="top_bar_item header_item language_selector {if isset($istopbar) && $istopbar}{if isset($languages_position) && $languages_position} pull-right{else} pull-left{/if}{/if}">{if $display_flags!=1}<img src="{$img_lang_dir}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="16" height="11" class="mar_r4" />{/if}{if $display_flags!=2}{$language.name|regex_replace:"/\s\(.*\)$/":""}{/if}</span>
		{/if}
	{/foreach}
{/if}
<!-- /Block languages module -->