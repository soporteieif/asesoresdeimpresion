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
    
require (dirname(__FILE__).'/StFeaturedCategoriesSliderClass.php');

class StFeaturedCategoriesSlider extends Module
{
    protected static $cache_fc_slider = false;
	private $_html = '';
    public $fields_list;
    public $fields_form;
    private $_baseUrl;
    private $spacer_size = '5';
    public $validation_errors = array();
    public  $fields_form_setting;
    private $_prefix_st = 'ST_PRO_CATE_F_C_S_';
    private $_prefix_stsn = 'STSN_';
    public $imgtype = array('jpg', 'gif', 'jpeg', 'png');
    protected static $access_rights = 0775;
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
	
	public function __construct()
	{
		$this->name          = 'stfeaturedcategoriesslider';
		$this->tab           = 'front_office_features';
		$this->version       = '1.0';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
		$this->bootstrap 	 = true;
		parent::__construct();
        
        $this->initHookArray();

		$this->displayName   = $this->l('Featured categories slider');
		$this->description   = $this->l('Display featured categories slider or grid on your homepage.');
	}
    
    private function initHookArray()
    {
        $this->_hooks = array(
            'Hooks' => array(
                array(
        			'id' => 'displayHome',
        			'val' => '1',
        			'name' => $this->l('displayHome')
        		),
        		array(
        			'id' => 'displayHomeTop',
        			'val' => '1',
        			'name' => $this->l('displayHomeTop')
        		),
                array(
        			'id' => 'displayHomeBottom',
        			'val' => '1',
        			'name' => $this->l('displayHomeBottom')
        		),
                array(
                    'id' => 'displayTopColumn',
                    'val' => '1',
                    'name' => $this->l('displayTopColumn')
                ),
                array(
                    'id' => 'displayBottomColumn',
                    'val' => '1',
                    'name' => $this->l('displayBottomColumn')
                ),
                array(
                    'id' => 'displayFullWidthBottom',
                    'val' => '1',
                    'name' => $this->l('displayFullWidthBottom')
                ),
                array(
                    'id' => 'displayFullWidthTop',
                    'val' => '1',
                    'name' => $this->l('displayFullWidthTop')
                ),
                array(
                    'id' => 'displayFullWidthTop2',
                    'val' => '1',
                    'name' => $this->l('displayFullWidthTop2')
                ),
                array(
                    'id' => 'displayHomeTertiaryLeft',
                    'val' => '1',
                    'name' => $this->l('displayHomeTertiaryLeft')
                ),
                array(
                    'id' => 'displayHomeTertiaryRight',
                    'val' => '1',
                    'name' => $this->l('displayHomeTertiaryRight')
                ),
        		array(
        			'id' => 'displayHomeSecondaryLeft',
        			'val' => '1',
        			'name' => $this->l('displayHomeSecondaryLeft')
        		)
            )
        );
    }
    
    private function saveHook()
    {
        foreach($this->_hooks AS $key => $values)
        {
            if (!$key)
                continue;
            foreach($values AS $value)
            {
                $id_hook = Hook::getIdByName($value['id']);
                
                if (Tools::getValue($key.'_'.$value['id']))
                {
                    if ($id_hook && Hook::getModulesFromHook($id_hook, $this->id))
                        continue;
                    if (!$this->isHookableOn($value['id']))
                        $this->validation_errors[] = $this->l('This module cannot be transplanted to '.$value['id'].'.');
                    else
                        $rs = $this->registerHook($value['id'], Shop::getContextListShopID());
                }
                else
                {
                    if($id_hook && Hook::getModulesFromHook($id_hook, $this->id))
                    {
                        $this->unregisterHook($id_hook, Shop::getContextListShopID());
                        $this->unregisterExceptions($id_hook, Shop::getContextListShopID());
                    } 
                }
            }
        }
        // clear module cache to apply new data.
        Cache::clean('hook_module_list');
    }

