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

class StParallaxClass extends ObjectModel
{
	/** @var integer id*/
	public $id;
	/** @var integer group id*/
	public $id_st_parallax_group;
	/** @var integer */
    public $text_align;
	/** @var string */
    public $text_color;
	/** @var integer */
	public $active;
	/** @var integer */
	public $position;
	/** @var string banner description*/
	public $description;
	/** @var string */
    public $btn_color;
	/** @var string */
    public $btn_bg;
	/** @var string */
    public $btn_hover_color;
	/** @var string */
    public $btn_hover_bg;
	/** @var integer */
	public $width;
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table'     => 'st_parallax',
		'primary'   => 'id_st_parallax',
		'multilang' => true,
		'fields'    => array(
			'id_st_parallax_group'    =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'text_align'                 =>	array('type' => self::TYPE_INT),
			'text_color'                => array('type' => self::TYPE_STRING, 'size' => 7),
			'active'                     =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'position'                   =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),	
			'btn_color'       => array('type' => self::TYPE_STRING, 'size' => 7),
			'btn_bg'          => array('type' => self::TYPE_STRING, 'size' => 7),
			'btn_hover_color' => array('type' => self::TYPE_STRING, 'size' => 7),
			'btn_hover_bg'    => array('type' => self::TYPE_STRING, 'size' => 7),
			// Lang fields
			'description'    => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
			'width'                   =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),	
		)
	);
    public function delete()
    {
        if ($this->image && file_exists(_PS_ROOT_DIR_.$this->image))
	        @unlink(_PS_ROOT_DIR_.$this->image);
        if ($this->thumb && file_exists(_PS_ROOT_DIR_.$this->thumb))
	        @unlink(_PS_ROOT_DIR_.$this->thumb);
            
        if(isset($this->image_multi_lang) && count($this->image_multi_lang))
            foreach($this->image_multi_lang as $v)
                if ($v && file_exists(_PS_ROOT_DIR_.$v))
    	           @unlink(_PS_ROOT_DIR_.$v);
                                      
        if(isset($this->thumb_multi_lang) && count($this->thumb_multi_lang))
            foreach($this->thumb_multi_lang as $v)
                if ($v && file_exists(_PS_ROOT_DIR_.$v))
    	           @unlink(_PS_ROOT_DIR_.$v);
                                      
		$res = parent::delete();
        if ($res)
            StParallaxFontClass::deleteBySlider($this->id);
        return $res;
    }
    
	public static function getAll($id_st_parallax_group, $id_lang, $active=0)
	{
	   if (!Validate::isUnsignedId($id_lang))
			die(Tools::displayError());

        $result = Db::getInstance()->executeS('
			SELECT sms.*, smsl.*
			FROM `'._DB_PREFIX_.'st_parallax` sms
			LEFT JOIN `'._DB_PREFIX_.'st_parallax_lang` smsl ON (sms.`id_st_parallax` = smsl.`id_st_parallax`)
			WHERE smsl.`id_lang` = '.(int)$id_lang.' AND sms.`id_st_parallax_group`='.(int)$id_st_parallax_group.($active ? ' AND sms.`active`=1 ' : '').'
            ORDER BY sms.`position`
            ');
        return $result;
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
    public function updatePosition($way, $position)
	{
		if (!$res = Db::getInstance()->executeS('
			SELECT `id_st_parallax`, `position`, `id_st_parallax_group`
			FROM `'._DB_PREFIX_.'st_parallax` 
			WHERE `id_st_parallax_group` = '.(int)$this->id_st_parallax_group.'
			ORDER BY `position` ASC'
		))
			return false;

		foreach ($res as $slide)
			if ((int)$slide['id_st_parallax'] == (int)$this->id)
				$moved_slide = $slide;

		if (!isset($moved_slide) || !isset($position))
			return false;

		return (Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'st_parallax`
			SET `position`= `position` '.($way ? '- 1' : '+ 1').'
			WHERE `position`
			'.($way
				? '> '.(int)$moved_slide['position'].' AND `position` <= '.(int)$position
				: '< '.(int)$moved_slide['position'].' AND `position` >= '.(int)$position).'
			AND `id_st_parallax_group`='.(int)$moved_slide['id_st_parallax_group'])
		&& Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'st_parallax`
			SET `position` = '.(int)$position.'
			WHERE `id_st_parallax` = '.(int)$moved_slide['id_st_parallax']));
	}
    public function checkPosition()
    {
        $check = Db::getInstance()->getValue('
			SELECT count(0)
			FROM `'._DB_PREFIX_.'st_parallax` 
			WHERE `id_st_parallax_group` = '.(int)$this->id_st_parallax_group.' AND `position`='.(int)$this->position.($this->id ? ' AND `id_st_parallax`!='.$this->id : '')
		);
        if($check)
            return Db::getInstance()->getValue('
    			SELECT `position`+1
    			FROM `'._DB_PREFIX_.'st_parallax` 
    			WHERE `id_st_parallax_group` = '.(int)$this->id_st_parallax_group.'
                ORDER BY `position` DESC'
    		);
        return $this->position;
    }
    public static function getOptions()
    {
        return Db::getInstance()->executeS('
			SELECT `id_st_parallax`, `text_color`,`btn_color`, `btn_bg`, `btn_hover_color`, `btn_hover_bg`
			FROM `'._DB_PREFIX_.'st_parallax` 
			WHERE `active` = 1 and (`text_color`!="" or `btn_color`!="" or `btn_bg`!="" or `btn_hover_color`!="" or `btn_hover_bg`!="")
		');
    }
}