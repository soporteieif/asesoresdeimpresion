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

class StMultiLinkGroup extends ObjectModel
{
	public $id;
    public $location;
	public $new_window;
	public $active;
	public $position;
	public $url;
	public $name;
	/** @var boolen */
	public $hide_on_mobile;
	public $nofollow;
	public $link_align;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table'     => 'st_multi_link_group',
		'primary'   => 'id_st_multi_link_group',
		'multilang' => true,
		'fields'    => array(
			'location' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'new_window'=> array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'active'   => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'position' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'hide_on_mobile'=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'nofollow'=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'link_align'       => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			
			// Lang fields
			'name'     => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
			'url'      => array('type' => self::TYPE_STRING, 'lang' => true,  'validate' => 'isAnything','size' => 255),
		)
	);


	public static function getAll($id_lang)
	{
        Shop::addTableAssociation('st_multi_link_group', array('type' => 'shop'));
		return  Db::getInstance()->executeS('
			SELECT smlg.*,smlgl.`name`,smlgl.`url`
			FROM `'._DB_PREFIX_.'st_multi_link_group` smlg
			'.Shop::addSqlAssociation('st_multi_link_group', 'smlg').'
			LEFT JOIN `'._DB_PREFIX_.'st_multi_link_group_lang` smlgl ON (smlg.`id_st_multi_link_group` = smlgl.`id_st_multi_link_group`)
            WHERE smlgl.`id_lang` = '.(int)$id_lang
        );
	}
    
    public function delete()
    {
        $multi = Db::getInstance()->getValue('
			SELECT count(0) 
			FROM `'._DB_PREFIX_.'st_multi_link_group`
			WHERE id_st_multi_link_group='.$this->id
        );
        if($multi>1)
        {
            return Db::getInstance()->execute('
    			DELETE  
    			FROM `'._DB_PREFIX_.'st_multi_link_group`
    			WHERE id_st_multi_link_group='.$this->id.Shop::addSqlRestrictionOnLang()
            );
        }
        else
        {
            $links = Db::getInstance()->executeS('
    			SELECT id_st_multi_link 
    			FROM `'._DB_PREFIX_.'st_multi_link`
    			WHERE id_st_multi_link_group='.$this->id
            );
            $res = true;
            foreach($links as $v)
                if($links = new StMultiLinkClass($v['id_st_multi_link']))
                    $res &= $links->delete();
                    
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
    public static function getLinkGroup($identify,$type=1,$id_lang)
    {
        if (!Validate::isUnsignedId($id_lang))
			die(Tools::displayError());

        $identify = (array)$identify;
        
        Shop::addTableAssociation('st_multi_link_group', array('type' => 'shop'));
        $where = '';
        if($type==1)
            $where .= ' AND smlg.`location` IN ('.implode(',',$identify).')';
        elseif($type==2)
            $where .= ' AND smlg.`id_st_multi_link_group` IN ('.implode(',',$identify).')';
        return  Db::getInstance()->executeS('
			SELECT smlg.*,smlgl.`name`,smlgl.`url`
			FROM `'._DB_PREFIX_.'st_multi_link_group` smlg
			'.Shop::addSqlAssociation('st_multi_link_group', 'smlg').'
			LEFT JOIN `'._DB_PREFIX_.'st_multi_link_group_lang` smlgl ON (smlg.`id_st_multi_link_group` = smlgl.`id_st_multi_link_group`)
			WHERE smlg.`active`=1 '.$where.' AND smlgl.`id_lang` = '.(int)$id_lang.'
            ORDER BY smlg.`position`');
    }
}