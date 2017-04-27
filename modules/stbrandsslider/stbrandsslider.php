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

include_once(dirname(__FILE__).'/StBrandsSliderClass.php');
class StBrandsSlider extends Module
{
    private $_html = '';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();
    private $_prefix_st = 'BRANDS_';
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
    public static $sort_by = array(
        1 => array('id' =>1 , 'name' => 'Product Name: A to Z'),
        2 => array('id' =>2 , 'name' => 'Product Name: Z to A'),
        3 => array('id' =>3 , 'name' => 'Random'),
    );
    public static $items = array(
		array('id' => 1, 'name' => '1'),
		array('id' => 2, 'name' => '2'),
		array('id' => 3, 'name' => '3'),
		array('id' => 4, 'name' => '4'),
		array('id' => 5, 'name' => '5'),
		array('id' => 6, 'name' => '6'),
		array('id' => 7, 'name' => '7'),
    );
    private $_hooks = array();
	public function __construct()
	{
		$this->name          = 'stbrandsslider';
		$this->tab           = 'front_office_features';
		$this->version       = '1.3.7';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
		$this->bootstrap 	 = true;
		parent::__construct();
        
        $this->initHookArray();
		
		$this->displayName = $this->l('Brands Slider');
		$this->description = $this->l('Brands slider on your home page.');
	}

	public function install()
	{
		if (!parent::install() 
            || !$this->installDB()
            || !$this->initData()
            || !$this->registerHook('displayHeader')
			|| !$this->registerHook('displayHomeBottom')
			|| !$this->registerHook('actionObjectManufacturerDeleteAfter')
			|| !$this->registerHook('actionObjectManufacturerUpdateAfter')
            || !Configuration::updateValue('BRANDS_SLIDER_NBR', 10)
            || !Configuration::updateValue('BRANDS_SLIDER_NAME', 0)
            || !Configuration::updateValue('BRANDS_SLIDER_SHORT_DESC', 0)
            || !Configuration::updateValue('BRANDS_SLIDER_ORDER', 1)

            || !Configuration::updateValue('STSN_BRANDS_PRO_PER_LG_0', 6)
            || !Configuration::updateValue('STSN_BRANDS_PRO_PER_MD_0', 5)
            || !Configuration::updateValue('STSN_BRANDS_PRO_PER_SM_0', 4)
            || !Configuration::updateValue('STSN_BRANDS_PRO_PER_XS_0', 3)
            || !Configuration::updateValue('STSN_BRANDS_PRO_PER_XXS_0', 2)
            || !Configuration::updateValue('BRANDS_SLIDER_SLIDESHOW', 0)
            || !Configuration::updateValue('BRANDS_SLIDER_S_SPEED', 7000)
            || !Configuration::updateValue('BRANDS_SLIDER_A_SPEED', 400)
            || !Configuration::updateValue('BRANDS_SLIDER_PAUSE_ON_HOVER', 1)
            || !Configuration::updateValue('BRANDS_SLIDER_EASING', 0)
            || !Configuration::updateValue('BRANDS_SLIDER_LOOP', 0)
            || !Configuration::updateValue('BRANDS_SLIDER_MOVE', 0)

            || !Configuration::updateValue('BRANDS_S_ITEMS_COL', 2)
            || !Configuration::updateValue('BRANDS_S_SLIDESHOW_COL', 0)
            || !Configuration::updateValue('BRANDS_S_S_SPEED_COL', 7000)
            || !Configuration::updateValue('BRANDS_S_A_SPEED_COL', 400)
            || !Configuration::updateValue('BRANDS_S_PAUSE_ON_HOVER_COL', 1)
            || !Configuration::updateValue('BRANDS_S_EASING_COL', 0)
            || !Configuration::updateValue('BRANDS_S_LOOP_COL', 0)
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
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_HOVER_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_DISABLED_BG', '')
            
            || !Configuration::updateValue($this->_prefix_st.'TITLE_ALIGNMENT', 0)
            || !Configuration::updateValue($this->_prefix_st.'TITLE_NO_BG', 0)
            || !Configuration::updateValue($this->_prefix_st.'TITLE_FONT_SIZE', 0)
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_NAV', 0)
            
            || !Configuration::updateValue($this->_prefix_st.'ALL', 0)
            )
			return false;
        $this->clearBrandsSliderCache();
		return true;
	}
    
