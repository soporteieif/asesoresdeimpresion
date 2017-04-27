if (typeof $.uniform.defaults !== 'undefined')
{
    if (typeof stblogcomments_fileDefaultHtml !== 'undefined')
        $.uniform.defaults.fileDefaultHtml = stblogcomments_fileDefaultHtml;
    if (typeof stblogcomments_fileButtonHtml !== 'undefined')
        $.uniform.defaults.fileButtonHtml = stblogcomments_fileButtonHtml;
}
jQuery(function($) {
    if(!isPlaceholer())
    {
        $('#comment_input input').each(function(){
            $(this).focusin(function(){
                if ($(this).val()==$(this).attr('placeholder'))
                    $(this).val('');
            }).focusout(function(){
                if ($(this).val()=='')
                    $(this).val($(this).attr('placeholder'));
            });
        });
    }
	$('form[name=st_blog_comment_form]').submit(function(e) {
        e.preventDefault();
        var is_success = false;
		// Form element
        var sub_btn = $('#st_blog_comment_submit');
        if(sub_btn.hasClass('disabled'))
            return false;
        else
            sub_btn.addClass('disabled');
        
		$.ajax({
			url: $('form[name=st_blog_comment_form]').attr('action'),
			type: 'POST',
			headers: { "cache-control": "no-cache" },
            dataType: 'json',
            data: $('form[name=st_blog_comment_form]').serialize(),
            cache: false,
			success: function(json){
                sub_btn.removeClass('disabled');
				if (json.r)
				{
				    is_success = true;

                    if (!!$.prototype.fancybox)
                        $.fancybox.open([
                            {
                                type: 'inline',
                                autoScale: true,
                                minHeight: 30,
                                afterClose: function(){
                                    if(is_success)
                                        window.location.reload(true);
                                    return true;
                                },
                                content: '<p class="fancybox-error">' + stblogcomments_thank + (stblogcomments_moderate ? '<br/>' + stblogcomments_moderation : '') + '</p>'
                            }
                        ], {
                            padding: 0
                        });
                    else
                        alert(added_to_wishlist);
				}
				else
				{
                    if (!!$.prototype.fancybox)
                        $.fancybox.open([
                            {
                                type: 'inline',
                                autoScale: true,
                                minHeight: 30,
                                content: '<p class="fancybox-error">' + json.m + '</p>'
                            }
                        ], {
                            padding: 0
                        });
                    else
                        alert(json.m);
				}
			}
		});
		return false;
	});
    $('.comment_reply_link').click(function(){
        var id_st_blog_comment = $(this).attr('data-id-st-blog-comment');
        if(id_st_blog_comment)
            stblogcomments.move_to(id_st_blog_comment);
    });
    $('#cancel_comment_reply_link').click(function(){
        stblogcomments.move_back();
    });
});
var stblogcomments = {
    'move_to' : function(id_st_blog_comment)
    {
        $('#comment-'+id_st_blog_comment+' > .comment_node').after($('#st_blog_comment_reply_block').get(0));
        $('#blog_comment_parent_id').val(id_st_blog_comment);
        $('#cancel_comment_reply_link').show();
    },
    'move_back' : function()
    {
        $('#comments').after($('#st_blog_comment_reply_block').get(0));
        $('#cancel_comment_reply_link').hide();
        $('#blog_comment_parent_id').val(0);
    }
};