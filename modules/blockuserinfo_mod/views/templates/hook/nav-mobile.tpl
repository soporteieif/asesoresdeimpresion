<!-- Block user information module NAV  -->
{assign var='welcome_logged' value=Configuration::get('STSN_WELCOME_LOGGED', $lang_id)}
{assign var='welcome_link' value=Configuration::get('STSN_WELCOME_LINK', $lang_id)}
{assign var='welcome' value=Configuration::get('STSN_WELCOME', $lang_id)}
<ul id="userinfo_mod_mobile_menu" class="mo_advanced_mu_level_0 st_side_item">
{if $is_logged}
	{if isset($welcome_logged) && trim($welcome_logged)}
    <li class="mo_advanced_ml_level_0 mo_advanced_ml_column">
        <a href="{if $welcome_link}{$welcome_link}{else}javascript:;{/if}" rel="nofollow" class="mo_advanced_ma_level_0 {if !$welcome_link} advanced_ma_span{/if}" title="{$welcome_logged}">
            {$welcome_logged}
        </a>
    </li>
    {/if}
    <li class="mo_advanced_ml_level_0 mo_advanced_ml_column">
        <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" rel="nofollow" class="mo_advanced_ma_level_0" title="{l s='View my customer account' mod='blockuserinfo_mod'}">
            {$cookie->customer_firstname} {$cookie->customer_lastname}
        </a>
    </li>
    <li class="mo_advanced_ml_level_0 mo_advanced_ml_column">
        <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" rel="nofollow" class="mo_advanced_ma_level_0" title="{l s='View my customer account' mod='blockuserinfo_mod'}">
            {l s='My Account' mod='blockuserinfo_mod'}
        </a>
    </li>
    <li class="mo_advanced_ml_level_0 mo_advanced_ml_column">
        <a href="{$link->getPageLink('index', true, NULL, 'mylogout')|escape:'html':'UTF-8'}" rel="nofollow" class="mo_advanced_ma_level_0" title="{l s='Log me out' mod='blockuserinfo_mod'}">
            {l s='Sign out' mod='blockuserinfo_mod'}
        </a>
    </li>
{else}
	{if isset($welcome) && trim($welcome)}
    <li class="mo_advanced_ml_level_0 mo_advanced_ml_column">
        <a href="{if $welcome_link}{$welcome_link}{else}javascript:;{/if}" rel="nofollow" class="mo_advanced_ma_level_0 {if !$welcome_link} advanced_ma_span{/if}" title="{$welcome}">
            {$welcome}
        </a>
    </li>
    {/if}
    <li class="mo_advanced_ml_level_0 mo_advanced_ml_column">
        <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='Log in to your customer account' mod='blockuserinfo_mod'}" rel="nofollow" class="mo_advanced_ma_level_0">
            {l s='Login' mod='blockuserinfo_mod'}
        </a>
    </li>
{/if}
</ul>
<!-- /Block usmodule NAV -->
