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
<!-- MODULE Twitter Embedded Timelines  -->
<section class="col-sm-12 col-md-{if $wide_on_footer}{$wide_on_footer}{else}3{/if} block">
    <div class="title_block"><div class="title_block_name">{if $tw_block_title}{$tw_block_title|escape:html:'UTF-8'}{else}{l s='Latest tweets' mod='sttwitterembeddedtimelines'}{/if}</div><a href="javascript:;" class="opener dlm">&nbsp;</a></div>
    <div class="footer_block_content">
<a class="twitter-timeline"  href="https://twitter.com/{$name}"  data-widget-id="{$widget_id}" {if $height}height="{$height}"{/if} {if $link_color}data-link-color="{$link_color}"{/if} {if $theme}data-theme="{$theme}"{/if} data-chrome="{if $noheader}noheader {/if}{if $nofooter}nofooter {/if}{if $noborders}noborders {/if}{if $noscrollbar}noscrollbar {/if}{if $transparent}transparent {/if}" {if $border_color}data-border-color="{$border_color}"{/if} {if $limit}data-tweet-limit="{$limit}"{/if} {if $language}lang="{$language}"{/if} {if $screen_name}data-screen-name="{$screen_name}"{/if} data-show-replies="{if $show_replies}true{else}false{/if}" >{l s='Tweets by' mod='sttwitterembeddedtimelines'} @{$name}</a>
<script>
// <![CDATA[
{literal}
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
{/literal}
//]]>
</script>
    </div>
</section>
<!-- /MODULE Twitter Embedded Timelines -->