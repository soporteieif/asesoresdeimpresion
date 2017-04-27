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
if(typeof(blocksearch_type)=='undefined')
	var blocksearch_type = 'top';

if(typeof(blocksearch_hide_image)=='undefined')
	var blocksearch_hide_image = 0;

var instantSearchQueries = [];
$(document).ready(function()
{
	if (typeof blocksearch_type == 'undefined')
		return;
	var $input = $("#search_query_" + blocksearch_type);

	$input.focus(function(){
	     $(this).parent().addClass('active');
	}).blur(function(){
	     $(this).parent().removeClass('active');
	});


	var width_ac_results = 	$input.parent('form').outerWidth();
	if (typeof ajaxsearch != 'undefined' && ajaxsearch)
	{
		$input
		.focus(function(){
		     $(this).parent().addClass('active');
		})
		.blur(function(){
		     $(this).parent().removeClass('active');
		});
		var search_query_autocomplete = $input.autocomplete(
			search_url,
			{
				minChars: 3,
				max: 10,
				width: ($input.parent().outerWidth() > 0 ? $input.parent().outerWidth() : 306),
				selectFirst: false,
				scroll: false,
				dataType: "json",
				formatItem: function(data, i, max, value, term) {
					return value;
				},
				parse: function(data) {
					if($('#search_block_top').hasClass('quick_search_simple'))
                        search_query_autocomplete.setOptions({'width':$('#search_block_top').outerWidth()+$('#search_query_top').outerWidth()});
                    else
                        search_query_autocomplete.setOptions({'width':$("#search_block_" + blocksearch_type).outerWidth()});
					var mytab = new Array();
					for (var i = 0; i < data.length; i++)
                    if(i==6){
						data[i].pname = 'searchboxsubmit';
						data[i].product_link = $('#search_query_' + blocksearch_type).val();
						mytab[mytab.length] = { data: data[i], value:  '<div id="ac_search_more"> ' + $("#more_prod_string").html()+' </div>'};
                        break;
					}else
					    mytab[mytab.length] = { data: data[i], value:  (blocksearch_hide_image ? '' : '<img src="'+ data[i].pthumb + '" alt="'  + data[i].pname + '" />')+'<span class="ac_product_name">'  + data[i].pname + ' </span> '};
					return mytab;
				},
				extraParams: {
					ajaxSearch: 1,
					id_lang: id_lang
				}
			}
		)
		.result(function(event, data, formatted) {
			if(data.pname=='searchboxsubmit'){
				$('#search_query_' + blocksearch_type).val(data.product_link);
                $("#searchbox").submit();
            }else{
				$('#search_query_' + blocksearch_type).val(data.pname);
				document.location.href = data.product_link;
            }
		});
	}

	if (typeof instantsearch != 'undefined' && instantsearch)		
		$input.keyup(function(){
			if($(this).val().length > 4)
			{
				stopInstantSearchQueries();
				instantSearchQuery = $.ajax({
					url: search_url + '?rand=' + new Date().getTime(),
					data: {
						instantSearch: 1,
						id_lang: id_lang,
						q: $(this).val()
					},
					dataType: 'html',
					type: 'POST',
					headers: { "cache-control": "no-cache" },
					async: true,
					cache: false,
					success: function(data){
						if($input.val().length > 0)
						{
							tryToCloseInstantSearch();
							$('#center_column').attr('id', 'old_center_column');
							$('#old_center_column').after('<div id="center_column" class="' + $('#old_center_column').attr('class') + '">' + data + '</div>').hide();
							// Button override
							ajaxCart.overrideButtonsInThePage();
							$("#instant_search_close").on('click', function() {
								$input.val('');
								return tryToCloseInstantSearch();
							});
							return false;
						}
						else
							tryToCloseInstantSearch();
					}
				});
				instantSearchQueries.push(instantSearchQuery);
			}
			else
				tryToCloseInstantSearch();
		});
	
});

function tryToCloseInstantSearch()
{
	var $oldCenterColumn = $('#old_center_column');
	if ($oldCenterColumn.length > 0)
	{
		$('#center_column').remove();
		$oldCenterColumn.attr('id', 'center_column').show();
		return false;
	}
}

function stopInstantSearchQueries()
{
	for(var i=0; i<instantSearchQueries.length; i++)
		instantSearchQueries[i].abort();
	instantSearchQueries = [];
}