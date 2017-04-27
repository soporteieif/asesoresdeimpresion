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

class HomeFeatured_mod extends Module
{
    protected static $cache_products = false;
	private $_html = '';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();
    private $_prefix_st = 'HOME_FEATURED_';
    private $_prefix_stsn = 'STSN_';
    public $imgtype = array('jpg', 'gif', 'jpeg', 'png');
    protected static $access_rights = 0775;
    public static $sort_by = array(
        1 => array('id' =>1 , 'name' => 'Product Name: A to Z'),
        2 => array('id' =>2 , 'name' => 'Product Name: Z to A'),
        3 => array('id' =>3 , 'name' => 'Price: Lowest first'),
        4 => array('id' =>4 , 'name' => 'Price: Highest first'),
        5 => array('id' =>5 , 'name' => 'Product ID: Asc'),
        6 => array('id' =>6 , 'name' => 'Product ID: Desc'),
        7 => array('id' =>7 , 'name' => 'Random'),
        8 => array('id' =>8 , 'name' => 'Position: Asc'),
        9 => array('id' =>9 , 'name' => 'Position: Desc'),
        10 => array('id' =>10 , 'name' => 'Date update: Asc'),
        11 => array('id' =>11 , 'name' => 'Date update: Desc'),
        12 => array('id' =>12 , 'name' => 'Date add: Asc'),
        13 => array('id' =>13 , 'name' => 'Date add: Desc'),
    );
	function __construct()
	{
		$this->name = 'homefeatured_mod';
		$this->tab = 'front_office_features';
		$this->version = '1.6.4';
		$this->author = 'SUNNYTOO.COM';
		$this->need_instance = 0;
        $this->bootstrap = true;

        $this->bootstrap = true;
		parent::__construct();
        
        $this->initHookArray();

		$this->displayName = $this->l('Featured products on the homepage mod.');
		$this->description = $this->l('Displays featured products in the middle of your homepage.');
	}
    