    private function initHookArray()
    {
        $this->_hooks = array(
            'Hooks' => array(
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
        			'id' => 'displayHomeTop',
        			'val' => '1',
        			'name' => $this->l('displayHomeTop')
        		),
                array(
        			'id' => 'displayHome',
        			'val' => '1',
        			'name' => $this->l('displayHome')
        		),
        		array(
        			'id' => 'displayHomeSecondaryRight',
        			'val' => '1',
        			'name' => $this->l('displayHomeSecondaryRight')
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
        			'id' => 'displayHomeBottom',
        			'val' => '1',
        			'name' => $this->l('displayHomeBottom')
        		),
                array(
        			'id' => 'displayBottomColumn',
        			'val' => '1',
        			'name' => $this->l('displayBottomColumn')
        		),
        		array(
        			'id' => 'displayHomeVeryBottom',
        			'val' => '1',
        			'name' => $this->l('displayFullWidthBottom(homeverybottom)')
        		),
                array(
        			'id' => 'displayProductSecondaryColumn',
        			'val' => '1',
        			'name' => $this->l('displayProductSecondaryColumn')
        		),
				array(
        			'id' => 'displayFooterProduct',
        			'val' => '1',
        			'name' => $this->l('displayFooterProduct')
        		)
            ),
            'Column' => array(
                array(
        			'id' => 'displayLeftColumn',
        			'val' => '1',
        			'name' => $this->l('displayLeftColumn')
        		),
        		array(
        			'id' => 'displayRightColumn',
        			'val' => '1',
        			'name' => $this->l('displayRightColumn')
        		)
            ),
            'Footer' => array(
        		array(
        			'id' => 'displayFooterTop',
        			'val' => '1',
        			'name' => $this->l('displayFooterTop')
        		),
                array(
        			'id' => 'displayFooter',
        			'val' => '1',
        			'name' => $this->l('displayFooter')
        		),
                array(
        			'id' => 'displayFooterSecondary',
        			'val' => '1',
        			'name' => $this->l('displayFooterSecondary')
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

	public function uninstall()
	{
        $this->clearBrandsSliderCache();
		if (!parent::uninstall() 
            || !$this->uninstallDB()
        )
			return false;
		return true;
	}
    private function installDB()
	{
		return Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_brands_slider` (
                 `id_manufacturer` int(10) NOT NULL,  
                 `id_shop` int(11) NOT NULL,                   
                PRIMARY KEY (`id_manufacturer`,`id_shop`),    
                KEY `id_shop` (`id_shop`)       
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
	}
	private function uninstallDB()
	{
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_brands_slider`');
	}  
    private function initData()
    {
        $res = true;
        $manufacturers = Manufacturer::getManufacturers(false, (int)$this->context->language->id);
        if (is_array($manufacturers) && count($manufacturers))
        {
            foreach($manufacturers as $k => $v)
                if($k<10)
                    $res &= Db::getInstance()->insert('st_brands_slider', array(
        					'id_manufacturer' => (int)$v['id_manufacturer'],
        					'id_shop' => (int)$this->context->shop->id,
        				));
                else
                    break;
        }
        return $res;
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

	    $this->initFieldsForm();
        if (Tools::getValue('act') == 'gmfb' && Tools::getValue('ajax')==1)
        {
            if(!$q = Tools::getValue('q'))
                die;
            $excludeIds = Tools::getValue('excludeIds');
            $result = Db::getInstance()->executeS('
			SELECT m.`id_manufacturer`,m.`name`
			FROM `'._DB_PREFIX_.'manufacturer` m
            LEFT JOIN `'._DB_PREFIX_.'manufacturer_shop` ms
            ON m.`id_manufacturer` = ms.`id_manufacturer`
			WHERE `name` LIKE \'%'.pSQL($q).'%\'
            AND id_shop = '.(int)Shop::getContextShopID().'
            AND `active` = 1
            '.($excludeIds ? 'AND m.`id_manufacturer` NOT IN('.$excludeIds.')' : '').'
    		');
            foreach ($result AS $value)
		      echo trim($value['name']).'|'.(int)($value['id_manufacturer'])."\n";
            die;
        }
		if (isset($_POST['savestbrandsslider']))
		{
		    StBrandsSliderClass::deleteByShop((int)$this->context->shop->id);
    		$manufacturers = Manufacturer::getManufacturers(false, (int)$this->context->language->id);
            $res = true;
            if($id_manufacturer = Tools::getValue('id_manufacturer'))
                foreach($id_manufacturer AS $value)
                {
                  $res &= Db::getInstance()->insert('st_brands_slider', array(
        					'id_manufacturer' => (int)$value,
        					'id_shop' => (int)$this->context->shop->id,
        				));  
                } 
            if($res)
            {
                foreach($this->fields_form as $form)
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
                                Configuration::updateValue('BRANDS_'.strtoupper($field['name']), $value);
                            }
                            else
                                Configuration::updateValue('BRANDS_'.strtoupper($field['name']), $value);
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
                    $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
            }
            else
                $this->_html .= $this->displayError($this->l('Cannot update settings'));
                
            $this->saveHook();
                
            $this->clearBrandsSliderCache();            
        }
        $this->fields_form[1]['form']['input']['brands_pro_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer());
        if ($bg_img = Configuration::get($this->_prefix_st.'BG_IMG'))
        {
            $this->fetchMediaServer($bg_img);
            $this->fields_form[1]['form']['input']['bg_img_field']['image'] = '<img width=200 src="'.($bg_img).'" /><p><a class="btn btn-default st_delete_image" href="javascript:;"><i class="icon-trash"></i> Delete</a></p>';
        }
		$helper = $this->initForm();                    
		return $this->_html.$helper->generateForm($this->fields_form);
	}

    public function updateCatePerRow() {
        $arr = $this->findCateProPer();
        foreach ($arr as $v)
            if($gv = Tools::getValue($v['id']))
                Configuration::updateValue('STSN_'.strtoupper($v['id']), (int)$gv);
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
    
    public function initFieldsForm()
    {
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->displayName,
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Define the number of brands to be displayed:'),
					'name' => 'slider_nbr',
                    'required' => true,
                    'desc' => $this->l('Define the number of brands that you would like to display on homepage (default: 8).'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Display brand name:'),
					'name' => 'slider_name',
					'is_bool' => true,
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'slide_name_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'slide_name_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'radio',
					'label' => $this->l('Display brand short description:'),
					'name' => 'slider_short_desc',
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'slider_short_desc_0',
							'value' => 0,
							'label' => $this->l('No')),
						array(
							'id' => 'slider_short_desc_1',
							'value' => 1,
							'label' => $this->l('Normal(100 characters)')),
                        array(
							'id' => 'slider_short_desc_2',
							'value' => 2,
							'label' => $this->l('Full description')),
					),
                    'validation' => 'isUnsignedInt',
				),
                array(
                    'type' => 'select',
    				'label' => $this->l('Sort order:'),
    				'name' => 'slider_order',
    				'options' => array(
    					'query' => self::$sort_by,
        				'id' => 'id',
        				'name' => 'name',
    				),
                    'validation' => 'isUnsignedInt',
                    //'desc' => $this->l('If you choose "RANDOM" smarty cache will be disabled'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show all Brands:'),
                    'name' => 'all',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'all_1',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'all_0',
                            'value' => 0,
                            'label' => $this->l('No'))
                    ),
                    'validation' => 'isUnsignedInt',
                ),
				'manufacturers' => array(
					'type' => 'text',
					'label' => $this->l('Specific Brands:'),
					'name' => 'manufacturers',
                    'autocomplete' => false,
                    'class' => 'fixed-width-xxl',
                    'desc' => '',
				),
			),
			'submit' => array(
				'title' => $this->l('   Save all  ')
			),
		);
        $this->fields_form[1]['form'] = array(
			'legend' => array(
				'title' => $this->l('Slider on homepage'),
			),
			'input' => array(
                'brands_pro_per_0' => array(
                    'type' => 'html',
                    'id' => 'brands_pro_per_0',
                    'label'=> $this->l('The number of columns'),
                    'name' => '',
                ),
                array(
					'type' => 'switch',
					'label' => $this->l('Autoplay:'),
					'name' => 'slider_slideshow',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'slide_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'slide_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'text',
					'label' => $this->l('Time:'),
					'name' => 'slider_s_speed',
                    'default_value' => 7000,
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Transition period:'),
					'name' => 'slider_a_speed',
                    'default_value' => 400,
                    'desc' => $this->l('The period, in milliseconds, of the transition effect.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Pause On Hover:'),
					'name' => 'slider_pause_on_hover',
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
        			'name' => 'slider_easing',
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
					'name' => 'slider_loop',
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
					'name' => 'slider_move',
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
			),
			'submit' => array(
				'title' => $this->l('   Save all  ')
			),
		);
        $this->fields_form[2]['form'] = array(
			'legend' => array(
				'title' => $this->l('Slide on left column/right column'),
			),
			'input' => array(
                array(
					'type' => 'select',
        			'label' => $this->l('The number of columns:'),
        			'name' => 's_items_col',
                    'options' => array(
        				'query' => self::$items,
        				'id' => 'id',
        				'name' => 'name',
        			),
                    'desc' => array(
                        $this->l('Set number of columns for default screen resolution(980px).'),
                    ),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Autoplay:'),
					'name' => 's_slideshow_col',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'slide_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'slide_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'text',
					'label' => $this->l('Time:'),
					'name' => 's_s_speed_col',
                    'default_value' => 7000,
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Transition period:'),
					'name' => 's_a_speed_col',
                    'default_value' => 400,
                    'desc' => $this->l('The period, in milliseconds, of the transition effect.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Pause On Hover:'),
					'name' => 's_pause_on_hover_col',
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
        			'name' => 's_easing_col',
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
					'name' => 's_loop_col',
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
			),
			'submit' => array(
				'title' => $this->l('   Save all  ')
			),
		);
        
        $this->fields_form[3]['form'] = array(
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
            $this->fields_form[3]['form']['input'][] = array(
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
    protected function initForm()
	{
	    $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
        $helper->module = $this;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestbrandsslider';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper;
	}

    public function hookDisplayHeader($params)
    {
        if (!$this->isCached('header.tpl', $this->getCacheId()))
        {
            $custom_css = '';
            $title_block_no_bg = '.brands_slider_container .title_block, .brands_slider_container .nav_top_right .flex-direction-nav,.brands_slider_container .title_block a, .brands_slider_container .title_block span{background:none;}';
            
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
                $custom_css .= '.brands_slider_container{background-attachment:fixed;'.$group_css.'}'.$title_block_no_bg;

            if ($top_padding = (int)Configuration::get($this->_prefix_st.'TOP_PADDING'))
                $custom_css .= '.brands_slider_container{padding-top:'.$top_padding.'px;}';
            if ($bottom_padding = (int)Configuration::get($this->_prefix_st.'BOTTOM_PADDING'))
                $custom_css .= '.brands_slider_container{padding-bottom:'.$bottom_padding.'px;}';

            $top_margin = Configuration::get($this->_prefix_st.'TOP_MARGIN');
            if($top_margin || $top_margin!==null)
                $custom_css .= '.brands_slider_container{margin-top:'.$top_margin.'px;}';
            $bottom_margin = Configuration::get($this->_prefix_st.'BOTTOM_MARGIN');
            if($bottom_margin || $bottom_margin!==null)
                $custom_css .= '.brands_slider_container{margin-bottom:'.$bottom_margin.'px;}';

            if (Configuration::get($this->_prefix_st.'TITLE_ALIGNMENT'))
                $custom_css .= '.brands_slider_container .title_block{text-align:center;}'.$title_block_no_bg;
            if (Configuration::get($this->_prefix_st.'TITLE_NO_BG'))
                $custom_css .= $title_block_no_bg;
            if ($title_font_size = (int)Configuration::get($this->_prefix_st.'TITLE_FONT_SIZE'))
            {
                 $custom_css .= '.brands_slider_container .title_block{font-size:'.$title_font_size.'px;}';
                 $custom_css .= '.brands_slider_container .nav_top_right .flex-direction-nav{top:-'.(round((round($title_font_size*1.3)-24)/2)+24+22).'px;}';
            }
            
            if ($title_color = Configuration::get($this->_prefix_st.'TITLE_COLOR'))
                $custom_css .= '.brands_slider_container.block .title_block a, .brands_slider_container.block .title_block span{color:'.$title_color.';}';

            if ($direction_color = Configuration::get($this->_prefix_st.'DIRECTION_COLOR'))
                $custom_css .= '.brands_slider_container .flex-direction-nav a{color:'.$direction_color.';}';
            if ($direction_bg = Configuration::get($this->_prefix_st.'DIRECTION_BG'))
                $custom_css .= '.brands_slider_container .flex-direction-nav a{background-color:'.$direction_bg.';}';
            if ($direction_hover_bg = Configuration::get($this->_prefix_st.'DIRECTION_HOVER_BG'))
                $custom_css .= '.brands_slider_container .flex-direction-nav a:hover{background-color:'.$direction_hover_bg.';}';
            if ($direction_disabled_bg = Configuration::get($this->_prefix_st.'DIRECTION_DISABLED_BG'))
                $custom_css .= '.brands_slider_container .flex-direction-nav a.flex-disabled{background-color:'.$direction_disabled_bg.';}';

            if($custom_css)
                $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
        }
        return $this->display(__FILE__, 'header.tpl', $this->getCacheId());
    }
	public function hookDisplayHome($params,  $hook_hash = '', $flag=0)
	{
	    if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
		if (Configuration::get('BRANDS_SLIDER_ORDER')==3 || !$this->isCached('stbrandsslider.tpl', $this->stGetCacheId($hook_hash)))
            $this->_prepareHook(0);
        $this->smarty->assign(array(
            'homeverybottom'         => ($flag==2 ? true : false),
            'hook_hash'              => $hook_hash,

            'has_background_img'     => ((int)Configuration::get($this->_prefix_st.'BG_PATTERN') || Configuration::get($this->_prefix_st.'BG_IMG')) ? 1 : 0,
            'speed'          => (float)Configuration::get($this->_prefix_st.'SPEED'),
            'direction_nav'          => Configuration::get($this->_prefix_st.'DIRECTION_NAV'),
        ));
        if(Configuration::get('BRANDS_SLIDER_ORDER')==3)
            return $this->display(__FILE__, 'stbrandsslider.tpl');
        else
		    return $this->display(__FILE__, 'stbrandsslider.tpl', $this->stGetCacheId($hook_hash));
	}
    public function hookDisplayHomeBottom($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    public function hookDisplayHomeTop($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }

    public function hookDisplayHomeVeryBottom($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__) ,2);
    }
    
    public function hookDisplayFullWidthTop($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__) ,2);
    }
    public function hookDisplayFullWidthTop2($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__) ,2);
    }
    public function hookDisplayBottomColumn($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }

    public function hookDisplayHomeTertiaryLeft($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    public function hookDisplayHomeTertiaryRight($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    

    public function hookDisplayHomeSecondaryRight($params)
    {
        return $this->hookDisplayLeftColumn($params, $this->getHookHash(__FUNCTION__));
    }
    public function hookDisplayRightColumn($params)
    {
        return $this->hookDisplayLeftColumn($params, $this->getHookHash(__FUNCTION__));
    }
    public function hookDisplayLeftColumn($params, $hook_hash = '')
    {
        if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
		if (Configuration::get('BRANDS_SLIDER_ORDER')==3 || !$this->isCached('stbrandsslider-column.tpl', $this->stGetCacheId($hook_hash)))
            $this->_prepareHook(1);
        $this->smarty->assign(array(
            'hook_hash' => $hook_hash
        ));
        if(Configuration::get('BRANDS_SLIDER_ORDER')==3)
            return $this->display(__FILE__, 'stbrandsslider-column.tpl');
        else
		    return $this->display(__FILE__, 'stbrandsslider-column.tpl', $this->stGetCacheId($hook_hash));
    }
	public function hookDisplayProductSecondaryColumn($params)
	{
        return $this->hookDisplayLeftColumn($params, $this->getHookHash(__FUNCTION__));
	}
    
    public function hookDisplayCategoryFooter($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    
    public function hookDisplayFooterProduct($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    
    public function hookDisplayFooterTop($params)
    {
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));
    }
    public function hookDisplayFooter($params, $hook_hash = '')
    {
        if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
	    if (!$this->isCached('stbrandsslider-footer.tpl', $this->stGetCacheId($hook_hash)))
	    {
            if (Configuration::get('BRANDS_ALL'))
                $brands = Manufacturer::getManufacturers();
            else
                $brands = $this->getManufacturers(Configuration::get('BRANDS_SLIDER_NBR'), $this->context->shop->id,$this->context->language->id);
            
            $this->smarty->assign(array(
                'brands'            => $brands,
    			'manufacturerSize'  => Image::getSize(ImageType::getFormatedName('thumb')),
                'hook_hash'         => $hook_hash
    		));
	    }
		return $this->display(__FILE__, 'stbrandsslider-footer.tpl', $this->stGetCacheId($hook_hash));
    }
    public function hookDisplayFooterSecondary($params)
    {
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));
    }
    
    private function _prepareHook($col=0)
    {
        if (Configuration::get('BRANDS_ALL'))
            $brands = $this->getManufacturersAll();
        else
            $brands = $this->getManufacturers(Configuration::get('BRANDS_SLIDER_NBR'), $this->context->shop->id,$this->context->language->id);

        $pre = $col ? 'S' : 'SLIDER';
        $ext = $col ? '_COL' : '';

		$this->smarty->assign(array(
            'brands' => $brands,
			'manufacturerSize' => Image::getSize(ImageType::getFormatedName('manufacturer')),

            'brand_slider_easing' => self::$easing[Configuration::get('BRANDS_'.$pre.'_EASING'.$ext)]['name'],
            'brand_slider_slideshow' => Configuration::get('BRANDS_'.$pre.'_SLIDESHOW'.$ext),
            'brand_slider_s_speed' => Configuration::get('BRANDS_'.$pre.'_S_SPEED'.$ext),
            'brand_slider_a_speed' => Configuration::get('BRANDS_'.$pre.'_A_SPEED'.$ext),
            'brand_slider_pause_on_hover' => Configuration::get('BRANDS_'.$pre.'_PAUSE_ON_HOVER'.$ext),
            'brand_slider_loop' => Configuration::get('BRANDS_'.$pre.'_LOOP'.$ext),

            'brand_slider_move' => Configuration::get('BRANDS_SLIDER_MOVE'),
            'brand_slider_items' => Configuration::get('BRANDS_S_ITEMS_COL'),
            'direction_nav'     => Configuration::get('BRANDS_DIRECTION_NAV'),

            'pro_per_lg'       => (int)Configuration::get('STSN_BRANDS_PRO_PER_LG_0'),
            'pro_per_md'       => (int)Configuration::get('STSN_BRANDS_PRO_PER_MD_0'),
            'pro_per_sm'       => (int)Configuration::get('STSN_BRANDS_PRO_PER_SM_0'),
            'pro_per_xs'       => (int)Configuration::get('STSN_BRANDS_PRO_PER_XS_0'),
            'pro_per_xxs'       => (int)Configuration::get('STSN_BRANDS_PRO_PER_XXS_0'),
        ));
    }
    public function hookActionObjectManufacturerDeleteAfter($params)
    {
        $this->clearBrandsSliderCache();
    }
    public function hookActionObjectManufacturerUpdateAfter($params)
    {
        $this->clearBrandsSliderCache();
    }
	private function clearBrandsSliderCache()
	{
        $this->_clearCache('*');
	}
    
    protected function stGetCacheId($key,$name = null)
    {
        $cache_id = parent::getCacheId($name);
        return $cache_id.'_'.$key;
    }

	public function getManufacturers($nbr, $id_shop , $id_lang = 0 )
	{
		if (!$id_lang)
			$id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		if (!$id_shop)
			$id_shop = (int)$this->context->shop->id;

		$sql = 'SELECT m.*, ml.`description`, ml.`short_description`
			FROM `'._DB_PREFIX_.'st_brands_slider` sbs
            LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = sbs.`id_manufacturer` 
			LEFT JOIN `'._DB_PREFIX_.'manufacturer_lang` ml ON (
				m.`id_manufacturer` = ml.`id_manufacturer`
				AND ml.`id_lang` = '.(int)$id_lang.'
			)
			'.Shop::addSqlAssociation('manufacturer', 'm');
			$sql .= ' WHERE sbs.`id_shop` = '.$id_shop.' AND m.`active` = 1 
            GROUP BY m.id_manufacturer ';
            switch(Configuration::get('BRANDS_SLIDER_ORDER'))
            {
                case 1:
                    $sql .= ' ORDER BY m.`name` ASC ';
                break;
                case 2:
                    $sql .= ' ORDER BY m.`name` DESC ';
                break;
                case 3:
                    $sql .= ' ORDER BY RAND() ';
                break;
                default:
                    $sql .= ' ORDER BY m.`name` ASC ';
                break;
            }
            $sql .= ($nbr ? ' LIMIT '.$nbr : '');

		$manufacturers = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if ($manufacturers === false)
			return false;

		$total_manufacturers = count($manufacturers);
		$rewrite_settings = (int)Configuration::get('PS_REWRITING_SETTINGS');

		for ($i = 0; $i < $total_manufacturers; $i++)
			if ($rewrite_settings)
				$manufacturers[$i]['link_rewrite'] = Tools::link_rewrite($manufacturers[$i]['name']);
			else
				$manufacturers[$i]['link_rewrite'] = 0;
		return $manufacturers;
	}
    
    public function getManufacturersAll($active = true)
	{
		$id_lang = (int)$this->context->language->id;
        
        $order_by = 'm.`name` DESC'; 
        switch(Configuration::get($this->_prefix_st.'SLIDER_ORDER'))
        {
            case 1:
                $order_by = ' m.`name` ASC ';
            break;
            case 2:
                $order_by = ' m.`name` DESC ';
            break;
            case 3:
                $order_by = ' RAND() ';
            break;
            default:
                $order_by = ' m.`name` ASC ';
            break;
        }   
   
		$manufacturers = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT m.*, ml.`description`, ml.`short_description`
		FROM `'._DB_PREFIX_.'manufacturer` m
		'.Shop::addSqlAssociation('manufacturer', 'm').'
		INNER JOIN `'._DB_PREFIX_.'manufacturer_lang` ml ON (m.`id_manufacturer` = ml.`id_manufacturer` AND ml.`id_lang` = '.(int)$id_lang.')
		'.($active ? 'WHERE m.`active` = 1' : '')
		.' GROUP BY m.`id_manufacturer`'
        .' ORDER by'.$order_by);
		if ($manufacturers === false)
			return false;

		$total_manufacturers = count($manufacturers);
		$rewrite_settings = (int)Configuration::get('PS_REWRITING_SETTINGS');
		for ($i = 0; $i < $total_manufacturers; $i++)
			$manufacturers[$i]['link_rewrite'] = ($rewrite_settings ? Tools::link_rewrite($manufacturers[$i]['name']) : 0);
		return $manufacturers;
	}
    
    private function getConfigFieldsValues()
    {
        $fields_values = array(
            'slider_nbr' => Configuration::get('BRANDS_SLIDER_NBR'),
            'slider_order' => Configuration::get('BRANDS_SLIDER_ORDER'),
            'slider_name' => Configuration::get('BRANDS_SLIDER_NAME'),
            'slider_short_desc' => Configuration::get('BRANDS_SLIDER_SHORT_DESC'),

            'slider_easing' => Configuration::get('BRANDS_SLIDER_EASING'),
            'slider_slideshow' => Configuration::get('BRANDS_SLIDER_SLIDESHOW'),
            'slider_s_speed' => Configuration::get('BRANDS_SLIDER_S_SPEED'),
            'slider_a_speed' => Configuration::get('BRANDS_SLIDER_A_SPEED'),
            'slider_pause_on_hover' => Configuration::get('BRANDS_SLIDER_PAUSE_ON_HOVER'),
            'slider_loop' => Configuration::get('BRANDS_SLIDER_LOOP'),

            'slider_move' => Configuration::get('BRANDS_SLIDER_MOVE'),

            's_easing_col' => Configuration::get('BRANDS_S_EASING_COL'),
            's_slideshow_col' => Configuration::get('BRANDS_S_SLIDESHOW_COL'),
            's_s_speed_col' => Configuration::get('BRANDS_S_S_SPEED_COL'),
            's_a_speed_col' => Configuration::get('BRANDS_S_A_SPEED_COL'),
            's_pause_on_hover_col' => Configuration::get('BRANDS_S_PAUSE_ON_HOVER_COL'),
            's_loop_col' => Configuration::get('BRANDS_S_LOOP_COL'),
            's_items_col' => Configuration::get('BRANDS_S_ITEMS_COL'),
            
            'manufacturers' => '',
            'all' =>  Configuration::get($this->_prefix_st.'ALL'),

            'top_padding'        => Configuration::get($this->_prefix_st.'TOP_PADDING'),
            'bottom_padding'     => Configuration::get($this->_prefix_st.'BOTTOM_PADDING'),
            'top_margin'         => Configuration::get($this->_prefix_st.'TOP_MARGIN'),
            'bottom_margin'      => Configuration::get($this->_prefix_st.'BOTTOM_MARGIN'),
            'bg_pattern'         => Configuration::get($this->_prefix_st.'BG_PATTERN'),
            'bg_img'             => Configuration::get($this->_prefix_st.'BG_IMG'),
            'bg_color'           => Configuration::get($this->_prefix_st.'BG_COLOR'),
            'speed'              => Configuration::get($this->_prefix_st.'SPEED'),

            'title_color'           => Configuration::get($this->_prefix_st.'TITLE_COLOR'),
            'direction_color'         => Configuration::get($this->_prefix_st.'DIRECTION_COLOR'),
            'direction_bg'         => Configuration::get($this->_prefix_st.'DIRECTION_BG'),
            'direction_hover_bg'   => Configuration::get($this->_prefix_st.'DIRECTION_HOVER_BG'),
            'direction_disabled_bg' => Configuration::get($this->_prefix_st.'DIRECTION_DISABLED_BG'),
            
            'title_alignment'       => Configuration::get($this->_prefix_st.'TITLE_ALIGNMENT'),
            'title_no_bg'           => Configuration::get($this->_prefix_st.'TITLE_NO_BG'),
            'title_font_size'       => Configuration::get($this->_prefix_st.'TITLE_FONT_SIZE'),
            'direction_nav'         => Configuration::get($this->_prefix_st.'DIRECTION_NAV'),
        );
        
        $manufacturers_html = '';
        foreach(StBrandsSliderClass::getByShop((int)$this->context->shop->id) AS $value)
        {
            $manufacturers_html .= '<li>'.Manufacturer::getNameById($value['id_manufacturer']).'
            <a href="javascript:;" class="del_manufacturer"><img src="../img/admin/delete.gif" /></a>
            <input type="hidden" name="id_manufacturer[]" value="'.$value['id_manufacturer'].'" /></li>';
        }
        
        $this->fields_form[0]['form']['input']['manufacturers']['desc'] = $this->l('Actually only for "Show all Brands" is set to "No".').'<br/>'.$this->l('Current manufacturers')
                .': <ul id="curr_manufacturers">'.$manufacturers_html.'</ul>';
                
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
            
            for ($i=1; $i < 8; $i++){
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
                'id' => 'brands_pro_per_lg_0',
                'label' => $this->l('Large devices'),
                'tooltip' => $this->l('Desktops (>1200px)'),
            ),
            array(
                'id' => 'brands_pro_per_md_0',
                'label' => $this->l('Medium devices'),
                'tooltip' => $this->l('Desktops (>992px)'),
            ),
            array(
                'id' => 'brands_pro_per_sm_0',
                'label' => $this->l('Small devices'),
                'tooltip' => $this->l('Tablets (>768px)'),
            ),
            array(
                'id' => 'brands_pro_per_xs_0',
                'label' => $this->l('Extra small devices'),
                'tooltip' => $this->l('Phones (>480px)'),
            ),
            array(
                'id' => 'brands_pro_per_xxs_0',
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