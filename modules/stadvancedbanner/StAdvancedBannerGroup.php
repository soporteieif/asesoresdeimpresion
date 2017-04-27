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

class StAdvancedBannerGroup extends ObjectModel
{
    /** @var integer*/
    public $id;
	/** @var integer*/
	public $id_parent;
	/** @var string*/
	public $name;
	/** @var integer */
	public $location;
	/** @var integer */
	public $id_category;
    /** @var integer */
    public $id_manufacturer;
    /** @var integer */
    public $id_cms;
    /** @var integer */
	public $id_cms_category;
    /** @var integer */
	/** @var boolen */
	public $hide_on_mobile;
	/** @var boolen */
	public $hover_effect;
	/** @var integer */
	public $active;
    /** @var integer */
    public $position; 
    /** @var integer */
    public $width; 
    /** @var integer */
    public $height; 
    /** @var string */
    public $padding; 
    /** @var string */
    public $top_spacing; 
    /** @var string */
    public $bottom_spacing;
    /** @var integer */
    public $show_on_sub; 
    /** @var string */
    public $style; 
                
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table'     => 'st_advanced_banner_group',
		'primary'   => 'id_st_advanced_banner_group',
		'fields'    => array(
            'id_parent'       => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'name'            => array('type' => self::TYPE_STRING, 'size' => 255, 'validate' => 'isGenericName', 'required' => true),
            'location'        => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_category'     => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_cms'          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_cms_category' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_manufacturer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'hide_on_mobile'  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hover_effect'    => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'active'          => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'position'        => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'width'           => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'height'          => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'padding'         => array('type' => self::TYPE_STRING, 'size' => 10),
            'top_spacing'     => array('type' => self::TYPE_STRING, 'size' => 10),
            'bottom_spacing'  => array('type' => self::TYPE_STRING, 'size' => 10),
            'show_on_sub'     => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'style'           => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
        ),
	);
	public static function getAll()
	{
        Shop::addTableAssociation('st_advanced_banner_group', array('type' => 'shop'));
		return  Db::getInstance()->executeS('
			SELECT smsg.*
			FROM `'._DB_PREFIX_.'st_advanced_banner_group` smsg
			'.Shop::addSqlAssociation('st_advanced_banner_group', 'smsg')
            );
	}
    
    public static function getSub($id_parent,$active=0)
    {
        Shop::addTableAssociation('st_advanced_banner_group', array('type' => 'shop'));
        return Db::getInstance()->executeS('
            SELECT smsg.*
            FROM `'._DB_PREFIX_.'st_advanced_banner_group` smsg
            '.($id_parent ? '' : Shop::addSqlAssociation('st_advanced_banner_group', 'smsg')).
            ' WHERE smsg.`id_parent`='.(int)$id_parent.
            ($active ? ' AND smsg.`active`=1 ' : '').
            ' ORDER BY smsg.`position`');
    }

    public static function recurseTree($id_parent,$max_depth=2,$current_depth=0,$active=0)
    {
        $tree = self::getSub($id_parent,$active);
        if ( ( $max_depth==0 || ($current_depth < $max_depth) ) && $tree && count($tree))
            foreach($tree as &$v)
            {
                $jon = self::recurseTree($v['id_st_advanced_banner_group'],$max_depth,$current_depth+1,$active);
                if(is_array($jon) && count($jon))
                    $v['columns'] = $jon;
            }

        return $tree;
    }

    public function delete()
    {
        $multi = Db::getInstance()->getValue('
			SELECT count(0) 
			FROM `'._DB_PREFIX_.'st_advanced_banner_group_shop`
			WHERE id_st_advanced_banner_group='.$this->id
        );
        if($multi>1)
        {
            return Db::getInstance()->execute('
    			DELETE  
    			FROM `'._DB_PREFIX_.'st_advanced_banner_group_shop`
    			WHERE id_st_advanced_banner_group='.$this->id.Shop::addSqlRestrictionOnLang()
            );
        }
        else
        {
            $banners = Db::getInstance()->executeS('
    			SELECT id_st_advanced_banner 
    			FROM `'._DB_PREFIX_.'st_advanced_banner`
    			WHERE id_st_advanced_banner_group='.$this->id
            );
            $res = true;
            foreach($banners as $v)
                if($banner = new StAdvancedBannerClass($v['id_st_advanced_banner']))
                    $res &= $banner->delete();
                    
    		$res &= parent::delete();
            
            if ($res)
                $this->deleteChildren($this->id);
    		return $res;
        }
    }
    
    public function deleteChildren($id_parent = 0)
    {
        if (!$id_parent)
            return false;
        $res = Db::getInstance()->executeS('
    			SELECT *  
    			FROM `'._DB_PREFIX_.'st_advanced_banner_group`
    			WHERE id_parent='.(int)$id_parent
            );
       foreach($res AS $value)
       {
            $group = new StAdvancedBannerGroup($value['id_st_advanced_banner_group']);
            $group->delete();
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
			FROM `'._DB_PREFIX_.'st_advanced_banner_group` smsg
			'.Shop::addSqlAssociation('st_advanced_banner_group', 'smsg').'
			WHERE smsg.`active`=1 '.($show_on_sub ? ' and smsg.`show_on_sub`=1' : '').( is_array($identify) ? ' and smsg.`id_category` IN ('.implode(',',$identify).') ' : ' and smsg.`id_category` = '.(int)$identify )
        );
    }
    public static function getBannerGroup($identify,$type=1)
    {
        $identify = (array)$identify;
        Shop::addTableAssociation('st_advanced_banner_group', array('type' => 'shop'));
        $where = '';
        if($type==1)
            $where .= ' AND smsg.`location` IN ('.implode(',',$identify).')';
        elseif($type==3)
            $where .= ' AND smsg.`id_manufacturer` IN ('.implode(',',$identify).')';
        elseif($type==4)
            $where .= ' AND smsg.`id_cms` IN ('.implode(',',$identify).')';            
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
			FROM `'._DB_PREFIX_.'st_advanced_banner_group` smsg
			'.Shop::addSqlAssociation('st_advanced_banner_group', 'smsg').'
			WHERE smsg.`active`=1 AND smsg.`id_parent`=0 '.$where.'
            ORDER BY smsg.`position`');
    }
    public static function getParentsGroups($id_st_advanced_banner_group)
    {
        $categories = array();

        while (true)
        {
            $sql = '
            SELECT smsg.*
            FROM `'._DB_PREFIX_.'st_advanced_banner_group` smsg 
            WHERE smsg.`id_st_advanced_banner_group` = '.(int)$id_st_advanced_banner_group;

            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);

            if ($result)
                $categories[] = $result;
            if (!$result || ($result['id_parent'] == 0))
                return $categories;
            $id_st_advanced_banner_group = $result['id_parent'];
        }
    }
    public function hasColumn()
    {
        return Db::getInstance()->getValue('
            SELECT count(0) 
            FROM `'._DB_PREFIX_.'st_advanced_banner_group`
            WHERE id_parent='.$this->id
        );
    }
    public function hasBanner()
    {
        return Db::getInstance()->getValue('
            SELECT count(0) 
            FROM `'._DB_PREFIX_.'st_advanced_banner`
            WHERE id_st_advanced_banner_group='.$this->id
        );
    }
    public static function getCustomCss()
    {
        $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_advanced_banner_group` `padding`');  
          
        if(!is_array($field) || !count($field))
            return false;

        return  Db::getInstance()->executeS('
            SELECT `id_st_advanced_banner_group`, `location`, `padding`, `top_spacing`, `bottom_spacing`
            FROM `'._DB_PREFIX_.'st_advanced_banner_group` 
            WHERE `active` = 1 and (`padding`!="" or `top_spacing`!="" or `bottom_spacing`!="")'
        );
    }
}