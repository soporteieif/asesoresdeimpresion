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

include_once dirname(__FILE__).'/StBannerClass.php';
include_once dirname(__FILE__).'/StBannerGroup.php';

class StBanner extends Module
{
    protected static $access_rights = 0775;
    
    public static $location = array(
        6 => array('id' =>6 , 'name' => 'Page top secondary'),
        1 => array('id' =>1 , 'name' => 'Homepage'),
        2 => array('id' =>2 , 'name' => 'Homepage top'),
        3 => array('id' =>3 , 'name' => 'Homepage bottom'),
        18 => array('id' =>18 , 'name' => 'Very bottom of homepage'),
        4 => array('id' =>4 , 'name' => 'Homepage secondary left'),
        5 => array('id' =>5 , 'name' => 'Homepage secondary right'),
        19 => array('id' =>19 , 'name' => 'Homepage tertiary left'),
        20 => array('id' =>20 , 'name' => 'Homepage tertiary right'),
        7 => array('id' =>7 , 'name' => 'Left column'),
        8 => array('id' =>8 , 'name' => 'Right column'),
        9 => array('id' =>9 , 'name' => 'Footer'),
        10 => array('id' =>10, 'name' => 'Footer top'),
        16 => array('id' =>16 , 'name' => 'At bottom of prodcut page'),
        17 => array('id' =>17 , 'name' => 'At bottom of category page'),
        11 => array('id' =>11 , 'name' => 'Blog homepage'),
        12 => array('id' =>12 , 'name' => 'Blog homepage top'),
        13 => array('id' =>13 , 'name' => 'Blog homepage bottom'),
        14 => array('id' =>14 , 'name' => 'Blog left column'),
        15 => array('id' =>15 , 'name' => 'Blog right column'),
        21 => array('id' =>21 , 'name' => 'Most top of the page'),
    );
    public static $_layout = array(
    	1 => array('col-xs-12 col-sm-3 col-md-3','col-xs-12 col-sm-3 col-md-3','col-xs-12 col-sm-3 col-md-3','col-xs-12 col-sm-3 col-md-3'),
    	2 => array('col-xs-12 col-sm-6 col-md-6','col-xs-12 col-sm-6 col-md-6'),
    	3 => array('col-xs-12 col-sm-4 col-md-4','col-xs-12 col-sm-4 col-md-4','col-xs-12 col-sm-4 col-md-4'),
    	6 => array('col-xs-12 col-sm-4 col-md-4','col-xs-12 col-sm-8 col-md-8'),
    	7 => array('col-xs-12 col-sm-8 col-md-8','col-xs-12 col-sm-4 col-md-4'),
    	8 => array('col-xs-12'),
        9 => array('col-xs-12 col-sm-3 col-md-3','col-xs-12 col-sm-3 col-md-3','col-xs-12 col-sm-6 col-md-6'),
        10 => array('col-xs-12 col-sm-6 col-md-6','col-xs-12 col-sm-3 col-md-3','col-xs-12 col-sm-3 col-md-3'),
	);
    
    public  $fields_list;
    public  $fields_list_banner;
    public  $fields_value;
    public  $fields_form;
    public  $fields_form_banner;
	private $_html = '';
	private $spacer_size = '5';
    private $_style = array();

	public function __construct()
	{
		$this->name          = 'stbanner';
		$this->tab           = 'front_office_features';
		$this->version       = '1.7.5';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
        $this->bootstrap     = true;

		parent::__construct();
        
        $this->_style = array(
				array(
					'id' => 'style_1',
					'value' => 1,
					'label' => '1<img src="'.$this->_path.'views/img/1.jpg" />'),
				array(
					'id' => 'style_2',
					'value' => 2,
					'label' => '2<img src="'.$this->_path.'views/img/2.jpg" />'),
				array(
					'id' => 'style_3',
					'value' => 3,
					'label' => '3<img src="'.$this->_path.'views/img/3.jpg" />'),
				array(
					'id' => 'style_6',
					'value' => 6,
					'label' => '4<img src="'.$this->_path.'views/img/6.jpg" />'),
				array(
					'id' => 'style_7',
					'value' => 7,
					'label' => '5<img src="'.$this->_path.'views/img/7.jpg" />'),
                array(
                    'id' => 'style_8',
                    'value' => 8,
                    'label' => '6<img src="'.$this->_path.'views/img/8.jpg" />'),
                array(
                    'id' => 'style_9',
                    'value' => 9,
                    'label' => '7<img src="'.$this->_path.'views/img/9.jpg" />'),
				array(
					'id' => 'style_10',
					'value' => 10,
					'label' => '8<img src="'.$this->_path.'views/img/10.jpg" />'),
			);

		$this->displayName   = $this->l('Static banners');
		$this->description   = $this->l('This module was made to easy upload banners in your shop.');
	}
            
