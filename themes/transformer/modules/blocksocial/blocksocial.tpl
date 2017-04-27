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
<section id="social_networking_block" class="block col-sm-12 col-md-3">
    <div class="title_block"><div class="title_block_name">{l s='Follow us' mod='blocksocial'}</div><a href="javascript:;" class="opener dlm">&nbsp;</a></div>
    <div id="social_block" class="footer_block_content">
		<ul class="clearfix li_fl">
			{if isset($facebook_url) && $facebook_url!=''}
				<li class="facebook">
					<a target="_blank" href="{$facebook_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Facebook' mod='blocksocial'}">
						<i class="icon-facebook icon-large"></i>
					</a>
				</li>
			{/if}
			{if isset($twitter_url) && $twitter_url!=''}
				<li class="twitter">
					<a target="_blank" href="{$twitter_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Twitter' mod='blocksocial'}">
						<i class="icon-twitter  icon-large"></i>
					</a>
				</li>
			{/if}
			{if isset($rss_url) && $rss_url!=''}
				<li class="rss">
					<a target="_blank" href="{$rss_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Rss' mod='blocksocial'}">
						<i class="icon-rss icon-large"></i>
					</a>
				</li>
			{/if}
	        {if isset($youtube_url) && $youtube_url!=''}
	        	<li class="youtube">
	        		<a target="_blank" href="{$youtube_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Youtube' mod='blocksocial'}">
	        			<i class="icon-youtube icon-large"></i>
	        		</a>
	        	</li>
	        {/if}
	        {if isset($google_plus_url) && $google_plus_url!=''}
	        	<li class="google-plus">
	        		<a target="_blank" href="{$google_plus_url|escape:html:'UTF-8'}" rel="publisher" title="{l s='Google plus' mod='blocksocial'}">
	        			<i class="icon-pinterest icon-large"></i>
	        		</a>
	        	</li>
	        {/if}
	        {if isset($pinterest_url) && $pinterest_url!=''}
	        	<li class="pinterest">
	        		<a target="_blank" href="{$pinterest_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Pinterest' mod='blocksocial'}">
	        			<i class="icon-gplus icon-large"></i>
	        		</a>
	        	</li>
	        {/if}
	        {if isset($vimeo_url) && $vimeo_url != ''}
	        	<li class="vimeo">
	        		<a target="_blank" href="{$vimeo_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Vimeo' mod='blocksocial'}">
	        			<i class="icon-vimeo icon-large"></i>
	        		</a>
	        	</li>
	        {/if}
	        {if isset($instagram_url) && $instagram_url != ''}
	        	<li class="instagram">
	        		<a target="_blank" href="{$instagram_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Instagram' mod='blocksocial'}">
	        			<i class="icon-instagram icon-large"></i>
	        		</a>
	        	</li>
	        {/if}
		</ul>
	</div>
</section>