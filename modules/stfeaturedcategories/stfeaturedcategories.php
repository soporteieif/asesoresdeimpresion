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
    
require (dirname(__FILE__).'/StFeaturedCategoriesClass.php');

class StFeaturedCategories extends Module
{
    protected static $cache_featured_categories = false;
	private $_html = '';
    public $fields_list;
    public $fields_form;
    private $_baseUrl;
    private $spacer_size = '5';
    public static $_auto_type = array();
    public $validation_errors = array();
    public  $fields_form_setting;
	
	public function __construct()
	{
		$this->name          = 'stfeaturedcategories';
		$this->tab           = 'front_office_features';
		$this->version       = '1.4.7';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
		$this->bootstrap 	 = true;
		parent::__construct();

		$this->displayName   = $this->l('Featured categories');
		$this->description   = $this->l('Display featured categories on your homepage.');
        
        self::$_auto_type[0] = $this->l('No');
        self::$_auto_type[1] = $this->l('Yes');
	}

	public function install()
	{
	    $res = $this->installDB() &&
            parent::install() &&
			$this->registerHook('displayHeader') &&
            $this->registerHook('actionCategoryAdd') &&
			$this->registerHook('actionCategoryDelete') &&
			$this->registerHook('actionCategoryUpdate') &&
			$this->registerHook('displayHomeSecondaryLeft') &&
            Configuration::updateValue('ST_PRO_CATE_F_C_NUMBER', 5) &&
            Configuration::updateValue('ST_PRO_CATE_F_C_IMAGE', 1) &&
            Configuration::updateValue('STSN_FEATURED_CATE_PER_LG_0', 4) &&
            Configuration::updateValue('STSN_FEATURED_CATE_PER_MD_0', 4) &&
            Configuration::updateValue('STSN_FEATURED_CATE_PER_SM_0', 3) &&
            Configuration::updateValue('STSN_FEATURED_CATE_PER_XS_0', 2) &&
            Configuration::updateValue('STSN_FEATURED_CATE_PER_XXS_0', 1);
            
		$this->clearstfeaturedcategoryCache();
		return $res;
	}

