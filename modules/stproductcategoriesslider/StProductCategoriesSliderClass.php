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

class StProductCategoriesSliderClass extends ObjectModel
{
	/** @var integer reinsurance id*/
	public $id;
	
	/** @var integer */
	public $id_shop;
    
	/** @var integer */
	public $id_category;
    
	/** @var integer */
	public $product_nbr;
    
	/** @var integer */
	public $product_order;
    
	/** @var integer*/
	public $active;
	
	/** @var integer */
	public $position;
    
	/** @var integer */
	public $display_on;
    /** @var string */
    public $top_spacing; 
    /** @var string */
    public $bottom_spacing; 
    /** @var string */
    public $top_padding; 
    /** @var string */
    public $bottom_padding; 
	/** @var integer */
	public $bg_pattern;
    /** @var string */
    public $bg_img; 
    /** @var string */
    public $bg_color; 
    /** @var float */
    public $speed; 
    /** @var string */
    public $title_color; 
    /** @var string */
    public $title_hover_color; 
    /** @var string */
    public $text_color; 
    /** @var string */
    public $price_color; 
    /** @var string */
    public $grid_hover_bg; 
    /** @var string */
    public $link_hover_color; 
    /** @var string */
    public $direction_color; 
    /** @var string */
    public $direction_bg; 
    /** @var string */
    public $direction_hover_bg; 
    /** @var string */
    public $direction_disabled_bg; 

	/** @var integer*/
	public $title_alignment;
	/** @var integer*/
	public $title_no_bg;
	/** @var integer*/
	public $title_font_size;
	/** @var integer*/
	public $direction_nav; 

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table'     => 'st_product_categories_slider',
		'primary'   => 'id_st_product_categories_slider',
		'fields'    => array(
			'id_shop'               => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_category'           => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'product_nbr'           => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'product_order'         => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'active'                => array('type' => self::TYPE_INT, 'validate' => 'isBool',),
			'position'              => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'display_on'            => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'top_spacing'           => array('type' => self::TYPE_STRING, 'size' => 10),
			'bottom_spacing'        => array('type' => self::TYPE_STRING, 'size' => 10),
			'top_padding'           => array('type' => self::TYPE_STRING, 'size' => 10),
			'bottom_padding'        => array('type' => self::TYPE_STRING, 'size' => 10),
			'bg_pattern'            => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'bg_img'                => array('type' => self::TYPE_STRING, 'size' => 255),
			'bg_color'              => array('type' => self::TYPE_STRING, 'size' => 7),
			'speed'                 => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
			'title_color'           => array('type' => self::TYPE_STRING, 'size' => 7),
			'title_hover_color'     => array('type' => self::TYPE_STRING, 'size' => 7),
			'text_color'            => array('type' => self::TYPE_STRING, 'size' => 7),
			'price_color'           => array('type' => self::TYPE_STRING, 'size' => 7),
			'grid_hover_bg'      	=> array('type' => self::TYPE_STRING, 'size' => 7),
			'link_hover_color'      => array('type' => self::TYPE_STRING, 'size' => 7),
			'direction_color'       => array('type' => self::TYPE_STRING, 'size' => 7),
			'direction_bg'          => array('type' => self::TYPE_STRING, 'size' => 7),
			'direction_hover_bg'    => array('type' => self::TYPE_STRING, 'size' => 7),
			'direction_disabled_bg' => array('type' => self::TYPE_STRING, 'size' => 7),
			'title_alignment'       => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'title_no_bg'       	=> array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'title_font_size'       => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'direction_nav'         => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
		)
	);
    
	public static function getListContent($active=0,$display_on=0)
	{
		return  Db::getInstance()->executeS('
			SELECT spcs.*
			FROM `'._DB_PREFIX_.'st_product_categories_slider` spcs
			WHERE 1 '.Shop::addSqlRestrictionOnLang('spcs').($active ? ' AND spcs.`active`=1 ' : '').($display_on ? ' AND spcs.`display_on`&'.(int)$display_on : '').'
            ORDER BY spcs.`position`');
	}
    public static function deleteByCategoryId($id_category)
    {
        if(!$id_category)
            return false;
        return Db::getInstance()->execute('
            DELETE 
            FROM `'._DB_PREFIX_.'st_product_categories_slider`
            WHERE `id_category` ='.(int)$id_category.Shop::addSqlRestrictionOnLang()
        );
    }
	public function copyFromPost()
	{
		/* Classical fields */
		foreach ($_POST AS $key => $value)
			if (key_exists($key, $this) AND $key != 'id_'.$this->table)
				$this->{$key} = $value;
	}
    
    public function updatePosition($way, $position)
	{
		if (!$res = Db::getInstance()->executeS('
			SELECT `id_st_product_categories_slider`, `position`
			FROM `'._DB_PREFIX_.'st_product_categories_slider`
			ORDER BY `position` ASC'
		))
			return false;

		foreach ($res as $item)
			if ((int)$item['id_st_product_categories_slider'] == (int)$this->id)
				$moved_item = $item;

		if (!isset($moved_item) || !isset($position))
			return false;

		return (Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'st_product_categories_slider`
			SET `position`= `position` '.($way ? '- 1' : '+ 1').'
			WHERE `position`
			'.($way
				? '> '.(int)$moved_item['position'].' AND `position` <= '.(int)$position
				: '< '.(int)$moved_item['position'].' AND `position` >= '.(int)$position))
		&& Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'st_product_categories_slider`
			SET `position` = '.(int)$position.'
			WHERE `id_st_product_categories_slider` = '.(int)$moved_item['id_st_product_categories_slider']));
	}
    
    public function checkPostion()
    {
        $check = Db::getInstance()->getValue('
			SELECT count(0)
			FROM `'._DB_PREFIX_.'st_product_categories_slider` 
			WHERE `position`='.$this->position.($this->id ? ' AND `id_st_product_categories_slider`!='.$this->id : '')
		);
        if($check)
            return Db::getInstance()->getValue('
    			SELECT `position`+1
    			FROM `'._DB_PREFIX_.'st_product_categories_slider` 
                ORDER BY `position` DESC'
    		);
        return $this->position;
    }
    public static function getOptions()
    {
        return Db::getInstance()->executeS('
            SELECT * 
            FROM `'._DB_PREFIX_.'st_product_categories_slider` 
            WHERE `active` = 1 
        ');
    }

    public static function fetchMediaServer(&$slider)
    {
        $fields = array('bg_img','bg_pattern');
        if (is_string($slider) && $slider)
        {
            if(strpos($slider, '/modules/') === false)
                $slider = _THEME_PROD_PIC_DIR_.$slider;
            $slider = context::getContext()->link->protocol_content.Tools::getMediaServer($slider).$slider;
        }
        foreach($fields AS $field)
        {
            if (is_array($slider) && isset($slider[$field]) && $slider[$field])
            {
                if(strpos($slider[$field], '/modules/') === false)
                    $slider[$field] = _THEME_PROD_PIC_DIR_.$slider[$field];
                $slider[$field] = context::getContext()->link->protocol_content.Tools::getMediaServer($slider[$field]).$slider[$field];
            }
        }
    }
}