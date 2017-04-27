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
<ul id="languages-block_mobile_menu" class="mo_advanced_mu_level_0 st_side_item">
	<li class="mo_advanced_ml_level_0 mo_advanced_ml_column">
		{foreach from=$languages key=k item=language name="languages"}
			{if $language.iso_code == $lang_iso}
			    <a href="javascript:;" rel="alternate" hreflang="{$language.iso_code|escape:'html':'UTF-8'}" class="mo_advanced_ma_level_0 advanced_ma_span">
			    	{if $display_flags!=1}<img src="{$img_lang_dir}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="16" height="11" class="mar_r4" />{/if}{if $display_flags!=2}{$language.name|regex_replace:"/\s\(.*\)$/":""}{/if}
			    </a>
			{/if}
		{/foreach}
		{if count($languages) > 1}
		<span class="opener">&nbsp;</span>
		<ul class="mo_advanced_mu_level_1 mo_advanced_sub_ul">
		{foreach from=$languages key=k item=language name="languages"}
    		{if $language.iso_code != $lang_iso}
			<li class="mo_advanced_ml_level_1 mo_advanced_sub_li">
				{assign var=indice_lang value=$language.id_lang}
				{if isset($lang_rewrite_urls.$indice_lang)}
					<a href="{$lang_rewrite_urls.$indice_lang|escape:'html':'UTF-8'}" title="{$language.name|escape:'html':'UTF-8'}" rel="alternate" hreflang="{$language.iso_code|escape:'html':'UTF-8'}" class="mo_advanced_ma_level_1 mo_advanced_sub_a">
				{else}
					<a href="{$link->getLanguageLink($language.id_lang)|escape:'html':'UTF-8'}" title="{$language.name|escape:'html':'UTF-8'}" rel="alternate" hreflang="{$language.iso_code|escape:'html':'UTF-8'}" class="mo_advanced_ma_level_1 mo_advanced_sub_a">
				{/if}
				    {if $display_flags!=1}<img src="{$img_lang_dir}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="16" height="11" class="mar_r4" />{/if}{if $display_flags!=2}{$language.name|regex_replace:"/\s\(.*\)$/":""}{/if}
				</a>
			</li>
			{/if}
		{/foreach}
		</ul>
		{/if}
	</li>
</ul>
<!-- /Block languages module -->