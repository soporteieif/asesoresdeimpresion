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
{if isset($custom_css) && $custom_css}
<style type="text/css">{$custom_css}</style>
{/if}
{if isset($countdown_active) && $countdown_active}
<script type="text/javascript">
//<![CDATA[
{literal}
var s_countdown_all = {/literal}{$display_all}{literal};
var s_countdown_id_products = [{/literal}{$id_products}{literal}];
jQuery(function($) {
    $('.s_countdown_block .s_countdown_timer, .c_countdown_timer').each(function() {
        var that = $(this), finalDate = $(this).data('countdown'), id = that.data('id-product'), countdown_pro = $(this).hasClass('countdown_pro');
        
        if (s_countdown_all || $.inArray(id, s_countdown_id_products) > -1)
        {
            that.countdown(finalDate).on('update.countdown', function(event) {
                {/literal}
                {if Configuration::get('ST_COUNTDOWN_STYLE') == 1}
                var format = '<div><i class="icon-clock"></i>%D '+((event.offset.totalDays == 1) ? "{l s='day' mod='stcountdown'}" : "{l s='days' mod='stcountdown'}")+' %H : %M : %S</div>';
                if(countdown_pro)
                    format = '%D '+((event.offset.totalDays == 1) ? "{l s='day' mod='stcountdown'}" : "{l s='days' mod='stcountdown'}")+' %H : %M : %S';
                {else}
                var format = '<div><span class="countdown_number">%D</span><span class="countdown_text">'+((event.offset.totalDays == 1) ? "{l s='day' mod='stcountdown'}" : "{l s='days' mod='stcountdown'}")+'</span></div><div><span class="countdown_number">%H</span><span class="countdown_text">{l s='hrs' mod='stcountdown'}</span></div><div><span class="countdown_number">%M</span><span class="countdown_text">{l s='min' mod='stcountdown'}</span></div><div><span class="countdown_number">%S</span><span class="countdown_text">{l s='sec' mod='stcountdown'}</span></div>';
                if(countdown_pro)
                    format = '%D '+((event.offset.totalDays == 1) ? "{l s='day' mod='stcountdown'}" : "{l s='days' mod='stcountdown'}")+' %H {l s='hrs' mod='stcountdown'} %M {l s='min' mod='stcountdown'} %S {l s='sec' mod='stcountdown'}';
                {/if}
                {literal}
                that.html(event.strftime(format));
            }).on('finish.countdown',function(event){
                window.location.reload(true);
            });
            if(countdown_pro)
                that.closest('.countdown_outer_box').addClass('counting');
            else
                that.addClass('counting');
        }
    });
    $('.s_countdown_block .s_countdown_perm, .c_countdown_perm, .countdown_pro_perm').each(function() {
        if (s_countdown_all || $.inArray($(this).data('id-product'), s_countdown_id_products) > -1)
            $(this).addClass('counting');
    });
});    
{/literal} 
//]]>
</script>
{/if}