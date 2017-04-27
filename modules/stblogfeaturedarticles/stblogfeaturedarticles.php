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

class StBlogFeaturedArticles extends Module
{
    private $_html = '';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();
    private $_field_prefix = 'ST_B_FEATURED_A_';
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
        5 => array('id' =>5 , 'name' => 'Blog ID: Desc'),
        6 => array('id' =>6 , 'name' => 'Blog ID: Asc'),
        7 => array('id' =>7 , 'name' => 'Position: Desc'),
        8 => array('id' =>8 , 'name' => 'Position: Asc'),
    );
    private $_hooks = array();
	public function __construct()
	{
		$this->name          = 'stblogfeaturedarticles';
		$this->tab           = 'front_office_features';
		$this->version       = '1.2.9';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
		$this->bootstrap 	 = true;
		parent::__construct();
        
        $this->initHookArray();
		
        $this->displayName = $this->l('Blog Module - Featured articles');
        $this->description = $this->l('Display featured articles on your store.');
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
        			'id' => 'displayTopColumn',
        			'val' => '1',
        			'name' => $this->l('displayTopColumn')
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
        		),
                array(
        			'id' => 'displayStBlogHome',
        			'val' => '1',
        			'name' => $this->l('displayStBlogHome')
        		),
                array(
        			'id' => 'displayStBlogHomeTop',
        			'val' => '1',
        			'name' => $this->l('displayStBlogHomeTop')
        		),
                array(
        			'id' => 'displayStBlogHomeBottom',
        			'val' => '1',
        			'name' => $this->l('displayStBlogHomeBottom')
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

	public function install()
	{
		if (!parent::install() 
			|| !$this->registerHook('displayStBlogLeftColumn')
			|| !$this->registerHook('displayStBlogRightColumn')
			|| !$this->registerHook('displayStBlogHome')
			|| !$this->registerHook('displayHomeBottom')
			|| !$this->registerHook('displayHeader')
            
            || !Configuration::updateValue($this->_field_prefix.'NBR', 6)
            || !Configuration::updateValue($this->_field_prefix.'CAT_MOD', 1)
            || !Configuration::updateValue($this->_field_prefix.'SOBY', 1)
            || !Configuration::updateValue($this->_field_prefix.'GRID', 0)
            || !Configuration::updateValue($this->_field_prefix.'HIDE_MOB', 0)
            || !Configuration::updateValue($this->_field_prefix.'AW_DISPLAY', 1)
            
            || !Configuration::updateValue($this->_field_prefix.'NBR_H', 6)
            || !Configuration::updateValue($this->_field_prefix.'CAT_MOD_H', 1)
            || !Configuration::updateValue($this->_field_prefix.'SOBY_H', 1)
            || !Configuration::updateValue($this->_field_prefix.'GRID_H', 0)
            || !Configuration::updateValue($this->_field_prefix.'HIDE_MOB_H', 0)
            || !Configuration::updateValue($this->_field_prefix.'AW_DISPLAY_H', 1)
            
            
            || !Configuration::updateValue($this->_field_prefix.'EASING', 0)
            || !Configuration::updateValue($this->_field_prefix.'SLIDESHOW', 0)
            || !Configuration::updateValue($this->_field_prefix.'S_SPEED', 7000)
            || !Configuration::updateValue($this->_field_prefix.'A_SPEED', 400)
            || !Configuration::updateValue($this->_field_prefix.'PAUSE_ON_HOVER', 1)
            || !Configuration::updateValue($this->_field_prefix.'LOOP', 0)
            || !Configuration::updateValue($this->_field_prefix.'MOVE', 0) 
            
            
            || !Configuration::updateValue($this->_field_prefix.'NBR_COL', 4)
            || !Configuration::updateValue($this->_field_prefix.'NBR_FOOTER', 3)
            
            || !Configuration::updateValue('STSN_BHOME_FB_PRO_PER_LG_0', 2)
            || !Configuration::updateValue('STSN_BHOME_FB_PRO_PER_MD_0', 1)
            || !Configuration::updateValue('STSN_BHOME_FB_PRO_PER_SM_0', 1)
            || !Configuration::updateValue('STSN_BHOME_FB_PRO_PER_XS_0', 1)
            || !Configuration::updateValue('STSN_BHOME_FB_PRO_PER_XXS_0', 1)
            || !Configuration::updateValue('STSN_HOME_FB_PRO_PER_LG_0', 4)
            || !Configuration::updateValue('STSN_HOME_FB_PRO_PER_MD_0', 4)
            || !Configuration::updateValue('STSN_HOME_FB_PRO_PER_SM_0', 3)
            || !Configuration::updateValue('STSN_HOME_FB_PRO_PER_XS_0', 2)
            || !Configuration::updateValue('STSN_HOME_FB_PRO_PER_XXS_0', 1)

            //
            || !Configuration::updateValue($this->_field_prefix.'TOP_PADDING', '')
            || !Configuration::updateValue($this->_field_prefix.'BOTTOM_PADDING', '')
            || !Configuration::updateValue($this->_field_prefix.'TOP_MARGIN', '')
            || !Configuration::updateValue($this->_field_prefix.'BOTTOM_MARGIN', '')
            || !Configuration::updateValue($this->_field_prefix.'BG_PATTERN', 0)
            || !Configuration::updateValue($this->_field_prefix.'BG_IMG', '')
            || !Configuration::updateValue($this->_field_prefix.'BG_COLOR', '')
            || !Configuration::updateValue($this->_field_prefix.'SPEED', 0)
            || !Configuration::updateValue($this->_field_prefix.'TITLE_COLOR', '')
            || !Configuration::updateValue($this->_field_prefix.'TEXT_COLOR', '')
            || !Configuration::updateValue($this->_field_prefix.'LINK_HOVER_COLOR', '')
            || !Configuration::updateValue($this->_field_prefix.'DIRECTION_COLOR', '')
            || !Configuration::updateValue($this->_field_prefix.'DIRECTION_BG', '')
            || !Configuration::updateValue($this->_field_prefix.'DIRECTION_HOVER_BG', '')
            || !Configuration::updateValue($this->_field_prefix.'DIRECTION_DISABLED_BG', '')

            || !Configuration::updateValue($this->_field_prefix.'TITLE_ALIGNMENT', 0)
            || !Configuration::updateValue($this->_field_prefix.'TITLE_NO_BG', 0)
            || !Configuration::updateValue($this->_field_prefix.'TITLE_FONT_SIZE', 0)
            || !Configuration::updateValue($this->_field_prefix.'DIRECTION_NAV', 0)
        )
			return false;
		return true;
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
	    if(!Module::isInstalled('stblog'))
            $this->_html .= $this->displayConfirmation($this->l('Please, install Blog module first.'));
	    if(!Module::isEnabled('stblog'))
            $this->_html .= $this->displayConfirmation($this->l('Please, enable Blog module first.'));
            
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
            if(Configuration::updateValue($this->_field_prefix.'BG_IMG', ''))
                $result['r'] = true;
            die(json_encode($result));
        }

	    $this->initFieldsForm();
		if (isset($_POST['savestblogfeaturedarticles']))
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
                        
                        if($field['name']=='limit' && $value>20)
                             $value=20;
                        
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
                            Configuration::updateValue($this->_field_prefix.strtoupper($field['name']), $value);
                        }
                        else
                            Configuration::updateValue($this->_field_prefix.strtoupper($field['name']), $value);
                    }
             
            $this->updateCatePerRow('bhome');
            $this->updateCatePerRow('home');
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
                            Configuration::updateValue($this->_field_prefix.'BG_IMG', $this->name.'/'.$bg_image);
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

        $this->fields_form[0]['form']['input']['bhome_b_pro_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer(), 'bhome');
        $this->fields_form[1]['form']['input']['home_b_pro_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer(), 'home');
        if ($bg_img = Configuration::get($this->_field_prefix.'BG_IMG'))
        {
            $this->fetchMediaServer($bg_img);
            $this->fields_form[1]['form']['input']['bg_img_field']['image'] = '<img width=200 src="'.($bg_img).'" /><p><a class="btn btn-default st_delete_image" href="javascript:;"><i class="icon-trash"></i> Delete</a></p>';
        }
		$helper = $this->initForm();
		return $this->_html.$helper->generateForm($this->fields_form);
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
    protected function initFieldsForm()
    {
        $this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Blog homepage'),
                'icon'  => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Blog homepage:'),
					'name' => 'nbr',
                    'default_value' => 6,
                    'required' => true,
                    'desc' => $this->l('Define the number of blogs that you would like to display on blog homepage.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
                    'type' => 'text',
                    'label' => $this->l('Category from which to pick blogs to be displayed'),
                    'name' => 'cat_mod',
                    'class' => 'fixed-width-xs',
                    'desc' => $this->l('Choose the category ID of the blogs that you would like to display on blog homepage (default: 1 for "Home").'),
                    'validation' => 'isUnsignedInt',
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
                    'label' => $this->l('How to display:'),
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
                            'label' => $this->l('Grid(Image on the left side)')),
                        array(
                            'id' => 'grid_top',
                            'value' => 3,
                            'label' => $this->l('Grid(Image on the top)')),
                        array(
                            'id' => 'grid_list',
                            'value' => 2,
                            'label' => $this->l('List view')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                'bhome_b_pro_per_0' => array(
                    'type' => 'html',
                    'id' => 'bhome_b_pro_per_0',
                    'label'=> $this->l('The number of columns'),
                    'name' => '',
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
			),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
		);
        $this->fields_form[1]['form'] = array(
			'legend' => array(
				'title' => $this->l('Homepage'),
                'icon'  => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Store homepage:'),
					'name' => 'nbr_h',
                    'default_value' => 6,
                    'required' => true,
                    'desc' => $this->l('Define the number of blogs that you would like to display on store homepage.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
                    'type' => 'text',
                    'label' => $this->l('Category from which to pick blogs to be displayed'),
                    'name' => 'cat_mod_h',
                    'class' => 'fixed-width-xs',
                    'desc' => $this->l('Choose the category ID of the blogs that you would like to display on homepage (default: 1 for "Home").'),
                    'validation' => 'isUnsignedInt',
                ),
                array(
					'type' => 'select',
        			'label' => $this->l('Sort by:'),
        			'name' => 'soby_h',
                    'options' => array(
        				'query' => self::$sort_by,
        				'id' => 'id',
        				'name' => 'name',
        			),
                    'validation' => 'isUnsignedInt',
				), 
                array(
                    'type' => 'radio',
                    'label' => $this->l('How to display:'),
                    'name' => 'grid_h',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'grid_h_slider',
                            'value' => 0,
                            'label' => $this->l('Slider')),
                        array(
                            'id' => 'grid_h_grid',
                            'value' => 1,
                            'label' => $this->l('Grid view')),
                        array(
                            'id' => 'grid_h_list',
                            'value' => 2,
                            'label' => $this->l('List view')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                'home_b_pro_per_0' => array(
                    'type' => 'html',
                    'id' => 'home_b_pro_per_0',
                    'label'=> $this->l('The number of columns'),
                    'name' => '',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide slideshow on mobile devices:'),
                    'name' => 'hide_mob_h',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'hide_mob_h_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'hide_mob_h_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'desc' => $this->l('if set to Yes, slider will be hidden on mobile devices (if screen width is less than 768 pixels).'),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Always display this block:'),
                    'name' => 'aw_display_h',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'aw_display_h_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'aw_display_h_off',
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
                    'label' => $this->l('Link hover color:'),
                    'name' => 'link_hover_color',
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
				'title' => $this->l('   Save all   '),
			),
		);
        
        $this->fields_form[2]['form'] = array(
			'legend' => array(
				'title' => $this->l('Slider settings'),
                'icon'  => 'icon-cogs'
			),
			'input' => array(
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
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
		);
        
        $this->fields_form[3]['form'] = array(
			'legend' => array(
				'title' => $this->l('Others'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Left/right column:'),
					'name' => 'nbr_col',
					'desc' => $this->l('Define the number of featured articles to be displayed in left/right column.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Footer:'),
					'name' => 'nbr_footer',
					'desc' => $this->l('Define the number of featured articles to be displayed in footer.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
			),
			'submit' => array(
				'title' => $this->l('   Save all  ')
			),
		);
        
        $this->fields_form[4]['form'] = array(
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
            $this->fields_form[4]['form']['input'][] = array(
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
		$helper->submit_action = 'savestblogfeaturedarticles';
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
        if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;

        if (!$this->isCached('header.tpl', $this->getCacheId()))
        {
            $custom_css = '';
            $title_block_no_bg = 'body#index .st_blog_featured_article_container .title_block, body#index .st_blog_featured_article_container .nav_top_right .flex-direction-nav,body#index .st_blog_featured_article_container .title_block a, body#index .st_blog_featured_article_container .title_block span{background:none;}';
            
            $group_css = '';
            if ($bg_color = Configuration::get($this->_field_prefix.'BG_COLOR'))
                $group_css .= 'background-color:'.$bg_color.';';
            if ($bg_img = Configuration::get($this->_field_prefix.'BG_IMG'))
            {
                $this->fetchMediaServer($bg_img);
                $group_css .= 'background-image: url('.$bg_img.');';
            }
            elseif ($bg_pattern = Configuration::get($this->_field_prefix.'BG_PATTERN'))
            {
                $img = _MODULE_DIR_.'stthemeeditor/patterns/'.$bg_pattern.'.png';
                $img = $this->context->link->protocol_content.Tools::getMediaServer($img).$img;
                $group_css .= 'background-image: url('.$img.');';
            }
            if($group_css)
                $custom_css .= 'body#index .st_blog_featured_article_container{background-attachment:fixed;'.$group_css.'}'.$title_block_no_bg;

            if ($top_padding = (int)Configuration::get($this->_field_prefix.'TOP_PADDING'))
                $custom_css .= 'body#index .st_blog_featured_article_container{padding-top:'.$top_padding.'px;}';
            if ($bottom_padding = (int)Configuration::get($this->_field_prefix.'BOTTOM_PADDING'))
                $custom_css .= 'body#index .st_blog_featured_article_container{padding-bottom:'.$bottom_padding.'px;}';

            $top_margin = Configuration::get($this->_field_prefix.'TOP_MARGIN');
            if($top_margin || $top_margin!==null)
                $custom_css .= 'body#index .st_blog_featured_article_container{margin-top:'.$top_margin.'px;}';
            $bottom_margin = Configuration::get($this->_field_prefix.'BOTTOM_MARGIN');
            if($bottom_margin || $bottom_margin!==null)
                $custom_css .= 'body#index .st_blog_featured_article_container{margin-bottom:'.$bottom_margin.'px;}';

            if (Configuration::get($this->_field_prefix.'TITLE_ALIGNMENT'))
                $custom_css .= 'body#index .st_blog_featured_article_container .title_block{text-align:center;}'.$title_block_no_bg;
            if (Configuration::get($this->_field_prefix.'TITLE_NO_BG'))
                $custom_css .= $title_block_no_bg;
            if ($title_font_size = (int)Configuration::get($this->_field_prefix.'TITLE_FONT_SIZE'))
            {
                 $custom_css .= 'body#index .st_blog_featured_article_container .title_block{font-size:'.$title_font_size.'px;}';
                 $custom_css .= 'body#index .st_blog_featured_article_container .nav_top_right .flex-direction-nav{top:-'.(round((round($title_font_size*1.3)-24)/2)+24+22).'px;}';
            }

            if ($title_color = Configuration::get($this->_field_prefix.'TITLE_COLOR'))
                $custom_css .= 'body#index .st_blog_featured_article_container.block .title_block a, body#index .st_blog_featured_article_container.block .title_block span{color:'.$title_color.';}';
            
            if ($text_color = Configuration::get($this->_field_prefix.'TEXT_COLOR'))
                $custom_css .= 'body#index .st_blog_featured_article_container .s_title_block a,
                body#index .st_blog_featured_article_container .blog_info,
                body#index .st_blog_featured_article_container .blok_blog_short_content a.go,
                body#index .st_blog_featured_article_container .blok_blog_short_content{color:'.$text_color.';}';

            if ($link_hover_color = Configuration::get($this->_field_prefix.'LINK_HOVER_COLOR'))
                $custom_css .= 'body#index .st_blog_featured_article_container .s_title_block a:hover,
                body#index .st_blog_featured_article_container .blok_blog_short_content a.go:hover{color:'.$link_hover_color.';}';

            if ($direction_color = Configuration::get($this->_field_prefix.'DIRECTION_COLOR'))
                $custom_css .= 'body#index .st_blog_featured_article_container .flex-direction-nav a, body#index .st_blog_featured_article_container .nav_left_right .flex-direction-nav a{color:'.$direction_color.';}';
            if ($direction_bg = Configuration::get($this->_field_prefix.'DIRECTION_BG'))
                $custom_css .= 'body#index .st_blog_featured_article_container .flex-direction-nav a, body#index .st_blog_featured_article_container .nav_left_right .flex-direction-nav a{background-color:'.$direction_bg.';}';
            if ($direction_hover_bg = Configuration::get($this->_field_prefix.'DIRECTION_HOVER_BG'))
                $custom_css .= 'body#index .st_blog_featured_article_container .flex-direction-nav a:hover, body#index .st_blog_featured_article_container .nav_left_right .flex-direction-nav a:hover{background-color:'.$direction_hover_bg.';}';
            if ($direction_disabled_bg = Configuration::get($this->_field_prefix.'DIRECTION_DISABLED_BG'))
                $custom_css .= 'body#index .st_blog_featured_article_container .flex-direction-nav a.flex-disabled, body#index .st_blog_featured_article_container .nav_left_right .flex-direction-nav a.flex-disabled{background-color:'.$direction_disabled_bg.';}';

            if($custom_css)
                $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
        }
        return $this->display(__FILE__, 'header.tpl', $this->getCacheId());
    }
    private function clearSliderCache()
	{
		$this->_clearCache('*');
    }
    protected function stGetCacheId($key,$name = null)
	{
		$cache_id = parent::getCacheId($name);
		return $cache_id.'_'.$key;
	}
    private function _prepareHook($ext='')
    {
        include_once(_PS_MODULE_DIR_.'stblog/classes/StBlogClass.php');
        include_once(_PS_MODULE_DIR_.'stblog/classes/StBlogCategory.php');
        include_once(_PS_MODULE_DIR_.'stblog/classes/StBlogImageClass.php');
        
        $ext = $ext ? '_'.strtoupper($ext) : '';
        $nbr = Configuration::get($this->_field_prefix.'NBR'.$ext);
        
        if(!$nbr)
            $nbr = 4;
            
        $order_by = 'position';
        $order_way = 'DESC';
        $soby = (int)Configuration::get($this->_field_prefix.'SOBY'.$ext);
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
                $order_by = 'id_st_blog';
                $order_way = 'DESC';
            break;
            case 6:
                $order_by = 'id_st_blog';
                $order_way = 'ASC';
            break;
            case 7:
                $order_by = 'position';
                $order_way = 'DESC';
            break;
            case 8:
                $order_by = 'position';
                $order_way = 'ASC';
            break;
        }
        
        $featured_category_id = (int)Configuration::get($this->_field_prefix.'CAT_MOD');
        if ($ext)
        {
            $featured_category_id2 = (int)Configuration::get($this->_field_prefix.'CAT_MOD'.$ext);
            $featured_category_id2 && $featured_category_id = $featured_category_id2;
        }
        
        if (!$featured_category_id)
        {
            $root_category = StBlogCategory::getShopCategoryRoot((int)$this->context->language->id);
            if(!is_array($root_category) || !isset($root_category['id_st_blog_category']))
                return false;
            $featured_category_id =  $root_category['id_st_blog_category'];
        }
        
        $category = new StBlogCategory($featured_category_id, (int)$this->context->language->id);
		$blogs = $category->getBlogs((int)$this->context->language->id, 1, $nbr, $order_by, $order_way);
        /*
        if(!$blogs)
            return false;
        */    
       
        $easing = Configuration::get($this->_field_prefix.'EASING');

		$this->smarty->assign(array(
            'blogs' => $blogs,
            'imageSize' => StBlogImageClass::$imageTypeDef,
            'featured_a_per_nbr_home' => Configuration::get('ST_B_FEATURED_A_PER_NBR_HOME'),
            'display_viewcount' => Configuration::get('ST_BLOG_DISPLAY_VIEWCOUNT'),

            'slider_easing'         => self::$easing[$easing]['name'],
            'slider_slideshow'      => Configuration::get($this->_field_prefix.'SLIDESHOW'),
            'slider_s_speed'        => Configuration::get($this->_field_prefix.'S_SPEED'),
            'slider_a_speed'        => Configuration::get($this->_field_prefix.'A_SPEED'),
            'slider_pause_on_hover' => Configuration::get($this->_field_prefix.'PAUSE_ON_HOVER'),
            'slider_loop'           => Configuration::get($this->_field_prefix.'LOOP'),
            'slider_move'           => Configuration::get($this->_field_prefix.'MOVE'),

            'hide_mob'              => (int)Configuration::get($this->_field_prefix.'HIDE_MOB'.$ext),
            'aw_display'            => (int)Configuration::get($this->_field_prefix.'AW_DISPLAY'.$ext),
            'display_as_grid'       => Configuration::get($this->_field_prefix.'GRID'.$ext),
            'direction_nav'         => Configuration::get($this->_field_prefix.'DIRECTION_NAV')
        ));
        return true;
    }
    
	public function hookDisplayLeftColumn($params, $hook_hash='')
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
        
        if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
        if (!$this->isCached('stblogfeaturedarticles-column.tpl', $this->stGetCacheId($hook_hash)))
	    {        
            if(!$this->_prepareHook('col'))    
                return false;
             
            $this->smarty->assign(array(
                    'hook_hash' => $hook_hash,
                ));
        }    
		return $this->display(__FILE__, 'stblogfeaturedarticles-column.tpl', $this->stGetCacheId($hook_hash));
	}
	public function hookDisplayRightColumn($params)
	{
        return $this->hookDisplayLeftColumn($params, $this->getHookHash(__FUNCTION__)); 
	}
    public function hookDisplayProductSecondaryColumn($params)
	{
        return $this->hookDisplayLeftColumn($params, $this->getHookHash(__FUNCTION__));
	}
    public function hookDisplayHomeSecondaryRight($params)
    {
        return $this->hookDisplayLeftColumn($params, $this->getHookHash(__FUNCTION__));
    }
	public function hookDisplayStBlogRightColumn($params)
	{
        return $this->hookDisplayLeftColumn($params, $this->getHookHash(__FUNCTION__)); 
	}
	public function hookDisplayStBlogLeftColumn($params)
	{
        return $this->hookDisplayLeftColumn($params,$this->getHookHash(__FUNCTION__)); 
	}
    public function hookDisplayStBlogHome($params, $hook_hash='')
    {
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
        
        if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
        if (!$this->isCached('stblogfeaturedarticles-home.tpl', $this->stGetCacheId($hook_hash)))
	    {    
            if(!$this->_prepareHook())    
                return false; 
            
            $this->smarty->assign(array(
                'hook_hash'             => $hook_hash,
                'isbloghomepage'        => true,
    
                'pro_per_lg'            => Configuration::get('STSN_BHOME_FB_PRO_PER_LG_0') ? Configuration::get('STSN_BHOME_FB_PRO_PER_LG_0') : 4,
                'pro_per_md'            => Configuration::get('STSN_BHOME_FB_PRO_PER_MD_0') ? Configuration::get('STSN_BHOME_FB_PRO_PER_MD_0') : 4,
                'pro_per_sm'            => Configuration::get('STSN_BHOME_FB_PRO_PER_SM_0') ? Configuration::get('STSN_BHOME_FB_PRO_PER_SM_0') : 3,
                'pro_per_xs'            => Configuration::get('STSN_BHOME_FB_PRO_PER_XS_0') ? Configuration::get('STSN_BHOME_FB_PRO_PER_XS_0') : 2,
                'pro_per_xxs'           => Configuration::get('STSN_BHOME_FB_PRO_PER_XXS_0') ? Configuration::get('STSN_BHOME_FB_PRO_PER_XXS_0') : 1,
            ));
        }
        return $this->display(__FILE__, 'stblogfeaturedarticles-home.tpl', $this->stGetCacheId($hook_hash)); 
    }
    public function hookDisplayStBlogHomeTop($params)
    {
        return $this->hookDisplayStBlogHome($params, $this->getHookHash(__FUNCTION__)); 
    }
    public function hookDisplayStBlogHomeBottom($params)
    {
        return $this->hookDisplayStBlogHome($params, $this->getHookHash(__FUNCTION__)); 
    }
    public function hookDisplayHome($params, $hook_hash = '', $flag=0)
    {
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
            
        if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
        if (!$this->isCached('stblogfeaturedarticles-home.tpl', $this->stGetCacheId($hook_hash)))
	    {     
            if(!$this->_prepareHook('h'))    
                return false; 
    
            $this->smarty->assign(array(
                'hook_hash'             => $hook_hash,
                'isHomeVeryBottom'        => ($flag==2 ? true : false),
    
                'pro_per_lg'            => Configuration::get('STSN_HOME_FB_PRO_PER_LG_0') ? Configuration::get('STSN_HOME_FB_PRO_PER_LG_0') : 4,
                'pro_per_md'            => Configuration::get('STSN_HOME_FB_PRO_PER_MD_0') ? Configuration::get('STSN_HOME_FB_PRO_PER_MD_0') : 4,
                'pro_per_sm'            => Configuration::get('STSN_HOME_FB_PRO_PER_SM_0') ? Configuration::get('STSN_HOME_FB_PRO_PER_SM_0') : 3,
                'pro_per_xs'            => Configuration::get('STSN_HOME_FB_PRO_PER_XS_0') ? Configuration::get('STSN_HOME_FB_PRO_PER_XS_0') : 2,
                'pro_per_xxs'           => Configuration::get('STSN_HOME_FB_PRO_PER_XXS_0') ? Configuration::get('STSN_HOME_FB_PRO_PER_XXS_0') : 1,
    
                'has_background_img'     => ((int)Configuration::get($this->_field_prefix.'BG_PATTERN') || Configuration::get($this->_field_prefix.'BG_IMG')) ? 1 : 0,
                'speed'          => (float)Configuration::get($this->_field_prefix.'SPEED'),
                'control_nav'           => Configuration::get($this->_field_prefix.'CONTROL_NAV'),
            ));
        }                
		return $this->display(__FILE__, 'stblogfeaturedarticles-home.tpl', $this->stGetCacheId($hook_hash)); 
    }
    public function hookDisplayHomeTop($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__)); 
    }
    public function hookDisplayHomeBottom($params)
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
    public function hookDisplayHomeVeryBottom($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__), 2); 
    }
    
    public function hookDisplayTopColumn($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__)); 
    }

    public function hookDisplayBottomColumn($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__)); 
    }
    public function hookDisplayFooter($params, $hook_hash = '')
    {
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
        
        if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
        if (!$this->isCached('stblogfeaturedarticles-footer.tpl', $this->stGetCacheId($hook_hash)))
	    {     
            if(!$this->_prepareHook('footer'))    
                return false;
            
            $this->smarty->assign(array(
                    'hook_hash' => $hook_hash
        		)); 
        }    
		return $this->display(__FILE__, 'stblogfeaturedarticles-footer.tpl', $this->stGetCacheId($hook_hash));
    }
    public function hookDisplayFooterTop($params)
    {
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));         
    }
    public function hookDisplayFooterSecondary($params)
    {
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));         
    }
    private function getConfigFieldsValues()
    {
        $fields_values = array(
            'nbr'=> Configuration::get($this->_field_prefix.'NBR'),
            'soby'=> Configuration::get($this->_field_prefix.'SOBY'),
            'hide_mob'=> Configuration::get($this->_field_prefix.'HIDE_MOB'),
            'display_sd'=> Configuration::get($this->_field_prefix.'DISPLAY_SD'),
            'aw_display'=> Configuration::get($this->_field_prefix.'AW_DISPLAY'),
            'grid'=> Configuration::get($this->_field_prefix.'GRID'),
                        
            'nbr_h'=> Configuration::get($this->_field_prefix.'NBR_H'),
            'soby_h'=> Configuration::get($this->_field_prefix.'SOBY_H'),
            'hide_mob_h'=> Configuration::get($this->_field_prefix.'HIDE_MOB_H'),
            'display_sd_h'=> Configuration::get($this->_field_prefix.'DISPLAY_SD_H'),
            'aw_display_h'=> Configuration::get($this->_field_prefix.'AW_DISPLAY_H'),
            'grid_h'=> Configuration::get($this->_field_prefix.'GRID_H'),
            
            'easing'=> Configuration::get($this->_field_prefix.'EASING'),
            'slideshow'=> Configuration::get($this->_field_prefix.'SLIDESHOW'),
            's_speed'=> Configuration::get($this->_field_prefix.'S_SPEED'),
            'a_speed'=> Configuration::get($this->_field_prefix.'A_SPEED'),
            'pause_on_hover'=> Configuration::get($this->_field_prefix.'PAUSE_ON_HOVER'),
            'loop'=> Configuration::get($this->_field_prefix.'LOOP'),
            'move'=> Configuration::get($this->_field_prefix.'MOVE'),
            
            'nbr_col'=> Configuration::get($this->_field_prefix.'NBR_COL'),    
            'nbr_footer'=> Configuration::get($this->_field_prefix.'NBR_FOOTER'),  

            'top_padding'        => Configuration::get($this->_field_prefix.'TOP_PADDING'),
            'bottom_padding'     => Configuration::get($this->_field_prefix.'BOTTOM_PADDING'),
            'top_margin'         => Configuration::get($this->_field_prefix.'TOP_MARGIN'),
            'bottom_margin'      => Configuration::get($this->_field_prefix.'BOTTOM_MARGIN'),
            'bg_pattern'         => Configuration::get($this->_field_prefix.'BG_PATTERN'),
            'bg_img'             => Configuration::get($this->_field_prefix.'BG_IMG'),
            'bg_color'           => Configuration::get($this->_field_prefix.'BG_COLOR'),
            'speed'              => Configuration::get($this->_field_prefix.'SPEED'),

            'title_color'           => Configuration::get($this->_field_prefix.'TITLE_COLOR'),
            'text_color'            => Configuration::get($this->_field_prefix.'TEXT_COLOR'),
            'link_hover_color'      => Configuration::get($this->_field_prefix.'LINK_HOVER_COLOR'),
            'direction_color'       => Configuration::get($this->_field_prefix.'DIRECTION_COLOR'),
            'direction_bg'          => Configuration::get($this->_field_prefix.'DIRECTION_BG'),
            'direction_hover_bg'    => Configuration::get($this->_field_prefix.'DIRECTION_HOVER_BG'),
            'direction_disabled_bg' => Configuration::get($this->_field_prefix.'DIRECTION_DISABLED_BG'),
            
            'title_alignment'       => Configuration::get($this->_field_prefix.'TITLE_ALIGNMENT'),
            'title_no_bg'           => Configuration::get($this->_field_prefix.'TITLE_NO_BG'),
            'title_font_size'       => Configuration::get($this->_field_prefix.'TITLE_FONT_SIZE'),
            'direction_nav'         => Configuration::get($this->_field_prefix.'DIRECTION_NAV'),
            'cat_mod'               => Configuration::get($this->_field_prefix.'CAT_MOD'),
            'cat_mod_h'             => Configuration::get($this->_field_prefix.'CAT_MOD_H'),    
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
    
    public function updateCatePerRow($prefix = '') {
        $arr = $this->findCateProPer();
        $prefix = $prefix.'_';
        foreach ($arr as $v)
            if($gv = Tools::getValue($prefix.$v['id']))
                Configuration::updateValue('STSN_'.strtoupper($prefix.$v['id']), (int)$gv);
    }
    
    public function BuildDropListGroup($group, $prefix='')
    {
        if(!is_array($group) || !count($group))
            return false;
        $prefix = $prefix.'_';
        $html = '<div class="row">';
        foreach($group AS $key => $k)
        {
             if($key==3)
                 $html .= '</div><div class="row">';

             $html .= '<div class="col-xs-4 col-sm-3"><label '.(isset($k['tooltip']) ? ' data-html="true" data-toggle="tooltip" class="label-tooltip" data-original-title="'.$k['tooltip'].'" ':'').'>'.$k['label'].'</label>'.
             '<select name="'.$prefix.$k['id'].'" 
             id="'.$prefix.$k['id'].'" 
             class="'.(isset($k['class']) ? $k['class'] : 'fixed-width-md').'"'.
             (isset($k['onchange']) ? ' onchange="'.$k['onchange'].'"':'').' >';
            
            for ($i=1; $i < 7; $i++){
                $html .= '<option value="'.$i.'" '.(Configuration::get('STSN_'.strtoupper($prefix.$k['id'])) == $i ? ' selected="selected"':'').'>'.$i.'</option>';
            }
                                
            $html .= '</select></div>';
        }
        return $html.'</div>';
    }
    
    public function findCateProPer()
    {
        return array(
            array(
                'id' => 'fb_pro_per_lg_0',
                'label' => $this->l('Large devices'),
                'tooltip' => $this->l('Desktops (>1200px)'),
            ),
            array(
                'id' => 'fb_pro_per_md_0',
                'label' => $this->l('Medium devices'),
                'tooltip' => $this->l('Desktops (>992px)'),
            ),
            array(
                'id' => 'fb_pro_per_sm_0',
                'label' => $this->l('Small devices'),
                'tooltip' => $this->l('Tablets (>768px)'),
            ),
            array(
                'id' => 'fb_pro_per_xs_0',
                'label' => $this->l('Extra small devices'),
                'tooltip' => $this->l('Phones (>480px)'),
            ),
            array(
                'id' => 'fb_pro_per_xxs_0',
                'label' => $this->l('Extra extra small devices'),
                'tooltip' => $this->l('Phones (<480px)'),
            ),
        );
    }

    public function hookDisplayHomeSecondaryLeft($params)
    {
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
    public function fetchMediaServer(&$slider)
    {
        $slider = _THEME_PROD_PIC_DIR_.$slider;
        $slider = context::getContext()->link->protocol_content.Tools::getMediaServer($slider).$slider;
    }
    public function getHookHash($func='')
    {
        if (!$func)
            return '';
        return substr(md5($func), 0, 10);
    }
    public function get_prefix()
    {
        if (isset($this->_prefix_st) && $this->_prefix_st)
            return $this->_prefix_st;
        return false;
    }
}