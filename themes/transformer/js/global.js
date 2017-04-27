/*
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
*/
//global variables
var responsiveflag = false;

var window_width;
var verifMailREGEX = /^([\w+-]+(?:\.[\w+-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
var menu_container_top=null;
var adv_container_top=null;
var nav_container_height=0;
if(typeof(st_sticky_menu) == 'undefined')
	st_sticky_menu = 0;
if(typeof(st_sticky_adv) == 'undefined')
	st_sticky_adv = 0;
if(typeof(st_sticky_mobile_header) == 'undefined')
	st_sticky_mobile_header = 0;

$(document).ready(function(){
	highdpiInit();
	//responsiveResize();
	//$(window).resize(responsiveResize);
	//blockHover();
	if (typeof quickView !== 'undefined' && quickView)
		quick_view();
	
	if (typeof page_name !== 'undefined' && !in_array(page_name, ['index', 'product']))
	{
		bindGrid();

 		$(document).on('change', '.selectProductSort', function(e){
			if (typeof request != 'undefined' && request)
				var requestSortProducts = request;
			var url = '';
 			var splitData = $(this).val().split(':');
			if (typeof requestSortProducts != 'undefined' && requestSortProducts)
			{
				url += requestSortProducts ;
				if (typeof splitData[0] !== 'undefined' && splitData[0])
				{
					url += ( requestSortProducts.indexOf('?') < 0 ? '?' : '&') + 'orderby=' + splitData[0] + (splitData[1] ? '&orderway=' + splitData[1] : '');
					if (typeof splitData[1] !== 'undefined' && splitData[1])
						url += '&orderway=' + splitData[1];
				}
				document.location.href = url;
			}
    	});

		$(document).on('change', 'select[name="n"]', function(){
			$(this.form).submit();
		});

		$(document).on('change', 'select[name="currency_payment"]', function(){
			setCurrency($(this).val());
		});
	}

	$(document).on('change', 'select[name="manufacturer_list"], select[name="supplier_list"]', function(){
		if (this.value != '')
			location.href = this.value;
	});

	$(document).on('click', '.back', function(e){
		e.preventDefault();
		history.back();
	});

	jQuery.curCSS = jQuery.css;
	if (!!$.prototype.cluetip)
		$('a.cluetip').cluetip({
			local:true,
			cursor: 'pointer',
			dropShadow: false,
			dropShadowSteps: 0,
			showTitle: false,
			tracking: true,
			sticky: false,
			mouseOutClose: true,
			fx: {
		    	open:       'fadeIn',
		    	openSpeed:  'fast'
			}
		}).css('opacity', 0.8);

	if (typeof(FancyboxI18nClose) !== 'undefined' && typeof(FancyboxI18nNext) !== 'undefined' && typeof(FancyboxI18nPrev) !== 'undefined' && !!$.prototype.fancybox)
		$.extend($.fancybox.defaults.tpl, {
			closeBtn : '<a title="' + FancyboxboxI18nClose + '" class="fancybox-item fancybox-close" href="javascript:;"></a>',
			next     : '<a title="' + FancyboxI18nNext + '" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
			prev     : '<a title="' + FancyboxI18nPrev + '" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
		});
	// Close Alert messages
	$(".alert.alert-danger").on('click', this, function(e){
		if (e.offsetX >= 16 && e.offsetX <= 39 && e.offsetY >= 16 && e.offsetY <= 34)
			$(this).fadeOut();
	});
	//blocksearch.js might not be loaded so put this code here.
	$("#blocksearch_mod_tri").click(function(){
        stSidebar('blocksearch_mod');
        return false;
    });
    //
    $("#st-side-overlay,#st-side-close").click(function(){
		resetSidebar();
		return false;
	});
	
    $('.st-side .mo_advanced_mu_level_0,#st_advanced_menu_column_mobile .mo_advanced_mu_level_0').staccordion();
    
    if (!!$.prototype.fancybox)
        $("a.iframe").fancybox({
            'type': 'iframe',
            'width': 600,
            'height': 600
        });
});

function highdpiInit()
{
	if(typeof st_retina !== 'undefined' && st_retina && isRetina())
	{
		var els = $("img.replace-2x").get();
		var img = new Array();
		for(var i = 0; i < els.length; i++)
		{
			var src = els[i].src;
			if (els[i].getAttribute('data-2x')) 
				var src_2x = els[i].getAttribute('data-2x');
			else
				var src_2x = src.replace(/\/(\d+)\-(\w+)\_default([\.\/])/i, "/$1-$2_default_2x$3");

			if(src_2x==src)
				continue;
			
			img[i] = new Image();
			img[i].setAttribute('title',i);
			img[i].setAttribute('alt',src);
			img[i].setAttribute('rel',src_2x);
			img[i].onload = (function() {
				//Currently, this is the only way to say if the image is 404.gif
				if (this.height != 125 && this.width != 125 && els[this.getAttribute('title')].src==this.getAttribute('alt'))
					els[this.getAttribute('title')].src = this.getAttribute('rel');
			});
			img[i].src = src_2x;
		}
		
		var img_links = new Array();
		var img_small = new Array();
		var img_large = new Array();
		var links = $("a.replace-2x").get();
		for(var i = 0; i < links.length; i++)
		{
			var ahref = links[i].href;
			var ahref_2x = ahref.replace(/\/(\d+)\-(\w+)\_default([\.\/])/i, "/$1-$2_default_2x$3");
			if(ahref_2x!=ahref)
			{
				img_links[i] = new Image();
				img_links[i].setAttribute('title',i);
				img_links[i].setAttribute('rel',ahref_2x);
				img_links[i].onload = (function() {
					if (this.height != 125 && this.width != 125)
						links[this.getAttribute('title')].href = this.getAttribute('rel');
				});
				img_links[i].src = ahref_2x;
			}

			var arel = links[i].rel;
			if(arel)
			{
				var arel_obj = $.extend({}, eval("(" + $.trim(arel) + ")"));
				if(arel_obj && typeof(arel_obj.smallimage)!== 'undefined' && typeof(arel_obj.largeimage)!== 'undefined')
				{
					var ahref_2x = arel_obj.smallimage.replace(/\/(\d+)\-(\w+)\_default([\.\/])/i, "/$1-$2_default_2x$3");
					if(ahref_2x!=arel_obj.smallimage)
					{
						img_small[i] = new Image();
						img_small[i].setAttribute('title',i);
						img_small[i].setAttribute('rel',ahref_2x);
						img_small[i].onload = (function() {
							if (this.height != 125 && this.width != 125)
							{
								var img_current_rel = links[this.getAttribute('title')].rel;
								var img_current_rel_obj = $.extend({}, eval("(" + $.trim(img_current_rel) + ")"));
								//this code can be improved by using json.js
								links[this.getAttribute('title')].rel = "{gallery: 'gal1', smallimage: '"+this.getAttribute('rel')+"',largeimage: '"+img_current_rel_obj.largeimage+"'}";
							}
						});
						img_small[i].src = ahref_2x;
					}

					var ahref_2x = arel_obj.largeimage.replace(/\/(\d+)\-(\w+)\_default([\.\/])/i, "/$1-$2_default_2x$3");
					if(ahref_2x!=arel_obj.largeimage)
					{
						img_large[i] = new Image();
						img_large[i].setAttribute('title',i);
						img_large[i].setAttribute('rel',ahref_2x);
						img_large[i].onload = (function() {
							if (this.height != 125 && this.width != 125)
							{
								var img_current_rel = links[this.getAttribute('title')].rel;
								var img_current_rel_obj = $.extend({}, eval("(" + $.trim(img_current_rel) + ")"));
								//this code can be improved by using json.js
								links[this.getAttribute('title')].rel = "{gallery: 'gal1', smallimage: '"+img_current_rel_obj.smallimage+"',largeimage: '"+this.getAttribute('rel')+"'}";
							}
						});
						img_large[i].src = ahref_2x;
					}
				}
			}
		}
	}
}

var isRetina = function(){
    var root = (typeof exports === 'undefined' ? window : exports);
    var mediaQuery = '(-webkit-min-device-pixel-ratio: 1.5), (min--moz-device-pixel-ratio: 1.5), (-o-min-device-pixel-ratio: 3/2), (min-resolution: 1.5dppx)';

    if (root.devicePixelRatio > 1) {
        return true;
    }

    if (root.matchMedia && root.matchMedia(mediaQuery).matches) {
        return true;
    }

    return false;
};


// Used to compensante Chrome/Safari bug (they don't care about scroll bar for width)
function scrollCompensate()
{
    var inner = document.createElement('p');
    inner.style.width = "100%";
    inner.style.height = "200px";

    var outer = document.createElement('div');
    outer.style.position = "absolute";
    outer.style.top = "0px";
    outer.style.left = "0px";
    outer.style.visibility = "hidden";
    outer.style.width = "200px";
    outer.style.height = "150px";
    outer.style.overflow = "hidden";
    outer.appendChild(inner);

    document.body.appendChild(outer);
    var w1 = inner.offsetWidth;
    outer.style.overflow = 'scroll';
    var w2 = inner.offsetWidth;
    if (w1 == w2) w2 = outer.clientWidth;

    document.body.removeChild(outer);

    return (w1 - w2);
}

function responsiveResize()
{
	compensante = scrollCompensate();
	if (($(window).width()+scrollCompensate()) <= 767 && responsiveflag == false)
	{
		accordion('enable');
		responsiveflag = true;
	}
	else if (($(window).width()+scrollCompensate()) >= 768)
	{
		accordion('disable');
	    responsiveflag = false;
	    if (typeof bindUniform !=='undefined')
			bindUniform();
	}
	blockHover();
}

function blockHover(status)
{
	var screenLg = $('body').find('.container').width() == 1170;

	if ($('.product_list').is('.grid'))
		if (screenLg)
			$('.product_list .button-container').hide();
		else
			$('.product_list .button-container').show();

	$(document).off('mouseenter').on('mouseenter', '.product_list.grid li.ajax_block_product .product-container', function(e){
		if (screenLg)
		{
			var pcHeight = $(this).parent().outerHeight();
			var pcPHeight = $(this).parent().find('.button-container').outerHeight() + $(this).parent().find('.comments_note').outerHeight() + $(this).parent().find('.functional-buttons').outerHeight();
			$(this).parent().addClass('hovered').css({'height':pcHeight + pcPHeight, 'margin-bottom':pcPHeight * (-1)});
			$(this).find('.button-container').show();
		}
	});

	$(document).off('mouseleave').on('mouseleave', '.product_list.grid li.ajax_block_product .product-container', function(e){
		if (screenLg)
		{
			$(this).parent().removeClass('hovered').css({'height':'auto', 'margin-bottom':'0'});
			$(this).find('.button-container').hide();
		}
	});
}

function quick_view()
{
	$(document).on('click', '.quick-view:visible, .quick-view-mobile:visible', function(e)
	{
		quickViewCaller = this;
		
		e.preventDefault();
		var url = this.rel;
		var anchor = '';

		if (url.indexOf('#') != -1)
		{
			anchor = url.substring(url.indexOf('#'), url.length);
			url = url.substring(0, url.indexOf('#'));
		}

		if (url.indexOf('?') != -1)
			url += '&';
		else
			url += '?';

		if (!!$.prototype.fancybox)
			$.fancybox({
				'padding':  0,
				'width':    1087,
				'height':   610,
				'type':     'iframe',
				'href':     url + 'content_only=1' + anchor
			});
	});
}

function bindGrid()
{
	var storage = false;
	if (typeof(getStorageAvailable) !== 'undefined') {
		storage = getStorageAvailable();
	}else{
		test = 'foo';
		storage_temp =  window.localStorage || window.sessionStorage;
		try {
			storage_temp.setItem(test, test);
			storage_temp.removeItem(test);
			storage = storage_temp;
		}
		catch (error) {
			storage = false;
		}
	}
	if (!storage) {
		return;
	}

	var view = $.totalStorage('display');
	if(!view && $('ul.product_list').length==1)
	{
		var default_view = $.trim($('ul.product_list').attr('data-default-view'));
		if(default_view=='grid')view='grid';
		if(default_view=='list')view='list';
	}

	if (view && view != 'grid')
		display(view);
	else
		$('.content_sortPagiBar .display').find('li.grid').addClass('selected');
	
	$(document).on('click', '.content_sortPagiBar .display .grid', function(e){
		e.preventDefault();
		display('grid');
	});

	$(document).on('click', '.content_sortPagiBar .display .list', function(e){
		e.preventDefault();
		display('list');
	});
}

function display(view)
{
	if (view == 'list')
	{
		var classnames = $('ul.product_list').removeClass('grid row').addClass('list').attr('data-classnames');
		$('.product_list > li').removeClass(classnames).addClass('col-xs-12 clearfix');
		
		$('.content_sortPagiBar .display').find('li.list').addClass('selected');
		$('.content_sortPagiBar .display').find('li.grid').removeClass('selected');
		$.totalStorage('display', 'list');
	}
	else 
	{
		var classnames = $('ul.product_list').removeClass('list').addClass('grid row').attr('data-classnames');
		$('.product_list > li').removeClass('col-xs-12 clearfix').addClass(classnames);
		
		$('.content_sortPagiBar .display').find('li.grid').addClass('selected');
		$('.content_sortPagiBar .display').find('li.list').removeClass('selected');
		$.totalStorage('display', 'grid');
	}	
}

function accordionFooter(status)
{
	if(status == 'enable')
	{
		$('#footer .footer-block h4').on('click', function(e){
			$(this).toggleClass('active').parent().find('.toggle-footer').stop().slideToggle('medium');
			e.preventDefault();
		})
		$('#footer').addClass('accordion').find('.toggle-footer').slideUp('fast');
	}
	else
	{
		$('.footer-block h4').removeClass('active').off().parent().find('.toggle-footer').removeAttr('style').slideDown('fast');
		$('#footer').removeClass('accordion');
	}
}

function accordion(status)
{
	if(status == 'enable')
	{
		var accordion_selector = '#right_column .block .title_block, #left_column .block .title_block, #left_column #newsletter_block_left h4,' +
								'#left_column .shopping_cart > a:first-child, #right_column .shopping_cart > a:first-child';

		$(accordion_selector).on('click', function(e){
			$(this).toggleClass('active').parent().find('.block_content').stop().slideToggle('medium');
		});
		$('#right_column, #left_column').addClass('accordion').find('.block .block_content').slideUp('fast');
		if (typeof(ajaxCart) !== 'undefined')
			ajaxCart.collapse();
	}
	else
	{
		$('#right_column .block:not(#layered_block_left) .title_block, #left_column .block:not(#layered_block_left) .title_block, #left_column #newsletter_block_left h4').removeClass('active').off().parent().find('.block_content').removeAttr('style').slideDown('fast');
		$('#left_column, #right_column').removeClass('accordion');
	}
}
function isPlaceholer(){
    var input = document.createElement('input');
    return "placeholder" in input;
} 

function getFlexSliderSize(per_row) {
	var ww= 'md';
    if(st_responsive)
    {
        var window_width = $(window).innerWidth();
        if(window_width>=1200 && st_responsive_max==1)
            ww = 'lg';
        else if(window_width>=992)
            ww = 'md';
        else if(window_width>=768)
            ww = 'sm';
        else if(window_width>=480)
            ww = 'xs';
        else
            ww = 'xxs';
    }
    return eval('per_row.'+ww);
}

jQuery(function($){
    window_width = $(window).width();
    $('#to_top').click(function() {
    	$('body,html').animate({
    		scrollTop: 0
    	}, 'fast');
		return false;
    }); 
    $('.dropdown_wrap').hover(function(){
        // if($(this).find('.dropdown_list ul li').size())
            $(this).addClass('open');
    },function(){
        $(this).removeClass('open');
    });
    $('#footer .opener, #footer div.title_block_name').click(function(){
        $(this).closest('.block').toggleClass('open');
        return false;
    }); 
    $('.product_accordion .product_accordion_title').click(function(){
        $(this).closest('.product_accordion').toggleClass('open');
        return false;
    }); 
    $('#switch_left_column,#switch_right_column').click(function(){
        var column = $(this).attr('data-column');
        var opened = $('#'+column).hasClass('opened');
        var that_left_column = $('#'+column);
        if(!that_left_column.size())
        {
            $(this).hide();
            return false;
        }
        //
        $('#left_column,#right_column').removeClass('opened');
        $('#switch_left_column i').removeClass('icon-left-open-1').addClass('icon-right-open-1');
        $('#switch_right_column i').removeClass('icon-right-open-1').addClass('icon-left-open-1');
        
        if(!opened){
        	var that_center_column = $('#center_column');
	        if(that_left_column.height()>that_center_column.height())
	            that_center_column.height(that_left_column.height());
	        $('#'+column).addClass('opened');
	        if(column=='left_column')
	            $(this).find('i').removeClass('icon-right-open-1').addClass('icon-left-open-1');
	        else
	            $(this).find('i').removeClass('icon-left-open-1').addClass('icon-right-open-1');
	        var center_column_offset = $('#center_column').offset();
	        //if(center_column_offset.top+that_left_column.height() < $(window).scrollTop())            
	        	$('body,html').animate({
	        		scrollTop: center_column_offset.top-20
	        	}, 'fast');
        }
        else
        {
        	$('#center_column').height('auto');
        }
        
        return false;
    });
    to_top_wrap_master($(window).scrollTop() > 300);
    $('.version_switching').click(function(){
        $.getJSON(baseDir+'modules/stthemeeditor/stthemeeditor-ajax.php?action=version_switching&vs='+($(this).hasClass('vs_desktop') ? 1 : 0)+'&_ts='+new Date().getTime(),
            function(json){
                if(json.r)
                    window.location.reload(true);
            }
        ); 
        return false;
    });
    if($('#st_mega_menu_container').size())
    {
        menu_container_top = $('#st_mega_menu_container').offset().top;
        sticky_menu();
    }
    if($((st_sticky_adv==3 || st_sticky_adv==4) ? '#page_header #header' : '#st_advanced_menu_container').size())
    {
        adv_container_top = $((st_sticky_adv==3 || st_sticky_adv==4) ? '#page_header #header' : '#st_advanced_menu_container').offset().top;
        sticky_adv();
    }
    if($('#top_bar').size())
        nav_container_height = $('#top_bar').outerHeight();
    sticky_mobile_header();
});
$(window).scroll(function() {
    to_top_wrap_master($(this).scrollTop() > 300);
    sticky_menu();
    sticky_adv();
    sticky_mobile_header();
});
function sticky_menu()
{    
    if(!$.isNumeric(menu_container_top) || !st_sticky_menu)
        return false;
        
    if(window_width<991)
    {
        $('#st_mega_menu_container').removeClass('sticky fadeInDownLarge');
        $('#page_header').removeClass('has_sticky');
        return false;
    }

    var c_window_scroll_top = $(window).scrollTop();
    var page_header_dom = $('#page_header');
    if (c_window_scroll_top > menu_container_top && !page_header_dom.hasClass('has_sticky')) {
        $('#st_mega_menu_container').addClass('sticky '+(st_sticky_menu==2 ? 'fadeInDownLarge' : ''));
        page_header_dom.addClass('has_sticky');
    }
    else if(c_window_scroll_top <= menu_container_top && page_header_dom.hasClass('has_sticky')){
        $('#st_mega_menu_container').removeClass('sticky fadeInDownLarge');
        page_header_dom.removeClass('has_sticky');
    }
    return false;
}

function sticky_adv()
{    
    if(!$.isNumeric(adv_container_top) || !st_sticky_adv)
        return false;
        
    if(window_width<991)
    {
        $((st_sticky_adv==3 || st_sticky_adv==4) ? '#page_header #header' : '#st_advanced_menu_container').removeClass('sticky fadeInDownLarge');
        $('#page_header').removeClass('has_sticky');
        return false;
    }
    if($('body').hasClass('cart_popup'))
    	return false;
    var c_window_scroll_top = $(window).scrollTop();
    var page_header_dom = $('#page_header');
    if (c_window_scroll_top > adv_container_top && !page_header_dom.hasClass('has_sticky')) {
        $((st_sticky_adv==3 || st_sticky_adv==4) ? '#page_header #header' : '#st_advanced_menu_container').addClass('sticky '+((st_sticky_adv==2 || st_sticky_adv==4) ? 'fadeInDownLarge' : ''));
        page_header_dom.addClass('has_sticky');
    }
    else if(c_window_scroll_top <= adv_container_top && page_header_dom.hasClass('has_sticky')){
        $((st_sticky_adv==3 || st_sticky_adv==4) ? '#page_header #header' : '#st_advanced_menu_container').removeClass('sticky fadeInDownLarge');
        page_header_dom.removeClass('has_sticky');
    }
    return false;
}

function sticky_mobile_header()
{    
    if(!$.isNumeric(nav_container_height) || st_sticky_mobile_header<2)
        return false;
        
    var page_header_dom = $('#page_header');
    var mobile_bar_dom = $('#mobile_bar');

    var c_window_scroll_top = $(window).scrollTop();
    if (c_window_scroll_top > (nav_container_height + st_sticky_mobile_header_height) && !page_header_dom.hasClass('sticky_mh')) {
        page_header_dom.addClass('sticky_mh');
        mobile_bar_dom.addClass('fadeInDownLarge')
    }
    else if(c_window_scroll_top <= (nav_container_height + st_sticky_mobile_header_height) && page_header_dom.hasClass('sticky_mh')){
        page_header_dom.removeClass('sticky_mh');
        mobile_bar_dom.removeClass('fadeInDownLarge')
    }
    return false;
}

function to_top_wrap_master($statu)
{
    if($statu)
        $('#to_top_wrap .icon_wrap').removeClass('disabled');
    else
       $('#to_top_wrap .icon_wrap').addClass('disabled'); 
}

$(window).resize(function() {
    window_width = $(window).width();
    if(window_width>=991)
    	resetSidebar();
    if($('#st_mega_menu_container').size())
        menu_container_top = $('#st_mega_menu_container').offset().top;
    if($((st_sticky_adv==3 || st_sticky_adv==4) ? '#page_header #header' : '#st_advanced_menu_container').size())
        adv_container_top = $((st_sticky_adv==3 || st_sticky_adv==4) ? '#page_header #header' : '#st_advanced_menu_container').offset().top;
    if($('#top_bar').size())
        nav_container_height = $('#top_bar').outerHeight();
    if(st_sticky_menu)
        sticky_menu();
    if(st_sticky_adv)
        sticky_adv();
    if(st_sticky_mobile_header)
        sticky_mobile_header();
});

function pug() {
	if (!arguments || typeof arguments[0] != 'object') {
		return
	};
	var param = arguments[0],
	url = arguments[1] || window.location.href;
	for (var i in param) {
		if (!i) {
			continue
		};
		var name = i,
		val = param[i];
		var reg = new RegExp('([\\?&])((' + name + '\=)([\\w\-]+))(&?)', 'i');
		var omatch = url.match(reg);
		if (!val && omatch) { (omatch[5] && omatch[2]) ? url = url.replace(omatch[2] + '&', '') : (omatch[1] && omatch[2]) ? url = url.replace(omatch[0], '') : ''
		}
		if (val && !omatch) {
			url.indexOf('?') > -1 ? url += '&' + name + '=' + val: url += '?' + name + '=' + val
		}
		if (val && omatch && val != omatch[4]) {
			url = url.replace(omatch[2], omatch[3] + val)
		}
	};
	if (!arguments[1] && window.location.href != url) {
		if (location.hash != '') {
			url = url.replace(location.hash, '');
			url += location.hash
		}
		window.location.href = url
	} else {
		return url
	}
};

/**
*	@name							Accordion
*	@descripton						This Jquery plugin makes creating accordions pain free
*	@version						1.4
*	@requires						Jquery 1.2.6+
*
*	@author							Jan Jarfalk
*	@author-email					jan.jarfalk@unwrongest.com
*	@author-website					http://www.unwrongest.com
*
*	@licens							MIT License - http://www.opensource.org/licenses/mit-license.php
*/

(function(jQuery){
     jQuery.fn.extend({
         staccordion: function() {       
            return this.each(function() {
            	
            	var $ul						= $(this),
					elementDataKey			= 'accordiated',
					activeClassName			= 'active',
					activationEffect 		= 'slideToggle',
					panelSelector			= 'ul, div',
					activationEffectSpeed 	= 'fast',
					itemSelector			= 'li';
            	
				if($ul.data(elementDataKey))
					return false;
							
				if($(this).attr('id')=='stmobilemenu')					
					$.each($ul.find('ul.stmlevel1, ul.stmlevel2, li>div'), function(){
						$(this).data(elementDataKey, true);
						$(this).hide();
					});
				else
					$.each($ul.find('ul.mo_advanced_sub_ul, div.stmobileadvancedmenu_column'), function(){
						$(this).data(elementDataKey, true);
						$(this).hide();
					});
				
				$.each($ul.find('span.opener'), function(){
					$(this).click(function(e){
						activate(this, activationEffect);
						return void(0);
					});
					
					$(this).bind('activate-node', function(){
						$ul.find( panelSelector ).not($(this).parents()).not($(this).siblings()).slideUp( activationEffectSpeed );
						activate(this,'slideDown');
					});
				});
				
				var active = (location.hash)?$ul.find('a[href="' + location.hash + '"]')[0]:$ul.find('li.current a')[0];

				if(active){
					activate(active, false);
				}
				
				function activate(el,effect){
					
					$(el).parent( itemSelector ).siblings().removeClass(activeClassName).children( panelSelector ).slideUp( activationEffectSpeed );
					
					$(el).siblings( panelSelector )[(effect || activationEffect)](((effect == "show")?activationEffectSpeed:false),function(){
						
						if($(el).siblings( panelSelector ).is(':visible')){
							$(el).parents( itemSelector ).not($ul.parents()).addClass(activeClassName);
						} else {
							$(el).parent( itemSelector ).removeClass(activeClassName);
						}
						
						if(effect == 'show'){
							$(el).parents( itemSelector ).not($ul.parents()).addClass(activeClassName);
						}
					
						$(el).parents().show();
						//$.scrollTo($(el).parents(), 400);
					});
					
				}
				
            });
        }
    }); 
})(jQuery);
var resetSidebar = function(){
	$('body').removeClass('open_st_mobile_advanced_menu open_block_cart_mod open_blocksearch_mod open_st_mobile_menu');
	return false;
};
var stSidebar = function(id){
	var st_body = $('body');
	if(st_body.hasClass('open_'+id))
		resetSidebar();
	else
	{
		resetSidebar();
		st_body.addClass('open_'+id);
	}
	return false;
};
function bindUniform()
{
	if (!!$.prototype.uniform)
		$("select.form-control,input[type='radio'],input[type='checkbox']").not(".not_uniform").uniform();
}

// The button to increment the product value
$(document).on('click', '.s_product_quantity_up', function(e){
	e.preventDefault();
	var currentVal = parseInt($(this).parent().find('input[name=qty]').val());
	if (!isNaN(currentVal))
		$(this).parent().find('input[name=qty]').val(currentVal + 1).trigger('keyup');
	else
		$(this).parent().find('input[name=qty]').val(1);
});
 // The button to decrement the product value
$(document).on('click', '.s_product_quantity_down', function(e){
	e.preventDefault();
	var currentVal = parseInt($(this).parent().find('input[name=qty]').val());
	if (!isNaN(currentVal) && currentVal > 1)
		$(this).parent().find('input[name=qty]').val(currentVal - 1).trigger('keyup');
	else
		$(this).parent().find('input[name=qty]').val(1);
});