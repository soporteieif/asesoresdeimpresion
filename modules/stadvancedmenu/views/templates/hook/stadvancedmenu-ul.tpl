<ul class="st_advanced_menu clearfix advanced_mu_level_0">
	{if isset($stvertical) && count($stvertical)}
		{assign var='responsive_max' value=Configuration::get('STSN_RESPONSIVE_MAX')}
		<li id="st_advanced_menu_0" class="advanced_ml_level_0 {if Configuration::get('STSN_ADV_MENU_VER_OPEN')}menu_ver_open_{if $responsive_max==1}lg{else}md{/if}{/if}">
			<a id="st_advanced_ma_0" href="javascript:;" class="advanced_ma_level_0 is_parent" title="{l s='Categories' mod='stadvancedmenu'}" rel="nofollow"><i class="icon-menu"></i>{l s='Categories' mod='stadvancedmenu'}</a>
			<ul class="stadvancedmenu_sub stadvancedmenu_vertical col-md-3 {if Configuration::get('STSN_ADV_MENU_VER_SUB_STYLE')} stadvancedmenu_vertical_box {/if}">
				{foreach $stvertical as $mm}
					<li id="st_advanced_menu_{$mm.id_st_advanced_menu}" class="advanced_mv_level_1"><a id="st_advanced_ma{$mm.id_st_advanced_menu}" href="{if $mm.m_link}{$mm.m_link|escape:'html':'UTF-8'}{else}javascript:;{/if}" class="advanced_mv_item"{if !$adv_menu_title} title="{$mm.m_title|escape:'html':'UTF-8'}"{/if}{if $mm.nofollow} rel="nofollow"{/if}{if $mm.new_window} target="_blank"{/if}>{if $mm.icon_class}<i class="{$mm.icon_class}"></i>{/if}{$mm.m_name|escape:'html':'UTF-8'}{if isset($mm.column) && count($mm.column)}<i class="icon-right-dir-2"></i>{/if}{if $mm.cate_label}<span class="cate_label">{$mm.cate_label}</span>{/if}</a>
						{if isset($mm.column) && count($mm.column)}
							{include file="./stadvancedmenu-sub.tpl" is_mega_menu_vertical=1}
						{/if}
					</li>
				{/foreach}
			</ul>
		</li>
	{/if}
	{foreach $stmenu as $mm}
		{if $mm.hide_on_mobile == 2}{continue}{/if}
		<li id="st_advanced_menu_{$mm.id_st_advanced_menu}" class="advanced_ml_level_0 m_alignment_{$mm.alignment}">
			<a id="st_advanced_ma_{$mm.id_st_advanced_menu}" href="{if $mm.m_link}{$mm.m_link|escape:'html':'UTF-8'}{else}javascript:;{/if}" class="advanced_ma_level_0{if isset($mm.column) && count($mm.column)} is_parent{/if}{if $mm.m_icon} ma_icon{/if}" {if !$adv_menu_title} title="{$mm.m_title|escape:'html':'UTF-8'}"{/if}{if $mm.nofollow} rel="nofollow"{/if}{if $mm.new_window} target="_blank"{/if}>{if $mm.m_icon}{$mm.m_icon}{else}{if $mm.icon_class}<i class="{$mm.icon_class}"></i>{/if}{$mm.m_name|escape:'html':'UTF-8'}{/if}{if isset($mm.column) && count($mm.column)}{if isset($iscolumnmenu) && $iscolumnmenu}<i class="icon-right-dir-2"></i>{else}<i class="icon-down-dir-2"></i>{/if}{/if}{if $mm.cate_label}<span class="cate_label">{$mm.cate_label}</span>{/if}</a>
			{if isset($mm.column) && count($mm.column)}
				{include file="./stadvancedmenu-sub.tpl"}
			{/if}
		</li>
	{/foreach}
</ul>