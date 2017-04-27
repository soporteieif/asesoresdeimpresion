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

class StRelatedProductsClass
{
	
	public static function saveRelatedProducts($id_product_1,$related_products_id)
	{
	    if(!Validate::isUnsignedId($id_product_1))
            return false;
	    $res = true;
		foreach ($related_products_id as $id_product_2)
            if(Validate::isUnsignedId($id_product_2))
                $res &= Db::getInstance()->insert('st_related_products', array(
    				'id_product_1' => (int)$id_product_1,
    				'id_product_2' => (int)$id_product_2,
    			));
        return $res;
	}
	public static function deleteRelatedProducts($id_product)
	{
		return Db::getInstance()->delete('st_related_products', 'id_product_1 = '.(int)$id_product);
	}
	public static function deleteFromRelatedProducts($id_product)
	{
		return Db::getInstance()->delete('st_related_products', 'id_product_2 = '.(int)$id_product);
	}
    public static function getRelatedProductsLight($id_lang, $id_product)
	{
		$sql = 'SELECT p.`id_product`, p.`reference`, pl.`name`
				FROM `'._DB_PREFIX_.'st_related_products`
				LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.`id_product`= `id_product_2`)
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				)
				WHERE `id_product_1` = '.(int)$id_product;

		return Db::getInstance()->executeS($sql);
	}
    
    public static function getRelatedProducts($id_product)
	{
		$sql = 'SELECT p.`id_product`
				FROM `'._DB_PREFIX_.'st_related_products`
				LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.`id_product`= `id_product_2`)
				'.Shop::addSqlAssociation('product', 'p').'
				WHERE `id_product_1` = '.(int)$id_product;
        $result = array();
        $data = Db::getInstance()->executeS($sql);
        if(is_array($data) && count($data))
            foreach($data as $v)
                $result[] = $v['id_product'];
		return $result;
	} 
}

?>
