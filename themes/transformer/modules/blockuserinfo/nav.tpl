<!-- Block user information module NAV  -->
{assign var='userinfo_position' value=Configuration::get('ST_USERINFO_POSITION')}
{assign var='welcome_logged' value=Configuration::get('STSN_WELCOME_LOGGED', $lang_id)}
{assign var='welcome_link' value=Configuration::get('STSN_WELCOME_LINK', $lang_id)}
{assign var='welcome' value=Configuration::get('STSN_WELCOME', $lang_id)}
<div id="header_user_info" class="header_user_info {if isset($userinfo_position)}{if $userinfo_position} pull-left{else} pull-right{/if}{/if} clearfix">
	{if $is_logged}
		{if isset($welcome_logged) && trim($welcome_logged)}{if $welcome_link}<a href="{$welcome_link}" class="welcome header_item" rel="nofollow">{else}<span class="welcome header_item">{/if}{$welcome_logged}{if $welcome_link}</a>{else}</span>{/if}{/if}
		<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="header_item account" rel="nofollow">{$cookie->customer_firstname} {$cookie->customer_lastname}</a>
		<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="header_item my_account_link" rel="nofollow">{l s='My Account' mod='blockuserinfo'}</a>
		<a class="header_item logout" href="{$link->getPageLink('index', true, NULL, "mylogout")|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Log me out' mod='blockuserinfo'}">
			{l s='Sign out' mod='blockuserinfo'}
		</a>
	{else}
		{if isset($welcome) && trim($welcome)}{if $welcome_link}<a href="{$welcome_link}" class="header_item welcome" rel="nofollow">{else}<span class="welcome header_item">{/if}{$welcome}{if $welcome_link}</a>{else}</span>{/if}{/if}
		<a class="header_item login" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Log in to your customer account' mod='blockuserinfo'}">
			{l s='Login' mod='blockuserinfo'}
		</a>
		<a class="header_item sing_up" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Log in to your customer account' mod='blockuserinfo'}">
			{l s='Sign Up' mod='blockuserinfo'}
		</a>
	{/if}
</div>
<!-- /Block usmodule NAV -->