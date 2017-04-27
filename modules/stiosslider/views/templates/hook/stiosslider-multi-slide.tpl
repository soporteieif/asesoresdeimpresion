<!-- MODULE stiossldier -->
{if isset($slide['slide']) && count($slide['slide'])}
{if $slide['location']==14 || $slide['location']==23}<div class="iosslider_wide_container wide_container">{/if}
<div id="iosSlider_containerOuter_{$slide.id_st_iosslider_group}" class="containerOuter_multi_slide  {if $slide['hide_on_mobile']} hidden-xs {/if} iosSlider_tb{$slide['padding_tb']} block">
<div class="container_multi_slide {if $slide['location']==14} container {/if} {if $slide['location']==14 || (isset($sttheme.boxstyle) && $sttheme.boxstyle==2)} iosslider_boxed {else} iosslider_stretched {/if}">
<div class="containerInner_multi_slide">
<div id="iosSlider_{$slide.id_st_iosslider_group}" class="iosSlider multi_slide">
    <div class="slider clearfix">
        {assign var="selectorsBlock" value=""}
        {foreach $slide['slide'] as $banner}
            <div id="iosSliderBanner_{$banner.id_st_iosslider}" class="iosSlideritem">
                {if $banner.url}<a href="{$banner.url}" title="{$banner.title|escape:'html':'UTF-8'}" target="{if $banner.new_window}_blank{else}_self{/if}">{/if}<img src="{$banner.image_multi_lang}" alt="{$banner.title}" />{if $banner.url}</a>{/if}
			</div>
            {if !$banner@index}
                {$selectorsBlock = $selectorsBlock|cat:'<a class="selectoritem first selected" href="javascript:;"><span></span></a>'}
            {else}
                {$selectorsBlock = $selectorsBlock|cat:'<a class="selectoritem" href="javascript:;"><span></span></a>'}
            {/if}
        {/foreach}
    </div>
    {if $slide.prev_next}
	<div id="iosSliderPrev_{$slide.id_st_iosslider_group}" class="iosSlider_prev none {if $slide.prev_next==2 || $slide.prev_next==4} hidden-xs {/if} {if $slide.prev_next==3 || $slide.prev_next==4} showonhover {/if}"><i class="icon-angle-left"></i></div>
	<div id="iosSliderNext_{$slide.id_st_iosslider_group}" class="iosSlider_next none {if $slide.prev_next==2 || $slide.prev_next==4} hidden-xs {/if} {if $slide.prev_next==3 || $slide.prev_next==4} showonhover {/if}"><i class="icon-angle-right"></i></div>
    {/if}
    {if $slide.pag_nav}
	<div id="iosSlider_selectors_{$slide.id_st_iosslider_group}" class="iosSlider_selectors iosSlider_selectors_{if $slide.pag_nav==1 || $slide.pag_nav==2}round{else if $slide.pag_nav==2 || $slide.pag_nav==4}square{/if} {if $slide.pag_nav==2 || $slide.pag_nav==4} hidden-xs {/if}">
		{$selectorsBlock}
	</div>
    {/if}
    <div class="css3loader css3loader-3"></div>
</div>
</div>
</div>
</div>
{if $slide['location']==14 || $slide['location']==23}</div>{/if}
<script type="text/javascript">
//<![CDATA[
{literal}
    jQuery(function($){
		$('#iosSlider_{/literal}{$slide.id_st_iosslider_group}{literal}').iosSlider({
			{/literal}
            desktopClickDrag: {if $slide.desktopClickDrag}true{else}false{/if},
            infiniteSlider: {if $slide.infiniteSlider}true{else}false{/if},
			scrollbar: {if $slide.scrollbar}true{else}false{/if},
			autoSlide: {if $slide.auto_advance}true{else}false{/if},
			autoSlideTimer: {$slide.time|default:5000},
			autoSlideTransTimer: {$slide.trans_period|default:750},
			autoSlideHoverPause: {if $slide.pause}true{else}false{/if},
            {if $slide.prev_next}
            {literal}
			navNextSelector: $('#iosSliderNext_{/literal}{$slide.id_st_iosslider_group}{literal}'),
			navPrevSelector: $('#iosSliderPrev_{/literal}{$slide.id_st_iosslider_group}{literal}'),
            {/literal}
            {/if}
            {if $slide.pag_nav}
            navSlideSelector: '#iosSlider_selectors_{$slide.id_st_iosslider_group} .selectoritem',
            {/if}
            {literal}
			onSliderLoaded: sliderLoaded_{/literal}{$slide.id_st_iosslider_group}{literal},
			onSlideChange: slideChange_{/literal}{$slide.id_st_iosslider_group}{literal},
			snapSlideCenter: true,
			snapToChildren: true                    
		});
	});
    function slideChange_{/literal}{$slide.id_st_iosslider_group}{literal}(args) {
        {/literal}{if $slide.pag_nav}{literal}
		$('#iosSlider_selectors_{/literal}{$slide.id_st_iosslider_group}{literal} .selectoritem').removeClass('selected');
		$('#iosSlider_selectors_{/literal}{$slide.id_st_iosslider_group}{literal} .selectoritem:eq(' + (args.currentSlideNumber - 1) + ')').addClass('selected');
        {/literal}{/if}{literal}
	}
	
	function sliderLoaded_{/literal}{$slide.id_st_iosslider_group}{literal}(args) {
        $(args.sliderContainerObject).find('.css3loader').fadeOut();
        $(args.sliderContainerObject).find('.iosSlider_selectors,.iosSlider_prev,.iosSlider_next').fadeIn();
	}
{/literal} 
//]]>
</script>
{/if}
<!--/ MODULE stiossldier -->