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

class BlockLanguages_mod extends Module
{
    private $_html = '';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();
    public  $hooks = array(
        array('id' =>'0-1', 'hook' => 'displayNav'),
        array('id' =>'2',   'hook' => 'displayTop'),
        array('id' =>'3',   'hook' => 'displayTopLeft')
    );
    
	public function __construct()
	{
		$this->name = 'blocklanguages_mod';
		$this->tab = 'front_office_features';
		$this->version = '1.3.3';
		$this->author = 'SUNNYTOO.COM';
		$this->need_instance = 0;
		$this->bootstrap     = true;

		parent::__construct();

		$this->displayName = $this->l('Language selector block mod');
		$this->description = $this->l('Adds a block allowing customers to select a language for your store\'s content.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		return (parent::install() 
            && $this->registerHook('displayNav')
			&& $this->registerHook('displaySideBar')
			&& Configuration::updateValue('ST_LANGUAGES_FLAGS', 1)
            && Configuration::updateValue('ST_LANGUAGES_POSITION', 0)
			&& Configuration::updateValue('ST_LANGUAGES_STYLE', 0)
		);
	}

    public function getContent()
	{
	    $this->initFieldsForm();
		if (isset($_POST['saveblocklanguages_mod']))
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
                                default:
                                    $value = '';
                                break;
                            }
                            Configuration::updateValue('ST_'.strtoupper($field['name']), $value);
                        }
                        else
                            Configuration::updateValue('ST_'.strtoupper($field['name']), $value);
                    }
            $this->prepareHooks((int)Configuration::get('ST_LANGUAGES_POSITION'));
            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else 
                $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
        }

		$helper = $this->initForm();
		return $this->_html.$helper->generateForm($this->fields_form);
	}

    protected function initFieldsForm()
    {
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->displayName,
                'icon' => 'icon-cogs'
			),
            'input' => array(
                array(
                    'type' => 'radio',
                    'label' => $this->l('Language label:'),
                    'name' => 'languages_flags',
                    'default_value' => 1,
                    'values' => array(
                        array(
                            'id' => 'languages_flags_both',
                            'value' => 0,
                            'label' => $this->l('Flag + name')),
                        array(
                            'id' => 'languages_flags_name',
                            'value' => 1,
                            'label' => $this->l('Name')),
                        array(
                            'id' => 'languages_flags_flag',
                            'value' => 2,
                            'label' => $this->l('Flag')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('How to display:'),
                    'name' => 'languages_style',
                    'default_value' => 1,
                    'values' => array(
                        array(
                            'id' => 'languages_style_0',
                            'value' => 0,
                            'label' => $this->l('A drop-down list')),
                        array(
                            'id' => 'languages_style_1',
                            'value' => 1,
                            'label' => $this->l('Buttons')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Show on:'),
                    'name' => 'languages_position',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'languages_position_nav_right',
                            'value' => 1,
                            'label' => $this->l('Right side of the topbar')),
                        array(
                            'id' => 'languages_position_nav_left',
                            'value' => 0,
                            'label' => $this->l('Left side of the topbar')),
                        array(
                            'id' => 'languages_position_top_right',
                            'value' => 2,
                            'label' => $this->l('Right side of the top')),
                        array(
                            'id' => 'languages_position_top_left',
                            'value' => 3,
                            'label' => $this->l('Left side of the top')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
			),
			'submit' => array(
				'title' => $this->l('   Save   ')
			)
		);
        
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
		$helper->submit_action = 'saveblocklanguages_mod';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper;
	}

    private function getConfigFieldsValues()
    {
        $posi = 0;
	    foreach($this->hooks AS $hook)
        {
            if($id_hook = Hook::getIdByName($hook['hook']))
                if(Hook::getModulesFromHook($id_hook, $this->id))
                {
                    if ($hook['hook'] != 'displayNav')
                        $posi = $hook['id'];
                    elseif(Configuration::get('ST_LANGUAGES_POSITION') > 1)
                        Configuration::updateValue('ST_LANGUAGES_POSITION', 0);
                    elseif (Configuration::get('ST_LANGUAGES_POSITION'))
                        $posi = 1;
                }
        }
        $fields_values = array(
            'languages_flags' => Configuration::get('ST_LANGUAGES_FLAGS'),
            'languages_style' => Configuration::get('ST_LANGUAGES_STYLE'),
            'languages_position' => $posi,
        );
        return $fields_values;
    }
	private function _prepareHook($params)
	{
		$languages = Language::getLanguages(true, $this->context->shop->id);
		if (!count($languages))
			return false;
		$link = new Link();

		if ((int)Configuration::get('PS_REWRITING_SETTINGS'))
		{
			$default_rewrite = array();
			if (Dispatcher::getInstance()->getController() == 'product' && ($id_product = (int)Tools::getValue('id_product')))
			{
				$rewrite_infos = Product::getUrlRewriteInformations((int)$id_product);
				foreach ($rewrite_infos as $infos)
					$default_rewrite[$infos['id_lang']] = $link->getProductLink((int)$id_product, $infos['link_rewrite'], $infos['category_rewrite'], $infos['ean13'], (int)$infos['id_lang']);
			}

			if (Dispatcher::getInstance()->getController() == 'category' && ($id_category = (int)Tools::getValue('id_category')))
			{
				$rewrite_infos = Category::getUrlRewriteInformations((int)$id_category);
				foreach ($rewrite_infos as $infos)
					$default_rewrite[$infos['id_lang']] = $link->getCategoryLink((int)$id_category, $infos['link_rewrite'], $infos['id_lang']);
			}

			if (Dispatcher::getInstance()->getController() == 'cms' && (($id_cms = (int)Tools::getValue('id_cms')) || ($id_cms_category = (int)Tools::getValue('id_cms_category'))))
			{
				$rewrite_infos = (isset($id_cms) && !isset($id_cms_category)) ? CMS::getUrlRewriteInformations($id_cms) : CMSCategory::getUrlRewriteInformations($id_cms_category);
				foreach ($rewrite_infos as $infos)
				{
					$arr_link = (isset($id_cms) && !isset($id_cms_category)) ?
						$link->getCMSLink($id_cms, $infos['link_rewrite'], null, $infos['id_lang']) :
						$link->getCMSCategoryLink($id_cms_category, $infos['link_rewrite'], $infos['id_lang']);
					$default_rewrite[$infos['id_lang']] = $arr_link;
				}
			}

			$module_name = Validate::isModuleName(Tools::getValue('module')) ? Tools::getValue('module') : '';
                
			if (Tools::getValue('fc') == 'module' && $module_name=='stblog' && Dispatcher::getInstance()->getController() == 'article' && ($id_st_blog = (int)Tools::getValue('id_blog')))
			{
				$rewrite_infos = StBlogClass::getUrlRewriteInformations((int)$id_st_blog);
				foreach ($rewrite_infos as $infos)
					$default_rewrite[$infos['id_lang']] = $link->getModuleLink('stblog', 'article', array('id_blog'=>$id_st_blog,'rewrite'=>$infos['link_rewrite']),null,$infos['id_lang']);
			}
			if (Tools::getValue('fc') == 'module' && $module_name=='stblog' &&  Dispatcher::getInstance()->getController() == 'category' && ($id_st_blog_category = (int)Tools::getValue('blog_id_category')))
			{
				$rewrite_infos = StBlogCategory::getUrlRewriteInformations((int)$id_st_blog_category);
				foreach ($rewrite_infos as $infos)
					$default_rewrite[$infos['id_lang']] = $link->getModuleLink('stblog', 'category', array('blog_id_category'=>$id_st_blog_category,'rewrite'=>$infos['link_rewrite']),null,$infos['id_lang']);
			}
			if (Tools::getValue('fc') == 'module' && $module_name=='stblogarchives' &&  Dispatcher::getInstance()->getController() == 'default' && ($m = (int)Tools::getValue('m')))
			{
				foreach ($languages as $language)
					$default_rewrite[$language['id_lang']] = $link->getModuleLink('stblogarchives', 'default', array('m'=>Tools::getValue('m')),null,$language['id_lang']);
			}

			$this->smarty->assign(array(
				'lang_rewrite_urls' => $default_rewrite,
			));
		}
		$this->smarty->assign(array(
            'display_flags' => Configuration::get('ST_LANGUAGES_FLAGS'),
			'languages_style' => Configuration::get('ST_LANGUAGES_STYLE'),
			'languages_position' => Configuration::get('ST_LANGUAGES_POSITION'),
		));
		return true;
	}

	/**
	* Returns module content for header
	*
	* @param array $params Parameters
	* @return string Content
	*/
	public function hookDisplayTop($params)
	{
		if (!$this->_prepareHook($params))
			return;
		return $this->display(__FILE__, 'blocklanguages.tpl');
	}
	public function hookDisplayTopLeft($params)
	{
		return $this->hookDisplayTop($params);
	}

    public function hookDisplayNav($params)
    {
        if (!$this->_prepareHook($params))
            return;
        $this->smarty->assign(array(
            'istopbar' => true,
        ));
        return $this->display(__FILE__, 'blocklanguages.tpl');
    }
	public function hookDisplaySideBar($params)
	{
        if ($this->_prepareHook($params))
            return $this->display(__FILE__, 'blocklanguages-mobile.tpl');
	}
    
    public function prepareHooks($val = 0)
    {
        foreach($this->hooks AS $hook)
        {
            $id_hook = Hook::getIdByName($hook['hook']);
            $val_array = explode('-', $hook['id']);
            if ($id_hook && count($val_array) && in_array($val, $val_array))
            {
                if ($id_hook && Hook::getModulesFromHook($id_hook, $this->id))
                    continue;
                if (!$this->isHookableOn($hook['hook']))
                    $this->validation_errors[] = $this->l('This module cannot be transplanted to '.$hook['hook'].'.');
                else
                    $this->registerHook($hook['hook'], Shop::getContextListShopID());
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
        Cache::clean('hook_module_list');
    }

}


