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
jQuery(function($){
	$('.multiple_select_add').click(function(){
	    $('.select_left option:selected', $(this).parents('td')).each(function(){
	       addAttrItem($(this).val(), $(this).text(), $(this));
	    });
		return !$('.select_left option:selected', $(this).parents('td')).remove().appendTo($(this).parents('td').next().children('select'));
	})
	$('.multiple_select_remove').click(function(){
	    $('.select_right option:selected', $(this).parents('td')).each(function(){
	       removeAttrItem($(this).val(), $(this));
	    });
		return !$('.select_right option:selected', $(this).parents('td')).remove().appendTo($(this).parents('td').prev().children('select'));
	});
    $('.select_left').dblclick(function(){
        var opt = $("option:selected",this);
        addAttrItem(opt.val(), opt.text(), opt);
        opt.appendTo($(this).parents('td').next().children('select'));
    });
    $('.select_right').dblclick(function(){
        var opt = $("option:selected",this);
        removeAttrItem(opt.val(), opt);
        $("option:selected",this).appendTo($(this).parents('td').prev().children('select'));
    });
    $('input[name^="style"]').click(function(){
        var dn = $(this).attr('name').replace(/style/ig,'');
        troggleItem($(this).val(), dn);
    });
    
    $('input[name^="style"][value="3"]').each(function(){
        if($(this).attr('checked')){
            var dn = $(this).attr('name').replace(/style/ig,'');
            troggleItem($(this).val(), dn);
        }
    });
    $('input[name^="style"][value="0"]').each(function(){
        if($(this).attr('checked')){
            var dn = $(this).attr('name').replace(/style/ig,'');
            troggleItem($(this).val(), dn);
        }
    });
    
    $('table:visible').find('.select_right option').each(function(){
        addAttrItem($(this).val(), $(this).text(), $(this));
    });
    
    $('.addAttribute').click(function(){
        $(this).parents('.advanced_attr').find('ul').toggle();
    });
    
    $('#configuration_form').submit(function()
    {
    	$('.select_right option').each(function(i){
    		$(this).attr("selected", "selected");
    	});
    });
    
    $('.st_delete_image').click(function(){
        var self = $(this);
        $.getJSON(currentIndex+'&token='+token+'&configure=staddthisbutton&act=delete_image&ajax=1&ts='+new Date().getTime(),
            function(json){
                if(json.r)
                {
                    $('.img_preview').remove();
                    self.closest('p').remove();
                }
                else
                    alert('Error');
            }
        ); 
        return false;
    });
    
    function troggleItem(v, dn)
    {
        var tables = $('table.customizing'+dn);
        troggleButton(dn, true);
        var o_h = o_s = null;
        
        if (v == 0) {
            troggleBox('customizing'+dn, true);
            tables.first().hide();
            tables.last().show();
            o_h = tables.first();
            o_s = tables.last();
        }else if(v == 3){
            troggleBox('customizing'+dn, false);
            troggleButton(dn, false);
            tables.first().hide();
            tables.last().hide();
            o_h = tables.first();
            $("option", tables.last().find('.select_right')).each(function(){
                removeAttrItem($(this).val(), $(this));
            });
        }else{
            troggleBox('customizing'+dn, true);
            tables.first().show();
            tables.last().hide();
            o_h = tables.last();
            o_s = tables.first();
        }
        
        if (o_h)
            $("option", o_h.find('.select_right')).each(function(){
                removeAttrItem($(this).val(), $(this));
            });
        if (o_s)
            $("option", o_s.find('.select_right')).each(function(){
                addAttrItem($(this).val(), $(this).text(), $(this));
            });
    }
    
    function troggleBox(id, flag){
        var obj = $('#'+id).parents('.form-group');
        return flag ? obj.show() : obj.hide();
    };
    
    function troggleButton(ext, flag){
        var obj = $(':radio[name="show_more'+ext+'"]').parents('.form-group');
        return flag ? obj.show() : obj.hide();
    }
    
    function addAttrItem(id, text, o)
    {
        var prefix = 'at_ext_';
        var value = '';
        if(o.parents('div.addthis_box').attr('id').indexOf('for_blog') > -1)
            prefix = 'for_blog_ext_';
        if (prefix == 'for_blog_ext_' && _EXT_ATTR_FOR_BLOG[id])
            value = _EXT_ATTR_FOR_BLOG[id];
        else if(_EXT_ATTR[id])
            value = _EXT_ATTR[id];
        if (!$('#'+prefix+id).size())
            o.parents('div.addthis_box').find('ul.advanced_contain').append('<li id="'+prefix+id+'"><label>'+text+' </label><textarea name="'+prefix+id+'" row = "1" col = "30" >'+value+'</textarea></li>');
    }
    
    function removeAttrItem(id, o)
    {
        var prefix = 'at_ext_';
        if(o.parents('div.addthis_box').attr('id').indexOf('for_blog') > -1)
            prefix = 'for_blog_ext_';
        $('#'+prefix+id).remove();
    }
});
