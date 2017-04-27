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

include_once(dirname(__FILE__).'/StCountDownClass.php');
class StCountDown extends Module
{
    protected static $cache_products = array();
    private $_html = '';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();
    private $_prefix_st = 'ST_COUNTDOWN_';
    private $_prefix_stsn = 'STSN_';
    private $_pages = array();     
    private $systemFonts = array("Helvetica","Arial","Verdana","Georgia","Tahoma","Times New Roman","sans-serif");
    private $googleFonts;
    public static $textTransform = array(
        array('id' => 0, 'name' => 'none'),
        array('id' => 1, 'name' => 'uppercase'),
        array('id' => 2, 'name' => 'lowercase'),
        array('id' => 3, 'name' => 'capitalize'),
    );
    private $_font_inherit = 'inherit';
	function __construct()
	{
		$this->name           = 'stcountdown';
		$this->tab            = 'front_office_features';
		$this->version        = '1.0';
		$this->author         = 'SUNNYTOO.COM';
		$this->need_instance  = 0;
        $this->bootstrap      = true;        

		parent::__construct();
        $this->initPages();
        $this->googleFonts = include_once(dirname(__FILE__).'/googlefonts.php');

		$this->displayName = $this->l('Countdown module');
		$this->description = $this->l('Display countdown special products.');
	}
    
    private function initPages()
    {
        $this->_pages = array(
                array(
                    'id' => 'index',
                    'val' => '1',
                    'name' => $this->l('Index')
                ),
                array(
        			'id' => 'category',
        			'val' => '1',
        			'name' => $this->l('Category')
        		),
        		array(
        			'id' => 'product',
        			'val' => '1',
        			'name' => $this->l('Product')
        		),
                array(
        			'id' => 'pricesdrop',
        			'val' => '1',
        			'name' => $this->l('Prices Drop')
        		),
                array(
                    'id' => 'newproducts',
                    'val' => '1',
                    'name' => $this->l('New Products')
                ),
        		array(
        			'id' => 'manufacturer',
        			'val' => '1',
        			'name' => $this->l('Manufacturer')
        		),
                array(
        			'id' => 'supplier',
        			'val' => '1',
        			'name' => $this->l('Supplier')
        		),
                array(
        			'id' => 'bestsales',
        			'val' => '1',
        			'name' => $this->l('Best Sales')
        		),
            );
    }
    
    private function savePages()
    {
        foreach($this->_pages AS $value)
            if (Tools::getValue('display_on_'.$value['id']))
                Configuration::updateValue($this->_prefix_st.strtoupper('display_on_'.$value['id']), 1);
            else
                Configuration::updateValue($this->_prefix_st.strtoupper('display_on_'.$value['id']), 0);
    }
    
