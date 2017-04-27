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

class StIosSliderGroup extends ObjectModel
{
	/** @var integer*/
	public $id;
	/** @var string*/
	public $name;
	/** @var integer */
	public $location;
	/** @var integer */
	public $id_category;
    /** @var integer */
	public $id_cms;
    /** @var integer */
	public $id_cms_category;
	/** @var float */
    public $height;
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
	/** @var integer */
	public $scrollbar;
	/** @var string */
	public $scrollbar_bg;
	/** @var string */
	public $scrollbar_color;
	/** @var integer */
	public $active;
	/** @var integer */
	public $position;
	/** @var integer */
    public $templates;
	/** @var integer */
    public $desktopClickDrag;
	/** @var integer */
    public $infiniteSlider;
	/** @var string */
    public $bg_color;
	/** @var integer */
    public $bg_pattern;
	/** @var string */
    public $bg_img;
	/** @var integer */
    public $bg_repeat;
	/** @var integer */
    public $bg_position;
	/** @var integer */
    public $padding_tb;
	/** @var integer */
    public $width;
	/** @var integer */
    public $slide_padding;
	/** @var integer */
    public $slide_shadow;
	/** @var string */
    public $prev_next_color;
	/** @var string */
    public $prev_next_bg;
	/** @var string */
    public $pag_nav_bg;
	/** @var string */
    public $pag_nav_bg_active;
    /** @var string */
    public $top_spacing; 
    /** @var string */
    public $bottom_spacing;
    /** @var integer */
    public $show_on_sub;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table'     => 'st_iosslider_group',
		'primary'   => 'id_st_iosslider_group',
		'fields'    => array(
			'name'                   =>	array('type' => self::TYPE_STRING, 'size' => 255, 'validate' => 'isGenericName', 'required' => true),
            'location'               => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_category'            => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_cms'                 => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_cms_category'        => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'height'                 => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'prev_next'              => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'pag_nav'                => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'hide_on_mobile'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'time'                   =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'trans_period'           =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'auto_advance'           =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'pause'                  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'scrollbar'              =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'scrollbar_bg'           =>	array('type' => self::TYPE_STRING, 'size' => 7),
			'scrollbar_color'        =>	array('type' => self::TYPE_STRING, 'size' => 7),
			'active'                 => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'position'               => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'templates'              =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'desktopClickDrag'       =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'infiniteSlider'         =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'bg_color'               => array('type' => self::TYPE_STRING, 'size' => 7),
			'bg_pattern'             =>	array('type' => self::TYPE_STRING, 'validate' => 'isunsignedInt'),
			'bg_img'                 =>	array('type' => self::TYPE_STRING, 'validate' => 'isAnything', 'size' => 255),
			'bg_repeat'              =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'bg_position'            =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'padding_tb'             =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'width'                  => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'slide_padding'          =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'slide_shadow'           => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'prev_next_color'        =>	array('type' => self::TYPE_STRING, 'size' => 7),
			'prev_next_bg'           =>	array('type' => self::TYPE_STRING, 'size' => 7),
			'pag_nav_bg'             =>	array('type' => self::TYPE_STRING, 'size' => 7),
			'pag_nav_bg_active'      =>	array('type' => self::TYPE_STRING, 'size' => 7),
            'top_spacing'            => array('type' => self::TYPE_STRING, 'size' => 10),
            'bottom_spacing'         => array('type' => self::TYPE_STRING, 'size' => 10),
            'show_on_sub'            => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        ),
	);
	public static function getAll()
	{
        Shop::addTableAssociation('st_iosslider_group', array('type' => 'shop'));
		return  Db::getInstance()->executeS('
			SELECT smsg.*
			FROM `'._DB_PREFIX_.'st_iosslider_group` smsg
			'.Shop::addSqlAssociation('st_iosslider_group', 'smsg')
            );
	}
    
    public function delete()
    {
        $multi = Db::getInstance()->getValue('
			SELECT count(0) 
			FROM `'._DB_PREFIX_.'st_iosslider_group_shop`
			WHERE id_st_iosslider_group='.$this->id
        );
        if($multi>1)
        {
            return Db::getInstance()->execute('
    			DELETE  
    			FROM `'._DB_PREFIX_.'st_iosslider_group_shop`
    			WHERE id_st_iosslider_group='.$this->id.Shop::addSqlRestrictionOnLang()
            );
        }
        else
        {
            $slides = Db::getInstance()->executeS('
    			SELECT id_st_iosslider 
    			FROM `'._DB_PREFIX_.'st_iosslider`
    			WHERE id_st_iosslider_group='.$this->id
            );
            $res = true;
            foreach($slides as $v)
                if($slide = new StIosSliderClass($v['id_st_iosslider']))
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
	}
    public static function categoryHasSlide($identify, $show_on_sub = 1)
    {
        return Db::getInstance()->getValue('
			SELECT count(0)
			FROM `'._DB_PREFIX_.'st_iosslider_group` smsg
			'.Shop::addSqlAssociation('st_iosslider_group', 'smsg').'
			WHERE smsg.`active`=1 '.($show_on_sub ? ' and smsg.`show_on_sub`=1' : '').( is_array($identify) ? ' and smsg.`id_category` IN ('.implode(',',$identify).') ' : ' and smsg.`id_category` = '.(int)$identify )
        );
    }
    public static function getSlideGroup($identify,$type=1)
    {
        $identify = (array)$identify;
        Shop::addTableAssociation('st_iosslider_group', array('type' => 'shop'));
        $where = '';
        if($type==1)
            $where .= ' AND smsg.`location` IN ('.implode(',',$identify).')';
        elseif($type==2)
        {
            if(self::categoryHasSlide($identify, 0))
                $where .= ' AND smsg.`id_category` IN ('.implode(',',$identify).')';
            else
            {
                $id_has = 0; 
                foreach($identify as $id_category)
                {
                    if($id_has)
                        break;
                    $category = new Category($id_category);
                    $parents = $category->getParentsCategories();
                    
                    foreach($parents as $parent)
                    {
                        if(self::categoryHasSlide($parent['id_category'], 1))
                        {
                            $id_has = $parent['id_category'];
                            break;
                        }
                    }
                }
                if($id_has)
                    $where .= ' AND smsg.`id_category` = '.$id_has;
            }
        }
        elseif($type==3)
            $where .= ' AND smsg.`id_st_iosslider_group` IN ('.implode(',',$identify).')';
        elseif($type==4)
            $where .= ' AND smsg.`id_cms` IN ('.implode(',',$identify).')';
        elseif($type==5)
            $where .= ' AND smsg.`id_cms_category` IN ('.implode(',',$identify).')';
            
        if(!$where)
            return false;
        return Db::getInstance()->executeS('
			SELECT smsg.*
			FROM `'._DB_PREFIX_.'st_iosslider_group` smsg
			'.Shop::addSqlAssociation('st_iosslider_group', 'smsg').'
			WHERE smsg.`active`=1 '.$where.'
            ORDER BY smsg.`position`');
    }
    public static function getOptions()
    {
        return Db::getInstance()->executeS('
			SELECT *
			FROM `'._DB_PREFIX_.'st_iosslider_group` 
		');
    }
}