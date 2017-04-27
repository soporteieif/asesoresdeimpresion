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
    
include_once(dirname(__FILE__).'/StBlogRelatedArticlesClass.php');

class StBlogRelatedArticles extends Module
{
    private $_html = '';
    public $fields_form;
    public $fields_value;
    private $_prefix_st = 'ST_BRA_';
    public $validation_errors = array();
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
        0 => array('id' =>0 , 'name' => 'Random'),
        1 => array('id' =>1 , 'name' => 'Date add: Desc'),
        2 => array('id' =>2 , 'name' => 'Date add: Asc'),
        3 => array('id' =>3 , 'name' => 'Date update: Desc'),
        4 => array('id' =>4 , 'name' => 'Date update: Asc'),
        5 => array('id' =>5 , 'name' => 'Position: Asc'),
        6 => array('id' =>6 , 'name' => 'Position ID: Desc'),
        7 => array('id' =>7 , 'name' => 'Blog ID: Asc'),
        8 => array('id' =>8 , 'name' => 'Blog ID: Desc'),
    );
	function __construct()
	{
		$this->name           = 'stblogrelatedarticles';
		$this->tab            = 'front_office_features';
		$this->version        = '1.0';
		$this->author         = 'SUNNYTOO.COM';
		$this->need_instance  = 0;
		$this->bootstrap 	  = true;
		parent::__construct();
        
        Shop::addTableAssociation('st_blog', array('type' => 'shop'));

		$this->displayName = $this->l('Blog Module - Related articles');
		$this->description = $this->l('Add related articles on blog artice pages.');
	}

	function install()
	{
		if (!parent::install()
			|| !$this->installDB()
			|| !$this->registerHook('actionObjectStBlogClassAddAfter')
			|| !$this->registerHook('actionObjectStBlogClassUpdateAfter')
			|| !$this->registerHook('actionObjectStBlogClassDeleteAfter')
            || !$this->registerHook('actionAdminStBlogFormModifier')
            || !$this->registerHook('displayStBlogRightColumn')
            || !Configuration::updateValue($this->_prefix_st.'BY_TAG', 1)
            || !Configuration::updateValue($this->_prefix_st.'NBR', 8)
            || !Configuration::updateValue($this->_prefix_st.'SLIDESHOW', 0)
            || !Configuration::updateValue($this->_prefix_st.'S_SPEED', 7000)
            || !Configuration::updateValue($this->_prefix_st.'A_SPEED', 400)
            || !Configuration::updateValue($this->_prefix_st.'PAUSE_ON_HOVER', 1)
            || !Configuration::updateValue($this->_prefix_st.'S_EASING', 0)
            || !Configuration::updateValue($this->_prefix_st.'S_LOOP', 0)
            || !Configuration::updateValue($this->_prefix_st.'MOVE', 0)
            || !Configuration::updateValue($this->_prefix_st.'NBR_COL', 8) 
            || !Configuration::updateValue($this->_prefix_st.'SLIDESHOW_COL', 0)
            || !Configuration::updateValue($this->_prefix_st.'S_SPEED_COL', 7000)
            || !Configuration::updateValue($this->_prefix_st.'A_SPEED_COL', 400)
            || !Configuration::updateValue($this->_prefix_st.'PAUSE_ON_HOVER_COL', 1)
            || !Configuration::updateValue($this->_prefix_st.'S_EASING_COL', 1)
            || !Configuration::updateValue($this->_prefix_st.'S_LOOP_COL', 0)
            || !Configuration::updateValue($this->_prefix_st.'MOVE_COL', 0)
            || !Configuration::updateValue($this->_prefix_st.'ITEMS_COL', 4)
            || !Configuration::updateValue($this->_prefix_st.'SOBY', 1)
            || !Configuration::updateValue($this->_prefix_st.'SOBY_COL', 1)
            
            || !Configuration::updateValue('STSN_BRA_PRO_PER_LG', 4)
            || !Configuration::updateValue('STSN_BRA_PRO_PER_MD', 4)
            || !Configuration::updateValue('STSN_BRA_PRO_PER_SM', 3)
            || !Configuration::updateValue('STSN_BRA_PRO_PER_XS', 2)
            || !Configuration::updateValue('STSN_BRA_PRO_PER_XXS', 1)
        )
			return false;
		$this->_clearCache('stblogrelatedarticles.tpl');
		return true;
	}
    private function installDB()
	{
		$return = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_related_articles` (                 
              `id_st_blog_1` int(10) unsigned NOT NULL DEFAULT 0,         
              `id_st_blog_2` int(10) unsigned NOT NULL DEFAULT 0,
			  PRIMARY KEY (`id_st_blog_1`,`id_st_blog_2`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
            
		return $return;
	}
	private function uninstallDB()
	{
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_blog_related_articles`');
	}	
	public function uninstall()
	{
		$this->_clearCache('stblogrelatedarticles.tpl');
		if (!parent::uninstall() ||
			!$this->uninstallDB())
			return false;
		return true;
	}
        
    public function getContent()
	{
	    $this->initFieldsForm();
        if (Tools::getValue('act') == 'gsbra' && Tools::getValue('ajax')==1)
        {
            if(!$q = Tools::getValue('q'))
                die;
            if(!$id_st_blog = Tools::getValue('id_st_blog'))
                die;
            
            $excludeIds = Tools::getValue('excludeIds');
            $result = Db::getInstance()->executeS('
			SELECT b.`id_st_blog`,bl.`name`
			FROM `'._DB_PREFIX_.'st_blog` b
            LEFT JOIN `'._DB_PREFIX_.'st_blog_lang` bl
            ON (b.`id_st_blog` = bl.`id_st_blog`
            AND bl.`id_lang`='.(int)$this->context->language->id.')
            '.Shop::addSqlAssociation('st_blog', 'b').'
			WHERE bl.`name` LIKE \'%'.pSQL($q).'%\'
            AND b.`active` = 1
            AND b.`id_st_blog` != '.(int)$id_st_blog.'
            '.($excludeIds ? 'AND b.`id_st_blog` NOT IN('.$excludeIds.')' : '').'
    		');
            foreach ($result AS $value)
		      echo trim($value['name']).'|'.(int)($value['id_st_blog'])."\n";
            die;
        }
		if (isset($_POST['savestblogrelatedarticles']))
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
            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else 
            {
		        $this->_clearCache('stblogrelatedarticles.tpl');
                $this->_html .= $this->displayConfirmation($this->l('Settings updated'));  
            }    
        }
        $this->fields_form[1]['form']['input']['related_pro_per']['name'] = $this->BuildDropListGroup($this->findCateProPer());
		$helper = $this->initForm();
		return $this->_html.$helper->generateForm($this->fields_form);
	}

    public function initFieldsForm()
    {
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('General settings'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'switch',
					'label' => $this->l('Automatically generate related articles(using tags):'),
					'name' => 'by_tag',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'by_tag_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'by_tag_off',
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
				'title' => $this->l('Sldie settings'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Define the number of articles to be displayed:'),
					'name' => 'nbr',
                    'default_value' => 8,
                    'required' => true,
                    'desc' => $this->l('Define the number of articles that you would like to display on homepage (default: 8).'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                'related_pro_per' => array(
                    'type' => 'html',
                    'id' => 'related_pro_per',
                    'label' => $this->l('The number of columns:'),
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
					'type' => 'switch',
					'label' => $this->l('Autoplay:'),
					'name' => 'slideshow',
					'is_bool' => true,
                    'default_value' => 0,
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
        			'name' => 's_easing',
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
					'name' => 's_loop',
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
				'title' => $this->l('   Save all  '),
			)
		);
        $this->fields_form[2]['form'] = array(
			'legend' => array(
				'title' => $this->l('Column Slide Settings'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Define the number of articles to be displayed:'),
					'name' => 'nbr_col',
                    'default_value' => 8,
                    'required' => true,
                    'desc' => $this->l('Define the number of articles that you would like to display on homepage (default: 8).'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'select',
        			'label' => $this->l('The number of columns:'),
        			'name' => 'items_col',
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
        			'name' => 'soby_col',
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
					'name' => 'slideshow_col',
					'is_bool' => true,
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'slideshow_col_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'slideshow_col_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'text',
					'label' => $this->l('Time:'),
					'name' => 's_speed_col',
                    'default_value' => 7000,
                    'required' => true,
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Transition period:'),
					'name' => 'a_speed_col',
                    'default_value' => 400,
                    'required' => true,
                    'desc' => $this->l('The period, in milliseconds, of the transition effect.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Pause On Hover:'),
					'name' => 'pause_on_hover_col',
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
				'title' => $this->l('   Save all  '),
			)
		);
    }
    protected function initForm()
	{
	    $helper = new HelperForm();
		$helper->show_toolbar = false;
        $helper->module = $this;
		$helper->table =  $this->table;
        $helper->module = $this;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestblogrelatedarticles';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper;
	}
    public function hookActionAdminStBlogFormModifier($params)
    {
        if(!$id_st_blog = Tools::getValue('id_st_blog'))
            return false;
        $fields_form['form'] = array(
			'legend' => array(
				'title' => 'Related articles',
                'icon' => 'icon-cogs'
			),
			'input' => array(
                'relatedarticles' => array(
					'type' => 'text',
					'label' => $this->l('Related articles:'),
					'name' => 'relatedarticles',
                    'autocomplete' => false,
                    'class' => 'fixed-width-xxl',
                    'desc' => $this->l('Begin typing the first letters of the artilce title, then select the article from the drop-down list.'),
				),
			),
			'buttons' => array(
                array(
    				'title' => $this->l('Save all'),
                    'class' => 'btn btn-default pull-right',
                    'icon'  => 'process-icon-save',
    				'type' => 'submit'
                )
			),
			'submit' => array(
				'title' => $this->l('Save and stay'),
				'stay' => true
			),
		);
        
        $js = '<script type="text/javascript">var m_token = "'.Tools::getAdminTokenLite('AdminModules').'";</script>';
        $html = '';
        foreach(StBlogRelatedArticlesClass::getRelatedArticlesLight((int)$this->context->language->id,(int)$id_st_blog) AS $value)
        {
            $html .= '<li>'.$value['name'].'
            <a href="javascript:;" class="del_relatedarticles"><img src="../img/admin/delete.gif" /></a>
            <input type="hidden" name="id_relatedarticles[]" value="'.$value['id_st_blog_2'].'" /></li>';
        }
        
        $fields_form['form']['input']['relatedarticles']['desc'] .= '<br>'.$js.$this->l('Current articles')
                .': <ul id="curr_relatedarticles">'.$html.'</ul>';
        
        $this->context->controller->addJS($this->_path. 'views/js/admin.js');
        $gallery = array_pop($params['fields']);
        $params['fields'][] = $fields_form;
        $params['fields'][] = $gallery;
        $params['fields_value']['relatedarticles'] = '';
        
    }
    private function _prepareHook($col=0, $id_product = 0)
    {            
        $ext = $col ? '_COL' : '';
        $nbr = Configuration::get($this->_prefix_st.'NBR'.$ext);
        ($nbr===false && $col) && $nbr = Configuration::get($this->_prefix_st.'NBR');
        
        if(!$nbr)
            return false;
        
        $id_st_blog = Tools::getValue('id_blog');
        if (!$id_st_blog && !$id_product)
            return false;
        
        $order_by = 'id_st_blog';
        $order_way = 'DESC';
        $soby = (int)Configuration::get($this->_prefix_st.'SOBY'.$ext);
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
                $order_by = 'position';
                $order_way = 'ASC';
            break;
            case 6:
                $order_by = 'position';
                $order_way = 'DESC';
            break;
            case 7:
                $order_by = 'id_st_blog';
                $order_way = 'ASC';
            break;
            case 8:
                $order_by = 'id_st_blog';
                $order_way = 'DESC';
            break;
            default:
            break;
        }
        
        $id_st_blog_array = array();
        if ($id_product > 0)
        {
            $result = Db::getInstance()->executeS('
            SELECT `id_st_blog` FROM '._DB_PREFIX_.'st_blog_product_link
            WHERE `id_product` = '.(int)$id_product.'
            ');
            foreach($result AS $value)
                $id_st_blog_array[] = $value['id_st_blog'];
        }
        elseif( $id_st_blog && Configuration::get($this->_prefix_st.'BY_TAG') )
		{
            $result = Db::getInstance()->executeS('
            SELECT DISTINCT `id_st_blog` 
            FROM '._DB_PREFIX_.'st_blog_tag_map tm 
            LEFT JOIN '._DB_PREFIX_.'st_blog_tag t
            ON t.`id_st_blog_tag`=tm.`id_st_blog_tag`
            WHERE `id_lang` = '.(int)$this->context->language->id.'
            AND `id_st_blog` != '.(int)$id_st_blog.' 
            AND `name` IN(
            SELECT `name` FROM '._DB_PREFIX_.'st_blog_tag t1 
            LEFT JOIN '._DB_PREFIX_.'st_blog_tag_map tm1 
            ON t1.`id_st_blog_tag` = tm1.`id_st_blog_tag` 
            WHERE id_st_blog = '.(int)$id_st_blog.')
            ');
            foreach($result AS $value)
                $id_st_blog_array[] = $value['id_st_blog'];
		}
        
        if ($id_st_blog)
        {
            $result = Db::getInstance()->executeS('
            SELECT `id_st_blog_2` FROM '._DB_PREFIX_.'st_blog_related_articles
            WHERE `id_st_blog_1` = '.(int)$id_st_blog.'
            ');
            
            foreach($result AS $value)
                $id_st_blog_array[] = $value['id_st_blog_2'];    
        }
        
        if (!count($id_st_blog_array))
            return false;
            
        $id_st_blog_array = array_unique($id_st_blog_array);
        
        include_once(_PS_MODULE_DIR_.'stblog/classes/StBlogClass.php');
        include_once(_PS_MODULE_DIR_.'stblog/classes/StBlogImageClass.php');
        
        $sql = new DbQuery();
		$sql->select(
			'b.*, st_blog_shop.*, bl.`content_short`, bl.`link_rewrite`, bl.`name`, bl.`video`'
		);

		$sql->from('st_blog', 'b');
		$sql->join(Shop::addSqlAssociation('st_blog', 'b'));
		$sql->leftJoin('st_blog_lang', 'bl', '
			b.`id_st_blog` = bl.`id_st_blog`
			AND bl.`id_lang` = '.(int)$this->context->language->id
		);
		$sql->where('st_blog_shop.`active` = 1 AND b.`id_st_blog` IN ('.implode(',', $id_st_blog_array).')');

		$sql->groupBy('st_blog_shop.`id_st_blog`');

		$sql->orderBy($order_by && $order_way ? 'b.'.$order_by.' '.$order_way : 'b.`date_add` DESC');
		$sql->limit($nbr);
        
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

		if (!$result)
			return false;

		$blogs = StBlogClass::getBlogsDetials((int)$this->context->language->id, $result);
        
        $slideshow = Configuration::get($this->_prefix_st.'SLIDESHOW'.$ext);
        
        $s_speed = Configuration::get($this->_prefix_st.'S_SPEED'.$ext);
        
        $a_speed = Configuration::get($this->_prefix_st.'A_SPEED'.$ext);
        
        $pause_on_hover = Configuration::get($this->_prefix_st.'PAUSE_ON_HOVER'.$ext);

        $easing = Configuration::get($this->_prefix_st.'S_EASING'.$ext);
        
        $loop = Configuration::get($this->_prefix_st.'S_LOOP'.$ext);
        
        $move = Configuration::get($this->_prefix_st.'MOVE'.$ext);
        
        $items = Configuration::get($this->_prefix_st.'ITEMS_COL');
        
        $this->smarty->assign(array(
			'blogs' => $blogs,
            'imageSize' => StBlogImageClass::$imageTypeDef,
            'slideshow' => $slideshow,
            's_speed' => $s_speed,
            'a_speed' => $a_speed,
            'pause_on_hover' => $pause_on_hover,
            'easing' => self::$easing[$easing]['name'],
            'loop' => $loop,
            'move' => $move,
            'items' => $items,
		));
        return true;
    }
    public function hookDisplayStBlogRightColumn($params)
	{
        return $this->hookDisplayStBlogLeftColumn($params);
	}
	public function hookDisplayStBlogLeftColumn($params)
	{
        if(!(Tools::getValue('fc') == 'module' && Tools::getValue('module')=='stblog' && Dispatcher::getInstance()->getController() == 'article' 
            && ($id_st_blog = (int)Tools::getValue('id_blog'))))
                return false;
        if(!$this->_prepareHook(1))
            return false;
        $this->smarty->assign(array(
            'column_slider'         => true,
        ));
		return $this->display(__FILE__, 'stblogrelatedarticles.tpl');
	}
    public function hookDisplayLeftColumn($params)
	{
	    if(Dispatcher::getInstance()->getController() != 'product' || !($id_product = Tools::getValue('id_product')))
            return false;
        
        if(!$this->_prepareHook(1, $id_product))
                return false;
        
        $this->smarty->assign(array(
            'column_slider'         => true,
        ));
		return $this->display(__FILE__, 'stblogrelatedarticles.tpl'); 
	}
	public function hookDisplayRightColumn($params)
    {
        return $this->hookDisplayLeftColumn($params);
    }
    public function hookDisplayStBlogArticleFooter($params)
    {
        $module_name = Validate::isModuleName(Tools::getValue('module')) ? Tools::getValue('module') : '';
        if(!(Tools::getValue('fc') == 'module' && $module_name=='stblog' && Dispatcher::getInstance()->getController() == 'article' 
            && ($id_st_blog = (int)Tools::getValue('id_blog'))))
                return false;
        if(!$this->_prepareHook(0))
            return false;
        $this->smarty->assign(array(
            'column_slider'         => false,
            'pro_per_lg'       => (int)Configuration::get('STSN_BRA_PRO_PER_LG'),
            'pro_per_md'       => (int)Configuration::get('STSN_BRA_PRO_PER_MD'),
            'pro_per_sm'       => (int)Configuration::get('STSN_BRA_PRO_PER_SM'),
            'pro_per_xs'       => (int)Configuration::get('STSN_BRA_PRO_PER_XS'),
            'pro_per_xxs'      => (int)Configuration::get('STSN_BRA_PRO_PER_XXS'),
        ));
		return $this->display(__FILE__, 'stblogrelatedarticles.tpl');
    }
    public function hookDisplayFooterProduct($params)
    {
        if(Dispatcher::getInstance()->getController() != 'product' || !($id_product = Tools::getValue('id_product')))
            return false;
        
        if(!$this->_prepareHook(1, $id_product))
                return false;
        $this->smarty->assign(array(
            'column_slider'         => false,
            'pro_per_lg'       => (int)Configuration::get('STSN_BRA_PRO_PER_LG'),
            'pro_per_md'       => (int)Configuration::get('STSN_BRA_PRO_PER_MD'),
            'pro_per_sm'       => (int)Configuration::get('STSN_BRA_PRO_PER_SM'),
            'pro_per_xs'       => (int)Configuration::get('STSN_BRA_PRO_PER_XS'),
            'pro_per_xxs'      => (int)Configuration::get('STSN_BRA_PRO_PER_XXS'),
        ));
        return $this->display(__FILE__, 'stblogrelatedarticles.tpl');    
    }
    public function hookDisplayProductSecondaryColumn($params)
	{
        return $this->hookDisplayLeftColumn($params);
	}
	public function hookActionObjectStBlogClassUpdateAfter($params)
	{        
        if (Tools::getValue('ajax') == 1)
            return false;
        if(!$id_st_blog = $params['object']->id)
            return false;
        
        StBlogRelatedArticlesClass::deleteRelatedArticles($id_st_blog);
		if ($related_articles = Tools::getValue('id_relatedarticles'))
		{
			$related_articles = array_unique($related_articles);
            if (in_array($id_st_blog, $related_articles))
                unset($related_articles[array_search($id_st_blog, $related_articles)]);
			if (count($related_articles))
				StBlogRelatedArticlesClass::saveRelatedArticles($id_st_blog, $related_articles);
		}    
		$this->_clearCache('stblogrelatedarticles.tpl');
        return ;
	}
    
    public function hookActionObjectStBlogClassAddAfter($params)
	{
	    $this->hookActionObjectStBlogClassUpdateAfter($params);
	}

	public function hookActionObjectStBlogClassDeleteAfter($params)
	{
	    if (Tools::getValue('ajax') == 1)
            return false;
        if(!$params['object']->id)
            StBlogRelatedArticlesClass::deleteRelatedArticles($params['object']->id);
		$this->_clearCache('stblogrelatedarticles.tpl');
        return;
	}
    
    private function getConfigFieldsValues()
    {
        $fields_values = array(
            'by_tag'             => Configuration::get($this->_prefix_st.'BY_TAG'),
            
            'nbr'                => Configuration::get($this->_prefix_st.'NBR'),
            'slideshow'          => Configuration::get($this->_prefix_st.'SLIDESHOW'),
            's_speed'            => Configuration::get($this->_prefix_st.'S_SPEED'),
            'a_speed'            => Configuration::get($this->_prefix_st.'A_SPEED'),
            'pause_on_hover'     => Configuration::get($this->_prefix_st.'PAUSE_ON_HOVER'),
            's_easing'           => Configuration::get($this->_prefix_st.'S_EASING'),
            's_loop'             => Configuration::get($this->_prefix_st.'S_LOOP'),
            'move'               => Configuration::get($this->_prefix_st.'MOVE'),
            'soby'               => Configuration::get($this->_prefix_st.'SOBY'),
            
            'nbr_col'            => Configuration::get($this->_prefix_st.'NBR_COL'),
            'slideshow_col'      => Configuration::get($this->_prefix_st.'SLIDESHOW_COL'),
            's_speed_col'        => Configuration::get($this->_prefix_st.'S_SPEED_COL'),
            'a_speed_col'        => Configuration::get($this->_prefix_st.'A_SPEED_COL'),
            'pause_on_hover_col' => Configuration::get($this->_prefix_st.'PAUSE_ON_HOVER_COL'),
            's_easing_col'       => Configuration::get($this->_prefix_st.'S_EASING_COL'),
            's_loop_col'         => Configuration::get($this->_prefix_st.'S_LOOP_COL'),
            'move_col'           => Configuration::get($this->_prefix_st.'MOVE_COL'),
            'items_col'          => Configuration::get($this->_prefix_st.'ITEMS_COL'),
            'soby_col'           => Configuration::get($this->_prefix_st.'SOBY_COL'),
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
                'id' => 'bra_pro_per_lg',
                'label' => $this->l('Large devices'),
                'tooltip' => $this->l('Desktops (>1200px)'),
            ),
            array(
                'id' => 'bra_pro_per_md',
                'label' => $this->l('Medium devices'),
                'tooltip' => $this->l('Desktops (>992px)'),
            ),
            array(
                'id' => 'bra_pro_per_sm',
                'label' => $this->l('Small devices'),
                'tooltip' => $this->l('Tablets (>768px)'),
            ),
            array(
                'id' => 'bra_pro_per_xs',
                'label' => $this->l('Extra small devices'),
                'tooltip' => $this->l('Phones (>480px)'),
            ),
            array(
                'id' => 'bra_pro_per_xxs',
                'label' => $this->l('Extra extra small devices'),
                'tooltip' => $this->l('Phones (<480px)'),
            ),
        );
    }
    
    public function updateCatePerRow() {
        $arr = $this->findCateProPer();
        foreach ($arr as $v)
            if($gv = Tools::getValue($v['id']))
                Configuration::updateValue('STSN_'.strtoupper($v['id']), (int)$gv);
    }
}