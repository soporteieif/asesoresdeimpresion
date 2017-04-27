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
    
require (dirname(__FILE__).'/classes/StBlogCommentClass.php');

class StBlogComments extends Module
{
    private $_html = '';
    protected $secure_key;
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();
    
    private static $_bo_menu = array(
        array(
            'class_name' => 'AdminStBlogComment',
            'name' => 'St Blog Comments',
            'tab' => 'AdminParentModules',
        ),
    );
    
	public function __construct()
	{
		$this->name          = 'stblogcomments';
		$this->tab           = 'front_office_features';
		$this->version       = '1.0';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
        $this->bootstrap	 = true;
		
		$this->secure_key = Tools::encrypt($this->name);
		parent::__construct();
		
        $this->displayName = $this->l('Blog Module - Comments');
        $this->description = $this->l('Allows users to post comments.');
	}

	public function install()
	{
		if (!$this->installDB()
            || !$this->installTab()
            || !parent::install() 
			|| !$this->registerHook('displayStBlogLeftColumn')
			|| !$this->registerHook('displayStBlogRightColumn')
			|| !$this->registerHook('displayHeader')
			|| !$this->registerHook('displayStBlogArticleSecondary')
            || !$this->registerHook('displayAnywhere')
            || !$this->registerHook('displayCustomerAccount')
			|| !Configuration::updateValue('ST_BLOG_C_MODERATE', 1)
			|| !Configuration::updateValue('ST_BLOG_C_MINIMAL_TIME', 30)
			|| !Configuration::updateValue('ST_BLOG_C_ALLOW_GUESTS', 0)
			|| !Configuration::updateValue('ST_BLOG_C_COLUMN_NBR', 4)
        )
			return false;
		return true;
	}
    public function installDb()
	{
		$return = true;
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_comment` (
            `id_st_blog_comment` int(10) unsigned NOT NULL auto_increment,
            `id_parent` int(10) unsigned NOT NULL DEFAULT 0,
            `id_st_blog` int(10) UNSIGNED NOT NULL,
            `id_shop` int(10) UNSIGNED NOT NULL,
            `id_customer` int(10) unsigned NOT NULL DEFAULT 0,
            `id_guest` int(10) unsigned DEFAULT NULL,
            `customer_name` varchar(64) NULL,
            `customer_email` varchar(64) NULL,         
            `content` text NOT NULL,                               
            `customer_website` varchar(128) DEFAULT NULL,  
            `active` tinyint(1) NOT NULL DEFAULT 0,
            `deleted` tinyint(1) NOT NULL,
            `date_add` datetime NOT NULL,
            PRIMARY KEY (`id_st_blog_comment`),
            KEY `id_st_blog` (`id_st_blog`),
            KEY `id_customer` (`id_customer`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		return $return;
	}

	public function uninstall()
	{
		if (!parent::uninstall()
			|| !$this->uninstallDB()
            || !$this->uninstallTab()
        )
			return false;
		return true;
	}

	private function uninstallDb()
	{
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_blog_comment`');
	}
    
	public function installTab()
	{
	    $result = true;
	    foreach(self::$_bo_menu as $v)
        {
            $tab = new Tab();
    		$tab->active = 1;
    		$tab->class_name = $v['class_name'];
    		$tab->name = array();
    		foreach (Language::getLanguages(true) as $lang)
    			$tab->name[$lang['id_lang']] = $v['name'];
    		$tab->id_parent = (int)Tab::getIdFromClassName($v['tab']);
    		$tab->module = $this->name;
    		$result &= $tab->add();
        }
		return $result;
	}
	
