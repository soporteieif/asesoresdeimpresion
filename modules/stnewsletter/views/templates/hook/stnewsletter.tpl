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
{if isset($news_letter_array) && $news_letter_array|@count > 0}
	{assign var='has_news_letter_popup' value=0}
    {foreach $news_letter_array as $ec}
        {if $ec.location==4}
        	{if !$has_news_letter_popup}
        		{$has_news_letter_popup=1}
        		<div class="st_news_letter_popup_wrap">
        		<div id="st_news_letter_{$ec.id_st_news_letter}" class="st_news_letter_{$ec.id_st_news_letter} st_news_letter st_news_letter_popup {if $ec.text_align==2} text-center {elseif $ec.text_align==3} text-right {/if}">
         	      <div class="st_news_letter_box">
                    <div class="alert alert-danger hidden"></div>
                    <div class="alert alert-success hidden"></div>                   
	            	{if $ec.content}<div class="st_news_letter_content style_content">{$ec.content|stripslashes}</div>{/if}
	            	{if $ec.show_newsletter}
	            	<form action="{$content_dir}/modules/stnewsletter/stnewsletter-ajax.php" method="post" class="st_news_letter_form">
						<div class="form-group st_news_letter_form_inner" >
							<input class="inputNew form-control st_news_letter_input" type="text" name="email" size="18" value="{if isset($value) && $value}{$value}{/if}" placeholder="{if isset($sttheme.newsletter_label) && $sttheme.newsletter_label}{$sttheme.newsletter_label}{else}{l s='Your e-mail' mod='stnewsletter'}{/if}" />
			                <button type="submit" name="submitStNewsletter" class="btn btn-medium st_news_letter_submit">
			                    {l s='Subscribe' mod='stnewsletter'}
			                </button>
							<input type="hidden" name="action" value="0" />
						</div>
					</form>				
					{/if}
	            	</div>
					{if !$ec.show_popup}
					<div class="st_news_letter_do_not_show_outer clearfix">
                    	<div class="st_news_letter_do_not_show_inner">
                    		<input type="checkbox" name="st_news_letter_do_not_show" class="st_news_letter_do_not_show" autocomplete="off" /><label for="st_news_letter_do_not_show">{l s='Do not show again' mod='stnewsletter'}</label>
                    	</div>
					</div>
					{/if}	
	            </div>
	            </div>
		        <script type="text/javascript">
		        {literal}
		        jQuery(function($){
		            {/literal}{if !$ec.delay_popup}{literal}
		            	open_st_news_letter_{/literal}{$ec.id_st_news_letter}{literal}();
		            {/literal}{else}{literal}
		            	setTimeout(open_st_news_letter_{/literal}{$ec.id_st_news_letter}{literal}, {/literal}{$ec.delay_popup}{literal}*1000);
		            {/literal}{/if}{literal}
		            {/literal}{if !$ec.show_popup}{literal}
		            $('#st_news_letter_{/literal}{$ec.id_st_news_letter}{literal} .st_news_letter_do_not_show').change(function () {
			            if ($(this).is(':checked')) {
			                $.cookie("st_popup_do_not_show_{/literal}{$ec.id_st_news_letter}{literal}", '{/literal}{$ec.show_popup}{literal}', {
			                    'expires': {/literal}{if $ec.cookies_time}{$ec.cookies_time}{else}30{/if}{literal},
			                    'domain': '{/literal}{$news_letter_cookie_domain}{literal}',
			                    'path': '{/literal}{$news_letter_cookie_path}{literal}'
			                });
			            } else {
			                $.cookie("st_popup_do_not_show_{/literal}{$ec.id_st_news_letter}{literal}", '{/literal}{$ec.show_popup}{literal}', {
			                	'expires':-1,
			                    'domain': '{/literal}{$news_letter_cookie_domain}{literal}',
			                    'path': '{/literal}{$news_letter_cookie_path}{literal}'
			                }); 
			            }
			        });
		            {/literal}{/if}{literal}
		            {/literal}{if $ec.show_newsletter}{literal}
		            regested_popup = function() {
		                $.cookie("st_popup_do_not_show_{/literal}{$ec.id_st_news_letter}{literal}", '{/literal}{$ec.show_popup}{literal}', {
		                    'domain': '{/literal}{$news_letter_cookie_domain}{literal}',
		                    'path': '{/literal}{$news_letter_cookie_path}{literal}'
		                });
		            	return true;
		            };
                    {/literal}{if $ec.show_popup == 2}{literal}
                    $.cookie("st_popup_do_not_show_{/literal}{$ec.id_st_news_letter}{literal}", '{/literal}{$ec.show_popup}{literal}', {
	                    'expires': {/literal}{if $ec.cookies_time}{$ec.cookies_time}{else}30{/if}{literal},
	                    'domain': '{/literal}{$news_letter_cookie_domain}{literal}',
	                    'path': '{/literal}{$news_letter_cookie_path}{literal}'
	                });
                    {/literal}{/if}{literal}
		            {/literal}{/if}{literal}
		        });
		        var open_st_news_letter_{/literal}{$ec.id_st_news_letter}{literal} = function(){
		        	if (!!$.prototype.fancybox)
			        	$.fancybox({
			            	'padding': '0',
			            	'type': 'inline',
			            	{/literal}{if $ec.hide_on_mobile}{literal}
					        'beforeLoad' : function(){
					            if(st_responsive && $(window).width()<=768)
					                return false;
					        },
					        {/literal}{/if}{literal}
			                'href': '#st_news_letter_{/literal}{$ec.id_st_news_letter}{literal}'
			            });
		        };
		        {/literal}
		        </script>
        	{/if}
        {else}
	        {if isset($ec.is_full_width) && $ec.is_full_width}<div id="st_news_letter_container_{$ec.id_st_news_letter}" class="st_news_letter_container full_container {if $ec.hide_on_mobile}hidden-xs{/if} block"><div class="container"><div class="row"><div class="col-xs-12 col-sm-12">{/if}
	            <div id="st_news_letter_{$ec.id_st_news_letter}" class="st_news_letter_{$ec.id_st_news_letter} {if $ec.hide_on_mobile}hidden-xs{/if} {if !isset($ec.is_full_width) || !$ec.is_full_width}block{/if} st_news_letter {if isset($ec.is_column) && $ec.is_column} column_block {/if} {if $ec.text_align==2} text-center {elseif $ec.text_align==3} text-right {/if}">
	            	<div class="st_news_letter_box">
	            	{if $ec.content}<div class="st_news_letter_content style_content">{$ec.content|stripslashes}</div>{/if}
	            	{if $ec.show_newsletter}
                    <div class="alert alert-danger hidden"></div>
                    <div class="alert alert-success hidden"></div>
	            	<form action="{$content_dir}/modules/stnewsletter/stnewsletter-ajax.php" method="post" class="st_news_letter_form">
						<div class="form-group st_news_letter_form_inner" >
							<input class="inputNew form-control st_news_letter_input" type="text" name="email" size="18" value="{if isset($value) && $value}{$value}{/if}" placeholder="{if isset($sttheme.newsletter_label) && $sttheme.newsletter_label}{$sttheme.newsletter_label}{else}{l s='Your e-mail' mod='stnewsletter'}{/if}" />
			                <button type="submit" name="submitStNewsletter" class="btn btn-medium st_news_letter_submit">
			                    {l s='Subscribe' mod='stnewsletter'}
			                </button>
							<input type="hidden" name="action" value="0" />
						</div>
					</form>
					{/if}
					</div>
	            </div>
	        {if isset($ec.is_full_width) && $ec.is_full_width}</div></div></div></div>{/if}
        {/if}
    {/foreach}
{/if}
<script type="text/javascript">
	var wrongemailaddress_stnewsletter = "{l s='Invalid email address.' mod='stnewsletter' js=1}";
</script>