	public function install()
	{
	    if (!$this->installDB()
            || !parent::install()
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('actionCategoryAdd')
			|| !$this->registerHook('actionCategoryDelete')
			|| !$this->registerHook('actionCategoryUpdate')
			|| !$this->registerHook('displayHome')
            || !Configuration::updateValue($this->_prefix_st.'EASING', 0)
            || !Configuration::updateValue($this->_prefix_st.'SLIDESHOW', 0)
            || !Configuration::updateValue($this->_prefix_st.'S_SPEED', 7000)
            || !Configuration::updateValue($this->_prefix_st.'A_SPEED', 400)
            || !Configuration::updateValue($this->_prefix_st.'PAUSE_ON_HOVER', 1)
            || !Configuration::updateValue($this->_prefix_st.'LOOP', 0)
            || !Configuration::updateValue($this->_prefix_st.'MOVE', 0)
            || !Configuration::updateValue($this->_prefix_st.'HIDE_MOB', 0)
            || !Configuration::updateValue($this->_prefix_st.'AW_DISPLAY', 0)
            || !Configuration::updateValue($this->_prefix_st.'GRID', 0)
            || !Configuration::updateValue($this->_prefix_stsn.'FC_SLIDER_PER_LG_0', 4)
            || !Configuration::updateValue($this->_prefix_stsn.'FC_SLIDER_PER_MD_0', 4)
            || !Configuration::updateValue($this->_prefix_stsn.'FC_SLIDER_PER_SM_0', 3)
            || !Configuration::updateValue($this->_prefix_stsn.'FC_SLIDER_PER_XS_0', 2)
            || !Configuration::updateValue($this->_prefix_stsn.'FC_SLIDER_PER_XXS_0', 1)
            
            || !Configuration::updateValue($this->_prefix_st.'TOP_PADDING', '')
            || !Configuration::updateValue($this->_prefix_st.'BOTTOM_PADDING', '')
            || !Configuration::updateValue($this->_prefix_st.'TOP_MARGIN', '')
            || !Configuration::updateValue($this->_prefix_st.'BOTTOM_MARGIN', '')
            || !Configuration::updateValue($this->_prefix_st.'BG_PATTERN', 0)
            || !Configuration::updateValue($this->_prefix_st.'BG_IMG', '')
            || !Configuration::updateValue($this->_prefix_st.'BG_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'SPEED', 0)
            || !Configuration::updateValue($this->_prefix_st.'TITLE_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'TEXT_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'LINK_HOVER_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'GRID_HOVER_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_HOVER_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_DISABLED_BG', '')

            || !Configuration::updateValue($this->_prefix_st.'TITLE_ALIGNMENT', 0)
            || !Configuration::updateValue($this->_prefix_st.'TITLE_NO_BG', 0)
            || !Configuration::updateValue($this->_prefix_st.'TITLE_FONT_SIZE', 0)
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_NAV', 0))
            return false;
            
		$this->clearstfeaturedcategoryCache();
		return true;
	}

	public function installDb()
	{
		$return = true;
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_featured_category_slider` (
				`id_st_featured_category_slider` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_parent` int(10) NOT NULL DEFAULT 0,
                `level_depth` tinyint(3) unsigned NOT NULL DEFAULT 0,   
				`id_shop` int(10) unsigned NOT NULL,
                `id_category` int(10) unsigned NOT NULL DEFAULT 0,
                `position` int(10) unsigned NOT NULL DEFAULT 0,
                `txt_color` varchar(7) DEFAULT NULL,
                `txt_color_over` varchar(7) DEFAULT NULL,
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
				PRIMARY KEY (`id_st_featured_category_slider`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		return $return;
	}

	public function uninstall()
	{
		if (!parent::uninstall() ||
			!$this->uninstallDB())
			return false;
        $this->clearstfeaturedcategoryCache();
		return true;
	}

	private function uninstallDb()
	{
		$this->clearstfeaturedcategoryCache();
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_featured_category_slider`');
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
    	$id_st_featured_category_slider = (int)Tools::getValue('id_st_featured_category_slider');
		if (isset($_POST['save'.$this->name]) || isset($_POST['save'.$this->name.'AndStay']))
        {
            if($id_st_featured_category_slider)
            {
                $category = new StFeaturedCategoriesSliderClass($id_st_featured_category_slider);
                $id_category_old = $category->id_category;
            }
			else
				$category = new StFeaturedCategoriesSliderClass();
            
            $error = array();
            if (!Tools::getValue('id_category'))
                 $error[] = $this->displayError($this->l('Top category is required.'));
            
            $category->id_shop = (int)Shop::getContextShopID();
            
            if (!$category->id_shop)
                $error[] = $this->displayError($this->l('Action denied, please select a store.'));
            
            if (!count($error))
            {                
        		$category->copyFromPost();
        		$category->id_parent = 0;
                $category->level_depth = 0; 

                if ($category->validateFields(false) && $category->validateFieldsLang(false))
                {
                    if($category->position==0)
                        $category->position = StFeaturedCategoriesSliderClass::getMaximumPosition(0);
                    if($category->save())
                    {
                        $category->clearPosition();
                        $this->clearstfeaturedcategoryCache();
                        if(isset($_POST['save'.$this->name.'AndStay']) || Tools::getValue('fr') == 'view')
                        {
                            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_featured_category_slider='.$category->id.'&conf='.($id_st_featured_category_slider?4:3).'&'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                        }    
                        else
                            $this->_html .= $this->displayConfirmation($this->l('Featured category').' '.($id_st_featured_category_slider ? $this->l('updated') : $this->l('added')));
                    }
                    else
                        $this->_html .= $this->displayError($this->l('An error occurred during Featured category').' '.($id_st_featured_category_slider ? $this->l('updating') : $this->l('creation')));
                }
            }
			else
				$this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }
        if (isset($_POST['savesetting'.$this->name]))
		{
		    $this->initSettingForm();
            
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
                            Configuration::updateValue($this->_prefix_st.strtoupper($field['name']), $value);
                        }
                        else
                            Configuration::updateValue($this->_prefix_st.strtoupper($field['name']), $value);
                    }
             
            $this->updateCatePerRow();
            $this->saveHook();
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
                $this->clearstfeaturedcategoryCache();
                $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
            }
            $this->fields_form_setting[0]['form']['input']['fc_slider_pro_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer());
            if ($bg_img = Configuration::get($this->_prefix_st.'BG_IMG'))
            {
                $this->fetchMediaServer($bg_img);
                $this->fields_form_setting[0]['form']['input']['bg_img_field']['image'] = '<img width=200 src="'.($bg_img).'" /><p><a class="btn btn-default st_delete_image" href="javascript:;"><i class="icon-trash"></i> Delete</a></p>';
            }
            $helper = $this->initFormSetting();
			return $this->_html.$helper->generateForm($this->fields_form_setting);
		}
        if (Tools::isSubmit('way') && Tools::isSubmit('id_st_featured_category_slider') && Tools::isSubmit('position'))
		{
		    $category = new StFeaturedCategoriesSliderClass((int)$id_st_featured_category_slider);
            if($category->id && $category->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position')))
            {
                $this->clearstfeaturedcategoryCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'));                
            }
            else
                $this->_html .= $this->displayError($this->l('Failed to update the position.'));
		}
        if (Tools::getValue('action') == 'updatePositions')
        {
            $this->processUpdatePositions();
        }
        if (Tools::isSubmit('add'.$this->name))
		{
            $helper = $this->_displayForm(); 
            $this->_html .= $helper->generateForm($this->fields_form);
			return $this->_html;
		}
        elseif (Tools::isSubmit('update'.$this->name))
        {
    		$category = new StFeaturedCategoriesSliderClass((int)$id_st_featured_category_slider);
            if(!Validate::isLoadedObject($category) || $category->id_shop!=(int)Shop::getContextShopID())
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));

            $helper = $this->_displayForm(); 
            $this->_html .= $helper->generateForm($this->fields_form);
            
			return $this->_html; 
        }
        else if (Tools::isSubmit('delete'.$this->name))
		{
    		$category = new StFeaturedCategoriesSliderClass((int)$id_st_featured_category_slider);
            if(Validate::isLoadedObject($category))
            {                    
                $category->delete();
                $category->clearPosition();
                $this->clearstfeaturedcategoryCache();
            }
            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
        elseif (Tools::isSubmit('status'.$this->name))
		{
            $category = new StFeaturedCategoriesSliderClass($id_st_featured_category_slider);
            if (Validate::isLoadedObject($category))
            {
                $category->troggleStatus();
                $this->clearstfeaturedcategoryCache();
            }
            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
        elseif (Tools::isSubmit('setting'.$this->name))
		{
		    $this->initSettingForm();
            
            $this->fields_form_setting[0]['form']['input']['fc_slider_pro_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer());
            if ($bg_img = Configuration::get($this->_prefix_st.'BG_IMG'))
            {
                $this->fetchMediaServer($bg_img);
                $this->fields_form_setting[0]['form']['input']['bg_img_field']['image'] = '<img width=200 src="'.($bg_img).'" /><p><a class="btn btn-default st_delete_image" href="javascript:;"><i class="icon-trash"></i> Delete</a></p>';
            }
            
            $helper = $this->initFormSetting();
            
			return $this->_html.$helper->generateForm($this->fields_form_setting);
		}
        else
        {
            $this->_html .= '<script type="text/javascript">var currentIndex="'.AdminController::$currentIndex.'&configure='.$this->name.'";</script>';
            $helper = $this->initList();
            $list = StFeaturedCategoriesSliderClass::getSub(0);
			return $this->_html.$helper->generateList($list, $this->fields_list);
        }
            
	}
    
    public function updateCatePerRow() {
        $arr = $this->findCateProPer();
        foreach ($arr as $v)
            if($gv = Tools::getValue($v['id']))
                Configuration::updateValue($this->_prefix_stsn.strtoupper($v['id']), (int)$gv);
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
    
    public function initSettingForm()
    {
		$this->fields_form_setting[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Settings'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
                    'type' => 'radio',
                    'label' => $this->l('How to display categories:'),
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
                            'id' => 'grid_images',
                            'value' => 2,
                            'label' => $this->l('Grid view with category images')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                'fc_slider_pro_per_0' => array(
                    'type' => 'html',
                    'id' => 'fc_slider_pro_per_0',
                    'label'=> $this->l('The number of columns'),
                    'name' => '',
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
                    'type' => 'switch',
                    'label' => $this->l('Always display this block:'),
                    'name' => 'aw_display',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'aw_display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'aw_display_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
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
                    'validation' => 'isBool',
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
                    'validation' => 'isUnsignedInt',
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
                    'label' => $this->l('Text color:'),
                    'name' => 'text_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Category name hover color:'),
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
                    'validation' => 'isUnsignedInt',
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
            'submit' => array(
				'title' => $this->l('Save all'),
			),
		);
        $this->fields_form_setting[1]['form'] = array(
			'legend' => array(
				'title' => $this->l('Hook manager'),
                'icon' => 'icon-cogs'
			),
            'description' => $this->l('Check the hook that you would like this module to display on.').'<br/><a href="'._MODULE_DIR_.'stthemeeditor/img/hook_into_hint.jpg" target="_blank" >'.$this->l('Click here to see hook position').'</a>.',
			'input' => array(
			),
			'submit' => array(
				'title' => $this->l('   Save all  ')
			),
		);
        
        foreach($this->_hooks AS $key => $values)
        {
            if (!is_array($values) || !count($values))
                continue;
            $this->fields_form_setting[1]['form']['input'][] = array(
					'type' => 'checkbox',
					'label' => $this->l($key),
					'name' => $key,
					'lang' => true,
					'values' => array(
						'query' => $values,
						'id' => 'id',
						'name' => 'name'
					)
				);
        }
    }
    
    protected function initFormSetting()
	{
	    $helper = new HelperForm();
		$helper->show_toolbar = false;
        $helper->module = $this;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savesetting'.$this->name;
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper;
	}

	private function _displayForm()
    {
        $id_lang = $this->context->language->id;
        $category_arr = array();
		$this->getCategoryOption($category_arr, Category::getRootCategory()->id, (int)$id_lang, (int)Shop::getContextShopID(),true);
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Top category'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'select',
					'label' => $this->l('Category:'),
					'name' => 'id_category',
                    'required' => true,
					'options' => array(
						'query' => $category_arr,
						'id' => 'id',
						'name' => 'name'
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
                /*array(
					'type' => 'text',
					'label' => $this->l('Position:'),
					'name' => 'position',
                    'default_value' => 0,
                    'class' => 'fixed-width-sm'                 
				),*/
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
        
        $this->fields_form[0]['form']['input'][] = array(
			'type' => 'html',
            'id' => 'a_cancel',
			'label' => '',
			'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
		);
        
        $id_st_featured_category_slider = (int)Tools::getValue('id_st_featured_category_slider');
        if($id_st_featured_category_slider)
            $category = new StFeaturedCategoriesSliderClass((int)$id_st_featured_category_slider);
        else
            $category = new StFeaturedCategoriesSliderClass();
        if(Validate::isLoadedObject($category))
        {
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_featured_category_slider');
        }
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'save'.$this->name;
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($category),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper;
    }

    private function getCategoryOption(&$category_arr, $id_category = 1, $id_lang = false, $id_shop = false, $recursive = true)
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
		$category_arr[] = array('id'=>(int)$category->id,'name'=>(isset($spacer) ? $spacer : '').$category->name.' ('.$shop->name.')');

		if (isset($children) && is_array($children) && count($children))
			foreach ($children as $child)
			{
				$this->getCategoryOption($category_arr, (int)$child['id_category'], (int)$id_lang, (int)$child['id_shop'],$recursive);
			}
	}
    
    protected function initList()
	{
		$this->fields_list = array(
			'name' => array(
				'title' => $this->l('Category name'),
				'class' => 'fixed-width-xxl',
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'position' => array(
				'title' => $this->l('Position'),
				'class' => 'fixed-width-xxl',
				'position' => 'position',
                'search' => false,
                'orderby' => false
			),
            'active' => array(
    			'title' => $this->l('Status'), 
                'class' => 'fixed-width-xxl',
                'active' => 'status',
    			'align' => 'center',
                'type' => 'bool',
                'search' => false,
                'orderby' => false
            ),
		);

		if (Shop::isFeatureActive())
			$this->fields_list['id_shop'] = array(
                'title' => $this->l('ID Shop'), 
                'align' => 'center', 
                'class' => 'fixed-width-sm',
                'type' => 'int',
                'search' => false,
                'orderby' => false
            );

		$helper = new HelperList();
        $helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_st_featured_category_slider';
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
        $helper->position_identifier = 'id_st_featured_category_slider';
        
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    public function deleteFeaturedCategories($id_category)
    {
        if ($id_category)
        {
            $cats = Db::getInstance('
            SELECT `id_st_featured_category_slider` FROM '._DB_PREFIX_.'st_featured_category
            WHERE `id_category` = '.(int)$id_category.'
            ');
            foreach($cats AS $cat)
            {
                $obj = new StFeaturedCategoriesSliderClass($cat['id_st_featured_category_slider']);
                $obj->delete();
            }
        }
    }

	public function hookActionCategoryDelete($params)
	{
	    if(isset($params['category']))
	       $this->deleteFeaturedCategories($params['category']->id);
		$this->clearstfeaturedcategoryCache();
	}
    
    public function hookActionCategoryAdd($params)
	{
		$this->clearstfeaturedcategoryCache();
	}
    
	public function hookActionCategoryUpdate($params)
	{
		$this->clearstfeaturedcategoryCache();
	}
    
    public function hookDisplayHomeTop($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    public function hookDisplayHomeBottom($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }

    public function hookDisplayTopColumn($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }    
    public function hookDisplayBottomColumn($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }    

    public function hookDisplayFullWidthTop($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__), 2);
    }
    public function hookDisplayFullWidthTop2($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__), 2);
    }
    public function hookDisplayFullWidthBottom($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__), 2);
    }
    
    public function hookDisplayHomeTertiaryLeft($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    public function hookDisplayHomeTertiaryRight($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }

    private function _prepareHook($location= null)
    {
        if (!empty(self::$cache_fc_slider))
            $fc_slider = self::$cache_fc_slider;
        else
        {
            $fc_slider = StFeaturedCategoriesSliderClass::getAll();
            self::$cache_fc_slider = $fc_slider;
        }
        
        // if(!$fc_slider)
        //     return false;
            
        $easing = Configuration::get($this->_prefix_st.'EASING');
        $slideshow = Configuration::get($this->_prefix_st.'SLIDESHOW');
        $s_speed = Configuration::get($this->_prefix_st.'S_SPEED');
        $a_speed = Configuration::get($this->_prefix_st.'A_SPEED');
        $pause_on_hover = Configuration::get($this->_prefix_st.'PAUSE_ON_HOVER');
        $loop = Configuration::get($this->_prefix_st.'LOOP');
        $move = Configuration::get($this->_prefix_st.'MOVE');
        $hide_mob = Configuration::get($this->_prefix_st.'HIDE_MOB');
        $aw_display = Configuration::get($this->_prefix_st.'AW_DISPLAY');
        $grid = Configuration::get($this->_prefix_st.'GRID');
        
		$this->smarty->assign(array(
            'fc_slider'   => $fc_slider,
            'categorySize'          => Image::getSize(ImageType::getFormatedName('category')),
            'homeSize'              => Image::getSize(ImageType::getFormatedName('home')),
            'mediumSize'            => Image::getSize(ImageType::getFormatedName('medium')),
            'slider_easing'         => self::$easing[$easing]['name'],
            'slider_slideshow'      => $slideshow,
            'slider_s_speed'        => $s_speed,
            'slider_a_speed'        => $a_speed,
            'slider_pause_on_hover' => $pause_on_hover,
            'slider_loop'           => $loop,
            'slider_move'           => $move,
            'hide_mob'              => (int)$hide_mob,
            'aw_display'            => (int)$aw_display,
            'display_as_grid'       => $grid,
        ));
        return true;
    }
    
    public function hookDisplayHeader($params)
    {
        if (!$this->isCached('header.tpl', $this->getCacheId()))
        {
            $custom_css = '';
            $title_block_no_bg = '.fc_slider_block_container .title_block, .fc_slider_block_container .nav_top_right .flex-direction-nav,.fc_slider_block_container .title_block a, .fc_slider_block_container .title_block span{background:none;}';
            
            $group_css = '';
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
                $custom_css .= '.fc_slider_block_container{background-attachment:fixed;'.$group_css.'}'.$title_block_no_bg;

            if ($top_padding = (int)Configuration::get($this->_prefix_st.'TOP_PADDING'))
                $custom_css .= '.fc_slider_block_container{padding-top:'.$top_padding.'px;}';
            if ($bottom_padding = (int)Configuration::get($this->_prefix_st.'BOTTOM_PADDING'))
                $custom_css .= '.fc_slider_block_container{padding-bottom:'.$bottom_padding.'px;}';

            $top_margin = Configuration::get($this->_prefix_st.'TOP_MARGIN');
            if($top_margin || $top_margin!==null)
                $custom_css .= '.fc_slider_block_container{margin-top:'.$top_margin.'px;}';
            $bottom_margin = Configuration::get($this->_prefix_st.'BOTTOM_MARGIN');
            if($bottom_margin || $bottom_margin!==null)
                $custom_css .= '.fc_slider_block_container{margin-bottom:'.$bottom_margin.'px;}';

            if (Configuration::get($this->_prefix_st.'TITLE_ALIGNMENT'))
                $custom_css .= '.fc_slider_block_container .title_block{text-align:center;}'.$title_block_no_bg;
            if (Configuration::get($this->_prefix_st.'TITLE_NO_BG'))
                $custom_css .= $title_block_no_bg;
            if ($title_font_size = (int)Configuration::get($this->_prefix_st.'TITLE_FONT_SIZE'))
            {
                 $custom_css .= '.fc_slider_block_container .title_block{font-size:'.$title_font_size.'px;}';
                 $custom_css .= '.fc_slider_block_container .nav_top_right .flex-direction-nav{top:-'.(round((round($title_font_size*1.3)-24)/2)+24+22).'px;}';
            }

            if ($title_color = Configuration::get($this->_prefix_st.'TITLE_COLOR'))
                $custom_css .= '.fc_slider_block_container.block .title_block a, .fc_slider_block_container.block .title_block span{color:'.$title_color.';}';

            if ($text_color = Configuration::get($this->_prefix_st.'TEXT_COLOR'))
                $custom_css .= '.fc_slider_block_container .s_title_block a{color:'.$text_color.';}';

            if ($link_hover_color = Configuration::get($this->_prefix_st.'LINK_HOVER_COLOR'))
                $custom_css .= '.fc_slider_block_container .s_title_block a:hover{color:'.$link_hover_color.';}';

            if ($grid_hover_bg = Configuration::get($this->_prefix_st.'GRID_HOVER_BG'))
                $custom_css .= '.fc_slider_block_container .products_slider .ajax_block_product:hover .pro_second_box{background-color:'.$grid_hover_bg.';}';

            if ($direction_color = Configuration::get($this->_prefix_st.'DIRECTION_COLOR'))
                $custom_css .= '.fc_slider_block_container .nav_top_right .flex-direction-nav a, .fc_slider_block_container .nav_left_right .flex-direction-nav a{color:'.$direction_color.';}';
            if ($direction_bg = Configuration::get($this->_prefix_st.'DIRECTION_BG'))
                $custom_css .= '.fc_slider_block_container .nav_top_right .flex-direction-nav a, .fc_slider_block_container .nav_left_right .flex-direction-nav a{background-color:'.$direction_bg.';}';
            if ($direction_hover_bg = Configuration::get($this->_prefix_st.'DIRECTION_HOVER_BG'))
                $custom_css .= '.fc_slider_block_container .nav_top_right .flex-direction-nav a:hover, .fc_slider_block_container .nav_left_right .flex-direction-nav a:hover{background-color:'.$direction_hover_bg.';}';
            if ($direction_disabled_bg = Configuration::get($this->_prefix_st.'DIRECTION_DISABLED_BG'))
                $custom_css .= '.fc_slider_block_container .nav_top_right .flex-direction-nav a.flex-disabled, .fc_slider_block_container .nav_left_right .flex-direction-nav a.flex-disabled{background-color:'.$direction_disabled_bg.';}';

            if($custom_css)
                $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
        }
        return $this->display(__FILE__, 'header.tpl', $this->getCacheId());
    }
    
	public function hookDisplayHome($params, $hook_hash = '', $flag = 0)
	{
	    if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
        
	    if (!$this->isCached('stfeaturedcategoriesslider.tpl', $this->getCacheId($hook_hash)))
        {
    	    if(!$this->_prepareHook())
                return false;
        
            $this->smarty->assign(array(
                'hook_hash'      => $hook_hash,
                'homeverybottom' => ($flag==2 ? true : false),
                'pro_per_lg'     => (int)Configuration::get($this->_prefix_stsn.'FC_SLIDER_PER_LG_0'),
                'pro_per_md'     => (int)Configuration::get($this->_prefix_stsn.'FC_SLIDER_PER_MD_0'),
                'pro_per_sm'     => (int)Configuration::get($this->_prefix_stsn.'FC_SLIDER_PER_SM_0'),
                'pro_per_xs'     => (int)Configuration::get($this->_prefix_stsn.'FC_SLIDER_PER_XS_0'),
                'pro_per_xxs'    => (int)Configuration::get($this->_prefix_stsn.'FC_SLIDER_PER_XXS_0'),
    
                'has_background_img'     => ((int)Configuration::get($this->_prefix_st.'BG_PATTERN') || Configuration::get($this->_prefix_st.'BG_IMG')) ? 1 : 0,
                'speed'                  => (float)Configuration::get($this->_prefix_st.'SPEED'),
                'direction_nav'          => Configuration::get($this->_prefix_st.'DIRECTION_NAV'),
            ));
        }
		return $this->display(__FILE__, 'stfeaturedcategoriesslider.tpl', $this->getCacheId($hook_hash));
	}
    
    private function clearstfeaturedcategoryCache()
    {
        foreach($this->_hooks AS $key => $values)
        {
            foreach($values AS $value)
            {
                if (!isset($value['id']) || !$value['id'])
                    continue;
                $this->_clearCache('stfeaturedcategoriesslider.tpl',$this->getHookHash('hook'.ucfirst($value['id'])));
            }
        }
        $this->_clearCache('*');
    }
    
	public function hookDisplayHomeSecondaryLeft($params)
	{
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__)); 
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
            'easing'             => Configuration::get($this->_prefix_st.'EASING'),
            'slideshow'          => Configuration::get($this->_prefix_st.'SLIDESHOW'),
            's_speed'            => Configuration::get($this->_prefix_st.'S_SPEED'),
            'a_speed'            => Configuration::get($this->_prefix_st.'A_SPEED'),
            'pause_on_hover'     => Configuration::get($this->_prefix_st.'PAUSE_ON_HOVER'),
            'loop'               => Configuration::get($this->_prefix_st.'LOOP'),
            'move'               => Configuration::get($this->_prefix_st.'MOVE'),
            'soby'               => Configuration::get($this->_prefix_st.'SOBY'),
            'hide_mob'           => Configuration::get($this->_prefix_st.'HIDE_MOB'),
            'aw_display'         => Configuration::get($this->_prefix_st.'AW_DISPLAY'),
            'grid'               => Configuration::get($this->_prefix_st.'GRID'),  
            
            'top_padding'        => Configuration::get($this->_prefix_st.'TOP_PADDING'),
            'bottom_padding'     => Configuration::get($this->_prefix_st.'BOTTOM_PADDING'),
            'top_margin'         => Configuration::get($this->_prefix_st.'TOP_MARGIN'),
            'bottom_margin'      => Configuration::get($this->_prefix_st.'BOTTOM_MARGIN'),
            'bg_pattern'         => Configuration::get($this->_prefix_st.'BG_PATTERN'),
            'bg_img'             => Configuration::get($this->_prefix_st.'BG_IMG'),
            'bg_color'           => Configuration::get($this->_prefix_st.'BG_COLOR'),
            'speed'              => Configuration::get($this->_prefix_st.'SPEED'),

            'title_color'           => Configuration::get($this->_prefix_st.'TITLE_COLOR'),
            'text_color'            => Configuration::get($this->_prefix_st.'TEXT_COLOR'),
            'link_hover_color'      => Configuration::get($this->_prefix_st.'LINK_HOVER_COLOR'),
            'grid_hover_bg'         => Configuration::get($this->_prefix_st.'GRID_HOVER_BG'),
            'direction_color'       => Configuration::get($this->_prefix_st.'DIRECTION_COLOR'),
            'direction_bg'          => Configuration::get($this->_prefix_st.'DIRECTION_BG'),
            'direction_hover_bg'    => Configuration::get($this->_prefix_st.'DIRECTION_HOVER_BG'),
            'direction_disabled_bg' => Configuration::get($this->_prefix_st.'DIRECTION_DISABLED_BG'),
            
            'title_alignment'       => Configuration::get($this->_prefix_st.'TITLE_ALIGNMENT'),
            'title_no_bg'           => Configuration::get($this->_prefix_st.'TITLE_NO_BG'),
            'title_font_size'       => Configuration::get($this->_prefix_st.'TITLE_FONT_SIZE'),
            'direction_nav'         => Configuration::get($this->_prefix_st.'DIRECTION_NAV'),
        );
        
        foreach($this->_hooks AS $key => $values)
        {
            if (!$key)
                continue;
            foreach($values AS $value)
            {
                $fields_values[$key.'_'.$value['id']] = 0;
                if($id_hook = Hook::getIdByName($value['id']))
                    if(Hook::getModulesFromHook($id_hook, $this->id))
                        $fields_values[$key.'_'.$value['id']] = 1;
            }
        }
        return $fields_values;
    }
    
    public function processUpdatePositions()
	{
		if (Tools::getValue('action') == 'updatePositions' && Tools::getValue('ajax'))
		{
			$way = (int)(Tools::getValue('way'));
			$id = (int)(Tools::getValue('id'));
			$positions = Tools::getValue('st_featured_category_slider');
            $msg = '';
			if (is_array($positions))
				foreach ($positions as $position => $value)
				{
					$pos = explode('_', $value);

					if ((isset($pos[2])) && ((int)$pos[2] === $id))
					{
						if ($object = new StFeaturedCategoriesSliderClass((int)$pos[2]))
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
                $html .= '<option value="'.$i.'" '.(Configuration::get($this->_prefix_stsn.strtoupper($k['id'])) == $i ? ' selected="selected"':'').'>'.$i.'</option>';
            }
                                
            $html .= '</select></div>';
        }
        return $html.'</div>';
    }
    public function findCateProPer()
    {
        return array(
            array(
                'id' => 'fc_slider_per_lg_0',
                'label' => $this->l('Large devices'),
                'tooltip' => $this->l('Desktops (>1200px)'),
            ),
            array(
                'id' => 'fc_slider_per_md_0',
                'label' => $this->l('Medium devices'),
                'tooltip' => $this->l('Desktops (>992px)'),
            ),
            array(
                'id' => 'fc_slider_per_sm_0',
                'label' => $this->l('Small devices'),
                'tooltip' => $this->l('Tablets (>768px)'),
            ),
            array(
                'id' => 'fc_slider_per_xs_0',
                'label' => $this->l('Extra small devices'),
                'tooltip' => $this->l('Phones (>480px)'),
            ),
            array(
                'id' => 'fc_slider_per_xxs_0',
                'label' => $this->l('Extra extra small devices'),
                'tooltip' => $this->l('Phones (<480px)'),
            ),
        );
    }
    
    public function getHookHash($func='')
    {
        if (!$func)
            return '';
        return substr(md5($func), 0, 10);
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