    private function initHookArray()
    {
        $this->_hooks = array(
            'Hooks' => array(
                array(
                    'id' => 'displayFullWidthTop',
                    'val' => '1',
                    'name' => $this->l('displayFullWidthTop'),
                    'full_width' => 1,
                ),
                array(
        			'id' => 'displayFullWidthTop2',
        			'val' => '1',
        			'name' => $this->l('displayFullWidthTop2'),
                    'full_width' => 1,
        		),
        		array(
        			'id' => 'displayHomeTop',
        			'val' => '1',
        			'name' => $this->l('displayHomeTop'),
        		),
                array(
        			'id' => 'displayHome',
        			'val' => '1',
        			'name' => $this->l('displayHome'),
        		),
        		array(
        			'id' => 'displayHomeSecondaryLeft',
        			'val' => '1',
        			'name' => $this->l('displayHomeSecondaryLeft'),
        		),
        		array(
        			'id' => 'displayHomeTertiaryLeft',
        			'val' => '1',
        			'name' => $this->l('displayHomeTertiaryLeft'),
        		),
        		array(
        			'id' => 'displayHomeTertiaryRight',
        			'val' => '1',
        			'name' => $this->l('displayHomeTertiaryRight'),
        		),
                array(
        			'id' => 'displayHomeBottom',
        			'val' => '1',
        			'name' => $this->l('displayHomeBottom'),
        		),
                array(
        			'id' => 'displayBottomColumn',
        			'val' => '1',
        			'name' => $this->l('displayBottomColumn'),
        		),
                array(
        			'id' => 'displayHomeVeryBottom',
        			'val' => '1',
        			'name' => $this->l('displayFullWidthBottom(homeverybottom)'),
                    'full_width' => 1,
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
		if (!Configuration::updateValue('HOME_FEATURED_NBR_MOD', 6) 
            || !Configuration::updateValue('HOME_FEATURED_SOBY', 6) 
            || !Configuration::updateValue('HOME_FEATURED_CAT_MOD', (int)Context::getContext()->shop->getCategory())
            || !parent::install() 
            || !$this->registerHook('displayHeader')
			|| !$this->registerHook('addproduct')
			|| !$this->registerHook('updateproduct')
			|| !$this->registerHook('deleteproduct')
            || !$this->registerHook('displayHomeSecondaryLeft')
            || !Configuration::updateValue('STSN_FMOD_PRO_PER_LG_0', 3)
            || !Configuration::updateValue('STSN_FMOD_PRO_PER_MD_0', 3)
            || !Configuration::updateValue('STSN_FMOD_PRO_PER_SM_0', 2)
            || !Configuration::updateValue('STSN_FMOD_PRO_PER_XS_0', 2)
            || !Configuration::updateValue('STSN_FMOD_PRO_PER_XXS_0', 1)
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
            || !Configuration::updateValue($this->_prefix_st.'TITLE_NO_BG', 0)
            || !Configuration::updateValue($this->_prefix_st.'TEXT_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'PRICE_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'LINK_HOVER_COLOR', '')
        )
			return false;
		$this->clearSliderCache();
		return true;
	}
    public function uninstall()
	{
		$this->clearSliderCache();
		return parent::uninstall();
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
        if (isset($_POST['savehomefeatured_mod']))
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
        $this->fields_form[0]['form']['input']['fmod_pro_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer());
        if ($bg_img = Configuration::get($this->_prefix_st.'BG_IMG'))
        {
            $this->fetchMediaServer($bg_img);
            $this->fields_form[0]['form']['input']['bg_img_field']['image'] = '<img width=200 src="'.($bg_img).'" /><p><a class="btn btn-default st_delete_image" href="javascript:;"><i class="icon-trash"></i> Delete</a></p>';
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
                'title' => $this->l('Settings'),
                'icon'  => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Define the number of products to be displayed:'),
                    'name' => 'nbr_mod',
                    'default_value' => 10,
                    'desc' => array(
                        $this->l('To add products to your homepage, simply add them to the corresponding product category (default: "Home").'),
                        $this->l('Define the number of products that you would like to display on homepage (default: 6).'),
                    ),
                    'class' => 'fixed-width-sm',
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Category from which to pick products to be displayed'),
                    'name' => 'cat_mod',
                    'class' => 'fixed-width-xs',
                    'desc' => $this->l('Choose the category ID of the products that you would like to display on homepage (default: 2 for "Home").'),
                    'validation' => 'isAnything',
                ),
                'fmod_pro_per_0' => array(
                    'type' => 'html',
                    'id' => 'fmod_pro_per_0',
                    'label'=> $this->l('The number of columns'),
                    'name' => '',
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
            ),
            'submit' => array(
                'title' => $this->l('   Save all  ')
            )
        );
        
        $this->fields_form[1]['form'] = array(
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
            $this->fields_form[1]['form']['input'][] = array(
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
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savehomefeatured_mod';
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
            $title_block_no_bg = '.featured-products_block_center_container .title_block,.featured-products_block_center_container .title_block a,.featured-products_block_center_container .title_block span{background:none;}';            
                        
            $group_css = '';            
            if ($bg_color = Configuration::get($this->_prefix_st.'BG_COLOR'))
                $group_css .= 'background-color:'.$bg_color.';';
            if ($bg_img = Configuration::get($this->_prefix_st.'BG_IMG'))
            {
                $this->fetchMediaServer($bg_img);
                $group_css .= 'background-image: url("'.$bg_img.'");';
            }
            elseif ($bg_pattern = Configuration::get($this->_prefix_st.'BG_PATTERN'))
            {
                $img = _MODULE_DIR_.'stthemeeditor/patterns/'.$bg_pattern.'.png';
                $img = $this->context->link->protocol_content.Tools::getMediaServer($img).$img;
                $group_css .= 'background-image: url("'.$img.'");';
            }
            if($group_css)
                $custom_css .= '.featured-products_block_center_container{background-attachment:fixed;'.$group_css.'}'.$title_block_no_bg;

            if ($top_padding = (int)Configuration::get($this->_prefix_st.'TOP_PADDING'))
                $custom_css .= '.featured-products_block_center_container{padding-top:'.$top_padding.'px;}';
            if ($bottom_padding = (int)Configuration::get($this->_prefix_st.'BOTTOM_PADDING'))
                $custom_css .= '.featured-products_block_center_container{padding-bottom:'.$bottom_padding.'px;}';

            $top_margin = Configuration::get($this->_prefix_st.'TOP_MARGIN');
            if($top_margin || $top_margin!==null)
                $custom_css .= '.featured-products_block_center_container{margin-top:'.$top_margin.'px;}';
            $bottom_margin = Configuration::get($this->_prefix_st.'BOTTOM_MARGIN');
            if($bottom_margin || $bottom_margin!==null)
                $custom_css .= '.featured-products_block_center_container{margin-bottom:'.$bottom_margin.'px;}';

            if ($title_color = Configuration::get($this->_prefix_st.'TITLE_COLOR'))
                $custom_css .= '.featured-products_block_center_container.block .title_block a, .featured-products_block_center_container.block .title_block span{color:'.$title_color.';}';
            if ($title_hover_color = Configuration::get($this->_prefix_st.'TITLE_HOVER_COLOR'))
                $custom_css .= '.featured-products_block_center_container.block .title_block a:hover{color:'.$title_hover_color.';}';
            if (Configuration::get($this->_prefix_st.'TITLE_NO_BG'))
                $custom_css .= $title_block_no_bg;

            if ($text_color = Configuration::get($this->_prefix_st.'TEXT_COLOR'))
                $custom_css .= '.featured-products_block_center_container .s_title_block a,
                .featured-products_block_center_container .price,
                .featured-products_block_center_container .old_price,
                .featured-products_block_center_container .product_desc{color:'.$text_color.';}';

            if ($price_color = Configuration::get($this->_prefix_st.'PRICE_COLOR'))
                $custom_css .= '.featured-products_block_center_container .price{color:'.$price_color.';}';
            if ($link_hover_color = Configuration::get($this->_prefix_st.'LINK_HOVER_COLOR'))
                $custom_css .= '.featured-products_block_center_container .s_title_block a:hover{color:'.$link_hover_color.';}';

            if($custom_css)
                $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
        }
        return $this->display(__FILE__, 'header.tpl', $this->getCacheId());
    }
	public function hookDisplayHome($params, $hook_hash = '', $flag = 0)
	{
        if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
	    $is_random = Configuration::get('HOME_FEATURED_SOBY')==7;
	    if ($is_random || !$this->isCached('homefeatured.tpl', $this->getCacheId()))
    		if(!$this->_prepareHook())
                return false;

        $this->smarty->assign(array(
            'hook_hash'              => $hook_hash,
            'homeverybottom'         => ($flag==2 ? true : false),
            'has_background_img'     => ((int)Configuration::get($this->_prefix_st.'BG_PATTERN') || Configuration::get($this->_prefix_st.'BG_IMG')) ? 1 : 0,
            'speed'                  => (float)Configuration::get($this->_prefix_st.'SPEED'),
        ));
                
		return $is_random ? $this->display(__FILE__, 'homefeatured.tpl') : $this->display(__FILE__, 'homefeatured.tpl', $this->getCacheId());
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
    
    public function hookDisplayHomeTop($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    
    public function hookDisplayHomeBottom($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
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
    
    private function _prepareHook()
    {
		$nb = $random_number_products =(int)(Configuration::get('HOME_FEATURED_NBR_MOD'));
        
        $order_by = 'id_product';
        $order_way = 'DESC';
        $random = false;
        switch(Configuration::get('HOME_FEATURED_SOBY'))
        {
            case 1:
                $order_by = 'name';
                $order_way = 'ASC';
            break;
            case 2:
                $order_by = 'name';
                $order_way = 'DESC';
            break;
            case 3:
                $order_by = 'price';
                $order_way = 'ASC';
            break;
            case 4:
                $order_by = 'price';
                $order_way = 'DESC';
            break;
            case 5:
                $order_by = 'id_product';
                $order_way = 'ASC';
            break;
            case 7:
                $order_by = null;
                $order_way = null;
                $random = true;
            break;
            case 8:
                $order_by = 'position';
                $order_way = 'ASC';
            break;
            case 9:
                $order_by = 'position';
                $order_way = 'DESC';
            break;
            case 10:
                $order_by = 'date_upd';
                $order_way = 'ASC';
            break;
            case 11:
                $order_by = 'date_upd';
                $order_way = 'DESC';
            break;
            case 12:
                $order_by = 'date_add';
                $order_way = 'ASC';
            break;
            case 13:
                $order_by = 'date_add';
                $order_way = 'DESC';
            break;
            default:
            break;
        }
        
        if (!empty(self::$cache_products))
            $products = self::$cache_products ;
        else
        {
            $featured_category_id = (int)Configuration::get('HOME_FEATURED_CAT_MOD');
            if(!$featured_category_id)
                $featured_category_id = (int)Context::getContext()->shop->getCategory();
            $category = new Category($featured_category_id, (int)Context::getContext()->language->id);
    		$products = $category->getProducts((int)Context::getContext()->language->id, 1, ($nb ? $nb : 10), $order_by, $order_way, false, true, $random, $random_number_products);
            self::$cache_products = $products;
        }
        
        /*
        if(!$products)
            return false;
		*/
        $this->smarty->assign(array(
			'products' => $products,
			'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
			'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
            'mediumSize' => Image::getSize(ImageType::getFormatedName('medium')),

            'pro_per_lg'       => (int)Configuration::get('STSN_FMOD_PRO_PER_LG_0'),
            'pro_per_md'       => (int)Configuration::get('STSN_FMOD_PRO_PER_MD_0'),
            'pro_per_sm'       => (int)Configuration::get('STSN_FMOD_PRO_PER_SM_0'),
            'pro_per_xs'       => (int)Configuration::get('STSN_FMOD_PRO_PER_XS_0'),
            'pro_per_xxs'       => (int)Configuration::get('STSN_FMOD_PRO_PER_XXS_0'),
		));
        return true;
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
    private function getConfigFieldsValues()
    {
        $fields_values = array( 
            'nbr_mod' => Configuration::get('HOME_FEATURED_NBR_MOD'),
            'soby' => Configuration::get('HOME_FEATURED_SOBY'),
            'cat_mod' => Configuration::get('HOME_FEATURED_CAT_MOD'), 
            
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
            'title_no_bg'           => Configuration::get($this->_prefix_st.'TITLE_NO_BG'),
            'text_color'            => Configuration::get($this->_prefix_st.'TEXT_COLOR'),
            'price_color'           => Configuration::get($this->_prefix_st.'PRICE_COLOR'),
            'link_hover_color'      => Configuration::get($this->_prefix_st.'LINK_HOVER_COLOR'),
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
                'id' => 'fmod_pro_per_lg_0',
                'label' => $this->l('Large devices'),
                'tooltip' => $this->l('Desktops (>1200px)'),
            ),
            array(
                'id' => 'fmod_pro_per_md_0',
                'label' => $this->l('Medium devices'),
                'tooltip' => $this->l('Desktops (>992px)'),
            ),
            array(
                'id' => 'fmod_pro_per_sm_0',
                'label' => $this->l('Small devices'),
                'tooltip' => $this->l('Tablets (>768px)'),
            ),
            array(
                'id' => 'fmod_pro_per_xs_0',
                'label' => $this->l('Extra small devices'),
                'tooltip' => $this->l('Phones (>480px)'),
            ),
            array(
                'id' => 'fmod_pro_per_xxs_0',
                'label' => $this->l('Extra extra small devices'),
                'tooltip' => $this->l('Phones (<480px)'),
            ),
        );
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
