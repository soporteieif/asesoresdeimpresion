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

class StTwitterEmbeddedTimelines extends Module
{
    private $_html = '';
    private $_prefix_st = 'ST_TW_';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();
    public static $wide_map = array(
        array('id'=>'1', 'name'=>'1/12'),
        array('id'=>'2', 'name'=>'2/12'),
        array('id'=>'2-4', 'name'=>'2.4/12'),
        array('id'=>'4', 'name'=>'4/12'),
        array('id'=>'5', 'name'=>'5/12'),
        array('id'=>'6', 'name'=>'6/12'),
        array('id'=>'7', 'name'=>'7/12'),
        array('id'=>'8', 'name'=>'8/12'),
        array('id'=>'9', 'name'=>'9/12'),
        array('id'=>'10', 'name'=>'10/12'),
        array('id'=>'11', 'name'=>'11/12'),
        array('id'=>'12', 'name'=>'12/12'),
    );
	public function __construct()
	{
		$this->name          = 'sttwitterembeddedtimelines';
		$this->tab           = 'front_office_features';
		$this->version       = '1.0';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
        $this->bootstrap     = true;
		
		parent::__construct();
		
        $this->displayName = $this->l('Twitter Embedded Timelines');
        $this->description = $this->l('Display the recent tweets of a twitter user');
	}

	public function install()
	{
		if (!parent::install() 
			|| !$this->registerHook('displayFooterTop')
            || !Configuration::updateValue($this->_prefix_st.'NAME', '')
            || !Configuration::updateValue($this->_prefix_st.'WIDGET_ID', '')
            || !Configuration::updateValue($this->_prefix_st.'BLOCK_TITLE', array('1'=>'Twitter feed'))
            || !Configuration::updateValue($this->_prefix_st.'HEIGHT', 0)
            || !Configuration::updateValue($this->_prefix_st.'LINK_COLOR', '#00A161')
            || !Configuration::updateValue($this->_prefix_st.'THEME', 'light')
            || !Configuration::updateValue($this->_prefix_st.'NOHEADER', 1)
            || !Configuration::updateValue($this->_prefix_st.'NOFOOTER', 1)
            || !Configuration::updateValue($this->_prefix_st.'NOBORDERS', 1)
            || !Configuration::updateValue($this->_prefix_st.'NOSCROLLBAR', 1)
            || !Configuration::updateValue($this->_prefix_st.'TRANSPARENT', 1)
            || !Configuration::updateValue($this->_prefix_st.'BORDER_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'LANGUAGE', '')
            || !Configuration::updateValue($this->_prefix_st.'LIMIT', 2)
            || !Configuration::updateValue($this->_prefix_st.'LANGUAGE', '')
            || !Configuration::updateValue($this->_prefix_st.'SCREEN_NAME', '')
            || !Configuration::updateValue($this->_prefix_st.'SHOW_REPLIES', 0)
            || !Configuration::updateValue($this->_prefix_st.'WIDE_ON_FOOTER', 3)

        )
			return false;
		return true;
	}

    public function getContent()
	{
	    $this->initFieldsForm();
		if (isset($_POST['savesttwitterembeddedtimelines']))
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
                            Configuration::updateValue($this->_prefix_st.strtoupper($field['name']), $value);
                        }
                        else
                            Configuration::updateValue($this->_prefix_st.strtoupper($field['name']), $value);
                    }
            
