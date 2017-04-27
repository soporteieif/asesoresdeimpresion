<!-- MODULE stcameraslideshow -->
{if isset($slide_group) && is_array($slide_group)}
    {if isset($google_font_links)}{$google_font_links}{/if}
    {foreach $slide_group as $slide}
        {if isset($slide['slide']) && is_array($slide['slide']) && count($slide['slide'])}
            {if count($slide['slide'])>1}
            <div id="camera_container_{$slide.id_st_camera_slideshow_group}" class="slideshow_block small_slideshow {if $slide['hide_on_mobile']}hidden-xs{/if} mar_b1 clearfix">
                <div class="camera_wrap camera_emboss" id="camera_wrap_{$slide.id_st_camera_slideshow_group}">
                    {foreach $slide['slide'] as $banner}
                        <div data-thumb="{if (isset($banner.thumb_multi_lang) && $banner.thumb_multi_lang)}{$banner.thumb_multi_lang}{else}{$banner.thumb}{/if}" data-src="{if (isset($banner.image_multi_lang) && $banner.image_multi_lang)}{$banner.image_multi_lang}{else}{$banner.image}{/if}" {if $banner.url && !$banner.button} data-link="{$banner.url|escape:'html'}" data-target="{if $banner.new_window}_blank{else}_self{/if}" {/if} data-alt="{$banner.title|escape:'html':'UTF-8'}">
                        </div>
                    {/foreach}
                </div>
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
                        onLoaded : function(cs){
                            $('.camera_prev,.camera_next',cs).removeClass('hidden');
                        }
            		});
                });
                {/literal} 
                //]]>
                </script>
            </div>
            {else}
                <div class="block slideshow_block small_slideshow {if $slide['hide_on_mobile']}hidden-xs{/if}">
                    {assign var="banner" value=$slide['slide'][0]}
                    {if $banner.url}<a href="{$banner.url|escape:'html'}" target="{if $banner.new_window}_blank{else}_self{/if}" title="{$banner.title|escape:'html':'UTF-8'}">{/if}
                    <img src="{if (isset($banner.image_multi_lang) && $banner.image_multi_lang)}{$banner.image_multi_lang}{else}{$banner.image}{/if}" alt="{$banner.title|escape:'html':'UTF-8'}" />
                    {if $banner.url}</a>{/if}
                </div>
            {/if}
        {/if}
    {/foreach}
{/if}
<!--/ MODULE stcameraslideshow -->
