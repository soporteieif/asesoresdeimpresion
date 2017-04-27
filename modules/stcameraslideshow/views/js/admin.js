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
    $('.fontOptions').trigger('change');
});
var handle_font_change = function(that,systemFonts)
{
    var systemFontsArr = systemFonts.split(',');
    var selected_font = $(that).val();
    var identi = $(that).attr('id');
    if(!$('#'+identi+'_link').size())
        $('head').append('<link id="'+identi+'_link" rel="stylesheet" type="text/css" href="" />');
    if($.inArray(selected_font, systemFontsArr)<0)
        $('link#'+identi+'_link').attr({href:'http://fonts.googleapis.com/css?family=' + selected_font.replace(' ', '+')});
    $('#'+identi+'_example').css('font-family',selected_font);
    
};