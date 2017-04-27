<?php
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

if (!defined('_PS_VERSION_'))
	exit;

class StProductLinkNav extends Module
{
	public function __construct()
	{
		$this->name          = 'stproductlinknav';
		$this->tab           = 'front_office_features';
		$this->version       = '1.0';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
        $this->bootstrap 	 = true;
		
		parent::__construct();
		
		$this->displayName = $this->l('Next and previous links on the product page');
		$this->description = $this->l('This module adds Next and Previous links on the product page.');
	}

	public function install()
	{
		if (!parent::install() 
			|| !$this->registerHook('displayHeader')
			|| !$this->registerHook('displayAnywhere')
			|| !$this->registerHook('displayFooterProduct')
            )
			return false;
		return true;
	}
         
    public function hookDisplayHeader()
    {        
		//$this->context->controller->addCSS($this->_path.'views/css/stproductlinknav.css');
		//$this->context->controller->addJS($this->_path.'views/js/stproductlinknav.js');
        return false;
    }
    public function hookDisplayFooterProduct($params)
    {
        if(isset($params['category']->id_category))
			$this->context->cookie->nav_last_visited_category = (int)$params['category']->id_category;
    }
    
    
    private function _prepareHook($nav)
    {
        $id_product = (int)Tools::getValue('id_product');
		if (!$id_product)
			return false;
        
        $product = new Product($id_product, false, (int)$this->context->language->id);
        if(!Validate::isLoadedObject($product))
            return false;
        
        $id_lang = $this->context->language->id;
        
        if (!isset($this->context->cookie->nav_last_visited_category) || !Product::idIsOnCategoryId($id_product, array('0' => array('id_category' => $this->context->cookie->nav_last_visited_category))))
		  $this->context->cookie->nav_last_visited_category = (int)($product->id_category_default);
          
        $curr_position = $this->getWsPositionInCategory($id_product, $this->context->cookie->nav_last_visited_category);
        if(!Validate::isUnsignedInt($curr_position))
            return false;
        $sql = 'SELECT p.`id_product`,p.`ean13`,
            pl.`link_rewrite`,pl.`name`,
            product_shop.`id_category_default`, 
            MAX(image_shop.`id_image`) id_image
        FROM `'._DB_PREFIX_.'category_product` cp
		LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
		'.Shop::addSqlAssociation('product', 'p').'
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
			ON (p.`id_product` = pl.`id_product`
			AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
		LEFT JOIN `'._DB_PREFIX_.'image` i
			ON (i.`id_product` = p.`id_product`)'.
		Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il
			ON (image_shop.`id_image` = il.`id_image`
			AND il.`id_lang` = '.(int)$id_lang.')
        WHERE product_shop.`id_shop` = '.(int)$this->context->shop->id.'
		AND cp.`id_category` = '.(int)$this->context->cookie->nav_last_visited_category.'
		AND product_shop.`active` = 1
        AND product_shop.`visibility` IN ("both", "catalog") 
        AND cp.`position` '.($nav=='next' ? '>' : '<').$curr_position.'
        GROUP BY product_shop.id_product
        ORDER BY cp.`position` '.($nav=='next' ? 'ASC' : 'DESC');
        if($product = Db::getInstance()->getRow($sql))
        {  
            $product['category'] =  Category::getLinkRewrite((int)$product['id_category_default'], (int)$id_lang);
    		$this->context->smarty->assign(array(
                'nav_product' => $product,
                'mediumSize'=>Image::getSize(ImageType::getFormatedName('medium')),
                'nav'=>$nav,
            ));
            return true;
        }    
        else
            return false;
    }
    
    public function hookDisplayAnywhere($params)
    {
	    if(!isset($params['caller']) || $params['caller']!=$this->name)
            return false;
        if( Dispatcher::getInstance()->getController() != 'product' )
            return false;
        if(isset($params['nav']) && ($params['nav']=='prev' || $params['nav']=='next'))
        {
        	if(!$this->_prepareHook($params['nav']))
                return false;
		    return $this->display(__FILE__, 'stproductlinknav.tpl');
        }
        else
            return false;
    }
    public function getWsPositionInCategory($id_product = 0, $id_category = 0)
	{
		$result = Db::getInstance()->executeS('SELECT position
			FROM `'._DB_PREFIX_.'category_product`
			WHERE id_category = '.(int)$id_category.'
			AND id_product = '.(int)$id_product);
		if (count($result) > 0)
			return $result[0]['position'];
		return '';
	}
}