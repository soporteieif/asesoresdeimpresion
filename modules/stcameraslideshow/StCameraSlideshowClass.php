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

class StCameraSlideshowClass extends ObjectModel
{
	/** @var integer id*/
	public $id;
	/** @var integer group id*/
	public $id_st_camera_slideshow_group;
	/** @var integer currency id*/
	public $id_currency;
	/** @var string banner image*/
	public $image;
	/** @var string banner thumb*/
	public $thumb;
	/** @var boolen open in a new window */
	public $new_window;
	/** @var string */
    public $text_position;
	/** @var integer */
    public $text_align;
	/** @var string */
    public $title_color;
	/** @var string */
    public $title_bg;
	/** @var string */
    public $title_font_family;
	/** @var string */
    public $description_color;
	/** @var string */
    public $description_bg;
	/** @var integer */
	public $active;
	/** @var integer */
	public $position;
	/** @var boolen */
	public $isbanner;
	/** @var string banner url*/
	public $url;
	/** @var string */
	public $video;
	/** @var string banner description*/
	public $description;
	/** @var string banner button*/
	public $button;
	/** @var string banner image*/
	public $image_multi_lang;
	/** @var string banner thumb*/
	public $thumb_multi_lang;
	/** @var string */
	public $title;
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table'     => 'st_camera_slideshow',
		'primary'   => 'id_st_camera_slideshow',
		'multilang' => true,
		'fields'    => array(
			'id_st_camera_slideshow_group'    =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
            'id_currency'                =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'image'                      =>	array('type' => self::TYPE_STRING, 'size' => 255),
			'thumb'                      =>	array('type' => self::TYPE_STRING, 'size' => 255),
			'new_window'                 =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'text_position'              =>	array('type' => self::TYPE_STRING),
			'text_align'                 =>	array('type' => self::TYPE_INT),
			'title_color'                => array('type' => self::TYPE_STRING, 'size' => 7),
			'title_bg'                   => array('type' => self::TYPE_STRING, 'size' => 7),
            'title_font_family'          => array('type' => self::TYPE_STRING),
			'description_color'          => array('type' => self::TYPE_STRING, 'size' => 7),
			'description_bg'             => array('type' => self::TYPE_STRING, 'size' => 7),
			'active'                     =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'position'                   =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),	
			'isbanner'                   =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),		
			// Lang fields
			'url'            =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isAnything', 'size' => 255),
            'video'          => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
			'description'    => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
			'button'         =>	array('type' => self::TYPE_STRING, 'lang' => true,'validate' => 'isGenericName', 'size' => 255),
			'image_multi_lang'            =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isAnything', 'size' => 255),
			'thumb_multi_lang'            =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isAnything', 'size' => 255),
			'title'            =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
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
                                      
		return parent::delete();
    }
    
	public static function getAll($id_st_camera_slideshow_group, $id_lang, $active=0, $isbanner=0)
	{
	   if (!Validate::isUnsignedId($id_lang))
			die(Tools::displayError());

		$result = Db::getInstance()->executeS('
			SELECT sms.*, smsl.*
			FROM `'._DB_PREFIX_.'st_camera_slideshow` sms
			LEFT JOIN `'._DB_PREFIX_.'st_camera_slideshow_lang` smsl ON (sms.`id_st_camera_slideshow` = smsl.`id_st_camera_slideshow`)
			WHERE smsl.`id_lang` = '.(int)$id_lang.' AND sms.`id_st_camera_slideshow_group`='.(int)$id_st_camera_slideshow_group.' AND sms.`isbanner`='.($isbanner ? 1 : 0).($active ? ' AND sms.`active`=1 ' : '').'
            ORDER BY sms.`position`
            ');
        foreach($result AS &$rs)
            self::fetchMediaServer($rs);
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
			SELECT `id_st_camera_slideshow`, `position`, `id_st_camera_slideshow_group`
			FROM `'._DB_PREFIX_.'st_camera_slideshow` 
			WHERE `id_st_camera_slideshow_group` = '.(int)$this->id_st_camera_slideshow_group.'
			ORDER BY `position` ASC'
		))
			return false;

		foreach ($res as $slide)
			if ((int)$slide['id_st_camera_slideshow'] == (int)$this->id)
				$moved_slide = $slide;

		if (!isset($moved_slide) || !isset($position))
			return false;

		return (Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'st_camera_slideshow`
			SET `position`= `position` '.($way ? '- 1' : '+ 1').'
			WHERE `position`
			'.($way
				? '> '.(int)$moved_slide['position'].' AND `position` <= '.(int)$position
				: '< '.(int)$moved_slide['position'].' AND `position` >= '.(int)$position).'
			AND `id_st_camera_slideshow_group`='.(int)$moved_slide['id_st_camera_slideshow_group'])
		&& Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'st_camera_slideshow`
			SET `position` = '.(int)$position.'
			WHERE `id_st_camera_slideshow` = '.(int)$moved_slide['id_st_camera_slideshow']));
	}
    public function checkPosition()
    {
        $check = Db::getInstance()->getValue('
			SELECT count(0)
			FROM `'._DB_PREFIX_.'st_camera_slideshow` 
			WHERE `id_st_camera_slideshow_group` = '.(int)$this->id_st_camera_slideshow_group.' AND `position`='.(int)$this->position.($this->id ? ' AND `id_st_camera_slideshow`!='.$this->id : '')
		);
        if($check)
            return Db::getInstance()->getValue('
    			SELECT `position`+1
    			FROM `'._DB_PREFIX_.'st_camera_slideshow` 
    			WHERE `id_st_camera_slideshow_group` = '.(int)$this->id_st_camera_slideshow_group.'
                ORDER BY `position` DESC'
    		);
        return $this->position;
    }
    public static function getOptions()
    {
        return Db::getInstance()->executeS('
			SELECT `id_st_camera_slideshow`, `title_color`, `title_bg`, `title_font_family`, `description_color`, `description_bg`
			FROM `'._DB_PREFIX_.'st_camera_slideshow` 
			WHERE `active` = 1 
		');
    }
    public static function fetchMediaServer(&$slider)
    {
        $fields = array('image','thumb','image_multi_lang','thumb_multi_lang');
        if (is_string($slider) && $slider)
        {
            if (strpos($slider, '/upload/') === false && strpos($slider, '/modules/') === false)
                $slider = _THEME_PROD_PIC_DIR_.$slider;
            $slider = context::getContext()->link->protocol_content.Tools::getMediaServer($slider).$slider;
        }
        foreach($fields AS $field)
        {
            if (is_array($slider) && isset($slider[$field]) && $slider[$field])
            {
                if (strpos($slider[$field], '/upload/') === false && strpos($slider[$field], '/modules/') === false )
                    $slider[$field] = _THEME_PROD_PIC_DIR_.$slider[$field];
                $slider[$field] = context::getContext()->link->protocol_content.Tools::getMediaServer($slider[$field]).$slider[$field];
            }
        }
    }
}