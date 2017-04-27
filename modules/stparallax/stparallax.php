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
    
include_once dirname(__FILE__).'/StParallaxClass.php';
include_once dirname(__FILE__).'/StParallaxGroup.php';
include_once dirname(__FILE__).'/StParallaxFontClass.php';

class StParallax extends Module
{
    public $imgtype = array('jpg', 'gif', 'jpeg', 'png');
    protected static $access_rights = 0775;
    public static $location = array(

        36 => array('id' =>36 , 'name' => 'Full width top', 'full_width' => 1),
        38 => array('id' =>38 , 'name' => 'Full width top 2', 'full_width' => 1),
        37 => array('id' =>37 , 'name' => 'Full width bottom(Home very bottom)', 'full_width' => 1),
        35 => array('id' =>35 , 'name' => 'Top column'),
        28 => array('id' =>28 , 'name' => 'Bottom column'),

        4 => array('id' =>4 , 'name' => 'Homepage top'),
        3 => array('id' =>3 , 'name' => 'Homepage'),
        17 => array('id' =>17 , 'name' => 'Homepage bottom'),

        /*7 => array('id' =>7 , 'name' => 'Blog homepage top(fullwidth)'),
        8 => array('id' =>8 , 'name' => 'Blog homepage top'),*/
        6 => array('id' =>6 , 'name' => 'Blog homepage'),
    );

    public  $fields_list;
    public  $fields_list_slide;
    public  $fields_list_banner;
    public  $fields_value;
    public  $fields_form;
    public  $fields_form_slide;
	private $_html = '';
	private $spacer_size = '5';
	
    private $googleFonts;
	
    public function __construct()
	{
		$this->name          = 'stparallax';
		$this->tab           = 'front_office_features';
		$this->version       = '1.1.7';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
        $this->bootstrap     = true;

		parent::__construct();
        
        $this->googleFonts = include_once(dirname(__FILE__).'/googlefonts.php');

		$this->displayName   = $this->l('Parallax block');
		$this->description   = $this->l('This module allows you to create a Parallax effect.');
	}

	public function install()
	{
	    $res = $this->installDB() &&
            parent::install() &&
			$this->registerHook('displayHeader') &&
            $this->registerHook('displayHome') &&
			$this->registerHook('displayHomeTop') &&
			$this->registerHook('displayHomeBottom') &&
			$this->registerHook('displayAnywhere') &&
            $this->registerHook('actionShopDataDuplication') &&
			$this->registerHook('displayStBlogHome') &&
			$this->registerHook('displayStBlogLeftColumn') && 
            $this->registerHook('displayStBlogRightColumn') && 
            $this->registerHook('displayTopColumn') && 
            $this->registerHook('displayHomeVeryBottom') && 
            $this->registerHook('displayFullWidthTop') && 
            $this->registerHook('displayFullWidthTop2') && 
            $this->registerHook('displayBottomColumn');
                    
		$this->clearParallaxCache();
		return $res;
	}