	function install()
	{
		if (!parent::install()
            || !$this->installDB() 
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayAdminProductPriceFormFooter')
            || !Configuration::updateValue($this->_prefix_st.'ACTIVE', 1)
            || !Configuration::updateValue($this->_prefix_st.'DISAPLY_ALL', 0)
            || !Configuration::updateValue($this->_prefix_st.'STYLE', 0)
            || !Configuration::updateValue($this->_prefix_st.'HEIGHT', 0)
            || !Configuration::updateValue($this->_prefix_st.'PADDING', 2)
            || !Configuration::updateValue($this->_prefix_st.'TEXT_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'BG', '')
            || !Configuration::updateValue($this->_prefix_st.'BG_OPACITY', 0.9)
            || !Configuration::updateValue($this->_prefix_st.'NUMBER_SIZE', 0)
            || !Configuration::updateValue($this->_prefix_st.'FONT_SIZE', 0)
            || !Configuration::updateValue($this->_prefix_st.'DIVIDER', 1)
            || !Configuration::updateValue($this->_prefix_st.'DIVIDER_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'V_ALIGNMENT', 0)
            || !Configuration::updateValue($this->_prefix_st.'TITLE_AW_DISPLAY', 1)
            || !Configuration::updateValue($this->_prefix_st.'PRO_TEXT_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'PRO_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'PRO_FONT_SIZE', 0)
            || !Configuration::updateValue($this->_prefix_st.'PRO_FONT_TRANS', 0)
            || !Configuration::updateValue($this->_prefix_st.'NUMBER_FONT', '')
            || !Configuration::updateValue($this->_prefix_st.'TEXT_FONT', '')
            || !Configuration::updateValue($this->_prefix_st.'PRO_TEXT_FONT', '')
            
            || !Configuration::updateValue($this->_prefix_st.'BLKNEW', 0)
            || !Configuration::updateValue($this->_prefix_st.'BLKSPECIAL', 0)
            || !Configuration::updateValue($this->_prefix_st.'BLKBEST', 0)
            || !Configuration::updateValue($this->_prefix_st.'BLKFEATURED', 0)
        )
			return false;
        foreach($this->_pages AS $value)
             if(!Configuration::updateValue($this->_prefix_st.strtoupper('display_on_'.$value['id']), 1))
                return false;
            
		$this->clearSliderCache();
		return true;
	}
    
    private function installDB()
	{
		return Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_countdown_product` (
                 `id_product` int(10) NOT NULL,  
                 `id_shop` int(11) NOT NULL,                   
                PRIMARY KEY (`id_product`,`id_shop`),    
                KEY `id_shop` (`id_shop`)       
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
	}
    
	private function uninstallDB()
	{
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_countdown_product`');
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
    
    public function getContent()
	{
        $this->context->controller->addJS($this->_path.'views/js/admin.js');
        $this->context->controller->addCSS(($this->_path).'views/css/admin.css');
        $this->_html .= '<script type="text/javascript">var systemFonts = \''.implode(',',$this->systemFonts).'\'; var googleFontsString=\''.Tools::jsonEncode($this->googleFonts).'\';</script>';
        
        if (Tools::getValue('act') == 'gsp' && Tools::getValue('ajax')==1)
        {
            if(!$q = Tools::getValue('q'))
                die;
            $result = $this->getProducts($q, Tools::getValue('excludeIds'));
            foreach ($result AS $value)
		      echo trim($value['name']).'['.trim($value['reference']).']|'.(int)($value['id_product'])."\n";
            die;
        }
        
        if (Tools::getValue('act') == 'setstcountdown' && Tools::getValue('ajax')==1)
        {
            $ret = array('r'=>false,'msg'=>'');
            if(!$id_product = Tools::getValue('id_product'))
                $ret['msg'] = $this->l('Product ID error');
            else
            {
                if (StCountDownClass::setByProductId($id_product, (int)Tools::getValue('fl'), $this->context->shop->id))
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
		if (isset($_POST['savestcountdown']))
		{
            StCountDownClass::deleteByShop((int)$this->context->shop->id);
            $res = true;
            if($id_product= Tools::getValue('id_product'))
                foreach($id_product AS $value)
                {
                  $res &= Db::getInstance()->insert('st_countdown_product', array(
        					'id_product' => (int)$value,
        					'id_shop' => (int)$this->context->shop->id,
        				));  
                }
            if ($res)
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
                $this->savePages();
                if(count($this->validation_errors))
                    $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
                else 
                {
    		        $this->clearSliderCache();
                    $this->_html .= $this->displayConfirmation($this->l('Settings updated'));     
                }
            }
            else
                $this->_html .= $this->displayError($this->l('Cannot update settings'));  
        }
		$helper = $this->initForm();
		return $this->_html.$helper->generateForm($this->fields_form);
	}
    
    public function initFieldsForm()
    {
        $this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Countdwon settings'),
                'icon'  => 'icon-cogs'
			),
            'description' => $this->l('This module does not have a slider to display products on the front office.').'<br/>'.
                        $this->l('If you set a product with countdown timer, but the product does not show up on the homepage, then you can use the "Special products slider" module to force it to show up.').'<br/>'.
                        $this->l('Each products slider module has an option to do not show the countdown timer.'),
			'input' => array(
                array(
					'type' => 'switch',
					'label' => $this->l('Display countdown:'),
					'name' => 'active',
					'is_bool' => true,
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display on all special products:'),
                    'name' => 'disaply_all',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'disaply_all_1',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'disaply_all_0',
                            'value' => 0,
                            'label' => $this->l('No'))
                    ),
                    'validation' => 'isUnsignedInt',
                    'desc' => $this->l('If you turn this option on, each special product will has a countdown timer.'),
                ),
				'products' => array(
					'type' => 'text',
					'label' => $this->l('Specific products to have a countdown timer:'),
					'name' => 'products',
                    'autocomplete' => false,
                    'class' => 'fixed-width-xxl',
                    'desc' => '',
				),
                array(
					'type' => 'checkbox',
					'label' => $this->l('Display on'),
					'name' => 'display_on',
					'lang' => true,
					'values' => array(
						'query' => $this->_pages,
						'id' => 'id',
						'name' => 'name'
					)
				), 
                array(
                    'type' => 'radio',
                    'label' => $this->l('Style:'),
                    'name' => 'style',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'style_0',
                            'value' => 0,
                            'label' => $this->l('00 days 00 hours 00 minutes 00 seconds')),
                        array(
                            'id' => 'style_1',
                            'value' => 1,
                            'label' => $this->l('00 days 00:00:00')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),    
                 array(
                    'type' => 'switch',
                    'label' => $this->l('Display "Limited special offer" when a speical offer has NO end date:'),
                    'name' => 'title_aw_display',
                    'is_bool' => true,
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'title_aw_display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'title_aw_display_off',
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
        $this->fields_form[1]['form'] = array(
            'legend' => array(
                'title' => $this->l('Advanced Settings - Grid'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Height:'),
                    'name' => 'height',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'text',
                    'label' => $this->l('Top & Bottom padding:'),
                    'name' => 'padding',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'validation' => 'isUnsignedInt',
                    'desc' => $this->l('Increasing the vaule of this field can make the number and text get colser. The vaule of this field can not larger than half of the height.'),
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
                    'label' => $this->l('Background color:'),
                    'name' => 'bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Background color opacity:'),
                    'name' => 'bg_opacity',
                    'validation' => 'isFloat',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('From 0.0 (fully transparent) to 1.0 (fully opaque).'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Numbers font:'),
                    'name' => 'number_font_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="number_font_list_example" class="fontshow">01 23 45 67 89</p>',
                ),
                'number_font'=>array(
                    'type' => 'select',
                    'label' => $this->l('Numbers font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'number_font',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
                 array(
                    'type' => 'text',
                    'label' => $this->l('Numbers font size:'),
                    'name' => 'number_size',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Text font:'),
                    'name' => 'text_font_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="text_font_list_example" class="fontshow">Days hrs min sec</p>',
                ),
                'text_font'=>array(
                    'type' => 'select',
                    'label' => $this->l('Text font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'text_font',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
                 array(
                    'type' => 'text',
                    'label' => $this->l('Text font size:'),
                    'name' => 'font_size',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'validation' => 'isUnsignedInt',
                    'desc' => $this->l('Days hrs min sec.'),
                ),       
                array(
                    'type' => 'select',
                    'label' => $this->l('Text transform:'),
                    'name' => 'font_trans',
                    'options' => array(
                        'query' => self::$textTransform,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isUnsignedInt',
                ),         
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display dividers:'),
                    'name' => 'divider',
                    'is_bool' => true,
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'divider_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'divider_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Divider color:'),
                    'name' => 'divider_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Position:'),
                    'name' => 'v_alignment',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'v_alignment_0',
                            'value' => 0,
                            'label' => $this->l('At the bottom of the product image')),
                        array(
                            'id' => 'v_alignment_1',
                            'value' => 1,
                            'label' => $this->l('At the middle of the product image')),
                        array(
                            'id' => 'v_alignment_2',
                            'value' => 2,
                            'label' => $this->l('Under the price')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all  ')
            ),
        );

        $this->fields_form[2]['form'] = array(
            'legend' => array(
                'title' => $this->l('Advanced Settings - Product page'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'color',
                    'label' => $this->l('Text color:'),
                    'name' => 'pro_text_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Background color:'),
                    'name' => 'pro_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Text font:'),
                    'name' => 'pro_text_font_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="pro_text_font_list_example" class="fontshow">01 23 45 67 89</p>',
                ),
                'pro_text_font'=>array(
                    'type' => 'select',
                    'label' => $this->l('Text font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'pro_text_font',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Text font size:'),
                    'name' => 'pro_font_size',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'validation' => 'isUnsignedInt',
                    'desc' => $this->l('Days hrs min sec.'),
                ),           
                array(
                    'type' => 'select',
                    'label' => $this->l('Text transform:'),
                    'name' => 'pro_font_trans',
                    'options' => array(
                        'query' => self::$textTransform,
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
        
        $this->fields_form[3]['form'] = array(
            'legend' => array(
                'title' => $this->l('Display countdown timers on the native modules'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display on the "New products block" module:'),
                    'name' => 'blknew',
                    'is_bool' => true,
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'blknew_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'blknew_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display the on "Specials block" module:'),
                    'name' => 'blkspecial',
                    'is_bool' => true,
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'blkspecial_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'blkspecial_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display the on "Top-sellers block" module:'),
                    'name' => 'blkbest',
                    'is_bool' => true,
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'blkbest_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'blkbest_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display the on "Featured products on the homepage" module:'),
                    'name' => 'blkfeatured',
                    'is_bool' => true,
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'blkfeatured_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'blkfeatured_off',
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

	}
    protected function initForm()
	{
        foreach (array('number_font'=>1, 'text_font'=>1, 'pro_text_font'=>2) as $font=>$wf) {
            if ($font_menu_string = Configuration::get($this->_prefix_st.strtoupper($font))) {
                $font_menu = explode(":", $font_menu_string);
                $font_menu = $font_menu[0];
                $font_menu_key = str_replace(' ', '_', $font_menu);
            }
            else
            {
                $font_menu_key = $font_menu = $this->_font_inherit;
            }  
            if(array_key_exists($font_menu_key, $this->googleFonts))
            {
                foreach ($this->googleFonts[$font_menu_key]['variants'] as $g) {
                    $this->fields_form[$wf]['form']['input'][$font]['options']['query'][] = array(
                            'id'=> $font_menu.':'.($g=='regular' ? '400' : $g),
                            'name'=> $g,
                        );
                }
            }
            else
            {
                $this->fields_form[$wf]['form']['input'][$font]['options']['query'] = array(
                    array('id'=> $font_menu,'name'=>'Normal'),
                    array('id'=> $font_menu.':700','name'=>'Bold'),
                    array('id'=> $font_menu.':italic','name'=>'Italic'),
                    array('id'=> $font_menu.':700italic','name'=>'Bold & Italic'),
                );
            } 
        }

	    $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestcountdown';
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
        if(!$countdown_active = Configuration::get($this->_prefix_st.'ACTIVE'))
            return ;
        
        $this->context->controller->addJS(($this->_path).'views/js/countdown.min.js');

        $theme_font = array();
        $theme_font[] = Configuration::get($this->_prefix_st.'NUMBER_FONT');
        $theme_font[] = Configuration::get($this->_prefix_st.'TEXT_FONT');
        $theme_font[] = Configuration::get($this->_prefix_st.'PRO_TEXT_FONT');
        
        $theme_font = array_unique($theme_font);
        $fonts = $this->systemFonts;
        $theme_font = array_diff($theme_font,$fonts);
        
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
        if(is_array($theme_font) && count($theme_font))
            foreach($theme_font as $v)
            {
                $arr = explode(':', $v);
                if(!isset($arr[0]) || !$arr[0] || $arr[0] == $this->_font_inherit || in_array($arr[0], $this->systemFonts))
                    continue;
                $this->context->controller->addCSS($this->context->link->protocol_content."fonts.googleapis.com/css?family=".str_replace(' ', '+', $v).($font_support ? rtrim($font_support,',') : ''), 'all');
            }
        
        $controller = Dispatcher::getInstance()->getController();
        $cache_id   = $this->getCacheId().'_'.$controller;
        if (!$this->isCached('header.tpl', $cache_id))
        {
            if ($countdown_active && $controller && Configuration::get($this->_prefix_st.strtoupper('display_on_'.$controller)))
            {
                $id_products = '';
                $display_all = (int)Configuration::get($this->_prefix_st.'DISAPLY_ALL');
                
                if (!$display_all)
                    foreach(StCountDownClass::getByShop((int)$this->context->shop->id) AS $value)
                        $id_products .= $value['id_product'].',';                
                $this->smarty->assign(array(
                        'countdown_active'         => $countdown_active,
                        'display_all'   => $display_all,
                        'id_products'   => trim($id_products, ',')
                        ));
            }
            $custom_css = '';
            if ($height = Configuration::get($this->_prefix_st.'HEIGHT'))
            {
                $custom_css .= '.countdown_wrap.v_middle{margin-bottom:-'.(floor($height/2)).'px;}';
                $custom_css .= 'body.mobile_device .countdown_wrap{margin-bottom:-'.(floor($height/2)).'px;}';
                $padding = (int)Configuration::get($this->_prefix_st.'PADDING');
                $height_span = floor(($height-2*$padding)/2);
                $custom_css .= '.countdown_timer.countdown_style_0 div{padding-top:'.$padding.'px;padding-bottom:'.$padding.'px;}.countdown_timer.countdown_style_0 div span{height:'.$height_span.'px;line-height:'.$height_span.'px;}.countdown_timer.countdown_style_1 div, .countdown_wrap .countdown_title{height:'.$height.'px;line-height:'.$height.'px;}';
            }
            elseif($padding = (int)Configuration::get($this->_prefix_st.'PADDING'))
            {
                $height_span = floor((35-2*$padding)/2);
                $custom_css .= '.countdown_timer.countdown_style_0 div{padding-top:'.$padding.'px;padding-bottom:'.$padding.'px;}.countdown_timer.countdown_style_0 div span{height:'.$height_span.'px;line-height:'.$height_span.'px;}';
            }

            if ($text_color = Configuration::get($this->_prefix_st.'TEXT_COLOR'))
                $custom_css .= '.countdown_wrap, .pro_second_box .countdown_box, .pro_column_right .countdown_box{color:'.$text_color.';}';

            if($bg = Configuration::get($this->_prefix_st.'BG'))
            {
                $custom_css .= '.countdown_wrap, .pro_second_box .countdown_box, .pro_column_right .countdown_box{background:'.$bg.';}';  
                $bg_arr = self::hex2rgb($bg);
                if(is_array($bg_arr))
                {
                    $bg_opacity = (float)Configuration::get($this->_prefix_st.'BG_OPACITY');
                    if($bg_opacity<0 || $bg_opacity>1)
                        $bg_opacity = 0.8;
                    $custom_css .= '.countdown_wrap, .pro_second_box .countdown_box{background:rgba('.$bg_arr[0].','.$bg_arr[1].','.$bg_arr[2].','.$bg_opacity.');}';  
                }
            }

            if ($number_size = Configuration::get($this->_prefix_st.'NUMBER_SIZE'))
                $custom_css .= '.countdown_timer.countdown_style_0 div span.countdown_number, .countdown_timer.countdown_style_1 div, .pro_second_box .countdown_box{font-size:'.$number_size.'px;}';
            if ($font_size = Configuration::get($this->_prefix_st.'FONT_SIZE'))
                $custom_css .= '.countdown_timer.countdown_style_0 div span.countdown_text, .countdown_wrap .countdown_title{font-size:'.$font_size.'px;}';
            if ($font_trans = Configuration::get($this->_prefix_st.'FONT_TRANS'))
                $custom_css .= '.countdown_wrap, .pro_second_box .countdown_box{text-transform:'.self::$textTransform[(int)$font_trans]['name'].';}';
            if (!Configuration::get($this->_prefix_st.'DIVIDER'))
                $custom_css .= '.countdown_timer.countdown_style_0 div{border-right:none;}';
            if ($divider_color = Configuration::get($this->_prefix_st.'DIVIDER_COLOR'))
                $custom_css .= '.countdown_timer.countdown_style_0 div{border-right-color:'.$divider_color.';}';
            //
            if ($pro_text_color = Configuration::get($this->_prefix_st.'PRO_TEXT_COLOR'))
                $custom_css .= '.box-info-product .countdown_box{color:'.$pro_text_color.';}';
            if ($pro_bg = Configuration::get($this->_prefix_st.'PRO_BG'))
                $custom_css .= '.box-info-product .countdown_box{background:'.$pro_bg.';}';
            if ($pro_font_size = Configuration::get($this->_prefix_st.'PRO_FONT_SIZE'))
                $custom_css .= '.box-info-product .countdown_box{font-size:'.$pro_font_size.'px;}';
            if ($pro_font_trans = Configuration::get($this->_prefix_st.'PRO_FONT_TRANS'))
                $custom_css .= '.box-info-product .countdown_box{text-transform:'.self::$textTransform[(int)$pro_font_trans]['name'].';}';


            $fontNumber = $fontText = $fontProText = '';
            $fontNumberWeight = $fontTextWeight = $fontProTextWeight = '';
            $fontNumberStyle = $fontTextStyle = $fontProTextStyle = '';

            if($fontNumberString = Configuration::get($this->_prefix_st.'NUMBER_FONT'))
            {
                preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontNumberString, $fontNumberArr);
                $fontNumber = $fontNumberArr[1][0];
                $fontNumberArr[2] && $fontNumberWeight = 'font-weight:'.$fontNumberArr[2][0].';';
                $fontNumberArr[3] && $fontNumberStyle = 'font-style:'.$fontNumberArr[3][0].';';
            }

            if($fontTextString = Configuration::get($this->_prefix_st.'TEXT_FONT'))
            {
                preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontTextString, $fontTextArr);
                $fontText = $fontTextArr[1][0];
                $fontTextArr[2] && $fontTextWeight = 'font-weight:'.$fontTextArr[2][0].';';
                $fontTextArr[3] && $fontTextStyle = 'font-style:'.$fontTextArr[3][0].';';
            }

            if($fontProTextString = Configuration::get($this->_prefix_st.'PRO_TEXT_FONT'))
            {
                preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontProTextString, $fontProTextArr);
                $fontProText = $fontProTextArr[1][0];
                $fontProTextArr[2] && $fontProTextWeight = 'font-weight:'.$fontProTextArr[2][0].';';
                $fontProTextArr[3] && $fontProTextStyle = 'font-style:'.$fontProTextArr[3][0].';';
            }
            if($fontNumber)
                $custom_css .= '.countdown_timer.countdown_style_0 div span.countdown_number, .countdown_timer.countdown_style_1 div, .pro_second_box .countdown_box{'.($fontNumber != $this->_font_inherit ? 'font-family: "'.$fontNumber.'";' : '').$fontTextWeight.$fontTextStyle.'}';
            if($fontText)
                $custom_css .= '.countdown_timer.countdown_style_0 div span.countdown_text, .countdown_wrap .countdown_title{'.($fontText != $this->_font_inherit ? 'font-family: "'.$fontNumber.'";' : '').$fontTextWeight.$fontTextStyle.'}';
            if($fontProText)
                $custom_css .= '.box-info-product .countdown_box span{'.($fontProText != $this->_font_inherit ? 'font-family: "'.$fontNumber.'";' : '').$fontProTextWeight.$fontProTextStyle.'}';


            if($custom_css)
                $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
        }
        return $this->display(__FILE__, 'header.tpl', $cache_id);
    }
    
    public function hookDisplayAdminProductPriceFormFooter($params)
    {
        $this->smarty->assign(array(
            'id_product' => Tools::getValue('id_product'),
            'currentIndex' => $this->context->link->getAdminLink('AdminModules', true).'&configure='.$this->name,
            'checked' => StCountDownClass::exists(Tools::getValue('id_product') ,$this->context->shop->id)
            ));
        return $this->display(__FILE__, 'views/templates/admin/stcountdown.tpl');
    }
    
    private function getProducts($q = '',$excludeIds = false)
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
            'active'             => Configuration::get($this->_prefix_st.'ACTIVE'),
            'products'           => '',
            'disaply_all'        => Configuration::get($this->_prefix_st.'DISAPLY_ALL'),
            'style'              => Configuration::get($this->_prefix_st.'STYLE'),
            'height'             => Configuration::get($this->_prefix_st.'HEIGHT'),
            'padding'            => Configuration::get($this->_prefix_st.'PADDING'),
            'text_color'         => Configuration::get($this->_prefix_st.'TEXT_COLOR'),
            'bg'                 => Configuration::get($this->_prefix_st.'BG'),
            'bg_opacity'         => Configuration::get($this->_prefix_st.'BG_OPACITY'),
            'number_size'        => Configuration::get($this->_prefix_st.'NUMBER_SIZE'),
            'font_size'          => Configuration::get($this->_prefix_st.'FONT_SIZE'),
            'font_trans'         => Configuration::get($this->_prefix_st.'FONT_TRANS'),
            'divider'            => Configuration::get($this->_prefix_st.'DIVIDER'),
            'divider_color'      => Configuration::get($this->_prefix_st.'DIVIDER_COLOR'),
            'v_alignment'        => Configuration::get($this->_prefix_st.'V_ALIGNMENT'),
            'title_aw_display'   => Configuration::get($this->_prefix_st.'TITLE_AW_DISPLAY'),
            'pro_text_color'     => Configuration::get($this->_prefix_st.'PRO_TEXT_COLOR'),
            'pro_bg'             => Configuration::get($this->_prefix_st.'PRO_BG'),
            'pro_font_size'      => Configuration::get($this->_prefix_st.'PRO_FONT_SIZE'),
            'pro_font_trans'     => Configuration::get($this->_prefix_st.'PRO_FONT_TRANS'),
            'number_font'        => Configuration::get($this->_prefix_st.'NUMBER_FONT'),
            'text_font'          => Configuration::get($this->_prefix_st.'TEXT_FONT'),
            'pro_text_font'      => Configuration::get($this->_prefix_st.'PRO_TEXT_FONT'),
            
            'blknew'             => Configuration::get($this->_prefix_st.'BLKNEW'),
            'blkspecial'         => Configuration::get($this->_prefix_st.'BLKSPECIAL'),
            'blkbest'            => Configuration::get($this->_prefix_st.'BLKBEST'),
            'blkfeatured'        => Configuration::get($this->_prefix_st.'BLKFEATURED'),
        );
        foreach($this->_pages AS $value)
            if(Configuration::get($this->_prefix_st.strtoupper('display_on_'.$value['id'])))
                $fields_values['display_on_'.$value['id']] = 1;
                
        $products_html = '';
        foreach(StCountDownClass::getByShop((int)$this->context->shop->id) AS $value)
        {
            $product = new Product($value['id_product'], false, Context::getContext()->language->id);
            $products_html .= '<li>'.$product->name.'['.$product->reference.']
            <a href="javascript:;" class="del_product"><img src="../img/admin/delete.gif" /></a>
            <input type="hidden" name="id_product[]" value="'.$value['id_product'].'" /></li>';
        }
        
        $this->fields_form[0]['form']['input']['products']['desc'] = $this->l('Actually only for "display on all special products" is set to "No".').'<br/>'.$this->l('Current products')
                .': <ul id="curr_products">'.$products_html.'</ul>';

        $number_font_string = Configuration::get($this->_prefix_st.'NUMBER_FONT');
        $number_font_string && $number_font_string = explode(":", $number_font_string);
        $fields_values['number_font_list'] = $number_font_string ? $number_font_string[0] : '';

        $text_font_string = Configuration::get($this->_prefix_st.'TEXT_FONT');
        $text_font_string && $text_font_string = explode(":", $text_font_string);
        $fields_values['text_font_list'] = $text_font_string ? $text_font_string[0] : '';

        $pro_text_font_string = Configuration::get($this->_prefix_st.'PRO_TEXT_FONT');
        $pro_text_font_string && $pro_text_font_string = explode(":", $pro_text_font_string);
        $fields_values['pro_text_font_list'] = $pro_text_font_string ? $pro_text_font_string[0] : '';

        return $fields_values;
    }
    
    public function getHookHash($func='')
    {
        if (!$func)
            return '';
        return substr(md5($func), 0, 10);
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
       return $rgb;
    }
    public function fontOptions() {
        $system = $google = array();
        foreach($this->systemFonts as $v)
            $system[] = array('id'=>$v,'name'=>$v);
        foreach($this->googleFonts as $v)
            $google[] = array('id'=>$v['family'],'name'=>$v['family']);
        $module = new StCountDown();
        return array(
            array('name'=>$module->l('System Web fonts'),'query'=>$system),
            array('name'=>$module->l('Google Web Fonts'),'query'=>$google),
        );
    }
    public function get_prefix()
    {
        if (isset($this->_prefix_st) && $this->_prefix_st)
            return $this->_prefix_st;
        return false;
    }
}