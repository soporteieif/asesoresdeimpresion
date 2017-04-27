jQuery(function($){
    var stadvancedmenu_down_timer;
    function advancedMegaHoverOver(){
        $(this).addClass('current');
        if($(this).find('.stadvancedmenu_sub').children().size())
        {
            var stmenu_sub_dom = $(this).find(".stadvancedmenu_sub");
            stmenu_sub_dom.stop();
            stadvancedmenu_down_timer = setTimeout(function(){
                if(typeof(st_adv_submemus_animation) !== 'undefined' && st_adv_submemus_animation)
                    stmenu_sub_dom.slideDown('fast',function(){
                      stmenu_sub_dom.css('overflow','visible');
                    });
                else
                    stmenu_sub_dom.fadeIn('fast',function(){
                      stmenu_sub_dom.css('overflow','visible');
                    });
            },100);
        }
    }
    function advancedMegaHoverOut(){ 
        clearTimeout(stadvancedmenu_down_timer);
        $(this).removeClass('current');
        $(this).find(".stadvancedmenu_sub").stop().hide(); 
    }
    $(".advanced_ml_level_0").hoverIntent({    
         sensitivity: 7, 
         interval: 0, 
         over: advancedMegaHoverOver,
         timeout: 0,
         out: advancedMegaHoverOut
    });

    if(('ontouchstart' in document.documentElement || window.navigator.msMaxTouchPoints))
    {
        $(".advanced_ma_level_0").click(function(e){
            var ml_level_0 = $(this).parent();
            if(ml_level_0.find('.stadvancedmenu_sub').children().size())
            {
                if(!ml_level_0.hasClass('advanced_ma_touched'))
                {
                    $(".advanced_ml_level_0").removeClass('advanced_ma_touched');
                    ml_level_0.addClass('advanced_ma_touched');
                    return false;
                }
                else
                    ml_level_0.removeClass('advanced_ma_touched');
            }
        });
        $('.stadvancedmenu_sub .has_children').click(function(e){
            if(!$(this).hasClass('item_touched'))
            {
                $(".stadvancedmenu_sub .menu_touched").removeClass('item_touched');
                $(this).addClass('item_touched');
                return false;
            }
            else
                $(this).removeClass('item_touched');
        });
    }
    $("#stmobileadvancedmenu_tri").click(function(){
        stSidebar('st_mobile_advanced_menu');
        return false;
    });
});