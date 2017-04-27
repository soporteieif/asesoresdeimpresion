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

class StParallaxGroup extends ObjectModel
{
	/** @var integer*/
	public $id;
    /** @var string*/
    public $name;
	/** @var integer */
	public $location;
	/** @var boolen */
	public $prev_next;
	/** @var boolen */
	public $pag_nav;
	/** @var boolen */
	public $hide_on_mobile;
	/** @var integer */
	public $time;
	/** @var integer */
	public $trans_period;
	/** @var boolen */
	public $auto_advance;
    /** @var boolen */
    public $pause;
	/** @var boolen */
	public $autoHeight;
	/** @var integer */
	public $active;
	/** @var integer */
	public $position;
	/** @var integer */
    public $desktopClickDrag;
    /** @var string */
    public $text_color;
	/** @var string */
    public $bg_color;
	/** @var integer */
    public $bg_pattern;
	/** @var string */
    public $bg_img;
    /** @var integer */
    public $padding_top;
	/** @var integer */
    public $padding_bottom;
	/** @var string */
    public $prev_next_color;
    /** @var string */
    public $prev_next_hover;
	/** @var string */
    public $prev_next_bg;
	/** @var string */
    public $pag_nav_bg;
    /** @var string */
    public $pag_nav_bg_active;
    /** @var string */
    public $title_color;
	/** @var integer */
    public $speed;
    /** @var string*/
    public $title;
    /** @var string */
    public $top_spacing; 
    /** @var string */
    public $bottom_spacing; 
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table'     => 'st_parallax_group',
		'primary'   => 'id_st_parallax_group',
        'multilang' => true,
		'fields'    => array(
            'name'              => array('type' => self::TYPE_STRING, 'size' => 255, 'validate' => 'isGenericName', 'required' => true),
            'location'          => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'prev_next'         => array('type' => self::TYPE_BOOL, 'validate' => 'isunsignedInt'),
            'pag_nav'           => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hide_on_mobile'    => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'time'              =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
            'trans_period'      =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'auto_advance'      =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'pause'             => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'autoHeight'             => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'active'            => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'position'          => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'desktopClickDrag'  =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'text_color'          => array('type' => self::TYPE_STRING, 'size' => 7),
            'bg_color'          => array('type' => self::TYPE_STRING, 'size' => 7),
            'bg_pattern'        =>	array('type' => self::TYPE_STRING, 'validate' => 'isunsignedInt'),
            'bg_img'            =>	array('type' => self::TYPE_STRING, 'validate' => 'isAnything', 'size' => 255),
            'padding_top'        =>  array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'padding_bottom'        =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'prev_next_color'   =>	array('type' => self::TYPE_STRING, 'size' => 7),
            'prev_next_hover'   =>	array('type' => self::TYPE_STRING, 'size' => 7),
            'prev_next_bg'      =>	array('type' => self::TYPE_STRING, 'size' => 7),
            'pag_nav_bg'        =>	array('type' => self::TYPE_STRING, 'size' => 7),
            'pag_nav_bg_active' =>  array('type' => self::TYPE_STRING, 'size' => 7),
            'title_color' =>  array('type' => self::TYPE_STRING, 'size' => 7),
            'speed'           => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'top_spacing'     => array('type' => self::TYPE_STRING, 'size' => 10),
            'bottom_spacing'  => array('type' => self::TYPE_STRING, 'size' => 10),
            // Lang fields
            'title'            =>   array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
        ),
	);
	public static function getAll($id_lang,$active=0)
	{
        Shop::addTableAssociation('st_parallax_group', array('type' => 'shop'));
		return Db::getInstance()->executeS('
			SELECT spg.*, spgl.`title`
			FROM `'._DB_PREFIX_.'st_parallax_group` spg
			'.Shop::addSqlAssociation('st_parallax_group', 'spg').'
            LEFT JOIN `'._DB_PREFIX_.'st_parallax_group_lang` spgl ON (spg.`id_st_parallax_group` = spgl.`id_st_parallax_group`)
            WHERE spgl.`id_lang` = '.(int)$id_lang.($active ? ' AND spg.`active`=1 ' : '').'
            ORDER BY spg.`location`, spg.`position`'
            );
	}
    
    public function delete()
    {
        $multi = Db::getInstance()->getValue('
			SELECT count(0) 
			FROM `'._DB_PREFIX_.'st_parallax_group_shop`
			WHERE id_st_parallax_group='.$this->id
        );
        if($multi>1)
        {
            return Db::getInstance()->execute('
    			DELETE  
    			FROM `'._DB_PREFIX_.'st_parallax_group_shop`
    			WHERE id_st_parallax_group='.$this->id.Shop::addSqlRestrictionOnLang()
            );
        }
        else
        {
            $slides = Db::getInstance()->executeS('
    			SELECT id_st_parallax 
    			FROM `'._DB_PREFIX_.'st_parallax`
    			WHERE id_st_parallax_group='.$this->id
            );
            $res = true;
            foreach($slides as $v)
                if($slide = new StParallaxClass($v['id_st_parallax']))
                    $res &= $slide->delete();
                    
    		$res &= parent::delete();
    		return $res;
        }
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
    public static function categoryHasSlide($identify)
    {
        return Db::getInstance()->getValue('
			SELECT count(0)
			FROM `'._DB_PREFIX_.'st_parallax_group` smsg
			'.Shop::addSqlAssociation('st_parallax_group', 'smsg').'
			WHERE smsg.`active`=1 '.( is_array($identify) ? ' and smsg.`id_category` IN ('.implode(',',$identify).') ' : ' and smsg.`id_category` = '.(int)$identify )
        );
    }
    public static function getSlideGroup($id_lang, $identify, $type=1)
    {
        $identify = (array)$identify;
        Shop::addTableAssociation('st_parallax_group', array('type' => 'shop'));
        $where = '';
        if($type==1)
            $where .= ' AND spg.`location` IN ('.implode(',',$identify).')';
        elseif($type==3)
            $where .= ' AND spg.`id_st_parallax_group` IN ('.implode(',',$identify).')';

        if(!$where)
            return false;
        $result = Db::getInstance()->executeS('
			SELECT spg.*, spgl.`title`
			FROM `'._DB_PREFIX_.'st_parallax_group` spg
			'.Shop::addSqlAssociation('st_parallax_group', 'spg').'
            LEFT JOIN `'._DB_PREFIX_.'st_parallax_group_lang` spgl ON (spg.`id_st_parallax_group` = spgl.`id_st_parallax_group`)
			WHERE spg.`active`=1 AND spgl.`id_lang` = '.(int)$id_lang.$where.' 
            ORDER BY spg.`position`');
        foreach($result AS &$rs)
            self::fetchMediaServer($rs);
        return $result;
    }
    public static function getOptions()
    {
        return Db::getInstance()->executeS('
			SELECT *
			FROM `'._DB_PREFIX_.'st_parallax_group` 
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