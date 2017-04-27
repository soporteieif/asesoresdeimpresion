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

include_once dirname(__FILE__).'/StAdvancedBannerClass.php';
include_once dirname(__FILE__).'/StAdvancedBannerGroup.php';
include_once dirname(__FILE__).'/StAdvancedBannerFontClass.php';

class StAdvancedBanner extends Module
{
    protected static $access_rights = 0775;

    public static $location = array(
        23 => array('id' =>23 , 'name' => 'Full width top boxed', 'full_width' => 1, 'auto_height' => 1),
        26 => array('id' =>26 , 'name' => 'Full width top', 'stretched' => 1, 'full_width' => 1, 'auto_height' => 1),
        28 => array('id' =>28 , 'name' => 'Full width top 2 boxed', 'full_width' => 1, 'auto_height' => 1),
        29 => array('id' =>29 , 'name' => 'Full width top 2', 'stretched' => 1, 'full_width' => 1, 'auto_height' => 1),
        22 => array('id' =>22 , 'name' => 'Top column', 'auto_height' => 1),
        1 => array('id' =>1 , 'name' => 'Homepage', 'auto_height' => 2),
        2 => array('id' =>2 , 'name' => 'Homepage top', 'auto_height' => 2),
        3 => array('id' =>3 , 'name' => 'Homepage bottom', 'auto_height' => 2),
        4 => array('id' =>4 , 'name' => 'Homepage secondary left', 'auto_height' => 0),
        5 => array('id' =>5 , 'name' => 'Homepage secondary right', 'auto_height' => 0),
        19 => array('id' =>19 , 'name' => 'Homepage tertiary left', 'auto_height' => 0),
        20 => array('id' =>20 , 'name' => 'Homepage tertiary right', 'auto_height' => 0),
        18 => array('id' =>18 , 'name' => 'Bottom column', 'auto_height' => 1),
        24 => array('id' =>24 , 'name' => 'Full width bottom boxed(Home very bottom)', 'full_width' => 1, 'auto_height' => 1),
        27 => array('id' =>27 , 'name' => 'Full width bottom(Home very bottom)', 'stretched' => 1, 'full_width' => 1, 'auto_height' => 1),
        7 => array('id' =>7 , 'name' => 'Left column', 'auto_height' => 0, 'column'=>1),
        8 => array('id' =>8 , 'name' => 'Right column', 'auto_height' => 0, 'column'=>1),
        /*
        9 => array('id' =>9 , 'name' => 'Footer'),
        10 => array('id' =>10, 'name' => 'Footer top'),
        */
        16 => array('id' =>16 , 'name' => 'At bottom of prodcut page', 'auto_height' => 2),
        17 => array('id' =>17 , 'name' => 'At bottom of category page', 'auto_height' => 2),
        11 => array('id' =>11 , 'name' => 'Blog homepage', 'auto_height' => 2),
        12 => array('id' =>12 , 'name' => 'Blog homepage top', 'auto_height' => 2),
        13 => array('id' =>13 , 'name' => 'Blog homepage bottom', 'auto_height' => 2),
        14 => array('id' =>14 , 'name' => 'Blog left column', 'auto_height' => 0, 'column'=>1),
        15 => array('id' =>15 , 'name' => 'Blog right column', 'auto_height' => 0, 'column'=>1),
        21 => array('id' =>21 , 'name' => 'Most top of the page', 'auto_height' => 1),
        25 => array('id' =>25 , 'name' => 'Product secondary column'),
    );
    public static $text_position = array(
        array('id' =>'center' , 'name' => 'Middle'),
        array('id' =>'bottom' , 'name' => 'Bottom'),
        array('id' =>'top' , 'name' => 'Top'),
    );
    public  $fields_list;
    public  $fields_list_group;
    public  $fields_value;
    public  $fields_form;
    public  $fields_form_banner;
    public  $fields_form_column;
	private $_html = '';
	private $spacer_size = '5';

    private $googleFonts;

	public function __construct()
	{
		$this->name          = 'stadvancedbanner';
		$this->tab           = 'front_office_features';
		$this->version       = '1.8.5';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
        $this->bootstrap     = true;

		parent::__construct();
        $this->googleFonts = include_once(dirname(__FILE__).'/googlefonts.php');

		$this->displayName   = $this->l('Advanced banner');
		$this->description   = $this->l('This module was made to easy upload banners in your shop.');
	}

	public function install()
	{
		$res = parent::install() &&
			$this->installDB() &&
            $this->registerHook('displayHeader') &&
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
			$this->registerHook('displayAnywhere') &&
			$this->registerHook('actionObjectCategoryDeleteAfter') &&
            $this->registerHook('actionObjectManufacturerDeleteAfter') &&
            $this->registerHook('actionShopDataDuplication') &&
			$this->registerHook('displayStBlogHome') &&
			$this->registerHook('displayStBlogHomeTop') &&
			$this->registerHook('displayStBlogHomeBottom') &&
			$this->registerHook('displayStBlogLeftColumn') &&
            $this->registerHook('displayStBlogRightColumn') &&
            $this->registerHook('displayHomeVeryBottom') &&
            $this->registerHook('displayHomeTertiaryLeft') &&
            $this->registerHook('displayHomeTertiaryRight')&&
			$this->registerHook('displayBanner') &&
            $this->registerHook('displayTopColumn') &&
            $this->registerHook('displayFullWidthTop') &&
            $this->registerHook('displayFullWidthTop2') &&
            $this->registerHook('displayBottomColumn') &&
            $this->registerHook('displayFooterProduct') &&
            $this->registerHook('displayProductSecondaryColumn');
		if ($res)
			foreach(Shop::getShops(false) as $shop)
				$res &= $this->sampleData($shop['id_shop']);
        $this->clearBannerCache();
        return $res;
	}

