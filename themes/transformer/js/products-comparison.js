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
	$(document).on('click', '.add_to_compare', function(e){
		e.preventDefault();
		if (typeof addToCompare != 'undefined')
			addToCompare(parseInt($(this).data('id-product')),this);
	});

	$(document).on('click', '#layer_compare .cross, #layer_compare .continue, .layer_compare_overlay', function(e){
		e.preventDefault();
		$('.layer_compare_overlay').hide();
		$('#layer_compare').fadeOut('fast');
	});

	reloadProductComparison();
	compareButtonsStatusRefresh();
	totalCompareButtons();
});

function addToCompare(productId,callerElement)
{
	if (contentOnly)
		var dom_compare_quantity = $('#rightbar_compare .compare_quantity', window.parent.document);
	else
		var dom_compare_quantity = $('#rightbar_compare .compare_quantity');

    var totalValueNow = parseInt(dom_compare_quantity.html(),10);
    if(isNaN(totalValueNow))
        totalValueNow=0;

	var action, totalVal;
	if ($.inArray(parseInt(productId),comparedProductsIds) === -1 && $.inArray(''+productId,comparedProductsIds) === -1)
		action = 'add';
	else
		action = 'remove';
	
	$(callerElement).addClass('active');
	$.ajax({
		url: baseUri + '?controller=products-comparison&ajax=1&action=' + action + '&id_product=' + productId,
		async: true,
		cache: false,
		success: function(data) {
			$(callerElement).removeClass('active');
	        var pro_name = $(callerElement).attr('data-product-name');
	        var pro_cover = $(callerElement).attr('data-product-cover');
        	$('#compare_add_success,#compare_remove_success').addClass('hidden');

			if (action === 'add' && comparedProductsIds.length < comparator_max_item) {
				comparedProductsIds.push(parseInt(productId)),
				compareButtonsStatusRefresh(),
				totalVal = totalValueNow +1;
				if (contentOnly)
					window.parent.totalValue(totalVal);
				else
					totalValue(totalVal);

        		$('#compare_add_success').removeClass('hidden');
				$('#layer_compare_product_title').text(pro_name);
				$('.layer_compare_img').html('<img class="layer_compare_img img-responsive" src="' + pro_cover + '" alt="' + pro_name + '" title="' + pro_name + '" />');

				var n = parseInt($(window).scrollTop()) + 100 + 'px';

				$('.layer_compare_overlay').css('width','100%');
				$('.layer_compare_overlay').css('height','100%');
				$('.layer_compare_overlay').show();
				if (contentOnly)
					$('.layer_compare_btn').hide();
				$('#layer_compare').css({'top': n}).fadeIn('fast');

			}
			else if (action === 'remove') {
				var compare_index_of = $.inArray(parseInt(productId), comparedProductsIds)===-1 ? $.inArray(''+productId,comparedProductsIds) : $.inArray(parseInt(productId),comparedProductsIds);
				comparedProductsIds.splice(compare_index_of, 1),
				compareButtonsStatusRefresh(),
				totalVal = totalValueNow -1;
				if (contentOnly)
					window.parent.totalValue(totalVal);
				else
					totalValue(totalVal);

        		$('#compare_remove_success').removeClass('hidden');
				$('#layer_compare_product_title').text(pro_name);
				$('.layer_compare_img').html('<img class="layer_compare_img img-responsive" src="' + pro_cover + '" alt="' + pro_name + '" title="' + pro_name + '" />');

				var n = parseInt($(window).scrollTop()) + 100 + 'px';

				$('.layer_compare_overlay').css('width','100%');
				$('.layer_compare_overlay').css('height','100%');
				$('.layer_compare_overlay').show();
				if (contentOnly)
					$('.layer_compare_btn').hide();
				$('#layer_compare').css({'top': n}).fadeIn('fast');
			}
			else
			{
				if (!!$.prototype.fancybox)
					$.fancybox.open([
						{
							type: 'inline',
							autoScale: true,
							minHeight: 30,
							content: '<p class="fancybox-error">' + max_item + '</p>'
						}
					], {
						padding: 0
					});
				else
					alert(max_item);
			}
			totalCompareButtons();
		},
		error: function(){
			$(callerElement).removeClass('active');
		}
	});
}

function reloadProductComparison()
{
	$(document).on('click', 'a.cmp_remove', function(e){
		e.preventDefault();
		var idProduct = parseInt($(this).data('id-product'));
		$.ajax({
			url: baseUri + '?controller=products-comparison&ajax=1&action=remove&id_product=' + idProduct,
			async: false,
			cache: false
		});
		$('td.product-' + idProduct).fadeOut(600);

		var compare_product_list = products_comparision_get('compare_product_list');
		var bak = compare_product_list;
		var new_compare_product_list = [];
		compare_product_list = decodeURIComponent(compare_product_list).split('|');
		for (var i in compare_product_list)
			if (parseInt(compare_product_list[i]) != idProduct)
				new_compare_product_list.push(compare_product_list[i]);
		if (new_compare_product_list.length)
			window.location.search = window.location.search.replace(bak, new_compare_product_list.join(encodeURIComponent('|')));

		var dom_compare_quantity = $('#rightbar_compare .compare_quantity');
	    var totalValueNow = parseInt(dom_compare_quantity.html(),10);
	    if(isNaN(totalValueNow))
	        totalValueNow=0;
	    if(totalValueNow)
			if (contentOnly)
				window.parent.totalValue(totalValueNow-1);
			else
				totalValue(totalValueNow-1);
	});
};

function compareButtonsStatusRefresh()
{
	$('.add_to_compare').each(function() {
		if ($.inArray(parseInt($(this).data('id-product')), comparedProductsIds) !== -1 || $.inArray(''+$(this).data('id-product'), comparedProductsIds) !== -1)
			$(this).addClass('checked');
		else
			$(this).removeClass('checked');
	});
}

function totalCompareButtons()
{
	var totalProductsToCompare = parseInt($('.bt_compare .total-compare-val').html());
	if (typeof totalProductsToCompare !== "number" || totalProductsToCompare === 0)
		$('.bt_compare').attr("disabled",true);
	else
		$('.bt_compare').attr("disabled",false);
}

function totalValue(value)
{
    var dom_compare_quantity = $('#rightbar_compare .compare_quantity');
	if(value>0)
		dom_compare_quantity.html(value).show();
	else
		dom_compare_quantity.html(0).hide();
}

function products_comparision_get(name)
{
	var regexS = "[\\?&]" + name + "=([^&#]*)";
	var regex = new RegExp(regexS);
	var results = regex.exec(window.location.search);
	if (results == null)
		return "";
	else
		return results[1];
}