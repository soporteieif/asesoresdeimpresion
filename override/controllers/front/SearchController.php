<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
class SearchController extends SearchControllerCore
{
	/*
    * module: stoverride
    * date: 2017-04-17 10:26:35
    * version: 1.2.0
    */
    public function initContent()
	{
		$query = Tools::replaceAccentedChars(urldecode(Tools::getValue('q')));
		if ($this->ajax_search)
		{
		    $image = new Image();
			$searchResults = Search::find((int)(Tools::getValue('id_lang')), $query, 1, 10, 'position', 'desc', true);
			foreach ($searchResults as &$product)
			{
                $product['product_link'] = $this->context->link->getProductLink($product['id_product'], $product['prewrite'], $product['crewrite']);
                $imageID = $image->getCover($product['id_product']);
        	    if(isset($imageID['id_image']))
                    $product['pthumb'] = $this->context->link->getImageLink($product['prewrite'], (int)$product['id_product'].'-'.$imageID['id_image'], 'small_default');
                else
                    $product['pthumb'] = _THEME_PROD_DIR_.$this->context->language->iso_code."-default-small_default.jpg";
			}
			die(Tools::jsonEncode($searchResults));
		}
		parent::initContent();
	}
}
