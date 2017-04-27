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

class StFbLikeBox extends Module
{
    private $_html = '';
    private $_prefix_st = 'ST_FB_LB_';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();
    public static $locale = array(
        array('id'=>'ca_ES', 'name'=>'Catalan'), 
        array('id'=>'cs_CZ', 'name'=>'Czech'), 
        array('id'=>'cy_GB', 'name'=>'Welsh'), 
        array('id'=>'da_DK', 'name'=>'Danish'), 
        array('id'=>'de_DE', 'name'=>'German'), 
        array('id'=>'eu_ES', 'name'=>'Basque'), 
        array('id'=>'en_PI', 'name'=>'English (Pirate)'),
        array('id'=>'en_UD', 'name'=>'English (Upside Down)'),
        array('id'=>'ck_US', 'name'=>'Cherokee'), 
        array('id'=>'es_LA', 'name'=>'Spanish'), 
        array('id'=>'es_CL', 'name'=>'Spanish (Chile)'),
        array('id'=>'es_CO', 'name'=>'Spanish (Colombia)'),
        array('id'=>'es_ES', 'name'=>'Spanish (Spain)'),
        array('id'=>'es_MX', 'name'=>'Spanish (Mexico)'),
        array('id'=>'es_VE', 'name'=>'Spanish (Venezuela)'),
        array('id'=>'fb_FI', 'name'=>'Finnish (test)'),
        array('id'=>'fi_FI', 'name'=>'Finnish'), 
        array('id'=>'fr_FR', 'name'=>'French (France)'),
        array('id'=>'gl_ES', 'name'=>'Galician'), 
        array('id'=>'hu_HU', 'name'=>'Hungarian'), 
        array('id'=>'it_IT', 'name'=>'Italian'), 
        array('id'=>'ja_JP', 'name'=>'Japanese'), 
        array('id'=>'ko_KR', 'name'=>'Korean'), 
        array('id'=>'nb_NO', 'name'=>'Norwegian (bokmal)'),
        array('id'=>'nn_NO', 'name'=>'Norwegian (nynorsk)'),
        array('id'=>'nl_NL', 'name'=>'Dutch'),
        array('id'=>'pl_PL', 'name'=>'Polish'),
        array('id'=>'pt_BR', 'name'=>'Portuguese (Brazil)'),
        array('id'=>'pt_PT', 'name'=>'Portuguese (Portugal)'),
        array('id'=>'ro_RO', 'name'=>'Romanian'),
        array('id'=>'ru_RU', 'name'=>'Russian'),
        array('id'=>'sk_SK', 'name'=>'Slovak'),
        array('id'=>'sl_SI', 'name'=>'Slovenian'),
        array('id'=>'sv_SE', 'name'=>'Swedish'),
        array('id'=>'th_TH', 'name'=>'Thai'),
        array('id'=>'tr_TR', 'name'=>'Turkish'),
        array('id'=>'ku_TR', 'name'=>'Kurdish'),
        array('id'=>'zh_CN', 'name'=>'Simplified Chinese (China)'),
        array('id'=>'zh_HK', 'name'=>'Traditional Chinese (Hong Kong)'), 
        array('id'=>'zh_TW', 'name'=>'Traditional Chinese (Taiwan)'),
        array('id'=>'fb_LT', 'name'=>'Leet Speak'), 
        array('id'=>'af_ZA', 'name'=>'Afrikaans'),
        array('id'=>'sq_AL', 'name'=>'Albanian'),
        array('id'=>'hy_AM', 'name'=>'Armenian'),
        array('id'=>'az_AZ', 'name'=>'Azeri'),
        array('id'=>'be_BY', 'name'=>'Belarusian'),
        array('id'=>'bn_IN', 'name'=>'Bengali'),
        array('id'=>'bs_BA', 'name'=>'Bosnian'),
        array('id'=>'bg_BG', 'name'=>'Bulgarian'),
        array('id'=>'hr_HR', 'name'=>'Croatian'),
        array('id'=>'nl_BE', 'name'=>'Dutch (België)'),
        array('id'=>'en_GB', 'name'=>'English (UK)'),
        array('id'=>'eo_EO', 'name'=>'Esperanto'),
        array('id'=>'et_EE', 'name'=>'Estonian'),
        array('id'=>'fo_FO', 'name'=>'Faroese'),
        array('id'=>'fr_CA', 'name'=>'French (Canada)'),
        array('id'=>'ka_GE', 'name'=>'Georgian'),
        array('id'=>'el_GR', 'name'=>'Greek'),
        array('id'=>'gu_IN', 'name'=>'Gujarati'),
        array('id'=>'hi_IN', 'name'=>'Hindi'),
        array('id'=>'is_IS', 'name'=>'Icelandic'),
        array('id'=>'id_ID', 'name'=>'Indonesian'),
        array('id'=>'ga_IE', 'name'=>'Irish'),
        array('id'=>'jv_ID', 'name'=>'Javanese'),
        array('id'=>'kn_IN', 'name'=>'Kannada'),
        array('id'=>'kk_KZ', 'name'=>'Kazakh'),
        array('id'=>'la_VA', 'name'=>'Latin'),
        array('id'=>'lv_LV', 'name'=>'Latvian'),
        array('id'=>'li_NL', 'name'=>'Limburgish'),
        array('id'=>'lt_LT', 'name'=>'Lithuanian'),
        array('id'=>'mk_MK', 'name'=>'Macedonian'),
        array('id'=>'mg_MG', 'name'=>'Malagasy'),
        array('id'=>'ms_MY', 'name'=>'Malay'),
        array('id'=>'mt_MT', 'name'=>'Maltese'),
        array('id'=>'mr_IN', 'name'=>'Marathi'),
        array('id'=>'mn_MN', 'name'=>'Mongolian'),
        array('id'=>'ne_NP', 'name'=>'Nepali'),
        array('id'=>'pa_IN', 'name'=>'Punjabi'),
        array('id'=>'rm_CH', 'name'=>'Romansh'),
        array('id'=>'sa_IN', 'name'=>'Sanskrit'),
        array('id'=>'sr_RS', 'name'=>'Serbian'),
        array('id'=>'so_SO', 'name'=>'Somali'),
        array('id'=>'sw_KE', 'name'=>'Swahili'),
        array('id'=>'tl_PH', 'name'=>'Filipino'),
        array('id'=>'ta_IN', 'name'=>'Tamil'),
        array('id'=>'tt_RU', 'name'=>'Tatar'),
        array('id'=>'te_IN', 'name'=>'Telugu'),
        array('id'=>'ml_IN', 'name'=>'Malayalam'),
        array('id'=>'uk_UA', 'name'=>'Ukrainian'),
        array('id'=>'uz_UZ', 'name'=>'Uzbek'),
        array('id'=>'vi_VN', 'name'=>'Vietnamese'),
        array('id'=>'xh_ZA', 'name'=>'Xhosa'),
        array('id'=>'zu_ZA', 'name'=>'Zulu'),
        array('id'=>'km_KH', 'name'=>'Khmer'),
        array('id'=>'tg_TJ', 'name'=>'Tajik'),
        array('id'=>'ar_AR', 'name'=>'Arabic'),
        array('id'=>'he_IL', 'name'=>'Hebrew'),
        array('id'=>'ur_PK', 'name'=>'Urdu'),
        array('id'=>'fa_IR', 'name'=>'Persian'),
        array('id'=>'sy_SY', 'name'=>'Syriac'),
        array('id'=>'yi_DE', 'name'=>'Yiddish'),
        array('id'=>'gn_PY', 'name'=>'Guaraní'),
        array('id'=>'qu_PE', 'name'=>'Quechua'),
        array('id'=>'ay_BO', 'name'=>'Aymara'),
        array('id'=>'se_NO', 'name'=>'Northern Sámi'),
        array('id'=>'ps_AF', 'name'=>'Pashto'),
        array('id'=>'tl_ST', 'name'=>'Klingon'),
    );
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
		$this->name          = 'stfblikebox';
		$this->tab           = 'front_office_features';
		$this->version       = '1.0.7';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
        $this->bootstrap     = true;
		
