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

include_once(dirname(__FILE__).'/StProductCategoriesSliderClass.php');
class StProductCategoriesSlider extends Module
{
    protected static $cache_product_categories = array();
    public  $fields_list;
    public  $fields_form;
    public  $fields_value;
    public $validation_errors = array();
    public  $fields_form_setting;
    
	private $_html = '';
	private $spacer_size = '5';
    private $_prefix_st = 'ST_PRO_CATE_';
    private $_prefix_stsn = 'STSN_';
    public $imgtype = array('jpg', 'gif', 'jpeg', 'png');
    protected static $access_rights = 0775;
    public static $sort_by = array(
        1 => array('id' =>1 , 'name' => 'Product position DESC', 'orderBy'=>'position', 'orderWay'=>'DESC' ),
        2 => array('id' =>2 , 'name' => 'Product position ASC', 'orderBy'=>'position', 'orderWay'=>'ASC'),
        3 => array('id' =>3 , 'name' => 'Product Name: A to Z', 'orderBy'=>'name', 'orderWay'=>'ASC'),
        4 => array('id' =>4 , 'name' => 'Product Name: Z to A', 'orderBy'=>'name', 'orderWay'=>'DESC'),
        5 => array('id' =>5 , 'name' => 'Price: Lowest first', 'orderBy'=>'price', 'orderWay'=>'ASC'),
        6 => array('id' =>6 , 'name' => 'Price: Highest first', 'orderBy'=>'price', 'orderWay'=>'DESC'),
    );
        
    public static $easing = array(
		array('id' => 0, 'name' => 'swing'),
		array('id' => 1, 'name' => 'easeInQuad'),
		array('id' => 2, 'name' => 'easeOutQuad'),
		array('id' => 3, 'name' => 'easeInOutQuad'),
		array('id' => 4, 'name' => 'easeInCubic'),
		array('id' => 5, 'name' => 'easeOutCubic'),
		array('id' => 6, 'name' => 'easeInOutCubic'),
		array('id' => 7, 'name' => 'easeInQuart'),
		array('id' => 8, 'name' => 'easeOutQuart'),
		array('id' => 9, 'name' => 'easeInOutQuart'),
		array('id' => 10, 'name' => 'easeInQuint'),
		array('id' => 11, 'name' => 'easeOutQuint'),
		array('id' => 12, 'name' => 'easeInOutQuint'),
		array('id' => 13, 'name' => 'easeInSine'),
		array('id' => 14, 'name' => 'easeOutSine'),
		array('id' => 15, 'name' => 'easeInOutSine'),
		array('id' => 16, 'name' => 'easeInExpo'),
		array('id' => 17, 'name' => 'easeOutExpo'),
		array('id' => 18, 'name' => 'easeInOutExpo'),
		array('id' => 19, 'name' => 'easeInCirc'),
		array('id' => 20, 'name' => 'easeOutCirc'),
		array('id' => 21, 'name' => 'easeInOutCirc'),
		array('id' => 22, 'name' => 'easeInElastic'),
		array('id' => 23, 'name' => 'easeOutElastic'),
		array('id' => 24, 'name' => 'easeInOutElastic'),
		array('id' => 25, 'name' => 'easeInBack'),
		array('id' => 26, 'name' => 'easeOutBack'),
		array('id' => 27, 'name' => 'easeInOutBack'),
		array('id' => 28, 'name' => 'easeInBounce'),
		array('id' => 29, 'name' => 'easeOutBounce'),
		array('id' => 30, 'name' => 'easeInOutBounce'),
	);
    public static $items = array(
		array('id' => 2, 'name' => '2'),
		array('id' => 3, 'name' => '3'),
		array('id' => 4, 'name' => '4'),
		array('id' => 5, 'name' => '5'),
		array('id' => 6, 'name' => '6'),
    );
    private $_hooks = array();
	function __construct()
	{
		$this->name           = 'stproductcategoriesslider';
		$this->tab            = 'front_office_features';
		$this->version        = '1.7.7';
		$this->author         = 'SUNNYTOO.COM';
		$this->need_instance  = 0;
        $this->bootstrap      = true;

		parent::__construct();
        
        $this->initHookArray();

		$this->displayName = $this->l('Product slider for each category');
		$this->description = $this->l('Product slider for each category.');
	}
    
    private function initHookArray()
    {
        $this->_hooks = array(
                array(
                    'id' => 'displayFullWidthTop',
                    'val' => '4',
                    'name' => $this->l('FullWidthTop'),
                    'full_width' => 1,
                ),
                array(
        			'id' => 'displayFullWidthTop2',
        			'val' => '65536',
        			'name' => $this->l('FullWidthTop2'),
                    'full_width' => 1,
        		),
        		array(
        			'id' => 'displayHomeTop',
        			'val' => '2',
        			'name' => $this->l('HomeTop')
        		),
                array(
        			'id' => 'displayHome',
        			'val' => '1',
        			'name' => $this->l('Home')
        		),
        		array(
        			'id' => 'displayHomeSecondaryLeft',
        			'val' => '8',
        			'name' => $this->l('HomeSecondaryLeft')
        		),
        		array(
        			'id' => 'displayHomeSecondaryRight',
        			'val' => '16',
        			'name' => $this->l('HomeSecondaryRight')
        		),
        		array(
        			'id' => 'displayHomeTertiaryLeft',
        			'val' => '32',
        			'name' => $this->l('HomeTertiaryLeft')
        		),
        		array(
        			'id' => 'displayHomeTertiaryRight',
        			'val' => '64',
        			'name' => $this->l('HomeTertiaryRight')
        		),
                array(
        			'id' => 'displayHomeBottom',
        			'val' => '128',
        			'name' => $this->l('HomeBottom')
        		),
                array(
        			'id' => 'displayBottomColumn',
        			'val' => '256',
        			'name' => $this->l('BottomColumn')
        		),
        		array(
        			'id' => 'displayHomeVeryBottom',
        			'val' => '512',
        			'name' => $this->l('FullWidthBottom(Homeverybottom)'),
                    'full_width' => 1,
        		),
                array(
        			'id' => 'displayProductSecondaryColumn',
        			'val' => '1024',
        			'name' => $this->l('ProductSecondaryColumn')
        		),
                array(
        			'id' => 'displayLeftColumn',
        			'val' => '2048',
        			'name' => $this->l('LeftColumn')
        		),
        		array(
        			'id' => 'displayRightColumn',
        			'val' => '4096',
        			'name' => $this->l('RightColumn')
        		),
        		array(
        			'id' => 'displayFooterTop',
        			'val' => '8192',
        			'name' => $this->l('FooterTop')
        		),
                array(
        			'id' => 'displayFooter',
        			'val' => '16384',
        			'name' => $this->l('Footer')
        		),
                array(
        			'id' => 'displayFooterSecondary',
        			'val' => '32768',
        			'name' => $this->l('FooterSecondary')
        		)
        );
    }