	public function install()
	{
		$res = parent::install() &&
			$this->createTables() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayTopSecondary') &&
			$this->registerHook('displayLeftColumn') && 
			$this->registerHook('displayRightColumn') && 
            $this->registerHook('displayHome') &&
            $this->registerHook('displayHomeTop') &&
            $this->registerHook('displayHomeBottom') &&
			$this->registerHook('displayHomeSecondaryLeft') &&
			$this->registerHook('displayHomeSecondaryRight') &&
            $this->registerHook('displayCategoryHeader') &&
            $this->registerHook('displayCategoryFooter') &&
            $this->registerHook('displayManufacturerHeader') &&
            $this->registerHook('displayFooter') &&
            $this->registerHook('displayFooterTop') &&
			$this->registerHook('displayAnywhere') &&
			$this->registerHook('actionObjectCategoryDeleteAfter') &&
            $this->registerHook('actionObjectManufacturerDeleteAfter') &&
			$this->registerHook('displayStBlogHome') &&
			$this->registerHook('displayStBlogHomeTop') &&
			$this->registerHook('displayStBlogHomeBottom') &&
			$this->registerHook('displayStBlogLeftColumn') && 
            $this->registerHook('displayStBlogRightColumn') && 
            $this->registerHook('displayHomeVeryBottom') && 
            $this->registerHook('displayHomeTertiaryLeft') && 
            $this->registerHook('displayHomeTertiaryRight')&& 
			$this->registerHook('displayBanner');
		/*if ($res)
			foreach(Shop::getShops(false) as $shop)
				$res &= $this->sampleData($shop['id_shop']);*/
        $this->clearBannerCache();
        return $res;
	}
	
