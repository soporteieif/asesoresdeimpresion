jQuery(function($){
    $('.rt_size_chart td').hover(function(){
        var sc_table = $(this).closest('table');
        sc_table.find('th,td').removeClass('hover');
        var sb = $(this).addClass('hover').parent().find('td');
        sb.eq(0).addClass('hover');
        sc_table.find('th').eq($(this).index()).addClass('hover');
    });
    $('.rt_size_chart').mouseout(function(){
        $(this).find('th,td').removeClass('hover');
    });
});