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
{assign var='slide_lr_column' value=Configuration::get('STSN_SLIDE_LR_COLUMN')}
{if !isset($content_only) || !$content_only}
					</div><!-- #center_column -->
					{if isset($right_column_size) && !empty($right_column_size)}
						<div id="right_column" class="{if $slide_lr_column} col-xxs-8 col-xs-6{else} col-xs-12{/if} col-sm-{$right_column_size|intval} column">{$HOOK_RIGHT_COLUMN}</div>
					{/if}
					</div><!-- .row -->
					{capture name="displayBottomColumn"}{hook h="displayBottomColumn"}{/capture}
					{if isset($smarty.capture.displayBottomColumn) && $smarty.capture.displayBottomColumn|trim}
						<div id="bottom_row" class="row">
							<div id="bottom_column" class="col-xs-12 col-sm-12">{$smarty.capture.displayBottomColumn}</div>
						</div>
	            	{/if}
				</div><!-- #columns -->
			</div><!-- .columns-container -->
			{hook h="displayHomeVeryBottom"}
			<div class="main_content_area_footer"><div class="wide_container"></div></div>
			</div><!-- #main_content_area -->
			<!-- Footer -->
			<footer id="footer" class="footer-container">
				{if isset($HOOK_FOOTER_TOP) && $HOOK_FOOTER_TOP|trim}
	            <section id="footer-top">
					<div class="wide_container">
			            <div class="container">
			                <div class="row">
			                    {$HOOK_FOOTER_TOP}
			                </div>
						</div>
		            </div>
	            </section>
	            {/if}
	            {if isset($HOOK_FOOTER) && $HOOK_FOOTER|trim}
	            <section id="footer-primary">
					<div class="wide_container">
						<div class="container">
			                <div class="row">
							    {$HOOK_FOOTER}
			                </div>
						</div>
		            </div>
	            </section>
	            {/if}
	            {if isset($HOOK_FOOTER_SECONDARY) && $HOOK_FOOTER_SECONDARY|trim}
	            <section id="footer-secondary">
					<div class="wide_container">
						<div class="container">
			                <div class="row">
							    {$HOOK_FOOTER_SECONDARY}
			                </div>
						</div>
		            </div>
	            </section>
	            {/if}

	            {capture name="st_footer_bottom_left"}{if isset($HOOK_FOOTER_BOTTOM_LEFT) && $HOOK_FOOTER_BOTTOM_LEFT|trim}{$HOOK_FOOTER_BOTTOM_LEFT}{else}{hook h='displayFooterBottomLeft'}{/if}{/capture}
	            {capture name="st_footer_bottom_right"}{if isset($HOOK_FOOTER_BOTTOM_RIGHT) && $HOOK_FOOTER_BOTTOM_RIGHT|trim}{$HOOK_FOOTER_BOTTOM_RIGHT}{else}{hook h='displayFooterBottomRight'}{/if}{/capture}

	            {if (isset($sttheme.copyright_text) && $sttheme.copyright_text) 
	            || $smarty.capture.st_footer_bottom_left|trim 
	            || $smarty.capture.st_footer_bottom_right|trim
	            || (isset($sttheme.footer_img_src) && $sttheme.footer_img_src) 
	            || (isset($sttheme.responsive) && $sttheme.responsive && isset($sttheme.enabled_version_swithing) && $sttheme.enabled_version_swithing)}
	            <div id="footer_info" class="bottom-footer {if Configuration::get('STSN_F_INFO_CENTER')} fotter_bottom_center {/if}">
					<div class="wide_container">
		    			<div class="container">
		                    <div class="row" data-version="{$smarty.const._PS_VERSION_|replace:'.':'-'}{if isset($sttheme.theme_version)}-{$sttheme.theme_version|replace:'.':'-'}{/if}">
		                        <div class="col-xs-12 col-sm-12 clearfix">  
		                        	{if (isset($sttheme.footer_img_src) && $sttheme.footer_img_src) || $smarty.capture.st_footer_bottom_right|trim} 
			                        <aside id="footer_bottom_right">
			                        	{if isset($sttheme.footer_img_src) && $sttheme.footer_img_src}    
				                            <img id="paymants_logos" src="{$sttheme.footer_img_src}" alt="{l s='Payment methods'}" />
				                        {/if}
			                            {$smarty.capture.st_footer_bottom_right} 
			                        </aside>
			                        {/if}
			                        <aside id="footer_bottom_left">
			                        {if isset($sttheme.copyright_text)}<span id="copyright_text">{$sttheme.copyright_text}</span>{/if}
	            					{$smarty.capture.st_footer_bottom_left} 
	            					</aside>     
		                        </div>
		                    </div>
		                    {if isset($sttheme.responsive) && $sttheme.responsive && isset($sttheme.enabled_version_swithing) && $sttheme.enabled_version_swithing}
		                    <div id="version_switching" class="row">
		                        <div class="col-xs-12 col-sm-12">
		                            {if $sttheme.version_switching==0}<a href="javascript:;" rel="nofollow" class="version_switching vs_desktop {if !$sttheme.version_switching} active {/if}" title="{l s='Switch to Desktop Version'}"><i class="icon-monitor icon-mar-lr2"></i>{l s='Desktop'}</a>{/if}
		                            {if $sttheme.version_switching==1}<a href="javascript:;" rel="nofollow" class="version_switching vs_mobile {if $sttheme.version_switching} active {/if}" title="{l s='Switch to Mobile Version'}"><i class="icon-mobile icon-mar-lr2"></i>{l s='Mobile'}</a>{/if}
		                        </div>
		                    </div>
		                    {/if}
		                </div>
		            </div>
	            </div>
	            {/if}
			</footer><!-- #footer -->
			{if isset($sttheme.boxstyle) && $sttheme.boxstyle==2}</div>{/if}<!-- #page_wrapper -->
		</div><!-- #page -->
  
		{capture name="rightbar_strightbarcart"}
		    {hook h='displayAnywhere' mod='strightbarcart' caller='strightbarcart'} 
		{/capture}
		{capture name="rightbar_stcompare"}
		    {hook h='displayAnywhere' mod='stcompare' caller='stcompare'}
		{/capture}
		{capture name="rightbar_productlinknav_prev"}
		{hook h='displayAnywhere' mod='stproductlinknav' nav='prev' caller='stproductlinknav'}
		{/capture}
		{capture name="rightbar_productlinknav_next"}
		{hook h='displayAnywhere' mod='stproductlinknav' nav='next' caller='stproductlinknav'}
		{/capture}
		{capture name="rightbar_bloglinknav_prev"}
		{if $page_name == 'module-stblog-article'}
		{hook h='displayAnywhere' mod='stbloglinknav' nav='prev' caller='stbloglinknav'}
		{/if}
		{/capture}
		{capture name="rightbar_bloglinknav_next"}
		{if $page_name == 'module-stblog-article'}
		{hook h='displayAnywhere' mod='stbloglinknav' nav='next' caller='stbloglinknav'}
		{/if}
		{/capture}
		{assign var="rightbar_i" value=0}
		{if trim($smarty.capture.rightbar_stcompare)}{assign var="rightbar_i" value=$rightbar_i+1}{/if}
		{if trim($smarty.capture.rightbar_productlinknav_prev)}{assign var="rightbar_i" value=$rightbar_i+1}{/if}
		{if trim($smarty.capture.rightbar_productlinknav_next)}{assign var="rightbar_i" value=$rightbar_i+1}{/if}
		{if trim($smarty.capture.rightbar_bloglinknav_prev)}{assign var="rightbar_i" value=$rightbar_i+1}{/if}
		{if trim($smarty.capture.rightbar_bloglinknav_next)}{assign var="rightbar_i" value=$rightbar_i+1}{/if}
		{if $slide_lr_column && isset($left_column_size) && $left_column_size}{assign var="rightbar_i" value=$rightbar_i+1}{/if}
		{if $slide_lr_column && isset($right_column_size) && $right_column_size}{assign var="rightbar_i" value=$rightbar_i+1}{/if}
		{if (!isset($sttheme.scroll_to_top) || (isset($sttheme.scroll_to_top) && $sttheme.scroll_to_top))}{assign var="rightbar_i" value=$rightbar_i+1}{/if}

		<div id="rightbar" class="{if !$rightbar_i} hidden {/if}"> 
		    <div id="rightbar_inner" class="clearfix rightbar_{$rightbar_i}">
		    {$smarty.capture.rightbar_strightbarcart}
		    {$smarty.capture.rightbar_stcompare}
		    {$smarty.capture.rightbar_productlinknav_prev}
		    {$smarty.capture.rightbar_productlinknav_next}
		    {$smarty.capture.rightbar_bloglinknav_prev}
		    {$smarty.capture.rightbar_bloglinknav_next}
		    {if $slide_lr_column && isset($left_column_size) && !empty($left_column_size)}
		    <div id="switch_left_column_wrap" class="visible-xs">
		        <a href="javascript:;" id="switch_left_column" data-column="left_column" class="icon_wrap" title="{l s="Display left column"}"><i class="icon-right-open-1 icon-0x"></i><span class="icon_text">{l s="Left"}</span></a>   
		    </div>
		    {/if}
		    {if $slide_lr_column && isset($right_column_size) && !empty($right_column_size)}
		    <div id="switch_right_column_wrap" class="visible-xs">
		        <a href="javascript:;" id="switch_right_column" data-column="right_column" class="icon_wrap" title="{l s="Display right column"}"><i class="icon-left-open-1 icon-0x"></i><span class="icon_text">{l s="Right"}</span></a>   
		    </div>
		    {/if}
		    {if (!isset($sttheme.scroll_to_top) || (isset($sttheme.scroll_to_top) && $sttheme.scroll_to_top))}
		    <div id="to_top_wrap">
		        <div id="to_top"><a href="#top_bar" class="icon_wrap disabled" title="{l s="Back to top"}"><i class="icon-up-open-2 icon-0x"></i><span class="icon_text">{l s="Top"}</span></a></div>
		    </div>
		    {/if}
		    </div>  
		</div><!-- #rightbar -->
		{hook h='displayAnywhere' mod='stnewsletter' caller='stnewsletter'}
		<div class="st-side">
			{if isset($HOOK_SIDE_BAR) && $HOOK_SIDE_BAR|trim}{$HOOK_SIDE_BAR}{else}{hook h='displaySideBar'}{/if}
		</div>
		<div id="st-side-close"><i class="icon-cancel-2 close-st-side"></i></div>
		<div id="st-side-overlay"></div>
{/if}
{if $comparator_max_item}
    <div id="layer_compare" class="layer_box">
		<div class="layer_inner_box">
			<div class="layer_product clearfix mar_b10">
				<span class="cross" title="{l s='Close window'}"></span>
				<div class="product-image-container layer_compare_img">
				</div>
				<div class="layer_product_info">
					<span id="layer_compare_product_title" class="product-name"></span>
				</div>
			</div>
	        <div id="compare_add_success" class="success hidden">{l s='has been added to compare.'}</div>
	        <div id="compare_remove_success" class="success hidden">{l s='has been removed from compare.'}</div>
			<div class="button-container clearfix">	
				<a class="continue pull-left btn btn-default" href="javascript:;" rel="nofollow">{l s='Continue shopping'}</a>
            	<a class="pull-right btn btn-default layer_compare_btn" href="{$link->getPageLink('products-comparison')|escape:'html':'UTF-8'}" title="{l s='Compare'}" rel="nofollow">{l s='Compare'}</a>
			</div>
		</div>
	</div> <!-- #layer_compare -->
	<div class="layer_compare_overlay layer_overlay"></div>
{/if}
{include file="$tpl_dir./global.tpl"}
    {if isset($sttheme.tracking_code) && $sttheme.tracking_code}{$sttheme.tracking_code}{/if}
	</body>
</html>