	public function uninstallTab()
	{
	    $result = true;
        
	    foreach(self::$_bo_menu as $v)
        {
                
    		$id_tab = (int)Tab::getIdFromClassName($v['class_name']);
    		if ($id_tab)
    		{
    			$tab = new Tab($id_tab);
    			$result &= $tab->delete();
    		}
            else
                $result = false;
        }
		return $result;
	}
    
    
    public function getContent()
	{
	    if(!Module::isInstalled('stblog'))
            $this->_html .= $this->displayConfirmation($this->l('Please, install Blog module first.'));
	    if(!Module::isEnabled('stblog'))
            $this->_html .= $this->displayConfirmation($this->l('Please, enable Blog module first.'));
	    $this->initFieldsForm();
		if (isset($_POST['savestblogcomments']))
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
                            Configuration::updateValue('ST_BLOG_C_'.strtoupper($field['name']), $value);
                        }
                        else
                            Configuration::updateValue('ST_BLOG_C_'.strtoupper($field['name']), $value);
                    }
                  
             // Upload avatar
             if (isset($_FILES['default_avatar']) && $_FILES['default_avatar']['tmp_name'])
             {
                 $comment = new StBlogCommentClass();
                 if (true !== $comment->uploadAvatar('default_avatar'))
                    $this->validation_errors[] = $this->l('Upload avatar failed.');
                    
                 // Redirect
                 if(!count($this->validation_errors))
                 {
                     $current_index = 'index.php'.(($controller = Tools::getValue('controller')) ? '?controller='.$controller : '').'&configure='.$this->name.'&module_name='.$this->name.'&conf=4&tab_module='.$this->tab.'&token='.Tools::getValue('token');
                     Tools::redirectAdmin($current_index);   
                 }
             }
            
            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else 
                $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
        }
        if (Tools::isSubmit('deleteavatar'))
        {
            if ($avatar = StBlogCommentClass::getAvatarDefault('large'))
                @unlink(_PS_ROOT_DIR_.$avatar);
            if ($avatar = StBlogCommentClass::getAvatarDefault('small'))
                @unlink(_PS_ROOT_DIR_.$avatar);
            if ($avatar = StBlogCommentClass::getAvatarDefault(''))
                @unlink(_PS_ROOT_DIR_.$avatar);
            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=7&token='.Tools::getAdminTokenLite('AdminModules'));
        }
		$helper = $this->initForm();
        if ($avatar = StBlogCommentClass::getAvatarDefault('large'))
        {
            $this->fields_form[0]['form']['input']['avatar']['image'] = '<img src="'.$avatar.'" />';
            $this->fields_form[0]['form']['input']['avatar']['delete_url'] = AdminController::$currentIndex.'&configure='.$this->name.'&deleteavatar&token='.Tools::getAdminTokenLite('AdminModules');
        }
        $this->context->smarty->assign('object', new StBlogCommentClass());
        $this->context->smarty->assign('base_uri', _PS_BASE_URL_);
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
					'type' => 'switch',
					'label' => $this->l('All comments must be validated by an employee:'),
					'name' => 'moderate',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'moderate_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'moderate_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Allow guest comments:'),
					'name' => 'allow_guests',
					'is_bool' => true,
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'allow_guests_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'allow_guests_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				),
                array(
					'type' => 'text',
					'label' => $this->l('Seconds:'),
					'name' => 'minimal_time',
					'desc' => $this->l('Minimum time between 2 comments from the same user.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Define the number of latest comments to be displayed:'),
					'name' => 'column_nbr',
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),

                'avatar' => array(
					'type' => 'file',
					'label' => $this->l('Default avatar:'),
					'name' => 'default_avatar',
					'desc' => $this->l('The image will be default avatar when no upload.'),
				),
			),
			'submit' => array(
				'title' => $this->l('   Save   '),
			)
		);
        
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
		$helper->submit_action = 'savestblogcomments';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper;
	}
    
	public function hookDisplayStBlogLeftColumn($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
            
        $nbr = (int)Configuration::get('ST_BLOG_C_COLUMN_NBR');
        $latest_comments = StBlogCommentClass::getLatestComments($nbr,$this->context->language->id,$this->context->shop->id);
            
        $this->smarty->assign(array(   
            'latest_comments' => $latest_comments, 
		));
            
		return $this->display(__FILE__, 'stblogcomments-column.tpl');
	}
	public function hookDisplayStBlogRightColumn($params)
	{
        return $this->hookDisplayStBlogLeftColumn($params); 
    }
    
	public function hookDisplayLeftColumn($params)
	{
        return $this->hookDisplayStBlogLeftColumn($params); 
	}
	public function hookDisplayRightColumn($params)
	{
        return $this->hookDisplayStBlogLeftColumn($params); 
	}
    public function hookDisplayStBlogArticleSecondary($params)
    {
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
            
        $id_st_blog = (int)Tools::getValue('id_blog');
        if (!StBlogCommentClass::blogAcceptComment($id_st_blog))
            return false;
		$id_guest = (!$id_customer = (int)$this->context->cookie->id_customer) ? (int)$this->context->cookie->id_guest : false;
		$customerComment = StBlogCommentClass::getBlogLatestCommentByCustomer((int)(Tools::getValue('id_blog')), (int)$this->context->cookie->id_customer, (int)$id_guest);
		$moderate = (int)Configuration::get('ST_BLOG_C_MODERATE');
        
		$this->smarty->assign(array(
			'logged' => (int)$this->context->customer->isLogged(true),
			'comments' => StBlogCommentClass::getByBlogRec($id_st_blog, 0, $this->context->shop->id),
			'blogcomments_path' => $this->_path,
			'allow_guests' => (int)Configuration::get('ST_BLOG_C_ALLOW_GUESTS'),
			'too_early' => ($customerComment && (strtotime($customerComment['date_add']) + Configuration::get('ST_BLOG_C_MINIMAL_TIME')) > time()),
			'delay' => Configuration::get('ST_BLOG_C_MINIMAL_TIME'),
			'id_blog_comment_form' => $id_st_blog,
			'secure_key' => $this->secure_key,
			'nbComments' => (int)StBlogCommentClass::countComments($id_st_blog,$this->context->shop->id,$moderate),
            'moderate' => $moderate,            
		));

		return $this->display(__FILE__, 'stblogcomments.tpl');
    }
    public function hookDisplayHeader($params)
    {
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
            
		$this->context->controller->addJS(($this->_path).'views/js/stblogcomments.js');
		$this->context->controller->addCSS(($this->_path).'views/css/stblogcomments.css');
    }
    
    public function getCommentNumber($id_st_blog,$link_rewrite=null)
    {
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
        if(!$id_st_blog || !Validate::isUnsignedInt($id_st_blog))
            return false;
        if (!StBlogCommentClass::blogAcceptComment($id_st_blog))
            return false;
        
		$moderate = (int)Configuration::get('ST_BLOG_C_MODERATE');
        $comment_number = (int)StBlogCommentClass::countComments($id_st_blog,$this->context->shop->id,$moderate);
	    $this->smarty->assign(array(
            'comment_number'=>$comment_number,
            'id_st_blog' => $id_st_blog,
        ));
        if($link_rewrite)
            $this->smarty->assign('link_rewrite',$link_rewrite);
            
        return $this->display(__FILE__, 'comment_number.tpl');
    }
    
    public function hookDisplayAnywhere($params)
    {
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
	    if(!isset($params['caller']) || $params['caller']!=$this->name)
            return false;
        if(isset($params['function']) && method_exists($this,$params['function']))
        {
            if($params['function']=='getCommentNumber')
            {
                $link_rewrite = null;
                if(isset($params['link_rewrite']) && $params['link_rewrite'])
                    $link_rewrite = $params['link_rewrite'];
                return call_user_func_array(array($this,$params['function']),array($params['id_blog'],$link_rewrite));
            }
            else
                return false;
        }
        return false;
    }
	public function hookDisplayCustomerAccount($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		return $this->display(__FILE__, 'my-account.tpl');
	}
    private function getConfigFieldsValues()
    {
        $fields_value = array(
            'moderate' =>  Configuration::get('ST_BLOG_C_MODERATE'),
            'minimal_time' =>  Configuration::get('ST_BLOG_C_MINIMAL_TIME'),
            'send_email' => Configuration::get('ST_BLOG_C_SEND_EMAIL'),
            'admin_email' => Configuration::get('ST_BLOG_C_ADMIN_EMAIL'),
            'allow_guests' =>  Configuration::get('ST_BLOG_C_ALLOW_GUESTS'),
            'column_nbr' =>  Configuration::get('ST_BLOG_C_COLUMN_NBR'),
        );
        return $fields_value;
    }
}
