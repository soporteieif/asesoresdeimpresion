<!-- MODULE stcameraslideshow -->
{if isset($slide_group)}
    {if isset($google_font_links)}{$google_font_links}{/if}
	{assign var="hasSlide" value="false"}
    {foreach $slide_group as $slide}
        {if isset($slide['slide']) && ($hasSlide eq "false")}
	        {assign var="hasSlide" value="true"}
            <div id="camera_container_{$slide.id_st_camera_slideshow_group}" class="slideshow_block {if $slide['location']==1 || $slide['location']==7 || $slide['location']==19} fullwidth_slideshow {/if} {if $slide['hide_on_mobile']} hidden-xs {/if} {if $slide['location']==4 || $slide['location']==8 || $slide['location']==13 || $slide['location']==14 || $slide['location']==20} wide_container {/if} clearfix mar_b1">
                {if $slide['location']==4 || $slide['location']==8 || $slide['location']==13 || $slide['location']==14 || $slide['location']==20}
                    <div class="container clearfix">
                {/if}
                {if $slide['location']==13 || $slide['location']==14}
                    <div class="row">
                        <div class="{if $slide['location']==13} col-xs-12 col-sm-8 col-md-8 {elseif $slide['location']==14} col-xs-12 col-sm-9 col-md-9 {/if} clearfix">
                {/if}
                <div class="camera_wrap camera_emboss" id="camera_wrap_{$slide.id_st_camera_slideshow_group}">
                    {foreach $slide['slide'] as $banner}
                        <div data-thumb="{if (isset($banner.thumb_multi_lang) && $banner.thumb_multi_lang)}{$banner.thumb_multi_lang}{else}{$banner.thumb}{/if}" data-src="{if (isset($banner.image_multi_lang) && $banner.image_multi_lang)}{$banner.image_multi_lang}{else}{$banner.image}{/if}" {if $banner.url && !$banner.button} data-link="{$banner.url|escape:'html'}" data-target="{if $banner.new_window}_blank{else}_self{/if}" {/if} data-alt="{$banner.title|escape:'html':'UTF-8'}">
                            {if $banner.description!=''}
                            <div class="camera_caption fadeFromBottom">
                                {if $slide['location']==13}<div class="cont_two_banners">{elseif $slide['location']==14}<div class="cont_three_banners">{else}<div class="container">{/if}
                                    <div id="camera_caption_{$banner.id_st_camera_slideshow}" class="camera_caption_box hidden-xs {$banner.text_position} {if $banner.text_align eq 2}text-left{elseif $banner.text_align eq 3}text-right{else}text-center{/if}">
				    {if $banner.text_position=='center_center'}<div class="camera_caption_inner">{/if}
                                    {$banner.description}
                                    <div class="clearBoth mar_b6"></div>
                                    {if $banner.url && $banner.button}
                                        <a href="{$banner.url|escape:'html'}" target="{if $banner.new_window}_blank{else}_self{/if}" title="{$banner.title|escape:'html':'UTF-8'}" class="btn btn-medium btn_primary">{$banner.button|escape:'html':'UTF-8'}</a>
                                    {/if}
				    {if $banner.text_position=='center_center'}</div>{/if}
                                    </div>
                                </div>
                            </div>
                            {/if}
                        </div>
                    {/foreach}
                </div>
                {if $slide['location']==13 || $slide['location']==14}
                        </div>
                        <div class="{if $slide['location']==13} col-xs-12 col-sm-4 col-md-4 camera_banner_2 {elseif $slide['location']==14} col-xs-12 col-sm-3 col-md-3 camera_banner_3 {/if} {if isset($slide['banners'])}camera_banner_nbr_{$slide['banners']|count}{/if} clearfix">
                        {if isset($slide['banners']) && $slide['banners']|count}
                            {foreach $slide['banners'] as $banner}
                                <div class="camera_banner">
                                    {if $banner.url}<a href="{$banner.url|escape:'html'}" target="{if $banner.new_window}_blank{else}_self{/if}" title="{$banner.title|escape:'html':'UTF-8'}">{/if}
                                        <img src="{$banner.image_multi_lang}" alt="{$banner.title|escape:'html':'UTF-8'}" class="hover_effect img-responsive" alt="{$banner.title|escape:'html':'UTF-8'}" />
                                    {if $banner.url}</a>{/if}
                                </div>
                            {/foreach}
                        {/if}
                        </div>
                    </div>
                {/if}
                {if $slide['location']==4 || $slide['location']==8 || $slide['location']==13 || $slide['location']==14 || $slide['location']==20}</div>{/if}
                <script type="text/javascript">
                //<![CDATA[
                {literal}
                jQuery(function($) {   
                    $('#camera_wrap_{/literal}{$slide.id_st_camera_slideshow_group}{literal}').camera({
            			autoAdvance: {/literal}{$slide.auto_advance}{literal},
            			mobileAutoAdvance:{/literal}{$slide.auto_advance}{literal},
            			barDirection : 'leftToRight',
            			barPosition : '{/literal}{$slide.bar_position}{literal}',
                        cols : {/literal}{$slide.mosaic_columns}{literal},
            			easing: '{/literal}{$slide.easing}{literal}',
            			fx : '{/literal}{$slide.effects}{literal}',
            			mobileFx : 'scrollRight',
            			height	: '{/literal}{$slide.height_ratio}{literal}%',
            			hover : {/literal}{$slide.pause}{literal},
            			loader : '{/literal}{if $slide.loader==1}pie{elseif $slide.loader==2}bar{else}none{/if}{literal}',
            			loaderColor: '{/literal}{$slide.loader_color}{literal}',
            			loaderBgColor: '{/literal}{$slide.loader_bg}{literal}',
            			loaderOpacity: .8,
            			loaderPadding: 0,
            			loaderStroke: 4,
                        minHeight : '',
            			navigation : {/literal}{$slide.prev_next}{literal},
            			navigationHover : {/literal}{$slide.prev_next_on_hover}{literal},
                        mobileNavHover : false,		
            			pagination : {/literal}{$slide.pag_nav}{literal},	
                        playPause :false,
            			piePosition : '{/literal}{$slide.pie_position}{literal}',
                        portrait : true,
            			rows: {/literal}{$slide.mosaic_rows}{literal},
            			slicedCols: {/literal}{$slide.curtain_columns}{literal},
            			slicedRows: {/literal}{$slide.blind_rows}{literal},
            			slideOn: 'random',
            			thumbnails : false,
            			time : {/literal}{$slide.time}{literal},
            			transPeriod : {/literal}{$slide.trans_period}{literal},
                        imagePath : '{/literal}{$image_path}{literal}',
                        withbanners : {/literal}{if $slide['location']==13 || $slide['location']==14}true{else}false{/if}{literal},
                        onLoaded : function(cs){
                            $('.camera_prev,.camera_next',cs).removeClass('hidden');
                        }
            		});
                });
                {/literal} 
                //]]>
                </script>
            </div>
        {/if}
    {/foreach}
{/if}
<!--/ MODULE stcameraslideshow -->