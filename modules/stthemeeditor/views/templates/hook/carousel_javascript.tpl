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
<script type="text/javascript">
//<![CDATA[
{literal}
jQuery(function($) {
    $('#{/literal}{$identify}{literal}-itemslider .sliderwrap').flexslider({
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
		controlsContainer: "#{/literal}{$identify}{literal}-itemslider .nav_top_right",
        itemWidth: 260,
        minItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),
        maxItems: getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}}),        move: {/literal}{$move}{literal},
        prevText: '<i class="icon-left-open-3"></i>',
        nextText: '<i class="icon-right-open-3"></i>',
        productSlider:true,
        allowOneSlide:false
    });
    var {/literal}{$identify}{literal}_flexslider_rs;
    $(window).resize(function(){
        clearTimeout({/literal}{$identify}{literal}_flexslider_rs);
        var rand_s = parseInt(Math.random()*200 + 300);
        {/literal}{$identify}{literal}_flexslider_rs = setTimeout(function() {
            var flexSliderSize = getFlexSliderSize({'lg':{/literal}{$pro_per_lg}{literal},'md':{/literal}{$pro_per_md}{literal},'sm':{/literal}{$pro_per_sm}{literal},'xs':{/literal}{$pro_per_xs}{literal},'xxs':{/literal}{$pro_per_xxs}{literal}});
            var flexslide_object = $('#{/literal}{$identify}{literal}-itemslider .sliderwrap').data('flexslider');
            if(flexSliderSize && flexslide_object != null )
                flexslide_object.setVars({'minItems': flexSliderSize, 'maxItems': flexSliderSize});
    	}, rand_s);
    });
});
{/literal} 
//]]>
</script>