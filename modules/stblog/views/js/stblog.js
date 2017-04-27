var blog_flexslider_options;
jQuery(function($){
    var blog_flexslider_options_default = {
        slideshow: false,
        slideshowSpeed: 7000,
        animationSpeed: 400,
        pauseOnHover: true,
        animationLoop: true,
        useCSS: false,
		controlNav: false,
        prevText: '<i class="icon-angle-left icon-3x"></i>',
        nextText: '<i class="icon-angle-right icon-3x"></i>'
    };
    var blog_flexslider_options_extend = $.extend({}, blog_flexslider_options_default, blog_flexslider_options);
    $('.blog_flexslider').flexslider(blog_flexslider_options_extend); 
    
    $('.full_video').fitVids();
});