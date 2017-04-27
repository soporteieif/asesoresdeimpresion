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

class StBannerGroup extends ObjectModel
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
    public $id_manufacturer;
	/** @var integer */
	public $layout;
	/** @var boolen */
	public $hide_on_mobile;
	/** @var boolen */
	public $hover_effect;
	/** @var integer */
	public $active;
	/** @var integer */
	public $position; 
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
		'table'     => 'st_banner_group',
		'primary'   => 'id_st_banner_group',
		'fields'    => array(
			'name'                   =>	array('type' => self::TYPE_STRING, 'size' => 255, 'validate' => 'isGenericName', 'required' => true),
            'location'               => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_category'            => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_manufacturer'        => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'layout'                 => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'hide_on_mobile'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hover_effect'           => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'active'                 => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'position'               => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'top_spacing'            => array('type' => self::TYPE_STRING, 'size' => 10),
            'bottom_spacing'         => array('type' => self::TYPE_STRING, 'size' => 10),
            'show_on_sub'            => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        ),
	);
	public static function getAll()
	{
        Shop::addTableAssociation('st_banner_group', array('type' => 'shop'));
		return  Db::getInstance()->executeS('
			SELECT smsg.*
			FROM `'._DB_PREFIX_.'st_banner_group` smsg
			'.Shop::addSqlAssociation('st_banner_group', 'smsg')
            );
	}
    
    public function delete()
    {
        $multi = Db::getInstance()->getValue('
			SELECT count(0) 
			FROM `'._DB_PREFIX_.'st_banner_group_shop`
			WHERE id_st_banner_group='.$this->id
        );
        if($multi>1)
        {
            return Db::getInstance()->execute('
    			DELETE  
    			FROM `'._DB_PREFIX_.'st_banner_group_shop`
    			WHERE id_st_banner_group='.$this->id.Shop::addSqlRestrictionOnLang()
            );
        }
        else
        {
            $banners = Db::getInstance()->executeS('
    			SELECT id_st_banner 
    			FROM `'._DB_PREFIX_.'st_banner`
    			WHERE id_st_banner_group='.$this->id
            );
            $res = true;
            foreach($banners as $v)
                if($banner = new StBannerClass($v['id_st_banner']))
                    $res &= $banner->delete();
                    
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
    public static function categoryHasBanner($identify, $show_on_sub = 1)
    {
        return Db::getInstance()->getValue('
			SELECT count(0)
			FROM `'._DB_PREFIX_.'st_banner_group` smsg
			'.Shop::addSqlAssociation('st_banner_group', 'smsg').'
			WHERE smsg.`active`=1 '.($show_on_sub ? ' and smsg.`show_on_sub`=1' : '').( is_array($identify) ? ' and smsg.`id_category` IN ('.implode(',',$identify).') ' : ' and smsg.`id_category` = '.(int)$identify )
        );
    }
    public static function getBannerGroup($identify,$type=1)
    {
        $identify = (array)$identify;
        Shop::addTableAssociation('st_banner_group', array('type' => 'shop'));
        $where = '';
        if($type==1)
            $where .= ' AND smsg.`location` IN ('.implode(',',$identify).')';
        elseif($type==3)
            $where .= ' AND smsg.`id_manufacturer` IN ('.implode(',',$identify).')';
        elseif($type==2)
        {
            if(self::categoryHasBanner($identify, 0))
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
                        if(self::categoryHasBanner($parent['id_category'], 1))
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
        if(!$where)
            return false;
        return Db::getInstance()->executeS('
			SELECT smsg.*
			FROM `'._DB_PREFIX_.'st_banner_group` smsg
			'.Shop::addSqlAssociation('st_banner_group', 'smsg').'
			WHERE smsg.`active`=1 '.$where.'
            ORDER BY smsg.`position`');
    }
    public static function getOptions()
    {
        return Db::getInstance()->executeS('
            SELECT * 
            FROM `'._DB_PREFIX_.'st_banner_group` 
            WHERE `active` = 1 
        ');
    }
}