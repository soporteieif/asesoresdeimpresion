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
<!-- Block Newsletter module-->
{if isset($news_letter_array) && $news_letter_array|@count > 0}
    {foreach $news_letter_array as $ec}
		<section id="st_news_letter_{$ec.id_st_news_letter}" class="st_news_letter_{$ec.id_st_news_letter} {if $ec.hide_on_mobile} hidden-xs{/if} block col-sm-12 col-md-{if $ec.span}{$ec.span}{else}3{/if}">
		    {if $ec.span && $ec.span!=12}
    		<div class="title_block"><div class="title_block_name">{l s='Newsletter' mod='stnewsletter'}</div><a href="javascript:;" class="opener dlm">&nbsp;</a></div>
			{/if}
			<div class="footer_block_content {if $ec.span && $ec.span==12}keep_open{/if} {if $ec.text_align==2} text-center {elseif $ec.text_align==3} text-right {/if}">
				<div class="st_news_letter_box">
            	{if $ec.content}<div class="st_news_letter_content style_content">{$ec.content|stripslashes}</div>{/if}
            	{if $ec.show_newsletter}
            	<div class="alert alert-danger hidden"></div>
                <div class="alert alert-success hidden"></div>
            	<form action="{$content_dir}/modules/stnewsletter/stnewsletter-ajax.php" method="post" class="st_news_letter_form">
					<div class="form-group st_news_letter_form_inner" >
						<input class="inputNew form-control st_news_letter_input" type="text" name="email" size="18" value="{if isset($value) && $value}{$value}{/if}" placeholder="{if isset($sttheme.newsletter_label) && $sttheme.newsletter_label}{$sttheme.newsletter_label}{else}{l s='Your e-mail' mod='stnewsletter'}{/if}" />
		                <button type="submit" name="submitStNewsletter" class="btn btn-medium st_news_letter_submit">
		                    {l s='Go!' mod='stnewsletter'}
		                </button>
						<input type="hidden" name="action" value="0" />
					</div>
				</form>
				{/if}
				</div>
			</div>
		</section>
    {/foreach}
{/if}
<!-- /Block Newsletter module-->
<script type="text/javascript">
	var wrongemailaddress_stnewsletter = "{l s='Invalid email address.' mod='stnewsletter' js=1}";
</script>