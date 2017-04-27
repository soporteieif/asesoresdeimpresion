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

include_once(dirname(__FILE__).'/StEasyTabsClass.php');
class StEasyTabs extends Module
{
    const INSTALL_SQL_FILE = 'install.sql';
	private $_html = '';
    public  $fields_list;
    public  $fields_value;
    public  $fields_form;
	private $spacer_size = '5';

	public function __construct()
	{
		$this->name          = 'steasytabs';
		$this->tab           = 'front_office_features';
		$this->version       = '1.1.9';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
		$this->bootstrap 	 = true;
		parent::__construct();

		$this->displayName   = $this->l('Product Extra Tabs');
		$this->description   = $this->l('Through the module, you can add as many tabs as you need. Its the perfect module, if you have to add mass additional information about your products, shipment methods or some other information.');
	}

	public function install()
	{
	   if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
			return false;
		else if (!$sql = file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
			return false;

		$sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
		$sql = preg_split("/;\s*[\r\n]+/", trim($sql));

		foreach ($sql as $query)
			if (!Db::getInstance()->execute(trim($query)))
				return false;   
        
        $res = parent::install() &&
			$this->registerHook('displayHeader')  &&
			$this->registerHook('productTab')  &&
			$this->registerHook('productTabContent')  &&
			$this->registerHook('displayAdminProductsExtra')  &&
			$this->registerHook('actionProductUpdate')  &&
			$this->registerHook('actionProductAdd')  &&
			$this->registerHook('actionProductDelete')  &&
			$this->registerHook('ActionCategoryDelete');
        
		if ($res)
			foreach(Shop::getShops(false) as $shop)
				$res &= $this->sampleData($shop['id_shop']);
        return $res;
	}

    public function sampleData($id_shop)
    {
        $return = true;
		$samples = array(
			array('allitems' => 1, 'title' => 'Custom tab', 'content' => '<p>This is a custom block edited from admin panel.You can insert any content here.</p>'),
		);
		
		foreach($samples as $k=>$sample)
		{
			$module = new StEasyTabsClass();
			foreach (Language::getLanguages(false) as $lang)
            {
				$module->title[$lang['id_lang']] = $sample['title'];
				$module->content[$lang['id_lang']] = $sample['content'];
            }
			$module->allitems = $sample['allitems'];
			$module->active = 1;
			$module->position = $k;
			$module->id_shop = (int)$id_shop;
			$return &= $module->add();
		}
		return $return;
    }

	public function uninstall()
	{
		if (!parent::uninstall() || 
            !$this->deleteTables()
        )
			return false;
		return true;
	}

    private function deleteTables()
	{
		return Db::getInstance()->execute('
			DROP TABLE IF EXISTS
			`'._DB_PREFIX_.'st_easy_tabs`,
			`'._DB_PREFIX_.'st_easy_tabs_lang`');
	}

    public function hookDisplayHeader()
    {
		$this->context->controller->addJS($this->_path.'views/js/steasytabs.js');
    }
    
    public function hookProductTab($params)
	{
        if (!$id_product = (int)Tools::getValue('id_product'))
            return false;
		$product = new Product($id_product);
		$id_category = $product->id_category_default;
        $id_manufacturer = $product->id_manufacturer;
        
        $tabsHeader = StEasyTabsClass::getTabs('title',$id_product,$id_category,$id_manufacturer,$this->context->language->id);
        
		$this->smarty->assign(array(
            'tabsHeader' => $tabsHeader,
		));
		return ($this->display(__FILE__, 'tab.tpl'));
	}

    public function hookProductTabContent($params)
    {
        if (!$id_product = (int)Tools::getValue('id_product'))
            return false;
        
		$product = new Product($id_product);
		$id_category = $product->id_category_default;
        $id_manufacturer = $product->id_manufacturer;
        
        $tabsContent = StEasyTabsClass::getTabs('title,content',$id_product,$id_category,$id_manufacturer,$this->context->language->id);
        
		$this->smarty->assign(array(
            'tabsContent' => $tabsContent,
		));
		return ($this->display(__FILE__, 'steasytabs.tpl'));
	}
    public function hookDisplayAdminProductsExtra($params)
    {
	    $id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $languages = Language::getLanguages(false);
        $id_st_easy_tabs = StEasyTabsClass::getIdBySpecific((int)Tools::getValue('id_product'));
		$easytabs = new StEasyTabsClass((int)$id_st_easy_tabs);
		$iso_tiny_mce = $this->context->language->iso_code;
		$iso_tiny_mce = (file_exists(_PS_JS_DIR_.'tiny_mce/langs/'.$iso_tiny_mce.'.js') ? $iso_tiny_mce : 'en');
        
        $this->smarty->assign(array(
            'steasytabs' => $easytabs,
			'languages' => $this->getLanguages(),
            'default_form_language'=> $this->context->controller->default_form_language,
			'ad' => dirname($_SERVER['PHP_SELF']),
			'iso_tiny_mce' => $iso_tiny_mce,
        ));
        return $this->display(__FILE__, 'views/templates/admin/steasytabs.tpl');
    }
    public function getLanguages()
	{
		$cookie = $this->context->cookie;
		$allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		if ($allow_employee_form_lang && !$cookie->employee_form_lang)
			$cookie->employee_form_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		$use_lang_from_cookie = false;
		$languages = Language::getLanguages(false);
		if ($allow_employee_form_lang)
			foreach ($languages as $lang)
				if ($cookie->employee_form_lang == $lang['id_lang'])
					$use_lang_from_cookie = true;
		if (!$use_lang_from_cookie)
			$default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
		else
			$default_form_language = (int)$cookie->employee_form_lang;

		foreach ($languages as $k => $language)
			$languages[$k]['is_default'] = (int)($language['id_lang'] == $default_form_language);

		return $languages;
	}
    
    public function hookActionProductUpdate($params)
    {
	    $id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $languages = Language::getLanguages(false);
        $id_st_easy_tabs = StEasyTabsClass::getIdBySpecific((int)Tools::getValue('id_product'));
		$easytabs = new StEasyTabsClass((int)$id_st_easy_tabs);
        
		$easytabs->id_product_specific = (int)Tools::getValue('id_product');
		$easytabs->active = (int)Tools::getValue('easytabs_active');
        $easytabs->id_shop = (int)$this->context->shop->id;
        
        $defaultLanguage = new Language($id_lang);
        
        if (!isset($_POST['easytabs_active']))
            return ;
	
        $is_blank_lang = true;
        foreach ($languages as $lang)
		{
		    if (!empty($_POST['easytabs_title_'.$lang['id_lang']]))
		      $easytabs->title[(int)$lang['id_lang']] = Tools::getValue('easytabs_title_'.(int)$lang['id_lang']);
    		else
		      $easytabs->title[(int)$lang['id_lang']] = Tools::getValue('easytabs_title_'.$id_lang);
              
		    if (!empty($_POST['easytabs_content_'.$lang['id_lang']]))
			  $easytabs->content[(int)$lang['id_lang']] = Tools::getValue('easytabs_content_'.(int)$lang['id_lang']);
    		else
			  $easytabs->content[(int)$lang['id_lang']] = Tools::getValue('easytabs_content_'.$id_lang);
            $is_blank_lang &= empty($easytabs->title[(int)$lang['id_lang']]) && empty($easytabs->content[(int)$lang['id_lang']]);
                
		}
        
        if ($is_blank_lang)
        {
            if ($easytabs->id)
                $easytabs->delete();
            return ;
        }
        
		if (!$easytabs->save())
		     $params['errors'][] = $this->displayError($this->l('An error occurred during easy tabs updating'));
        return ;
    }
    public function hookActionProductAdd($params)
    {
        return $this->hookActionProductUpdate($params);
    }
    public function hookActionProductDelete($params)
    {
        $res = true;
        $id_st_easy_tabs = StEasyTabsClass::getIdBySpecific($params['product']->id);
        if($id_st_easy_tabs)
		{
    		$easytabs = new StEasyTabsClass((int)$id_st_easy_tabs);
    		if ($easytabs->id)
                $res &= $easytabs->delete();
		}
        $res &= StEasyTabsClass::deleteByProductId($params['product']->id);
        return ;
    }
    
    public function hookActionCategoryDelete($params)
    {
        if(!$params['category']->id)
            return ;
        return StEasyTabsClass::deleteByCategoryId($params['category']->id);
    }
    
    public function getContent()
    {
        $id_st_easy_tabs = (int)Tools::getValue('id_st_easy_tabs');
	    if ((Tools::isSubmit('statussteasytabs')))
        {
            $easy_tabs = new StEasyTabsClass((int)$id_st_easy_tabs);
            if($easy_tabs->id && $easy_tabs->toggleStatus())
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));  
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
		if (isset($_POST['savesteasytabs']) || isset($_POST['savesteasytabsAndStay']))
		{
			if ($id_st_easy_tabs)
				$easy_tabs = new StEasyTabsClass((int)$id_st_easy_tabs);
			else
				$easy_tabs = new StEasyTabsClass();
            
			$easy_tabs->copyFromPost(); 
            $easy_tabs->id_shop = (int)Shop::getContextShopID();
            
            $error = array();
            $defaultLanguage = new Language((int)(Configuration::get('PS_LANG_DEFAULT')));
            if(!$easy_tabs->title[$defaultLanguage->id])
                $error[] = $this->displayError($this->l('The field "Title" is required at least in '.$defaultLanguage->name));
            
            if($easy_tabs->id_category)
            {
                $item_arr = explode('-',$easy_tabs->id_category); 
                if(count($item_arr)==2)
                {
                    $easy_tabs->id_category = 0;
                    $easy_tabs->id_product = 0;
                    $easy_tabs->allitems = 0;
                    $easy_tabs->id_manufacturer = 0;
                    if($item_arr[0]==1)
                        $easy_tabs->id_product = (int)$item_arr[1];
                    elseif($item_arr[0]==2)
                        $easy_tabs->id_category = (int)$item_arr[1];
                    elseif($item_arr[0]==3)
                        $easy_tabs->allitems = (int)$item_arr[1];
                    elseif($item_arr[0]==4)
                        $easy_tabs->id_manufacturer = (int)$item_arr[1];
                }
                else
                    $error[] = $this->displayError($this->l('"Apply to" is required'));
            }  
            else
                $error[] = $this->displayError($this->l('"Apply to" is required'));

			if (!count($error) && $easy_tabs->validateFields(false) && $easy_tabs->validateFieldsLang(false))
            {
                if($easy_tabs->save())
                    if(isset($_POST['savesteasytabsAndStay']))
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_easy_tabs='.$easy_tabs->id.'&conf='.($id_st_easy_tabs?4:3).'&update'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));    
                    else
                        $this->_html .= $this->displayConfirmation($this->l('Tab').' '.($id_st_easy_tabs ? $this->l('updated') : $this->l('added')));
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during tab').' '.($id_st_easy_tabs ? $this->l('updating') : $this->l('creation')));
            }
			else
				$this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
		}
		
		if (Tools::isSubmit('updatesteasytabs') || Tools::isSubmit('addsteasytabs'))
		{
		    $this->context->controller->addJS($this->_path.'views/js/admin.js');
			$helper = $this->initForm();
			return $this->_html.$helper->generateForm($this->fields_form);
		}
		else if (Tools::isSubmit('deletesteasytabs'))
		{
			$easytabs = new StEasyTabsClass((int)$id_st_easy_tabs);
			if ($easytabs->id)
                $easytabs->delete();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else
		{
			$helper = $this->initList();
			return $this->_html.$helper->generateList(StEasyTabsClass::getListContent((int)Configuration::get('PS_LANG_DEFAULT')), $this->fields_list);
		}
    }
    

    public static function getApplyTo($check_id_product)
    {
        
        $module = new StEasyTabs();
        
        $products = array(
            array('id'=>'3-1','name'=>$module->l('All products')),
        );
        if($check_id_product)
            $products[] = array('id'=>'1-'.$check_id_product,'name'=>$module->l('Product ID').' '.$check_id_product);
        else
            $products[] = array('id'=>'PRODUCT','name'=>$module->l('Choose ID product'));
            
        $root_category = Category::getRootCategory();
        $category_arr = array();
        $module->getCategoryOption($category_arr,$root_category->id);
        
        $manufacturer_arr = array();
		$manufacturers = Manufacturer::getManufacturers(false, Context::getContext()->language->id);
		foreach ($manufacturers as $manufacturer)
            $manufacturer_arr[] = array('id'=>'4-'.$manufacturer['id_manufacturer'],'name'=>$manufacturer['name']);
        
        return array(
            array('name'=>$module->l('Products'),'query'=>$products),
            array('name'=>$module->l('Categories'),'query'=>$category_arr),
            array('name'=>$module->l('Manufacturer'),'query'=>$manufacturer_arr),
        );
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
            'id' => '2-'.$category->id,
            'name' => (isset($spacer) ? $spacer : '').$category->name.' ('.$shop->name.')',
        );
        
		if (isset($children) && count($children))
			foreach ($children as $child)
			{
				$this->getCategoryOption($category_arr,(int)$child['id_category'], (int)$id_lang, (int)$child['id_shop'],$recursive,$selected_id_category);
			}
	}
    
	protected function initForm()
	{
	   
        $id_st_easy_tabs = (int)Tools::getValue('id_st_easy_tabs');
		$easy_tabs = new StEasyTabsClass($id_st_easy_tabs);
        $check_id_product = ($easy_tabs->id && $easy_tabs->id_product) ? $easy_tabs->id_product : 0;
        
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Easy Tabs'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
				array(
					'type' => 'select',
					'label' => $this->l('Where to display:'),
					'name' => 'id_category',
					'onchange' => 'handle_apply_to(this,\''.$this->l('Set ID product').'\',\''.$this->l('Product ID').'\',\''.$this->l('Choose ID product').'\');',
                    'required' => true,
					'options' => array(
                        'optiongroup' => array (
							'query' => $this->getApplyTo($check_id_product),
							'label' => 'name'
						),
						'options' => array (
							'query' => 'query',
							'id' => 'id',
							'name' => 'name'
						),
						'default' => array(
							'value' => 0,
							'label' => $this->l('Please select')
						),
					),
				),
                array(
					'type' => 'text',
					'label' => $this->l('Title:'),
					'name' => 'title',
					'lang' => true,
					'size' => 64,
                    'required' => true,
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Content:'),
					'lang' => true,
					'name' => 'content',
					'cols' => 40,
					'rows' => 10,
					'autoload_rte' => true,
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
        if($easy_tabs->id)
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_easy_tabs');
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savesteasytabs';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($easy_tabs),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
        if($easy_tabs->id){
            $helper->tpl_vars['fields_value']['id_category'] = $easy_tabs->id_category ? '2-'.$easy_tabs->id_category : ($easy_tabs->id_product ? '1-'.$easy_tabs->id_product : ($easy_tabs->id_manufacturer ? '4-'.$easy_tabs->id_manufacturer :'3-1'));  
        }
		return $helper;
	}
    
    public static function displayCategory($value, $row)
	{
	    if($row['allitems'])
        {
            $classInstance = new StEasyTabs();
            return $classInstance->l('All categories');
        }
        if(!$value)
            return '-';
        $id_lang = (int)Context::getContext()->language->id;
        $category = new Category((int)$value,$id_lang);
        if($category->id)
            return $category->name;
		return '';
	}
    public static function displayProduct($value, $row)
	{
	    if($row['allitems'])
        {
            $classInstance = new StEasyTabs();
            return $classInstance->l('All products');
        }  
        if(!$value)
            return '-';
        $id_lang = (int)Context::getContext()->language->id;
        $product = new Product((int)$value, true, (int)$id_lang);
        if ($product->id)
            return $product->name;
		return '';
	}
    public static function displayManufacturer($value, $row)
	{
	    if($row['allitems'])
        {
            $classInstance = new StEasyTabs();
            return $classInstance->l('All manufacturer');
        }
        if(!$value)
            return '-';
        $id_lang = (int)Context::getContext()->language->id;
        $manufacturer = Manufacturer::getNameById((int)$value);
		return (string)$manufacturer;
	}
    protected function initList()
	{
        $this->fields_list = array(
			'id_st_easy_tabs' => array(
				'title' => $this->l('Id'),
				'class' => 'fixed-width-md',
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'title' => array(
				'title' => $this->l('Title'),
				'class' => 'fixed-width-lg',
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'id_category' => array(
				'title' => $this->l('Category'),
				'class' => 'fixed-width-xl',
				'type' => 'text',
				'callback' => 'displayCategory',
				'callback_object' => 'StEasyTabs',
                'search' => false,
                'orderby' => false
			),
			'id_product' => array(
				'title' => $this->l('Product'),
				'class' => 'fixed-width-xl',
				'type' => 'text',
				'callback' => 'displayProduct',
				'callback_object' => 'StEasyTabs',
                'search' => false,
                'orderby' => false
			),
            'id_manufacturer' => array(
				'title' => $this->l('Manufacturer'),
				'class' => 'fixed-width-xl',
				'type' => 'text',
				'callback' => 'displayManufacturer',
				'callback_object' => 'StEasyTabs',
                'search' => false,
                'orderby' => false
			),
            'active' => array(
				'title' => $this->l('Status'),
				'align' => 'center',
				'active' => 'status',
				'type' => 'bool',
				'class' => 'fixed-width-md',
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
		$helper->identifier = 'id_st_easy_tabs';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&add'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add new')
		);

		$helper->title = $this->displayName;
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
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
}