	function install()
	{
		if (!parent::install() 
            || !$this->installDB()
            || !$this->registerHook('displayHeader')
			|| !$this->registerHook('addproduct')
			|| !$this->registerHook('updateproduct')
			|| !$this->registerHook('deleteproduct')
            || !$this->registerHook('displayAnywhere')
            || !$this->registerHook('actionCategoryDelete')
            || !$this->registerHook('actionObjectCategoryDeleteAfter')
            || !Configuration::updateValue('ST_PRO_CATE_TABS', 0)
            || !Configuration::updateValue('ST_PRO_CATE_RANDOM', 0)
            || !Configuration::updateValue('ST_PRO_CATE_EASING', 0)
            || !Configuration::updateValue('ST_PRO_CATE_SLIDESHOW', 0)
            || !Configuration::updateValue('ST_PRO_CATE_S_SPEED', 7000)
            || !Configuration::updateValue('ST_PRO_CATE_A_SPEED', 400)
            || !Configuration::updateValue('ST_PRO_CATE_PAUSE_ON_HOVER', 1)
            || !Configuration::updateValue('ST_PRO_CATE_LOOP', 0)
            || !Configuration::updateValue('ST_PRO_CATE_MOVE', 0)
            || !Configuration::updateValue('ST_PRO_CATE_COUNTDOWN_ON', 1)
            || !Configuration::updateValue('ST_PRO_CATE_EASING_COL', 0)
            || !Configuration::updateValue('ST_PRO_CATE_SLIDESHOW_COL', 0)
            || !Configuration::updateValue('ST_PRO_CATE_S_SPEED_COL', 7000)
            || !Configuration::updateValue('ST_PRO_CATE_A_SPEED_COL', 400)
            || !Configuration::updateValue('ST_PRO_CATE_PAUSE_ON_HOVER_COL', 1)
            || !Configuration::updateValue('ST_PRO_CATE_LOOP_COL', 0)
            || !Configuration::updateValue('ST_PRO_CATE_MOVE_COL', 0)
            || !Configuration::updateValue('ST_PRO_CATE_ITEMS_COL', 4)
            || !Configuration::updateValue('ST_PRO_CATE_DISPLAY_PRO_COL', 0)
            || !Configuration::updateValue('ST_PRO_CATE_HIDE_MOB', 0)
            || !Configuration::updateValue('ST_PRO_CATE_HIDE_MOB_COL', 0)
            || !Configuration::updateValue('ST_PRO_CATE_DISPLAY_SD', 0)
            || !Configuration::updateValue('ST_PRO_CATE_COUNTDOWN_ON_COL', 1)
            || !Configuration::updateValue('ST_PRO_CATE_GRID', 0)
            || !Configuration::updateValue('STSN_PRO_CATE_PRO_PER_LG_0', 4)
            || !Configuration::updateValue('STSN_PRO_CATE_PRO_PER_MD_0', 4)
            || !Configuration::updateValue('STSN_PRO_CATE_PRO_PER_SM_0', 3)
            || !Configuration::updateValue('STSN_PRO_CATE_PRO_PER_XS_0', 2)
            || !Configuration::updateValue('STSN_PRO_CATE_PRO_PER_XXS_0', 1)
            //
            || !Configuration::updateValue($this->_prefix_st.'TOP_PADDING', '')
            || !Configuration::updateValue($this->_prefix_st.'BOTTOM_PADDING', '')
            || !Configuration::updateValue($this->_prefix_st.'TOP_MARGIN', '')
            || !Configuration::updateValue($this->_prefix_st.'BOTTOM_MARGIN', '')
            || !Configuration::updateValue($this->_prefix_st.'BG_PATTERN', 0)
            || !Configuration::updateValue($this->_prefix_st.'BG_IMG', '')
            || !Configuration::updateValue($this->_prefix_st.'BG_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'SPEED', 0)
            || !Configuration::updateValue($this->_prefix_st.'TITLE_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'TITLE_HOVER_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'TAB_TITLE_NO_BG', 0)
            || !Configuration::updateValue($this->_prefix_st.'TEXT_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'PRICE_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'LINK_HOVER_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'GRID_HOVER_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_HOVER_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_DISABLED_BG', '')
        )
			return false;
        $res = $this->installExtraHook();
        $this->clearProductCategoriesSliderCache();
		return true;
	}
    public function installExtraHook()
    {
        foreach($this->_hooks AS $value)
            if (!$this->registerHook($value['id']))
                return false;
        return true;
    }
	public function installDB()
	{
		$return = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_product_categories_slider` (
				`id_st_product_categories_slider` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_category` int(10) unsigned NOT NULL DEFAULT 0,
                `id_shop` int(10) unsigned NOT NULL,    
                `product_nbr` int(10) unsigned NOT NULL DEFAULT 0, 
                `product_order` int(10) unsigned NOT NULL DEFAULT 0, 
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `position` int(10) unsigned NOT NULL DEFAULT 0,
                `display_on` int(10) unsigned NOT NULL DEFAULT 1,
                `top_spacing` varchar(10) DEFAULT NULL,
                `bottom_spacing` varchar(10) DEFAULT NULL,
                `top_padding` varchar(10) DEFAULT NULL,
                `bottom_padding` varchar(10) DEFAULT NULL,
                `bg_pattern` tinyint(2) unsigned NOT NULL DEFAULT 0, 
                `bg_img` varchar(255) DEFAULT NULL,
                `bg_color` varchar(7) DEFAULT NULL,
                `speed` float(4,1) unsigned NOT NULL DEFAULT 0.1,
                `title_color` varchar(7) DEFAULT NULL,
                `title_hover_color` varchar(7) DEFAULT NULL,
                `text_color` varchar(7) DEFAULT NULL,
                `price_color` varchar(7) DEFAULT NULL,
                `grid_hover_bg` varchar(7) DEFAULT NULL,
                `link_hover_color` varchar(7) DEFAULT NULL,
                `direction_color` varchar(7) DEFAULT NULL,
                `direction_bg` varchar(7) DEFAULT NULL,
                `direction_hover_bg` varchar(7) DEFAULT NULL,
                `direction_disabled_bg` varchar(7) DEFAULT NULL,
                `title_alignment` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `title_no_bg` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `title_font_size` int(10) unsigned NOT NULL DEFAULT 0, 
                `direction_nav` tinyint(1) unsigned NOT NULL DEFAULT 0, 
				PRIMARY KEY (`id_st_product_categories_slider`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		return $return;
	}

	public function uninstall()
	{
        $this->clearProductCategoriesSliderCache();
		// Delete configuration
		return $this->uninstallDB() && parent::uninstall();
	}

	public function uninstallDB()
	{
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_product_categories_slider`');
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
    public function getContent()
	{
        $check_result = $this->_checkImageDir();
        $this->context->controller->addCSS($this->_path.'views/css/admin.css');
        $this->context->controller->addJS($this->_path.'views/js/admin.js');
		$id_st_product_categories_slider = (int)Tools::getValue('id_st_product_categories_slider');

        if(Tools::getValue('act')=='delete_slider_image' && $identi = Tools::getValue('identi'))
        {
            $result = array(
                'r' => false,
                'm' => '',
                'd' => ''
            );
            $product_categories_slider = new StProductCategoriesSliderClass((int)$identi);
            if(Validate::isLoadedObject($product_categories_slider))
            {
                $product_categories_slider->bg_img = '';
                if($product_categories_slider->save())
                {
                    $result['r'] = true;
                }
            }
            die(json_encode($result));
        }

        if(Tools::getValue('act')=='delete_image')
        {
            $result = array(
                'r' => false,
                'm' => '',
                'd' => ''
            );
            if(Configuration::updateValue($this->_prefix_st.'BG_IMG', ''))
                $result['r'] = true;
            die(json_encode($result));
        }

		if (isset($_POST['savestproductcategoriesslider']) || isset($_POST['savestproductcategoriessliderAndStay']))
		{
            $error = array();
            
			if ($id_st_product_categories_slider)
				$product_categories_slider = new StProductCategoriesSliderClass((int)$id_st_product_categories_slider);
			else
				$product_categories_slider = new StProductCategoriesSliderClass();
                
			$product_categories_slider->copyFromPost();
            $display_on = 0;
            foreach($this->_hooks as $v)
                $display_on += (int)Tools::getValue('display_on_'.$v['id']);
                
            if(!$display_on)
                $error[] = $this->displayError($this->l('The field "Display on" is required'));
                
            $product_categories_slider->display_on = $display_on;
            $product_categories_slider->id_shop = (int)Shop::getContextShopID();
            
            $defaultLanguage = new Language((int)(Configuration::get('PS_LANG_DEFAULT')));
            if(!$product_categories_slider->id_category)
                $error[] = $this->displayError($this->l('The field "Category" is required'));

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
                           $product_categories_slider->bg_img = $this->name.'/'.$bg_image;
                    }
                }
            }

			if (!count($error) && $product_categories_slider->validateFields(false) && $product_categories_slider->validateFieldsLang(false))
            {
                /*position*/
                $product_categories_slider->position = $product_categories_slider->checkPostion();
                if($product_categories_slider->save())
                {
                    $this->clearProductCategoriesSliderCache();
                    if(isset($_POST['savestproductcategoriessliderAndStay']))
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_product_categories_slider='.$product_categories_slider->id.'&conf='.($id_st_product_categories_slider?4:3).'&update'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));    
                    else
                        $this->_html .= $this->displayConfirmation($this->l('Product categories slider').' '.($id_st_product_categories_slider ? $this->l('updated') : $this->l('added')));
                        
                }
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during Product categories slider').' '.($id_st_product_categories_slider ? $this->l('updating') : $this->l('creation')));
            }
			else
				$this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
		}
	    if (Tools::isSubmit('statusstproductcategoriesslider'))
        {
            $product_categories_slider = new StProductCategoriesSliderClass((int)$id_st_product_categories_slider);
            if($product_categories_slider->id && $product_categories_slider->toggleStatus())
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));  
                $this->clearProductCategoriesSliderCache();
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
        if (Tools::isSubmit('way') && Tools::isSubmit('id_st_product_categories_slider') && (Tools::isSubmit('position')))
		{
		    $prduct_categories = new StProductCategoriesSliderClass((int)$id_st_product_categories_slider);
            if($prduct_categories->id && $prduct_categories->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position')))
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));
                $this->clearProductCategoriesSliderCache();
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));    
            }
            else
                $this->_html .= $this->displayError($this->l('Failed to update the position.'));
		}
        if (Tools::getValue('action') == 'updatePositions')
        {
            $this->processUpdatePositions();
        }
		if (isset($_POST['savesettingstproductcategoriesslider']) || isset($_POST['savesettingstproductcategoriessliderAndStay']))
        {
		    $this->initFieldsForm();
            
            foreach($this->fields_form_setting as $form)
                foreach($form['form']['input'] as $field)
                    if(isset($field['validation']))
                    {
                        $errors = array();       
                        $value = Tools::getValue($field['name']);
                        if (isset($field['required']) && $field['required'] && $value==false && (string)$value != '0')
        						$errors[] = sprintf(Tools::displayError('Field "%s" is required.'), $field['label']);
                        elseif($value)
                        {
                            $field_validation = $field['validation'];
        					if (!Validate::$field_validation($value))
        						$errors[] = sprintf(Tools::displayError('Field "%s" is invalid.'), $field['label']);
                        }
        				// Set default value
        				if ($value === false && isset($field['default_value']))
        					$value = $field['default_value'];
                            
                        if(count($errors))
                        {
                            $this->validation_errors = array_merge($this->validation_errors, $errors);
                        }
                        elseif($value==false)
                        {
                            switch($field['validation'])
                            {
                                case 'isUnsignedId':
                                case 'isUnsignedInt':
                                case 'isInt':
                                case 'isBool':
                                    $value = 0;
                                break;
                                case 'isNullOrUnsignedId':
                                    $value = $value==='0' ? '0' : '';
                                break;
                                default:
                                    $value = '';
                                break;
                            }
                            Configuration::updateValue('ST_PRO_CATE_'.strtoupper($field['name']), $value);
                        }
                        else
                            Configuration::updateValue('ST_PRO_CATE_'.strtoupper($field['name']), $value);
                    }
            $this->updateCatePerRow();
            if(!count($this->validation_errors))
            {
                if (isset($_FILES['bg_img']) && isset($_FILES['bg_img']['tmp_name']) && !empty($_FILES['bg_img']['tmp_name'])) 
                {
                    if ($vali = ImageManager::validateUpload($_FILES['bg_img'], Tools::convertBytes(ini_get('upload_max_filesize'))))
                       $this->validation_errors[] = Tools::displayError($vali);
                    else 
                    {
                        $bg_image = $this->uploadCheckAndGetName($_FILES['bg_img']['name']);
                        if(!$bg_image)
                            $this->validation_errors[] = Tools::displayError('Image format not recognized');
                        $this->_checkEnv();
                        if (!move_uploaded_file($_FILES['bg_img']['tmp_name'], _PS_UPLOAD_DIR_.$this->name.'/'.$bg_image))
                            $this->validation_errors[] = Tools::displayError('Error move uploaded file');
                        else
                            Configuration::updateValue($this->_prefix_st.'BG_IMG', $this->name.'/'.$bg_image);
                    }
                }
            }
            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else 
            {
                $this->clearProductCategoriesSliderCache();
                if(isset($_POST['savesettingstproductcategoriessliderAndStay']))
                    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=4&setting'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules')); 
                else
                    $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
            }
                
        }
		
		if (Tools::isSubmit('updatestproductcategoriesslider') || Tools::isSubmit('addstproductcategoriesslider'))
		{
			$helper = $this->initForm();
			return $this->_html.$helper->generateForm($this->fields_form);
		}
		if (Tools::isSubmit('settingstproductcategoriesslider'))
		{
		    $this->initFieldsForm();
			$helper = $this->initFormSetting();
            
			return $this->_html.$helper->generateForm($this->fields_form_setting);
		}
		else if (Tools::isSubmit('deletestproductcategoriesslider'))
		{
			$product_categories_slider = new StProductCategoriesSliderClass((int)$id_st_product_categories_slider);
			if ($product_categories_slider->id)
                $product_categories_slider->delete();
            
            $this->clearProductCategoriesSliderCache();
                
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else
		{
			$helper = $this->initList();
            $this->_html .= '<script type="text/javascript">var currentIndex="'.AdminController::$currentIndex.'&configure='.$this->name.'";</script>';
			return $this->_html.$helper->generateList(StProductCategoriesSliderClass::getListContent(), $this->fields_list);
		}
	}
    public function updateCatePerRow() {
        $arr = $this->findCateProPer();
        foreach ($arr as $v)
            if($gv = Tools::getValue($v['id']))
                Configuration::updateValue('STSN_'.strtoupper($v['id']), (int)$gv);
    }
    public static function getCategories()
    {
        $module = new StProductCategoriesSlider();
        $root_category = Category::getRootCategory();
        $category_arr = array();
        $module->getCategoryOption($category_arr,$root_category->id);
        return $category_arr;
    }
    private function getCategoryOption(&$category_arr,$id_category = 1, $id_lang = false, $id_shop = false, $recursive = true,$selected_id_category=0)
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
		$category_arr[] = array(
            'id' => $category->id,
            'name' => (isset($spacer) ? $spacer : '').$category->name.' ('.$shop->name.')',
        );
        
		if (isset($children) && count($children))
			foreach ($children as $child)
			{
				$this->getCategoryOption($category_arr,(int)$child['id_category'], (int)$id_lang, (int)$child['id_shop'],$recursive,$selected_id_category);
			}
	}
    public static function getSortBy()
    {
        $sort_by = self::$sort_by;
        if(Configuration::get('PS_CATALOG_MODE'))
            unset($sort_by['5'],$sort_by['6']);                
        return $sort_by;
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
	protected function initForm()
	{
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Product categories sldier'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
				array(
					'type' => 'select',
					'label' => $this->l('Category:'),
					'name' => 'id_category',
                    'required' => true,
					'options' => array(
						'query' => $this->getCategories(),
        				'id' => 'id',
        				'name' => 'name',
						'default' => array(
							'value' => 0,
							'label' => $this->l('please select')
						)
					),
				),
				array(
					'type' => 'checkbox',
					'label' => $this->l('Display on:'),
					'name' => 'display_on',
                    'required' => true,
					'values' => array(
						'query' => $this->_hooks,
        				'id' => 'id',
        				'name' => 'name',
					),
				),
                array(
					'type' => 'text',
					'label' => $this->l('Define the number of products to be displayed:'),
					'name' => 'product_nbr',
                    'default_value' => 8,
                    'required' => true,
                    'desc' => $this->l('Define the number of products that you would like to display on homepage (default: 8).'),
                    'class' => 'fixed-width-sm'
				),
                'sort_by' => array(
                    'type' => 'select',
					'label' => $this->l('Sort by:'),
					'name' => 'product_order',
                    'required' => true,
					'options' => array(
						'query' => $this->getSortBy(),
        				'id' => 'id',
        				'name' => 'name',
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
					'type' => 'html',
                    'id' => 'a_cancel',
					'label' => '',
					'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
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

        $this->fields_form[1]['form'] = array(
            'legend' => array(
                'title' => $this->l('Advanced settings'),
                'icon' => 'icon-cogs'                
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Top padding:'),
                    'name' => 'top_padding',
                    'default_value' => '',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Leave it empty to use the default value.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Bottom padding:'),
                    'name' => 'bottom_padding',
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
                    'type' => 'color',
                    'label' => $this->l('Background color:'),
                    'name' => 'bg_color',
                    'class' => 'color',
                    'size' => 20,
                 ),
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
                    'default_value' => 0,
                    'desc' => $this->l('Speed to move relative to vertical scroll. Example: 0.1 is one tenth the speed of scrolling, 2 is twice the speed of scrolling.'),
                    'class' => 'fixed-width-sm'
                ),

                array(
                    'type' => 'radio',
                    'label' => $this->l('Heading alignment:'),
                    'name' => 'title_alignment',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'left',
                            'value' => 0,
                            'label' => $this->l('Left')),
                        array(
                            'id' => 'center',
                            'value' => 1,
                            'label' => $this->l('Center')),
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Remove heading background:'),
                    'name' => 'title_no_bg',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'title_no_bg_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'title_no_bg_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'desc' => $this->l('If the heading is center aligned, heading background will be removed automatically.'),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Heading font size:'),
                    'name' => 'title_font_size',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ), 
                 array(
                    'type' => 'color',
                    'label' => $this->l('Heading color:'),
                    'name' => 'title_color',
                    'class' => 'color',
                    'size' => 20,
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Heading hover color:'),
                    'name' => 'title_hover_color',
                    'class' => 'color',
                    'size' => 20,
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Text color:'),
                    'name' => 'text_color',
                    'class' => 'color',
                    'size' => 20,
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Price color:'),
                    'name' => 'price_color',
                    'class' => 'color',
                    'size' => 20,
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Product name hover color:'),
                    'name' => 'link_hover_color',
                    'class' => 'color',
                    'size' => 20,
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Grid hover background:'),
                    'name' => 'grid_hover_bg',
                    'class' => 'color',
                    'size' => 20,
                 ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Prev/next button:'),
                    'name' => 'direction_nav',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'top-right',
                            'value' => 0,
                            'label' => $this->l('Top right-hand side')),
                        array(
                            'id' => 'each_side',
                            'value' => 1,
                            'label' => $this->l('Each side')),
                    ),
                ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Prev/next color:'),
                    'name' => 'direction_color',
                    'class' => 'color',
                    'size' => 20,
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Prev/next background:'),
                    'name' => 'direction_bg',
                    'class' => 'color',
                    'size' => 20,
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Prev/next hover background:'),
                    'name' => 'direction_hover_bg',
                    'class' => 'color',
                    'size' => 20,
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Prev/next disabled background:'),
                    'name' => 'direction_disabled_bg',
                    'class' => 'color',
                    'size' => 20,
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
        
        $id_st_product_categories_slider = (int)Tools::getValue('id_st_product_categories_slider');
		$product_categories_slider = new StProductCategoriesSliderClass($id_st_product_categories_slider);
        
        if($product_categories_slider->id)
        {
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_product_categories_slider');

            if ($product_categories_slider->bg_img)
            {
                StProductCategoriesSliderClass::fetchMediaServer($product_categories_slider->bg_img);
                $this->fields_form[1]['form']['input']['bg_img_field']['image'] = '<img width=200 src="'.($product_categories_slider->bg_img).'" /><p><a class="btn btn-default delete_slider_image" href="javascript:;" data-identi="'.(int)$product_categories_slider->id.'"><i class="icon-trash"></i> Delete</a></p>';
            }
        }

        if(Configuration::get('ST_PRO_CATE_RANDOM'))
            unset($this->fields_form[0]['form']['input']['sort_by']);

        if(Configuration::get('ST_PRO_CATE_TABS'))
            unset($this->fields_form[1]);

        $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestproductcategoriesslider';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($product_categories_slider),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);       

        foreach($this->_hooks as $v)
            $helper->tpl_vars['fields_value']['display_on_'.$v['id']] = (int)$v['val']&(int)$product_categories_slider->display_on;   

		return $helper;
	}
    
    public function initFieldsForm()
    {
        $this->fields_form_setting[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Tab'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Tab:'),
                    'name' => 'tabs',
                    'is_bool' => true,
                    'default_value' => 1,
                    'values' => array(
                        array(
                            'id' => 'tabs_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'tabs_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
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

        $this->fields_form_setting[1]['form'] = array(
            'legend' => array(
                'title' => $this->l('Tab settings'),
                'icon' => 'icon-cogs'
            ),
            'description' => $this->l('These settings will take effect if you have the above "Tab" option enabled.'),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Top padding:'),
                    'name' => 'top_padding',
                    'validation' => 'isNullOrUnsignedId',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Leave it empty to use the default value.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Bottom padding:'),
                    'name' => 'bottom_padding',
                    'validation' => 'isNullOrUnsignedId',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Leave it empty to use the default value.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Top spacing:'),
                    'name' => 'top_margin',
                    'validation' => 'isNullOrUnsignedId',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Leave it empty to use the default value.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Bottom spacing:'),
                    'name' => 'bottom_margin',
                    'validation' => 'isNullOrUnsignedId',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Leave it empty to use the default value.'),
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
                    'default_value' => 0,
                    'desc' => $this->l('Speed to move relative to vertical scroll. Example: 0.1 is one tenth the speed of scrolling, 2 is twice the speed of scrolling.'),
                    'validation' => 'isFloat',
                    'class' => 'fixed-width-sm'
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
                    'type' => 'color',
                    'label' => $this->l('Heading hover color:'),
                    'name' => 'title_hover_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'switch',
                    'label' => $this->l('Remove heading background:'),
                    'name' => 'tab_title_no_bg',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'tab_title_no_bg_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'tab_title_no_bg_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'desc' => $this->l('If the heading is center aligned, heading background will be removed automatically.'),
                    'validation' => 'isBool',
                ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Text color:'),
                    'name' => 'text_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Price color:'),
                    'name' => 'price_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Product name hover color:'),
                    'name' => 'link_hover_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Grid hover background:'),
                    'name' => 'grid_hover_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Prev/next color:'),
                    'name' => 'direction_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Prev/next background:'),
                    'name' => 'direction_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Prev/next hover background:'),
                    'name' => 'direction_hover_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Prev/next disabled background:'),
                    'name' => 'direction_disabled_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
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

        $this->fields_form_setting[2]['form'] = array(
            'legend' => array(
                'title' => $this->l('Sliders on homepage'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
					'type' => 'switch',
					'label' => $this->l('Show products on your homepage randomly:'),
					'name' => 'random',
					'is_bool' => true,
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'random_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'random_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
                    'type' => 'radio',
                    'label' => $this->l('How to display products:'),
                    'name' => 'grid',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'grid_slider',
                            'value' => 0,
                            'label' => $this->l('Slider')),
                        array(
                            'id' => 'grid_grid',
                            'value' => 1,
                            'label' => $this->l('Grid view')),
                        array(
                            'id' => 'grid_samll',
                            'value' => 2,
                            'label' => $this->l('Simple layout')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'html',
                    'id' => 'pro_cate_pro_per_0',
                    'label'=> $this->l('The number of columns'),
                    'name' => $this->BuildDropListGroup($this->findCateProPer()),
                ),
                array(
					'type' => 'switch',
					'label' => $this->l('Autoplay:'),
					'name' => 'slideshow',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'slideshow_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'slideshow_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'text',
					'label' => $this->l('Time:'),
					'name' => 's_speed',
                    'default_value' => 7000,
                    'required' => true,
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Transition period:'),
					'name' => 'a_speed',
                    'default_value' => 400,
                    'required' => true,
                    'desc' => $this->l('The period, in milliseconds, of the transition effect.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Pause On Hover:'),
					'name' => 'pause_on_hover',
                    'default_value' => 1,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'pause_on_hover_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'pause_on_hover_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				),
                array(
					'type' => 'select',
        			'label' => $this->l('Easing method:'),
        			'name' => 'easing',
                    'options' => array(
        				'query' => self::$easing,
        				'id' => 'id',
        				'name' => 'name',
        			),
                    'desc' => $this->l('The type of easing applied to the transition animation'),
                    'validation' => 'isUnsignedInt',
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Loop:'),
					'name' => 'loop',
                    'default_value' => 0,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'loop_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'loop_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('"No" if you want to perform the animation once; "Yes" to loop the animation'),
                    'validation' => 'isBool',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Move:'),
					'name' => 'move',
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'move_on',
							'value' => 1,
							'label' => $this->l('1 item')),
						array(
							'id' => 'move_off',
							'value' => 0,
							'label' => $this->l('All visible items')),
					),
                    'validation' => 'isBool',
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide slideshow on mobile devices:'),
                    'name' => 'hide_mob',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'hide_mob_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'hide_mob_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'desc' => $this->l('if set to Yes, slider will be hidden on mobile devices (if screen width is less than 768 pixels).'),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Display product short description:'),
                    'name' => 'display_sd',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'display_sd_off',
                            'value' => 0,
                            'label' => $this->l('NO')),
                        array(
                            'id' => 'display_sd_on',
                            'value' => 1,
                            'label' => $this->l('Yes, 120 characters')),
                        array(
                            'id' => 'display_sd_full',
                            'value' => 2,
                            'label' => $this->l('Yes, full short description')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display countdown timers:'),
                    'name' => 'countdown_on',
                    'is_bool' => true,
                    'default_value' => 1,
                    'desc' => $this->l('Make sure the Coundown module is installed & enabled.'),
                    'values' => array(
                        array(
                            'id' => 'countdown_on_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'countdown_on_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
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

		$this->fields_form_setting[3]['form'] = array(
			'legend' => array(
				'title' => $this->l('Sliders on the left/right column'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
                    'type' => 'radio',
                    'label' => $this->l('How to display products:'),
                    'name' => 'display_pro_col',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'display_pro_col_0',
                            'value' => 0,
                            'label' => $this->l('Compact')),
                        array(
                            'id' => 'display_pro_col_1',
                            'value' => 1,
                            'label' => $this->l('Large')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
					'type' => 'select',
        			'label' => $this->l('The number of columns:'),
        			'name' => 'items_col',
                    'options' => array(
        				'query' => self::$items,
        				'id' => 'id',
        				'name' => 'name',
        			),
                    'desc' => array(
                        $this->l('Set number of columns for default screen resolution(980px).'),
                    ),
                    'validation' => 'isUnsignedInt',
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Autoplay:'),
					'name' => 'slideshow_col',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'slideshow_col_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'slideshow_col_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'text',
					'label' => $this->l('Time:'),
					'name' => 's_speed_col',
                    'default_value' => 7000,
                    'required' => true,
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Transition period:'),
					'name' => 'a_speed_col',
                    'default_value' => 400,
                    'required' => true,
                    'desc' => $this->l('The period, in milliseconds, of the transition effect.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Pause On Hover:'),
					'name' => 'pause_on_hover_col',
                    'default_value' => 1,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'pause_on_hover_col_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'pause_on_hover_col_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				),
                array(
					'type' => 'select',
        			'label' => $this->l('Easing method:'),
        			'name' => 'easing_col',
                    'options' => array(
        				'query' => self::$easing,
        				'id' => 'id',
        				'name' => 'name',
        			),
                    'desc' => $this->l('The type of easing applied to the transition animation'),
                    'validation' => 'isUnsignedInt',
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Loop:'),
					'name' => 'loop_col',
                    'default_value' => 0,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'loop_col_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'loop_col_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('"No" if you want to perform the animation once; "Yes" to loop the animation'),
                    'validation' => 'isBool',
				),
                array(
					'type' => 'hidden',
					'name' => 'move_col',
                    'default_value' => 1,
                    'validation' => 'isBool',
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Hide slideshow on mobile devices:'),
					'name' => 'hide_mob_col',
                    'default_value' => 0,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'hide_mob_col_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'hide_mob_col_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('if set to Yes, slider will be hidden on mobile devices (if screen width is less than 768 pixels).'),
                    'validation' => 'isBool',
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display countdown timers:'),
                    'name' => 'countdown_on_col',
                    'is_bool' => true,
                    'default_value' => 1,
                    'desc' => $this->l('Make sure the Coundown module is installed & enabled.'),
                    'values' => array(
                        array(
                            'id' => 'countdown_on_col_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'countdown_on_col_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
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
        
        if ($bg_img = Configuration::get($this->_prefix_st.'BG_IMG'))
        {
            StProductCategoriesSliderClass::fetchMediaServer($bg_img);
            $this->fields_form_setting[1]['form']['input']['bg_img_field']['image'] = '<img width=200 src="'.($bg_img).'" /><p><a class="btn btn-default st_delete_image" href="javascript:;"><i class="icon-trash"></i> Delete</a></p>';
        }
    }
    protected function initFormSetting()
	{
	    $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savesettingstproductcategoriesslider';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper;
	}
    public static function displayCategory($value, $row)
	{
        if(!$value)
            return '-';
        $id_lang = (int)Context::getContext()->language->id;
        $category = new Category((int)$value,$id_lang);
        if($category->id)
            return $category->name;
		return '';
	}
	protected function initList()
	{
		$this->fields_list = array(
			'id_st_product_categories_slider' => array(
				'title' => $this->l('Id'),
				'width' => 120,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'id_category' => array(
				'title' => $this->l('Category'),
				'width' => 140,
				'type' => 'text',
				'callback' => 'displayCategory',
				'callback_object' => 'StProductCategoriesSlider',
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
				'active' => 'status',
				'type' => 'bool',
				'width' => 25,
                'search' => false,
                'orderby' => false
            ),
		);

		if (Shop::isFeatureActive())
			$this->fields_list['id_shop'] = array(
                'title' => $this->l('ID Shop'), 
                'align' => 'center', 
                'width' => 25, 
                'type' => 'int',
                'search' => false,
                'orderby' => false
                );

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_st_product_categories_slider';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&add'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add new')
		);
		$helper->toolbar_btn['edit'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&setting'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Setting'),
		);
		$helper->title = $this->displayName;
		$helper->table = $this->name;
		$helper->orderBy = 'position';
		$helper->orderWay = 'ASC';
	    $helper->position_identifier = 'id_st_product_categories_slider';
        
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    public function hookDisplayHomeTop($params)
    {
        return $this->hookDisplayHome($params, $this->getDisplayOn(__FUNCTION__));
    }
    public function hookDisplayHomeBottom($params)
    {
        return $this->hookDisplayHome($params, $this->getDisplayOn(__FUNCTION__));
    }
	public function hookDisplayHome($params,$display_on=0,$flag=0)
	{
	    if (!$display_on)
            $display_on = $this->getDisplayOn(__FUNCTION__);
        $random = Configuration::get('ST_PRO_CATE_RANDOM');
        if(Configuration::get('ST_PRO_CATE_TABS'))
        {
            $this->smarty->assign(array(
                'has_background_img'     => ((int)Configuration::get($this->_prefix_st.'BG_PATTERN') || Configuration::get($this->_prefix_st.'BG_IMG')) ? 1 : 0,
                'speed'          => (float)Configuration::get($this->_prefix_st.'SPEED'),
            ));
                
            if(Configuration::get('ST_PRO_CATE_GRID') || $random)
            {
                if(!$this->_prepareHook(0, $display_on))
                    return false;
                $this->smarty->assign(array(
                    'column_slider'         => false,
                    'homeverybottom'        => ($flag==2 ? true : false),
                    'hook_hash'             => $display_on,
                    'pro_per_lg'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_LG_0'),
                    'pro_per_md'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_MD_0'),
                    'pro_per_sm'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_SM_0'),
                    'pro_per_xs'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_XS_0'),
                    'pro_per_xxs'      => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_XXS_0'),
                ));
                return $this->display(__FILE__, 'stproductcategoriesslider_tab.tpl');
            }
            else
            {
                if (!$this->isCached('stproductcategoriesslider_tab.tpl', $this->stGetCacheId($display_on)))
            	{
                    if(!$this->_prepareHook(0, $display_on))
                        return false;
                    $this->smarty->assign(array(
                        'column_slider'         => false,
                        'homeverybottom'        => ($flag==2 ? true : false),
                        'hook_hash'             => $display_on,
                        'pro_per_lg'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_LG_0'),
                        'pro_per_md'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_MD_0'),
                        'pro_per_sm'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_SM_0'),
                        'pro_per_xs'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_XS_0'),
                        'pro_per_xxs'      => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_XXS_0'),
                    ));
                }
        		return $this->display(__FILE__, 'stproductcategoriesslider_tab.tpl', $this->stGetCacheId($display_on));
            }
            return false;
        }
        else
        {
            if(Configuration::get('ST_PRO_CATE_GRID') || $random)
            {
                if(!$this->_prepareHook(0, $display_on))
                    return false;
                $this->smarty->assign(array(
                    'column_slider'         => false,
                    'homeverybottom'        => ($flag==2 ? true : false),
                    'hook_hash'             => $display_on,
                    'pro_per_lg'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_LG_0'),
                    'pro_per_md'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_MD_0'),
                    'pro_per_sm'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_SM_0'),
                    'pro_per_xs'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_XS_0'),
                    'pro_per_xxs'      => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_XXS_0'),
                ));
                return $this->display(__FILE__, 'stproductcategoriesslider.tpl');
            }else{
                if(!$this->isCached('stproductcategoriesslider.tpl', $this->stGetCacheId($display_on)))
                {
                    if(!$this->_prepareHook(0, $display_on))
                        return false;
                    $this->smarty->assign(array(
                        'column_slider'         => false,
                        'homeverybottom'        => ($flag==2 ? true : false),
                        'hook_hash'             => $display_on,
                        'pro_per_lg'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_LG_0'),
                        'pro_per_md'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_MD_0'),
                        'pro_per_sm'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_SM_0'),
                        'pro_per_xs'       => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_XS_0'),
                        'pro_per_xxs'      => (int)Configuration::get('STSN_PRO_CATE_PRO_PER_XXS_0'),
                    ));
                }
                return $this->display(__FILE__, 'stproductcategoriesslider.tpl', $this->stGetCacheId($display_on));
            }
            return false;
        }
	}
    public function hookDisplayBottomColumn($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        return $this->hookDisplayHome($params, $this->getDisplayOn(__FUNCTION__));
    }
    public function hookDisplayFullWidthTop($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        return $this->hookDisplayHome($params, $this->getDisplayOn(__FUNCTION__) ,2);
    }
    public function hookDisplayFullWidthTop2($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        return $this->hookDisplayHome($params, $this->getDisplayOn(__FUNCTION__) ,2);
    }
    public function hookDisplayHomeVeryBottom($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        return $this->hookDisplayHome($params, $this->getDisplayOn(__FUNCTION__), 2);
    }

	public function hookDisplayHomeSecondaryLeft($params)
	{
        return $this->hookDisplayHome($params, $this->getDisplayOn(__FUNCTION__)); 
    }
    
    public function hookDisplayHomeTertiaryLeft($params)
    {
        return $this->hookDisplayHome($params, $this->getDisplayOn(__FUNCTION__));
    }
    public function hookDisplayHomeTertiaryRight($params)
    {
        return $this->hookDisplayHome($params, $this->getDisplayOn(__FUNCTION__));
    }
    
	public function hookDisplayAnywhere($params)
	{
	    if(!isset($params['caller']) || $params['caller']!=$this->name)
            return false;
	    if(isset($params['function']) && method_exists($this,$params['function']))
            return call_user_func(array($this,$params['function']));
    }
    
	public function hookDisplayLeftColumn($params, $display_on=0)
	{
	    if (!$display_on)
            $display_on = $this->getDisplayOn(__FUNCTION__);
	    if (!$this->isCached('stproductcategoriesslider.tpl', $this->stGetCacheId($display_on)))
    	{
            if(!$this->_prepareHook(1, $display_on))
                return false;
            $this->smarty->assign(array(
                'column_slider'         => true,
                'hook_hash'             => $display_on,
            ));
        }
		return $this->display(__FILE__, 'stproductcategoriesslider.tpl', $this->stGetCacheId($display_on));
	}
	public function hookDisplayRightColumn($params)
    {
        return $this->hookDisplayLeftColumn($params, $this->getDisplayOn(__FUNCTION__));
    }
    
	public function hookDisplayHomeSecondaryRight($params)
    {
        return $this->hookDisplayLeftColumn($params, $this->getDisplayOn(__FUNCTION__));
    }
    
	public function hookDisplayProductSecondaryColumn($params)
	{
        return $this->hookDisplayLeftColumn($params, $this->getDisplayOn(__FUNCTION__));
	}
    
    private function _prepareHook($col=0,$display_on=0)
    {
        $homeSize = Image::getSize(ImageType::getFormatedName('home'));
        
        $ext = $col ? '_COL' : '';
        
        $key = $display_on ? $display_on : 0;
        if (!$display_on)
            return false;
        if (isset(self::$cache_product_categories[$key]) && self::$cache_product_categories[$key])
            $categories = self::$cache_product_categories[$key];
        else
        {
            $categories = StProductCategoriesSliderClass::getListContent(1,$display_on);
            self::$cache_product_categories[$key] = $categories;
        }
               
        if(!is_array($categories) || !count($categories))     
            return false;
        $product_categories = array(); 
        
        $module_stthemeeditor = Module::getInstanceByName('stthemeeditor');
		if ($module_stthemeeditor && $module_stthemeeditor->id)
			$id_module_stthemeeditor = $module_stthemeeditor->id;
                    
        $module_sthoverimage = Module::getInstanceByName('sthoverimage');
        if ($module_sthoverimage && $module_sthoverimage->id)
            $id_module_sthoverimage = $module_sthoverimage->id;
            
	    $random = Configuration::get('ST_PRO_CATE_RANDOM');
        foreach($categories as $v)
        {
            $order_by = $random ? null : (isset(self::$sort_by[$v['product_order']]) ? self::$sort_by[$v['product_order']]['orderBy'] : null);
            $order_way = $random ? null : (isset(self::$sort_by[$v['product_order']]) ? self::$sort_by[$v['product_order']]['orderWay'] : null);
            
    		$category = new Category((int)$v['id_category'], (int)$this->context->language->id);
            $products = $category->getProducts($this->context->language->id, 1, (int)$v['product_nbr'], $order_by, $order_way, false, true, (bool)$random, (int)$v['product_nbr']);
            
            if(is_array($products) && count($products))
            {
                foreach($products as &$product)
                {
                    if(isset($id_module_stthemeeditor))
                    {
                        $product['pro_a_wishlist'] = Hook::exec('displayAnywhere', array('function'=>'getAddToWhishlistButton','id_product'=>$product['id_product'],'show_icon'=>0,'caller'=>'stthemeeditor'), $id_module_stthemeeditor);
                        $product['pro_rating_average'] = Hook::exec('displayAnywhere', array('function'=>'getProductRatingAverage','id_product'=>$product['id_product'],'caller'=>'stthemeeditor'), $id_module_stthemeeditor);
                    }
                    if(isset($id_module_sthoverimage))
                    {
                        $product['hover_image'] = Hook::exec('displayAnywhere', array('function'=>'getHoverImage','id_product'=>$product['id_product'],'product_link_rewrite'=>$product['link_rewrite'],'product_name'=>$product['name'],'home_default_height'=>$homeSize['height'],'home_default_width'=>$homeSize['width'],'caller'=>'sthoverimage'), $id_module_sthoverimage);
                    }
                }
            }
            
            $product_categories[] = array(
                'id_category' => $category->id,
                'link_rewrite' => $category->link_rewrite,
                'name'  => $category->name,
                'description'  => $category->description,
                'products' => $products,
                'direction_nav' => isset($v['direction_nav']) ? $v['direction_nav'] : 0,
            );
        }
        
        $easing = Configuration::get('ST_PRO_CATE_EASING'.$ext);
        ($easing===false && $col) && $easing = Configuration::get('ST_PRO_CATE_EASING');
        
        $slideshow = Configuration::get('ST_PRO_CATE_SLIDESHOW'.$ext);
        ($slideshow===false && $col) && $slideshow = Configuration::get('ST_PRO_CATE_SLIDESHOW');
        
        $s_speed = Configuration::get('ST_PRO_CATE_S_SPEED'.$ext);
        ($s_speed===false && $col) && $s_speed = Configuration::get('ST_PRO_CATE_S_SPEED');
        
        $a_speed = Configuration::get('ST_PRO_CATE_A_SPEED'.$ext);
        ($a_speed===false && $col) && $a_speed = Configuration::get('ST_PRO_CATE_A_SPEED');
        
        $pause_on_hover = Configuration::get('ST_PRO_CATE_PAUSE_ON_HOVER'.$ext);
        ($pause_on_hover===false && $col) && $pause_on_hover = Configuration::get('ST_PRO_CATE_PAUSE_ON_HOVER');
        
        $loop = Configuration::get('ST_PRO_CATE_LOOP'.$ext);
        ($loop===false && $col) && $loop = Configuration::get('ST_PRO_CATE_LOOP');
        
        $move = Configuration::get('ST_PRO_CATE_MOVE'.$ext);
        ($move===false && $col) && $move = Configuration::get('ST_PRO_CATE_MOVE');
        
        $items = Configuration::get('ST_PRO_CATE_ITEMS_COL');
        
        $hide_mob = Configuration::get('ST_PRO_CATE_HIDE_MOB'.$ext);
        ($hide_mob===false && $col) && $hide_mob = Configuration::get('ST_PRO_CATE_HIDE_MOB');

        $display_sd = Configuration::get('ST_PRO_CATE_DISPLAY_SD');
                
        $this->smarty->assign(array(
            'product_categories'      =>$product_categories,
            'add_prod_display'        => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
            'homeSize'                => $homeSize,
            'mediumSize'              => Image::getSize(ImageType::getFormatedName('medium')),
            'thumbSize'               => Image::getSize(ImageType::getFormatedName('thumb')),
            'pro_cate_easing'         => self::$easing[$easing]['name'],
            'pro_cate_slideshow'      => $slideshow,
            'pro_cate_s_speed'        => $s_speed,
            'pro_cate_a_speed'        => $a_speed,
            'pro_cate_pause_on_hover' => $pause_on_hover,
            'pro_cate_loop'           => $loop,
            'pro_cate_move'           => $move,
            'slider_items'            => $items,
            'hide_mob'                => (int)$hide_mob,
            'display_sd'              => (int)$display_sd,
            'display_as_grid'         => Configuration::get('ST_PRO_CATE_GRID'),
            'hook_hash'               => $display_on,
            'display_pro_col'         => Configuration::get('ST_PRO_CATE_DISPLAY_PRO_COL'),
            'countdown_on'            => Configuration::get('ST_PRO_CATE_COUNTDOWN_ON'.$ext)
        ));
        return true;
    }
    
    public function hookDisplayHeader($params)
    {
        if (!$this->isCached('header.tpl', $this->getCacheId()))
        {
            if(Configuration::get('ST_PRO_CATE_TABS'))
            {
                $custom_css = '';
            
                $group_css = '';
                $title_block_no_bg = '.pc_slider_block_container .title_block, .pc_slider_block_container .nav_top_right .flex-direction-nav,.pc_slider_block_container .title_block a, .pc_slider_block_container .title_block span{background:none;}';
            
                
                if ($bg_color = Configuration::get($this->_prefix_st.'BG_COLOR'))
                    $group_css .= 'background-color:'.$bg_color.';';
                if ($bg_img = Configuration::get($this->_prefix_st.'BG_IMG'))
                {
                    $this->fetchMediaServer($bg_img);
                    $group_css .= 'background-image: url('.$bg_img.');';
                }
                elseif ($bg_pattern = Configuration::get($this->_prefix_st.'BG_PATTERN'))
                {
                    $img = _MODULE_DIR_.'stthemeeditor/patterns/'.$bg_pattern.'.png';
                    $img = $this->context->link->protocol_content.Tools::getMediaServer($img).$img;
                    $group_css .= 'background-image: url('.$img.');';
                }
                if($group_css)
                    $custom_css .= '.pc_slider_block_container{background-attachment:fixed;'.$group_css.'}'.$title_block_no_bg;
                
                if ($top_padding = (int)Configuration::get($this->_prefix_st.'TOP_PADDING'))
                    $custom_css .= '.pc_slider_block_container{padding-top:'.$top_padding.'px;}';
                if ($bottom_padding = (int)Configuration::get($this->_prefix_st.'BOTTOM_PADDING'))
                    $custom_css .= '.pc_slider_block_container{padding-bottom:'.$bottom_padding.'px;}';

                $top_margin = Configuration::get($this->_prefix_st.'TOP_MARGIN');
                if($top_margin || $top_margin!==null)
                    $custom_css .= '.pc_slider_block_container{margin-top:'.$top_margin.'px;}';
                $bottom_margin = Configuration::get($this->_prefix_st.'BOTTOM_MARGIN');
                if($bottom_margin || $bottom_margin!==null)
                    $custom_css .= '.pc_slider_block_container{margin-bottom:'.$bottom_margin.'px;}';

                if ($title_color = Configuration::get($this->_prefix_st.'TITLE_COLOR'))
                    $custom_css .= '.pc_slider_block_container.block .title_block a, .pc_slider_block_container.block .title_block span{color:'.$title_color.';}';
                if (Configuration::get($this->_prefix_st.'TAB_TITLE_NO_BG'))
                    $custom_css .= $title_block_no_bg;
                if ($title_hover_color = Configuration::get($this->_prefix_st.'TITLE_HOVER_COLOR'))
                    $custom_css .= '.pc_slider_block_container.block .title_block a:hover{color:'.$title_hover_color.';}';

                if ($text_color = Configuration::get($this->_prefix_st.'TEXT_COLOR'))
                    $custom_css .= '.pc_slider_block_container .s_title_block a,
                    .pc_slider_block_container .price,
                    .pc_slider_block_container .old_price,
                    .pc_slider_block_container .product_desc{color:'.$text_color.';}';

                if ($price_color = Configuration::get($this->_prefix_st.'PRICE_COLOR'))
                    $custom_css .= '.pc_slider_block_container .price{color:'.$price_color.';}';
                if ($link_hover_color = Configuration::get($this->_prefix_st.'LINK_HOVER_COLOR'))
                    $custom_css .= '.pc_slider_block_container .s_title_block a:hover{color:'.$link_hover_color.';}';

                if ($grid_hover_bg = Configuration::get($this->_prefix_st.'GRID_HOVER_BG'))
                    $custom_css .= '.pc_slider_block_container .products_slider .ajax_block_product:hover .pro_second_box, .pc_slider_block_container .product_list.grid .ajax_block_product:hover .pro_second_box{background-color:'.$grid_hover_bg.';}';

                if ($direction_color = Configuration::get($this->_prefix_st.'DIRECTION_COLOR'))
                    $custom_css .= '.pc_slider_block_container .flex-direction-nav a{color:'.$direction_color.';}';
                if ($direction_bg = Configuration::get($this->_prefix_st.'DIRECTION_BG'))
                    $custom_css .= '.pc_slider_block_container .flex-direction-nav a{background-color:'.$direction_bg.';}';
                if ($direction_hover_bg = Configuration::get($this->_prefix_st.'DIRECTION_HOVER_BG'))
                    $custom_css .= '.pc_slider_block_container .flex-direction-nav a:hover{background-color:'.$direction_hover_bg.';}';
                if ($direction_disabled_bg = Configuration::get($this->_prefix_st.'DIRECTION_DISABLED_BG'))
                    $custom_css .= '.pc_slider_block_container .flex-direction-nav a.flex-disabled{background-color:'.$direction_disabled_bg.';}';

                if($custom_css)
                    $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
            }
            else
            {
                $custom_css_arr = StProductCategoriesSliderClass::getOptions();
                if (is_array($custom_css_arr) && count($custom_css_arr)) {
                    $custom_css = '';
                    foreach ($custom_css_arr as $v) {
                        $full_width = false;
                        foreach($this->_hooks AS $value)
                            if ($value['val'] == $v['display_on'] && isset($v['full_width']) && $v['full_width'])
                                $full_width = true;

                        $classname = $full_width ? '#product_categories_slider_container_'.$v['id_category'] : '#product_categories_slider_'.$v['id_category'];
                        
                        if(isset($v['top_spacing']) && ($v['top_spacing'] || $v['top_spacing']==='0'))
                            $custom_css .= $classname.'{margin-top:'.(int)$v['top_spacing'].'px;}';
                        if(isset($v['bottom_spacing']) && ($v['bottom_spacing'] || $v['bottom_spacing']==='0'))
                            $custom_css .= $classname.'{margin-bottom:'.(int)$v['bottom_spacing'].'px;}';
                        if(isset($v['top_padding']) && ($v['top_padding'] || $v['top_padding']===0))
                            $custom_css .= $classname.'{padding-top:'.(int)$v['top_padding'].'px;}';
                        if(isset($v['bottom_padding']) && ($v['bottom_padding'] || $v['bottom_padding']===0))
                            $custom_css .= $classname.'{padding-bottom:'.(int)$v['bottom_padding'].'px;}';

                        $group_css = '';
                        $title_block_no_bg = $classname.' .title_block, '.$classname.' .nav_top_right .flex-direction-nav,'.$classname.' .title_block a{background:none;}';
            
                        if ($v['bg_color'])
                            $group_css .= 'background-color:'.$v['bg_color'].';';
                        if ($v['bg_img'])
                        {
                            StProductCategoriesSliderClass::fetchMediaServer($v['bg_img']);
                            $group_css .= 'background-image: url('.$v['bg_img'].');';
                        }
                        elseif ($v['bg_pattern'])
                        {
                            $img = _MODULE_DIR_.'stthemeeditor/patterns/'.$v['bg_pattern'].'.png';
                            $img = $this->context->link->protocol_content.Tools::getMediaServer($img).$img;
                            $group_css .= 'background-image: url('.$img.');';
                        }
                        if($group_css)
                            $custom_css .= $classname.'{background-attachment:fixed;'.$group_css.'}'.$title_block_no_bg;
                        
                        if (isset($v['title_alignment']) && $v['title_alignment'])
                            $custom_css .= $classname.' .title_block{text-align:center;}';
                        if ($v['title_no_bg'])
                            $custom_css .= $title_block_no_bg;
                        if (isset($v['title_font_size']) && $v['title_font_size'])
                        {
                             $custom_css .= $classname.' .title_block{font-size:'.$v['title_font_size'].'px;}';
                             $custom_css .= $classname.' .nav_top_right .flex-direction-nav{top:-'.(round((round($v['title_font_size']*1.3)-24)/2)+24+22).'px;}';
                        }

                        if(isset($v['title_color']) && $v['title_color'])
                            $custom_css .= $classname.' .title_block a,'.$classname.' .title_block span{color:'.$v['title_color'].';}';
                        if(isset($v['title_hover_color']) && $v['title_hover_color'])
                            $custom_css .= $classname.' .title_block a:hover{color:'.$v['title_hover_color'].';}';
                        if(isset($v['text_color']) && $v['text_color'])
                            $custom_css .= $classname.' .s_title_block a,
                            '.$classname.' .price,
                            '.$classname.' .old_price,
                            '.$classname.' .product_desc{color:'.$v['text_color'].';}';
                        if(isset($v['price_color']) && $v['price_color'])
                            $custom_css .= $classname.' .price{color:'.$v['price_color'].';}';
                        if(isset($v['link_hover_color']) && $v['link_hover_color'])
                            $custom_css .= $classname.' .title_block a:hover{color:'.$v['link_hover_color'].';}';
                        if(isset($v['grid_hover_bg']) && $v['grid_hover_bg'])
                            $custom_css .= $classname.' .products_slider .ajax_block_product:hover .pro_second_box, '.$classname.' .product_list.grid .ajax_block_product:hover .pro_second_box{background-color:'.$v['grid_hover_bg'].';}';
                        if(isset($v['direction_color']) && $v['direction_color'])
                            $custom_css .= $classname.' .flex-direction-nav a{color:'.$v['direction_color'].';}';
                        if(isset($v['direction_bg']) && $v['direction_bg'])
                            $custom_css .= $classname.' .flex-direction-nav a{background-color:'.$v['direction_bg'].';}';
                        if(isset($v['direction_hover_bg']) && $v['direction_hover_bg'])
                            $custom_css .= $classname.' .flex-direction-nav a:hover{background-color:'.$v['direction_hover_bg'].';}';
                        if(isset($v['direction_disabled_bg']) && $v['direction_disabled_bg'])
                            $custom_css .= $classname.' .flex-direction-nav a.flex-disabled{background-color:'.$v['direction_disabled_bg'].';}';
                    }
                    if($custom_css)
                        $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
                }
            }
        }
        
        return $this->display(__FILE__, 'header.tpl', $this->getCacheId());
    }

    public function hookDisplayFooter($params, $display_on=0)
    {
        if (!$display_on)
            $display_on = $this->getDisplayOn(__FUNCTION__);
        if (!$display_on)
            return false;
	    if (!$this->isCached('stproductcategoriesslider-footer.tpl', $this->getCacheId()))
	    {
	        $key = 'FOT'.$display_on;
            if (isset(self::$cache_product_categories[$key]) && self::$cache_product_categories[$key])
                $categories = self::$cache_product_categories[$key];
            else
            {
                $categories = StProductCategoriesSliderClass::getListContent(1, $display_on);
                self::$cache_product_categories[$key] = $categories;    
            }
              
            if(!is_array($categories) || !count($categories))     
                return false;
            $product_categories = array(); 
                
            foreach($categories as $v)
            {
        		$category = new Category((int)$v['id_category'], (int)$this->context->language->id);
                $products = $category->getProducts($this->context->language->id, 1, (int)$v['product_nbr'], self::$sort_by[$v['product_order']]['orderBy'], self::$sort_by[$v['product_order']]['orderWay']);
                
                $product_categories[] = array(
                    'id_category' => $category->id,
                    'link_rewrite' => $category->link_rewrite,
                    'name'  => $category->name,
                    'products' => $products,
                );
            }
            
            $this->smarty->assign(array(
                'product_categories'=>$product_categories,
    			'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
    			'thumbSize' => Image::getSize(ImageType::getFormatedName('thumb')),
                'hook_hash' => $display_on,
    		));
	    }
		return $this->display(__FILE__, 'stproductcategoriesslider-footer.tpl', $this->getCacheId());
    }
    public function hookDisplayFooterTop($params)
    {
        return $this->hookDisplayFooter($params, $this->getDisplayOn(__FUNCTION__));
    }
    public function hookDisplayFooterSecondary($params)
    {
        return $this->hookDisplayFooter($params, $this->getDisplayOn(__FUNCTION__));        
    }
    
    public function hookActionObjectCategoryDeleteAfter($params)
    {
        $this->clearProductCategoriesSliderCache();
        
        if(!$params['object']->id)
            return ;
        $res = StProductCategoriesSliderClass::deleteByCategoryId($params['object']->id);
        return $res;
    }
	public function hookActionCategoryDelete($params)
	{
		return $this->hookActionObjectCategoryDeleteAfter($params);
	}
    public function hookActionObjectCategoryUpdateAfter($params)
    {
    }
    
    public function hookAddProduct($params)
	{
        $this->clearProductCategoriesSliderCache();
	}

	public function hookUpdateProduct($params)
	{
        $this->clearProductCategoriesSliderCache();
	}

	public function hookDeleteProduct($params)
	{
        $this->clearProductCategoriesSliderCache();
    }
    
	protected function stGetCacheId($key,$name = null)
	{
		$cache_id = parent::getCacheId($name);
		return $cache_id.'_'.$key;
	}
	private function clearProductCategoriesSliderCache()
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
    private function getConfigFieldsValues()
    {
        $fields_values = array(
            'tabs'=> Configuration::get('ST_PRO_CATE_TABS'),
            'random'=> Configuration::get('ST_PRO_CATE_RANDOM'),
            'easing'=> Configuration::get('ST_PRO_CATE_EASING'),
            'slideshow'=> Configuration::get('ST_PRO_CATE_SLIDESHOW'),
            's_speed'=> Configuration::get('ST_PRO_CATE_S_SPEED'),
            'a_speed'=> Configuration::get('ST_PRO_CATE_A_SPEED'),
            'pause_on_hover'=> Configuration::get('ST_PRO_CATE_PAUSE_ON_HOVER'),
            'loop'=> Configuration::get('ST_PRO_CATE_LOOP'),
            'move'=> Configuration::get('ST_PRO_CATE_MOVE'),
            'hide_mob'=> Configuration::get('ST_PRO_CATE_HIDE_MOB'),
            'display_sd'=> Configuration::get('ST_PRO_CATE_DISPLAY_SD'),
            'grid'       => Configuration::get('ST_PRO_CATE_GRID'),
            'countdown_on' => Configuration::get('ST_PRO_CATE_COUNTDOWN_ON'),
            
            'easing_col'=> Configuration::get('ST_PRO_CATE_EASING_COL'),
            'slideshow_col'=> Configuration::get('ST_PRO_CATE_SLIDESHOW_COL'),
            's_speed_col'=> Configuration::get('ST_PRO_CATE_S_SPEED_COL'),
            'a_speed_col'=> Configuration::get('ST_PRO_CATE_A_SPEED_COL'),
            'pause_on_hover_col'=> Configuration::get('ST_PRO_CATE_PAUSE_ON_HOVER_COL'),
            'loop_col'=> Configuration::get('ST_PRO_CATE_LOOP_COL'),
            'move_col'=> Configuration::get('ST_PRO_CATE_MOVE_COL'),
            'items_col'=> Configuration::get('ST_PRO_CATE_ITEMS_COL'),
            'display_pro_col'=> Configuration::get('ST_PRO_CATE_DISPLAY_PRO_COL'),
            'hide_mob_col'=> Configuration::get('ST_PRO_CATE_HIDE_MOB_COL'),
            'countdown_on_col' => Configuration::get('ST_PRO_CATE_COUNTDOWN_ON_COL'),  

            'top_padding'        => Configuration::get($this->_prefix_st.'TOP_PADDING'),
            'bottom_padding'     => Configuration::get($this->_prefix_st.'BOTTOM_PADDING'),
            'top_margin'         => Configuration::get($this->_prefix_st.'TOP_MARGIN'),
            'bottom_margin'      => Configuration::get($this->_prefix_st.'BOTTOM_MARGIN'),
            'bg_pattern'         => Configuration::get($this->_prefix_st.'BG_PATTERN'),
            'bg_img'             => Configuration::get($this->_prefix_st.'BG_IMG'),
            'bg_color'           => Configuration::get($this->_prefix_st.'BG_COLOR'),
            'speed'              => Configuration::get($this->_prefix_st.'SPEED'),

            'title_color'           => Configuration::get($this->_prefix_st.'TITLE_COLOR'),
            'title_hover_color'     => Configuration::get($this->_prefix_st.'TITLE_HOVER_COLOR'),
            'tab_title_no_bg'       => Configuration::get($this->_prefix_st.'TAB_TITLE_NO_BG'),
            'text_color'            => Configuration::get($this->_prefix_st.'TEXT_COLOR'),
            'price_color'           => Configuration::get($this->_prefix_st.'PRICE_COLOR'),
            'link_hover_color'      => Configuration::get($this->_prefix_st.'LINK_HOVER_COLOR'),
            'grid_hover_bg'         => Configuration::get($this->_prefix_st.'GRID_HOVER_BG'),
            'direction_color'         => Configuration::get($this->_prefix_st.'DIRECTION_COLOR'),
            'direction_bg'         => Configuration::get($this->_prefix_st.'DIRECTION_BG'),
            'direction_hover_bg'   => Configuration::get($this->_prefix_st.'DIRECTION_HOVER_BG'),
            'direction_disabled_bg' => Configuration::get($this->_prefix_st.'DIRECTION_DISABLED_BG'),  
        );
        return $fields_values;
    }
    public function BuildDropListGroup($group)
    {
        if(!is_array($group) || !count($group))
            return false;

        $html = '<div class="row">';
        foreach($group AS $key => $k)
        {
             if($key==3)
                 $html .= '</div><div class="row">';

             $html .= '<div class="col-xs-4 col-sm-3"><label '.(isset($k['tooltip']) ? ' data-html="true" data-toggle="tooltip" class="label-tooltip" data-original-title="'.$k['tooltip'].'" ':'').'>'.$k['label'].'</label>'.
             '<select name="'.$k['id'].'" 
             id="'.$k['id'].'" 
             class="'.(isset($k['class']) ? $k['class'] : 'fixed-width-md').'"'.
             (isset($k['onchange']) ? ' onchange="'.$k['onchange'].'"':'').' >';
            
            for ($i=1; $i < 7; $i++){
                $html .= '<option value="'.$i.'" '.(Configuration::get('STSN_'.strtoupper($k['id'])) == $i ? ' selected="selected"':'').'>'.$i.'</option>';
            }
                                
            $html .= '</select></div>';
        }
        return $html.'</div>';
    }
    public function findCateProPer()
    {
        return array(
            array(
                'id' => 'pro_cate_pro_per_lg_0',
                'label' => $this->l('Large devices'),
                'tooltip' => $this->l('Desktops (>1200px)'),
            ),
            array(
                'id' => 'pro_cate_pro_per_md_0',
                'label' => $this->l('Medium devices'),
                'tooltip' => $this->l('Desktops (>992px)'),
            ),
            array(
                'id' => 'pro_cate_pro_per_sm_0',
                'label' => $this->l('Small devices'),
                'tooltip' => $this->l('Tablets (>768px)'),
            ),
            array(
                'id' => 'pro_cate_pro_per_xs_0',
                'label' => $this->l('Extra small devices'),
                'tooltip' => $this->l('Phones (>480px)'),
            ),
            array(
                'id' => 'pro_cate_pro_per_xxs_0',
                'label' => $this->l('Extra extra small devices'),
                'tooltip' => $this->l('Phones (<480px)'),
            ),
        );
    }
    
    private function getDisplayOn($func = '')
    {
        $ret = 0;
        if (!$func)
            return $ret;
        foreach($this->_hooks AS $value)
            if ('hook'.strtolower($value['id']) == strtolower($func))
                return (int)$value['val'];
        return $ret;
    }
    
    public function processUpdatePositions()
	{
		if (Tools::getValue('action') == 'updatePositions' && Tools::getValue('ajax'))
		{
			$way = (int)(Tools::getValue('way'));
			$id = (int)(Tools::getValue('id'));
			$positions = Tools::getValue('st_product_categories_slider');
            $msg = '';
			if (is_array($positions))
				foreach ($positions as $position => $value)
				{
					$pos = explode('_', $value);

					if ((isset($pos[2])) && ((int)$pos[2] === $id))
					{
						if ($object = new StProductCategoriesSliderClass((int)$pos[2]))
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
    public function fetchMediaServer(&$slider)
    {
        $slider = _THEME_PROD_PIC_DIR_.$slider;
        $slider = context::getContext()->link->protocol_content.Tools::getMediaServer($slider).$slider;
    }
    public function get_prefix()
    {
        if (isset($this->_prefix_st) && $this->_prefix_st)
            return $this->_prefix_st;
        return false;
    }
}