	/**
	 * Creates tables
	 */
	public function installDB()
	{
		/* Banners */
		$return = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_advanced_banner` (
				`id_st_advanced_banner` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_st_advanced_banner_group` int(10) unsigned NOT NULL,
                `id_currency` int(10) unsigned DEFAULT 0,
                `new_window` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `position` int(10) unsigned NOT NULL DEFAULT 0,
                `description_color` varchar(7) DEFAULT NULL,
                `hide_text_on_mobile` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `text_position` varchar(32) DEFAULT NULL,
                `text_align` tinyint(1) unsigned NOT NULL DEFAULT 2,
                `bg_color` varchar(7) DEFAULT NULL,
                `btn_color` varchar(7) DEFAULT NULL,
                `btn_bg` varchar(7) DEFAULT NULL,
                `btn_hover_color` varchar(7) DEFAULT NULL,
                `btn_hover_bg` varchar(7) DEFAULT NULL,
                `text_width` tinyint(2) unsigned NOT NULL DEFAULT 0,
				PRIMARY KEY (`id_st_advanced_banner`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		/* Banners lang configuration */
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_advanced_banner_lang` (
				`id_st_advanced_banner` int(10) UNSIGNED NOT NULL,
				`id_lang` int(10) unsigned NOT NULL ,
    			`url` varchar(255) DEFAULT NULL,
                `title` varchar(255) DEFAULT NULL,
                `description` text,
                `image_multi_lang` varchar(255) DEFAULT NULL,
                `width` int(10) unsigned NOT NULL DEFAULT 0,
                `height` int(10) unsigned NOT NULL DEFAULT 0,
				PRIMARY KEY (`id_st_advanced_banner`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_advanced_banner_font` (
                `id_st_advanced_banner` int(10) unsigned NOT NULL,
                `font_name` varchar(255) NOT NULL
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		/* Banners group */
		$return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_advanced_banner_group` (
				`id_st_advanced_banner_group` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_parent` int(10) NOT NULL DEFAULT 0,
                `name` varchar(255) DEFAULT NULL,
                `location` int(10) unsigned NOT NULL DEFAULT 0,
                `id_category` int(10) unsigned NOT NULL DEFAULT 0,
                `id_manufacturer` int(10) unsigned NOT NULL DEFAULT 0,
                `id_cms` int(10) unsigned NOT NULL DEFAULT 0,
                `id_cms_category` int(10) unsigned NOT NULL DEFAULT 0,
                `hide_on_mobile` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `hover_effect` tinyint(2) unsigned NOT NULL DEFAULT 1,
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `position` int(10) unsigned NOT NULL DEFAULT 0,
                `width` tinyint(2) unsigned NOT NULL DEFAULT 4,
                `height` int(10) unsigned NOT NULL DEFAULT 0,
                `padding` varchar(10) DEFAULT NULL,
                `top_spacing` varchar(10) DEFAULT NULL,
                `bottom_spacing` varchar(10) DEFAULT NULL,
				`show_on_sub` tinyint(1) unsigned NOT NULL DEFAULT 1,				
                `style` tinyint(1) unsigned NOT NULL DEFAULT 0,				
                PRIMARY KEY (`id_st_advanced_banner_group`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		/* Banners group shop */
		$return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_advanced_banner_group_shop` (
				`id_st_advanced_banner_group` int(10) UNSIGNED NOT NULL,
                `id_shop` int(11) NOT NULL,
                PRIMARY KEY (`id_st_advanced_banner_group`,`id_shop`),
                KEY `id_shop` (`id_shop`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		return $return;
	}

    public function sampleData($id_shop)
    {
        $return = true;
        $path = _MODULE_DIR_.$this->name;
		$samples = array(
			0 => array(
                'id_st_advanced_banner_group' => 0,
                'name' => 'Homepage banner',
                'id_parent' => 0,
                'location' => 2,
                'hide_on_mobile' => 0,
                'hover_effect' => 4,
                'width' => 0,
                'height' => 278,
            ),
            1 => array(
                'sample_pid' => 0,
                'id_st_advanced_banner_group' => 0,
                'name' => 'Left column',
                'id_parent' => '',
                'location' => 0,
                'hide_on_mobile' => 0,
                'hover_effect' => 0,
                'width' => 4,
                'height' => 100,
                'child' => array(
                    array(
                        'description_color' => '',
                        'text_position' => 'center',
                        'text_align' => 2,
                        'url' => '',
                        'description' => '',
                        'image_multi_lang' => $path.'/views/img/sample_1.jpg',
                        'width' => 377,
                        'height' => 278,
                    ),
                ),
            ),
            2 => array(
                'sample_pid' => 0,
                'id_st_advanced_banner_group' => 0,
                'name' => 'Center column',
                'id_parent' => '',
                'location' => 0,
                'hide_on_mobile' => 0,
                'hover_effect' => 0,
                'width' => 4,
                'height' => 100,
                'child' => array(
                    array(
                        'description_color' => '',
                        'text_position' => 'center',
                        'text_align' => 2,
                        'url' => '',
                        'description' => '',
                        'image_multi_lang' => $path.'/views/img/sample_2.jpg',
                        'width' => 377,
                        'height' => 278,
                    ),
                ),
            ),
            3 => array(
                'sample_pid' => 0,
                'id_st_advanced_banner_group' => 0,
                'name' => 'Right column',
                'id_parent' => '',
                'location' => 0,
                'hide_on_mobile' => 0,
                'hover_effect' => 0,
                'width' => 4,
                'height' => 100,
                'child' => array(
                    array(
                        'description_color' => '',
                        'text_position' => 'center',
                        'text_align' => 2,
                        'url' => '',
                        'description' => '',
                        'image_multi_lang' => $path.'/views/img/sample_3.jpg',
                        'width' => 377,
                        'height' => 278,
                    ),
                ),
            ),
            4 => array(
                'id_st_advanced_banner_group' => 0,
                'name' => 'Homepage secondary right',
                'id_parent' => 0,
                'location' => 5,
                'hide_on_mobile' => 0,
                'hover_effect' => 4,
                'width' => 0,
                'height' => 400,
            ),
            5 => array(
                'sample_pid' => 4,
                'id_st_advanced_banner_group' => 0,
                'name' => 'Column A',
                'id_parent' => '',
                'location' => 0,
                'hide_on_mobile' => 0,
                'hover_effect' => 0,
                'width' => 12,
                'height' => 100,
                'child' => array(
                    array(
                        'description_color' => '',
                        'text_position' => 'center',
                        'text_align' => 2,
                        'url' => '',
                        'description' => '',
                        'image_multi_lang' => $path.'/views/img/sample_4.jpg',
                        'width' => 280,
                        'height' => 400,
                    ),
                ),
            ),
		);
		foreach($samples as $k=>&$sample)
		{
			$module = new StAdvancedBannerGroup();
			$module->name = $sample['name'];
            if(!isset($sample['sample_pid']))
                $id_parent = 0;
            else
                $id_parent = $samples[$sample['sample_pid']]['id_st_advanced_banner_group'];
            $module->id_parent = (int)$id_parent;
			$module->location = $sample['location'];
			$module->hide_on_mobile = $sample['hide_on_mobile'];
            $module->hover_effect = $sample['hover_effect'];
            $module->width = $sample['width'];
			$module->height = $sample['height'];
			$module->active = 1;
			$module->position = $k;
			$return &= $module->add();
            //
            if($return && $module->id)
            {
                $sample['id_st_advanced_banner_group'] = $module->id;
    			Db::getInstance()->insert('st_advanced_banner_group_shop', array(
    				'id_st_advanced_banner_group' => (int)$module->id,
    				'id_shop' => (int)$id_shop,
    			));
            }
		}

        foreach($samples as $sp)
		{
            if(!$sp['id_st_advanced_banner_group'] || !isset($sp['child']) || !count($sp['child']))
                continue;
		    foreach($sp['child'] as $k=>$v)
    		{
    			$module = new StAdvancedBannerClass();
                $module->id_st_advanced_banner_group = $sp['id_st_advanced_banner_group'];
                $module->description_color = $v['description_color'];
                $module->text_position = $v['text_position'];
    			$module->text_align = $v['text_align'];
    			$module->active = 1;
    			$module->position = $k;

    			foreach (Language::getLanguages(false) as $lang)
                {
                    $module->url[$lang['id_lang']] = $v['url'];
    				$module->description[$lang['id_lang']] = $v['description'];
                    $module->image_multi_lang[$lang['id_lang']] = $v['image_multi_lang'];
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
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_advanced_banner`,`'._DB_PREFIX_.'st_advanced_banner_lang`,`'._DB_PREFIX_.'st_advanced_banner_font`,`'._DB_PREFIX_.'st_advanced_banner_group`,`'._DB_PREFIX_.'st_advanced_banner_group_shop`');
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
        $this->context->controller->addJS(($this->_path).'views/js/admin.js');
        $this->_html .= '<script type="text/javascript">var googleFontsString=\''.Tools::jsonEncode($this->googleFonts).'\';</script>';

        $this->_html .= '<script type="text/javascript">var googleFontsString=\''.Tools::jsonEncode($this->googleFonts).'\';</script>';

        $id_st_advanced_banner_group = (int)Tools::getValue('id_st_advanced_banner_group');
        $id_st_advanced_banner = (int)Tools::getValue('id_st_advanced_banner');
	    if ((Tools::isSubmit('groupstatusstadvancedbanner')))
        {
            $group = new StAdvancedBannerGroup((int)$id_st_advanced_banner_group);
            if($group->id && $group->toggleStatus())
            {
                $this->clearBannerCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner_group='.$group->id_parent.'&viewstadvancedbanner&token='.Tools::getAdminTokenLite('AdminModules'));
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));
            }
            elseif($id_st_advanced_banner)
            {
                $banner = new StAdvancedBannerClass($id_st_advanced_banner);
                if ($banner->id && $banner->toggleStatus())
                {
                    $this->clearBannerCache();
                    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner_group='.$banner->id_st_advanced_banner_group.'&viewstadvancedbanner&token='.Tools::getAdminTokenLite('AdminModules'));
                }
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
	    if ((Tools::isSubmit('bannerstatusstadvancedbanner')))
        {
            $banner = new StAdvancedBannerClass((int)$id_st_advanced_banner);
            if($banner->id && $banner->toggleStatus())
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));
                $this->clearBannerCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner_group='.$banner->id_st_advanced_banner_group.'&viewstadvancedbanner&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
        if ((Tools::isSubmit('bannerdeletestadvancedbanner')))
        {
            $banner = new StAdvancedBannerClass((int)$id_st_advanced_banner);
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
                    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner_group='.$banner->id_st_advanced_banner_group.'&viewstadvancedbanner&conf=7&token='.Tools::getAdminTokenLite('AdminModules'));
                }else
                    $this->_html .= $this->displayError($this->l('An error occurred while delete banner.'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while delete banner.'));
        }
        if (Tools::isSubmit('copystadvancedbanner'))
        {
            if($this->processCopyAdvancedBannerGroup($id_st_advanced_banner_group))
            {
                $this->clearBannerCache();
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=19&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while copy banner.'));
        }
        if (Tools::isSubmit('way') && Tools::isSubmit('id_st_advanced_banner') && (Tools::isSubmit('position')))
		{
		    $banner = new StAdvancedBannerClass((int)$id_st_advanced_banner);
            if($banner->id && $banner->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position')))
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));
                $this->clearBannerCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner_group='.$banner->id_st_advanced_banner_group.'&viewstadvancedbanner&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            else
                $this->_html .= $this->displayError($this->l('Failed to update the position.'));
		}
        if (Tools::getValue('action') == 'updatePositions')
        {
            $this->processUpdatePositions();
        }
        if (isset($_POST['savestadvancedbannergroup']) || isset($_POST['savestadvancedbannergroupAndStay']))
        {
            if ($id_st_advanced_banner_group)
                $group = new StAdvancedBannerGroup((int)$id_st_advanced_banner_group);
            else
                $group = new StAdvancedBannerGroup();

            $error = array();
            $group->copyFromPost();

            if(!$group->name)
                $error[] = $this->displayError($this->l('The field "Group name" is required'));

            if($group->location)
            {
                $item_arr = explode('-',$group->location);
                if(count($item_arr)==2)
                {
                    $group->id_category = 0;
                    $group->location = 0;
                    $group->id_manufacturer = 0;
                    $group->id_cms = 0;
                    if($item_arr[0]==1)
                        $group->location = (int)$item_arr[1];
                    elseif($item_arr[0]==2)
                        $group->id_category = (int)$item_arr[1];
                    elseif($item_arr[0]==3)
                        $group->id_manufacturer = (int)$item_arr[1];
                    elseif($item_arr[0]==4)
                        $group->id_cms = (int)$item_arr[1];
                }
            }

            $group->id_parent = 0;
            if (!count($error) && $group->validateFields(false))
            {
                if($group->save())
                {
                    Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'st_advanced_banner_group_shop WHERE id_st_advanced_banner_group='.(int)$group->id);
                    if (!Shop::isFeatureActive())
                    {
                        Db::getInstance()->insert('st_advanced_banner_group_shop', array(
                            'id_st_advanced_banner_group' => (int)$group->id,
                            'id_shop' => (int)Context::getContext()->shop->id,
                        ));
                    }
                    else
                    {
                        $assos_shop = Tools::getValue('checkBoxShopAsso_st_advanced_banner_group');
                        if (empty($assos_shop))
                            $assos_shop[(int)Context::getContext()->shop->id] = Context::getContext()->shop->id;
                        foreach ($assos_shop as $id_shop => $row)
                            Db::getInstance()->insert('st_advanced_banner_group_shop', array(
                                'id_st_advanced_banner_group' => (int)$group->id,
                                'id_shop' => (int)$id_shop,
                            ));
                    }
                    $this->clearBannerCache();
                    if(isset($_POST['savestadvancedbannergroupAndStay']) || Tools::getValue('fr') == 'view')
                    {
                        $rd_str = isset($_POST['savestadvancedbannergroupAndStay']) && Tools::getValue('fr') == 'view' ? 'fr=view&update' : (isset($_POST['savestadvancedbannergroupAndStay']) ? 'update' : 'view');
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner_group='.$group->id.'&conf='.($id_st_advanced_banner_group?4:3).'&'.$rd_str.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                    }
                    else
                        $this->_html .= $this->displayConfirmation($this->l('Banner group').' '.($id_st_advanced_banner_group ? $this->l('updated') : $this->l('added')));
                }
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during banner group').' '.($id_st_advanced_banner_group ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
            $this->clearBannerCache();
        }
		if (isset($_POST['savestadvancedbannercolumn']) || isset($_POST['savestadvancedbannercolumnAndStay']))
		{
            if ($id_st_advanced_banner_group)
				$group = new StAdvancedBannerGroup((int)$id_st_advanced_banner_group);
			else
				$group = new StAdvancedBannerGroup();

            $error = array();
    		$group->copyFromPost();

            if(!$group->name)
                $error[] = $this->displayError($this->l('The field "Banner group name" is required'));

            if(!$group->id_parent)
                $error[] = $this->displayError($this->l('The field "Parent" is required'));

			if (!count($error) && $group->validateFields(false))
            {
                if($group->save())
                {
                    $this->clearBannerCache();
                    if(isset($_POST['savestadvancedbannercolumnAndStay']) || Tools::getValue('fr') == 'view')
                    {
                        $rd_str = isset($_POST['savestadvancedbannercolumnAndStay']) && Tools::getValue('fr') == 'view' ? 'fr=view&update' : (isset($_POST['savestadvancedbannercolumnAndStay']) ? 'update' : 'view');
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner_group='.$group->id.'&conf='.($id_st_advanced_banner_group?4:3).'&'.$rd_str.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                    }
                    else
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner_group='.$group->id_parent.'&viewstadvancedbanner&token='.Tools::getAdminTokenLite('AdminModules'));
                }
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during column').' '.($id_st_advanced_banner_group ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
            $this->clearBannerCache();
        }
		if (isset($_POST['savestadvancedbanner']) || isset($_POST['savestadvancedbannerAndStay']))
		{
            if ($id_st_advanced_banner)
				$banner = new StAdvancedBannerClass((int)$id_st_advanced_banner);
			else
				$banner = new StAdvancedBannerClass();
            /**/

            $error = array();

		    $banner->copyFromPost();
            if(!$banner->id_st_advanced_banner_group)
                $error[] = $this->displayError($this->l('The field "Banner group" is required'));
            else
            {
                $languages = Language::getLanguages(false);
                $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

                $res = $this->stUploadImage('image_multi_lang_'.$default_lang);
                if(count($res['error']))
                    $error = array_merge($error,$res['error']);
                elseif($res['image'])
                {
                    $banner->image_multi_lang[$default_lang] = $res['image'];
                    $banner->width[$default_lang] = $res['width'];
                    $banner->height[$default_lang] = $res['height'];
                }

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
                    elseif(!Tools::isSubmit('has_image_'.$lang['id_lang']) && !$res['image'] && $banner->image_multi_lang[$default_lang])
                    {
                        $banner->image_multi_lang[$lang['id_lang']] = $banner->image_multi_lang[$default_lang];
                        $banner->width[$lang['id_lang']] = $banner->width[$default_lang];
                        $banner->height[$lang['id_lang']] = $banner->height[$default_lang];
                    }
                }
            }

			if (!count($error) && $banner->validateFields(false) && $banner->validateFieldsLang(false))
            {
                /*position*/
                $banner->position = $banner->checkPosition();
                if($banner->save())
                {
                    $jon = trim(Tools::getValue('google_font_name'),'¤');
                    StAdvancedBannerFontClass::deleteBySlider($banner->id);
                    $jon_arr = array_unique(explode('¤', $jon));
                    if (count($jon_arr))
                        StAdvancedBannerFontClass::changeSliderFont($banner->id, $jon_arr);

                    $this->clearBannerCache();
                    //$this->_html .= $this->displayConfirmation($this->l('Banner').' '.($id_st_advanced_banner ? $this->l('updated') : $this->l('added')));
			        if(isset($_POST['savestadvancedbannerAndStay']))
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner='.$banner->id.'&conf='.($id_st_advanced_banner?4:3).'&update'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                    else
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner_group='.$banner->id_st_advanced_banner_group.'&viewstadvancedbanner&token='.Tools::getAdminTokenLite('AdminModules'));
                }
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during banner').' '.($id_st_advanced_banner ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }

		if (Tools::isSubmit('addstadvancedbannergroup') || Tools::isSubmit('addstadvancedbannercolumn') || (Tools::isSubmit('updatestadvancedbanner') && $id_st_advanced_banner_group))
		{
            if($id_st_advanced_banner_group)
                $group = new StAdvancedBannerGroup($id_st_advanced_banner_group);
            if(Tools::isSubmit('addstadvancedbannergroup') || (isset($group) && $group->id_parent==0))
            {
                $helper = $this->initForm();
                return $helper->generateForm($this->fields_form);
            }

            if(Tools::isSubmit('addstadvancedbannercolumn') || (isset($group) && $group->id_parent))
            {
                $helper = $this->initFormColumn();
                return $helper->generateForm($this->fields_form_column);
            }
		}
        elseif(Tools::isSubmit('addstadvancedbanner') || (Tools::isSubmit('updatestadvancedbanner') && $id_st_advanced_banner))
        {
            $helper = $this->initFormBanner();
            return $this->_html.$helper->generateForm($this->fields_form_banner);
        }
        elseif(Tools::isSubmit('viewstadvancedbanner'))
        {
            $this->_html .= '<script type="text/javascript">var currentIndex="'.AdminController::$currentIndex.'&configure='.$this->name.'";</script>';
			$group = new StAdvancedBannerGroup($id_st_advanced_banner_group);
            if($group->id_parent==0 && !$group->isAssociatedToShop())
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));

            if(!$group->hasBanner())
            {
                $helper_group = $this->initListColumn();
                $this->_html .= $helper_group->generateList(StAdvancedBannerGroup::recurseTree($group->id,0,0,0), $this->fields_list_group);
            }

            if(!$group->hasColumn())
            {
                $helper = $this->initListBanner();
                $this->_html .= $this->displayConfirmation($this->l('Each column should have one banner, and only one.')).$helper->generateList(StAdvancedBannerClass::getAll($id_st_advanced_banner_group,(int)$this->context->language->id), $this->fields_list);
            }

			return $this->_html;
        }
		else if (Tools::isSubmit('deletestadvancedbanner') && $id_st_advanced_banner)
		{
			$banner = new StAdvancedBannerClass($id_st_advanced_banner);
            $banner->delete();
            $this->clearBannerCache();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner_group='.$banner->id_st_advanced_banner_group.'&viewstadvancedbanner&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else if (Tools::isSubmit('deletestadvancedbanner') && $id_st_advanced_banner_group)
		{
			$group = new StAdvancedBannerGroup($id_st_advanced_banner_group);
            $group->delete();
            $this->clearBannerCache();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.($group->id_parent?'&viewstadvancedbanner&id_st_advanced_banner_group='.$group->id_parent:'').'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else
		{
			$helper = $this->initList();
			return $this->_html.$helper->generateList(StAdvancedBannerGroup::recurseTree(0,0,0,0), $this->fields_list);
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
            $name = str_replace(strrchr($_FILES[$item]['name'], '.'), '', $_FILES[$item]['name']);
			$imagesize = array();
			$imagesize = @getimagesize($_FILES[$item]['tmp_name']);
			if (!empty($imagesize) &&
				in_array(strtolower(substr(strrchr($imagesize['mime'], '/'), 1)), array('jpg', 'gif', 'jpeg', 'png')) &&
				in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
			{
                $this->_checkEnv();
				$temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
				$salt = $name ? Tools::str2url($name) : sha1(microtime());
                $c_name = $salt;
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
        $module = new StAdvancedBanner();
        $location = array();
        foreach(self::$location as $v)
            $location[] = array('id'=>'1-'.$v['id'],'name'=>$v['name']);

        $root_category = Category::getRootCategory();
        $category_arr = array();
        $module->getCategoryOption($category_arr,$root_category->id);
        //unset root category
        if(isset($category_arr[$root_category->id]))
            unset($category_arr[$root_category->id]);

        $cms_arr = array();
		$module->getCMSOptions($cms_arr, 0, 1);

        $manufacturer_arr = array();
		$manufacturers = Manufacturer::getManufacturers(false, Context::getContext()->language->id);
		foreach ($manufacturers as $manufacturer)
            $manufacturer_arr[] = array('id'=>'3-'.$manufacturer['id_manufacturer'],'name'=>$manufacturer['name']);

        return array(
            array('name'=>$module->l('Hook'),'query'=>$location),
            array('name'=>$module->l('Categories'),'query'=>$category_arr),
            array('name'=>$module->l('CMS'),'query'=>$cms_arr),
            array('name'=>$module->l('Manufacturers'),'query'=>$manufacturer_arr),
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

    private function getCMSCategories($recursive = false, $parent = 1, $id_lang = false)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $id_shop = (int)Context::getContext()->shop->id;

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

    protected function initForm()
    {
        $id_st_advanced_banner_group = (int)Tools::getValue('id_st_advanced_banner_group');
        $group = new StAdvancedBannerGroup($id_st_advanced_banner_group);

        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Group configuration'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Group name:'),
                    'name' => 'name',
                    'size' => 64,
                    'required'  => true,
                ),
                'location' => array(
                    'type' => 'select',
                    'label' => $this->l('Show on:'),
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
                    'type' => 'radio',
                    'label' => $this->l('Type:'),
                    'name' => 'style',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'style_0',
                            'value' => 0,
                            'label' => $this->l('Advanced. You can put text and buttons on banners. Images will be scaled to cover banner areas, which means some parts of image may not be visiable, the bright side is that you do not have to pay much attention to the dimensions of images.')
                        ),
                        array(
                            'id' => 'style_1',
                            'value' => 1,
                            'label' => $this->l('Simple. You can not put anything on banners, and banners might be uneven on mobile devices. All "Height" settings will be ignored. The dimensions of images are important, refer to the Documenation to learn how to get the correct dimensions.')
                        ),
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
                    'default_value' => 200,
                    'required' => true,
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => array(
                            $this->l('This field is required for advanced banners.'),
                            $this->l('The value of this field is used to equal the height of banners.'),
                        ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Space between banners:'),
                    'name' => 'padding',
                    'default_value' => '',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Leave it empty to use the default value.'),
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
                    'type' => 'radio',
                    'label' => $this->l('Hover effect:'),
                    'name' => 'hover_effect',
                    'default_value' => 4,
                    'values' => array(
                        array(
                            'id' => 'hover_effect_0',
                            'value' => 0,
                            'label' => $this->l('None')
                        ),
                        array(
                            'id' => 'hover_effect_1',
                            'value' => 1,
                            'label' => $this->l('Fade & scale')
                        ),
                        array(
                            'id' => 'hover_effect_2',
                            'value' => 2,
                            'label' => $this->l('White line')
                        ),
                        array(
                            'id' => 'hover_effect_3',
                            'value' => 3,
                            'label' => $this->l('White block')
                        ),
                        array(
                            'id' => 'hover_effect_4',
                            'value' => 4,
                            'label' => $this->l('Fade')
                        ),
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
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_advanced_banner_group');
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->id = (int)$group->id;
        $helper->table =  'st_advanced_banner_group';
        $helper->identifier = 'id_st_advanced_banner_group';
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->submit_action = 'savestadvancedbannergroup';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getFieldsValueSt($group),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        if($group->id)
            $helper->tpl_vars['fields_value']['location'] = $group->location ? '1-'.$group->location :
                ($group->id_category ? '2-'.$group->id_category :
                    ($group->id_cms ? '4-'.$group->id_cms : '3-'.$group->id_manufacturer));

        return $helper;
    }
	protected function initFormColumn()
	{
        $id_st_advanced_banner_group = (int)Tools::getValue('id_st_advanced_banner_group');
		$group = new StAdvancedBannerGroup($id_st_advanced_banner_group);

        if(Validate::isLoadedObject($group))
            $id_parent = $group->id_parent;

        if(!isset($id_parent))
        {
            if(Tools::getValue('id_parent'))
                $id_parent = (int)Tools::getValue('id_parent');
            else
                $id_parent = 0;
        }

		$this->fields_form_column[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Column configuration'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Column name:'),
					'name' => 'name',
                    'size' => 64,
                    'required'  => true,
				),
                array(
                    'type' => 'select',
                    'label' => $this->l('Width:'),
                    'name' => 'width',
                    'options' => array(
                        'query' => array(
                                array('id'=>1, 'name'=> '1/12'),
                                array('id'=>2, 'name'=> '2/12'),
                                array('id'=>3, 'name'=> '3/12'),
                                array('id'=>5, 'name'=> '5/12'),
                                array('id'=>6, 'name'=> '6/12'),
                                array('id'=>7, 'name'=> '7/12'),
                                array('id'=>8, 'name'=> '8/12'),
                                array('id'=>9, 'name'=> '9/12'),
                                array('id'=>10, 'name'=> '10/12'),
                                array('id'=>11, 'name'=> '11/12'),
                                array('id'=>12, 'name'=> '12/12'),
                            ),
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => 4,
                            'label' => '4/12',
                        ),
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Height:'),
                    'name' => 'height',
                    'options' => array(
                        'query' => array(
                                array('id'=>20, 'name'=> '20%'),
                                array('id'=>25, 'name'=> '25%'),
                                array('id'=>30, 'name'=> '30%'),
                                array('id'=>33, 'name'=> '33%'),
                                array('id'=>40, 'name'=> '40%'),
                                array('id'=>50, 'name'=> '50%'),
                                array('id'=>60, 'name'=> '60%'),
                                array('id'=>66, 'name'=> '66%'),
                                array('id'=>70, 'name'=> '70%'),
                                array('id'=>75, 'name'=> '75%'),
                                array('id'=>80, 'name'=> '80%'),
                            ),
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => 100,
                            'label' => '100%',
                        ),
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
                array(
					'type' => 'hidden',
					'name' => 'id_parent',
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

        $this->fields_form_column[0]['form']['input'][] = array(
			'type' => 'html',
            'id' => 'a_cancel',
			'label' => '',
            'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner_group='.$id_parent.'&viewstadvancedbanner&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',
		);

        if($group->id)
        {
            $this->fields_form_column[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_advanced_banner_group');
        }

        $helper = new HelperForm();
		$helper->show_toolbar = false;
        $helper->id = (int)$group->id;
		$helper->table =  'st_advanced_banner_group';
		$helper->identifier = 'id_st_advanced_banner_group';
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->submit_action = 'savestadvancedbannercolumn';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($group, "fields_form_column"),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

        $helper->tpl_vars['fields_value']['id_parent'] = (int)$id_parent;

		return $helper;
	}
	protected function initFormBanner()
	{
        $id_st_advanced_banner = (int)Tools::getValue('id_st_advanced_banner');
        $banner = new StAdvancedBannerClass($id_st_advanced_banner);
        if(Validate::isLoadedObject($banner))
        {
            $group = new StAdvancedBannerGroup($banner->id_st_advanced_banner_group);
        }
        elseif ($id_st_advanced_banner_group = (int)Tools::getValue('id_st_advanced_banner_group')) {
            $group = new StAdvancedBannerGroup($id_st_advanced_banner_group);
        }

        if(!Validate::isLoadedObject($group))
            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));

        $id_parent = $group->id_parent;

        $google_font_name_html = $google_font_name =  $google_font_link = '';
        if(Validate::isLoadedObject($banner)){
            $jon_arr = StAdvancedBannerFontClass::getBySlider($banner->id);
            if(is_array($jon_arr) && count($jon_arr))
                foreach ($jon_arr as $key => $value) {
                    $google_font_name_html .= '<li id="#'.str_replace(' ', '_', strtolower($value['font_name'])).'_li" class="form-control-static"><button type="button" class="delGoogleFont btn btn-default" name="'.$value['font_name'].'"><i class="icon-remove text-danger"></i></button>&nbsp;<span style="'.$this->fontstyles($value['font_name']).'">style="'.$this->fontstyles($value['font_name']).'"</span></li>';

                    $google_font_name .= $value['font_name'].'¤';

                    $google_font_link .= '<link id="'.str_replace(' ', '_', strtolower($value['font_name'])).'_link" rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='.str_replace(' ', '+', $value['font_name']).'" />';
                }
        }

		$this->fields_form_banner[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Banner item'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Title(Image alt):'),
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
                    'desc' => '<strong>'.$this->l('If this field is filled in, whole image will become clickable. You can not put any other hyperlinks or buttons into the following caption, otherwise, unexpected errors will happen.').'</strong>',
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
                    'type' => 'hidden',
                    'name' => 'id_st_advanced_banner_group',
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
        $this->fields_form_banner[1]['form'] = array(
            'legend' => array(
                'title' => $this->l('Add caption'),
                'icon'  => 'icon-cogs'
            ),
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
                    'type' => 'color',
                    'label' => $this->l('Caption color:'),
                    'name' => 'description_color',
                    'size' => 33,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('background color:'),
                    'name' => 'bg_color',
                    'size' => 33,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Caption width:'),
                    'name' => 'text_width',
                    'options' => array(
                        'query' => array(
                                array('id' => 1, 'name'=>'Center10%'),
                                array('id' => 2, 'name'=>'Center20%'),
                                array('id' => 3, 'name'=>'Center30%'),
                                array('id' => 4, 'name'=>'Center40%'),
                                array('id' => 5, 'name'=>'Center50%'),
                                array('id' => 6, 'name'=>'Center60%'),
                                array('id' => 7, 'name'=>'Center70%'),
                                array('id' => 8, 'name'=>'Center80%'),
                                array('id' => 9, 'name'=>'Center90%'),
                                array('id' => 11, 'name'=>'Left10%'),
                                array('id' => 12, 'name'=>'Left20%'),
                                array('id' => 13, 'name'=>'Left30%'),
                                array('id' => 14, 'name'=>'Left40%'),
                                array('id' => 15, 'name'=>'Left50%'),
                                array('id' => 16, 'name'=>'Left60%'),
                                array('id' => 17, 'name'=>'Left70%'),
                                array('id' => 18, 'name'=>'Left80%'),
                                array('id' => 19, 'name'=>'Left90%'),
                                array('id' => 21, 'name'=>'Right10%'),
                                array('id' => 22, 'name'=>'Right20%'),
                                array('id' => 23, 'name'=>'Right30%'),
                                array('id' => 24, 'name'=>'Right40%'),
                                array('id' => 25, 'name'=>'Right50%'),
                                array('id' => 26, 'name'=>'Right60%'),
                                array('id' => 27, 'name'=>'Right70%'),
                                array('id' => 28, 'name'=>'Right80%'),
                                array('id' => 29, 'name'=>'Right90%'),
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
                    'type' => 'select',
                    'label' => $this->l('Position:'),
                    'name' => 'text_position',
                    'options' => array(
                        'query' => self::$text_position,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide caption on mobile:'),
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
                    'desc' => $this->l('screen width < 768px.'),
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

        $languages = Language::getLanguages(true);
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        foreach ($languages as $lang)
        {
            $this->fields_form_banner[0]['form']['input']['image_multi_lang_'.$lang['id_lang']] = array(
                    'type' => 'file',
					'label' => $this->l('Image').' - '.$lang['name'].($default_lang == $lang['id_lang'] ? '('.$this->l('default language').')' : '').':',
					'name' => 'image_multi_lang_'.$lang['id_lang'],
                    //'required'  => ($default_lang == $lang['id_lang']),
                    'desc' => $this->l('Please ensure the image name is unique, or it will override the same name files.').'<br/>',
                );
        }
        if($banner->id)
        {
            $this->fields_form_banner[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_advanced_banner');
             foreach ($languages as $lang)
                if($banner->image_multi_lang[$lang['id_lang']])
                {
                    StAdvancedBannerClass::fetchMediaServer($banner->image_multi_lang[$lang['id_lang']]);
                    $this->fields_form_banner[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'has_image_'.$lang['id_lang'], 'default_value'=>1);
                    $this->fields_form_banner[0]['form']['input']['image_multi_lang_'.$lang['id_lang']]['required'] = false;
                    $this->fields_form_banner[0]['form']['input']['image_multi_lang_'.$lang['id_lang']]['image'] = '<img src="'.$banner->image_multi_lang[$lang['id_lang']].'" width="200"/>';
                }
        }

        $this->fields_form_banner[0]['form']['input'][] = array(
			'type' => 'html',
            'id' => 'a_cancel_0',
			'label' => '',
			'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner_group='.$group->id.'&viewstadvancedbanner&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a><a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to main page</a>',
		);
        $this->fields_form_banner[1]['form']['input'][] = array(
			'type' => 'html',
            'id' => 'a_cancel_1',
			'label' => '',
			'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&id_st_advanced_banner_group='.$group->id.'&viewstadvancedbanner&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a><a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to main page</a>',
		);

        $helper = new HelperForm();
		$helper->show_toolbar = false;
        $helper->module = $this;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestadvancedbanner';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($banner,"fields_form_banner"),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

        $helper->tpl_vars['fields_value']['id_st_advanced_banner_group'] = (int)$group->id;
        $helper->tpl_vars['fields_value']['google_font_name'] = $google_font_name;

		return $helper;
	}
    public static function showApplyTo($value,$row)
    {
        $result = '';
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
        elseif($row['id_cms'])
        {
            $cms = new CMS((int)$row['id_cms'], (int)Context::getContext()->language->id);
            if ($cms->id)
            {
                $module = new StAdvancedBanner();
                $result = $cms->meta_title.'('.$module->l('CMS').')';
            }
        }
        else
        {
            $module = new StAdvancedBanner();
            $result = $module->l('--');
        }
        return $result;
    }
    public static function showColumnWidth($value, $row)
    {
        return $value.'/12';
    }
    public static function showColumnHeight($value, $row)
    {
        return $value.'%';
    }
    protected function initList()
    {
        $this->fields_list = array(
            'id_st_advanced_banner_group' => array(
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
                'callback_object' => 'StAdvancedBanner',
                'search' => false,
                'orderby' => false
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'align' => 'center',
                'class'=>'fixed-width-xl',
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'align' => 'center',
                'active' => 'groupstatus',
                'type' => 'bool',
                'class' => 'fixed-width-sm',
                'search' => false,
                'orderby' => false
            ),
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->module = $this;
        $helper->identifier = 'id_st_advanced_banner_group';
        $helper->actions = array('view', 'edit', 'delete','duplicate');
        $helper->show_toolbar = true;
        $helper->imageType = 'jpg';
        $helper->toolbar_btn['new'] =  array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstadvancedbannergroup&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Add a group'),
        );

        $helper->title = $this->displayName;
        $helper->table = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        return $helper;
    }
    public function displayDuplicateLink($token, $id, $name)
    {
        return '<li class="divider"></li><li><a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&copy'.$this->name.'&id_st_advanced_banner_group='.(int)$id.'&token='.$token.'"><i class="icon-copy"></i>'.$this->l(' Duplicate ').'</a></li>';
    }
	protected function initListColumn()
	{
        $id_st_advanced_banner_group = (int)Tools::getValue('id_st_advanced_banner_group');
        $group = new StAdvancedBannerGroup($id_st_advanced_banner_group);

        if(Validate::isLoadedObject($group))
            $id_parent = $group->id_parent;

        $parents = StAdvancedBannerGroup::getParentsGroups($id_st_advanced_banner_group);

		$this->fields_list_group = array(
			'id_st_advanced_banner_group' => array(
				'title' => $this->l('Id'),
				'class' => 'fixed-width-md',
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
            'name' => array(
                'title' => $this->l('Column name'),
                'class' => '',
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'width' => array(
                'title' => $this->l('Width'),
                'class' => '',
                'type' => 'text',
                'callback' => 'showColumnWidth',
                'callback_object' => 'StAdvancedBanner',
                'search' => false,
                'orderby' => false
            ),
			'height' => array(
				'title' => $this->l('Height'),
				'class' => '',
				'type' => 'text',
                'callback' => 'showColumnHeight',
                'callback_object' => 'StAdvancedBanner',
                'search' => false,
                'orderby' => false
			),
            'position' => array(
                'title' => $this->l('Position'),
                'align' => 'center',
                'class'=>'fixed-width-xl',
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'active' => array(
				'title' => $this->l('Status'),
				'align' => 'center',
				'active' => 'groupstatus',
				'type' => 'bool',
				'class' => 'fixed-width-sm',
                'search' => false,
                'orderby' => false
            ),
		);

		$helper = new HelperList();
        $helper->module = $this;
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_st_advanced_banner_group';
		$helper->actions = array('view', 'edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstadvancedbannercolumn&id_parent='.$id_st_advanced_banner_group.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add a column'),
		);
        $helper->toolbar_btn['back'] =  array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name.( (isset($id_parent) && $id_parent) ? '&id_st_advanced_banner_group='.$id_parent.'&viewstadvancedbanner' : '').'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Back to list')
        );
        $parents_title = '';
        if(is_array($parents) && count($parents))
        {
            $parents = array_reverse($parents);
            $count = count($parents);
            foreach ($parents as $i => $value) {
                if ($i < $count-1)
                    $parents_title .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&viewstadvancedbanner&id_st_advanced_banner_group='.$value['id_st_advanced_banner_group'].'">'.$value['name'].'</a>|';
                else
                    $parents_title .= $value['name'];
            }
        }

        $parents_title0 = array(
            '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'">'.$this->displayName.'</a>'
        );

        $helper->tpl_vars['navigate'] = array_merge($parents_title0, explode('|',$parents_title));

		$helper->title = $this->l('Columns');
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    public static function showBannerGroupName($value,$row)
    {
        $group = new StAdvancedBannerGroup((int)$value);
        return $group->id ? $group->name : '-';
    }
    public static function showBannerImage($value,$row)
    {
        return $value ? '<img src="'.$value.'" width="200" />' : '-';
    }
	protected function initListBanner()
	{
        $id_st_advanced_banner_group = (int)Tools::getValue('id_st_advanced_banner_group');
        $group = new StAdvancedBannerGroup($id_st_advanced_banner_group);

        if(Validate::isLoadedObject($group))
            $id_parent = $group->id_parent;

        if(!isset($id_parent))
        {
            if(Tools::getValue('id_parent'))
                $id_parent = (int)Tools::getValue('id_parent');
            else
                $id_parent = 0;
        }

		$this->fields_list = array(
			'id_st_advanced_banner' => array(
				'title' => $this->l('Id'),
				'class' => 'fixed-width-md',
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'id_st_advanced_banner_group' => array(
				'title' => $this->l('Column name'),
				'class' => 'fixed-width-xxl',
				'type' => 'text',
				'callback' => 'showBannerGroupName',
				'callback_object' => 'StAdvancedBanner',
                'search' => false,
                'orderby' => false
			),
            'image_multi_lang' => array(
				'title' => $this->l('Image'),
				'type' => 'text',
				'callback' => 'showBannerImage',
				'callback_object' => 'StAdvancedBanner',
                'class' => 'fixed-width-xxl',
                'search' => false,
                'orderby' => false
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'align' => 'center',
                'class'=>'fixed-width-xl',
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'active' => array(
				'title' => $this->l('Status'),
				'align' => 'center',
				'active' => 'groupstatus',
				'type' => 'bool',
				'class' => 'fixed-width-sm',
                'search' => false,
                'orderby' => false
            ),
		);

		$helper = new HelperList();
        $helper->module = $this;
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_st_advanced_banner';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstadvancedbanner&id_st_advanced_banner_group='.(int)Tools::getValue('id_st_advanced_banner_group').'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add a banner')
		);
		$helper->toolbar_btn['back'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.($id_parent ? '&id_st_advanced_banner_group='.$id_parent.'&viewstadvancedbanner' : '').'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Back to list')
		);

        $parents = StAdvancedBannerGroup::getParentsGroups($id_st_advanced_banner_group);
        $parents_title = '';
        if(is_array($parents) && count($parents))
        {
            $parents = array_reverse($parents);
            $count = count($parents);
            foreach ($parents as $i => $value) {
                if ($i < $count-1)
                    $parents_title .= '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&viewstadvancedbanner&id_st_advanced_banner_group='.$value['id_st_advanced_banner_group'].'">'.$value['name'].'</a>|';
                else
                    $parents_title .= $value['name'];
            }
        }

        $parents_title0 = array(
            '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'">'.$this->displayName.'</a>'
        );

        $helper->tpl_vars['navigate'] = array_merge($parents_title0, explode('|',$parents_title));

        $helper->title = $this->l('Banner');
		$helper->table = $this->name;
		$helper->orderBy = 'position';
		$helper->orderWay = 'ASC';
	    $helper->position_identifier = 'id_st_advanced_banner';

		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    private function _prepareHook($identify,$type=1)
    {
        $group = StAdvancedBannerGroup::getBannerGroup($identify,$type);
        if(!is_array($group) || !count($group))
            return false;
        $col_sum = $row_sum = 0;
        foreach($group as $k=>$v)
        {
            /*$col_sum += $v['width'];
            $row_sum += $v['height'];

            if($col_sum>12 && $row_sum>100)
            {
                unset($group[$k]);
                continue;
            }*/
            $banners = StAdvancedBannerClass::getAll($v['id_st_advanced_banner_group'], $this->context->language->id, 1);
            if(is_array($banners) && $banner_nbr=count($banners))
               $group[$k]['banners'] = $banners;
            $columns = StAdvancedBannerGroup::recurseTree($v['id_st_advanced_banner_group'],0,0,1);
            $group[$k]['columns'] = $this->_recurseBanners($columns, $v['height'], $v['padding']);
            $group[$k]['is_full_width'] = $type==1 ? isset(self::$location[$v['location']]['full_width']) : false;
            $group[$k]['auto_height'] = $type==1 ? isset(self::$location[$v['location']]['auto_height']) : 0;
            $group[$k]['is_column'] = $type==1 ? isset(self::$location[$v['location']]['is_column']) : false;
            $group[$k]['stretched'] = $type==1 ? isset(self::$location[$v['location']]['stretched']) : 0;
        }

	    $this->smarty->assign(array(
            'groups' => $group,
        ));
        return true;
    }
    private function _recurseBanners($columns, $height, $padding)
    {
        $col_sum = $row_sum = 0;
        foreach ($columns as $k => $v)
        {
            $col_sum += $v['width'];
            $row_sum += $v['height'];

            if($col_sum>12 && $row_sum>100)
            {
                unset($columns[$k]);
                continue;
            }
        }

        $col_a = $banner_b_nbr = 0;
        foreach ($columns as &$column) {
            if($col_sum>12)
            {
                $column['banner_b'] = 1;
                $col_a +=$column['width'];
                if(($col_sum - $col_a)<12)
                {
                    $column['banner_b'] = 0;
                }else{
                    $banner_b_nbr++;
                }
            }
        }
        $padding = $padding!=="" ? (int)$padding : 20;
        $height_column = $height - $banner_b_nbr*$padding;
        foreach ($columns as &$column) {
            $column['height_px'] = Tools::ps_round($height_column*$column['height']/100);
            $banners = StAdvancedBannerClass::getAll($column['id_st_advanced_banner_group'],$this->context->language->id,1);
            if(is_array($banners) && $banner_nbr=count($banners))
               $column['banners'] = $banners;
            if(isset($column['columns']))
                $column['columns'] = $this->_recurseBanners($column['columns'],$column['height_px'],$padding);
        }
        return $columns;
    }
    public function hookDisplayHeader($params)
    {
        // $this->context->controller->addJS(($this->_path).'views/js/stadvancedbanner.js');

        /*$data = StAdvancedBannerFontClass::getAll(1);
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
            $custom_css_arr = StAdvancedBannerClass::getCustomCss();
            if (is_array($custom_css_arr) && count($custom_css_arr)) {
                foreach ($custom_css_arr as $v) {
                    $classname = '.st_advanced_banner_block_'.$v['id_st_advanced_banner'].' ';

                    $v['description_color'] && $custom_css .= $classname.'.style_content,
                    a'.$classname.',
                    '.$classname.'.style_content a{color:'.$v['description_color'].';}
                    '.$classname.'.icon_line:after, '.$classname.'.icon_line:before{background-color:'.$v['description_color'].';}
                    '.$classname.'.line, '.$classname.'.btn{border-color:'.$v['description_color'].';}';

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
            }

            $custom_css_arr = StAdvancedBannerGroup::getCustomCss();
            if (is_array($custom_css_arr) && count($custom_css_arr)) {
                foreach ($custom_css_arr as $v) {
                    if($v['padding'] || $v['padding']!==null)
                    {
                        $custom_css .= '#st_advanced_banner_'.$v['id_st_advanced_banner_group'].'.st_advanced_banner_row .row{margin-left:-'.floor($v['padding']/2).'px;margin-right:-'.floor($v['padding']/2).'px;}';
                        $custom_css .= '#st_advanced_banner_'.$v['id_st_advanced_banner_group'].' .advanced_banner_col{padding-left:'.floor($v['padding']/2).'px;padding-right:'.floor($v['padding']/2).'px;}';
                        $custom_css .= '#st_advanced_banner_'.$v['id_st_advanced_banner_group'].' .advanced_banner_col.advanced_banner_b .st_advanced_banner_block{margin-bottom:'.(int)$v['padding'].'px;}@media (max-width: 767px) {.st_advanced_banner_block {margin-bottom:'.(int)$v['padding'].'px;}}';
                    }

                    $classname = (isset(self::$location[$v['location']]['full_width']) ? '#advanced_banner_container_'.$v['id_st_advanced_banner_group'].' ' : '#st_advanced_banner_'.$v['id_st_advanced_banner_group']);
                    if($v['top_spacing'] || $v['top_spacing']==='0')
                        $custom_css .= $classname.'{margin-top:'.(int)$v['top_spacing'].'px;}';
                    if($v['bottom_spacing'] || $v['bottom_spacing']==='0')
                        $custom_css .= $classname.'{margin-bottom:'.(int)$v['bottom_spacing'].'px;}';
                }
            }
            if($custom_css)
                $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
        }
        return $this->display(__FILE__, 'header.tpl', $this->getCacheId());
    }
	public function hookDisplayHome($params)
	{
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(1)))
            if(!$this->_prepareHook(1))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(1));
	}

	public function hookDisplayHomeTop($params)
	{
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(2)))
    		if(!$this->_prepareHook(2))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(2));
	}

	public function hookDisplayHomeBottom($params)
	{
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(3)))
    		if(!$this->_prepareHook(3))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(3));
	}

    public function hookDisplayHomeSecondaryLeft($params)
    {
        if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(4)))
            if(!$this->_prepareHook(4))
                return false;
        return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(4));
    }

    public function hookDisplayHomeSecondaryRight($params)
    {
        if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(5)))
            if(!$this->_prepareHook(5))
                return false;
        return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(5));
    }

	public function hookDisplayHomeTertiaryLeft($params)
	{
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(19)))
    		if(!$this->_prepareHook(19))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(19));
	}

    public function hookDisplayHomeTertiaryRight($params)
    {
        if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(20)))
            if(!$this->_prepareHook(20))
                return false;
        return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(20));
    }

    public function hookDisplayBottomColumn($params)
    {
        if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(18)))
            if(!$this->_prepareHook(18))
                return false;
        return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(18));
    }

	public function hookDisplayTopColumn($params)
	{
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(22)))
    		if(!$this->_prepareHook(22))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(22));
	}
	public function hookDisplayLeftColumn($params)
	{
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(7)))
            if(!$this->_prepareHook(7))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(7));
	}
	public function hookDisplayRightColumn($params)
	{
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(8)))
            if(!$this->_prepareHook(8))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(8));
	}

    public function hookDisplayProductSecondaryColumn($params)
    {
        if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(25)))
            if(!$this->_prepareHook(25))
                return false;
        return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(25));
    }

	public function hookDisplayBanner($params)
	{
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(21)))
            if(!$this->_prepareHook(21))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(21));
	}

    public function hookDisplayCategoryHeader($params)
    {
        $id_category = (int)Tools::getValue('id_category');
        if(!$id_category)
            return false;
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId($id_category,'category_main_column','stadvancedbanner')))
            if(!$this->_prepareHook($id_category,2,0))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId($id_category,'category_main_column','stadvancedbanner'));
    }

    public function hookDisplayManufacturerHeader($params)
    {
        $id_manufacturer = (int)Tools::getValue('id_manufacturer');
        if(!$id_manufacturer)
            return false;
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId($id_manufacturer,'manufacturer_main_column','stadvancedbanner')))
            if(!$this->_prepareHook($id_manufacturer,3,0))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId($id_manufacturer,'manufacturer_main_column','stadvancedbanner'));
    }

    public function hookDisplayFullWidthTop($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(23)))
            if(!$this->_prepareHook(array(23,26),1))
                return false;
        return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(23));
    }
    public function hookDisplayFullWidthTop2($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(28)))
            if(!$this->_prepareHook(array(28,29),1))
                return false;
        return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(28));
    }
    public function hookDisplayHomeVeryBottom($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(24)))
            if(!$this->_prepareHook(array(24,27),1))
                return false;
        return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(24));
    }

	public function hookDisplayAnywhere($params)
	{
	    if(!isset($params['caller']) || $params['caller']!=$this->name)
            return false;
	   if(isset($params['function']) && method_exists($this,$params['function']))
        {
            if($params['function']=='displayByBannerId')
                return call_user_func_array(array($this,$params['function']),array($params['identify']));
            elseif($params['function']=='displayCmsMainSlide')
                return call_user_func_array(array($this,$params['function']),array($params['identify']));
            elseif($params['function']=='displayCmsCategoryMainSlide')
                return call_user_func_array(array($this,$params['function']),array($params['identify']));
            else
                return false;
        }
        return false;
    }
    public function displayCmsMainSlide($identify)
    {
        if (!$identify)
            return false;
        if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId($identify, 4)))
            if(!$this->_prepareHook($identify, 4))
                return false;
        return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId($identify, 4));
    }
    public function displayCmsCategoryMainSlide($identify)
    {
        if (!$identify)
            return false;
        if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId($identify, 5)))
            if(!$this->_prepareHook($identify, 5))
                return false;
        return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId($identify, 5));
    }
    public function displayByBannerId($identify)
    {
        if(!Validate::isInt($identify))
            return false;

        $group_obj = new StAdvancedBannerGroup($identify);
        if(!$group_obj->id || !$group_obj->active)
            return false;
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId($group_obj->id,'id')))
        {
            $group = $group_obj->getFields();
            if(is_array($group) && count($group))
            {
                 $banner = StAdvancedBannerClass::getAll($group['id_st_advanced_banner_group'],$this->context->language->id,1);
                 if(is_array($banner) && count($banner))
                    $group['banner'] = $banner;
    		    $this->smarty->assign(array(
                    'group' => array($group),
                ));
            }
        }
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId($group_obj->id,'id'));
    }
    public function hookActionObjectCategoryDeleteAfter($params)
    {
        if(!$params['object']->id)
            return ;

        $group = StAdvancedBannerGroup::getBannerGroup($params['object']->id,2);
        return $this->deletePatch($group);
    }

    public function hookActionObjectManufacturerDeleteAfter($params)
    {
        if(!$params['object']->id)
            return ;

        $group = StAdvancedBannerGroup::getBannerGroup($params['object']->id,3);

        return $this->deletePatch($group);
    }

    private function deletePatch($group)
    {
        if(!is_array($group) || !count($group))
            return ;
        $res = true;
        foreach($group as $v)
        {
            $group = new StAdvancedBannerGroup($v['id_st_advanced_banner_group']);
            $res &= $group->delete();
        }

        return $res;
    }

	public function hookDisplayStBlogHome($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(11)))
    		if(!$this->_prepareHook(11))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(11));
	}

	public function hookDisplayStBlogHomeTop($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(12)))
    		if(!$this->_prepareHook(12))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(12));
	}

	public function hookDisplayStBlogHomeBottom($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(13)))
    		if(!$this->_prepareHook(13))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(13));
	}

	public function hookDisplayStBlogLeftColumn($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(14)))
            if(!$this->_prepareHook(14))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(14));
	}
	public function hookDisplayStBlogRightColumn($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(15)))
            if(!$this->_prepareHook(15))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(15));
	}

    public function hookDisplayCategoryFooter($params)
    {
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(17)))
            if(!$this->_prepareHook(17))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(17));
    }

    public function hookDisplayFooterProduct($params)
    {
		if (!$this->isCached('stadvancedbanner.tpl', $this->stGetCacheId(16)))
            if(!$this->_prepareHook(16))
                return false;
		return $this->display(__FILE__, 'stadvancedbanner.tpl', $this->stGetCacheId(16));
    }


	public function hookActionShopDataDuplication($params)
	{
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'st_advanced_banner_group_shop (id_st_advanced_banner_group, id_shop)
		SELECT id_st_advanced_banner_group, '.(int)$params['new_id_shop'].'
		FROM '._DB_PREFIX_.'st_advanced_banner_group_shop
		WHERE id_shop = '.(int)$params['old_id_shop']);
        $this->clearBannerCache();
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

    public function processCopyAdvancedBannerGroup($id_st_advanced_banner_group = 0)
    {
        if (!$id_st_advanced_banner_group)
            return false;

        $group = new StAdvancedBannerGroup($id_st_advanced_banner_group);
        // Make sure it is root node.
        if ($group->id_parent > 0)
            return false;

        return $this->processCopySubs($group);
    }

    public function processCopySubs($group, $id_parent = 0)
    {
        if (!is_object($group))
            return false;

        $group2 = clone $group;
        $group2->id = 0;
        $group2->id_st_advanced_banner_group = 0;
        if ($id_parent > 0)
            $group2->id_parent = $id_parent;
        $ret = $group2->add();

        if (!Shop::isFeatureActive())
        {
            Db::getInstance()->insert('st_advanced_banner_group_shop', array(
                'id_st_advanced_banner_group' => (int)$group2->id,
                'id_shop' => (int)Context::getContext()->shop->id,
            ));
        }
        else
        {
            $assos_shop = Tools::getValue('checkBoxShopAsso_st_advanced_banner_group');
            if (empty($assos_shop))
                $assos_shop[(int)Context::getContext()->shop->id] = Context::getContext()->shop->id;
            foreach ($assos_shop as $id_shop => $row)
                Db::getInstance()->insert('st_advanced_banner_group_shop', array(
                    'id_st_advanced_banner_group' => (int)$group2->id,
                    'id_shop' => (int)$id_shop,
                ));
        }

        if ($group->hasBanner())
        {
            foreach(Db::getInstance()->executeS('SELECT id_st_advanced_banner FROM '._DB_PREFIX_.'st_advanced_banner WHERE id_st_advanced_banner_group='.(int)$group->id) AS $row)
            {
                $banner = new StAdvancedBannerClass($row['id_st_advanced_banner']);
                $banner->id = 0;
                $banner->id_st_advanced_banner = 0;
                $banner->id_st_advanced_banner_group = (int)$group2->id;
                $ret &= $banner->add();
            }
        }

        if ($group->hasColumn())
        {
            foreach(Db::getInstance()->executeS('SELECT id_st_advanced_banner_group FROM '._DB_PREFIX_.'st_advanced_banner_group WHERE id_parent='.(int)$group->id) AS $value)
            {
                $group3 = new StAdvancedBannerGroup($value['id_st_advanced_banner_group']);
                $ret &= $this->processCopySubs($group3, $group2->id);
            }
        }
        return $ret;
    }

    public function processUpdatePositions()
	{
		if (Tools::getValue('action') == 'updatePositions' && Tools::getValue('ajax'))
		{
			$way = (int)(Tools::getValue('way'));
			$id = (int)(Tools::getValue('id'));
			$positions = Tools::getValue('st_advanced_banner');
            $msg = '';
			if (is_array($positions))
				foreach ($positions as $position => $value)
				{
					$pos = explode('_', $value);

					if ((isset($pos[2])) && ((int)$pos[2] === $id))
					{
						if ($object = new StAdvancedBannerClass((int)$pos[2]))
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

}