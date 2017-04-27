{*
* 2007-2012 PrestaShop
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
*  @copyright  2007-2012 PrestaShop SA
*  @version  Release: $Revision: 17677 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{include file="$tpl_dir./errors.tpl"}

{if isset($blog)}
	{if $blog->id AND $blog->active}
        <div id="blog_primary_block">
            {if $blog->type==1 && isset($cover)}
                <div class="blog_image"><img src="{$cover.links.large}" alt="{$blog->name|escape:'html':'UTF-8'}" width="{$imageSize[1]['large'][0]}" height="{$imageSize[1]['large'][1]}" class="hover_effect" /></div>
            {/if}
            
            {if $blog->type==2 && isset($galleries) && $galleries|count}
                <div class="blog_gallery">
                <div class="blog_flexslider flexslider">
                    <ul class="slides">
                        {foreach $galleries as $gallery}
                        <li>
                          <img src="{$gallery.links.large}" alt="{$blog->name|escape:'html':'UTF-8'}" width="{$imageSize[1]['large'][0]}" height="{$imageSize[1]['large'][1]}" class="hover_effect" />
                        </li>
                        {/foreach}
                    </ul>
                </div>
                </div>
            {elseif $blog->type==2 && isset($cover)}
                <div class="blog_image"><img src="{$cover.links.large}" alt="{$blog->name|escape:'html':'UTF-8'}" width="{$imageSize[1]['large'][0]}" height="{$imageSize[1]['large'][1]}" class="hover_effect" /></div>
            {/if}
            
            {if $blog->type==3 && $blog->video}
                <div class="blog_video"><div class="full_video">{$blog->video}</div></div>
            {elseif $blog->type==3 && isset($cover)}
                <div class="blog_image"><img src="{$cover.links.large}" alt="{$blog->name|escape:'html':'UTF-8'}" width="{$imageSize[1]['large'][0]}" height="{$imageSize[1]['large'][1]}" class="hover_effect" /></div>
            {/if}
            
            <h1 class="heading">{$blog->name}</h1>
            <div class="blog_content mar_b2">
                {$blog->content}
            </div>
            
            <div class="blog_info mar_b1">
                <span class="posted_on">{l s='Posted on' mod='stblog'}</span>
                <span class="date-add">{dateFormat date=$blog->date_add full=0}</span>
                {if isset($blog->author) && $blog->author}
                    <span class="posted_by">{l s='by' mod='stblog'}</span>
                    <span class="posted_author">{$blog->author|escape:'html':'UTF-8'}</span>
                {/if}
                <span class="blog-categories">
                    {foreach $categories as $category}
                        <a href="{$link->getModuleLink('stblog','category',['blog_id_category'=>$category.id_st_blog_category,'rewrite'=>$category.link_rewrite])|escape:'html'}" title="{$category.name|escape:'html':'UTF-8'}">{$category.name|truncate:30:'...'|escape:'html':'UTF-8'}</a>{if !$category@last},{/if}
                    {/foreach}
                </span>
                {hook h='displayAnywhere' function="getCommentNumber" id_blog=$blog->id mod='stblogcomments' caller='stblogcomments'}
                {if $display_viewcount}<span><i class="icon-eye-2 icon-mar-lr2"></i>{$blog->counter}</span>{/if}
            </div>
            
            {if $blog_tags && $blog_tags|count}
                <div id="blog_tags">
                    {l s='Tag' mod='stblog'}:
                    {foreach $blog_tags as $tag}
                        <a href="{$link->getModuleLink('stblogsearch', 'default', ["stb_search_query"=>"{$tag}"])|escape:'html'}" title="{l s='More about' mod='stblog'} {$tag|escape:html:'UTF-8'}">{$tag|escape:html:'UTF-8'}</a>{if !$tag@last},{/if}
                    {/foreach}
                </div>
            {/if}
        </div>
        {if isset($HOOK_ST_BLOG_ARTICLE_FOOTER)}
            {$HOOK_ST_BLOG_ARTICLE_FOOTER}
        {/if}
        {if $related_products && $related_products|count}
        <section id="blog_related_products" class="block products_block section">
        	<h4 class="title_block"><span>{l s='Related products' mod='stblog'}</span></h4>
            <div id="blog_related-itemslider" class="flexslider">
            	<div class="nav_top_right"></div>
                <div class="sliderwrap products_slider">
                    <ul class="slides">                
                	{foreach $related_products as $product}
                		<li class="ajax_block_product {if $product@first}first_item{elseif $product@last}last_item{else}item{/if}">
                            {capture name="new_on_sale"}
                                {if isset($product.new) && $product.new == 1}<span class="new"><i>{l s='New' mod='stblog'}</i></span>{/if}{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}<span class="on_sale"><i>{l s='Sale' mod='stblog'}</i></span>{/if}
                            {/capture}
                            <div class="pro_outer_box">
                            <div class="pro_first_box">
                                <a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}" class="product_image"><img src="{$link->getImageLink($product.link_rewrite,$product.id_image,'home_default')}" height="{$homeSize.height}" width="{$homeSize.width}" alt="{$product.name|escape:html:'UTF-8'}"  />{$smarty.capture.new_on_sale}</a>
                            </div>
                            <div class="pro_second_box">
                            {if isset($sttheme.length_of_product_name) && $sttheme.length_of_product_name==1}
                                {assign var="length_of_product_name" value=70}
                            {else}
                                {assign var="length_of_product_name" value=35}
                            {/if}
                			<p class="s_title_block {if isset($sttheme.length_of_product_name) && $sttheme.length_of_product_name} nohidden {/if}"><a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'html'}" title="{$product.name|escape:'html':'UTF-8'}">{if isset($sttheme.length_of_product_name) && $sttheme.length_of_product_name==2}{$product.name|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'|truncate:$length_of_product_name:'...'}{/if}</a></p>
                            {if $blogRelatedDisplayPrice AND $product.show_price == 1 AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
        						<div class="price_container">
        							<span class="price">{convertPrice price=$product.displayed_price}</span>
        						</div>
        					{else}
        					{/if}  
                            </div>
                            </div>
                		</li>
                	{/foreach}
                	</ul>
                </div>
        	</div>
            <script type="text/javascript">
            //<![CDATA[
            {literal}
            jQuery(function($) {
                $('#blog_related-itemslider .sliderwrap').flexslider({
                	easing: "{/literal}{$easing}{literal}",
                    useCSS: false,
            		slideshow: {/literal}{$slideshow}{literal},
                    slideshowSpeed: {/literal}{$s_speed}{literal},
            		animationSpeed: {/literal}{$a_speed}{literal},
            		pauseOnHover: {/literal}{$pause_on_hover}{literal},
                    direction: "horizontal",
                    animation: "slide",
            		animationLoop: {/literal}{$loop}{literal},
            		controlNav: false,
            		controlsContainer: "#blog_related-itemslider .nav_top_right",
                    itemWidth: 260,
                    minItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
                    maxItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
                    move: {/literal}{$move}{literal},
                    prevText: '<i class="icon-left-open-3"></i>',
                    nextText: '<i class="icon-right-open-3"></i>',
                    productSlider:true,
                    allowOneSlide:false
                });
                var blog_related_flexslider_rs;
                $(window).resize(function(){
                    clearTimeout(blog_related_flexslider_rs);
                    var rand_s = parseInt(Math.random()*200 + 300);
                    blog_related_flexslider_rs = setTimeout(function() {
                        var flexSliderSize = getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}});
                        var flexslide_object = $('#blog_related-itemslider .sliderwrap').data('flexslider');
                        if(flexSliderSize && flexslide_object != null )
                            flexslide_object.setVars({'minItems': flexSliderSize, 'maxItems': flexSliderSize});
                	}, rand_s);
                });
            });
            {/literal} 
            //]]>
            </script>
        </section>
        {/if}
        {if isset($HOOK_ST_BLOG_ARTICLE_SECONDARY)}
            <div id="blog_secondary_block">
                {$HOOK_ST_BLOG_ARTICLE_SECONDARY}
            </div>
        {/if}
	{elseif $blog->id}
		<p class="warning">{l s='This blog is currently unavailable.' mod='stblog'}</p>
	{/if}
{/if}