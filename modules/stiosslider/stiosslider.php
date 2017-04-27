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
if (!defined('_PS_VERSION_'))
	exit;
    
include_once dirname(__FILE__).'/StIosSliderClass.php';
include_once dirname(__FILE__).'/StIosSliderGroup.php';
include_once dirname(__FILE__).'/StIosSliderFontClass.php';

class StIosSlider extends Module
{
    public $imgtype = array('jpg', 'gif', 'jpeg', 'png');
    protected static $access_rights = 0775;
    public static $location = array(
        1 => array('id' =>1 , 'name' => 'Full width top', 'templates' => array(0,1,2,3)),
        14 => array('id' =>14 , 'name' => 'Full width top(boxed)', 'templates' => array(0,1,2,3)),
        22 => array('id' =>22 , 'name' => 'Full width top 2', 'templates' => array(0,1,2,3)),
        23 => array('id' =>23 , 'name' => 'Full width top 2(boxed)', 'templates' => array(0,1,2,3)),
        21 => array('id' =>21 , 'name' => 'Top column', 'templates' => array(0,2)),
        4 => array('id' =>4 , 'name' => 'Homepage top', 'templates' => array(0,2)),
        3 => array('id' =>3 , 'name' => 'Homepage', 'templates' => array(0,2)),
        17 => array('id' =>17 , 'name' => 'Homepage bottom', 'templates' => array(0,2)),
        20 => array('id' =>20 , 'name' => 'Bottom column', 'templates' => array(0,2)),
        18 => array('id' =>18 , 'name' => 'Full width bottom(Home very bottom)', 'templates' => array(0,1,2,3)),
        7 => array('id' =>7 , 'name' => 'Blog homepage top(fullwidth)', 'templates' => array(0,1,2,3)),
        8 => array('id' =>8 , 'name' => 'Blog homepage top', 'templates' => array(0,1,2,3)),
        6 => array('id' =>6 , 'name' => 'Blog homepage', 'templates' => array(0,2)),
        11 => array('id' =>11 , 'name' => 'At bottom of product page', 'templates' => array(0,2)),
        12 => array('id' =>12 , 'name' => 'At bottom of category page', 'templates' => array(0,2)),
    );
    public static $text_position = array(
        array('id' =>'left_center' , 'name' => 'Left center'),
        array('id' =>'left_bottom' , 'name' => 'Left bottom'),
        array('id' =>'left_top' , 'name' => 'Left top'),
        array('id' =>'right_center' , 'name' => 'Right center'),
        array('id' =>'right_bottom' , 'name' => 'Right bottom'),
        array('id' =>'right_top' , 'name' => 'Right top'),
        array('id' =>'center_center' , 'name' => 'Center Center'),
        array('id' =>'center_bottom' , 'name' => 'Center bottom'),
        array('id' =>'center_top' , 'name' => 'Center top'),
    );
    private $systemFonts = array("Helvetica","Arial","Verdana","Georgia","Tahoma","Times New Roman","sans-serif");
    
    private $googleFonts; 

    public static $slide_shadow_arr = array(
        array('id' => 0 , 'name' => 'none'),
        array('id' => 1 , 'name' => 'Small'),
        array('id' => 2 , 'name' => 'Medium'),
        array('id' => 3 , 'name' => 'Large'),
    );
    
    public static $_type = array(
        1 => 'location',
        2 => 'id_category',
        4 => 'id_cms',
        5 => 'id_cms_category',
    );
    
    public  $text_animation_group;
    public static $text_animation = array(
        0=>'',
        1=>'flash',
        2=>'shake',
        3=>'bounce',
        4=>'tada',
        5=>'swing',
        6=>'wobble',
        7=>'pulse',
        8=>'flip',
        9=>'flipInX',
        11=>'flipInY',
        13=>'fadeIn',
        14=>'fadeInUp',
        15=>'fadeInDown',
        16=>'fadeInLeft',
        17=>'fadeInRight',
        18=>'fadeInUpBig',
        19=>'fadeInDownBig',
        20=>'fadeInLeftBig',
        21=>'fadeInRightBig',
        31=>'slideInDown',
        32=>'slideInLeft',
        33=>'slideInRight',
        37=>'bounceIn',
        38=>'bounceInUp',
        39=>'bounceInDown',
        40=>'bounceInLeft',
        41=>'bounceInRight',
        47=>'rotateIn',
        48=>'rotateInUpLeft',
        49=>'rotateInDownLeft',
        50=>'rotateInUpRight',
        51=>'rotateInDownRight',
        57=>'lightSpeedIn',
        60=>'rollIn',
    );

    public  $fields_list;
    public  $fields_list_slide;
    public  $fields_list_banner;
    public  $fields_value;
    public  $fields_form;
    public  $fields_form_slide;
	private $_html = '';
	private $spacer_size = '5';
	
	public function __construct()
	{
		$this->name          = 'stiosslider';
		$this->tab           = 'front_office_features';
		$this->version       = '1.5.9';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
        $this->bootstrap     = true;

		parent::__construct();
        $this->googleFonts = include_once(dirname(__FILE__).'/googlefonts.php');

        $this->text_animation_group = array(
            array('name'=>$this->l('Flippers'),'query'=>array(
                array('id'=>8, 'name'=>self::$text_animation[8]),
                array('id'=>9, 'name'=>self::$text_animation[9]),
                array('id'=>11, 'name'=>self::$text_animation[11]),
            )),
            array('name'=>$this->l('Fading Entrances'),'query'=>array(
                array('id'=>14, 'name'=>self::$text_animation[14]),
                array('id'=>15, 'name'=>self::$text_animation[15]),
                array('id'=>16, 'name'=>self::$text_animation[16]),
                array('id'=>17, 'name'=>self::$text_animation[17]),
                array('id'=>18, 'name'=>self::$text_animation[18]),
                array('id'=>19, 'name'=>self::$text_animation[19]),
                array('id'=>20, 'name'=>self::$text_animation[20]),
                array('id'=>21, 'name'=>self::$text_animation[21]),
            )),
            array('name'=>$this->l('Sliders'),'query'=>array(
                array('id'=>31, 'name'=>self::$text_animation[31]),
                array('id'=>32, 'name'=>self::$text_animation[32]),
                array('id'=>33, 'name'=>self::$text_animation[33]),
            )),
            array('name'=>$this->l('Bouncing Entrances'),'query'=>array(
                array('id'=>37, 'name'=>self::$text_animation[37]),
                array('id'=>38, 'name'=>self::$text_animation[38]),
                array('id'=>39, 'name'=>self::$text_animation[39]),
                array('id'=>40, 'name'=>self::$text_animation[40]),
                array('id'=>41, 'name'=>self::$text_animation[41]),
            )),
            array('name'=>$this->l('Rotating Entrances'),'query'=>array(
                array('id'=>47, 'name'=>self::$text_animation[47]),
                array('id'=>48, 'name'=>self::$text_animation[48]),
                array('id'=>49, 'name'=>self::$text_animation[49]),
                array('id'=>50, 'name'=>self::$text_animation[50]),
                array('id'=>51, 'name'=>self::$text_animation[51]),
            )),
            array('name'=>$this->l('Lightspeed'),'query'=>array(
                array('id'=>57, 'name'=>self::$text_animation[57]),
            )),
            array('name'=>$this->l('Specials'),'query'=>array(
                array('id'=>60, 'name'=>self::$text_animation[60]),
            )),
            array('name'=>$this->l('Attention seekers'),'query'=>array(
                array('id'=>1, 'name'=>self::$text_animation[1]),
                array('id'=>2, 'name'=>self::$text_animation[2]),
                array('id'=>3, 'name'=>self::$text_animation[3]),
                array('id'=>4, 'name'=>self::$text_animation[4]),
                array('id'=>5, 'name'=>self::$text_animation[5]),
                array('id'=>6, 'name'=>self::$text_animation[6]),
                array('id'=>7, 'name'=>self::$text_animation[7]),
            )),
        );
                
		$this->displayName   = $this->l('IosSlider');
		$this->description   = $this->l('Touch Enabled, Responsive jQuery Horizontal Content Slider Plugin.');
	}

	public function install()
	{
	    $res = $this->installDB() &&
            parent::install() &&
			$this->registerHook('displayHeader') &&
			$this->registerHook('displayLeftColumn') && 
			$this->registerHook('displayRightColumn') && 
            $this->registerHook('displayHome') &&
			$this->registerHook('displayHomeTop') &&
			$this->registerHook('displayHomeBottom') &&
			$this->registerHook('displayAnywhere') &&
			$this->registerHook('actionObjectCategoryDeleteAfter') &&
            $this->registerHook('displayFooterProduct') &&
            $this->registerHook('displayCategoryHeader') &&
            $this->registerHook('displayCategoryFooter') &&
            $this->registerHook('actionShopDataDuplication') &&
			$this->registerHook('displayStBlogHome') &&
			$this->registerHook('displayStBlogLeftColumn') && 
            $this->registerHook('displayStBlogRightColumn') && 
            $this->registerHook('displayHomeVeryBottom') && 
            $this->registerHook('displayFullWidthTop') && 
            $this->registerHook('displayFullWidthTop2') && 
            $this->registerHook('displayTopColumn') && 
            $this->registerHook('displayBottomColumn');
                    
		$this->clearIosSliderCache();
		return $res;
	}

