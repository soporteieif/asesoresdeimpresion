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

class StAdvancedColumnClass extends ObjectModel
{
	/** @var integer banner id*/
	public $id;
    public $width;
	public $id_st_advanced_menu;
    public $position;
    public $active;
    public $hide_on_mobile;
    public $title;
	
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table'     => 'st_advanced_column',
		'primary'   => 'id_st_advanced_column',
		'fields' => array(
            'id_st_advanced_menu'         => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'width'       => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'position'        => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'active'          => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hide_on_mobile'  => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'title'           => array('type' => self::TYPE_STRING, 'size' => 255, 'validate' => 'isGenericName'),
		)
	);

    public static function getAll($id_st_advanced_menu, $active=0)
    {
        $result = Db::getInstance()->executeS('
            SELECT *
            FROM `'._DB_PREFIX_.'st_advanced_column`
            WHERE `id_st_advanced_menu`='.(int)$id_st_advanced_menu.($active ? ' AND `active`=1 ' : '').'
            ORDER BY `position`
            ');
        return $result;
    }
    public function copyFromPost()
    {
        /* Classical fields */
        foreach ($_POST AS $key => $value)
            if (key_exists($key, $this) AND $key != 'id_'.$this->table)
                $this->{$key} = $value;
    }
    public function delete()
    {
        if (!$this->id)
            return false;
        if (parent::delete())
        {
            StAdvancedMenuClass::deleteByColumn($this->id);
            return true;
        }
        return false;
    }
    public static function deleteByMenu($id_advanced_menu = 0)
    {
        if (!$id_advanced_menu)
            return false;
        $res = Db::getInstance()->executeS('
            SELECT `id_st_advanced_column`
            FROM `'._DB_PREFIX_.'st_advanced_column`
            WHERE `id_st_advanced_menu` = '.(int)$id_advanced_menu.'
        ');
        $ret = true;
        foreach($res AS $value)
        {
            $column = new StAdvancedColumnClass($value['id_st_advanced_column']);
            $ret &= $column->delete();
        }
        return $ret;
    }
}