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
{if isset($HOOK_HOME_TOP) && $HOOK_HOME_TOP|trim}{$HOOK_HOME_TOP}{/if}

{if isset($HOOK_HOME) && $HOOK_HOME|trim}
	{$HOOK_HOME}
{/if}
{if $HOOK_HOME_SECONDARY_LEFT || (isset($HOOK_HOME_SECONDARY_RIGHT) && $HOOK_HOME_SECONDARY_RIGHT)}
<div id="home_secondary_row" class="row">
    <div id="home_secondary_left" class="{if (!isset($HOOK_HOME_SECONDARY_RIGHT) || !$HOOK_HOME_SECONDARY_RIGHT) || (isset($HOOK_LEFT_COLUMN) && trim($HOOK_LEFT_COLUMN) && isset($HOOK_RIGHT_COLUMN) && trim($HOOK_RIGHT_COLUMN))} col-xs-12 col-md-12 {else} col-xs-12 col-sm-9 col-md-9 {/if}">
        {$HOOK_HOME_SECONDARY_LEFT}
    </div>
    {if !(isset($HOOK_LEFT_COLUMN) && trim($HOOK_LEFT_COLUMN) && isset($HOOK_RIGHT_COLUMN) && trim($HOOK_RIGHT_COLUMN)) && (isset($HOOK_HOME_SECONDARY_RIGHT) && $HOOK_HOME_SECONDARY_RIGHT)}
    <div id="home_secondary_right" class="col-sm-3 col-md-3 hidden-xs">
        {$HOOK_HOME_SECONDARY_RIGHT}
    </div>
    {/if}
</div>
{/if}
{capture name="displayHomeTertiaryLeft"}{hook h="displayHomeTertiaryLeft"}{/capture}
{capture name="displayHomeTertiaryRight"}{hook h="displayHomeTertiaryRight"}{/capture}
{if $smarty.capture.displayHomeTertiaryLeft|trim || $smarty.capture.displayHomeTertiaryRight|trim}
<div id="home_tertiary_row" class="row">
    <div id="home_tertiary_left" class="col-xs-12 col-sm-6 col-md-6">
        {$smarty.capture.displayHomeTertiaryLeft}
    </div>
    <div id="home_tertiary_right" class="col-xs-12 col-sm-6 col-md-6">
        {$smarty.capture.displayHomeTertiaryRight}
    </div>
</div>
{/if}
{if isset($HOOK_HOME_BOTTOM) && $HOOK_HOME_BOTTOM|trim}{$HOOK_HOME_BOTTOM}{/if}

{if isset($HOOK_HOME_TAB_CONTENT) && $HOOK_HOME_TAB_CONTENT|trim}
    {if isset($HOOK_HOME_TAB) && $HOOK_HOME_TAB|trim}
        <ul id="home-page-tabs" class="li_fl clearfix">
            {$HOOK_HOME_TAB}
        </ul>
    {/if}
    <div class="tab-content">{$HOOK_HOME_TAB_CONTENT}</div>
{/if}