	public function installDb()
	{
		/* Slides */
		$return = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_iosslider` (
				`id_st_iosslider` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_st_iosslider_group` int(10) unsigned NOT NULL,
				`id_currency` int(10) unsigned DEFAULT 0,
                `new_window` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `text_position` varchar(32) DEFAULT NULL,
                `text_align` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `title_color` varchar(7) DEFAULT NULL,
                `title_bg` varchar(7) DEFAULT NULL,
                `title_font_family` varchar(255) DEFAULT NULL,
                `description_color` varchar(7) DEFAULT NULL,
                `description_bg` varchar(7) DEFAULT NULL,
                `text_con_bg` varchar(7) DEFAULT NULL,
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `position` int(10) unsigned NOT NULL DEFAULT 0,
                `isbanner` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `text_width` tinyint(2) unsigned NOT NULL DEFAULT 40,
                `hide_text_on_mobile` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `text_animation` tinyint(2) unsigned NOT NULL DEFAULT 0,
                `btn_color` varchar(7) DEFAULT NULL,
                `btn_bg` varchar(7) DEFAULT NULL,
                `btn_hover_color` varchar(7) DEFAULT NULL,
                `btn_hover_bg` varchar(7) DEFAULT NULL,
				PRIMARY KEY (`id_st_iosslider`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		/* Slides lang configuration */
		$return && $return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_iosslider_lang` (
				`id_st_iosslider` int(10) UNSIGNED NOT NULL,
				`id_lang` int(10) unsigned NOT NULL ,
    			`url` varchar(255) DEFAULT NULL,
                `video` text,
                `description` text,
                `button` varchar(255) DEFAULT NULL,
                `image_multi_lang` varchar(255) DEFAULT NULL,
                `thumb_multi_lang` varchar(255) DEFAULT NULL,
                `title` varchar(255) DEFAULT NULL,
				PRIMARY KEY (`id_st_iosslider`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
            
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_iosslider_font` (
                `id_st_iosslider` int(10) unsigned NOT NULL,
                `font_name` varchar(255) NOT NULL
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		/* Slides group */
		$return && $return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_iosslider_group` (
				`id_st_iosslider_group` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,       
                `name` varchar(255) DEFAULT NULL,
                `location` int(10) unsigned NOT NULL DEFAULT 0,
                `id_cms` int(10) unsigned NOT NULL DEFAULT 0,
                `id_cms_category` int(10) unsigned NOT NULL DEFAULT 0,
                `id_category` int(10) unsigned NOT NULL DEFAULT 0,  
                `height` int(10) unsigned NOT NULL DEFAULT 500, 
                `prev_next` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `pag_nav` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `hide_on_mobile` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `time` int(10) unsigned NOT NULL DEFAULT 7000,
                `trans_period` int(10) unsigned NOT NULL DEFAULT 1000,
                `auto_advance` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `pause` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `scrollbar` tinyint(1) unsigned NOT NULL DEFAULT 0,    
                `scrollbar_bg` varchar(7) DEFAULT NULL,
                `scrollbar_color` varchar(7) DEFAULT NULL,
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `position` int(10) unsigned NOT NULL DEFAULT 0, 
                `desktopClickDrag` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `infiniteSlider` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `templates` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `bg_color` varchar(7) DEFAULT NULL,
                `bg_pattern` tinyint(2) unsigned NOT NULL DEFAULT 0, 
                `bg_img` varchar(255) DEFAULT NULL,
                `bg_repeat` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `bg_position` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `padding_tb` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `width` int(10) unsigned NOT NULL DEFAULT 900, 
                `slide_padding` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `slide_shadow` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `prev_next_color` varchar(7) DEFAULT NULL,
                `prev_next_bg` varchar(7) DEFAULT NULL,
                `pag_nav_bg` varchar(7) DEFAULT NULL,
                `pag_nav_bg_active` varchar(7) DEFAULT NULL,
                `top_spacing` varchar(10) DEFAULT NULL,
                `bottom_spacing` varchar(10) DEFAULT NULL,
                `show_on_sub` tinyint(1) unsigned NOT NULL DEFAULT 1,
				PRIMARY KEY (`id_st_iosslider_group`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		/* Slides group shop */
		$return && $return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_iosslider_group_shop` (
				`id_st_iosslider_group` int(10) UNSIGNED NOT NULL,
                `id_shop` int(11) NOT NULL,      
                PRIMARY KEY (`id_st_iosslider_group`,`id_shop`),    
                KEY `id_shop` (`id_shop`)   
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		return $return;
	}

	public function uninstall()
	{
	    $this->clearIosSliderCache();
		// Delete configuration
		return $this->uninstallDb() &&
			parent::uninstall();
	}

	private function uninstallDb()
	{
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_iosslider`,`'._DB_PREFIX_.'st_iosslider_lang`,`'._DB_PREFIX_.'st_iosslider_group`,`'._DB_PREFIX_.'st_iosslider_group_shop`');
	}
    private function _checkImageDir()
    {
        $result = '';
        if (!file_exists(_PS_UPLOAD_DIR_.$this->name))
        {
            $success = @mkdir(_PS_UPLOAD_DIR_.$this->name, self::$access_rights, true)
						|| @chmod(_PS_UPLOAD_DIR_.$this->name, self::$access_rights);
            if(!$success)
                $this->_html .= $this->displayError('"'._PS_UPLOAD_DIR_.$this->name.'" '.$this->l('An error occurred during new folder creation'));
        }

        if (!is_writable(_PS_UPLOAD_DIR_))
            $this->_html .= $this->displayError('"'._PS_UPLOAD_DIR_.$this->name.'" '.$this->l('directory isn\'t writable.'));
            
        if (!is_writable(_PS_MODULE_DIR_.$this->name.'/views/css'))
            $this->_html .= $this->displayError('"'._PS_MODULE_DIR_.$this->name.'/views/css'.'" '.$this->l('directory isn\'t writable.'));
        
        return $result;
    }
    
	public function getContent()
	{
        $check_result = $this->_checkImageDir();
		$this->context->controller->addCSS(($this ->_path).'views/css/admin.css');
		$this->context->controller->addJS($this->_path. 'views/js/admin.js');
        $this->_html .= '<script type="text/javascript">var stiosslider_base_uri = "'.__PS_BASE_URI__.'";var googleFontsString=\''.Tools::jsonEncode($this->googleFonts).'\';</script>';
        
        $id_st_iosslider_group = (int)Tools::getValue('id_st_iosslider_group');
        $id_st_iosslider = (int)Tools::getValue('id_st_iosslider');
	    if ((Tools::isSubmit('groupstatusstiosslider')))
        {
            $slide_group = new StIosSliderGroup((int)$id_st_iosslider_group);
            if($slide_group->id && $slide_group->toggleStatus())
            {
                $this->clearIosSliderCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
	    if ((Tools::isSubmit('slidestatusstiosslider')))
        {
            $slide = new StIosSliderClass((int)$id_st_iosslider);
            if($slide->id && $slide->toggleStatus())
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));  
                $this->clearIosSliderCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_iosslider_group='.$slide->id_st_iosslider_group.'&viewstiosslider&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
        if(Tools::getValue('act')=='delete_image' && $identi = Tools::getValue('identi'))
        {
            $result = array(
                'r' => false,
                'm' => '',
                'd' => ''
            );
            $slide_group = new StIosSliderGroup((int)(int)$identi);
            if(Validate::isLoadedObject($slide_group))
            {
                $slide_group->bg_img = '';
                if($slide_group->save())
                {
                    $module_instance = new StIosSlider();
                    $module_instance->writeCss();
                    $result['r'] = true;
                }
            }
            die(json_encode($result));
        }
        if ((Tools::isSubmit('groupdeleteimagestiosslider')))
        {
            $slide_group = new StIosSliderGroup($id_st_iosslider_group);
            if($slide_group->id)
            {
                @unlink(_PS_ROOT_DIR_._THEME_PROD_PIC_DIR_.$this->name.'/'.$slide_group->bg_img);
                $slide_group->bg_img = '';
                if ($slide_group->save())
                {
                    //$this->_html .= $this->displayConfirmation($this->l('The image was deleted successfully.'));  
                    $this->clearIosSliderCache();
                    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=7&updatestiosslider&id_st_iosslider_group='.(int)$slide_group->id.'&token='.Tools::getAdminTokenLite('AdminModules'));   
                }else
                    $this->_html .= $this->displayError($this->l('An error occurred while delete banner.'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while delete banner.'));
        }
        if (Tools::isSubmit('way') && Tools::isSubmit('id_st_iosslider') && (Tools::isSubmit('position')))
		{
		    $slide = new StIosSliderClass((int)$id_st_iosslider);
            if($slide->id && $slide->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position')))
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));  
                $this->clearIosSliderCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_iosslider_group='.$slide->id_st_iosslider_group.'&viewstiosslider&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            else
                $this->_html .= $this->displayError($this->l('Failed to update the position.'));
		}
        if (Tools::getValue('action') == 'updatePositions')
        {
            $this->processUpdatePositions();
        }
        if (Tools::isSubmit('copystiosslider'))
        {
            if($this->processCopyIosSlider($id_st_iosslider_group))
            {
                $this->clearIosSliderCache();
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=19&token='.Tools::getAdminTokenLite('AdminModules'));
            } 
            else
                $this->_html .= $this->displayError($this->l('An error occurred while copy IosSlider.'));
        }
		if (isset($_POST['savestiosslidergroup']) || isset($_POST['savestiosslidergroupAndStay']))
		{
            if ($id_st_iosslider_group)
				$slide_group = new StIosSliderGroup((int)$id_st_iosslider_group);
			else
				$slide_group = new StIosSliderGroup();
            
            $error = array();
    		$slide_group->copyFromPost();
            
            if(!$slide_group->name)
                $error[] = $this->displayError($this->l('The field "Slideshow name" is required'));
                
            if(!count($error))
            {
    			if (isset($_FILES['bg_img']) && isset($_FILES['bg_img']['tmp_name']) && !empty($_FILES['bg_img']['tmp_name'])) 
                {
    				if ($vali = ImageManager::validateUpload($_FILES['bg_img'], Tools::convertBytes(ini_get('upload_max_filesize'))))
					   $error[] = Tools::displayError($vali);
                    else 
                    {
                        $bg_image = $this->uploadCheckAndGetName($_FILES['bg_img']['name']);
                        if(!$bg_image)
                            $error[] = Tools::displayError('Image format not recognized');
    					if (!move_uploaded_file($_FILES['bg_img']['tmp_name'], _PS_UPLOAD_DIR_.$this->name.'/'.$bg_image))
    						$error[] = Tools::displayError('Error move uploaded file');
                        else
    					   $slide_group->bg_img = $bg_image;
    				}
    			}
            }
            
            if($slide_group->location)
            {
                $item_arr = explode('-',$slide_group->location);
                if(count($item_arr)==2)
                {
                    foreach(self::$_type as $k=>$v)
                    {
                        if($k==$item_arr[0])
                            $slide_group->$v = (int)$item_arr[1];
                        else
                            $slide_group->$v = 0;
                    }
                }
            }  
            
			if (!count($error) && $slide_group->validateFields(false) && $slide_group->validateFieldsLang(false))
            {
                if($slide_group->save())
                {
		            Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'st_iosslider_group_shop WHERE id_st_iosslider_group='.(int)$slide_group->id);
                    if (!Shop::isFeatureActive())
            		{
            			Db::getInstance()->insert('st_iosslider_group_shop', array(
            				'id_st_iosslider_group' => (int)$slide_group->id,
            				'id_shop' => (int)Context::getContext()->shop->id,
            			));
            		}
            		else
            		{
            			$assos_shop = Tools::getValue('checkBoxShopAsso_st_iosslider_group');
            			if (empty($assos_shop))
            				$assos_shop[(int)Context::getContext()->shop->id] = Context::getContext()->shop->id;
            			foreach ($assos_shop as $id_shop => $row)
            				Db::getInstance()->insert('st_iosslider_group_shop', array(
            					'id_st_iosslider_group' => (int)$slide_group->id,
            					'id_shop' => (int)$id_shop,
            				));
            		}
                    $this->clearIosSliderCache();
                    if(isset($_POST['savestiosslidergroupAndStay']) || Tools::getValue('fr') == 'view')
                    {
                        $rd_str = isset($_POST['savestiosslidergroupAndStay']) && Tools::getValue('fr') == 'view' ? 'fr=view&update' : (isset($_POST['savestiosslidergroupAndStay']) ? 'update' : 'view');
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_iosslider_group='.$slide_group->id.'&conf='.($id_st_iosslider_group?4:3).'&'.$rd_str.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules')); 
                    }    
                    else
                        $this->_html .= $this->displayConfirmation($this->l('Slideshow').' '.($id_st_iosslider_group ? $this->l('updated') : $this->l('added')));
                }                    
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during slideshow').' '.($id_st_iosslider_group ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }
		if (isset($_POST['savestiosslider']) || isset($_POST['savestiossliderAndStay']))
		{
            if ($id_st_iosslider)
				$slide = new StIosSliderClass((int)$id_st_iosslider);
			else
				$slide = new StIosSliderClass();
            /**/
            
            $error = array();
            
            $languages = Language::getLanguages(false);
            $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
            if (!Tools::isSubmit('has_image_'.$default_lang) && (!isset($_FILES['image_multi_lang_'.$default_lang]) || empty($_FILES['image_multi_lang_'.$default_lang]['tmp_name'])))
			{
                $defaultLanguage = new Language($default_lang);
			    $error[] = $this->displayError($this->l('Image is required at least in ').$defaultLanguage->name);
			}
            else
            {
			    $slide->copyFromPost();
                if(!$slide->id_st_iosslider_group)
                    $error[] = $this->displayError($this->l('The field "Slideshow" is required'));
                else
                {
                    $res = $this->stUploadImage('image_multi_lang_'.$default_lang);
                    if(count($res['error']))
                        $error = array_merge($error,$res['error']);
                    elseif($res['image'] && $res['thumb'])
                    {
                        $slide->image_multi_lang[$default_lang] = $res['image'];
                        $slide->thumb_multi_lang[$default_lang] = $res['thumb'];
                    }
                    elseif(!Tools::isSubmit('has_image_'.$default_lang) && !$res['image'] && !$res['thumb'])
                    {
                        $defaultLanguage = new Language($default_lang);
                        $error[] = $this->displayError($this->l('Image is required at least in ').$defaultLanguage->name);
                    }
                    
                    if($slide->image_multi_lang[$default_lang] && $slide->thumb_multi_lang[$default_lang])
                    {
                        foreach ($languages as $lang)
                        {
                            if($lang['id_lang']==$default_lang)
                                continue;
                            $res = $this->stUploadImage('image_multi_lang_'.$lang['id_lang']);
                            if(count($res['error']))
                                $error = array_merge($error,$res['error']);
                            elseif($res['image'] && $res['thumb'])
                            {
                                $slide->image_multi_lang[$lang['id_lang']] = $res['image'];
                                $slide->thumb_multi_lang[$lang['id_lang']] = $res['thumb'];
                            }
                            elseif(!Tools::isSubmit('has_image_'.$lang['id_lang']) && !$res['image'] && !$res['thumb'])
                            {
                                $slide->image_multi_lang[$lang['id_lang']] = $slide->image_multi_lang[$default_lang];
                                $slide->thumb_multi_lang[$lang['id_lang']] = $slide->thumb_multi_lang[$default_lang];
                            }
                        }
                    }
                }
            }
                
			if (!count($error) && $slide->validateFields(false) && $slide->validateFieldsLang(false))
            {
                /*position*/
                $slide->position = $slide->checkPosition();
                
                if($slide->save())
                {
                    $jon = trim(Tools::getValue('google_font_name'),'¤');
                    StIosSliderFontClass::deleteBySlider($slide->id);
                    $jon_arr = array_unique(explode('¤', $jon));
                    if (count($jon_arr))
                        StIosSliderFontClass::changeSliderFont($slide->id, $jon_arr);

                    $this->clearIosSliderCache();
                    //$this->_html .= $this->displayConfirmation($this->l('Slide').' '.($id_st_iosslider ? $this->l('updated') : $this->l('added')));
                    if(isset($_POST['savestiossliderAndStay']))
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_iosslider='.$slide->id.'&conf='.($id_st_iosslider?4:3).'&update'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));            
                    else
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_iosslider_group='.$slide->id_st_iosslider_group.'&viewstiosslider&token='.Tools::getAdminTokenLite('AdminModules'));
                     
                }
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during slide').' '.($id_st_iosslider ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }
        
		if(Tools::isSubmit('selecttemplates'))
        {
            $output = '<fieldset>
				<legend>'.$this->l('Select a template').'</legend><ul id="iosslider_templates_list">';
                
            for($i=0; $i<4; $i++)
                $output .= '<li><a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&addstiosslidergroup&templates='.$i.'&token='.Tools::getAdminTokenLite('AdminModules').'" title="'.$this->l('Template').' '.$i.'"><img src="'.$this ->_path.'/views/img/t_'.$i.'.jpg" /><br>'.$this->l('Template').' '.$i.'</a></li>';
    
            $output .= '</ul></fieldset>';
            
            return $this->_html.$output;
        }
        elseif(Tools::isSubmit('addstiosslidergroup') || (Tools::isSubmit('updatestiosslider') && $id_st_iosslider_group))
		{
            $helper = $this->initForm();
            return $this->_html.$helper->generateForm($this->fields_form);
		}
        elseif(Tools::isSubmit('addstiosslider') || (Tools::isSubmit('updatestiosslider') && $id_st_iosslider))
        {
            $helper = $this->initFormSlide(0);
            return $this->_html.$helper->generateForm($this->fields_form_slide);
        }
        elseif(Tools::isSubmit('addstiossliderbanner') || (Tools::isSubmit('updatestiosslider') && $id_st_iosslider))
        {
            $helper = $this->initFormSlide(1);
            return $this->_html.$helper->generateForm($this->fields_form_slide);
        }
        elseif(Tools::isSubmit('viewstiosslider'))
        {
            $this->_html .= '<script type="text/javascript">var currentIndex="'.AdminController::$currentIndex.'&configure='.$this->name.'";</script>';
			$slide_group = new StIosSliderGroup($id_st_iosslider_group);
            if(!$slide_group->isAssociatedToShop())
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
			$helper = $this->initListSlide();
            /*
            if($slide_group->location==15 || $slide_group->location==16 || $slide_group->location==18 || $slide_group->location==19)
                $helper_banner = $this->initListBanner();
            */
			return $this->_html.$helper->generateList(StIosSliderClass::getAll($id_st_iosslider_group,(int)$this->context->language->id,0,0), $this->fields_list).(isset($helper_banner) ? $helper_banner->generateList(StIosSliderClass::getAll($id_st_iosslider_group,(int)$this->context->language->id,0,1), $this->fields_list_banner) : '');
        
        }
		else if (Tools::isSubmit('deletestiosslider') && $id_st_iosslider)
		{
			$slide = new StIosSliderClass($id_st_iosslider);
            $slide->delete();
            $this->clearIosSliderCache();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_iosslider_group='.$id_st_iosslider_group.'&viewstiosslider&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else if (Tools::isSubmit('deletestiosslider') && $id_st_iosslider_group)
		{
			$slide_group = new StIosSliderGroup($id_st_iosslider_group);
            $slide_group->delete();
            $this->clearIosSliderCache();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else
		{
			$helper = $this->initList();
			return $this->_html.$helper->generateList(StIosSliderGroup::getAll(), $this->fields_list);
		}
	}
    
    public function uploadCheckAndGetName($name)
    {
		$type = strtolower(substr(strrchr($name, '.'), 1));
        if(!in_array($type, $this->imgtype))
            return false;
        $filename = Tools::encrypt($name.sha1(microtime()));
		while (file_exists(_PS_UPLOAD_DIR_.$filename.'.'.$type)) {
            $filename .= rand(10, 99);
        } 
        return $filename.'.'.$type;
    }
    public static function getType($row)
    {
        $type = array_flip(self::$_type);
        if($row['location'])
            return $type['location'];
        if($row['id_category'])
            return $type['id_category'];
        if($row['id_cms'])
            return $type['id_cms'];
        if($row['id_cms_category'])
            return $type['id_cms_category'];
        return false;
    }
    protected function stUploadImage($item)
    {
        $result = array(
            'error' => array(),
            'image' => '',
            'thumb' => '',
        );
        if (isset($_FILES[$item]) && isset($_FILES[$item]['tmp_name']) && !empty($_FILES[$item]['tmp_name']))
		{
			$type = strtolower(substr(strrchr($_FILES[$item]['name'], '.'), 1));
			$imagesize = array();
			$imagesize = @getimagesize($_FILES[$item]['tmp_name']);
			if (!empty($imagesize) &&
				in_array(strtolower(substr(strrchr($imagesize['mime'], '/'), 1)), array('jpg', 'gif', 'jpeg', 'png')) &&
				in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
			{
                $this->_checkEnv();
				$temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
				$salt = sha1(microtime());
                $c_name = Tools::encrypt($_FILES[$item]['name'].$salt);
                $c_name_thumb = $c_name.'_thumb';
				if ($upload_error = ImageManager::validateUpload($_FILES[$item]))
					$result['error'][] = $upload_error;
				elseif (!$temp_name || !move_uploaded_file($_FILES[$item]['tmp_name'], $temp_name))
					$result['error'][] = $this->displayError($this->l('An error occurred during move image.'));
				else{
				   $infos = getimagesize($temp_name);
                   $ratio_y = 72;
    			   $ratio_x = $infos[0] / ($infos[1] / $ratio_y);
                   if(!ImageManager::resize($temp_name, _PS_UPLOAD_DIR_.$this->name.'/'.$c_name.'.'.$type, null, null, $type) || !ImageManager::resize($temp_name, _PS_UPLOAD_DIR_.$this->name.'/'.$c_name_thumb.'.'.$type, $ratio_x, $ratio_y, $type))
				       $result['error'][] = $this->displayError($this->l('An error occurred during the image upload.'));
				} 
				if (isset($temp_name))
					@unlink($temp_name);
                    
                if(!count($result['error']))
                {
                    $result['image'] = $this->name.'/'.$c_name.'.'.$type;
                    $result['thumb'] = $this->name.'/'.$c_name_thumb.'.'.$type;
                }
                return $result;
			}
        }
        else
            return $result;
    }
    private function _checkEnv()
    {
        $file = _PS_UPLOAD_DIR_.'.htaccess';
        $file_tpl = _PS_MODULE_DIR_.'stthemeeditor/config/upload_htaccess.tpl';
        if (!file_exists($file) || !file_exists($file_tpl))
            return true;
        if (!is_writeable($file) || !is_readable($file_tpl))
            return false;
        
        return @file_put_contents($file, @file_get_contents($file_tpl));
    }
    public function writeCss()
    {
        $css = '';
        $options = StIosSliderClass::getOptions();
        if(is_array($options) && count($options))
            foreach($options as $v)    
            {
                $classname = '#iosSliderBanner_'.$v['id_st_iosslider'].' ';

                if($v['title_color'])
                    $css .= $classname.'.iosSlider_text, 
                '.$classname.'.iosSlider_text a, 
                '.$classname.'.iosslider_btn{color:'.$v['title_color'].';}';
                if($v['title_bg'])
                    $css .= ''.$classname.'.iosSlider_text h1, '.$classname.'.iosSlider_text h2, '.$classname.'.iosSlider_text h3, '.$classname.'.iosSlider_text h4, '.$classname.'.iosSlider_text h5, '.$classname.'.iosSlider_text h6, '.$classname.'.iosSlider_text p{padding: 0.2em 0.3em;background-color:'.$v['title_bg'].';background-color:rgba('.self::hex2rgb($v['title_bg']).',0.4);}'.$classname.'.iosSlider_text p{padding: 0.4em 0.6em;}';

                if($v['title_font_family'])
                    $css .= ''.$classname.'.iosSlider_text h1, '.$classname.'.iosSlider_text h2, '.$classname.'.iosSlider_text h3, '.$classname.'.iosSlider_text h4, '.$classname.'.iosSlider_text h5, '.$classname.'.iosSlider_text h6{font-family:\''.$v['title_font_family'].'\';}';

                /*$css_description = '';
                if($v['description_color'])
                    $css_description .= 'color:'.$v['description_color'].';';
                if($v['description_bg'])
                    $css_description .= 'background-color:'.$v['description_bg'].';background-color:rgba('.self::hex2rgb($v['description_bg']).',0.4);';
                if($css_description)
                    $css .= '#iosSliderBanner_'.$v['id_st_iosslider'].' .iosSlider_text p{'.$css_description.'}';        */
                if($v['btn_color'])
                    $css .= $classname.'.iosSlider_text .btn{color:'.$v['btn_color'].';}';
                if($v['btn_bg'])
                    $css .= $classname.'.iosSlider_text .btn{background-color:'.$v['btn_bg'].';}';
                if($v['btn_hover_color'])
                    $css .= $classname.'.iosSlider_text .btn:hover{color:'.$v['btn_hover_color'].';}';
                if ($v['btn_hover_bg'])
                    $css .= $classname.'.iosSlider_text .btn:hover{background-color: '.$v['btn_hover_bg'].';}';
                if($v['text_con_bg'])
                    $css .= '#iosSliderBanner_'.$v['id_st_iosslider'].' .iosSlider_text{padding:10px;background-color:'.$v['text_con_bg'].';background-color:rgba('.self::hex2rgb($v['text_con_bg']).',0.4);}';     
            }
        
        $options = StIosSliderGroup::getOptions();
        if(is_array($options) && count($options))
            foreach($options as $v)    
            {
                $group_css = '';
                if ($v['bg_color'])
        			$group_css .= 'background-color:'.$v['bg_color'].';';
                if ($v['bg_pattern'] && $v['bg_img']=="")
        			$group_css .= 'background-image: url('._MODULE_DIR_.'stthemeeditor/patterns/'.$v['bg_pattern'].'.png);';
                if ($v['bg_img'])
        			$group_css .= 'background-image:url(../../../../upload/'.$this->name.'/'.$v['bg_img'].');';
        		if ($v['bg_repeat']) {
        			switch($v['bg_repeat']) {
        				case 1 :
        					$repeat_option = 'repeat-x';
        					break;
        				case 2 :
        					$repeat_option = 'repeat-y';
        					break;
        				case 3 :
        					$repeat_option = 'no-repeat';
        					break;
        				default :
        					$repeat_option = 'repeat';
        			}
        			$group_css .= 'background-repeat:'.$repeat_option.';';
        		}
        		if ($v['bg_position']) {
        			switch($v['bg_position']) {
        				case 1 :
        					$position_option = 'center top';
        					break;
        				case 2 :
        					$position_option = 'right top';
        					break;
        				default :
        					$position_option = 'left top';
        			}
        			$group_css .= 'background-position: '.$position_option.';';
        		}     

                if(isset($v['top_spacing']) && ($v['top_spacing'] || $v['top_spacing']==='0'))
                    $group_css .= 'margin-top:'.(int)$v['top_spacing'].'px;';
                if(isset($v['bottom_spacing']) && ($v['bottom_spacing'] || $v['bottom_spacing']==='0'))
                    $group_css .= 'margin-bottom:'.(int)$v['bottom_spacing'].'px;';  
                
                if($group_css)
                    $css .= '#iosSlider_'.$v['id_st_iosslider_group'].'.fullwidth_default,#iosSlider_containerOuter_'.$v['id_st_iosslider_group'].'.containerOuter_fullwidth_boxed,#iosSlider_'.$v['id_st_iosslider_group'].'.multi_slide, #iosSlider_containerOuter_'.$v['id_st_iosslider_group'].'.containerOuter_center_background,#iosSlider_containerOuter_'.$v['id_st_iosslider_group'].'.containerOuter_multi_slide{'.$group_css.'}';
                    
                if ($v['prev_next_color'])
        			$css .= '#iosSlider_'.$v['id_st_iosslider_group'].' .iosSlider_prev i,#iosSlider_'.$v['id_st_iosslider_group'].' .iosSlider_next i{color:'.$v['prev_next_color'].';}';
                if ($v['prev_next_bg'])
        			$css .= '#iosSlider_'.$v['id_st_iosslider_group'].' .iosSlider_prev i,#iosSlider_'.$v['id_st_iosslider_group'].' .iosSlider_next i{background-color:'.$v['prev_next_bg'].';}';
                    
                if ($v['pag_nav_bg'])
        			$css .= '#iosSlider_'.$v['id_st_iosslider_group'].' .iosSlider_selectors .selectoritem{border-color:'.$v['pag_nav_bg'].';}#iosSlider_'.$v['id_st_iosslider_group'].' .iosSlider_selectors .selectoritem span{background-color:'.$v['pag_nav_bg'].';}';
                if ($v['pag_nav_bg_active'])
        			$css .= '#iosSlider_'.$v['id_st_iosslider_group'].' .iosSlider_selectors .selectoritem.selected,#iosSlider_'.$v['id_st_iosslider_group'].' .iosSlider_selectors .selectoritem:hover{border-color:'.$v['pag_nav_bg_active'].';}#iosSlider_'.$v['id_st_iosslider_group'].' .iosSlider_selectors .selectoritem.selected span,#iosSlider_'.$v['id_st_iosslider_group'].' .iosSlider_selectors .selectoritem:hover span{background-color:'.$v['pag_nav_bg_active'].';}';
                    
                if($v['templates']==3)
                {
                    $width = $v['width'] ? $v['width'] : 900;
                    $height = $v['height'] ? $v['height'] : 500;
                    $css .= '#iosSlider_containerOuter_'.$v['id_st_iosslider_group'].' .container_multi_slide{height: '.$height.'px;}';
                    $css .= '#iosSlider_'.$v['id_st_iosslider_group'].' .slider .iosSlideritem{width: '.($width+2*$v['slide_padding']).'px;}';
                    
                        $css .= '@media screen and (max-width: '.$width.'px) {#iosSlider_containerOuter_'.$v['id_st_iosslider_group'].' .container_multi_slide.iosslider_stretched{height:0;padding: 0 0 '.sprintf("%.3f",$height/$width*100).'% 0;}}';
                    
                        if($width>1170)
                        {
                            $max_width = $width;
                            $screen_width = 1170;
                            $gap_width = 30;
                        }
                        elseif($width>940)
                        {
                            $max_width = 1200;
                            $screen_width = 940;
                            $gap_width = 20;
                        }
                        elseif($width>724)
                        {
                            $max_width = 979;
                            $screen_width = 724;
                            $gap_width = 44;
                        }
                        elseif($width>580)
                        {
                            $max_width = 767;
                            $screen_width = 580;
                            $gap_width = 40;
                        }
                        elseif($width>440)
                        {
                            $max_width = 639;
                            $screen_width = 440;
                            $gap_width = 20;
                        }
                        else
                        {
                            $max_width = 479;  
                            $screen_width = 300;
                            $gap_width = 20;
                        }                     
                        $css .= '@media screen and (max-width: '.($max_width).'px) {#iosSlider_containerOuter_'.$v['id_st_iosslider_group'].' .container_multi_slide.iosslider_boxed{height:0;line-height:0;font-size:0;padding: 0 0 '.sprintf("%.3f",($height/$width*($screen_width-$gap_width-2*(int)$v['slide_padding']))/$screen_width*100).'% 0;}}';
                    
                    if($v['slide_padding'])
                        $css .= '#iosSlider_'.$v['id_st_iosslider_group'].'.multi_slide .slider .iosSlideritem img{padding-right:'.$v['slide_padding'].'px;padding-left:'.$v['slide_padding'].'px;}';
                    switch($v['slide_shadow'])
                    {
                        case 1:
                            $css .= '#iosSlider_'.$v['id_st_iosslider_group'].'.multi_slide .slider .iosSlideritem img{-webkit-box-shadow: 0 0 6px -3px #000000;-moz-box-shadow: 0 0 6px -3px #000000;box-shadow: 0 0 6px -3px #000000;}';
                        break;
                        case 2:
                            $css .= '#iosSlider_'.$v['id_st_iosslider_group'].'.multi_slide .slider .iosSlideritem img{-webkit-box-shadow: 0 0 10px -6px #000000;-moz-box-shadow: 0 0 10px -6px #000000;box-shadow: 0 0 10px -6px #000000;}';
                        break;
                        case 3:
                            $css .= '#iosSlider_'.$v['id_st_iosslider_group'].'.multi_slide .slider .iosSlideritem img{-webkit-box-shadow: 0 0 10px -4px #000000);-moz-box-shadow: 0 0 10px -4px #000000;box-shadow: 0 0 10px -4px #000000;}';
                        break;
                        default:
                        break;
                    }
                }
                elseif($v['templates']==2)
                {
                    $width = $v['width'] ? $v['width'] : 900;
                    $height = $v['height'] ? $v['height'] : 500;
                    $css .= '#iosSlider_'.$v['id_st_iosslider_group'].' .slider .iosSlideritem{width: '.$width.'px;}';
                    $css .= '#iosSlider_containerOuter_'.$v['id_st_iosslider_group'].', #iosSlider_containerOuter_'.$v['id_st_iosslider_group'].' .container_center_background, #iosSlider_containerOuter_'.$v['id_st_iosslider_group'].' .iosSlider_container_center_background,#iosSlider_'.$v['id_st_iosslider_group'].'{height: '.($height+2*$v['padding_tb']).'px;}';
                    
                    $left_right = $width/2+48+$v['slide_padding'];
                    $css .= '#iosSlider_'.$v['id_st_iosslider_group'].'.center_background .iosSlider_prev{margin-left:-'.sprintf("%.3f",$left_right+1).'px;}.center_background .iosSlider_next{margin-right:-'.sprintf("%.3f",$left_right-1).'px;}';
                    
                    $css .= '#iosSlider_'.$v['id_st_iosslider_group'].'.center_background .slider .iosSlideritem_inner{margin-right:'.(int)$v['slide_padding'].'px;margin-left:'.(int)$v['slide_padding'].'px;}';
                    switch($v['slide_shadow'])
                    {
                        case 1:
                            $css .= '#iosSlider_'.$v['id_st_iosslider_group'].'.center_background .slider .iosSlideritem_inner{-webkit-box-shadow: 0 0 6px -3px #000000;-moz-box-shadow: 0 0 6px -3px #000000;box-shadow: 0 0 6px -3px #000000;}';
                        break;
                        case 2:
                            $css .= '#iosSlider_'.$v['id_st_iosslider_group'].'.center_background .slider .iosSlideritem_inner{-webkit-box-shadow: 0 0 10px -6px #000000;-moz-box-shadow: 0 0 10px -6px #000000;box-shadow: 0 0 10px -6px #000000;}';
                        break;
                        case 3:
                            $css .= '#iosSlider_'.$v['id_st_iosslider_group'].'.center_background .slider .iosSlideritem_inner{-webkit-box-shadow: 0 0 10px -4px #000000);-moz-box-shadow: 0 0 10px -4px #000000;box-shadow: 0 0 10px -4px #000000;}';
                        break;
                        default:
                        break;
                    }
                    
                }
                elseif($v['templates']==1)
                {
                    switch($v['slide_shadow'])
                    {
                        case 1:
                            $css .= '#iosSlider_'.$v['id_st_iosslider_group'].'.fullwidth_default_boxed .container{-webkit-box-shadow: 0 0 6px -3px #000000;-moz-box-shadow: 0 0 6px -3px #000000;box-shadow: 0 0 6px -3px #000000;}';
                        break;
                        case 2:
                            $css .= '#iosSlider_'.$v['id_st_iosslider_group'].'.fullwidth_default_boxed .container{-webkit-box-shadow: 0 0 10px -6px #000000;-moz-box-shadow: 0 0 10px -6px #000000;box-shadow: 0 0 10px -6px #000000;}';
                        break;
                        case 3:
                            $css .= '#iosSlider_'.$v['id_st_iosslider_group'].'.fullwidth_default_boxed .container{-webkit-box-shadow: 0 0 10px -4px #000000);-moz-box-shadow: 0 0 10px -4px #000000;box-shadow: 0 0 10px -4px #000000;}';
                        break;
                        default:
                        break;
                    }
                }
                
            }
        $cssFile = $this->local_path."views/css/custom.css";
		$write_fd = fopen($cssFile, 'w') or die('can\'t open file "'.$cssFile.'"');
		fwrite($write_fd, $css);
		fclose($write_fd);
        return true;
    }
    public static function getApplyTo($templates=0)
    {
        $res = array();
        $module = new StIosSlider();
        $location = array();
        foreach(self::$location as $v)
            if(in_array($templates, $v['templates']))
                $location[] = array('id'=>'1-'.$v['id'],'name'=>$v['name']);
            
        $res[] = array('name'=>$module->l('Hook'),'query'=>$location);
        
        if($templates==0)
        {
            $root_category = Category::getRootCategory();
            $category_arr = array();
            $module->getCategoryOption($category_arr,$root_category->id);
            //unset root category
            if(isset($category_arr[$root_category->id]))
                unset($category_arr[$root_category->id]);
            $res[] = array('name'=>$module->l('Category'),'query'=>$category_arr);
            
            // cms
            $cms_arr = array();
            $module->getCMSOptions($cms_arr, 0, 1);
            $res[] = array('name'=>$module->l('CMS'),'query'=>$cms_arr);
        }        
        return $res;
    }
    
    private function getCMSOptions(&$cms_arr, $parent = 0, $depth = 1, $id_lang = false)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		//$categories = $this->getCMSCategories(false, (int)$parent, (int)$id_lang);
		$pages = $this->getCMSPages((int)$parent, false, (int)$id_lang);

		$spacer = str_repeat('&nbsp;', $this->spacer_size * (int)$depth);

		/*foreach ($categories as $category)
		{
            $cms_arr[] = array('id'=>'5-'.$category['id_cms_category'],'name'=>$spacer.$category['name']);
			$this->getCMSOptions($cms_arr, $category['id_cms_category'], (int)$depth + 1, (int)$id_lang);
		}*/

		foreach ($pages as $page)
            $cms_arr[] = array('id'=>'4-'.$page['id_cms'],'name'=>$spacer.$page['meta_title']);
	}

    private function getCMSCategories($recursive = false, $parent = 1, $id_lang = false, $id_shop = false)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;

		if ($recursive === false)
        {
            if(version_compare(_PS_VERSION_, '1.6.0.12', '>='))
                $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
                FROM `'._DB_PREFIX_.'cms_category` bcp
                INNER JOIN `'._DB_PREFIX_.'cms_category_shop` cs
                ON (bcp.`id_cms_category` = cs.`id_cms_category`)
                INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
                ON (bcp.`id_cms_category` = cl.`id_cms_category`)
                WHERE cl.`id_lang` = '.(int)$id_lang.'
                AND cs.`id_shop` = '.(int)$id_shop.'
                AND cl.`id_shop` = '.(int)$id_shop.'
                AND bcp.`id_parent` = '.(int)$parent;
            else
                $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
                FROM `'._DB_PREFIX_.'cms_category` bcp
                INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
                ON (bcp.`id_cms_category` = cl.`id_cms_category`)
                WHERE cl.`id_lang` = '.(int)$id_lang.'
                AND bcp.`id_parent` = '.(int)$parent;

            return Db::getInstance()->executeS($sql);
        }
        else
        {
            if(version_compare(_PS_VERSION_, '1.6.0.12', '>='))
                $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
                FROM `'._DB_PREFIX_.'cms_category` bcp
                INNER JOIN `'._DB_PREFIX_.'cms_category_shop` cs
                ON (bcp.`id_cms_category` = cs.`id_cms_category`)
                INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
                ON (bcp.`id_cms_category` = cl.`id_cms_category`)
                WHERE cl.`id_lang` = '.(int)$id_lang.'
                AND cs.`id_shop` = '.(int)$id_shop.'
                AND cl.`id_shop` = '.(int)$id_shop.'
                AND bcp.`id_parent` = '.(int)$parent;
            else
                $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
                FROM `'._DB_PREFIX_.'cms_category` bcp
                INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
                ON (bcp.`id_cms_category` = cl.`id_cms_category`)
                WHERE cl.`id_lang` = '.(int)$id_lang.'
                AND bcp.`id_parent` = '.(int)$parent;

			$results = Db::getInstance()->executeS($sql);
			foreach ($results as $result)
			{
				$sub_categories = $this->getCMSCategories(true, $result['id_cms_category'], (int)$id_lang);
				if ($sub_categories && count($sub_categories) > 0)
					$result['sub_categories'] = $sub_categories;
				$categories[] = $result;
			}

			return isset($categories) ? $categories : false;
		}

	}

	private function getCMSPages($id_cms_category, $id_shop = false, $id_lang = false)
	{
		$id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		$sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
			FROM `'._DB_PREFIX_.'cms` c
			INNER JOIN `'._DB_PREFIX_.'cms_shop` cs
			ON (c.`id_cms` = cs.`id_cms`)
			INNER JOIN `'._DB_PREFIX_.'cms_lang` cl
			ON (c.`id_cms` = cl.`id_cms`)
			WHERE '.($id_cms_category?'c.`id_cms_category` = '.(int)$id_cms_category:'1').'
            AND cs.`id_shop` = '.(int)$id_shop.
            (version_compare(_PS_VERSION_, '1.6.0.12', '>=') ? ' AND cl.`id_shop` = '.(int)$id_shop : '' ).' 
			AND cl.`id_lang` = '.(int)$id_lang.'
			AND c.`active` = 1
			ORDER BY `position`';

		return Db::getInstance()->executeS($sql);
	}
    
    private function getCategoryOption(&$category_arr,$id_category = 1, $id_lang = false, $id_shop = false, $recursive = true)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);

		if (is_null($category->id))
			return;

		if ($recursive)
		{
			$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
			$spacer = str_repeat('&nbsp;', $this->spacer_size * (int)$category->level_depth);
		}

		$shop = (object) Shop::getShop((int)$category->getShopID());
		$category_arr[$category->id] = array(
            'id' => '2-'.$category->id,
            'name' => (isset($spacer) ? $spacer : '').$category->name.' ('.$shop->name.')',
        );
        
		if (isset($children) && count($children))
			foreach ($children as $child)
			{
				$this->getCategoryOption($category_arr,(int)$child['id_category'], (int)$id_lang, (int)$child['id_shop'],$recursive);
			}
	}
        
	protected function initForm()
	{        
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Slide configuration'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Slideshow name:'),
					'name' => 'name',
                    'size' => 64,
                    'required'  => true,
                    
				),
                'location' => array(
					'type' => 'select',
        			'label' => $this->l('Hook into:'),
        			'name' => 'location',
                    'options' => array(
                        'optiongroup' => array (
							'query' => $this->getApplyTo(),
							'label' => 'name'
						),
						'options' => array (
							'query' => 'query',
							'id' => 'id',
							'name' => 'name'
						),
						'default' => array(
							'value' => 0,
							'label' => $this->l('--')
						)
        			),
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Show on subcategories:'),
					'name' => 'show_on_sub',
                    'default_value' => 1,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'show_on_sub_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'show_on_sub_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('Actually just apply to categories.')
				),
                array(
					'type' => 'text',
					'label' => $this->l('Height:'),
					'name' => 'height',
                    'default_value' => 500,
                    'required'  => true,
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg'
				),
                'slide_width' => array(
					'type' => 'text',
					'label' => $this->l('Slide width:'),
					'name' => 'width',
                    'default_value' => 900,
                    'required'  => true,
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg'
				),
                array(
                    'type' => 'text',
                    'label' => $this->l('Top spacing:'),
                    'name' => 'top_spacing',
                    'default_value' => '',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Leave it empty to use the default value.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Bottom spacing:'),
                    'name' => 'bottom_spacing',
                    'default_value' => '',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Leave it empty to use the default value.'),
                ),
                array(
					'type' => 'hidden',
					'name' => 'templates',
                    'default_value' => 0,
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Status:'),
					'name' => 'active',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
				),
                array(
					'type' => 'hidden',
					'name' => 'fr',
                    'default_value' => Tools::getValue('fr'),
				),
			),
            'buttons' => array(
                array(
                    'type' => 'submit',
                    'title'=> $this->l(' Save all '),
                    'icon' => 'process-icon-save',
                    'class'=> 'pull-right'
                ),
            ),
			'submit' => array(
				'title' => $this->l('Save and stay'),
                'stay' => true
			),
		);

        $this->fields_form[1]['form'] = array(
			'legend' => array(
				'title' => $this->l('Effect'),
                'icon' => 'icon-cogs'                
			),
			'input' => array( 
            
                array(
					'type' => 'radio',
					'label' => $this->l('Display prev/next buttons:'),
					'name' => 'prev_next',
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'prev_next_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'prev_next_show_on_mouse_hover',
							'value' => 3,
							'label' => $this->l('Display prev/next buttons when mouse hover over')),
						array(
							'id' => 'prev_next_hide_on_mobile',
							'value' => 2,
							'label' => $this->l('Hide on mobile devices')),
						array(
							'id' => 'prev_next_show_hide',
							'value' => 4,
							'label' => $this->l('Display prev/next buttons when mouse hover over, hide on mobile devices')),
						array(
							'id' => 'prev_next_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
				),  
				array(
					'type' => 'color',
					'label' => $this->l('Prev/next buttons color:'),
					'name' => 'prev_next_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				array(
					'type' => 'color',
					'label' => $this->l('Prev/next buttons background color:'),
					'name' => 'prev_next_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                array(
					'type' => 'radio',
					'label' => $this->l('Navigation:'),
					'name' => 'pag_nav',
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'pag_nav_on_circle',
							'value' => 1,
							'label' => $this->l('Round')),
						array(
							'id' => 'pag_nav_on_circle_hide_on_mobile',
							'value' => 2,
							'label' => $this->l('Round(hide on mobile devices)')),
						array(
							'id' => 'pag_nav_on_square',
							'value' => 3,
							'label' => $this->l('Square')),
						array(
							'id' => 'pag_nav_on_square_hide_on_mobile',
							'value' => 4,
							'label' => $this->l('Square(hide on mobile devices)')),
						array(
							'id' => 'pag_nav_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
				),  
				array(
					'type' => 'color',
					'label' => $this->l('Navigation color:'),
					'name' => 'pag_nav_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				array(
					'type' => 'color',
					'label' => $this->l('Navigation active color:'),
					'name' => 'pag_nav_bg_active',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                array(
					'type' => 'switch',
					'label' => $this->l('Desktop click and drag fallback for the desktop slider:'),
					'name' => 'desktopClickDrag',
                    'default_value' => 1,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'desktopClickDrag_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'desktopClickDrag_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Loop:'),
					'name' => 'infiniteSlider',
                    'default_value' => 1,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'infiniteSlider_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'infiniteSlider_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('Makes the slider loop in both directions infinitely with no end.'),
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Hide on mobile devices:'),
					'name' => 'hide_on_mobile',
                    'default_value' => 0,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'hide_on_mobile_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'hide_on_mobile_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('if set to Yes, slideshow will be hidden on mobile devices (if screen width is less than 768 pixels).'),
				), 
                array(
					'type' => 'text',
					'label' => $this->l('Time:'),
					'name' => 'time',
                    'default_value' => 7000,
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
                    'required'  => true,
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Transition period:'),
					'name' => 'trans_period',
                    'default_value' => 400,
                    'desc' => $this->l('The period, in milliseconds, of the transition effect.'),
                    'required'  => true,
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Autoplay:'),
					'name' => 'auto_advance',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'auto_advance_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'auto_advance_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('Automatically play animation.'),
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Pause On Hover:'),
					'name' => 'pause',
                    'default_value' => 1,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'pause_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'pause_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
				),
                array(
					'type' => 'html',
                    'id' => 'a_cancel',
					'label' => '',
					'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
				),
			),
            'buttons' => array(
                array(
                    'type' => 'submit',
                    'title'=> $this->l(' Save all '),
                    'icon' => 'process-icon-save',
                    'class'=> 'pull-right'
                ),
            ),
			'submit' => array(
				'title' => $this->l('Save and stay'),
                'stay' => true
			),
		);
        
        $padding_tb_arr = array();
        for($i=1;$i<=40;$i++)
                $padding_tb_arr[] = array('id'=>$i, 'name'=> $i.'px');
                
        $this->fields_form[2]['form'] = array(
			'legend' => array(
				'title' => $this->l('Background'),
                'icon'  => 'icon-cogs'
			),
			'input' => array( 
                array(
					'type' => 'select',
        			'label' => $this->l('Select a pattern number:'),
        			'name' => 'bg_pattern',
                    'options' => array(
        				'query' => $this->getPatternsArray(),
        				'id' => 'id',
        				'name' => 'name',
    					'default' => array(
    						'value' => 0,
    						'label' => $this->l('None'),
    					),
        			),
                    'desc' => $this->getPatterns(),
                    'validation' => 'isUnsignedInt',
				),
				'bg_img_field' => array(
					'type' => 'file',
					'label' => $this->l('Upload your own pattern or background image:'),
					'name' => 'bg_img',
                    'desc' => '',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Repeat:'),
					'name' => 'bg_repeat',
					'values' => array(
						array(
							'id' => 'bg_repeat_xy',
							'value' => 0,
							'label' => $this->l('Repeat xy')),
						array(
							'id' => 'bg_repeat_x',
							'value' => 1,
							'label' => $this->l('Repeat x')),
						array(
							'id' => 'bg_repeat_y',
							'value' => 2,
							'label' => $this->l('Repeat y')),
						array(
							'id' => 'bg_repeat_no',
							'value' => 3,
							'label' => $this->l('No repeat')),
					),
                    'validation' => 'isUnsignedInt',
				), 
                array(
					'type' => 'radio',
					'label' => $this->l('Position:'),
					'name' => 'bg_position',
					'values' => array(
						array(
							'id' => 'bg_position_left',
							'value' => 0,
							'label' => $this->l('Left')),
						array(
							'id' => 'bg_position_center',
							'value' => 1,
							'label' => $this->l('Center')),
						array(
							'id' => 'bg_position_right',
							'value' => 2,
							'label' => $this->l('Right')),
					),
                    'validation' => 'isUnsignedInt',
				),
				array(
					'type' => 'color',
					'label' => $this->l('Main background color:'),
					'name' => 'bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                array(
					'type' => 'select',
        			'label' => $this->l('Slider padding(padding-top and padding-bottom):'),
        			'name' => 'padding_tb',
                    'options' => array(
        				'query' => $padding_tb_arr,
        				'id' => 'id',
        				'name' => 'name',
    					'default' => array(
    						'value' => 0,
    						'label' => $this->l('0'),
    					),
        			),
                    'validation' => 'isUnsignedInt',
				),
                array(
					'type' => 'html',
                    'id' => 'a_cancel',
					'label' => '',
					'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
				),
			),
            'buttons' => array(
                array(
                    'type' => 'submit',
                    'title'=> $this->l(' Save all '),
                    'icon' => 'process-icon-save',
                    'class'=> 'pull-right'
                ),
            ),
			'submit' => array(
				'title' => $this->l('Save and stay'),
                'stay' => true
			),
		);
        
        $slide_padding_arr = array();
        for($i=0;$i<20;$i++)
                $slide_padding_arr[] = array('id'=>$i, 'name'=> $i.'px');
        for($i=21;$i<=40;$i++)
                $slide_padding_arr[] = array('id'=>$i, 'name'=> $i.'px');
            
        $this->fields_form[3]['form'] = array(
			'legend' => array(
				'title' => $this->l('Slider item'),
                'icon'  => 'icon-cogs'
			),
			'input' => array( 
                'slide_padding_field' => array(
					'type' => 'select',
        			'label' => $this->l('Slider item padding:'),
        			'name' => 'slide_padding',
                    'options' => array(
        				'query' => $slide_padding_arr,
        				'id' => 'id',
        				'name' => 'name',
                        'default' => array('value'=> 20,'label'=> '20px'),
        			),
                    'validation' => 'isUnsignedInt',
				), 
                'slide_shadow_field' => array(
					'type' => 'select',
        			'label' => $this->l('Slider item shadow:'),
        			'name' => 'slide_shadow',
                    'options' => array(
        				'query' => self::$slide_shadow_arr,
        				'id' => 'id',
        				'name' => 'name',
        			),
                    'validation' => 'isUnsignedInt',
				),
                array(
					'type' => 'html',
                    'id' => 'a_cancel',
					'label' => '',
					'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
				), 
			),
            'buttons' => array(
                array(
                    'type' => 'submit',
                    'title'=> $this->l(' Save all '),
                    'icon' => 'process-icon-save',
                    'class'=> 'pull-right'
                ),
            ),
			'submit' => array(
				'title' => $this->l('Save and stay'),
                'stay' => true
			),
		);
        
                
		if (Shop::isFeatureActive())
		{
			$this->fields_form[0]['form']['input'][] = array(
				'type' => 'shop',
				'label' => $this->l('Shop association:'),
				'name' => 'checkBoxShopAsso',
			);
		}
        
        $this->fields_form[0]['form']['input'][] = array(
			'type' => 'html',
            'id' => 'a_cancel',
			'label' => '',
			'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
		);
        
        $id_st_iosslider_group = (int)Tools::getValue('id_st_iosslider_group');
		$slide_group = new StIosSliderGroup($id_st_iosslider_group);
        
        $templates = 0;
        
        if($slide_group->id)
        {
            $templates = $slide_group->templates;
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_iosslider_group');
            
            if ($slide_group->bg_img)
            {
                $img_bg = $this->name.'/'.$slide_group->bg_img;
                StIosSliderClass::fetchMediaServer($img_bg);
                $this->fields_form[2]['form']['input']['bg_img_field']['image'] = '<img width=200 src="'.($img_bg).'" /><p><a class="btn btn-default st_delete_image" href="javascript:;" data-id-group="'.(int)$slide_group->id.'"><i class="icon-trash"></i> Delete</a></p>';
            }
        }
        else
        {
            $templates = isset($_GET['templates']) ? (int)$_GET['templates'] : 0;  
        }
        
        if($templates==1){
            unset($this->fields_form[0]['form']['input']['slide_width'],$this->fields_form[3]['form']['input']['slide_padding_field']);
        }elseif($templates==2){
        }elseif($templates==3){
        }else{
            unset($this->fields_form[0]['form']['input']['slide_width'],$this->fields_form[2],$this->fields_form[3]);
        }
        //reset location
        $this->fields_form[0]['form']['input']['location']['options']['optiongroup']['query'] = $this->getApplyTo($templates);
        
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
        $helper->id = (int)$slide_group->id;
		$helper->table =  'st_iosslider_group';
		$helper->identifier = 'id_st_iosslider_group';
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->submit_action = 'savestiosslidergroup';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($slide_group),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
        
		$helper->title = $this->displayName.' - '.$this->l('Template').' '.$templates; 
        if($slide_group->id)
        {
            $type  = self::getType(get_object_vars($slide_group));
            $field = self::$_type[$type];
            $type && $helper->tpl_vars['fields_value']['location'] = $type.'-'.$slide_group->$field;
        }
        else
            $helper->tpl_vars['fields_value']['templates'] = $templates;  
		
		return $helper;
	}

    public function getPatterns()
    {
        $html = '';
        foreach(range(1,24) as $v)
            $html .= '<div class="parttern_wrap" style="background:url('._MODULE_DIR_.'stthemeeditor/patterns/'.$v.'.png);"><span>'.$v.'</span></div>';
        $html .= '<div>Pattern credits:<a href="http://subtlepatterns.com" target="_blank">subtlepatterns.com</a></div>';
        return $html;
    }
    
    public function getPatternsArray()
    {
        $arr = array();
        for($i=1;$i<=24;$i++)
            $arr[] = array('id'=>$i,'name'=>$i); 
        return $arr;   
    }
    
    public function fontOptions() {
        $google = array();
        foreach($this->googleFonts as $v)
            $google[] = array('id'=>$v['family'],'name'=>$v['family']);
        return $google;
    }
    
    public function fontstyles($font_name = null)
    {
        $style = '';
        if (!$font_name)
            return $style;
        
        $name = $variant = '';
        if (strpos($font_name, ':') !== false)
            list($name, $variant) = explode(':', $font_name);
        else
            $name = $font_name;
        
        $style .= 'font-family:\''.$name.'\';';
        
        if ($variant == 'regular')
            $style .= 'font-weight:400;';
        elseif ($variant)
        {
            if (preg_match('/(\d+)/iS', $variant, $math))
            {
                if (!isset($math[1]))
                    $math[1] = '400';
                $style .= 'font-weight:'.$math[1].';';
            }
            if (preg_match('/([^\d]+)/iS', $variant, $math))
            {
                if (!isset($math[1]))
                    $math[1] = 'normal';
                $style .= 'font-style:'.$math[1].';';
            }
        }
        return $style;
    }
    
	protected function initFormSlide($isbanner=0)
	{
        $id_st_iosslider = (int)Tools::getValue('id_st_iosslider');
        $id_st_iosslider_group = (int)Tools::getValue('id_st_iosslider_group');
		$slide = new StIosSliderClass($id_st_iosslider);

        $google_font_name_html = $google_font_name =  $google_font_link = '';
        if(Validate::isLoadedObject($slide)){
            $jon_arr = StIosSliderFontClass::getBySlider($slide->id);
            if(is_array($jon_arr) && count($jon_arr))
                foreach ($jon_arr as $key => $value) {
                    $google_font_name_html .= '<li id="#'.str_replace(' ', '_', strtolower($value['font_name'])).'_li" class="form-control-static"><button type="button" class="delGoogleFont btn btn-default" name="'.$value['font_name'].'"><i class="icon-remove text-danger"></i></button>&nbsp;<span style="'.$this->fontstyles($value['font_name']).'">style="'.$this->fontstyles($value['font_name']).'"</span></li>';

                    $google_font_name .= $value['font_name'].'¤';

                    $google_font_link .= '<link id="'.str_replace(' ', '_', strtolower($value['font_name'])).'_link" rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='.str_replace(' ', '+', $value['font_name']).'" />';
                }
        }

		$this->fields_form_slide[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Slide item'),
                'icon'  => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'select',
        			'label' => $this->l('Slideshow:'),
        			'name' => 'id_st_iosslider_group',
                    'required'  => true,
                    'options' => array(
        				'query' => StIosSliderGroup::getAll(),
        				'id' => 'id_st_iosslider_group',
        				'name' => 'name',
						'default' => array(
							'value' => 0,
							'label' => $this->l('Please select')
						)
        			)
				),
                /*
                array(
					'type' => 'textarea',
					'label' => $this->l('Embedded video:'),
					'name' => 'video',
					'cols' => 80,
					'rows' => 10,
                    'lang' => true,
                    'desc' => $this->l('Paste here above an iFrame, set the width and the height to 100% to make the video fit the slideshow.'),
                ),
                */
                array(
					'type' => 'text',
					'label' => $this->l('Title(image alt):'),
					'name' => 'title',
                    'size' => 64,
                    'lang' => true,
				),
                array(
					'type' => 'text',
					'label' => $this->l('Url:'),
					'name' => 'url',
                    'size' => 64,
                    'lang' => true,
				),
                'button_text' => array(
					'type' => 'text',
					'label' => $this->l('Button text:'),
					'name' => 'button',
                    'lang' => true,
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Open in a new window:'),
					'name' => 'new_window',
					'is_bool' => true,
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'new_window_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'new_window_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
				), 
				array(
					'type' => 'switch',
					'label' => $this->l('Status:'),
					'name' => 'active',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
				),
                array(
					'type' => 'text',
					'label' => $this->l('Position:'),
					'name' => 'position',
                    'default_value' => 0,
                    'class' => 'fixed-width-sm'                    
				),
                array(
					'type' => 'hidden',
					'name' => 'isbanner',
                    'default_value' => 0,
                    'validation' => 'isBool',                   
				),
			),
            'buttons' => array(
                array(
                    'type' => 'submit',
                    'title'=> $this->l(' Save all '),
                    'icon' => 'process-icon-save',
                    'class'=> 'pull-right'
                ),
            ),
			'submit' => array(
				'title' => $this->l('Save and stay'),
                'stay' => true
			),
		); 
        
		$this->fields_form_slide[1]['form'] = array(
			'legend' => array(
				'title' => $this->l('Add caption'),
                'icon'  => 'icon-cogs'
			),
            'description' => $this->l('Template 3 dose not support text.'),
			'input' => array(
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Caption:'),
                    'lang' => true,
                    'name' => 'description',
                    'cols' => 40,
                    'rows' => 10,
                    'autoload_rte' => true,
                    'desc' => '<p>Format your entry with some basic HTML. Click <span style="color:#ff8230;">Flash</span> button to use predefined templates.</p>
                    <strong>Headings</strong>
                    <p>Headings are defined with the &lt;h1&gt; to &lt;h6&gt; tags.</p>
                    <ul>
                        <li>&lt;h2&gt;Big Heading 1&lt;/h2&gt;</li>
                        <li>&lt;h5&gt;Samll Heading 1&lt;/h5&gt;</li>
                    </ul>
                    <strong>Buttons</strong>
                    <p>You can click the <span style="color:#ff8230;">Flash</span> button in the toolbar of text editor to add buttons.</p>
                    <ul>
                        <li>&lt;a href="#" class="btn btn-small"&gt;Small Button&lt;/a&gt;</li>
                        <li>&lt;a href="#" class="btn btn-default"&gt;Button&lt;/a&gt;</li>
                        <li>&lt;a href="#" class="btn btn-medium"&gt;Medium Button&lt;/a&gt;</li>
                        <li>&lt;a href="#" class="btn btn-large"&gt;Large Button&lt;/a&gt;</li>
                    </ul>
                    <strong>Usefull class names</strong>
                    <ul>
                    <li>width_50 to width_90: &lt;div class="width_70"&gt;Sample&lt;/div&gt;</li>
                    <li>center_width_50 to center_width_90: &lt;div class="center_width_80"&gt;Sample&lt;/div&gt;</li>
                    <li>fs_sm fs_md fs_lg fs_xl fs_xxl fs_xxxl fs_xxxxl: &lt;p class="fs_lg"&gt;Sample&lt;/p&gt;</li>
                    <li>icon_line: &lt;div class="icon_line_wrap"&gt;&lt;div class="icon_line"&gt;Sample&lt;/div&gt;&lt;/div&gt;</li>
                    <li>line, line_white, line_black: &lt;p class="line_white"&gt;Sample&lt;/p&gt;</li>
                    <li>&lt;p class="uppercase"&gt;SAMPLE&lt;/p&gt;</li>
                    <li>color_000,color_333,color_444,color_666,color_999,color_ccc,color_fff: <span style="color:#999">&lt;p class="color_999"&gt;Sample&lt;/p&gt;</span></li>
                    </ul>
                    <div class="alert alert-info"><a href="javascript:;" onclick="$(\'#how_to_use_gf\').toggle();return false;">'.$this->l('How to use google fonts? Click here.').'</a>'.
                        '<div id="how_to_use_gf" style="display:none;"><img src="'.$this->_path.'views/img/how_to_use_gf.jpg" /></div></div>',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Google fonts:'),
                    'name' => 'google_font_select',
                    'onchange' => 'handle_font_change(this);',
                    'class' => 'fontOptions',
                    'options' => array(
                        'query' => $this->fontOptions(),
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default'),
                        ),
                    ),
                ),
                'font_text'=>array(
                    'type' => 'select',
                    'label' => $this->l('Font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'google_font_weight',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                    'desc' => '<p>'.$this->l('Once a font has been added, you can use it everywhere without adding it again.').'</p><a id="add_google_font" class="btn btn-default btn-block fixed-width-md" href="javascript:;">Add</a><br/><p id="google_font_example" class="fontshow">Example Title</p><ul id="curr_google_font_name">'.$google_font_name_html.'</ul>'.$google_font_link,
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'google_font_name',
                    'default_value' => '',
                ),
                array(
					'type' => 'text',
					'label' => $this->l('Caption width:'),
					'name' => 'text_width',
                    'default_value' => 60,     
                    'desc' => 'Value range 0 to 80. Default is 40.',
                    'prefix' => '%',
                    'class' => 'fixed-width-lg'               
				),
                array(
					'type' => 'select',
        			'label' => $this->l('Position:'),
        			'name' => 'text_position',
                    'options' => array(
        				'query' => self::$text_position,
        				'id' => 'id',
        				'name' => 'name',
        			),
				), 
                'text_animation' => array(
					'type' => 'select',
        			'label' => $this->l('Text animation:'),
        			'name' => 'text_animation',
                    'required' => true,
                    'options' => array(
                        'optiongroup' => array (
							'query' => $this->text_animation_group,
							'label' => 'name'
						),
						'options' => array (
							'query' => 'query',
							'id' => 'id',
							'name' => 'name'
						),
						'default' => array(
							'value' => 13,
							'label' => self::$text_animation[13],
						),
        			),
                    'desc' => 'Animate.css - http://daneden.me/animate',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Alignment:'),
					'name' => 'text_align',
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'text_align_left',
							'value' => 1,
							'label' => $this->l('Left')),
						array(
							'id' => 'text_align_center',
							'value' => 2,
							'label' => $this->l('Center')),
						array(
							'id' => 'text_align_right',
							'value' => 3,
							'label' => $this->l('Right')),
					),
				), 
                array(
					'type' => 'color',
        			'label' => $this->l('Caption color:'),
        			'name' => 'title_color',
        			'size' => 33,
				),
                array(
					'type' => 'color',
        			'label' => $this->l('Caption background:'),
        			'name' => 'title_bg',
        			'size' => 33,
				),
                /*array(
					'type' => 'color',
        			'label' => $this->l('Paragraph color:'),
        			'name' => 'description_color',
        			'size' => 33,
				),
                array(
					'type' => 'color',
        			'label' => $this->l('Paragraph background color:'),
        			'name' => 'description_bg',
        			'size' => 33,
				),*/ 
                array(
                    'type' => 'color',
                    'label' => $this->l('Button color:'),
                    'name' => 'btn_color',
                    'size' => 33,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Button background color:'),
                    'name' => 'btn_bg',
                    'size' => 33,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Button hover color:'),
                    'name' => 'btn_hover_color',
                    'size' => 33,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Button hover background color:'),
                    'name' => 'btn_hover_bg',
                    'size' => 33,
                ),
                array(
					'type' => 'color',
        			'label' => $this->l('Container background color:'),
        			'name' => 'text_con_bg',
        			'size' => 33,
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide caption on mobile devices:'),
                    'name' => 'hide_text_on_mobile',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'hide_text_on_mobile_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'hide_text_on_mobile_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'desc' => $this->l('if set to Yes, text will be hidden on mobile devices (if screen width is less than 768 pixels).'),
                ),
                array(
					'type' => 'html',
                    'id' => 'a_cancel',
					'label' => '',
					'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&id_st_iosslider_group='.$slide->id_st_iosslider_group.'&viewstiosslider&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
				),
			),
            'buttons' => array(
                array(
                    'type' => 'submit',
                    'title'=> $this->l(' Save all '),
                    'icon' => 'process-icon-save',
                    'class'=> 'pull-right'
                ),
            ),
			'submit' => array(
				'title' => $this->l('Save and stay'),
                'stay' => true
			),
		);
        
        $languages = Language::getLanguages(true);
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		foreach ($languages as $lang)
        {
            $this->fields_form_slide[0]['form']['input']['image_multi_lang_'.$lang['id_lang']] = array(
                    'type' => 'file',
					'label' => $this->l('Image').' - '.$lang['name'].($default_lang == $lang['id_lang'] ? '('.$this->l('default language').')' : '').':',
					'name' => 'image_multi_lang_'.$lang['id_lang'],
                    'required'  => ($default_lang == $lang['id_lang']),
                );
        }
        $this->fields_form_slide[0]['form']['input'][] = array(
			'type' => 'html',
            'id' => 'a_cancel',
			'label' => '',
			'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&id_st_iosslider_group='.$slide->id_st_iosslider_group.'&viewstiosslider&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
		);
        if(Validate::isLoadedObject($slide))
        {
            $this->fields_form_slide[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_iosslider');
            foreach ($languages as $lang)
                if($slide->image_multi_lang[$lang['id_lang']])
                {
                    StIosSliderClass::fetchMediaServer($slide->thumb_multi_lang[$lang['id_lang']]);
                    $this->fields_form_slide[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'has_image_'.$lang['id_lang'], 'default_value'=>1);
                    $this->fields_form_slide[0]['form']['input']['image_multi_lang_'.$lang['id_lang']]['required'] = false;
                    $this->fields_form_slide[0]['form']['input']['image_multi_lang_'.$lang['id_lang']]['image'] = '<img class="img_prev" src="'.$slide->thumb_multi_lang[$lang['id_lang']].'" />';
                }
        }
        elseif($id_st_iosslider_group)
            $slide->id_st_iosslider_group = $id_st_iosslider_group;
        
        if($isbanner)
            unset($this->fields_form_slide[1],$this->fields_form_slide[0]['form']['input']['button_text']);
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
        $helper->module = $this;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestiosslider';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($slide,"fields_form_slide"),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
        
        $helper->tpl_vars['fields_value']['isbanner'] = ($isbanner || (Validate::isLoadedObject($slide) && $slide->isbanner))? 1 : 0;
        $helper->tpl_vars['fields_value']['google_font_name'] = $google_font_name;
        
		return $helper;
	}
    public static function showApplyTo($value,$row)
    {
        $result = '--';
	    if($value)
		   $result = isset(self::$location[$value]) ? self::$location[$value]['name'] : '';
        elseif($row['id_category'])
        {
            $category = new Category($row['id_category'],(int)Context::getContext()->language->id);
            if($category->id)
            {
                $module = new StIosSlider();
                $result = $category->name.'('.$module->l('Category').')';
            }
        }
        elseif($row['id_cms'])
        {
            $cms = new CMS((int)$row['id_cms'], (int)Context::getContext()->language->id);
            if ($cms->id)
            {
                $module = new StIosSlider();
                $result = $cms->meta_title.'('.$module->l('CMS').')';
            }
        }
        return $result;
    }
	protected function initList()
	{
		$this->fields_list = array(
			'id_st_iosslider_group' => array(
				'title' => $this->l('Id'),
				'width' => 120,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'name' => array(
				'title' => $this->l('Name'),
				'width' => 200,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'location' => array(
				'title' => $this->l('Hook into'),
				'width' => 200,
				'type' => 'text',
				'callback' => 'showApplyTo',
				'callback_object' => 'StIosSlider',
                'search' => false,
                'orderby' => false
			),
            'active' => array(
				'title' => $this->l('Status'),
				'align' => 'center',
				'active' => 'groupstatus',
				'type' => 'bool',
				'width' => 25,
                'search' => false,
                'orderby' => false
            ),
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
        $helper->module = $this;
		$helper->identifier = 'id_st_iosslider_group';
		$helper->actions = array('view', 'edit', 'delete','duplicate');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&selecttemplates&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add a slideshow'),
		);

		$helper->title = $this->displayName;
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    public function displayDuplicateLink($token, $id, $name)
    {
        return '<li class="divider"></li><li><a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&copy'.$this->name.'&id_st_iosslider_group='.(int)$id.'&token='.$token.'"><i class="icon-copy"></i>'.$this->l(' Duplicate ').'</a></li>';
    }
    public static function showSlideGroupName($value,$row)
    {
        $slide_group = new StIosSliderGroup((int)$value);
        return $slide_group->id ? $slide_group->name : '-';
    }
    public static function showSlideImage($value,$row)
    {
        return '<img src="'.$value.'" />';
    }
	protected function initListSlide()
	{
		$this->fields_list = array(
			'id_st_iosslider' => array(
				'title' => $this->l('Id'),
				'width' => 120,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'id_st_iosslider_group' => array(
				'title' => $this->l('Slideshow'),
				'width' => 120,
				'type' => 'text',
				'callback' => 'showSlideGroupName',
				'callback_object' => 'StIosSlider',
                'search' => false,
                'orderby' => false
			),
            'thumb_multi_lang' => array(
				'title' => $this->l('Image'),
				'type' => 'text',
				'callback' => 'showSlideImage',
				'callback_object' => 'StIosSlider',
                'width' => 300,
                'search' => false,
                'orderby' => false
            ),
            'position' => array(
				'title' => $this->l('Position'),
				'width' => 40,
				'position' => 'position',
				'align' => 'left',
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
				'title' => $this->l('Status'),
				'align' => 'center',
				'active' => 'slidestatus',
				'type' => 'bool',
				'width' => 25,
                'search' => false,
                'orderby' => false
            ),
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_st_iosslider';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstiosslider&id_st_iosslider_group='.(int)Tools::getValue('id_st_iosslider_group').'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add a slider')
		);
        $helper->toolbar_btn['edit'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&update'.$this->name.'&id_st_iosslider_group='.(int)Tools::getValue('id_st_iosslider_group').'&fr=view&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Edit slideshow'),
		);
		$helper->toolbar_btn['back'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Back to list')
		);

		$helper->title = $this->l('Slides');
		$helper->table = $this->name;
		$helper->orderBy = 'position';
		$helper->orderWay = 'ASC';
	    $helper->position_identifier = 'id_st_iosslider';
        
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
	protected function initListBanner()
	{
		$this->fields_list_banner = array(
			'id_st_iosslider' => array(
				'title' => $this->l('Id'),
				'width' => 120,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'id_st_iosslider_group' => array(
				'title' => $this->l('Slideshow'),
				'width' => 120,
				'type' => 'text',
				'callback' => 'showSlideGroupName',
				'callback_object' => 'StIosSlider',
                'search' => false,
                'orderby' => false
			),
            'thumb_multi_lang' => array(
				'title' => $this->l('Image'),
				'type' => 'text',
				'callback' => 'showSlideImage',
				'callback_object' => 'StIosSlider',
                'width' => 300,
                'search' => false,
                'orderby' => false
            ),
            'position' => array(
				'title' => $this->l('Position'),
				'width' => 40,
				'position' => 'position',
				'align' => 'center',
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
				'title' => $this->l('Status'),
				'align' => 'center',
				'active' => 'slidestatus',
				'type' => 'bool',
				'width' => 25,
                'search' => false,
                'orderby' => false
            ),
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_st_iosslider';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstiossliderbanner&id_st_iosslider_group='.(int)Tools::getValue('id_st_iosslider_group').'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add a banner')
		);
		$helper->toolbar_btn['back'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Back to list')
		);

		$helper->title = $this->l('Banners');
		$helper->table = $this->name;
		$helper->orderBy = 'position';
		$helper->orderWay = 'ASC';
	    $helper->position_identifier = 'id_st_iosslider';
        
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    private function _prepareHook($identify,$type=1)
    {
        $googleFontLinks = '';
        $fonts      = $this->systemFonts;
	    $slide_font = array();
        
        $slide_group = StIosSliderGroup::getSlideGroup($identify,$type);
        if(!is_array($slide_group) || !count($slide_group))
            return false;
        foreach($slide_group as &$v)
        {
             $slide = StIosSliderClass::getAll($v['id_st_iosslider_group'],$this->context->language->id,1,0);
             if(is_array($slide) && $slide_nbr=count($slide))
             {
                foreach($slide as $m=>&$n)
                {
                    $slide_font[] = $n['title_font_family'];
                    $n['text_animation_name'] = self::$text_animation[$n['text_animation']];
                    /*
                    if($slide_nbr>1)
                    {
                        $n['next_thumb'] = isset($slide[$m+1]) 
                            ? ((isset($slide[$m+1]['thumb_multi_lang']) && $slide[$m+1]['thumb_multi_lang']) ? $slide[$m+1]['thumb_multi_lang'] : $slide[$m+1]['thumb']) 
                            : ((isset($slide[0]['thumb_multi_lang']) && $slide[0]['thumb_multi_lang']) ? $slide[0]['thumb_multi_lang'] : $slide[0]['thumb']);
                        $n['prev_thumb'] = isset($slide[$m-1]) 
                            ? ((isset($slide[$m-1]['thumb_multi_lang']) && $slide[$m-1]['thumb_multi_lang']) ? $slide[$m-1]['thumb_multi_lang'] : $slide[$m-1]['thumb']) 
                            : ((isset($slide[$slide_nbr-1]['thumb_multi_lang']) && $slide[$slide_nbr-1]['thumb_multi_lang']) ? $slide[$slide_nbr-1]['thumb_multi_lang'] : $slide[$slide_nbr-1]['thumb']);
                    }
                    */
                }
                $v['slide'] = $slide;
             }
             /*
             if($v['location']==15 || $v['location']==16 || $v['location']==18 || $v['location']==19)
             {
                $banners = StIosSliderClass::getAll($v['id_st_iosslider_group'],$this->context->language->id,1,1);
                if(is_array($banners) && count($banners))
                    $v['banners'] = $banners;
             }
             */
        }
        $slide_font = array_unique($slide_font);
        $slide_font = array_diff($slide_font,$fonts);    
        $font_latin_support = Configuration::get('STSN_FONT_LATIN_SUPPORT');
        $font_cyrillic_support = Configuration::get('STSN_FONT_CYRILLIC_SUPPORT');
        $font_vietnamese = Configuration::get('STSN_FONT_VIETNAMESE');
        $font_greek_support = Configuration::get('STSN_FONT_GREEK_SUPPORT');
        $font_arabic_support = Configuration::get('STSN_FONT_ARABIC_SUPPORT');
        $font_support = ($font_latin_support || $font_cyrillic_support || $font_vietnamese || $font_greek_support || $font_arabic_support) ? '&subset=' : '';
        $font_latin_support && $font_support .= 'latin,latin-ext,';
        $font_cyrillic_support && $font_support .= 'cyrillic,cyrillic-ext,';
        $font_vietnamese && $font_support .= 'vietnamese,';
        $font_greek_support && $font_support .= 'greek,greek-ext,';
        $font_arabic_support && $font_support .= 'arabic,';
        if(is_array($slide_font) && count($slide_font))
            foreach($slide_font as $x)
            {
                if(!$x)
                    continue;
                $this->context->controller->addCSS($this->context->link->protocol_content."fonts.googleapis.com/css?family=".str_replace(' ', '+', $x).($font_support ? rtrim($font_support,',') : ''));
            }
	    $this->smarty->assign(array(
            'slide_group' => $slide_group,
            'google_font_links'  => $googleFontLinks,
            'image_path'  => _MODULE_DIR_.$this->name.'/views/images/',
        ));
        return true;
    }
    public function hookDisplayHeader($params)
    {
		$this->context->controller->addJS(($this->_path).'views/js/jquery.iosslider.min.js');
		$this->context->controller->addCSS(($this->_path).'views/css/iosslider.css');
        if(!file_exists($this->local_path.'views/css/custom.css'))
            $this->writeCss();
        if(file_exists($this->local_path.'views/css/custom.css'))
            $this->context->controller->addCSS(($this->_path).'views/css/custom.css');

        /*$data = StIosSliderFontClass::getAll(1);
        if(is_array($data) && count($data))
        {
            $slide_font = array();
            foreach ($data as $value) {
                $slide_font[] = $value['font_name'];
            }

            $slide_font = array_unique($slide_font); 
            $font_latin_support = Configuration::get('STSN_FONT_LATIN_SUPPORT');
            $font_cyrillic_support = Configuration::get('STSN_FONT_CYRILLIC_SUPPORT');
            $font_vietnamese = Configuration::get('STSN_FONT_VIETNAMESE');
            $font_greek_support = Configuration::get('STSN_FONT_GREEK_SUPPORT');
            $font_arabic_support = Configuration::get('STSN_FONT_ARABIC_SUPPORT');
            $font_support = ($font_latin_support || $font_cyrillic_support || $font_vietnamese || $font_greek_support || $font_arabic_support) ? '&subset=' : '';
            $font_latin_support && $font_support .= 'latin,latin-ext,';
            $font_cyrillic_support && $font_support .= 'cyrillic,cyrillic-ext,';
            $font_vietnamese && $font_support .= 'vietnamese,';
            $font_greek_support && $font_support .= 'greek,greek-ext,';
            $font_arabic_support && $font_support .= 'arabic,';
            if(is_array($slide_font) && count($slide_font))
                foreach($slide_font as $x)
                {
                    if(!$x)
                        continue;
                    $this->context->controller->addCSS($this->context->link->protocol_content."fonts.googleapis.com/css?family=".str_replace(' ', '+', $x).($font_support ? rtrim($font_support,',') : ''));
                }
        }*/ 
    }
	public function hookDisplayHomeTop($params)
	{
		if (!$this->isCached('stiosslider.tpl', $this->stGetCacheId(4)))
            if(!$this->_prepareHook(4,1))
                return false;
		return $this->display(__FILE__, 'stiosslider.tpl', $this->stGetCacheId(4));
	}
    
	public function hookDisplayHome($params)
	{
		if (!$this->isCached('stiosslider.tpl', $this->stGetCacheId(3)))
            if(!$this->_prepareHook(3,1))
                return false;
		return $this->display(__FILE__, 'stiosslider.tpl', $this->stGetCacheId(3));
	}
    
    public function hookDisplayHomeBottom($params)
    {
        if (!$this->isCached('stiosslider.tpl', $this->stGetCacheId(17)))
            if(!$this->_prepareHook(17,1))
                return false;
        return $this->display(__FILE__, 'stiosslider.tpl', $this->stGetCacheId(17));
    }


    public function hookDisplayTopColumn($params)
    {
        if (!$this->isCached('stiosslider.tpl', $this->stGetCacheId(21)))
            if(!$this->_prepareHook(21,1))
                return false;
        return $this->display(__FILE__, 'stiosslider.tpl', $this->stGetCacheId(21));
    }

    public function hookDisplayBottomColumn($params)
    {
        if (!$this->isCached('stiosslider.tpl', $this->stGetCacheId(20)))
            if(!$this->_prepareHook(20,1))
                return false;
        return $this->display(__FILE__, 'stiosslider.tpl', $this->stGetCacheId(20));
    }

    
	public function hookDisplayHomeVeryBottom($params)
	{
		if (!$this->isCached('stiosslider.tpl', $this->stGetCacheId(18)))
            if(!$this->_prepareHook(18,1))
                return false;
		return $this->display(__FILE__, 'stiosslider.tpl', $this->stGetCacheId(18));
	}
    
	public function hookDisplayStBlogHome($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stiosslider.tpl', $this->stGetCacheId(6)))
            if(!$this->_prepareHook(6,1))
                return false;
		return $this->display(__FILE__, 'stiosslider.tpl', $this->stGetCacheId(6));
	}
    
    public function displayMainSlide()
	{
	   
	    if(Dispatcher::getInstance()->getController()!='index')
            return false;
		//if (!$this->isCached('stiosslider-topextra.tpl', $this->stGetCacheId(1)))
            if(!$this->_prepareHook(array(1,14,15,16),1))
                return false;
        
		return $this->display(__FILE__, 'stiosslider.tpl');
	}
    public function hookDisplayFullWidthTop($params)
    {
        $page_name = Context::getContext()->smarty->getTemplateVars('page_name');
        if($page_name=='index')
        {
            if(!$this->_prepareHook(array(1,14,15,16),1))
                return false;
        
            return $this->display(__FILE__, 'stiosslider.tpl');
        }
        elseif($page_name=='module-stblog-default')
        {
            if(!$this->_prepareHook(array(7,8),1))
                return false;
            return $this->display(__FILE__, 'stiosslider.tpl');
        }
    }
    public function hookDisplayFullWidthTop2($params)
    {
        $page_name = Context::getContext()->smarty->getTemplateVars('page_name');
        if($page_name=='index')
        {
            if(!$this->_prepareHook(array(22,23),1))
                return false;
        
            return $this->display(__FILE__, 'stiosslider.tpl');
        }
    }
    
    public function displayBlogMainSlide()
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		//if (!$this->isCached('stiosslider-topextra.tpl', $this->stGetCacheId(1)))
            if(!$this->_prepareHook(array(7,8),1))
                return false;
		return $this->display(__FILE__, 'stiosslider.tpl');
	}
	
    public function hookDisplayCategoryHeader($params)
    {
        $id_category = (int)Tools::getValue('id_category');
        if(!$id_category)
            return false;
		if (!$this->isCached('stiosslider.tpl', $this->stGetCacheId($id_category,'category-header','stiosslider')))
            if(!$this->_prepareHook($id_category,2))
                return false;
		return $this->display(__FILE__, 'stiosslider.tpl', $this->stGetCacheId($id_category,'category-header','stiosslider'));
    }
    public function hookDisplayCategoryFooter($params)
    {
        if (!$this->isCached('stiosslider.tpl', $this->stGetCacheId(12)))
            if(!$this->_prepareHook(12,1))
                return false;
		return $this->display(__FILE__, 'stiosslider.tpl', $this->stGetCacheId(12));
    }
    
    public function hookDisplayFooterProduct($params)
    {
        if (!$this->isCached('stiosslider.tpl', $this->stGetCacheId(11)))
            if(!$this->_prepareHook(11,1))
                return false;
		return $this->display(__FILE__, 'stiosslider.tpl', $this->stGetCacheId(11));
    }
    
	public function hookDisplayAnywhere($params)
	{
	    if(!isset($params['caller']) || $params['caller']!=$this->name)
            return false;
	   if(isset($params['function']) && method_exists($this,$params['function']))
        {
            if($params['function']=='displayMainSlide')
                return call_user_func(array($this,$params['function']));
            elseif($params['function']=='displayBySlideId')
                return call_user_func_array(array($this,$params['function']),array($params['identify']));
            elseif($params['function']=='displayBlogMainSlide')
                return call_user_func(array($this,$params['function']));
            elseif($params['function']=='displayCmsMainSlide')
                return call_user_func(array($this,$params['function']),array($params['identify']));
            elseif($params['function']=='displayCmsCategoryMainSlide')
                return call_user_func(array($this,$params['function']),array($params['identify']));
            else
                return false;
        }
        return false;
    }
    public function displayCmsMainSlide($identify)
    {
        if(!$identify || !$this->_prepareHook($identify, 4))
                return false;
		return $this->display(__FILE__, 'stiosslider.tpl');
    }
    public function displayCmsCategoryMainSlide($identify)
    {
        if(!$identify || !$this->_prepareHook($identify, 5))
                return false;
		return $this->display(__FILE__, 'stiosslider.tpl');
    }
    public function displayBySlideId($identify)
    {
        if(!Validate::isUnsignedInt($identify))
            return false;
            
        $slide_group_obj = new StIosSliderGroup($identify);
        if(!$slide_group_obj->id || !$slide_group_obj->active)
            return false;
		if (!$this->isCached('stiosslider.tpl', $this->stGetCacheId($slide_group_obj->id,'id')))
            if(!$this->_prepareHook($identify,3))
                return false;
		return $this->display(__FILE__, 'stiosslider.tpl', $this->stGetCacheId($slide_group_obj->id,'id'));
    }
    public function hookActionObjectCategoryDeleteAfter($params)
    {
        if(!$params['object']->id)
            return ;
        
        $slide_group = StIosSliderGroup::getSlideGroup($params['object']->id,2);
        if(!is_array($slide_group) || !count($slide_group))
            return ;
        $res = true;
        foreach($slide_group as $v)
        {
            $slide_group = new StIosSliderGroup($v['id_st_iosslider_group']);
            $res &= $slide_group->delete();
        }
        
        return $res;
    }
	public function hookActionShopDataDuplication($params)
	{
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'st_iosslider_group_shop (id_st_iosslider_group, id_shop)
		SELECT id_st_iosslider_group, '.(int)$params['new_id_shop'].'
		FROM '._DB_PREFIX_.'st_iosslider_group_shop
		WHERE id_shop = '.(int)$params['old_id_shop']);
        $this->clearIosSliderCache();
    }
	protected function stGetCacheId($key,$type='location',$name = null)
	{
		$cache_id = parent::getCacheId($name);
		return $cache_id.'_'.$key.'_'.$type;
	}
	private function clearIosSliderCache()
	{
	    $this->writeCss();
		$this->_clearCache('*');
	}
    public static function hex2rgb($hex) {
       $hex = str_replace("#", "", $hex);
    
       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       $rgb = array($r, $g, $b);
       return implode(",", $rgb); // returns the rgb values separated by commas
       //return $rgb;
    }
	/**
	 * Return the list of fields value
	 *
	 * @param object $obj Object
	 * @return array
	 */
	public function getFieldsValueSt($obj,$fields_form="fields_form")
	{
		foreach ($this->$fields_form as $fieldset)
			if (isset($fieldset['form']['input']))
				foreach ($fieldset['form']['input'] as $input)
					if (!isset($this->fields_value[$input['name']]))
						if (isset($input['type']) && $input['type'] == 'shop')
						{
							if ($obj->id)
							{
								$result = Shop::getShopById((int)$obj->id, $this->identifier, $this->table);
								foreach ($result as $row)
									$this->fields_value['shop'][$row['id_'.$input['type']]][] = $row['id_shop'];
							}
						}
						elseif (isset($input['lang']) && $input['lang'])
							foreach (Language::getLanguages(false) as $language)
							{
								$fieldValue = $this->getFieldValueSt($obj, $input['name'], $language['id_lang']);
								if (empty($fieldValue))
								{
									if (isset($input['default_value']) && is_array($input['default_value']) && isset($input['default_value'][$language['id_lang']]))
										$fieldValue = $input['default_value'][$language['id_lang']];
									elseif (isset($input['default_value']))
										$fieldValue = $input['default_value'];
								}
								$this->fields_value[$input['name']][$language['id_lang']] = $fieldValue;
							}
						else
						{
							$fieldValue = $this->getFieldValueSt($obj, $input['name']);
							if ($fieldValue===false && isset($input['default_value']))
								$fieldValue = $input['default_value'];
							$this->fields_value[$input['name']] = $fieldValue;
						}

		return $this->fields_value;
	}
    
	/**
	 * Return field value if possible (both classical and multilingual fields)
	 *
	 * Case 1 : Return value if present in $_POST / $_GET
	 * Case 2 : Return object value
	 *
	 * @param object $obj Object
	 * @param string $key Field name
	 * @param integer $id_lang Language id (optional)
	 * @return string
	 */
	public function getFieldValueSt($obj, $key, $id_lang = null)
	{
		if ($id_lang)
			$default_value = ($obj->id && isset($obj->{$key}[$id_lang])) ? $obj->{$key}[$id_lang] : false;
		else
			$default_value = isset($obj->{$key}) ? $obj->{$key} : false;

		return Tools::getValue($key.($id_lang ? '_'.$id_lang : ''), $default_value);
	}
    
    public function processCopyIosSlider($id_st_iosslider_group = 0)
    {
        if (!$id_st_iosslider_group)
            return false;
            
        $group = new StIosSliderGroup($id_st_iosslider_group);
        
        $group2 = clone $group;
        $group2->id = 0;
        $group2->id_st_iosslider_group = 0;
        $ret = $group2->add();
        
        if (!Shop::isFeatureActive())
        {
            Db::getInstance()->insert('st_iosslider_group_shop', array(
                'id_st_iosslider_group' => (int)$group2->id,
                'id_shop' => (int)Context::getContext()->shop->id,
            ));
        }
        else
        {
            $assos_shop = Tools::getValue('checkBoxShopAsso_st_advanced_banner_group');
            if (empty($assos_shop))
                $assos_shop[(int)Context::getContext()->shop->id] = Context::getContext()->shop->id;
            foreach ($assos_shop as $id_shop => $row)
                Db::getInstance()->insert('st_iosslider_group_shop', array(
                    'id_st_iosslider_group' => (int)$group2->id,
                    'id_shop' => (int)$id_shop,
                ));
        }
        
        foreach(Db::getInstance()->executeS('SELECT id_st_iosslider FROM '._DB_PREFIX_.'st_iosslider WHERE id_st_iosslider_group='.(int)$group->id) AS $row)
        {
            $slider = new StIosSliderClass($row['id_st_iosslider']);
            $slider->id = 0;
            $slider->id_st_iosslider = 0;
            $slider->id_st_iosslider_group = (int)$group2->id;
            $ret &= $slider->add();
        }
        return $ret;
    }
            
    public function processUpdatePositions()
	{
		if (Tools::getValue('action') == 'updatePositions' && Tools::getValue('ajax'))
		{
			$way = (int)(Tools::getValue('way'));
			$id = (int)(Tools::getValue('id'));
			$positions = Tools::getValue('st_iosslider');
            $msg = '';
			if (is_array($positions))
				foreach ($positions as $position => $value)
				{
					$pos = explode('_', $value);

					if ((isset($pos[2])) && ((int)$pos[2] === $id))
					{
						if ($object = new StIosSliderClass((int)$pos[2]))
							if (isset($position) && $object->updatePosition($way, $position))
								$msg = 'ok position '.(int)$position.' for ID '.(int)$pos[2]."\r\n";	
							else
								$msg = '{"hasError" : true, "errors" : "Can not update position"}';
						else
							$msg = '{"hasError" : true, "errors" : "This object ('.(int)$id.') can t be loaded"}';

						break;
					}
				}
                die($msg);
		}
	}
}
