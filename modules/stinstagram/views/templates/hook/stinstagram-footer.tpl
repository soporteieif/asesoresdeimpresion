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
<!-- MODULE ST instagram -->
{capture name="column_slider"}{if isset($column_slider) && $column_slider}_column{/if}{/capture}
<div id="instagram_block_footer{$smarty.capture.column_slider}" class="instagram_block_footer {if $hide_mob == 1}hidden-sm{/if} col-sm-12 col-md-{if $footer_wide}{$footer_wide}{else}3{/if} block">
    <div class="title_block"><div class="title_block_name">{l s='Follow us on Instagram' mod='stinstagram'}</div><a href="javascript:;" class="opener dlm">&nbsp;</a></div>
    <div class="footer_block_content">
        {if $ins_show_profile}
            <div class="instagram_profile mar_b1"></div>
        {/if}
        <ul class="instagram_list li_fl clearfix ins_connecting"></ul>
        <div class="warning hidden">{l s='No pictures' mod='stinstagram'}</div>
    </div>
    <script type="text/javascript">
    //<![CDATA[
    {literal}
    jQuery(function($) { 
        {/literal}
        {if $ins_show_profile}
        {literal}
            $("#instagram_block_footer{/literal}{$smarty.capture.column_slider}{literal} .instagram_profile").pongstgrm({ 
                {/literal}
                accessToken: '{Configuration::get('ST_INSTAGRAM_ACCESS_TOKEN')}',
                show:             'profile',
                show_counts:             {$ins_show_counts},
                picture_size:     '64'
                {literal}
            });
        {/literal}
        {/if}
        {literal}
        $("#instagram_block_footer{/literal}{$smarty.capture.column_slider}{literal} .instagram_list").pongstgrm({ 
            {/literal}
            isfooter: 1,
            accessToken: '{Configuration::get('ST_INSTAGRAM_ACCESS_TOKEN')}',
            count: {if $ins_count}{$ins_count}{else}8{/if},
            show: {if $ins_show_image==1}'liked'{elseif $ins_show_image==2}'feed'{elseif $ins_show_image==3 && $ins_hash_tag}'{$ins_hash_tag}'{else}'recent'{/if}
            {literal}
        });
    });
    {/literal} 
    //]]>
    </script>
</div>
<!-- /MODULE ST instagram -->