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

include_once(dirname(__FILE__).'/StSpecialSliderClass.php');
class StSpecialSlider extends Module
{
    protected static $cache_products = array();
    private $_html = '';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();
    private $_prefix_st = 'ST_SPECIAL_';
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
    
    public static $sort_by = array(
        1 => array('id' =>1 , 'name' => 'Date add: Desc'),
        2 => array('id' =>2 , 'name' => 'Date add: Asc'),
        3 => array('id' =>3 , 'name' => 'Date update: Desc'),
        4 => array('id' =>4 , 'name' => 'Date update: Asc'),
        5 => array('id' =>5 , 'name' => 'Product Name: A to Z'),
        6 => array('id' =>6 , 'name' => 'Product Name: Z to A'),
        7 => array('id' =>7 , 'name' => 'Price: Lowest first'),
        8 => array('id' =>8 , 'name' => 'Price: Highest first'),
        9 => array('id' =>9 , 'name' => 'Product ID: Asc'),
        10 => array('id' =>10 , 'name' => 'Product ID: Desc'),
    );
    private $_hooks = array();     
	function __construct()
	{
		$this->name           = 'stspecialslider';
		$this->tab            = 'front_office_features';
		$this->version        = '1.8.0';
		$this->author         = 'SUNNYTOO.COM';
		$this->need_instance  = 0;
        $this->bootstrap      = true;        

		parent::__construct();
        
        $this->initHookArray();

		$this->displayName = $this->l('Special Products Slider');
		$this->description = $this->l('Display special products slider on hompage.');
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
        			'id' => 'displayHomeTab',
        			'val' => '1',
        			'name' => $this->l('displayHomeTab')
        		),
                array(
        			'id' => 'displayHomeTabContent',
        			'val' => '1',
        			'name' => $this->l('displayHomeTabContent')
        		),
        		array(
        			'id' => 'displayHomeSecondaryLeft',
        			'val' => '1',
        			'name' => $this->l('displayHomeSecondaryLeft')
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
        		),
                array(
        			'id' => 'displayStBlogLeftColumn',
        			'val' => '1',
        			'name' => $this->l('displayStBlogLeftColumn')
        		),
        		array(
        			'id' => 'displayStBlogRightColumn',
        			'val' => '1',
        			'name' => $this->l('displayStBlogRightColumn')
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
    
	function install()
	{
		if (!parent::install()
            || !$this->installDB() 
            || !$this->registerHook('displayHeader')
			|| !$this->registerHook('addproduct')
			|| !$this->registerHook('updateproduct')
			|| !$this->registerHook('deleteproduct')
            || !$this->registerHook('displayHome')
            || !$this->registerHook('displayAdminProductPriceFormFooter')
            || !Configuration::updateValue('ST_SPECIAL_SLIDER_NBR', 8) 
            || !Configuration::updateValue('ST_SPECIAL_SLIDER_EASING', 0)
            || !Configuration::updateValue('ST_SPECIAL_SLIDER_SLIDESHOW', 0)
            || !Configuration::updateValue('ST_SPECIAL_SLIDER_S_SPEED', 7000)
            || !Configuration::updateValue('ST_SPECIAL_SLIDER_A_SPEED', 400)
            || !Configuration::updateValue('ST_SPECIAL_SLIDER_PAUSE_ON_HOVER', 1)
            || !Configuration::updateValue('ST_SPECIAL_SLIDER_LOOP', 0)
            || !Configuration::updateValue('ST_SPECIAL_SLIDER_MOVE', 0)
            || !Configuration::updateValue($this->_prefix_st.'COUNTDOWN_ON', 1)
            || !Configuration::updateValue('ST_SPECIAL_S_NBR_COL', 8) 
            || !Configuration::updateValue('ST_SPECIAL_DISPLAY_PRO_COL', 0) 
            || !Configuration::updateValue('ST_SPECIAL_S_EASING_COL', 0)
            || !Configuration::updateValue('ST_SPECIAL_S_SLIDESHOW_COL', 0)
            || !Configuration::updateValue('ST_SPECIAL_S_S_SPEED_COL', 7000)
            || !Configuration::updateValue('ST_SPECIAL_S_A_SPEED_COL', 400)
            || !Configuration::updateValue('ST_SPECIAL_S_PAUSE_ON_HOVER_COL', 1)
            || !Configuration::updateValue('ST_SPECIAL_S_LOOP_COL', 0)
            || !Configuration::updateValue('ST_SPECIAL_S_MOVE_COL', 0)
            || !Configuration::updateValue('ST_SPECIAL_S_ITEMS_COL', 4)
            || !Configuration::updateValue($this->_prefix_st.'COUNTDOWN_ON_COL', 1)
            || !Configuration::updateValue('ST_SPECIAL_SOBY', 7)
            || !Configuration::updateValue('ST_SPECIAL_S_SOBY_COL', 7)
            || !Configuration::updateValue('ST_SPECIAL_SLIDER_HIDE_MOB', 0)
            || !Configuration::updateValue('ST_SPECIAL_S_HIDE_MOB_COL', 0)
            || !Configuration::updateValue('ST_SPECIAL_S_NBR_FOT', 4) 
            || !Configuration::updateValue('ST_SPECIAL_S_SOBY_FOT', 1)
            || !Configuration::updateValue('ST_SPECIAL_SLIDER_DISPLAY_SD', 0)
            || !Configuration::updateValue('ST_SPECIAL_SLIDER_GRID', 0)
            || !Configuration::updateValue('STSN_SPECIAL_PRO_PER_LG_0', 4)
            || !Configuration::updateValue('STSN_SPECIAL_PRO_PER_MD_0', 4)
            || !Configuration::updateValue('STSN_SPECIAL_PRO_PER_SM_0', 3)
            || !Configuration::updateValue('STSN_SPECIAL_PRO_PER_XS_0', 2)
            || !Configuration::updateValue('STSN_SPECIAL_PRO_PER_XXS_0', 1)
            || !Configuration::updateValue('ST_SPECIAL_SLIDER_AW_DISPLAY', 1)
            || !Configuration::updateValue('ST_SPECIAL_S_AW_DISPLAY_COL', 1)
            || !Configuration::updateValue('ST_SPECIAL_S_AW_DISPLAY_FOT', 1)

            || !Configuration::updateValue('ST_SPECIAL_S_NBR_TAB', 8) 
            || !Configuration::updateValue('ST_SPECIAL_S_SOBY_TAB', 7)
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
            || !Configuration::updateValue($this->_prefix_st.'TEXT_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'PRICE_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'LINK_HOVER_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'GRID_HOVER_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_HOVER_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_DISABLED_BG', '')
            
            || !Configuration::updateValue($this->_prefix_st.'TITLE_ALIGNMENT', 0)
            || !Configuration::updateValue($this->_prefix_st.'TITLE_NO_BG', 0)
            || !Configuration::updateValue($this->_prefix_st.'TITLE_FONT_SIZE', 0)
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_NAV', 0)
        )
			return false;
            
		$this->clearSliderCache();
		return true;
	}
    
    public function uninstall()
	{
		$this->clearSliderCache();
		if (!parent::uninstall() 
            || !$this->uninstallDB()
        )
			return false;
		return true;
	}
    
    private function installDB()
	{
		return Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_special_product` (
                 `id_product` int(10) NOT NULL,  
                 `id_shop` int(11) NOT NULL,                   
                PRIMARY KEY (`id_product`,`id_shop`),    
                KEY `id_shop` (`id_shop`)       
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
	}
    
	private function uninstallDB()
	{
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_special_product`');
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
        if (Tools::getValue('act') == 'gsp' && Tools::getValue('ajax')==1)
        {
            if(!$q = Tools::getValue('q'))
                die;
            $result = $this->getAllSpecialProducts($q, Tools::getValue('excludeIds'));
            foreach ($result AS $value)
		      echo trim($value['name']).'['.trim($value['reference']).']|'.(int)($value['id_product'])."\n";
            die;
        }
        if (Tools::getValue('act') == 'setstspecial' && Tools::getValue('ajax')==1)
        {
            $ret = array('r'=>false,'msg'=>'');
            if(!$id_product = Tools::getValue('id_product'))
                $ret['msg'] = $this->l('Product ID error');
            else
            {
                if (StSpecialSliderClass::setByProductId($id_product, (int)Tools::getValue('fl'), $this->context->shop->id))
                {
                    $ret['r'] = true;
                    $ret['msg'] = $this->l('Successful update');
                    $this->clearSliderCache();
                }  
                else
                    $ret['msg'] = $this->l('Error occurred when updating');
            }
            echo json_encode($ret);
            die;
        }
	    $this->initFieldsForm();
		if (isset($_POST['savestspecialslider']))
		{
		    StSpecialSliderClass::deleteByShop((int)$this->context->shop->id);
            $res = true;
            if($id_product= Tools::getValue('id_product'))
                foreach($id_product AS $value)
                {
                  $res &= Db::getInstance()->insert('st_special_product', array(
        					'id_product' => (int)$value,
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
    		        $this->clearSliderCache();
                    $this->_html .= $this->displayConfirmation($this->l('Settings updated'));     
                }
            }   
        }
        $this->fields_form[1]['form']['input']['special_pro_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer());
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
				'title' => $this->l('General'),
                'icon'  => 'icon-cogs'
			),
			'input' => array(
				'products' => array(
					'type' => 'text',
					'label' => $this->l('Specific special products:'),
					'name' => 'products',
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
				'title' => $this->l('Slide on homepage'),
                'icon'  => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Define the number of products to be displayed:'),
					'name' => 'slider_nbr',
                    'default_value' => 8,
                    'required' => true,
                    'desc' => $this->l('Define the number of products that you would like to display on homepage (default: 8).'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'select',
        			'label' => $this->l('Sort by:'),
        			'name' => 'soby',
                    'options' => array(
        				'query' => self::$sort_by,
        				'id' => 'id',
        				'name' => 'name',
        			),
                    'validation' => 'isUnsignedInt',
				), 
                array(
                    'type' => 'radio',
                    'label' => $this->l('How to display products:'),
                    'name' => 'slider_grid',
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
                'special_pro_per_0' => array(
                    'type' => 'html',
                    'id' => 'special_pro_per_0',
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
							'id' => 'slider_slideshow_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'slider_slideshow_off',
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
                    'required' => true,
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Transition period:'),
					'name' => 'slider_a_speed',
                    'default_value' => 400,
                    'required' => true,
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
							'id' => 'slider_pause_on_hover_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'slider_pause_on_hover_off',
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
							'id' => 'slider_loop_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'slider_loop_off',
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
							'id' => 'slider_move_on',
							'value' => 1,
							'label' => $this->l('1 item')),
						array(
							'id' => 'slider_move_off',
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
					'label' => $this->l('Display product short description'),
					'name' => 'slider_display_sd',
                    'default_value' => 0,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'slider_display_sd_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'slider_display_sd_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Always display this block:'),
                    'name' => 'slider_aw_display',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'slider_aw_display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'slider_aw_display_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
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
                    'label' => $this->l('Heading hover color:'),
                    'name' => 'title_hover_color',
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
				'title' => $this->l('   Save all  '),
			),
		);
        
		$this->fields_form[2]['form'] = array(
			'legend' => array(
				'title' => $this->l('Slide on the left/right column'),
                'icon'  => 'icon-cogs'
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
					'type' => 'text',
					'label' => $this->l('Define the number of products to be displayed:'),
					'name' => 's_nbr_col',
                    'default_value' => 8,
                    'required' => true,
                    'desc' => $this->l('Define the number of products that you would like to display on sidebar (default: 8).'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
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
				), 
                array(
					'type' => 'select',
        			'label' => $this->l('Sort by:'),
        			'name' => 's_soby_col',
                    'options' => array(
        				'query' => self::$sort_by,
        				'id' => 'id',
        				'name' => 'name',
        			),
                    'validation' => 'isUnsignedInt',
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Autoplay:'),
					'name' => 's_slideshow_col',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 's_slideshow_col_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 's_slideshow_col_off',
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
                    'required' => true,
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Transition period:'),
					'name' => 's_a_speed_col',
                    'default_value' => 400,
                    'required' => true,
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
							'id' => 's_pause_on_hover_col_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 's_pause_on_hover_col_off',
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
							'id' => 's_loop_col_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 's_loop_col_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('"No" if you want to perform the animation once; "Yes" to loop the animation'),
                    'validation' => 'isBool',
				),
                array(
					'type' => 'hidden',
					'name' => 's_move_col',
                    'default_value' => 1,
                    'validation' => 'isBool',
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Hide slideshow on mobile devices:'),
					'name' => 's_hide_mob_col',
                    'default_value' => 0,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 's_hide_mob_col_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 's_hide_mob_col_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('if set to Yes, slider will be hidden on mobile devices (if screen width is less than 768 pixels).'),
                    'validation' => 'isBool',
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Always display this block:'),
                    'name' => 's_aw_display_col',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 's_aw_display_col_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 's_aw_display_col_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
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
			),
			'submit' => array(
				'title' => $this->l('   Save all  ')
			),
		);
        $this->fields_form[3]['form'] = array(
            'legend' => array(
                'title' => $this->l('Slide on footer'),
                'icon'  => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Define the number of products to be displayed:'),
                    'name' => 's_nbr_fot',
                    'default_value' => 4,
                    'required' => true,
                    'desc' => $this->l('Define the number of products that you would like to display on footer (default: 4).'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Sort by:'),
                    'name' => 's_soby_fot',
                    'options' => array(
                        'query' => self::$sort_by,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Always display this block:'),
                    'name' => 's_aw_display_fot',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 's_aw_display_fot_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 's_aw_display_fot_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Save   ')
            ),
        );
        $this->fields_form[4]['form'] = array(
			'legend' => array(
				'title' => $this->l('Home page tabs'),
                'icon'  => 'icon-cogs'
			),
			'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Define the number of products to be displayed:'),
                    'name' => 's_nbr_tab',
                    'default_value' => 8,
                    'required' => true,
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Sort by:'),
                    'name' => 's_soby_tab',
                    'options' => array(
                        'query' => self::$sort_by,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
			),
			'submit' => array(
				'title' => $this->l('   Save all  ')
			),
		);
        
        $this->fields_form[5]['form'] = array(
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
            $this->fields_form[5]['form']['input'][] = array(
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
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestspecialslider';
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
            $title_block_no_bg = '.special-products_block_center_container .title_block, .special-products_block_center_container .nav_top_right .flex-direction-nav,.special-products_block_center_container .title_block a, .special-products_block_center_container .title_block span{background:none;}';
            
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
                $custom_css .= '.special-products_block_center_container{background-attachment:fixed;'.$group_css.'}'.$title_block_no_bg;

            if ($top_padding = (int)Configuration::get($this->_prefix_st.'TOP_PADDING'))
                $custom_css .= '.special-products_block_center_container{padding-top:'.$top_padding.'px;}';
            if ($bottom_padding = (int)Configuration::get($this->_prefix_st.'BOTTOM_PADDING'))
                $custom_css .= '.special-products_block_center_container{padding-bottom:'.$bottom_padding.'px;}';

            $top_margin = Configuration::get($this->_prefix_st.'TOP_MARGIN');
            if($top_margin || $top_margin!==null)
                $custom_css .= '.special-products_block_center_container{margin-top:'.$top_margin.'px;}';
            $bottom_margin = Configuration::get($this->_prefix_st.'BOTTOM_MARGIN');
            if($bottom_margin || $bottom_margin!==null)
                $custom_css .= '.special-products_block_center_container{margin-bottom:'.$bottom_margin.'px;}';

            if (Configuration::get($this->_prefix_st.'TITLE_ALIGNMENT'))
                $custom_css .= '.special-products_block_center_container .title_block{text-align:center;}'.$title_block_no_bg;
            if (Configuration::get($this->_prefix_st.'TITLE_NO_BG'))
                $custom_css .= $title_block_no_bg;
            if ($title_font_size = (int)Configuration::get($this->_prefix_st.'TITLE_FONT_SIZE'))
            {
                 $custom_css .= '.special-products_block_center_container .title_block{font-size:'.$title_font_size.'px;}';
                 $custom_css .= '.special-products_block_center_container .nav_top_right .flex-direction-nav{top:-'.(round((round($title_font_size*1.3)-24)/2)+24+22).'px;}';
            }
            
            if ($title_color = Configuration::get($this->_prefix_st.'TITLE_COLOR'))
                $custom_css .= '.special-products_block_center_container.block .title_block a, .special-products_block_center_container.block .title_block span{color:'.$title_color.';}';
            if ($title_hover_color = Configuration::get($this->_prefix_st.'TITLE_HOVER_COLOR'))
                $custom_css .= '.special-products_block_center_container.block .title_block a:hover{color:'.$title_hover_color.';}';

            if ($text_color = Configuration::get($this->_prefix_st.'TEXT_COLOR'))
                $custom_css .= '.special-products_block_center_container .s_title_block a,
                .special-products_block_center_container .price,
                .special-products_block_center_container .old_price,
                .special-products_block_center_container .product_desc{color:'.$text_color.';}';

            if ($price_color = Configuration::get($this->_prefix_st.'PRICE_COLOR'))
                $custom_css .= '.special-products_block_center_container .price{color:'.$price_color.';}';
            if ($link_hover_color = Configuration::get($this->_prefix_st.'LINK_HOVER_COLOR'))
                $custom_css .= '.special-products_block_center_container .s_title_block a:hover{color:'.$link_hover_color.';}';

            if ($grid_hover_bg = Configuration::get($this->_prefix_st.'GRID_HOVER_BG'))
                $custom_css .= '.special-products_block_center_container .products_slider .ajax_block_product:hover .pro_second_box, .special-products_block_center_container .product_list.grid .ajax_block_product:hover .pro_second_box{background-color:'.$grid_hover_bg.';}';

            if ($direction_color = Configuration::get($this->_prefix_st.'DIRECTION_COLOR'))
                $custom_css .= '.special-products_block_center_container .flex-direction-nav a{color:'.$direction_color.';}';
            if ($direction_bg = Configuration::get($this->_prefix_st.'DIRECTION_BG'))
                $custom_css .= '.special-products_block_center_container .flex-direction-nav a{background-color:'.$direction_bg.';}';
            if ($direction_hover_bg = Configuration::get($this->_prefix_st.'DIRECTION_HOVER_BG'))
                $custom_css .= '.special-products_block_center_container .flex-direction-nav a:hover{background-color:'.$direction_hover_bg.';}';
            if ($direction_disabled_bg = Configuration::get($this->_prefix_st.'DIRECTION_DISABLED_BG'))
                $custom_css .= '.special-products_block_center_container .flex-direction-nav a.flex-disabled{background-color:'.$direction_disabled_bg.';}';

            if($custom_css)
                $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
        }
        return $this->display(__FILE__, 'header.tpl', $this->getCacheId());
    }
    public function hookDisplayAdminProductPriceFormFooter($params)
    {
        $this->smarty->assign(array(
            'id_product' => Tools::getValue('id_product'),
            'currentIndex' => $this->context->link->getAdminLink('AdminModules', true).'&configure='.$this->name,
            'checked' => StSpecialSliderClass::exists(Tools::getValue('id_product') ,$this->context->shop->id)
            ));
        return $this->display(__FILE__, 'views/templates/admin/stspecialslider.tpl');
    }
    public function hookDisplayHomeTop($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    public function hookDisplayHomeBottom($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    public function hookDisplayHome($params, $hook_hash = '' ,$flag=0)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return ;
        if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
	    if (Configuration::get('ST_SPECIAL_SLIDER_GRID') || !$this->isCached('stspecialslider.tpl', $this->stGetCacheId($hook_hash)))
	    {
            StSpecialSlider::$cache_products[0] = $this->getProducts(0);
            $this->smarty->assign(array(
                'products' => StSpecialSlider::$cache_products[0],
                'mediumSize' => Image::getSize(ImageType::getFormatedName('medium')),
                'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
                'add_prod_display'  => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
                'display_sd'            => (int)Configuration::get('ST_SPECIAL_SLIDER_DISPLAY_SD'),

                'column_slider'         => false,
                'homeverybottom'         => ($flag==2 ? true : false),
                'hook_hash'        => $hook_hash,

                'pro_per_lg'       => (int)Configuration::get('STSN_SPECIAL_PRO_PER_LG_0'),
                'pro_per_md'       => (int)Configuration::get('STSN_SPECIAL_PRO_PER_MD_0'),
                'pro_per_sm'       => (int)Configuration::get('STSN_SPECIAL_PRO_PER_SM_0'),
                'pro_per_xs'       => (int)Configuration::get('STSN_SPECIAL_PRO_PER_XS_0'),
                'pro_per_xxs'       => (int)Configuration::get('STSN_SPECIAL_PRO_PER_XXS_0'),
                'has_background_img'     => ((int)Configuration::get($this->_prefix_st.'BG_PATTERN') || Configuration::get($this->_prefix_st.'BG_IMG')) ? 1 : 0,
                'speed'          => (float)Configuration::get($this->_prefix_st.'SPEED'),
                'direction_nav'          => Configuration::get($this->_prefix_st.'DIRECTION_NAV'),
            ));
            if(!$this->_prepareHook(0))
                return false;        
        }

		return $this->display(__FILE__, 'stspecialslider.tpl', Configuration::get('ST_SPECIAL_SLIDER_GRID') ? NULL : $this->stGetCacheId($hook_hash));
	}
    
    public function hookDisplayBottomColumn($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
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
    public function hookDisplayHomeVeryBottom($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__) ,2);
    }

    public function hookDisplayHomeTertiaryLeft($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    public function hookDisplayHomeTertiaryRight($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    
	public function hookDisplayHomeSecondaryLeft($params)
	{
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__)); 
    }
    
    private function getProducts($col=0)
    {
        $pre = 'SLIDER';
        $col==1 && $pre = 'S';
        $col==2 && $pre = 'S';
        $col==3 && $pre = 'S';

        $ext = '';
        $col==1 && $ext = '_COL';
        $col==2 && $ext = '_FOT';
        $col==3 && $ext = '_TAB';

        $nbr = Configuration::get('ST_SPECIAL_'.$pre.'_NBR'.$ext);
        ($nbr===false && $col) && $nbr = Configuration::get('ST_SPECIAL_SLIDER_NBR');
        
        if(!$nbr)
            return false;
        
        $order_by = 'price';
        $order_way = 'ASC';
        $soby = $col ? (int)Configuration::get('ST_SPECIAL_'.$pre.'_SOBY'.$ext) : (int)Configuration::get('ST_SPECIAL_SOBY');
        switch($soby)
        {
            case 1:
                $order_by = 'date_add';
                $order_way = 'DESC';
            break;
            case 2:
                $order_by = 'date_add';
                $order_way = 'ASC';
            break;
            case 3:
                $order_by = 'date_upd';
                $order_way = 'DESC';
            break;
            case 4:
                $order_by = 'date_upd';
                $order_way = 'ASC';
            break;
            case 5:
                $order_by = 'name';
                $order_way = 'ASC';
            break;
            case 6:
                $order_by = 'name';
                $order_way = 'DESC';
            break;
            case 7:
                $order_by = 'price';
                $order_way = 'ASC';
            break;
            case 8:
                $order_by = 'price';
                $order_way = 'DESC';
            break;
            case 9:
                $order_by = 'id_product';
                $order_way = 'ASC';
            break;
            case 10:
                $order_by = 'id_product';
                $order_way = 'DESC';
            break;
            default:
            break;
        }
        
        $products = array();
        $id_checked = array();
        foreach(StSpecialSliderClass::getByShop($this->context->shop->id) as $value)
            $id_checked[] = $value['id_product'];
        if ($id_checked)
        {
            $id_address = $this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
    		$ids = Address::getCountryAndState($id_address);
    		$id_country = (int)($ids['id_country'] ? $ids['id_country'] : Configuration::get('PS_COUNTRY_DEFAULT'));
            $beginning = $ending = date('Y-m-d H:i:s');
    
    		$id_special = SpecificPrice::getProductIdByDate(
    			$this->context->shop->id,
    			$this->context->currency->id,
    			$id_country,
    			$this->context->customer->id_default_group,
    			$beginning,
    			$ending
    		);
            if (!$id_special)
                return $products;
            $nbr = count($id_special);
        }
        
        $products = Product::getPricesDrop((int)($this->context->language->id), 0, (int)$nbr, false, $order_by, $order_way);
        
        if (!$products)
            return $products;
        
        if($id_checked)
            foreach($products AS $key => $value)
                if (!in_array($value['id_product'], $id_checked))
                    unset($products[$key]);
        
        $homeSize = Image::getSize(ImageType::getFormatedName('home'));
        
        if(is_array($products) && count($products))
        {
            $module_stthemeeditor = Module::getInstanceByName('stthemeeditor');
            if ($module_stthemeeditor && $module_stthemeeditor->id)
                $id_module_stthemeeditor = $module_stthemeeditor->id;
                    
            $module_sthoverimage = Module::getInstanceByName('sthoverimage');
            if ($module_sthoverimage && $module_sthoverimage->id)
                $id_module_sthoverimage = $module_sthoverimage->id;
                
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
        return $products;
    }

    private function _prepareHook($col=0)
    {
        $pre = $col ? 'S' : 'SLIDER';
        $ext = $col ? '_COL' : '';

        $easing = Configuration::get('ST_SPECIAL_'.$pre.'_EASING'.$ext);
        ($easing===false && $col) && $easing = Configuration::get('ST_SPECIAL_SLIDER_EASING');
        
        $slideshow = Configuration::get('ST_SPECIAL_'.$pre.'_SLIDESHOW'.$ext);
        ($slideshow===false && $col) && $slideshow = Configuration::get('ST_SPECIAL_SLIDER_SLIDESHOW');
        
        $s_speed = Configuration::get('ST_SPECIAL_'.$pre.'_S_SPEED'.$ext);
        ($s_speed===false && $col) && $s_speed = Configuration::get('ST_SPECIAL_SLIDER_S_SPEED');
        
        $a_speed = Configuration::get('ST_SPECIAL_'.$pre.'_A_SPEED'.$ext);
        ($a_speed===false && $col) && $a_speed = Configuration::get('ST_SPECIAL_SLIDER_A_SPEED');
        
        $pause_on_hover = Configuration::get('ST_SPECIAL_'.$pre.'_PAUSE_ON_HOVER'.$ext);
        ($pause_on_hover===false && $col) && $pause_on_hover = Configuration::get('ST_SPECIAL_SLIDER_PAUSE_ON_HOVER');
        
        $loop = Configuration::get('ST_SPECIAL_'.$pre.'_LOOP'.$ext);
        ($loop===false && $col) && $loop = Configuration::get('ST_SPECIAL_SLIDER_LOOP');
        
        $move = Configuration::get('ST_SPECIAL_'.$pre.'_MOVE'.$ext);
        ($move===false && $col) && $move = Configuration::get('ST_SPECIAL_SLIDER_MOVE');
        
        $hide_mob = Configuration::get('ST_SPECIAL_'.$pre.'_HIDE_MOB'.$ext);
        ($hide_mob===false && $col) && $hide_mob = Configuration::get('ST_SPECIAL_SLIDER_HIDE_MOB');

        $aw_display = Configuration::get('ST_SPECIAL_'.$pre.'_AW_DISPLAY'.$ext);

        $this->smarty->assign(array(
            'slider_easing'         => self::$easing[$easing]['name'],
            'slider_slideshow'      => $slideshow,
            'slider_s_speed'        => $s_speed,
            'slider_a_speed'        => $a_speed,
            'slider_pause_on_hover' => $pause_on_hover,
            'slider_loop'           => $loop,
            'slider_move'           => $move,
            'hide_mob'              => (int)$hide_mob,
            'aw_display'            => $aw_display,

            'display_as_grid'       => Configuration::get('ST_SPECIAL_SLIDER_GRID'),
            'display_pro_col'       => Configuration::get('ST_SPECIAL_DISPLAY_PRO_COL'),
            'countdown_on'          => Configuration::get($this->_prefix_st.'COUNTDOWN_ON'.$ext),
		));
        return true;
    }
    
	public function hookDisplayLeftColumn($params, $hook_hash = '')
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return ;
        if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
	    if (!$this->isCached('stspecialslider.tpl', $this->stGetCacheId($hook_hash)))
    	{
            StSpecialSlider::$cache_products[1] = $this->getProducts(1);
            $this->smarty->assign(array(
                'products' => StSpecialSlider::$cache_products[1],
                'thumbSize' => Image::getSize(ImageType::getFormatedName('thumb')),
                'add_prod_display'  => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
                'slider_items'          => Configuration::get('ST_SPECIAL_S_ITEMS_COL'),
                'column_slider'         => true,
                'hook_hash'             => $hook_hash,
            ));
            if(!$this->_prepareHook(1))
                return false;        
        }
		return $this->display(__FILE__, 'stspecialslider.tpl', $this->stGetCacheId($hook_hash));
	}
	public function hookDisplayRightColumn($params)
    {
        return $this->hookDisplayLeftColumn($params, $this->getHookHash(__FUNCTION__));
    }
	public function hookDisplayHomeSecondaryRight($params)
    {
        return $this->hookDisplayLeftColumn($params, $this->getHookHash(__FUNCTION__));
    }
	public function hookDisplayProductSecondaryColumn($params)
	{
        return $this->hookDisplayLeftColumn($params, $this->getHookHash(__FUNCTION__));
	}
    
	public function hookDisplayStBlogLeftColumn($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
        return $this->hookDisplayLeftColumn($params, $this->getHookHash(__FUNCTION__));
	}
	public function hookDisplayStBlogRightColumn($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
        return $this->hookDisplayLeftColumn($params, $this->getHookHash(__FUNCTION__));
	}
    
    public function hookDisplayFooter($params, $hook_hash = '')
    {
        if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
	    if (!$this->isCached('stspecialslider-footer.tpl', $this->stGetCacheId($hook_hash)))
	    {
            StSpecialSlider::$cache_products[2] = $this->getProducts(2);
            $this->smarty->assign(array(
    			'products' => StSpecialSlider::$cache_products[2],
    			'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
    			'thumbSize' => Image::getSize(ImageType::getFormatedName('thumb')),
                'aw_display' => Configuration::get('ST_SPECIAL_S_AW_DISPLAY_FOT'),
                'hook_hash'=> $hook_hash,
    		));
	    }
		return $this->display(__FILE__, 'stspecialslider-footer.tpl', $this->stGetCacheId($hook_hash));
    }
    public function hookDisplayFooterTop($params)
    {
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));
    }
    public function hookDisplayFooterSecondary($params)
    {
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));        
    }

    public function hookdisplayHomeTab($params)
    {
        if (!isset(StSpecialSlider::$cache_products[3]))
            StSpecialSlider::$cache_products[3] = $this->getProducts(3);

        if (!StSpecialSlider::$cache_products[3])
            return false;

        return $this->display(__FILE__, 'tab.tpl', $this->stGetCacheId('stspecialslider-tab'));
    }

    public function hookdisplayHomeTabContent($params)
    {
        if (!isset(StSpecialSlider::$cache_products[3]) || StSpecialSlider::$cache_products[3]=== false)
            return false;
        
        if (!$this->isCached('stspecialslider-home.tpl', $this->stGetCacheId('stspecialslider-home')))
        {
            $this->smarty->assign(array(
                'speical_products' => StSpecialSlider::$cache_products[3],
                'mediumSize' => Image::getSize(ImageType::getFormatedName('medium')),
                'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
                'countdown_on' => Configuration::get($this->_prefix_st.'COUNTDOWN_ON'),
            ));
        }

        return $this->display(__FILE__, 'stspecialslider-home.tpl', $this->stGetCacheId('stspecialslider-home'));
    }

    public function hookAddProduct($params)
	{
		$this->clearSliderCache();
	}

	public function hookUpdateProduct($params)
	{
		$this->clearSliderCache();
	}

	public function hookDeleteProduct($params)
	{
		$this->clearSliderCache();
	}
	private function clearSliderCache()
	{
		$this->_clearCache('*');
    }
    protected function stGetCacheId($key = '')
    {
        $cache_id = parent::getCacheId();
		return $cache_id.'_'.$key;
    }
    private function getConfigFieldsValues()
    {
        $fields_values = array(
            'products' => '',
            'slider_nbr'=> Configuration::get('ST_SPECIAL_SLIDER_NBR'),
            'slider_easing'=> Configuration::get('ST_SPECIAL_SLIDER_EASING'),
            'slider_slideshow'=> Configuration::get('ST_SPECIAL_SLIDER_SLIDESHOW'),
            'slider_s_speed'=> Configuration::get('ST_SPECIAL_SLIDER_S_SPEED'),
            'slider_a_speed'=> Configuration::get('ST_SPECIAL_SLIDER_A_SPEED'),
            'slider_pause_on_hover'=> Configuration::get('ST_SPECIAL_SLIDER_PAUSE_ON_HOVER'),
            'slider_loop'=> Configuration::get('ST_SPECIAL_SLIDER_LOOP'),
            'slider_move'=> Configuration::get('ST_SPECIAL_SLIDER_MOVE'),
            'soby'=> Configuration::get('ST_SPECIAL_SOBY'),
            'hide_mob'=> Configuration::get('ST_SPECIAL_HIDE_MOB'),
            'slider_display_sd'=> Configuration::get('ST_SPECIAL_DISPLAY_SD'),
            'slider_grid'=> Configuration::get('ST_SPECIAL_SLIDER_GRID'),
            'slider_aw_display' => Configuration::get('ST_SPECIAL_SLIDER_AW_DISPLAY'),
            'countdown_on'  => Configuration::get($this->_prefix_st.'COUNTDOWN_ON'),
            
            'display_pro_col'=> Configuration::get('ST_SPECIAL_DISPLAY_PRO_COL'),
            's_nbr_col'=> Configuration::get('ST_SPECIAL_S_NBR_COL'),
            's_easing_col'=> Configuration::get('ST_SPECIAL_S_EASING_COL'),
            's_slideshow_col'=> Configuration::get('ST_SPECIAL_S_SLIDESHOW_COL'),
            's_s_speed_col'=> Configuration::get('ST_SPECIAL_S_S_SPEED_COL'),
            's_a_speed_col'=> Configuration::get('ST_SPECIAL_S_A_SPEED_COL'),
            's_pause_on_hover_col'=> Configuration::get('ST_SPECIAL_S_PAUSE_ON_HOVER_COL'),
            's_loop_col'=> Configuration::get('ST_SPECIAL_S_LOOP_COL'),
            's_move_col'=> Configuration::get('ST_SPECIAL_S_MOVE_COL'),
            's_items_col'=> Configuration::get('ST_SPECIAL_S_ITEMS_COL'),
            's_soby_col'=> Configuration::get('ST_SPECIAL_S_SOBY_COL'),
            's_hide_mob_col'=> Configuration::get('ST_SPECIAL_S_HIDE_MOB_COL'),
            's_aw_display_col' => Configuration::get('ST_SPECIAL_S_AW_DISPLAY_COL'),
            'countdown_on_col' => Configuration::get($this->_prefix_st.'COUNTDOWN_ON_COL'),
            
            's_nbr_fot'=> Configuration::get('ST_SPECIAL_S_NBR_FOT'),
            's_soby_fot'=> Configuration::get('ST_SPECIAL_S_SOBY_FOT'),   
            's_aw_display_fot' => Configuration::get('ST_SPECIAL_S_AW_DISPLAY_FOT'),   

            's_soby_tab'=> Configuration::get('ST_SPECIAL_S_SOBY_TAB'),
            's_nbr_tab'=> Configuration::get('ST_SPECIAL_S_NBR_TAB'),
            
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
            'text_color'            => Configuration::get($this->_prefix_st.'TEXT_COLOR'),
            'price_color'           => Configuration::get($this->_prefix_st.'PRICE_COLOR'),
            'link_hover_color'      => Configuration::get($this->_prefix_st.'LINK_HOVER_COLOR'),
            'grid_hover_bg'         => Configuration::get($this->_prefix_st.'GRID_HOVER_BG'),
            'direction_color'         => Configuration::get($this->_prefix_st.'DIRECTION_COLOR'),
            'direction_bg'         => Configuration::get($this->_prefix_st.'DIRECTION_BG'),
            'direction_hover_bg'   => Configuration::get($this->_prefix_st.'DIRECTION_HOVER_BG'),
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
        
        $products_html = '';
        foreach(StSpecialSliderClass::getByShop((int)$this->context->shop->id) AS $value)
        {
            $product = new Product($value['id_product'], false, Context::getContext()->language->id);
            $products_html .= '<li>'.$product->name.'['.$product->reference.']
            <a href="javascript:;" class="del_product"><img src="../img/admin/delete.gif" /></a>
            <input type="hidden" name="id_product[]" value="'.$value['id_product'].'" /></li>';
        }
        
        $this->fields_form[0]['form']['input']['products']['desc'] = $this->l('Only display the following products on frontoffice if specified.').'<br/>'.$this->l('Current products')
                .': <ul id="curr_products">'.$products_html.'</ul>';
        
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
                'id' => 'special_pro_per_lg_0',
                'label' => $this->l('Large devices'),
                'tooltip' => $this->l('Desktops (>1200px)'),
            ),
            array(
                'id' => 'special_pro_per_md_0',
                'label' => $this->l('Medium devices'),
                'tooltip' => $this->l('Desktops (>992px)'),
            ),
            array(
                'id' => 'special_pro_per_sm_0',
                'label' => $this->l('Small devices'),
                'tooltip' => $this->l('Tablets (>768px)'),
            ),
            array(
                'id' => 'special_pro_per_xs_0',
                'label' => $this->l('Extra small devices'),
                'tooltip' => $this->l('Phones (>480px)'),
            ),
            array(
                'id' => 'special_pro_per_xxs_0',
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
    
    private function getAllSpecialProducts($q = '',$excludeIds = false)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT `id_product`, `id_product_attribute`
			FROM `'._DB_PREFIX_.'specific_price`
			WHERE `reduction` > 0
		    ', false);
		$ids_product = array();
		while ($row = Db::getInstance()->nextRow($result))
			$ids_product[] = (int)$row['id_product'];
        if (!$ids_product)
            return $ids_product;
        
        $sql = '
		SELECT p.`id_product`,pl.`name`,p.`reference`
		FROM `'._DB_PREFIX_.'product` p
		'.Shop::addSqlAssociation('product', 'p').'
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
			p.`id_product` = pl.`id_product`
			AND pl.`id_lang` = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('pl').'
		)
        WHERE p.`id_product` IN('.implode(',', array_unique($ids_product)).')
        AND pl.`name` LIKE "%'.$q.'%"
        '.($excludeIds ? 'AND p.`id_product` NOT IN('.$excludeIds.')' : '').'
        ';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }
    public function get_prefix()
    {
        if (isset($this->_prefix_st) && $this->_prefix_st)
            return $this->_prefix_st;
        return false;
    }
}