	public function installDb()
	{
		$return = true;
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_featured_category` (
				`id_st_featured_category` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_parent` int(10) NOT NULL DEFAULT 0,
                `level_depth` tinyint(3) unsigned NOT NULL DEFAULT 0,   
				`id_shop` int(10) unsigned NOT NULL,
                `id_category` int(10) unsigned NOT NULL DEFAULT 0,
                `position` int(10) unsigned NOT NULL DEFAULT 0,
                `txt_color` varchar(7) DEFAULT NULL,
                `txt_color_over` varchar(7) DEFAULT NULL,
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `auto_sub` tinyint(1) unsigned NOT NULL DEFAULT 0,
    			`cover` varchar(255) DEFAULT NULL,
				PRIMARY KEY (`id_st_featured_category`)
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
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_featured_category`');
	}
           
	public function getContent()
	{
    	$id_st_featured_category = (int)Tools::getValue('id_st_featured_category');
		if (isset($_POST['savestfeaturedcategory']) || isset($_POST['savestfeaturedcategoryAndStay']))
        {
            if($id_st_featured_category)
            {
                $category = new StFeaturedCategoriesClass($id_st_featured_category);
                $id_category_old = $category->id_category;
            }
			else
				$category = new StFeaturedCategoriesClass();
            
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
                    // Clear auto subcate if parent category was changed.
                    if ($id_st_featured_category && ($id_category_old != $category->id_category || !$category->auto_sub))
                        foreach(StFeaturedCategoriesClass::getSub($id_st_featured_category) AS $sub)
                        {
                            if (!$sub['auto_sub'])
                                continue;
                            $subcate = new StFeaturedCategoriesClass($sub['id_st_featured_category']);
                            $subcate->delete();
                        }
                    if($category->position==0)
                        $category->position = StFeaturedCategoriesClass::getMaximumPosition(0);
                    if($category->save())
                    {
                        if ($category->auto_sub)
                            $this->autoBuildingSubcate($category->id_category,$category->id);
                        $this->clearstfeaturedcategoryCache();
                        if(isset($_POST['savestfeaturedcategoryAndStay']) || Tools::getValue('fr') == 'view')
                        {
                            $rd_str = isset($_POST['savestfeaturedcategoryAndStay']) && Tools::getValue('fr') == 'view' ? 'fr=view&update' : (isset($_POST['savestfeaturedcategoryAndStay']) ? 'update' : 'view');
                            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_featured_category='.$category->id.'&conf='.($id_st_featured_category?4:3).'&'.$rd_str.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                        }    
                        else
                            $this->_html .= $this->displayConfirmation($this->l('Featured category').' '.($id_st_featured_category ? $this->l('updated') : $this->l('added')));
                    }
                    else
                        $this->_html .= $this->displayError($this->l('An error occurred during Featured category').' '.($id_st_featured_category ? $this->l('updating') : $this->l('creation')));
                }
            }
			else
				$this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }
		if (isset($_POST['savesubstfeaturedcategories']) || isset($_POST['savesubstfeaturedcategoriesAndStay']))
        {
            if($id_st_featured_category)
				$category = new StFeaturedCategoriesClass($id_st_featured_category);
			else
				$category = new StFeaturedCategoriesClass();
                
            $error = array();
            $category->id_shop = (int)Shop::getContextShopID();
            
            if (!$category->id_shop)
                $error[] = $this->displayError($this->l('Action denied, please select a store.'));
            
            if (!count($error))
            {
        		$category->copyFromPost();
                
                if(!$category->id_parent)
                    $error[] = $this->displayError($this->l('The field "Parent" is required'));
                else
                {
                    $category_parent = new StFeaturedCategoriesClass($category->id_parent);
                    $category->level_depth = $category_parent->level_depth+1;
                }
            }
            
            if (!count($error) && $category->validateFields(false) && $category->validateFieldsLang(false))
            {
                if($category->position==0)
                    $category->position = StFeaturedCategoriesClass::getMaximumPosition($category->id_parent);
                    
                if($category->save())
                {
                    $this->clearstfeaturedcategoryCache();
                    if(isset($_POST['savesubstfeaturedcategoriesAndStay']))
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_featured_category='.$category->id.'&conf='.($id_st_featured_category?4:3).'&update'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules')); 
                    else
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_featured_category='.$category->id_parent.'&view'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                }
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during subcategory').' '.($id_st_featured_category ? $this->l('updating') : $this->l('creation')));
            }
			else
				$this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }
        if (isset($_POST['savesettingstfeaturedcategories']))
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

            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else 
            {
                $this->clearstfeaturedcategoryCache();
                $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
            }
            $this->fields_form_setting[0]['form']['input']['featured_cate_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer());
            $helper = $this->initFormSetting();
			return $this->_html.$helper->generateForm($this->fields_form_setting);
		}
        if (Tools::isSubmit('addstfeaturedcategories'))
		{
            $helper = $this->_displayForm(); 
            $this->_html .= $helper->generateForm($this->fields_form);
			return $this->_html;
		}
        elseif (Tools::isSubmit('addsubstfeaturedcategories'))
		{
            if(!Tools::getValue('id_parent'))
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
            $helper = $this->initCategoryForm(); 
            $this->_html .= $helper->generateForm($this->fields_form);
			return $this->_html;
		}
        elseif (Tools::isSubmit('updatestfeaturedcategories'))
        {
    		$category = new StFeaturedCategoriesClass((int)$id_st_featured_category);
            if(!Validate::isLoadedObject($category) || $category->id_shop!=(int)Shop::getContextShopID())
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
               
            if($category->id_parent)
            {
                $helper = $this->initCategoryForm(); 
                $this->_html .= $helper->generateForm($this->fields_form);
            }
            elseif(!$category->id_parent)
            {
                $helper = $this->_displayForm(); 
                $this->_html .= $helper->generateForm($this->fields_form);
            }
			return $this->_html; 
        }
        else if (Tools::isSubmit('deletestfeaturedcategories'))
		{
    		$category = new StFeaturedCategoriesClass((int)$id_st_featured_category);
            if(Validate::isLoadedObject($category))
            {                    
                $category->delete();
                $this->clearstfeaturedcategoryCache();
                
                if($category->id_parent)
                    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_featured_category='.$category->id_parent.'&view'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
        elseif (Tools::isSubmit('statusstfeaturedcategories'))
		{
            $category = new StFeaturedCategoriesClass($id_st_featured_category);
            if (Validate::isLoadedObject($category))
            {
                $category->troggleStatus();
                $this->clearstfeaturedcategoryCache();
            }
            if($category->id_parent)
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_featured_category='.$category->id_parent.'&view'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
        elseif (Tools::isSubmit('settingstfeaturedcategories'))
		{
		    $this->initSettingForm();
            $helper = $this->initFormSetting();
            
            $helper->fields_value['f_c_number'] = Configuration::get('ST_PRO_CATE_F_C_NUMBER');
            $helper->fields_value['f_c_image'] = Configuration::get('ST_PRO_CATE_F_C_IMAGE');
            
			return $this->_html.$helper->generateForm($this->fields_form_setting);
		}
        elseif(Tools::isSubmit('viewstfeaturedcategories'))
        {
    		$category = new StFeaturedCategoriesClass((int)$id_st_featured_category);
            if(!Validate::isLoadedObject($category))
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
            
            $helper = $this->initCategoryList();
            $list = StFeaturedCategoriesClass::getSub($id_st_featured_category);
            
            // skip delete action for auto-sub
            foreach($list AS $v)
                if ($v['auto_sub'])
                    $helper->list_skip_actions['delete'][] = $v['id_st_featured_category'];
            
            $this->_html .= $helper->generateList($list, $this->fields_list);
            
			return $this->_html;
        }
        else
        {
            $helper = $this->initList();
            $list = StFeaturedCategoriesClass::getSub(0);
			return $this->_html.$helper->generateList($list, $this->fields_list);
        }
            
	}
       
    public function updateCatePerRow() {
        $arr = $this->findCateProPer();
        foreach ($arr as $v)
            if($gv = Tools::getValue($v['id']))
                Configuration::updateValue('STSN_'.strtoupper($v['id']), (int)$gv);
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
					'type' => 'text',
					'label' => $this->l('The number of subcategory:'),
					'name' => 'f_c_number',
                    'default_value' => 5,
                    'required' => true,
                    'desc' => $this->l('Set number of children to show in the top category.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                'featured_cate_per_0' => array(
                    'type' => 'html',
                    'id' => 'featured_cate_per_0',
                    'label'=> $this->l('The number of columns'),
                    'name' => $this->BuildDropListGroup($this->findCateProPer()),
                ),
                array(
					'type' => 'switch',
					'label' => $this->l('Show category image:'),
					'name' => 'f_c_image',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'f_c_image_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'f_c_image_off',
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
            'submit' => array(
				'title' => $this->l('Save'),
			),
		);
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
		$helper->submit_action = 'savesettingstfeaturedcategories';
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
					'label' => $this->l('Top category:'),
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
					'label' => $this->l('Automatic building subcategories:'),
					'name' => 'auto_sub',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'auto_sub_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'auto_sub_off',
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
        
        $this->fields_form[0]['form']['input'][] = array(
			'type' => 'html',
            'id' => 'a_cancel',
			'label' => '',
			'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
		);
        
        $id_st_featured_category = (int)Tools::getValue('id_st_featured_category');
        if($id_st_featured_category)
            $category = new StFeaturedCategoriesClass((int)$id_st_featured_category);
        else
            $category = new StFeaturedCategoriesClass();
        if(Validate::isLoadedObject($category))
        {
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_featured_category');
        }
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestfeaturedcategory';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($category),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper;
    }
    public function getParentList($id_parent)
    {
        $result = array();
        $parents = StFeaturedCategoriesClass::recurseTree($id_parent,1,1,0,$this->context->language->id,1); 
         
        if($parents)
            $parents = $this->_toFlat($parents);          
        foreach ($parents as $parent)
        {
            if($parent['id_category'] && $parent['auto_sub'])
                continue;
            $spacer = str_repeat('&nbsp;', $this->spacer_size * (int)$parent['level_depth']);
            $result[] = array(
                'id' => $parent['id_st_featured_category'],
                'name' => $spacer.$this->displayTitle($parent['title'],$parent),
            );
        }
        return $result;
    }

	protected function initCategoryForm()
	{                
    	$id_st_featured_category = (int)Tools::getValue('id_st_featured_category');
        if($id_st_featured_category)
        {
            $category = new StFeaturedCategoriesClass($id_st_featured_category);
            $id_parent = $category->id_parent;
        }
        else
        {
            $category = new StFeaturedCategoriesClass();
        }
        if(!isset($id_parent) && Tools::getValue('id_parent'))
            $id_parent = (int)Tools::getValue('id_parent');
                
        $id_lang = $this->context->language->id;
        $category_arr = array();
		$this->getCategoryOption($category_arr, Category::getRootCategory()->id, (int)$id_lang, (int)Shop::getContextShopID(),true);
        
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Sub category'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
    				'type' => 'select',
    				'label' => $this->l('Sub category:'),
    				'name' => 'id_category',
                    'required' => true,
    				'options' => array(
    					'query' => $category_arr,
    					'id' => 'id',
    					'name' => 'name'
    		          ),
    		    ),
                array(
					'type' => 'color',
					'label' => $this->l('Color:'),
					'name' => 'txt_color',
					'class' => 'color',
					'size' => 20,
				), 
                array(
					'type' => 'color',
					'label' => $this->l('Color on hover:'),
					'name' => 'txt_color_over',
					'class' => 'color',
					'size' => 20,
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
					'name' => 'auto_sub',
                    'default_value' => $category->id ? $category->auto_sub : 0,
				),
                array(
					'type' => 'html',
                    'id' => 'a_cancel',
					'label' => '',
					'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&id_st_featured_category='.$id_parent.'&view'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
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
        
        if($category->id)
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_featured_category');
        $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_parent', 'default_value' => $id_parent);
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savesubstfeaturedcategories';
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

    public static function displayType($value, $row)
	{
	   return self::$_auto_type[(int)$value];
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
			'auto_sub' => array(
				'title' => $this->l('Automatic building subcategories'),
				'class' => 'fixed-width-xl',
				'type' => 'text',
				'callback' => 'displayType',
				'callback_object' => 'stfeaturedcategories',
                'search' => false,
                'orderby' => false
			),
            'active' => array(
    			'title' => $this->l('Displayed'), 
                'class' => 'fixed-width-xl',
                'active' => 'status',
    			'align' => 'center',
                'type' => 'bool',
                'search' => false,
                'orderby' => false
            ),
			'position' => array(
				'title' => $this->l('Position'),
				'class' => 'fixed-width-lg',
				'type' => 'text',
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
		$helper->identifier = 'id_st_featured_category';
		$helper->actions = array('view', 'edit', 'delete');
		$helper->show_toolbar = true;
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&add'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add new category')
		);
        $helper->toolbar_btn['edit'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&setting'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Setting'),
		);
		$helper->title = $this->displayName;
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    public static function displayContent($value, $row)
    {
        if($value)
            return stfeaturedcategory::displayTitle($value, $row);
        else
        {
            $module = new stfeaturedcategory();
            return $module->l('Custom content');
        }
    }
    
    public function initCategoryList()
    {
        $this->fields_list = array(
			'name' => array(
				'title' => $this->l('Category name'),
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
            'active' => array(
    			'title' => $this->l('Displayed'), 
                'class' => 'fixed-width-xxl',
                'active' => 'status',
    			'align' => 'center',
                'type' => 'bool',
                'search' => false,
                'orderby' => false
            ),
			'position' => array(
				'title' => $this->l('Position'),
				'class' => 'fixed-width-xxl',
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
		);

		if (Shop::isFeatureActive())
			$this->fields_list['id_shop'] = array(
                'title' => $this->l('ID Shop'), 
                'align' => 'center', 
                'class' => 'fixed-width-xs', 
                'type' => 'int',
                'search' => false,
                'orderby' => false
            );

		$helper = new HelperList();
		$helper->simple_header = false;
        $helper->shopLinkType = '';
		$helper->identifier = 'id_st_featured_category';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addsub'.$this->name.'&id_parent='.(int)Tools::getValue('id_st_featured_category').'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add subcategory')
		);
        $helper->toolbar_btn['edit'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&update'.$this->name.'&id_st_featured_category='.(int)Tools::getValue('id_st_featured_category').'&fr=view&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Edit parent category'),
		);
		$helper->toolbar_btn['back'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Back to list')
		);

		$helper->title = $this->l('Sub categories');
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
    }
    
    public function deleteFeaturedCategories($id_category)
    {
        if ($id_category)
        {
            $cats = Db::getInstance('
            SELECT `id_st_featured_category` FROM '._DB_PREFIX_.'st_featured_category
            WHERE `id_category` = '.(int)$id_category.'
            ');
            foreach($cats AS $cat)
            {
                $obj = new StFeaturedCategoriesClass($cat['id_st_featured_category']);
                $obj->delete();
            }
        }
    }
    
    public function updateFeaturedCategory($category)
    {
        if (!Validate::isLoadedObject($category))
            return false;
        
        // is featured category?
        $featured = Db::getInstance()->executeS('
        SELECT * FROM '._DB_PREFIX_.'st_featured_category
        WHERE (`id_category` = '.(int)$category->id.'
        OR `id_category` = '.(int)$category->id_parent.')
        AND `auto_sub` > 0
        ORDER BY id_st_featured_category
        ');
        foreach($featured AS $v)
        {
            if ($v['id_category'] == (int)$category->id)
            {
                $parent = StFeaturedCategoriesClass::getById($v['id_parent']);
                if ($parent['id_category'] != $category->id_parent)
                {
                    $new_parent = Db::getInstance()->executeS('
                    SELECT * FROM '._DB_PREFIX_.'st_featured_category
                    WHERE `id_category` = '.(int)$category->id_parent.'
                    AND `auto_sub` > 0 AND `id_parent` = 0
                    ');
                    $updated = false;
                    if ($new_parent)
                        foreach($new_parent AS $_v)
                        {
                            if (Db::getInstance()->getValue('
                            SELECT COUNT(0) FROM '._DB_PREFIX_.'st_featured_category
                            WHERE `id_category` = '.(int)$v['id_category'].'
                            AND `id_parent` = '.(int)$_v['id_st_featured_category'].'
                            '))
                                continue;
                            
                            $updated = Db::getInstance()->execute('
                            UPDATE '._DB_PREFIX_.'st_featured_category
                            SET `id_parent` = '.(int)$_v['id_st_featured_category'].'
                            WHERE `id_st_featured_category` = '.(int)$v['id_st_featured_category'].'
                            ');
                            break;
                        }
                    
                    if (!$updated)
                    {
                         Db::getInstance()->execute('
                            DELETE FROM '._DB_PREFIX_.'st_featured_category
                            WHERE `id_st_featured_category` = '.(int)$v['id_st_featured_category'].'
                            ');
                    }
                }
            }
            elseif($v['id_category'] == $category->id_parent && !$v['id_parent'])
            {
                $child = Db::getInstance()->getValue('
                SELECT COUNT(0) FROM '._DB_PREFIX_.'st_featured_category
                WHERE `id_category` = '.(int)$category->id.'
                AND `id_parent` = '.(int)$v['id_st_featured_category'].'
                ');
                if (!$child)
                {
                    $id_shop    = (int)Shop::getContextShopID();
                    $cate   = new StFeaturedCategoriesClass();
                    $cate->id_parent = (int)$v['id_st_featured_category'];
                    $cate->level_depth = $v['level_depth']+1;
                    $cate->id_shop = $id_shop;
                    $cate->id_category = (int)$category->id;
                    $cate->position = StFeaturedCategoriesClass::getMaximumPosition((int)$v['id_st_featured_category']);
                    $cate->active = 1;
                    $cate->auto_sub = 1;
                    
                    $cate->save();
               }
            }
        }
    }
    
    public function hookDisplayHeader()
    {
		$this->context->controller->addCSS($this->_path.'views/css/stfeaturedcategories.css');
    }

	public function hookActionCategoryDelete($params)
	{
	    if(isset($params['category']))
	       $this->deleteFeaturedCategories($params['category']->id);
		$this->clearstfeaturedcategoryCache();
	}
    
    public function hookActionCategoryAdd($params)
	{
	    if(isset($params['category']))
	       $this->updateFeaturedCategory($params['category']->id);
		$this->clearstfeaturedcategoryCache();
	}
    
	public function hookActionCategoryUpdate($params)
	{
	    if(isset($params['category']))
	       $this->updateFeaturedCategory($params['category']->id);
		$this->clearstfeaturedcategoryCache();
	}
    
    public function hookDisplayHomeTop($params)
    {
        return $this->hookDisplayHome($params);
    }
    public function hookDisplayHomeBottom($params)
    {
        return $this->hookDisplayHome($params);
    }
    
    private function _prepareHook($location= null)
    {
        if (!empty(self::$cache_featured_categories))
            $featured_categories = self::$cache_featured_categories;
        else
        {
            $featured_categories = StFeaturedCategoriesClass::getAll();
            self::$cache_featured_categories = $featured_categories;
        }
        
        if(!$featured_categories)
            return false;
		$this->smarty->assign(array(
            'featured_categories' => $featured_categories,
            'f_c_number' => Configuration::get('ST_PRO_CATE_F_C_NUMBER'),
            'f_c_image' => Configuration::get('ST_PRO_CATE_F_C_IMAGE'),
            'categorySize' => Image::getSize(ImageType::getFormatedName('category')),
			'mediumSize' => Image::getSize(ImageType::getFormatedName('medium')),
            'featured_cate_per_lg'       => (int)Configuration::get('STSN_FEATURED_CATE_PER_LG_0'),
            'featured_cate_per_md'       => (int)Configuration::get('STSN_FEATURED_CATE_PER_MD_0'),
            'featured_cate_per_sm'       => (int)Configuration::get('STSN_FEATURED_CATE_PER_SM_0'),
            'featured_cate_per_xs'       => (int)Configuration::get('STSN_FEATURED_CATE_PER_XS_0'),
            'featured_cate_per_xxs'       => (int)Configuration::get('STSN_FEATURED_CATE_PER_XXS_0'),
        ));
        return true;
    }
    
	public function hookDisplayHome($params)
	{
	    if (!$this->isCached('stfeaturedcategories.tpl', $this->getCacheId('stfeaturedcategories')))
    	    if(!$this->_prepareHook())
                return false;
		return $this->display(__FILE__, 'stfeaturedcategories.tpl', $this->getCacheId('stfeaturedcategories'));
	}
    
    private function clearstfeaturedcategoryCache()
    {
        $this->_clearCache('stfeaturedcategories.tpl');
    }
    
	public function hookDisplayHomeSecondaryLeft($params)
	{
        return $this->hookDisplayHome($params); 
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
    
    private function autoBuildingSubcate($id_category,$id_st_featured_category)
    {
        if (!$id_category)
            return false;
        
        $id_lang    = Context::getContext()->language->id;
        $id_shop    = (int)Shop::getContextShopID();
        $subcates   = Category::getChildren($id_category, $id_lang, false);
        $parent     = StFeaturedCategoriesClass::getById($id_st_featured_category);
        
        if (!$id_shop)
            return false;
        
        $ret = true;
        $maximum = StFeaturedCategoriesClass::getMaximumPosition((int)$id_st_featured_category);
        foreach($subcates AS $k => $sub)
        {
            // If sub is exists, skip it.
            $exists = Db::getInstance()->getValue('
            SELECT COUNT(0) FROM '._DB_PREFIX_.'st_featured_category
            WHERE id_parent = '.$id_st_featured_category.'
            AND id_category = '.(int)$sub['id_category'].'
            ');
            if ($exists)
                continue;
            
            $category   = new StFeaturedCategoriesClass();
            $category->id_parent = (int)$id_st_featured_category;
            $category->level_depth = $parent['level_depth']+1;
            $category->id_shop = $id_shop;
            $category->id_category = (int)$sub['id_category'];
            $category->position = $maximum+$k;
            $category->active = 1;
            $category->auto_sub = 1;
            
            $ret &= $category->save();
        }
        
        return $ret;
    }
    private function getConfigFieldsValues()
    {
        $fields_values = array(
            'f_c_number' => Configuration::get('ST_PRO_CATE_F_C_NUMBER'),
            'f_c_image' => Configuration::get('ST_PRO_CATE_F_C_IMAGE'),
            
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
                'id' => 'featured_cate_per_lg_0',
                'label' => $this->l('Large devices'),
                'tooltip' => $this->l('Desktops (>1200px)'),
            ),
            array(
                'id' => 'featured_cate_per_md_0',
                'label' => $this->l('Medium devices'),
                'tooltip' => $this->l('Desktops (>992px)'),
            ),
            array(
                'id' => 'featured_cate_per_sm_0',
                'label' => $this->l('Small devices'),
                'tooltip' => $this->l('Tablets (>768px)'),
            ),
            array(
                'id' => 'featured_cate_per_xs_0',
                'label' => $this->l('Extra small devices'),
                'tooltip' => $this->l('Phones (>480px)'),
            ),
            array(
                'id' => 'featured_cate_per_xxs_0',
                'label' => $this->l('Extra extra small devices'),
                'tooltip' => $this->l('Phones (<480px)'),
            ),
        );
    }
}