	/**
	 * Creates tables
	 */
	public function createTables()
	{
		/* Banners */
		$return = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_banner` (
				`id_st_banner` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_st_banner_group` int(10) unsigned NOT NULL,
                `id_currency` int(10) unsigned DEFAULT 0,
    			`image` varchar(255) DEFAULT NULL,
                `new_window` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `position` int(10) unsigned NOT NULL DEFAULT 0,
				PRIMARY KEY (`id_st_banner`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		/* Banners lang configuration */
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_banner_lang` (
				`id_st_banner` int(10) UNSIGNED NOT NULL,
				`id_lang` int(10) unsigned NOT NULL ,
    			`url` varchar(255) DEFAULT NULL,
                `title` varchar(255) DEFAULT NULL,
                `image_multi_lang` varchar(255) DEFAULT NULL,
                `width` int(10) unsigned NOT NULL DEFAULT 0,
                `height` int(10) unsigned NOT NULL DEFAULT 0,
				PRIMARY KEY (`id_st_banner`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
            
		/* Banners group */
		$return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_banner_group` (
				`id_st_banner_group` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,      
                `name` varchar(255) DEFAULT NULL, 
                `location` int(10) unsigned NOT NULL DEFAULT 0,
                `id_category` int(10) unsigned NOT NULL DEFAULT 0,
                `id_manufacturer` int(10) unsigned NOT NULL DEFAULT 0,  
                `layout` int(10) unsigned NOT NULL DEFAULT 0, 
                `hide_on_mobile` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `hover_effect` tinyint(2) unsigned NOT NULL DEFAULT 1,  
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `position` int(10) unsigned NOT NULL DEFAULT 0, 
                `top_spacing` varchar(10) DEFAULT NULL,
                `bottom_spacing` varchar(10) DEFAULT NULL,
                `show_on_sub` tinyint(1) unsigned NOT NULL DEFAULT 1,
				PRIMARY KEY (`id_st_banner_group`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		/* Banners group shop */
		$return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_banner_group_shop` (
				`id_st_banner_group` int(10) UNSIGNED NOT NULL,
                `id_shop` int(11) NOT NULL,      
                PRIMARY KEY (`id_st_banner_group`,`id_shop`),    
                KEY `id_shop` (`id_shop`)   
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		return $return;
	}
    
    public function sampleData($id_shop)
    {
        $return = true;
        $path = _MODULE_DIR_.$this->name;
		$samples = array(
			array(
                'name' => 'Homepage top',
                'location' => 2, 
                'layout' => 3, 
                'hide_on_mobile' => 0,
                'hover_effect' => 1,
                'child' => array(
        			array(
                        'image' => $path.'/views/img/sample_1.jpg', 
                        'title' => 'Sample banner',
                        'url' => '/',
                        'width' => 377,
                        'height' => 278,
                    ),
        			array(
                        'image' => $path.'/views/img/sample_2.jpg', 
                        'title' => 'Sample banner',
                        'url' => '/',
                        'width' => 377,
                        'height' => 278,
                    ),
        			array(
                        'image' => $path.'/views/img/sample_3.jpg', 
                        'title' => 'Sample banner',
                        'url' => '/',
                        'width' => 377,
                        'height' => 278,
                    ),
        		),
            ),
			array(
                'name' => 'Homepage right',
                'location' => 5, 
                'layout' => 8, 
                'hide_on_mobile' => 0,
                'hover_effect' => 1,
                'child' => array(
        			array(
                        'image' => $path.'/views/img/sample_4.jpg', 
                        'title' => 'Sample banner',
                        'url' => '/',
                        'width' => 280,
                        'height' => 400,
                    ),
        		),
            ),
		);
		foreach($samples as $k=>&$sample)
		{
			$module = new StBannerGroup();
			$module->name = $sample['name'];
			$module->location = $sample['location'];
			$module->layout = $sample['layout'];
			$module->hide_on_mobile = $sample['hide_on_mobile'];
			$module->hover_effect = $sample['hover_effect'];
			$module->active = 1;
			$module->position = $k;
			$return &= $module->add();
            //
            if($return && $module->id)
            {
                $sample['id_st_banner_group'] = $module->id;
    			Db::getInstance()->insert('st_banner_group_shop', array(
    				'id_st_banner_group' => (int)$module->id,
    				'id_shop' => (int)$id_shop,
    			));
            }
		}
        
        foreach($samples as $sp)
		{
            if(!$sp['id_st_banner_group'] || !isset($sp['child']) || !count($sp['child']))
                continue;
		    foreach($sp['child'] as $k=>$v)
    		{
    			$module = new StBannerClass();
    			$module->id_st_banner_group = $sp['id_st_banner_group'];
    			$module->active = 1;
    			$module->position = $k;
                
    			foreach (Language::getLanguages(false) as $lang)
                {
    				$module->title[$lang['id_lang']] = $v['title'];
    				$module->url[$lang['id_lang']] = $v['url'];
                    $module->image_multi_lang[$lang['id_lang']] = $v['image'];
                    $module->width[$lang['id_lang']] = $v['width'];
    			    $module->height[$lang['id_lang']] = $v['height'];
                }
                
    			$return &= $module->add();
    		}
		}
		return $return;
    }
     
	public function uninstall()
	{
	    $this->clearBannerCache();
		// Delete configuration
		return $this->deleteTables() &&
			parent::uninstall();
	}

	/**
	 * deletes tables
	 */
	public function deleteTables()
	{
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_banner`,`'._DB_PREFIX_.'st_banner_lang`,`'._DB_PREFIX_.'st_banner_group`,`'._DB_PREFIX_.'st_banner_group_shop`');
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
        
        $id_st_banner_group = (int)Tools::getValue('id_st_banner_group');
        $id_st_banner = (int)Tools::getValue('id_st_banner');
	    if ((Tools::isSubmit('groupstatusstbanner')))
        {
            $group = new StBannerGroup((int)$id_st_banner_group);
            if($group->id && $group->toggleStatus())
            {
                $this->clearBannerCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
	    if ((Tools::isSubmit('bannerstatusstbanner')))
        {
            $banner = new StBannerClass((int)$id_st_banner);
            if($banner->id && $banner->toggleStatus())
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));  
                $this->clearBannerCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_banner_group='.$banner->id_st_banner_group.'&viewstbanner&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
        if ((Tools::isSubmit('bannerdeletestbanner')))
        {
            $banner = new StBannerClass((int)$id_st_banner);
            if($banner->id)
            {
                $id_lang = Tools::getValue('id_lang');
                foreach(Language::getLanguages(true) AS $lang)
                    if ($id_lang == $lang['id_lang'])
                    {
                        $banner->image_multi_lang[$id_lang] = '';
                        $banner->width[$id_lang] = 0;
                        $banner->height[$id_lang] = 0;
                        break;
                    }
                if ($banner->save())
                {
                    //$this->_html .= $this->displayConfirmation($this->l('The image was deleted successfully.'));  
                    $this->clearBannerCache();
                    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_banner_group='.$banner->id_st_banner_group.'&viewstbanner&conf=7&token='.Tools::getAdminTokenLite('AdminModules'));   
                }else
                    $this->_html .= $this->displayError($this->l('An error occurred while delete banner.'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while delete banner.'));
        }
        if (Tools::isSubmit('way') && Tools::isSubmit('id_st_banner') && (Tools::isSubmit('position')))
		{
		    $banner = new StBannerClass((int)$id_st_banner);
            if($banner->id && $banner->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position')))
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));  
                $this->clearBannerCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_banner_group='.$banner->id_st_banner_group.'&viewstbanner&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            else
                $this->_html .= $this->displayError($this->l('Failed to update the position.'));
		}
        if (Tools::getValue('action') == 'updatePositions')
        {
            $this->processUpdatePositions();
        }
		if (isset($_POST['savestbannergroup']) || isset($_POST['savestbannergroupAndStay']))
		{
            if ($id_st_banner_group)
				$group = new StBannerGroup((int)$id_st_banner_group);
			else
				$group = new StBannerGroup();
            
            $error = array();
    		$group->copyFromPost();
            
            if(!$group->name)
                $error[] = $this->displayError($this->l('The field "Banner group name" is required'));
            
            if($group->location)
            {
                $item_arr = explode('-',$group->location);
                if(count($item_arr)==2)
                {
                    $group->id_category = 0;
                    $group->location = 0;
                    $group->id_manufacturer = 0;
                    if($item_arr[0]==1)
                        $group->location = (int)$item_arr[1];
                    elseif($item_arr[0]==2)
                        $group->id_category = (int)$item_arr[1];
                    elseif($item_arr[0]==3)
                        $group->id_manufacturer = (int)$item_arr[1];
                }
            }  
            
			if (!count($error) && $group->validateFields(false) && $group->validateFieldsLang(false))
            {
                if($group->save())
                {
		            Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'st_banner_group_shop WHERE id_st_banner_group='.(int)$group->id);
                    if (!Shop::isFeatureActive())
            		{
            			Db::getInstance()->insert('st_banner_group_shop', array(
            				'id_st_banner_group' => (int)$group->id,
            				'id_shop' => (int)Context::getContext()->shop->id,
            			));
            		}
            		else
            		{
            			$assos_shop = Tools::getValue('checkBoxShopAsso_st_banner_group');
            			if (empty($assos_shop))
            				$assos_shop[(int)Context::getContext()->shop->id] = Context::getContext()->shop->id;
            			foreach ($assos_shop as $id_shop => $row)
            				Db::getInstance()->insert('st_banner_group_shop', array(
            					'id_st_banner_group' => (int)$group->id,
            					'id_shop' => (int)$id_shop,
            				));
            		}
                    $this->clearBannerCache();
                    if(isset($_POST['savestbannergroupAndStay']) || Tools::getValue('fr') == 'view')
                    {
                        $rd_str = isset($_POST['savestbannergroupAndStay']) && Tools::getValue('fr') == 'view' ? 'fr=view&update' : (isset($_POST['savestbannergroupAndStay']) ? 'update' : 'view');
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_banner_group='.$group->id.'&conf='.($id_st_banner_group?4:3).'&'.$rd_str.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                    }      
                    else
                        $this->_html .= $this->displayConfirmation($this->l('Banner group').' '.($id_st_banner_group ? $this->l('updated') : $this->l('added')));
                }                    
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during banner group').' '.($id_st_banner_group ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
            $this->clearBannerCache();
        }
		if (isset($_POST['savestbanner']) || isset($_POST['savestbannerAndStay']))
		{
            if ($id_st_banner)
				$banner = new StBannerClass((int)$id_st_banner);
			else
				$banner = new StBannerClass();
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
			    $banner->copyFromPost();
                if(!$banner->id_st_banner_group)
                    $error[] = $this->displayError($this->l('The field "Banner group" is required'));
                else
                {
                    $res = $this->stUploadImage('image_multi_lang_'.$default_lang);
                    if(count($res['error']))
                        $error = array_merge($error,$res['error']);
                    elseif($res['image'])
                    {
                        $banner->image_multi_lang[$default_lang] = $res['image'];
                        $banner->width[$default_lang] = $res['width'];
                        $banner->height[$default_lang] = $res['height'];
                    }
                    elseif(!Tools::isSubmit('has_image_'.$default_lang) && !$res['image'])
                    {
                        $defaultLanguage = new Language($default_lang);
                        $error[] = $this->displayError($this->l('Image is required at least in ').$defaultLanguage->name);
                    }
                    
                    if($banner->image_multi_lang[$default_lang])
                    {
                        foreach ($languages as $lang)
                        {
                            if($lang['id_lang']==$default_lang)
                                continue;
                            $res = $this->stUploadImage('image_multi_lang_'.$lang['id_lang']);
                            if(count($res['error']))
                                $error = array_merge($error,$res['error']);
                            elseif($res['image'])
                            {
                                $banner->image_multi_lang[$lang['id_lang']] = $res['image'];
                                $banner->width[$lang['id_lang']] = $res['width'];
                                $banner->height[$lang['id_lang']] = $res['height'];
                            }
                            elseif(!Tools::isSubmit('has_image_'.$lang['id_lang']) && !$res['image'])
                            {
                                $banner->image_multi_lang[$lang['id_lang']] = $banner->image_multi_lang[$default_lang];
                                $banner->width[$lang['id_lang']] = $banner->width[$default_lang];
                                $banner->height[$lang['id_lang']] = $banner->height[$default_lang];
                            }
                        }
                    }
                }
            }
                        
			if (!count($error) && $banner->validateFields(false) && $banner->validateFieldsLang(false))
            {
                /*position*/
                $banner->position = $banner->checkPosition();
                if($banner->save())
                {
                    $this->clearBannerCache();
                    //$this->_html .= $this->displayConfirmation($this->l('Banner').' '.($id_st_banner ? $this->l('updated') : $this->l('added')));
			        if(isset($_POST['savestbannerAndStay']))
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_banner='.$banner->id.'&conf='.($id_st_banner?4:3).'&update'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));    
                    else
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_banner_group='.$banner->id_st_banner_group.'&viewstbanner&token='.Tools::getAdminTokenLite('AdminModules'));
                }
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during banner').' '.($id_st_banner ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }
        
		if (Tools::isSubmit('addstbannergroup') || (Tools::isSubmit('updatestbanner') && $id_st_banner_group))
		{
            $helper = $this->initForm();
            return $helper->generateForm($this->fields_form);
		}
        elseif(Tools::isSubmit('addstbanner') || (Tools::isSubmit('updatestbanner') && $id_st_banner))
        {
            $helper = $this->initFormBanner();
            return $helper->generateForm($this->fields_form_banner);
        }
        elseif(Tools::isSubmit('viewstbanner'))
        {
            $this->_html .= '<script type="text/javascript">var currentIndex="'.AdminController::$currentIndex.'&configure='.$this->name.'";</script>';
			$group = new StBannerGroup($id_st_banner_group);
            if(!$group->isAssociatedToShop())
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
			$helper = $this->initListBanner();
			return $this->_html.$helper->generateList(StBannerClass::getAll($id_st_banner_group,(int)$this->context->language->id), $this->fields_list);
        }
		else if (Tools::isSubmit('deletestbanner') && $id_st_banner)
		{
			$banner = new StBannerClass($id_st_banner);
            $banner->delete();
            $this->clearBannerCache();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_banner_group='.$id_st_banner_group.'&viewstbanner&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else if (Tools::isSubmit('deletestbanner') && $id_st_banner_group)
		{
			$group = new StBannerGroup($id_st_banner_group);
            $group->delete();
            $this->clearBannerCache();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else
		{
			$helper = $this->initList();
			return $this->_html.$helper->generateList(StBannerGroup::getAll(), $this->fields_list);
		}
	}
     protected function stUploadImage($item)
    {
        $result = array(
            'error' => array(),
            'image' => '',
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
				if ($upload_error = ImageManager::validateUpload($_FILES[$item]))
					$result['error'][] = $upload_error;
				elseif (!$temp_name || !move_uploaded_file($_FILES[$item]['tmp_name'], $temp_name))
					$result['error'][] = $this->l('An error occurred during move image.');
				else{
				   $infos = getimagesize($temp_name);
                   if(!ImageManager::resize($temp_name, _PS_UPLOAD_DIR_.$this->name.'/'.$c_name.'.'.$type, null, null, $type))
				       $result['error'][] = $this->l('An error occurred during the image upload.');
				} 
				if (isset($temp_name))
					@unlink($temp_name);
                    
                if(!count($result['error']))
                {
                    $result['image'] = $this->name.'/'.$c_name.'.'.$type;
                    $result['width'] = $imagesize[0];
                    $result['height'] = $imagesize[1];
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
    public static function getApplyTo()
    {
        $module = new StBanner();
        $location = array();
        foreach(self::$location as $v)
            $location[] = array('id'=>'1-'.$v['id'],'name'=>$v['name']);
            
        $root_category = Category::getRootCategory();
        $category_arr = array();
        $module->getCategoryOption($category_arr,$root_category->id);
        //unset root category
        if(isset($category_arr[$root_category->id]))
            unset($category_arr[$root_category->id]);
            
        $manufacturer_arr = array();
		$manufacturers = Manufacturer::getManufacturers(false, Context::getContext()->language->id);
		foreach ($manufacturers as $manufacturer)
            $manufacturer_arr[] = array('id'=>'3-'.$manufacturer['id_manufacturer'],'name'=>$manufacturer['name']);
                
        return array(
            array('name'=>$module->l('Hook'),'query'=>$location),
            array('name'=>$module->l('Category'),'query'=>$category_arr),
            array('name'=>$module->l('Manufacturer'),'query'=>$manufacturer_arr),
        );
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
        $id_st_banner_group = (int)Tools::getValue('id_st_banner_group');
		$group = new StBannerGroup($id_st_banner_group);
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Banner configuration'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Banner group name:'),
					'name' => 'name',
                    'size' => 64,
                    'required'  => true,
				),
                array(
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
                    'desc' => '<div class="alert alert-info"><a href="javascript:;" onclick="$(\'#des_page_layout\').toggle();return false;">'.$this->l('Click here to see hook position').'</a>'.
                        '<div id="des_page_layout" style="display:none;"><img src="'._MODULE_DIR_.'stthemeeditor/img/hook_into_hint.jpg" /></div></div>',
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
    				'type' => 'html',
                    'id' => 'style',
                    'label' => $this->l('Style:'),
    				'name' => $this->BuildRadioUI($this->_style, 'layout', $group->layout ? $group->layout : 1),
                    'desc' => array(
                        $this->l('Recommended banner width:'),
                        '1 -> 280px',
                        '2 -> 580px',
                        '3 -> 377px',
                        '4 -> 377px,773px',
                        '5 -> 773px,377px',
                        '6 -> 1170px',
                        '7 -> 280px,280px,580px',
                        '8 -> 580px,280px,280px',
                    ),
                    
                ),
                array(
					'type' => 'switch',
					'label' => $this->l('Image hover effects:'),
					'name' => 'hover_effect',
                    'default_value' => 1,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'hover_effect_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'hover_effect_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
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
                    'desc' => $this->l('if set to Yes, banner will be hidden on mobile devices (if screen width is less than 768 pixels).'),
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
					'name' => 'fr',
                    'default_value' => Tools::getValue('fr'),
				),
			),
            'buttons' => array(
                array(
                    'type' => 'submit',
                    'title'=> $this->l(' Save '),
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
        
        if($group->id)
        {
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_banner_group');
        }
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
        $helper->id = (int)$group->id;
		$helper->table =  'st_banner_group';
		$helper->identifier = 'id_st_banner_group';
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->submit_action = 'savestbannergroup';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($group),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
        if($group->id)
            $helper->tpl_vars['fields_value']['location'] = $group->location ? '1-'.$group->location : ($group->id_category ? '2-'.$group->id_category : '3-'.$group->id_manufacturer);
		return $helper;
	}

	protected function initFormBanner()
	{        
		$this->fields_form_banner[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Banner item'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'select',
        			'label' => $this->l('Banner group:'),
        			'name' => 'id_st_banner_group',
                    'required'  => true,
                    'options' => array(
        				'query' => StBannerGroup::getAll(),
        				'id' => 'id_st_banner_group',
        				'name' => 'name',
						'default' => array(
							'value' => 0,
							'label' => $this->l('Please select')
						)
        			)
				),
                array(
					'type' => 'text',
					'label' => $this->l('Title:'),
					'name' => 'title',
                    'size' => 64,
                    'lang' => true,               
				),
                array(
					'type' => 'text',
					'label' => $this->l('Link:'),
					'name' => 'url',
                    'size' => 64,
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
			),
            'buttons' => array(
                array(
                    'type' => 'submit',
                    'title'=> $this->l(' Save '),
                    'icon' => 'process-icon-save',
                    'class'=> 'pull-right'
                ),
            ),
			'submit' => array(
				'title' => $this->l('Save and stay'),
                'stay' => true
			),
		); 
        
        $id_st_banner = (int)Tools::getValue('id_st_banner');
        $id_st_banner_group = (int)Tools::getValue('id_st_banner_group');
		$banner = new StBannerClass($id_st_banner);
        
        $languages = Language::getLanguages(true);            
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        foreach ($languages as $lang)
        {
            $this->fields_form_banner[0]['form']['input']['image_multi_lang_'.$lang['id_lang']] = array(
                    'type' => 'file',
					'label' => $this->l('Image').' - '.$lang['name'].($default_lang == $lang['id_lang'] ? '('.$this->l('default language').')' : '').':',
					'name' => 'image_multi_lang_'.$lang['id_lang'],
                    'required'  => ($default_lang == $lang['id_lang']),
                );
        }
        if($banner->id)
        {
            $this->fields_form_banner[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_banner');
             foreach ($languages as $lang)
                if($banner->image_multi_lang[$lang['id_lang']])
                {
                    StBannerClass::fetchMediaServer($banner->image_multi_lang[$lang['id_lang']]);
                    $this->fields_form_banner[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'has_image_'.$lang['id_lang'], 'default_value'=>1);
                    $this->fields_form_banner[0]['form']['input']['image_multi_lang_'.$lang['id_lang']]['required'] = false;
                    $this->fields_form_banner[0]['form']['input']['image_multi_lang_'.$lang['id_lang']]['image'] = '<img src="'.$banner->image_multi_lang[$lang['id_lang']].'" width="200"/>';
                }
        }
        elseif($id_st_banner_group)
            $banner->id_st_banner_group = $id_st_banner_group;
            
        $this->fields_form_banner[0]['form']['input'][] = array(
			'type' => 'html',
            'id' => 'a_cancel',
			'label' => '',
			'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&id_st_banner_group='.$banner->id_st_banner_group.'&viewstbanner&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
		);
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestbanner';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($banner,"fields_form_banner"),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);        
        
		return $helper;
	}
    public static function showApplyTo($value,$row)
    {
	    if($value)
		   $result = isset(self::$location[$value]) ? self::$location[$value]['name'] : '';
        elseif($row['id_category'])
        {
            $category = new Category($row['id_category'],(int)Context::getContext()->language->id);
            if($category->id)
                $result = $category->name;
        }
        elseif($row['id_manufacturer'])
        {
            $id_lang = (int)Context::getContext()->language->id;
            $manufacturer = Manufacturer::getNameById((int)$row['id_manufacturer']);
    		$result = (string)$manufacturer;
        }
        else
        {
            $module = new StBanner();
            $result = $module->l('--');
        }
        return $result;
    }
	protected function initList()
	{
		$this->fields_list = array(
			'id_st_banner_group' => array(
				'title' => $this->l('Id'),
				'class' => 'fixed-width-md',
				'type' => 'text',
                'search' => false,
                'orderby' => false                
			),
			'name' => array(
				'title' => $this->l('Name'),
				'class' => '',
				'type' => 'text',
                'search' => false,
                'orderby' => false 
			),
			'location' => array(
				'title' => $this->l('Hook into'),
				'class' => '',
				'type' => 'text',
				'callback' => 'showApplyTo',
				'callback_object' => 'StBanner',
                'search' => false,
                'orderby' => false 
			),
            'active' => array(
				'title' => $this->l('Status'),
				'align' => 'center',
				'active' => 'groupstatus',
				'type' => 'bool',
				'class' => 'fixed-width-md',
                'search' => false,
                'orderby' => false 
            ),
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_st_banner_group';
		$helper->actions = array('view', 'edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstbannergroup&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add a group'),
		);

		$helper->title = $this->displayName;
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    public static function showBannerGroupName($value,$row)
    {
        $group = new StBannerGroup((int)$value);
        return $group->id ? $group->name : '-';
    }
    public static function showBannerImage($value,$row)
    {
        return '<img src="'.$value.'" width="200" />';
    }
	protected function initListBanner()
	{
		$this->fields_list = array(
			'id_st_banner' => array(
				'title' => $this->l('Id'),
				'class' => 'fixed-width-md',
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'id_st_banner_group' => array(
				'title' => $this->l('Banner group'),
				'class' => 'fixed-width-xxl',
				'type' => 'text',
				'callback' => 'showBannerGroupName',
				'callback_object' => 'StBanner',
                'search' => false,
                'orderby' => false
			),
            'image_multi_lang' => array(
				'title' => $this->l('Image'),
				'type' => 'text',
				'callback' => 'showBannerImage',
				'callback_object' => 'StBanner',
                'class' => 'fixed-width-xxl',
                'search' => false,
                'orderby' => false
            ),
            'position' => array(
				'title' => $this->l('Position'),
				'class' => 'fixed-width-lg',
				'position' => 'position',
				'align' => 'left',
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
				'title' => $this->l('Status'),
				'align' => 'center',
				'active' => 'bannerstatus',
				'type' => 'bool',
				'class' => 'fixed-width-xs',
                'search' => false,
                'orderby' => false
            ),
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_st_banner';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstbanner&id_st_banner_group='.(int)Tools::getValue('id_st_banner_group').'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add a banner')
		);
        $helper->toolbar_btn['edit'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&update'.$this->name.'&id_st_banner_group='.(int)Tools::getValue('id_st_banner_group').'&fr=view&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Edit group'),
		);
		$helper->toolbar_btn['back'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Back to list')
		);

		$helper->title = $this->displayName;
		$helper->table = $this->name;
		$helper->orderBy = 'position';
		$helper->orderWay = 'ASC';
	    $helper->position_identifier = 'id_st_banner';
        
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}

    private function _prepareHook($identify,$type=1)
    {
        $group = StBannerGroup::getBannerGroup($identify,$type);
        if(!is_array($group) || !count($group))
            return false;
        foreach($group as &$v)
        {
             $banner = StBannerClass::getAll($v['id_st_banner_group'],$this->context->language->id,1);
             if(is_array($banner) && $banner_nbr=count($banner))
                $v['banner'] = $banner;
        }
	    $this->smarty->assign(array(
            'group' => $group,
            'layout' => self::$_layout,
        ));
        return true;
    }
    public function hookDisplayHeader($params)
    {
        if (!$this->isCached('header.tpl', $this->getCacheId()))
        {
            $custom_css_arr = StBannerGroup::getOptions();
            if (is_array($custom_css_arr) && count($custom_css_arr)) {
                $custom_css = '';
                foreach ($custom_css_arr as $v) {
                    $classname = ($v['location']==6 || $v['location']==18) ? '#banner_container_'.$v['id_st_banner_group'] : '#banner_'.$v['id_st_banner_group'];
                    
                    if(isset($v['top_spacing']) && ($v['top_spacing'] || $v['top_spacing']==='0'))
                        $custom_css .= $classname.'{margin-top:'.(int)$v['top_spacing'].'px;}';
                    if(isset($v['bottom_spacing']) && ($v['bottom_spacing'] || $v['bottom_spacing']==='0'))
                        $custom_css .= $classname.'{margin-bottom:'.(int)$v['bottom_spacing'].'px;}';
                }
                if($custom_css)
                    $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
            }
        }
        
        return $this->display(__FILE__, 'header.tpl', $this->getCacheId());
    }
	public function hookDisplayHome($params)
	{
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(1)))
            if(!$this->_prepareHook(1))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(1));
	}
    
	public function hookDisplayHomeTop($params)
	{
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(2)))
    		if(!$this->_prepareHook(2))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(2));
	}
    
	public function hookDisplayHomeBottom($params)
	{
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(3)))
    		if(!$this->_prepareHook(3))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(3));
	}

    public function hookDisplayHomeSecondaryLeft($params)
    {
        if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(4)))
            if(!$this->_prepareHook(4))
                return false;
        return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(4));
    }

    public function hookDisplayHomeSecondaryRight($params)
    {
        if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(5)))
            if(!$this->_prepareHook(5))
                return false;
        return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(5));
    }
    
	public function hookDisplayHomeTertiaryLeft($params)
	{
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(19)))
    		if(!$this->_prepareHook(19))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(19));
	}

    public function hookDisplayHomeTertiaryRight($params)
    {
        if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(20)))
            if(!$this->_prepareHook(20))
                return false;
        return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(20));
    }
    
	public function hookDisplayHomeVeryBottom($params)
	{
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(18)))
    		if(!$this->_prepareHook(18))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(18));
	}
    
	public function hookDisplayTopSecondary($params)
	{
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(6)))
            if(!$this->_prepareHook(6))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(6));
	}
    
	public function hookDisplayLeftColumn($params)
	{
		if (!$this->isCached('stbanner-column.tpl', $this->stGetCacheId(7)))
            if(!$this->_prepareHook(7))
                return false;
		return $this->display(__FILE__, 'stbanner-column.tpl', $this->stGetCacheId(7));
	}
	public function hookDisplayRightColumn($params)
	{
		if (!$this->isCached('stbanner-column.tpl', $this->stGetCacheId(8)))
            if(!$this->_prepareHook(8))
                return false;
		return $this->display(__FILE__, 'stbanner-column.tpl', $this->stGetCacheId(8));
	}
    
	public function hookDisplayFooter($params)
	{
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(9)))
            if(!$this->_prepareHook(9))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(9));
	}
    
    public function hookDisplayFooterTop($params)
    {
        if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(10)))
            if(!$this->_prepareHook(10))
                return false;
        return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(10));
    }
 
	public function hookDisplayBanner($params)
	{
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(21)))
            if(!$this->_prepareHook(21))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(21));
	}
 
    public function hookDisplayCategoryHeader($params)
    {
        $id_category = (int)Tools::getValue('id_category');
        if(!$id_category)
            return false;
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId($id_category,'category','stbanner')))
            if(!$this->_prepareHook($id_category,2))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId($id_category,'category','stbanner'));
    }
    
    public function hookDisplayManufacturerHeader($params)
    {
        $id_manufacturer = (int)Tools::getValue('id_manufacturer');
        if(!$id_manufacturer)
            return false;
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId($id_manufacturer,'manufacturer','stbanner')))
            if(!$this->_prepareHook($id_manufacturer,3))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId($id_manufacturer,'manufacturer','stbanner'));
    }
    
	public function hookDisplayAnywhere($params)
	{
	    if(!isset($params['caller']) || $params['caller']!=$this->name)
            return false;
	   if(isset($params['function']) && method_exists($this,$params['function']))
        {
            if($params['function']=='displayByBannerId')
                return call_user_func_array(array($this,$params['function']),array($params['identify']));
            else
                return false;
        }
        return false;
    }
    public function displayByBannerId($identify)
    {
        if(!Validate::isInt($identify))
            return false;
            
        $group_obj = new StBannerGroup($identify);
        if(!$group_obj->id || !$group_obj->active)
            return false;
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId($group_obj->id,'id')))
        {
            $group = $group_obj->getFields();
            if(is_array($group) && count($group))
            {
                 $banner = StBannerClass::getAll($group['id_st_banner_group'],$this->context->language->id,1);
                 if(is_array($banner) && count($banner))
                    $group['banner'] = $banner;
    		    $this->smarty->assign(array(
                    'group' => array($group),
                    'layout' => self::$_layout       
                ));
            }
        }
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId($group_obj->id,'id'));
    }
    public function hookActionObjectCategoryDeleteAfter($params)
    {
        if(!$params['object']->id)
            return ;
        
        $group = StBannerGroup::getBannerGroup($params['object']->id,2);
        return $this->deletePatch($group);
    }
    
    public function hookActionObjectManufacturerDeleteAfter($params)
    {
        if(!$params['object']->id)
            return ;
        
        $group = StBannerGroup::getBannerGroup($params['object']->id,3);
        
        return $this->deletePatch($group);
    }
    
    private function deletePatch($group)
    {
        if(!is_array($group) || !count($group))
            return ;
        $res = true;
        foreach($group as $v)
        {
            $group = new StBannerGroup($v['id_st_banner_group']);
            $res &= $group->delete();
        }
        
        return $res;
    }
    
	public function hookDisplayStBlogHome($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(11)))
    		if(!$this->_prepareHook(11))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(11));
	}
    
	public function hookDisplayStBlogHomeTop($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(12)))
    		if(!$this->_prepareHook(12))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(12));
	}
    
	public function hookDisplayStBlogHomeBottom($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(13)))
    		if(!$this->_prepareHook(13))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(13));
	}
    
	public function hookDisplayStBlogLeftColumn($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stbanner-column.tpl', $this->stGetCacheId(14)))
            if(!$this->_prepareHook(14))
                return false;
		return $this->display(__FILE__, 'stbanner-column.tpl', $this->stGetCacheId(14));
	}
	public function hookDisplayStBlogRightColumn($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stbanner-column.tpl', $this->stGetCacheId(15)))
            if(!$this->_prepareHook(15))
                return false;
		return $this->display(__FILE__, 'stbanner-column.tpl', $this->stGetCacheId(15));
	}
    
    public function hookDisplayCategoryFooter($params)
    {
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(17)))
            if(!$this->_prepareHook(17))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(17));
    }
    
    public function hookDisplayFooterProduct($params)
    {
		if (!$this->isCached('stbanner.tpl', $this->stGetCacheId(16)))
            if(!$this->_prepareHook(16))
                return false;
		return $this->display(__FILE__, 'stbanner.tpl', $this->stGetCacheId(16));
    }
    
	protected function stGetCacheId($key,$type='location',$name = null)
	{
		$cache_id = parent::getCacheId($name);
		return $cache_id.'_'.$key.'_'.$type;
	}
	private function clearBannerCache()
	{
		$this->_clearCache('*');
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
        
    public function BuildRadioUI($array, $name, $checked_value = 0)
    {
        $html = '';
        foreach($array AS $key => $value)
        {
            $html .= '<label><input type="radio"'.($checked_value==$value['value'] ? ' checked="checked"' : '').' value="'.$value['value'].'" id="'.(isset($value['id']) ? $value['id'] : $name.'_'.$value['value']).'" name="'.$name.'">'.(isset($value['label'])?$value['label']:'').'</label>';
            if (($key+1) % 8 == 0)
                $html .= '<br />';
        }
        return $html;
    }
    
    public function processUpdatePositions()
	{
		if (Tools::getValue('action') == 'updatePositions' && Tools::getValue('ajax'))
		{
			$way = (int)(Tools::getValue('way'));
			$id = (int)(Tools::getValue('id'));
			$positions = Tools::getValue('st_banner');
            $msg = '';
			if (is_array($positions))
				foreach ($positions as $position => $value)
				{
					$pos = explode('_', $value);

					if ((isset($pos[2])) && ((int)$pos[2] === $id))
					{
						if ($object = new StBannerClass((int)$pos[2]))
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