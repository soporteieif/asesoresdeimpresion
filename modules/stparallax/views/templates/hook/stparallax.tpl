<!-- MODULE stparallax -->
{if isset($slide_group)}
    {foreach $slide_group as $slide}
        <div id="parallax_box_{$slide.id_st_parallax_group}" class="owl_carousel_wrap parallax_box full_container block {if $slide['bg_img']} parallax_box_img {/if} {if $slide['hide_on_mobile']} hidden-xs {/if}" >
            <div class="container">
            {if $slide['title']}<h3 class="parallax_heading">{$slide['title']|escape:'html':'UTF-8'}</h3>{/if}
            <div id="owl-parallax-{$slide.id_st_parallax_group}" class="{if count($slide['slide'])>1} owl-carousel owl-theme owl-navigation-lr {if $slide['prev_next']==2} owl-navigation-rectangle {elseif $slide['prev_next']==3} owl-navigation-circle {/if}{/if}">
            {if isset($slide['slide']) && count($slide['slide'])}
                {foreach $slide['slide'] as $banner}
                    {if $banner.description}
                        <div id="parallax_text_con_{$banner['id_st_parallax']}" class="container parallax_text_con parallax_text_con_{$banner['id_st_parallax']}">
                            <div class="style_content {if $banner.text_align==2} text-center {elseif $banner.text_align==3} text-right {else} text-left {/if} {if $banner.width} center_width_{$banner.width} {/if}">
                                {$banner.description}
                            </div>
                        </div>
                    {/if}
                {/foreach}
            {/if}
            </div>
            </div>
        </div>
        <script type="text/javascript">
        //<![CDATA[
        {literal}
            jQuery(function($){
                {/literal}{if $slide['bg_img']}{literal}
                $('#parallax_box_{/literal}{$slide.id_st_parallax_group}{literal}').parallax("50%", {/literal}{$slide.speed|floatval}{literal});
                {/literal}{/if}{literal}

                {/literal}{if count($slide['slide'])>1}{literal}
                $("#owl-parallax-{/literal}{$slide.id_st_parallax_group}{literal}").owlCarousel({
                    {/literal}
                    autoPlay : {if $slide.auto_advance}{$slide.time|default:5000}{else}false{/if},
                    navigation: {if $slide.prev_next}true{else}false{/if},
                    pagination: {if $slide.pag_nav}true{else}false{/if},
                    paginationSpeed : 1000,
                    goToFirstSpeed : 2000,
                    singleItem : true,
                    autoHeight : {if $slide.autoHeight}true{else}false{/if},
                    slideSpeed: {$slide.trans_period|default:200},
                    stopOnHover: {if $slide.pause}true{else}false{/if},
                    mouseDrag: {if $slide.desktopClickDrag}true{else}false{/if},
                    transitionStyle:"fade"
                    {literal}
                });
                {/literal}{/if}{literal}
            });
        {/literal} 
        //]]>
        </script>
    {/foreach}
{/if}
<!--/ MODULE stparallax -->