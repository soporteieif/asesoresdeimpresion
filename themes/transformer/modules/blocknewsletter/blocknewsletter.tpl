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
<section id="newsletter_block_left" class="block col-sm-12 col-md-3">
    <div class="title_block"><div class="title_block_name">{l s='Newsletter' mod='blocknewsletter'}</div><a href="javascript:;" class="opener dlm">&nbsp;</a></div>
	<div id="newsletter_form" class="footer_block_content">
		{if isset($msg) && $msg}
			<p class="{if $nw_error}alert alert-danger{else}alert alert-success{/if}">{$msg}</p>
	        <script type="text/javascript">
	        {literal}
	        jQuery(function($){
	            window.setTimeout(function(){
	                $('body,html').animate({
	            		scrollTop: $('#newsletter_form').offset().top,
	            	}, 'fast');
	            },1000);
	        });
	        {/literal}
	        </script>
		{/if}
		<form action="{$link->getPageLink('index', true, null, null, false, null, true)|escape:'html':'UTF-8'}" method="post">
			<div class="form-group{if isset($msg) && $msg } {if $nw_error}form-error{else}form-ok{/if}{/if}" >
	        	<label>{l s='Subscribe to our newsletter' mod='blocknewsletter'}</label>
				<input class="inputNew form-control grey newsletter-input" id="newsletter-input" type="text" name="email" size="18" value="{if isset($value) && $value}{$value}{/if}" placeholder="{if isset($sttheme.newsletter_label) && $sttheme.newsletter_label}{$sttheme.newsletter_label}{else}{l s='Your e-mail' mod='blocknewsletter'}{/if}" />
                <button type="submit" name="submitNewsletter" class="btn btn-medium">
                    {l s='Subscribe' mod='blocknewsletter'}
                </button>
				<input type="hidden" name="action" value="0" />
			</div>
		</form>
	</div>
	{hook h="displayBlockNewsletterBottom" from='blocknewsletter'}
</section>
<!-- /Block Newsletter module-->
{strip}
{addJsDefL name=wrongemailaddress_blocknewsletter}{l s='Invalid email address.' mod='blocknewsletter' js=1}{/addJsDefL}
{/strip}