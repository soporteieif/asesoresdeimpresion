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

class StCameraSlideshowGroup extends ObjectModel
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
    public $height_ratio;
	/** @var boolen */
	public $prev_next;
	/** @var boolen */
	public $prev_next_on_hover;
	/** @var boolen */
	public $pag_nav;
	/** @var boolen */
	public $hide_on_mobile;
	/** @var text */
	public $effects;
	/** @var integer */
	public $easing;
	/** @var integer */
	public $time;
	/** @var integer */
	public $trans_period;
	/** @var boolen */
	public $auto_advance;
	/** @var boolen */
	public $pause;
	/** @var integer */
	public $mosaic_rows;
	/** @var integer */
	public $mosaic_columns;
	/** @var integer */
	public $blind_rows;
	/** @var integer */
	public $curtain_columns;
	/** @var integer */
	public $loader;
	/** @var string */
	public $bar_position;
	/** @var string */
	public $pie_position;
	/** @var string */
	public $loader_bg;
	/** @var string */
	public $loader_color;
	/** @var integer */
	public $active;
	/** @var integer */
	public $position;
    /** @var string */
    public $top_spacing; 
    /** @var string */
    public $bottom_spacing; 
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table'     => 'st_camera_slideshow_group',
		'primary'   => 'id_st_camera_slideshow_group',
		'fields'    => array(
			'name'                   =>	array('type' => self::TYPE_STRING, 'size' => 255, 'validate' => 'isGenericName', 'required' => true),
            'location'               => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_category'            => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_cms'                 => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_cms_category'        => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'height_ratio'           => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat', 'required' => true),
            'prev_next'              => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'prev_next_on_hover'     => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'pag_nav'                => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hide_on_mobile'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'effects'                =>	array('type' => self::TYPE_STRING),
			'easing'                 => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'time'                   =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'trans_period'           =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'auto_advance'           =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'pause'                  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'mosaic_rows'            =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'mosaic_columns'         =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'blind_rows'             =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'curtain_columns'        =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'loader'                 =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'bar_position'           =>	array('type' => self::TYPE_STRING),
			'pie_position'           =>	array('type' => self::TYPE_STRING),
			'loader_bg'              =>	array('type' => self::TYPE_STRING, 'size' => 7),
			'loader_color'           =>	array('type' => self::TYPE_STRING, 'size' => 7),
			'active'                 => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'position'               => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'top_spacing'     => array('type' => self::TYPE_STRING, 'size' => 10),
            'bottom_spacing'  => array('type' => self::TYPE_STRING, 'size' => 10),
        ),
	);
	public static function getAll()
	{
        Shop::addTableAssociation('st_camera_slideshow_group', array('type' => 'shop'));
		return  Db::getInstance()->executeS('
			SELECT smsg.*
			FROM `'._DB_PREFIX_.'st_camera_slideshow_group` smsg
			'.Shop::addSqlAssociation('st_camera_slideshow_group', 'smsg')
            );
	}
    
    public function delete()
    {
        $multi = Db::getInstance()->getValue('
			SELECT count(0) 
			FROM `'._DB_PREFIX_.'st_camera_slideshow_group_shop`
			WHERE id_st_camera_slideshow_group='.$this->id
        );
        if($multi>1)
        {
            return Db::getInstance()->execute('
    			DELETE  
    			FROM `'._DB_PREFIX_.'st_camera_slideshow_group_shop`
    			WHERE id_st_camera_slideshow_group='.$this->id.Shop::addSqlRestrictionOnLang()
            );
        }
        else
        {
            $slides = Db::getInstance()->executeS('
    			SELECT id_st_camera_slideshow 
    			FROM `'._DB_PREFIX_.'st_camera_slideshow`
    			WHERE id_st_camera_slideshow_group='.$this->id
            );
            $res = true;
            foreach($slides as $v)
                if($slide = new StCameraSlideshowClass($v['id_st_camera_slideshow']))
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
    public static function categoryHasSlide($identify)
    {
        return Db::getInstance()->getValue('
			SELECT count(0)
			FROM `'._DB_PREFIX_.'st_camera_slideshow_group` smsg
			'.Shop::addSqlAssociation('st_camera_slideshow_group', 'smsg').'
			WHERE smsg.`active`=1 '.( is_array($identify) ? ' and smsg.`id_category` IN ('.implode(',',$identify).') ' : ' and smsg.`id_category` = '.(int)$identify )
        );
    }
    public static function getSlideGroup($identify,$type=1)
    {
        $identify = (array)$identify;
        Shop::addTableAssociation('st_camera_slideshow_group', array('type' => 'shop'));
        $where = '';
        if($type==1)
            $where .= ' AND smsg.`location` IN ('.implode(',',$identify).')';
        elseif($type==2)
        {
            if(self::categoryHasSlide($identify))
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
                        if(self::categoryHasSlide($parent['id_category']))
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
            $where .= ' AND smsg.`id_st_camera_slideshow_group` IN ('.implode(',',$identify).')';
        elseif($type==4)
            $where .= ' AND smsg.`id_cms` IN ('.implode(',',$identify).')';
        elseif($type==5)
            $where .= ' AND smsg.`id_cms_category` IN ('.implode(',',$identify).')';
            
        if(!$where)
            return false;
        return Db::getInstance()->executeS('
			SELECT smsg.*
			FROM `'._DB_PREFIX_.'st_camera_slideshow_group` smsg
			'.Shop::addSqlAssociation('st_camera_slideshow_group', 'smsg').'
			WHERE smsg.`active`=1 '.$where.'
            ORDER BY smsg.`position`');
    }
    public static function getOptions()
    {
        return Db::getInstance()->executeS('
			SELECT * 
			FROM `'._DB_PREFIX_.'st_camera_slideshow_group` 
			WHERE `active` = 1 
		');
    }
}