	public function installDb()
	{
		/* Slides */
		$return = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_parallax` (
				`id_st_parallax` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_st_parallax_group` int(10) unsigned NOT NULL,
                `text_align` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `text_color` varchar(7) DEFAULT NULL,
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `position` int(10) unsigned NOT NULL DEFAULT 0,
                `btn_color` varchar(7) DEFAULT NULL,
                `btn_bg` varchar(7) DEFAULT NULL,
                `btn_hover_color` varchar(7) DEFAULT NULL,
                `btn_hover_bg` varchar(7) DEFAULT NULL,
                `width` int(10) unsigned NOT NULL DEFAULT 0,
				PRIMARY KEY (`id_st_parallax`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		/* Slides lang configuration */
		$return && $return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_parallax_lang` (
				`id_st_parallax` int(10) UNSIGNED NOT NULL,
				`id_lang` int(10) unsigned NOT NULL ,
                `description` text,
				PRIMARY KEY (`id_st_parallax`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
            
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_parallax_font` (
                `id_st_parallax` int(10) unsigned NOT NULL,
                `font_name` varchar(255) NOT NULL
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

        /* Slides group */
        $return && $return &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_parallax_group` (
                `id_st_parallax_group` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, 
                `name` varchar(255) DEFAULT NULL,
                `location` int(10) unsigned NOT NULL DEFAULT 0,
                `prev_next` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `pag_nav` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `hide_on_mobile` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `time` int(10) unsigned NOT NULL DEFAULT 7000,
                `trans_period` int(10) unsigned NOT NULL DEFAULT 1000,
                `auto_advance` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `pause` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `autoHeight` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `position` int(10) unsigned NOT NULL DEFAULT 0, 
                `desktopClickDrag` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `text_color` varchar(7) DEFAULT NULL,
                `bg_color` varchar(7) DEFAULT NULL,
                `bg_pattern` tinyint(2) unsigned NOT NULL DEFAULT 0, 
                `bg_img` varchar(255) DEFAULT NULL,
                `padding_top` int(10) unsigned NOT NULL DEFAULT 0, 
                `padding_bottom` int(10) unsigned NOT NULL DEFAULT 0, 
                `prev_next_color` varchar(7) DEFAULT NULL,
                `prev_next_hover` varchar(7) DEFAULT NULL,
                `prev_next_bg` varchar(7) DEFAULT NULL,
                `pag_nav_bg` varchar(7) DEFAULT NULL,
                `pag_nav_bg_active` varchar(7) DEFAULT NULL,
                `title_color` varchar(7) DEFAULT NULL,
                `speed` float(4,1) unsigned NOT NULL DEFAULT 0.1,
                `top_spacing` varchar(10) DEFAULT NULL,
                `bottom_spacing` varchar(10) DEFAULT NULL,
                PRIMARY KEY (`id_st_parallax_group`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
        
        /* Slides group lang configuration */
        $return && $return &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_parallax_group_lang` (
                `id_st_parallax_group` int(10) UNSIGNED NOT NULL,
                `id_lang` int(10) unsigned NOT NULL ,
                `title` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id_st_parallax_group`, `id_lang`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
        
		/* Slides group shop */
		$return && $return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_parallax_group_shop` (
				`id_st_parallax_group` int(10) UNSIGNED NOT NULL,
                `id_shop` int(11) NOT NULL,      
                PRIMARY KEY (`id_st_parallax_group`,`id_shop`),    
                KEY `id_shop` (`id_shop`)   
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		return $return;
	}

	public function uninstall()
	{
	    $this->clearParallaxCache();
		// Delete configuration
		return $this->uninstallDb() &&
			parent::uninstall();
	}

	private function uninstallDb()
	{
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_parallax`,`'._DB_PREFIX_.'st_parallax_lang`,`'._DB_PREFIX_.'st_parallax_font`,`'._DB_PREFIX_.'st_parallax_group`,`'._DB_PREFIX_.'st_parallax_group_lang`,`'._DB_PREFIX_.'st_parallax_group_shop`');
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
           
        return $result;
    }
    
	public function getContent()
	{
        $check_result = $this->_checkImageDir();
		$this->context->controller->addCSS(($this->_path).'views/css/admin.css');
		$this->context->controller->addJS($this->_path.'views/js/admin.js');
        $this->_html .= '<script type="text/javascript">var stparallax_base_uri = "'.__PS_BASE_URI__.'";var googleFontsString=\''.Tools::jsonEncode($this->googleFonts).'\';</script>';
        
        $id_st_parallax_group = (int)Tools::getValue('id_st_parallax_group');
        $id_st_parallax = (int)Tools::getValue('id_st_parallax');
	    if ((Tools::isSubmit('groupstatusstparallax')))
        {
            $slide_group = new StParallaxGroup((int)$id_st_parallax_group);
            if($slide_group->id && $slide_group->toggleStatus())
            {
                $this->clearParallaxCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
	    if ((Tools::isSubmit('slidestatusstparallax')))
        {
            $slide = new StParallaxClass((int)$id_st_parallax);
            if($slide->id && $slide->toggleStatus())
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));  
                $this->clearParallaxCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_parallax_group='.$slide->id_st_parallax_group.'&viewstparallax&token='.Tools::getAdminTokenLite('AdminModules'));
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
            $slide_group = new StParallaxGroup((int)$identi);
            if(Validate::isLoadedObject($slide_group))
            {
                $slide_group->bg_img = '';
                if($slide_group->save())
                {
                    $result['r'] = true;
                }
            }
            die(json_encode($result));
        }
        if ((Tools::isSubmit('groupdeleteimagestparallax')))
        {
            $slide_group = new StParallaxGroup($id_st_parallax_group);
            if($slide_group->id)
            {
                @unlink(_PS_ROOT_DIR_._THEME_PROD_PIC_DIR_.$this->name.'/'.$slide_group->bg_img);
                $slide_group->bg_img = '';
                if ($slide_group->save())
                {
                    //$this->_html .= $this->displayConfirmation($this->l('The image was deleted successfully.'));  
                    $this->clearParallaxCache();
                    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=7&updatestparallax&id_st_parallax_group='.(int)$slide_group->id.'&token='.Tools::getAdminTokenLite('AdminModules'));   
                }else
                    $this->_html .= $this->displayError($this->l('An error occurred while delete image.'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while delete image.'));
        }
        if (Tools::isSubmit('way') && Tools::isSubmit('id_st_parallax') && (Tools::isSubmit('position')))
		{
		    $slide = new StParallaxClass((int)$id_st_parallax);
            if($slide->id && $slide->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position')))
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));  
                $this->clearParallaxCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_parallax_group='.$slide->id_st_parallax_group.'&viewstparallax&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            else
                $this->_html .= $this->displayError($this->l('Failed to update the position.'));
		}
        if (Tools::getValue('action') == 'updatePositions')
        {
            $this->processUpdatePositions();
        }
		if (isset($_POST['savestparallaxgroup']) || isset($_POST['savestparallaxgroupAndStay']))
		{
            if ($id_st_parallax_group)
				$slide_group = new StParallaxGroup((int)$id_st_parallax_group);
			else
				$slide_group = new StParallaxGroup();
            
            $error = array();
    		$slide_group->copyFromPost();
            
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
                        $this->_checkEnv();
    					if (!move_uploaded_file($_FILES['bg_img']['tmp_name'], _PS_UPLOAD_DIR_.$this->name.'/'.$bg_image))
    						$error[] = Tools::displayError('Error move uploaded file');
                        else
    					   $slide_group->bg_img = $this->name.'/'.$bg_image;
    				}
    			}
            }

			if (!count($error) && $slide_group->validateFields(false) && $slide_group->validateFieldsLang(false))
            {
                if($slide_group->save())
                {
		            Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'st_parallax_group_shop WHERE id_st_parallax_group='.(int)$slide_group->id);
                    if (!Shop::isFeatureActive())
            		{
            			Db::getInstance()->insert('st_parallax_group_shop', array(
            				'id_st_parallax_group' => (int)$slide_group->id,
            				'id_shop' => (int)Context::getContext()->shop->id,
            			));
            		}
            		else
            		{
            			$assos_shop = Tools::getValue('checkBoxShopAsso_st_parallax_group');
            			if (empty($assos_shop))
            				$assos_shop[(int)Context::getContext()->shop->id] = Context::getContext()->shop->id;
            			foreach ($assos_shop as $id_shop => $row)
            				Db::getInstance()->insert('st_parallax_group_shop', array(
            					'id_st_parallax_group' => (int)$slide_group->id,
            					'id_shop' => (int)$id_shop,
            				));
            		}
                    $this->clearParallaxCache();
                    if(isset($_POST['savestparallaxgroupAndStay']) || Tools::getValue('fr') == 'view')
                    {
                        $rd_str = isset($_POST['savestparallaxgroupAndStay']) && Tools::getValue('fr') == 'view' ? 'fr=view&update' : (isset($_POST['savestparallaxgroupAndStay']) ? 'update' : 'view');
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_parallax_group='.$slide_group->id.'&conf='.($id_st_parallax_group?4:3).'&'.$rd_str.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules')); 
                    }    
                    else
                        $this->_html .= $this->displayConfirmation($this->l('Parallax').' '.($id_st_parallax_group ? $this->l('updated') : $this->l('added')));
                }                    
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during parallax').' '.($id_st_parallax_group ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }
		if (isset($_POST['savestparallax']) || isset($_POST['savestparallaxAndStay']))
		{
            if ($id_st_parallax)
				$slide = new StParallaxClass((int)$id_st_parallax);
			else
				$slide = new StParallaxClass();
            /**/
            
            $error = array();
            
            $languages = Language::getLanguages(false);
            $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
         
		    $slide->copyFromPost();
            if(!$slide->id_st_parallax_group)
                $error[] = $this->displayError($this->l('The field "Slideshow" is required'));
                
			if (!count($error) && $slide->validateFields(false) && $slide->validateFieldsLang(false))
            {
                /*position*/
                $slide->position = $slide->checkPosition();
                
                if($slide->save())
                {
                    $jon = trim(Tools::getValue('google_font_name'),'¤');
                    StParallaxFontClass::deleteBySlider($slide->id);
                    $jon_arr = array_unique(explode('¤', $jon));
                    if (count($jon_arr))
                        StParallaxFontClass::changeSliderFont($slide->id, $jon_arr);
                    
                    $this->clearParallaxCache();
                    //$this->_html .= $this->displayConfirmation($this->l('Slide').' '.($id_st_parallax ? $this->l('updated') : $this->l('added')));
                    if(isset($_POST['savestparallaxAndStay']))
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_parallax='.$slide->id.'&conf='.($id_st_parallax?4:3).'&update'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));            
                    else
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_parallax_group='.$slide->id_st_parallax_group.'&viewstparallax&token='.Tools::getAdminTokenLite('AdminModules'));
                     
                }
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during slide').' '.($id_st_parallax ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }
        
        if(Tools::isSubmit('addstparallaxgroup') || (Tools::isSubmit('updatestparallax') && $id_st_parallax_group))
		{
            $helper = $this->initForm();
            return $this->_html.$helper->generateForm($this->fields_form);
		}
        elseif(Tools::isSubmit('addstparallax') || (Tools::isSubmit('updatestparallax') && $id_st_parallax))
        {
            $helper = $this->initFormSlide(0);
            return $this->_html.$helper->generateForm($this->fields_form_slide);
        }
        elseif(Tools::isSubmit('addstparallaxbanner') || (Tools::isSubmit('updatestparallax') && $id_st_parallax))
        {
            $helper = $this->initFormSlide(1);
            return $this->_html.$helper->generateForm($this->fields_form_slide);
        }
        elseif(Tools::isSubmit('viewstparallax'))
        {
            $this->_html .= '<script type="text/javascript">var currentIndex="'.AdminController::$currentIndex.'&configure='.$this->name.'";</script>';
			$slide_group = new StParallaxGroup($id_st_parallax_group);
            if(!$slide_group->isAssociatedToShop())
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
			$helper = $this->initListSlide();
			return $this->_html.$helper->generateList(StParallaxClass::getAll($id_st_parallax_group,(int)$this->context->language->id,0), $this->fields_list).(isset($helper_banner) ? $helper_banner->generateList(StParallaxClass::getAll($id_st_parallax_group,(int)$this->context->language->id,0), $this->fields_list_banner) : '');
        
        }
		else if (Tools::isSubmit('deletestparallax') && $id_st_parallax)
		{
			$slide = new StParallaxClass($id_st_parallax);
            $slide->delete();
            $this->clearParallaxCache();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_parallax_group='.$slide->id_st_parallax_group.'&viewstparallax&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else if (Tools::isSubmit('deletestparallax') && $id_st_parallax_group)
		{
			$slide_group = new StParallaxGroup($id_st_parallax_group);
            $slide_group->delete();
            $this->clearParallaxCache();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else
		{
			$helper = $this->initList();
			return $this->_html.$helper->generateList(StParallaxGroup::getAll((int)$this->context->language->id), $this->fields_list);
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
	protected function initForm()
	{        
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Configuration'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Name:'),
                    'name' => 'name',
                    'size' => 64,
                    'required'  => true,
                ),
                'location' => array(
					'type' => 'select',
        			'label' => $this->l('Show on:'),
        			'name' => 'location',
                    'options' => array(
                        'query' => self::$location,
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('--')
                        ),
        			),
				),
                array(
                    'type' => 'text',
                    'label' => $this->l('Heading:'),
                    'name' => 'title',
                    'size' => 64,
                    'lang' => true,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Heading color:'),
                    'name' => 'title_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
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
                    'type' => 'text',
                    'label' => $this->l('Parallax speed factor:'),
                    'name' => 'speed',
                    'default_value' => 0.1,
                    'desc' => $this->l('Speed to move relative to vertical scroll. Example: 0.1 is one tenth the speed of scrolling, 2 is twice the speed of scrolling.'),
                    'class' => 'fixed-width-sm'
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Background color:'),
                    'name' => 'bg_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Top padding:'),
                    'name' => 'padding_top',
                    'default_value' => 0,
                    'class' => 'fixed-width-sm',  
                    'suffix' => 'px'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Bottom padding:'),
                    'name' => 'padding_bottom',
                    'default_value' => 0,
                    'class' => 'fixed-width-sm',  
                    'suffix' => 'px'
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
                
        $this->fields_form[2]['form'] = array(
			'legend' => array(
				'title' => $this->l('Effect'),
                'icon' => 'icon-cogs'                
			),
			'input' => array( 
                array(
                    'type' => 'radio',
                    'label' => $this->l('Display prev/next buttons:'),
                    'name' => 'prev_next',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'none',
                            'value' => 0,
                            'label' => $this->l('None')),
                        /*array(
                            'id' => 'left-right',
                            'value' => 1,
                            'label' => $this->l('Full height')),*/
                        array(
                            'id' => 'square',
                            'value' => 2,
                            'label' => $this->l('Square')),
                        array(
                            'id' => 'circle',
                            'value' => 3,
                            'label' => $this->l('Circle')),
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
					'label' => $this->l('Prev/next buttons hover color:'),
					'name' => 'prev_next_hover',
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
                    'type' => 'switch',
                    'label' => $this->l('Show pagination:'),
                    'name' => 'pag_nav',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'pag_nav_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
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
					'label' => $this->l('Mouse drag:'),
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
					'label' => $this->l('Hide on mobile:'),
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
                    'desc' => $this->l('screen width < 768px.'),
				), 
                array(
					'type' => 'text',
					'label' => $this->l('Time:'),
					'name' => 'time',
                    'default_value' => 7000,
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Transition period:'),
					'name' => 'trans_period',
                    'default_value' => 400,
                    'desc' => $this->l('The period, in milliseconds, of the transition effect.'),
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
					'type' => 'switch',
					'label' => $this->l('Auto height:'),
					'name' => 'autoHeight',
                    'default_value' => 0,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'autoHeight_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'autoHeight_off',
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
        
        $id_st_parallax_group = (int)Tools::getValue('id_st_parallax_group');
		$slide_group = new StParallaxGroup($id_st_parallax_group);
        
        if($slide_group->id)
        {
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_parallax_group');
            
            if ($slide_group->bg_img)
            {
                StParallaxGroup::fetchMediaServer($slide_group->bg_img);
                $this->fields_form[1]['form']['input']['bg_img_field']['image'] = '<img width=200 src="'.($slide_group->bg_img).'" /><p><a class="btn btn-default st_delete_image" href="javascript:;" data-id-group="'.(int)$slide_group->id.'"><i class="icon-trash"></i> Delete</a></p>';
            }
        }
        
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
        $helper->id = (int)$slide_group->id;
		$helper->table =  'st_parallax_group';
		$helper->identifier = 'id_st_parallax_group';
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->submit_action = 'savestparallaxgroup';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($slide_group),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
        
		$helper->title = $this->displayName; 
		
		return $helper;
	}

    public function getPatterns()
    {
        $html = '';
        foreach(range(1,25) as $v)
            $html .= '<div class="parttern_wrap" style="background:url('._MODULE_DIR_.'stthemeeditor/patterns/'.$v.'.png);"><span>'.$v.'</span></div>';
        $html .= '<div>Pattern credits:<a href="http://subtlepatterns.com" target="_blank">subtlepatterns.com</a></div>';
        return $html;
    }
    
    public function getPatternsArray()
    {
        $arr = array();
        for($i=1;$i<=25;$i++)
            $arr[] = array('id'=>$i,'name'=>$i); 
        return $arr;   
    }
        
	protected function initFormSlide()
	{
        $languages = Language::getLanguages(true);
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $id_st_parallax = (int)Tools::getValue('id_st_parallax');
        $id_st_parallax_group = (int)Tools::getValue('id_st_parallax_group');
		$slide = new StParallaxClass($id_st_parallax);

        $google_font_name_html = $google_font_name =  $google_font_link = '';
        if(Validate::isLoadedObject($slide)){
            $jon_arr = StParallaxFontClass::getBySlider($slide->id);
            if(is_array($jon_arr) && count($jon_arr))
                foreach ($jon_arr as $key => $value) {
                    $google_font_name_html .= '<li id="#'.str_replace(' ', '_', strtolower($value['font_name'])).'_li" class="form-control-static"><button type="button" class="delGoogleFont btn btn-default" name="'.$value['font_name'].'"><i class="icon-remove text-danger"></i></button>&nbsp;<span style="'.$this->fontstyles($value['font_name']).'">style="'.$this->fontstyles($value['font_name']).'"</span></li>';

                    $google_font_name .= $value['font_name'].'¤';

                    $google_font_link .= '<link id="'.str_replace(' ', '_', strtolower($value['font_name'])).'_link" rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='.str_replace(' ', '+', $value['font_name']).'" />';
                }
        }

		$this->fields_form_slide[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Item'),
                'icon'  => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'select',
        			'label' => $this->l('Group name:'),
        			'name' => 'id_st_parallax_group',
                    'required'  => true,
                    'options' => array(
        				'query' => StParallaxGroup::getAll($default_lang),
        				'id' => 'id_st_parallax_group',
        				'name' => 'name',
						'default' => array(
							'value' => 0,
							'label' => $this->l('Please select')
						)
        			)
				),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Content:'),
                    'lang' => true,
                    'name' => 'description',
                    'cols' => 40,
                    'rows' => 10,
                    'autoload_rte' => true,
                    'required' => true,
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
                    <li>closer: &lt;h2 class="closer"&gt;Sample&lt;/h2&gt;</li>
                    <li>spacer: &lt;div class="spacer"&gt;&nbsp;&lt;/div&gt;</li>
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
                    'type' => 'select',
                    'label' => $this->l('Width:'),
                    'name' => 'width',
                    'options' => array(
                        'query' => array(
                                array('id' => 10, 'name'=>'10%'),
                                array('id' => 20, 'name'=>'20%'),
                                array('id' => 30, 'name'=>'30%'),
                                array('id' => 50, 'name'=>'50%'),
                                array('id' => 60, 'name'=>'60%'),
                                array('id' => 70, 'name'=>'70%'),
                                array('id' => 80, 'name'=>'80%'),
                                array('id' => 90, 'name'=>'90%'),
                            ),
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => '0',
                            'label' => $this->l('100%')
                        )
                    ),
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Alignment:'),
                    'name' => 'text_align',
                    'default_value' => 2,
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
                    'label' => $this->l('Text color:'),
                    'name' => 'text_color',
                    'size' => 33,
                ),
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
        
		
        $this->fields_form_slide[0]['form']['input'][] = array(
			'type' => 'html',
            'id' => 'a_cancel',
			'label' => '',
			'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&id_st_parallax_group='.$slide->id_st_parallax_group.'&viewstparallax&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
		);
        if(Validate::isLoadedObject($slide))
        {
            $this->fields_form_slide[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_parallax');
        }
        elseif($id_st_parallax_group)
            $slide->id_st_parallax_group = $id_st_parallax_group;
                
        $helper = new HelperForm();
		$helper->show_toolbar = false;
        $helper->module = $this;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestparallax';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($slide,"fields_form_slide"),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
        $helper->tpl_vars['fields_value']['google_font_name'] = $google_font_name;
              
		return $helper;
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
    public static function showApplyTo($value,$row)
    {
	    if($value)
		   $result = isset(self::$location[$value]) ? self::$location[$value]['name'] : '';
        else
        {
            $module = new StParallax();
            $result = $module->l('--');
        }
        return $result;
    }
	protected function initList()
	{
		$this->fields_list = array(
			'id_st_parallax_group' => array(
				'title' => $this->l('Id'),
				'width' => 120,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'title' => array(
				'title' => $this->l('Heading'),
				'width' => 200,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'location' => array(
				'title' => $this->l('Show on'),
				'width' => 200,
				'type' => 'text',
				'callback' => 'showApplyTo',
				'callback_object' => 'StParallax',
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
		$helper->identifier = 'id_st_parallax_group';
		$helper->actions = array('view', 'edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstparallaxgroup&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add a group'),
		);

		$helper->title = $this->displayName;
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    public static function showGroupName($value,$row)
    {
        $slide_group = new StParallaxGroup((int)$value);
        return $slide_group->id ? $slide_group->name : '-';
    }
    public static function showContent($value,$row)
    {
        return Tools::truncateString(strip_tags(stripslashes($value)), 80);
    }
	protected function initListSlide()
	{
		$this->fields_list = array(
			'id_st_parallax' => array(
				'title' => $this->l('Id'),
				'width' => 120,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'id_st_parallax_group' => array(
				'title' => $this->l('Group name'),
				'width' => 120,
				'type' => 'text',
				'callback' => 'showGroupName',
				'callback_object' => 'StParallax',
                'search' => false,
                'orderby' => false
			),
            'description' => array(
				'title' => $this->l('Content'),
				'type' => 'text',
				'callback' => 'showContent',
				'callback_object' => 'StParallax',
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
		$helper->identifier = 'id_st_parallax';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstparallax&id_st_parallax_group='.(int)Tools::getValue('id_st_parallax_group').'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add a item')
		);
        $helper->toolbar_btn['edit'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&update'.$this->name.'&id_st_parallax_group='.(int)Tools::getValue('id_st_parallax_group').'&fr=view&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Edit group'),
		);
		$helper->toolbar_btn['back'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Back to list')
		);

		$helper->title = $this->l('Items');
		$helper->table = $this->name;
		$helper->orderBy = 'position';
		$helper->orderWay = 'ASC';
	    $helper->position_identifier = 'id_st_parallax';
        
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    private function _prepareHook($identify,$type=1)
    {        
        $slide_group = StParallaxGroup::getSlideGroup($this->context->language->id, $identify, $type);
        if(!is_array($slide_group) || !count($slide_group))
            return false;
        
        foreach($slide_group as &$v)
        {
             $slide = StParallaxClass::getAll($v['id_st_parallax_group'],$this->context->language->id,1);
             if(is_array($slide) && $slide_nbr=count($slide))
             {
                $v['slide'] = $slide;
             }
        }
	    $this->smarty->assign(array(
            'slide_group' => $slide_group,
        ));
        return true;
    }
    public function hookDisplayHeader($params)
    {
        /*$data = StParallaxFontClass::getAll(1);
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

        if (!$this->isCached('header.tpl', $this->getCacheId()))
        {
            $custom_css = '';
            $options = StParallaxClass::getOptions();
            if(is_array($options) && count($options))
                foreach($options as $v)    
                {
                    $classname = '.parallax_text_con_'.$v['id_st_parallax'].' ';
                    if($v['text_color'])
                        $custom_css .= $classname.'.style_content,
                    '.$classname.'.style_content a{color:'.$v['text_color'].';}
                    '.$classname.'.icon_line:after, '.$classname.'.icon_line:before{background-color:'.$v['text_color'].';}
                    '.$classname.'.line, '.$classname.'.btn{border-color:'.$v['text_color'].';}';

                    if($v['btn_color'])
                        $custom_css .= $classname.'.style_content .btn{color:'.$v['btn_color'].';}';
                    if($v['btn_color'] && !$v['btn_bg'])
                        $custom_css .= $classname.'.style_content .btn{border-color:'.$v['btn_color'].';}';
                    if($v['btn_bg'])
                        $custom_css .= $classname.'.style_content .btn{background-color:'.$v['btn_bg'].';border-color:'.$v['btn_bg'].';}';
                    if($v['btn_hover_color'])
                        $custom_css .= $classname.'.style_content .btn:hover{color:'.$v['btn_hover_color'].';}';
                    if ($v['btn_hover_bg'])
                        $custom_css .= $classname.'.style_content .btn:hover{background-color: '.$v['btn_hover_bg'].';}';
                }
            
            $options = StParallaxGroup::getOptions();
            if(is_array($options) && count($options))
                foreach($options as $v)    
                {
                    $group_css = '';
                    if ($v['bg_color'])
                        $group_css .= 'background-color:'.$v['bg_color'].';';
                    if ($v['bg_img'])
                    {
                        $img = _THEME_PROD_PIC_DIR_.$v['bg_img'];
                        $img = $this->context->link->protocol_content.Tools::getMediaServer($img).$img;
                        $group_css .= 'background-image: url('.$img.');';
                    }
                    elseif ($v['bg_pattern'])
                    {
                        $img = _MODULE_DIR_.'stthemeeditor/patterns/'.$v['bg_pattern'].'.png';
                        $img = $this->context->link->protocol_content.Tools::getMediaServer($img).$img;
                        $group_css .= 'background-image: url('.$img.');';
                    }
                    if($group_css)
                        $custom_css .= '#parallax_box_'.$v['id_st_parallax_group'].'{'.$group_css.'}';
                        
                    if ($v['title_color'])
                        $custom_css .= '#parallax_box_'.$v['id_st_parallax_group'].' .parallax_heading{color:'.$v['title_color'].';}';

                    
                    if ($v['prev_next_color'])
                        $custom_css .= '#owl-parallax-'.$v['id_st_parallax_group'].'.owl-theme.owl-navigation-lr .owl-controls .owl-buttons div{color:'.$v['prev_next_color'].';}';
                    if ($v['prev_next_hover'])
                        $custom_css .= '#owl-parallax-'.$v['id_st_parallax_group'].'.owl-theme.owl-navigation-lr .owl-controls .owl-buttons div:hover{color:'.$v['prev_next_hover'].';}';
                    if($v['prev_next_bg'])
                    {
                        $prev_next_bg = self::hex2rgb($v['prev_next_bg'] );
                        $custom_css .= '#owl-parallax-'.$v['id_st_parallax_group'].'.owl-theme.owl-navigation-lr .owl-controls .owl-buttons div{background-color:rgba('.$prev_next_bg.',0.4);}#owl-parallax-'.$v['id_st_parallax_group'].'.owl-theme.owl-navigation-lr .owl-controls .owl-buttons div:hover{background-color:rgba('.$prev_next_bg.',0.8);}';
                    }
                    /*
                    if ($v['prev_next_bg'])
                        $custom_css .= '#owl-parallax-'.$v['id_st_parallax_group'].'.owl-theme .owl-controls .owl-buttons div{background-color:'.$v['prev_next_bg'].';}';
                    */
                   
                    if ($v['pag_nav_bg'])
                        $custom_css .= '#owl-parallax-'.$v['id_st_parallax_group'].'.owl-theme .owl-controls .owl-page span{background-color:'.$v['pag_nav_bg'].';}';
                    if ($v['pag_nav_bg_active'])
                        $custom_css .= '#owl-parallax-'.$v['id_st_parallax_group'].'.owl-theme .owl-controls .owl-page.active span{background-color:'.$v['pag_nav_bg_active'].';}';
                    
                    if ($v['padding_top'])
                        $custom_css .= '#parallax_box_'.$v['id_st_parallax_group'].'{padding-top:'.$v['padding_top'].'px;}';
                    if ($v['padding_bottom'])
                        $custom_css .= '#parallax_box_'.$v['id_st_parallax_group'].'{padding-bottom:'.$v['padding_bottom'].'px;}';

                    if(isset($v['top_spacing']) && ($v['top_spacing'] || $v['top_spacing']==='0'))
                        $custom_css .= '#parallax_box_'.$v['id_st_parallax_group'].'{margin-top:'.(int)$v['top_spacing'].'px;}';
                    if(isset($v['bottom_spacing']) && ($v['bottom_spacing'] || $v['bottom_spacing']==='0'))
                        $custom_css .= '#parallax_box_'.$v['id_st_parallax_group'].'{margin-bottom:'.(int)$v['bottom_spacing'].'px;}';
                }
            if($custom_css)
                $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
        }
        return $this->display(__FILE__, 'header.tpl', $this->getCacheId());
    }
	public function hookDisplayHomeTop($params)
	{
		if (!$this->isCached('stparallax.tpl', $this->stGetCacheId(4)))
            if(!$this->_prepareHook(4,1))
                return false;
		return $this->display(__FILE__, 'stparallax.tpl', $this->stGetCacheId(4));
	}
    
	public function hookDisplayHome($params)
	{
		if (!$this->isCached('stparallax.tpl', $this->stGetCacheId(3)))
            if(!$this->_prepareHook(3,1))
                return false;
		return $this->display(__FILE__, 'stparallax.tpl', $this->stGetCacheId(3));
	}
    
    public function hookDisplayHomeBottom($params)
    {
        if (!$this->isCached('stparallax.tpl', $this->stGetCacheId(17)))
            if(!$this->_prepareHook(17,1))
                return false;
        return $this->display(__FILE__, 'stparallax.tpl', $this->stGetCacheId(17));
    }
    

    public function hookDisplayFullWidthTop($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        
        if (!$this->isCached('stparallax.tpl', $this->stGetCacheId(36)))
            if(!$this->_prepareHook(36,1))
                return false;
        return $this->display(__FILE__, 'stparallax.tpl', $this->stGetCacheId(36));        
    }


    public function hookDisplayFullWidthTop2($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        
        if (!$this->isCached('stparallax.tpl', $this->stGetCacheId(38)))
            if(!$this->_prepareHook(38,1))
                return false;
        return $this->display(__FILE__, 'stparallax.tpl', $this->stGetCacheId(38));        
    }

    public function hookDisplayHomeVeryBottom($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        
        if (!$this->isCached('stparallax.tpl', $this->stGetCacheId(37)))
            if(!$this->_prepareHook(37,1))
                return false;
        return $this->display(__FILE__, 'stparallax.tpl', $this->stGetCacheId(37));
    }
    public function hookDisplayBottomColumn($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        
        if (!$this->isCached('stparallax.tpl', $this->stGetCacheId(28)))
            if(!$this->_prepareHook(28,1))
                return false;
        return $this->display(__FILE__, 'stparallax.tpl', $this->stGetCacheId(28));
    }

    public function hookDisplayTopColumn($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        
        if (!$this->isCached('stparallax.tpl', $this->stGetCacheId(35)))
            if(!$this->_prepareHook(35,1))
                return false;
        return $this->display(__FILE__, 'stparallax.tpl', $this->stGetCacheId(35));
    }

	public function hookDisplayStBlogHome($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stparallax.tpl', $this->stGetCacheId(6)))
            if(!$this->_prepareHook(6,1))
                return false;
		return $this->display(__FILE__, 'stparallax.tpl', $this->stGetCacheId(6));
	}
    
    
    public function displayBlogMainSlide()
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		//if (!$this->isCached('stparallax-topextra.tpl', $this->stGetCacheId(1)))
            if(!$this->_prepareHook(array(7,8,18,19),1))
                return false;
		return $this->display(__FILE__, 'stparallax.tpl');
	}
	
	public function hookDisplayAnywhere($params)
	{
	    if(!isset($params['caller']) || $params['caller']!=$this->name)
            return false;
	   if(isset($params['function']) && method_exists($this,$params['function']))
        {
            if($params['function']=='displayBySlideId')
                return call_user_func_array(array($this,$params['function']),array($params['identify']));
            elseif($params['function']=='displayBlogMainSlide')
                return call_user_func(array($this,$params['function']));
            else
                return false;
        }
        return false;
    }
    public function displayBySlideId($identify)
    {
        if(!Validate::isUnsignedInt($identify))
            return false;
            
        $slide_group_obj = new StParallaxGroup($identify);
        if(!$slide_group_obj->id || !$slide_group_obj->active)
            return false;
		if (!$this->isCached('stparallax.tpl', $this->stGetCacheId($slide_group_obj->id,'id')))
            if(!$this->_prepareHook($identify,3))
                return false;
		return $this->display(__FILE__, 'stparallax.tpl', $this->stGetCacheId($slide_group_obj->id,'id'));
    }
	public function hookActionShopDataDuplication($params)
	{
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'st_parallax_group_shop (id_st_parallax_group, id_shop)
		SELECT id_st_parallax_group, '.(int)$params['new_id_shop'].'
		FROM '._DB_PREFIX_.'st_parallax_group_shop
		WHERE id_shop = '.(int)$params['old_id_shop']);
        $this->clearParallaxCache();
    }
	protected function stGetCacheId($key,$type='location',$name = null)
	{
		$cache_id = parent::getCacheId($name);
		return $cache_id.'_'.$key.'_'.$type;
	}
	private function clearParallaxCache()
	{
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
            
    public function processUpdatePositions()
	{
		if (Tools::getValue('action') == 'updatePositions' && Tools::getValue('ajax'))
		{
			$way = (int)(Tools::getValue('way'));
			$id = (int)(Tools::getValue('id'));
			$positions = Tools::getValue('st_parallax');
            $msg = '';
			if (is_array($positions))
				foreach ($positions as $position => $value)
				{
					$pos = explode('_', $value);

					if ((isset($pos[2])) && ((int)$pos[2] === $id))
					{
						if ($object = new StParallaxClass((int)$pos[2]))
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
