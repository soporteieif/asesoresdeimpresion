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
<div id="instagram_block_center_container{$smarty.capture.column_slider}" class="instagram_block_center_container block {if $hide_mob == 1} hidden-xs{elseif $hide_mob == 2} visible-xs visible-xs-block{/if}">
{if isset($homeverybottom) && $homeverybottom && !$ins_items_fw}<div class="wide_container"><div class="container">{/if}
<section id="instagram_block_center{$smarty.capture.column_slider}" class="instagram_block_center{$smarty.capture.column_slider} {if isset($column_slider) && $column_slider} column_block {/if} section {if $ins_grid==1} ins_grid {/if}">
    {if $ins_title_position || (isset($column_slider) && $column_slider)}<h4 class="title_block {if (!isset($column_slider) || !$column_slider) && $ins_title_position} title_block_center {/if}"><span>{l s='Follow us on Instagram' mod='stinstagram'}</span></h4>{/if}
    <div id="instagram_block{$smarty.capture.column_slider}" class="block_content">
        {if $ins_grid}
        <ul class="instagram_con com_grid_view row ins_connecting">
        </ul>
        {else}
        <div class="instagram_con slides remove_after_init {if $ins_direction_nav>1} owl-navigation-lr {if $ins_direction_nav==4} owl-navigation-circle {else} owl-navigation-rectangle {/if} {elseif $ins_direction_nav==1} owl-navigation-tr{/if} ins_connecting">
        </div>
        {/if}
        <div class="warning hidden">{l s='No pictures' mod='stinstagram'}</div>
    </div>
    <script type="text/javascript">
    //<![CDATA[
    {literal}
    jQuery(function($) { 
        $("#instagram_block{/literal}{$smarty.capture.column_slider}{literal} .instagram_con").pongstgrm({ 
            {/literal}
            accessToken: '{Configuration::get('ST_INSTAGRAM_ACCESS_TOKEN')}',
            count: {if $ins_count}{$ins_count}{else}8{/if},
            grid: {if $ins_grid}1{else}0{/if},
            likes: {if $ins_show_likes}1{else}0{/if},       
            comments: {if $ins_show_comments}1{else}0{/if},    
            username: {if $ins_show_username}1{else}0{/if},   
            timestamp: {if $ins_show_timestamp}1{else}0{/if},   
            caption: {$ins_show_caption},   
            ins_lenght_of_caption: {$ins_lenght_of_caption},   
            image_size: {$ins_image_size},
            effects: {$ins_hover_effect},
            click_action: {$ins_click_action},
            {literal}
            owl: {
            {/literal}
                autoPlay : {if $ins_slideshow}{$ins_slider_s_speed|default:5000}{else}false{/if},
                navigation: {if $ins_direction_nav}true{else}false{/if},
                pagination: {if $ins_control_nav}true{else}false{/if},
                rewindNav: {if $ins_rewind_nav}true{else}false{/if},
                scrollPerPage: {if $ins_move}true{else}false{/if},
                {literal}
                itemsCustom : [
                    {/literal}
                    {if $sttheme.responsive && !$sttheme.version_switching}
                    {if isset($homeverybottom) && $homeverybottom && $ins_items_fw}[{if $sttheme.responsive_max==2}1660{else}1420{/if}, {$ins_items_fw}],{/if}
                    {if $sttheme.responsive_max==2}{literal}[1420, {/literal}{$ins_items_xl}{literal}],{/literal}{/if}
                    {if $sttheme.responsive_max>=1}{literal}[1180, {/literal}{$ins_items_lg}{literal}],{/literal}{/if}
                    {literal}
                    [972, {/literal}{$ins_items_md}{literal}],
                    [748, {/literal}{$ins_items_sm}{literal}],
                    [460, {/literal}{$ins_items_xs}{literal}],
                    [0, {/literal}{$ins_items_xxs}{literal}]
                    {/literal}{else}{literal}
                    [0, {/literal}{if $sttheme.responsive_max==2}{$ins_items_xl}{elseif $sttheme.responsive_max==1}{$ins_items_lg}{else}{$ins_items_md}{/if}{literal}]
                    {/literal}
                    {/if}
                    {literal} 
                ],
                {/literal}
                slideSpeed: {$ins_slider_a_speed|default:200},
                stopOnHover: {if $ins_slider_pause_on_hover}true{else}false{/if}
            {literal}
            },
            {/literal}
            ins_items_xl       : {if $ins_items_xl}{$ins_items_xl}{else}6{/if},
            ins_items_lg       : {if $ins_items_lg}{$ins_items_lg}{else}5{/if},
            ins_items_md       : {if $ins_items_md}{$ins_items_md}{else}4{/if},
            ins_items_sm       : {if $ins_items_sm}{$ins_items_sm}{else}3{/if},
            ins_items_xs       : {if $ins_items_xs}{$ins_items_xs}{else}2{/if},
            ins_items_xxs      : {if $ins_items_xxs}{$ins_items_xxs}{else}1{/if},
            show: {if $ins_show_image==1}'liked'{elseif $ins_show_image==2}'feed'{elseif $ins_show_image==3 && $ins_hash_tag}'{$ins_hash_tag}'{else}'recent'{/if}
            {literal}
        });
    });
    {/literal} 
    //]]>
    </script>
</section>
{if isset($homeverybottom) && $homeverybottom && !$ins_items_fw}</div></div>{/if}
</div>
<!-- /MODULE ST instagram -->