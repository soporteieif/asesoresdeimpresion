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

class StEasyTabsClass extends ObjectModel
{
	public $id;

	public $id_shop;
	public $id_category;
	public $id_product;
	public $id_product_specific;
    public $id_manufacturer;
	public $allitems;
	public $position;
	public $active;
	public $content;
	public $title;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table'   => 'st_easy_tabs',
		'primary' => 'id_st_easy_tabs',
		'multilang' => true,
		'fields' => array(
            'id_shop'         => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_category'     => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'id_product'      => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'id_product_specific'      => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_manufacturer'          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'allitems'        => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'position'        => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'active'          => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'content'         => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isAnything'),
			'title'           => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
		)
	);
    public static function getListContent($id_lang)
	{
		return  Db::getInstance()->executeS('
			SELECT ret.*, retl.`content`, retl.`title`
			FROM `'._DB_PREFIX_.'st_easy_tabs` ret
			LEFT JOIN `'._DB_PREFIX_.'st_easy_tabs_lang` retl ON (ret.`id_st_easy_tabs` = retl.`id_st_easy_tabs`)
			WHERE ret.`id_product_specific`=0 AND retl.`id_lang` = '.(int)$id_lang.' '.Shop::addSqlRestrictionOnLang('ret')
        );
	}
    public static function getTabs($field,$id_product,$id_category,$id_manufacturer,$id_lang)
    {
        $categories = array();
        $context = Context::getContext();
        if (isset($context->cookie->last_visited_category) && (int)$context->cookie->last_visited_category)
            $id_category = $context->cookie->last_visited_category;
        if($id_category)
        {
            foreach(Product::getProductCategories($id_product) AS $id_cate)
            {
                $category = new Category($id_cate);
                if (!$category->active)
                    continue;
                if($category->id)
                {
                    $id_array = array();
                    $category_parents = $category->getParentsCategories();
                    if(is_array($category_parents) && count($category_parents))
                        foreach($category_parents as $v)
                            $id_array[] = $v['id_category'];
                    if (in_array($id_category, $id_array))
                        $categories = array_merge($categories, $id_array);
                }    
            }
        }
        $where = ' ret.`allitems`=1 ';
        if(count($categories))
            $where .= ' OR ret.`id_category` IN ('.implode(',',array_unique($categories)).')';
        if($id_product)
            $where .= ' OR ret.`id_product`='.(int)$id_product.' OR ret.`id_product_specific`='.(int)$id_product;
        if ($id_manufacturer)
            $where .= ' OR ret.`id_manufacturer`='.(int)$id_manufacturer;
            
        return Db::getInstance()->executeS('
			SELECT ret.`id_st_easy_tabs`,'.$field.'
			FROM `'._DB_PREFIX_.'st_easy_tabs` ret
			LEFT JOIN `'._DB_PREFIX_.'st_easy_tabs_lang` retl ON (ret.`id_st_easy_tabs` = retl.`id_st_easy_tabs`)
			WHERE ret.`active`=1 AND ('.$where.') AND retl.`id_lang` = '.(int)$id_lang.' '.Shop::addSqlRestrictionOnLang('ret').' ORDER BY ret.position'
		);
    }
    public static function getIdBySpecific($id_product)
    {
        if(!$id_product)
            return false;
        return Db::getInstance()->getValue('
			SELECT ret.`id_st_easy_tabs`
			FROM `'._DB_PREFIX_.'st_easy_tabs` ret
			WHERE ret.`id_product_specific`='.(int)$id_product.Shop::addSqlRestrictionOnLang('ret')
		);
    }
    public static function deleteByProductId($id_product)
    {
        if(!$id_product)
            return false;
        $ret = true;
        $tabs = Db::getInstance()->executeS('
            SELECT `id_st_easy_tabs` 
            FROM `'._DB_PREFIX_.'st_easy_tabs` 
            WHERE `id_product` ='.(int)$id_product
        );
        foreach($tabs AS $tab)
        {
            $model = new StEasyTabsClass((int)$tab['id_st_easy_tabs']);
            if ($model->id)
                $ret &= $model->delete();
        }
        
        return $ret;
    }
    public static function deleteByCategoryId($id_category)
    {
        if(!$id_category)
            return false;
        $ret = true;
        $tabs = Db::getInstance()->executeS('
            SELECT `id_st_easy_tabs` 
            FROM `'._DB_PREFIX_.'st_easy_tabs` 
            WHERE `id_category` ='.(int)$id_category
        );
        foreach($tabs AS $tab)
        {
            $model = new StEasyTabsClass((int)$tab['id_st_easy_tabs']);
            if ($model->id)
                $ret &= $model->delete();
        }
        
        return $ret;
    }
	public function copyFromPost()
	{
		/* Classical fields */
		foreach ($_POST AS $key => $value)
			if (key_exists($key, $this) AND $key != 'id_'.$this->table)
				$this->{$key} = $value;

		/* Multilingual fields */
		if (sizeof($this->fieldsValidateLang))
		{
			$languages = Language::getLanguages(false);
			foreach ($languages AS $language)
				foreach ($this->fieldsValidateLang AS $field => $validation)
					if (isset($_POST[$field.'_'.(int)($language['id_lang'])]))
						$this->{$field}[(int)($language['id_lang'])] = $_POST[$field.'_'.(int)($language['id_lang'])];
		}
	}
}