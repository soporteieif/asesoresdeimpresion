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

$(document).ready(function(){
    $("#rightbar_cart").hoverIntent({    
		 sensitivity: 3, 
		 interval: 200, 
		 over: function(){
            if($(this).css('float')=='none' || !$(this).css('float'))
            {
                $('#rightbar-cart_block').slideDown('fast');
                $.ajax({
    				type: 'POST',
    				headers: { "cache-control": "no-cache" },
    				url: baseDir + 'modules/strightbarcart/strightbarcart-ajax.php?rand=' + new Date().getTime(),
    				async: true,
    				cache: false,
			        dataType : "json",
    				data: 'action=get_cart_content',
                    success: function(jsonData)
                    {
                        $('#rightbar-cart_block .stajax_loader').hide();
                        rightbarAjaxCart.updateCart(jsonData);
                        rightbarAjaxCart.refreshVouchers(jsonData);
                        
            			$('#rightbar-cart_block .products dt:first').addClass('first_item');
            			$('#rightbar-cart_block .products dt:not(:first,:last)').addClass('item');
            			$('#rightbar-cart_block .products dt:last').addClass('last_item');
                    },
        			error: function(XMLHttpRequest, textStatus, errorThrown)
        			{
                        //error
        			}
    			});
            }
		 },
		 timeout: 0,
		 out: function(){
			$('#rightbar-cart_block').hide();
		 }
	});
});

var rightbarAjaxCart = {
    updateCart : function(jsonData) {
        if (jsonData.hasError)
    	{
    		var errors = '';
    		for(error in jsonData.errors)
    			//IE6 bug fix
    			if(error != 'indexOf')
    				errors += jsonData.errors[error] + "\n";
    		alert(errors);
    	}
    	else
    	{
            if (parseInt(jsonData.nbTotalProducts) > 0)
            {
                $('#rightbar-cart-buttons,#rightbar-cart_block_summary').show();
    		$(jsonData.products).each(function(){
    			if (this.id != undefined)
    			{
    				if ($('#rightbar-products_list dl.products').length == 0)
    				{
    					$('#rightbar-products_list').append('<dl class="products"></dl>');
    					$('#rightbar-cart_block_no_products').hide();
    				}
    				var productId = parseInt(this.id);
    				var productAttributeId = (this.hasAttributes ? parseInt(this.attributes) : 0);
    				var content =  '<dt class="clearfix" >';
                    content += '<a class="cart_block_product_image" href="' + this.link + '" title="' + this.name + '"><img src="' + this.image_link + '" alt="' + this.name + '" /></a>';
    				content += '<span class="quantity-formated"><span class="quantity">' + this.quantity + '</span>x</span>';
    				var name = (this.name.length > 27 ? this.name.substring(0, 25) + '...' : this.name);
    				content += '<a href="' + this.link + '" title="' + this.name + '">' + name + '</a>';
    				
    				if (typeof(freeShippingTranslation) != 'undefined')
    					content += '<span class="price">' + (parseFloat(this.price_float) > 0 ? this.priceByLine : freeShippingTranslation) + '</span>';
    				if (this.hasAttributes)
    					  content += '<div><a href="' + this.link + '" title="' + this.name + '">' + this.attributes + '</a>';
    				if (this.hasCustomizedDatas)
    					content += rightbarAjaxCart.displayNewCustomizedDatas(this);
    				if (this.hasAttributes) content += '</div>';
    				content += '</dt>';
    				$('#rightbar-products_list dl.products').append(content);
    			}
    		});
            
            var dom_cart_block = $('#rightbar-cart_block');
            var height_cart_block = dom_cart_block.outerHeight(true);
            var offset_top = dom_cart_block.offset().top - $(window).scrollTop();
            var height_window = $(window).height();
            if(offset_top+height_cart_block>height_window)
            {
                if(height_cart_block>height_window)
                    dom_cart_block.css('top',0-offset_top);
                else
                    dom_cart_block.css('top',(height_window - offset_top - height_cart_block));
            }
            
            }else{
                $('#rightbar-cart_block_no_products').show();
                $('#rightbar-cart-buttons,#rightbar-cart_block_summary').hide();
            }
    	}
    },
    
	displayNewCustomizedDatas : function(product)
	{
		var content = '';
		var productId = parseInt(product.id);
		var productAttributeId = typeof(product.idCombination) == 'undefined' ? 0 : parseInt(product.idCombination);
		if (!product.hasAttributes)
			content += '<div>';
		content += '<ul class="cart_block_customizations" >';
		
		$(product.customizedDatas).each(function()
		{
			var done = 0;
			customizationId = parseInt(this.customizationId);
			content += '<li name="customization"><span class="quantity-formated"><span class="quantity">' + parseInt(this.quantity) + '</span>x</span>';

			// Give to the customized product the first textfield value as name
			$(this.datas).each(function(){
				if (this['type'] == CUSTOMIZE_TEXTFIELD)
				{
					$(this.datas).each(function(){
						if (this['index'] == 0)
						{
							content += ' ' + this.truncatedValue.replace(/<br \/>/g, ' ');
							done = 1;
							return false;
						}
					})
				}
			});

			// If the customized product did not have any textfield, it will have the customizationId as name
			if (!done)
				content += customizationIdMessage + customizationId;
			content += '</li>';
		});

		content += '</ul>';
		if (!product.hasAttributes) content += '</div>';
		return (content);
	},
	//refresh display of vouchers (needed for vouchers in % of the total)
	refreshVouchers : function (jsonData) {
		if (typeof(jsonData.discounts) == 'undefined' || jsonData.discounts.length == 0)
			$('#rightbar-vouchers').remove();
		else
		{
		    if($('#rightbar-vouchers').size())
			     $('#rightbar-vouchers tbody').html('');
            else
				 $('#rightbar-cart_block_no_products').before('<table id="rightbar-vouchers"><tbody></tbody></table>');
		
			for (i=0;i<jsonData.discounts.length;i++)
			{
				if (parseFloat(jsonData.discounts[i].price_float) > 0)
				{
					$('#rightbar-vouchers tbody').append($(
						'<tr class="bloc_cart_voucher">'
						+'	<td class="quantity">1x</td>'
						+'	<td class="name" title="'+jsonData.discounts[i].description+'">'+jsonData.discounts[i].name+'</td>'
						+'	<td class="price">-'+jsonData.discounts[i].price+'</td>'
						+'	<td class="delete"></td>'
						+'</tr>'
					));
				}
			}
		}

	}
};