            $this->updateBlockTitle();
            
            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else 
                $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
        }

		$helper = $this->initForm();
		return $this->_html.$helper->generateForm($this->fields_form);
	}
    public function updateBlockTitle() {
		$languages = Language::getLanguages();
		$result = array();
        $defaultLanguage = new Language((int)(Configuration::get('PS_LANG_DEFAULT')));
		foreach ($languages as $language)
			$result[$language['id_lang']] = Tools::getValue('block_title_' . $language['id_lang']) ? Tools::getValue('block_title_'.$language['id_lang']) : Tools::getValue('block_title_'.$defaultLanguage->id);
            
        Configuration::updateValue('ST_TW_BLOCK_TITLE', $result);
	}
    protected function initFieldsForm()
    {
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->displayName,
                'icon' => 'icon-cogs' 
			),
            'description' => '<a href="https://dev.twitter.com/docs/embedded-timelines#customization" target="_blank">'.$this->l('The customisation documentation.').'</a>', 
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Twitter user name:'),
					'name' => 'name',
                    'size' => 64,
                    'validation' => 'isGenericName',
				),
                array(
					'type' => 'text',
					'label' => $this->l('Widget ID:'),
					'name' => 'widget_id',
                    'size' => 64,
                    'desc' => '<a href="https://twitter.com/settings/widgets" target="_blank">'.$this->l('Create your own embedded timeline.').'</a>',
                    'validation' => 'isAnything',
				),
                array(
					'type' => 'text',
					'label' => $this->l('Block title:'),
					'name' => 'block_title',
                    'size' => 64,
                    'lang' => true,
				),
                array(
					'type' => 'text',
					'label' => $this->l('Height:'),
					'name' => 'height',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg'
				),
				 array(
					'type' => 'color',
					'label' => $this->l('Link color:'),
					'name' => 'link_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                 array(
					'type' => 'select',
        			'label' => $this->l('Theme:'),
        			'name' => 'theme',
                    'options' => array(
        				'query' => array(
                            array('id' => 'dark', 'name' => 'dark'),
		                    array('id' => 'light', 'name' => 'light'),
                        ),
        				'id' => 'id',
        				'name' => 'name',
        			),
                    'validation' => 'isGenericName',
				),
                array(
					'type' => 'switch',
					'label' => $this->l('No header:'),
					'name' => 'noheader',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'noheader_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'noheader_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('No Footer:'),
					'name' => 'nofooter',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'nofooter_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'nofooter_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('No Borders:'),
					'name' => 'noborders',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'noborders_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'noborders_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
				 array(
					'type' => 'color',
					'label' => $this->l('Border color:'),
					'name' => 'border_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                array(
					'type' => 'switch',
					'label' => $this->l('No scrollbar:'),
					'name' => 'noscrollbar',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'noscrollbar_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'noscrollbar_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Transparent:'),
					'name' => 'transparent',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'transparent_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'transparent_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'text',
					'label' => $this->l('Tweet limit:'),
					'name' => 'limit',
                    'default_value' => 2,
                    'required' => true,
                    'validation' => 'isUnsignedInt',
                    'desc' => $this->l('The timeline will render the specified number of Tweets from the timeline, expanding the height of the widget to display all Tweets without scrolling. Since the widget is of a fixed size, it will not poll for updates when using this option.'),
				), 
                array(
					'type' => 'text',
					'label' => $this->l('Language:'),
					'name' => 'language',
                    'size' => 64,
                    'desc' => '<a href="http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes" target="_blank">ISO_639-1</a> eg. EN,FR',
                    'validation' => 'isLanguageIsoCode',
				),  
                array(
					'type' => 'text',
					'label' => $this->l('Screen name:'),
					'name' => 'screen_name',
                    'size' => 64,
                    'desc' => $this->l('Whose timeline you want to display.'),
                    'validation' => 'isGenericName',
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Show replies:'),
					'name' => 'show_replies',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'show_replies_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'show_replies_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
                    'desc' => $this->l('You have to fill the screen name for the show replies attribute to take effect'),
				),
                array(
                    'type' => 'select',
                    'label' => $this->l('Wide on footer:'),
                    'name' => 'wide_on_footer',
                    'options' => array(
                        'query' => self::$wide_map,
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => 3,
                            'label' => '3/12',
                        ),
                    ),
                    'validation' => 'isGenericName',
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
        $helper->module = $this;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savesttwitterembeddedtimelines';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper;
	}
    public function prepare()
    {
        if(!Configuration::get('ST_TW_NAME') || !Configuration::get('ST_TW_WIDGET_ID'))
            return false;
        $this->smarty->assign(array(
            'name' => Configuration::get('ST_TW_NAME'),
            'widget_id' => Configuration::get('ST_TW_WIDGET_ID'),
            'tw_block_title' => Configuration::get('ST_TW_BLOCK_TITLE', $this->context->language->id),
            'height' => (int)Configuration::get('ST_TW_HEIGHT'),
            'link_color' => Configuration::get('ST_TW_LINK_COLOR'),
            'theme' => Configuration::get('ST_TW_THEME'),
            'noheader' => (int)Configuration::get('ST_TW_NOHEADER'),
            'nofooter' => (int)Configuration::get('ST_TW_NOFOOTER'),
            'noborders' => (int)Configuration::get('ST_TW_NOBORDERS'),
            'noscrollbar' => (int)Configuration::get('ST_TW_NOSCROLLBAR'),
            'transparent' => (int)Configuration::get('ST_TW_TRANSPARENT'),
            'border_color' => Configuration::get('ST_TW_BORDER_COLOR'),
            'limit' => (int)Configuration::get('ST_TW_LIMIT'),
            'language' => Configuration::get('ST_TW_LANGUAGE'),
            'screen_name' => Configuration::get('ST_TW_SCREEN_NAME'),
            'show_replies' => (int)Configuration::get('ST_TW_SHOW_REPLIES'),
            'wide_on_footer' => Configuration::get('ST_TW_WIDE_ON_FOOTER'),
		));
        return true;
    }
	public function hookDisplayLeftColumn($params)
	{
	    if(!$this->prepare())
            return false;
		return $this->display(__FILE__, 'sttwitterembeddedtimelines.tpl');
	}
	public function hookDisplayRightColumn($params)
	{
        return $this->hookDisplayLeftColumn($params); 
	}
	public function hookDisplayHomeSecondaryRight($params)
	{
        return $this->hookDisplayLeftColumn($params); 
	}
    public function hookDisplayFooter($params)
    {
	    if(!$this->prepare())
            return false;
		return $this->display(__FILE__, 'sttwitterembeddedtimelines-footer.tpl');
    }
    public function hookDisplayFooterTop($params)
    {
        return $this->hookDisplayFooter($params); 
    }
    public function hookDisplayFooterSecondary($params)
    {
        return $this->hookDisplayFooter($params); 
    }
    private function getConfigFieldsValues()
    {
        $fields_values = array(
            'name' => Configuration::get($this->_prefix_st.'NAME'),
            'widget_id' => Configuration::get($this->_prefix_st.'WIDGET_ID'),
            'height' => (int)Configuration::get($this->_prefix_st.'HEIGHT'),
            'link_color' => Configuration::get($this->_prefix_st.'LINK_COLOR'),
            'theme' => Configuration::get($this->_prefix_st.'THEME'),
            'noheader' => (int)Configuration::get($this->_prefix_st.'NOHEADER'),
            'nofooter' => (int)Configuration::get($this->_prefix_st.'NOFOOTER'),
            'noborders' => (int)Configuration::get($this->_prefix_st.'NOBORDERS'),
            'noscrollbar' => (int)Configuration::get($this->_prefix_st.'NOSCROLLBAR'),
            'transparent' => (int)Configuration::get($this->_prefix_st.'TRANSPARENT'),
            'border_color' => Configuration::get($this->_prefix_st.'BORDER_COLOR'),
            'limit' => (int)Configuration::get($this->_prefix_st.'LIMIT'),
            'language' => Configuration::get($this->_prefix_st.'LANGUAGE'),
            'screen_name' => Configuration::get($this->_prefix_st.'SCREEN_NAME'),
            'show_replies' => (int)Configuration::get($this->_prefix_st.'SHOW_REPLIES'),
            'wide_on_footer' => Configuration::get($this->_prefix_st.'WIDE_ON_FOOTER'),
        );
        $languages = Language::getLanguages(false);
		foreach ($languages as $language)
        {
            $fields_values['block_title'][$language['id_lang']] = Configuration::get($this->_prefix_st.'BLOCK_TITLE', $language['id_lang']);
        }
        return $fields_values;
    }
    public function get_prefix()
    {
        if (isset($this->_prefix_st) && $this->_prefix_st)
            return $this->_prefix_st;
        return false;
    }
}