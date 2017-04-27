$(document).ready(function(){
    reHoverImage();
});
function reHoverImage(){
	$('.pro_first_box').hover(function(){
        if($('.back-image',this).size())
            $(this).addClass('showhoverimage');
    },function(){
        $(this).removeClass('showhoverimage');
    });
}