		parent::__construct();
		
        $this->displayName = $this->l('Facebook page plugin');
        $this->description = $this->l('Adds a Facebook social plugin Like Box');
	}

	public function install()
	{
		if (!parent::install() 
			|| !$this->registerHook('displayFooterTop')
            || !Configuration::updateValue($this->_prefix_st.'URL', 'https://www.facebook.com/envato')
            || !Configuration::updateValue($this->_prefix_st.'HEIGHT', 200)
            || !Configuration::updateValue($this->_prefix_st.'FACE', 1)
            || !Configuration::updateValue($this->_prefix_st.'STREAM', 1)
            || !Configuration::updateValue($this->_prefix_st.'LOCALE', 'en_US')
            || !Configuration::updateValue($this->_prefix_st.'WIDE_ON_FOOTER', 3)
            || !Configuration::updateValue($this->_prefix_st.'HIDE_COVER', 0)
            || !Configuration::updateValue($this->_prefix_st.'SMALL_HEADER', 1)
            )
			return false;
		return true;
	}

    public function getContent()
	{
	    $this->initFieldsForm();
		if (isset($_POST['savestfblikebox']))
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
                            Configuration::updateValue('ST_FB_'.strtoupper($field['name']), $value);
                        }
                        else
                            Configuration::updateValue('ST_FB_'.strtoupper($field['name']), $value);
                    }
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
					'type' => 'text',
					'label' => $this->l('Facebook Page URL:'),
					'name' => 'lb_url',
                    'size' => 64,
                    'required' => true,
                    'validation' => 'isGenericName',
				),
                array(
					'type' => 'text',
					'label' => $this->l('Height:'),
					'name' => 'lb_height',
                    'required' => true,
                    'desc' => $this->l('The height of the plugin in pixels.'),
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg'
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Show Friend\'s Faces:'),
					'name' => 'lb_face',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'lb_face_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'lb_face_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('Show profile photos when friends like this.'),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Show Page Posts:'),
					'name' => 'lb_stream',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'lb_stream_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'lb_stream_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('Show posts from the Page\'s timeline.'),
                    'validation' => 'isBool',
				), 

                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide Cover Photo:'),
                    'name' => 'lb_hide_cover',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'lb_hide_cover_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'lb_hide_cover_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'desc' => $this->l('Hide cover photo in the header'),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Use the small header instead:'),
                    'name' => 'lb_small_header',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'llb_small_header_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'llb_small_header_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'desc' => $this->l('Use the small header instead'),
                    'validation' => 'isBool',
                ), 
				array(
					'type' => 'select',
					'label' => $this->l('Locale:'),
					'name' => 'lb_locale',
					'options' => array(
						'query' => self::$locale,
        				'id' => 'id',
        				'name' => 'name',
						'default' => array(
							'value' => 'en_US',
							'label' => 'English (US)'
						)
					),
                    'validation' => 'isAnything',
				),
                array(
                    'type' => 'select',
                    'label' => $this->l('Wide on footer:'),
                    'name' => 'lb_wide_on_footer',
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
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestfblikebox';
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
        if(!Configuration::get($this->_prefix_st.'URL'))
            return false;
        $this->smarty->assign(array(
			'st_lb_url' => Configuration::get($this->_prefix_st.'URL'),
			'st_lb_height' => (int)Configuration::get($this->_prefix_st.'HEIGHT'),
			'st_lb_face' => Configuration::get($this->_prefix_st.'FACE'),
            'st_lb_stream' => Configuration::get($this->_prefix_st.'STREAM'),
            'st_lb_hide_cover' => Configuration::get($this->_prefix_st.'HIDE_COVER'),
            'st_lb_small_header' => Configuration::get($this->_prefix_st.'SMALL_HEADER'),
            /*
			'st_lb_colorscheme' => Configuration::get($this->_prefix_st.'COLORSCHEME'),
			'st_lb_header' => Configuration::get($this->_prefix_st.'HEADER'),
			'st_lb_color_scheme' => Configuration::get($this->_prefix_st.'CS'),
			'st_lb_width' => (int)Configuration::get($this->_prefix_st.'WIDTH'),
			'st_lb_b_color' => Configuration::get($this->_prefix_st.'B_C'),
			'st_lb_bg_color' => Configuration::get($this->_prefix_st.'BG_C'),
            'st_lb_connections' => Configuration::get($this->_prefix_st.'CONNECTIONS'),
            */
            'st_lb_locale' => Configuration::get($this->_prefix_st.'LOCALE'),
            'st_fb_lb_wide_on_footer' => Configuration::get($this->_prefix_st.'WIDE_ON_FOOTER'),
		));
        return true;
    }
	public function hookDisplayLeftColumn($params)
	{
	    if(!$this->prepare())
            return false;
		return $this->display(__FILE__, 'stfblikebox.tpl');
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
		return $this->display(__FILE__, 'stfblikebox-footer.tpl');
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
            'lb_url' => Configuration::get($this->_prefix_st.'URL'),
            'lb_height' => (int)Configuration::get($this->_prefix_st.'HEIGHT'),
            'lb_face' => (int)Configuration::get($this->_prefix_st.'FACE'),
            'lb_stream' => (int)Configuration::get($this->_prefix_st.'STREAM'),
            'lb_hide_cover' => Configuration::get($this->_prefix_st.'HIDE_COVER'),
            'lb_small_header' => Configuration::get($this->_prefix_st.'SMALL_HEADER'),
            'lb_locale' => Configuration::get($this->_prefix_st.'LOCALE'),
            'lb_wide_on_footer' => Configuration::get($this->_prefix_st.'WIDE_ON_FOOTER'),
        );
        return $fields_values;
    }
    public function get_prefix()
    {
        if (isset($this->_prefix_st) && $this->_prefix_st)
            return $this->_prefix_st;
        return false;
    }
}