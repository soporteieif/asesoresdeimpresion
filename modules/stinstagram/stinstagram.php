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

class StInstagram extends Module
{
    private $_html = '';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();
    private $_prefix_st = 'ST_INSTAGRAM_';
    private $_prefix_stsn = 'STSN_';
    private static $client_id = 'd90cabca9f064cb2ba9e3de1ec6a567a';
    public static $wide_map = array(
        array('id'=>'1', 'name'=>'1/12'),
        array('id'=>'2', 'name'=>'2/12'),
        array('id'=>'2-4', 'name'=>'2.4/12'),
        array('id'=>'3', 'name'=>'3/12'),
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
    private $_hooks = array();
	function __construct()
	{
		$this->name           = 'stinstagram';
		$this->tab            = 'front_office_features';
		$this->version        = '1.0';
		$this->author         = 'SUNNYTOO.COM';
		$this->need_instance  = 0;
        $this->bootstrap      = true;

		parent::__construct();
        
        $this->initHookArray();

		$this->displayName = $this->l('Instagram block');
		$this->description = $this->l('Display Instagram photos on your store.');
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

	function install()
	{
		if (!parent::install() 
            || !$this->registerHook('displayHeader')
            || !Configuration::updateValue($this->_prefix_st.'CLIENT_ID', self::$client_id)
            || !Configuration::updateValue($this->_prefix_st.'SHOW_IMAGE', 0)
            || !Configuration::updateValue($this->_prefix_st.'HASH_TAG', '')
            || !Configuration::updateValue($this->_prefix_st.'COUNT', 10)
            || !Configuration::updateValue($this->_prefix_st.'IMAGE_SIZE', 0)
            || !Configuration::updateValue($this->_prefix_st.'HIDE_ON_MOBILE', 0)
            || !Configuration::updateValue($this->_prefix_st.'SHOW_PROFILE', 1)
            || !Configuration::updateValue($this->_prefix_st.'SHOW_COUNTS', 1)
            || !Configuration::updateValue($this->_prefix_st.'SHOW_LIKES', 1)
            || !Configuration::updateValue($this->_prefix_st.'SHOW_COMMENTS', 1)
            || !Configuration::updateValue($this->_prefix_st.'SHOW_USERNAME', 0)
            || !Configuration::updateValue($this->_prefix_st.'SHOW_TIMESTAMP', 0)
            || !Configuration::updateValue($this->_prefix_st.'SHOW_CAPTION', 1)
            || !Configuration::updateValue($this->_prefix_st.'LENGHT_OF_CAPTION', 0)
            || !Configuration::updateValue($this->_prefix_st.'CAPTION_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'CAPTION_ALIGN', 2)
            || !Configuration::updateValue($this->_prefix_st.'BG_HOVER_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'BG_OPACITY', 0.6)
            || !Configuration::updateValue($this->_prefix_st.'CLICK_ACTION', 0)
            || !Configuration::updateValue($this->_prefix_st.'TITLE', 1)
            || !Configuration::updateValue($this->_prefix_st.'GRID', 0)
            || !Configuration::updateValue($this->_prefix_st.'PADDING', 0)
            || !Configuration::updateValue($this->_prefix_st.'HOVER_EFFECT', 1)
            
            || !Configuration::updateValue($this->_prefix_st.'WIDE_ON_FOOTER', '3')
            || !Configuration::updateValue($this->_prefix_st.'HIDE_MOB_COL',0)
            || !Configuration::updateValue($this->_prefix_st.'COUNT_COL', 6)
            || !Configuration::updateValue($this->_prefix_st.'PADDING_COL', 4)
            || !Configuration::updateValue($this->_prefix_st.'PICTURE_SIZE_COL', '')
            
            || !Configuration::updateValue($this->_prefix_stsn.'INSTAGRAM_PRO_PER_FW', 0)
            || !Configuration::updateValue($this->_prefix_stsn.'INSTAGRAM_PRO_PER_XL', 6)
            || !Configuration::updateValue($this->_prefix_stsn.'INSTAGRAM_PRO_PER_LG', 5)
            || !Configuration::updateValue($this->_prefix_stsn.'INSTAGRAM_PRO_PER_MD', 5)
            || !Configuration::updateValue($this->_prefix_stsn.'INSTAGRAM_PRO_PER_SM', 4)
            || !Configuration::updateValue($this->_prefix_stsn.'INSTAGRAM_PRO_PER_XS', 3)
            || !Configuration::updateValue($this->_prefix_stsn.'INSTAGRAM_PRO_PER_XXS', 2)

            || !Configuration::updateValue($this->_prefix_st.'S_SPEED', 7000)
            || !Configuration::updateValue($this->_prefix_st.'A_SPEED', 400)
            || !Configuration::updateValue($this->_prefix_st.'PAUSE_ON_HOVER', 1)
            || !Configuration::updateValue($this->_prefix_st.'REWIND_NAV', 0)
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_NAV', 3)
            || !Configuration::updateValue($this->_prefix_st.'CONTROL_NAV', 0)
            || !Configuration::updateValue($this->_prefix_st.'MOVE', 1)            

            || !Configuration::updateValue($this->_prefix_st.'SLIDESHOW', '')
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
            || !Configuration::updateValue($this->_prefix_st.'TEXT_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_COLOR', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_COLOR_HOVER', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_COLOR_DISABLED', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_HOVER_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'DIRECTION_DISABLED_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'PAG_NAV_BG', '')
            || !Configuration::updateValue($this->_prefix_st.'PAG_NAV_BG_HOVER', '')
            || !Configuration::updateValue($this->_prefix_st.'TITLE_FONT_SIZE', 0)
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
        
    public function getContent()
	{
	    if (Tools::getValue('ajax') == 1)
        {
            if(Tools::getValue('act') == 'get_instagram')
            {
                $result = '';
                switch(Configuration::get($this->_prefix_st.'SHOW_IMAGE'))
                {
                    case 0:
                        $result = $this->getUser();
                        break;
                    case 1:
                        $result = $this->getHashTag();
                        break;
                    case 2:
                        $result = $this->getLiked();
                        break;
                    default:
                        break;
                }
                die($result);
            }
            elseif(Tools::getValue('act') == 'like_media')
            {
                $ret = '';
                $id = Tools::getValue('id_media');
                $id && $ret = $this->likeMedia($id);
                die($result);
            }
            elseif(Tools::getValue('act') == 'delete_liked_media')
            {
                $ret = '';
                $id = Tools::getValue('id_media');
                $id && $ret = $this->deleteLikedMedia($id);
                die($result);
            }
        }
        
        $this->context->controller->addCSS($this->_path.'views/css/admin.css');    
        $this->initFieldsForm();
		if (isset($_POST['save'.$this->name]))
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
            $name = $this->fields_form[0]['form']['input']['dropdownlistgroup']['name'];
            foreach ($this->fields_form[0]['form']['input']['dropdownlistgroup']['values']['medias'] as $v)
            {
                $t_v = (int)Tools::getValue($name.'_'.$v);
                if(Configuration::get($this->_prefix_st.'GRID')==1 && in_array($t_v, array(7,9,11)))
                    $t_v--;
                Configuration::updateValue($this->_prefix_stsn.strtoupper($name.'_'.$v), $t_v);
            }
            
            $this->saveHook();
            
            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else 
            {
	            $this->clearSliderCache();
                $this->_html .= $this->displayConfirmation($this->l('Settings updated'));  
            }
            $this->initFieldsForm();    
        }
		$helper = $this->initForm();
		return $this->_html.$helper->generateForm($this->fields_form);
	}

    public function getPatterns()
    {
        $html = '';
        foreach(range(1,27) as $v)
            $html .= '<div class="parttern_wrap" style="background:url('._MODULE_DIR_.'stthemeeditor/patterns/'.$v.'.png);"><span>'.$v.'</span></div>';
        $html .= '<div>Pattern credits:<a href="http://subtlepatterns.com" target="_blank">subtlepatterns.com</a></div>';
        return $html;
    }
    
    public function getPatternsArray()
    {
        $arr = array();
        for($i=1;$i<=27;$i++)
            $arr[] = array('id'=>$i,'name'=>$i); 
        return $arr;   
    }
    public function initFieldsForm()
    {
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings on homepage'),
                'icon'  => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'html',
                    'id'   => 'current_user',
                    'label' => $this->l('Current instagram user:'),
                    'name' => $this->_grantUserInfo(),
                    'desc' => '<p>'.sprintf($this->l('By using this module, you are agreeing to the %s Instagram API Terms of Use'), '<a href="http://instagram.com/about/legal/terms/api/" target="_blank">').'</a>.</p>'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Client ID:'),
                    'name' => 'client_id',
                    'default_value' => '',
                    'required' => true,
                    'validation' => 'isGenericName',
                    'class' => 'fixed-width-xxl',
                    'desc' => '<p>'.sprintf($this->l('How to get the Client ID ? click %s here %s or %s use default'), '<a href="http://sunnytoo.com/instagram/how_to_get_client_id.html" target="_blank">','</a>','<a href="javascript:;" onclick="$(\'#client_id\').val(\''.self::$client_id.'\');">').'</a>.</p>'.
                    sprintf($this->l('%sImportant%s the %sValid redirect URI%s must be %s when you register new client on instagram.'), '<b>', '</b>', '<b>', '</b>', '<b>http://www.sunnytoo.com/instagram/</b>')
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Show images:'),
                    'name' => 'show_image',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'show_image_0',
                            'value' => 0,
                            'label' => $this->l('Recent Media. Display your most recent  media.')),
                        array(
                            'id' => 'show_image_1',
                            'value' => 1,
                            'label' => $this->l('User Feed. Display the most recent media from the people you follow.')),
                        array(
                            'id' => 'show_image_2',
                            'value' => 2,
                            'label' => $this->l('User likes. Display your most recent liked media.')),
                        array(
                            'id' => 'show_image_3',
                            'value' => '3',
                            'label' => $this->l('Tag. Display the most recent media available using a tag.')),
                    ),
                    'validation' => 'isGenericName',
                ), 
                array(
                    'type' => 'text',
                    'label' => $this->l('Hash tag:'),
                    'name' => 'hash_tag',
                    'default_value' => '',
                    'validation' => 'isGenericName',
                    'class' => 'fixed-width-xxl',
                    'desc' => $this->l('Without "#"')
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('How to display images:'),
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
                            'label' => $this->l('Grid view')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),  
                array(
                    'type' => 'text',
                    'label' => $this->l('The number of photos:'),
                    'name' => 'count',
                    'default_value' => 10,
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Image size:'),
                    'name' => 'image_size',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'low_resolution',
                            'value' => 0,
                            'label' => $this->l('Low resolution 306px x 306px')),
                        array(
                            'id' => 'thumbnail',
                            'value' => 1,
                            'label' => $this->l('Thumbnail 150px x 150px')),
                        array(
                            'id' => 'standard_resolution',
                            'value' => 2,
                            'label' => $this->l('Standard resolution 612px x 612px')),
                    ),
                    'validation' => 'isGenericName',
                ),   
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show likes counter:'),
                    'name' => 'show_likes',
                    'is_bool' => true,
                    'default_value' => 1,
                    'values' => array(
                        array(
                            'id' => 'show_likes_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'show_likes_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show comments counter:'),
                    'name' => 'show_comments',
                    'is_bool' => true,
                    'default_value' => 1,
                    'values' => array(
                        array(
                            'id' => 'show_comments_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'show_comments_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show time:'),
                    'name' => 'show_timestamp',
                    'is_bool' => true,
                    'default_value' => 1,
                    'values' => array(
                        array(
                            'id' => 'show_timestamp_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'show_timestamp_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show username:'),
                    'name' => 'show_username',
                    'is_bool' => true,
                    'default_value' => 1,
                    'values' => array(
                        array(
                            'id' => 'show_username_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'show_username_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
					'type' => 'radio',
					'label' => $this->l('Show description:'),
					'name' => 'show_caption',
                    'default_value' => 0,
                    'validation' => 'isUnsignedInt',
					'values' => array(
                        array(
                            'id' => 'show_caption_0',
                            'value' => 0,
                            'label' => $this->l('No')),
						array(
							'id' => 'show_caption_1',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'show_caption_2',
							'value' => 2,
							'label' => $this->l('On PC only (screen width > 768px)')),
					),
				),
                array(
					'type' => 'radio',
					'label' => $this->l('The lenght of description:'),
					'name' => 'lenght_of_caption',
                    'default_value' => 0,
                    'validation' => 'isUnsignedInt',
					'values' => array(
						array(
							'id' => 'lenght_of_caption_0',
							'value' => 0,
							'label' => $this->l('Full caption')),
						array(
							'id' => 'lenght_of_caption_1',
							'value' => 1,
							'label' => $this->l('Truncated (100 characters)')),
					),
				),
                array(
					'type' => 'radio',
					'label' => $this->l('When click on a picture:'),
					'name' => 'click_action',
                    'default_value' => 0,
                    'validation' => 'isUnsignedInt',
					'values' => array(
						array(
							'id' => 'click_action_0',
							'value' => 0,
							'label' => $this->l('View the picture in Instagram.')),
						array(
							'id' => 'click_action_1',
							'value' => 1,
							'label' => $this->l('Show the picture in a lightbox.')),
					),
				),
                array(
                    'type' => 'text',
                    'label' => $this->l('Space between images:'),
                    'name' => 'padding',
                    'default_value' => '',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'validation' => 'isUnsignedInt',
                    'desc' => $this->l('For grid layout. Leave it empty to use the default value.'),
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Hover effect:'),
                    'name' => 'hover_effect',
                    'default_value' => 4,
                    'validation' => 'isUnsignedInt',
                    'values' => array(
                        array(
                            'id' => 'hover_effect_0',
                            'value' => 0,
                            'label' => $this->l('None')
                        ),
                        array(
                            'id' => 'hover_effect_1',
                            'value' => 1,
                            'label' => $this->l('Scale up')
                        ),
                    ),
                ),
                'dropdownlistgroup' => array(
                    'type' => 'dropdownlistgroup',
                    'label' => $this->l('The number of columns on home page:'),
                    'name' => 'instagram_pro_per',
                    'values' => array(
                            'maximum' => 12,
                            'medias' => array('fw','xl','lg','md','sm','xs','xxs'),
                        ),
                    'desc' => $this->l('7, 9 and 11 can not be used in grid view, they will be automatically decreased to 6, 8 and 10.'),
                ), 
                /*array(
                    'type' => 'radio',
                    'label' => $this->l('Caption alignment:'),
                    'name' => 'caption_align',
                    'default_value' => 2,
                    'validation' => 'isUnsignedInt',
                    'values' => array(
                        array(
                            'id' => 'text_align_left',
                            'value' => 1,
                            'label' => $this->l('Left')),
                        array(
                            'id' => 'text_align_center',
                            'value' => 2,
                            'label' => $this->l('Center')),
                        array(
                            'id' => 'text_align_right',
                            'value' => 3,
                            'label' => $this->l('Right')),
                    ),
                ),*/
                array(
                    'type' => 'color',
                    'label' => $this->l('Text color:'),
                    'name' => 'caption_color',
                    'size' => 33,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Text background color when mouseover:'),
                    'name' => 'bg_hover_color',
                    'size' => 33,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Text background color opacity:'),
                    'name' => 'bg_opacity',
                    'validation' => 'isFloat',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('From 0.0 (fully transparent) to 1.0 (fully opaque).'),
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
                    'default_value' => '',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'validation' => 'isNullOrUnsignedId',
                    'desc' => $this->l('Leave it empty to use the default value.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Bottom spacing:'),
                    'name' => 'bottom_margin',
                    'default_value' => '',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'validation' => 'isNullOrUnsignedId',
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
                    'label' => $this->l('Show block title:'),
                    'name' => 'title',
                    'default_value' => 1,
                    'validation' => 'isUnsignedInt',
                    'values' => array(
                        array(
                            'id' => 'title_no',
                            'value' => 0,
                            'label' => $this->l('No')),
                        array(
                            'id' => 'title_left',
                            'value' => 1,
                            'label' => $this->l('Left')),
                        array(
                            'id' => 'title_center',
                            'value' => 2,
                            'label' => $this->l('Center')),
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Block title font size:'),
                    'name' => 'title_font_size',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'validation' => 'isUnsignedInt',
                ), 
                 array(
                    'type' => 'color',
                    'label' => $this->l('Block title color:'),
                    'name' => 'title_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),         
                array(
                    'type' => 'radio',
                    'label' => $this->l('Hide on mobile:'),
                    'name' => 'hide_on_mobile',
                    'default_value' => 0,
                    'validation' => 'isUnsignedInt',
                    'values' => array(
                        array(
                            'id' => 'hide_on_mobile_0',
                            'value' => 0,
                            'label' => $this->l('No')),
                        array(
                            'id' => 'hide_on_mobile_1',
                            'value' => 1,
                            'label' => $this->l('Hide on mobile (screen width < 768px)')),
                        array(
                            'id' => 'hide_on_mobile_2',
                            'value' => 2,
                            'label' => $this->l('Hide on PC (screen width > 768px)')),
                    ),
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all   ')
            ),
        );
        $this->fields_form[1]['form'] = array(
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
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one. Default: 7000'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Transition period:'),
                    'name' => 'a_speed',
                    'default_value' => 400,
                    'desc' => $this->l('The period, in milliseconds, of the transition effect. Default: 400'),
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
                    'type' => 'switch',
                    'label' => $this->l('Rewind to first after the last slide:'),
                    'name' => 'rewind_nav',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'rewind_nav_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'rewind_nav_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Scroll:'),
                    'name' => 'move',
                    'default_value' => 1,
                    'values' => array(
                        array(
                            'id' => 'move_on',
                            'value' => 1,
                            'label' => $this->l('Scroll per page')),
                        array(
                            'id' => 'move_off',
                            'value' => 0,
                            'label' => $this->l('Scroll per item')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Display "next" and "prev" buttons:'),
                    'name' => 'direction_nav',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'none',
                            'value' => 0,
                            'label' => $this->l('None')),
                        array(
                            'id' => 'top-right',
                            'value' => 1,
                            'label' => $this->l('Top right-hand side')),
                        array(
                            'id' => 'square',
                            'value' => 3,
                            'label' => $this->l('Square')),
                        array(
                            'id' => 'circle',
                            'value' => 4,
                            'label' => $this->l('Circle')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show navigation:'),
                    'name' => 'control_nav',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'control_nav_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'control_nav_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
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
                    'label' => $this->l('Prev/next hover color:'),
                    'name' => 'direction_color_hover',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Prev/next disabled color:'),
                    'name' => 'direction_color_disabled',
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
                 array(
                    'type' => 'color',
                    'label' => $this->l('Navigation color:'),
                    'name' => 'pag_nav_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),  
                 array(
                    'type' => 'color',
                    'label' => $this->l('Navigation active color:'),
                    'name' => 'pag_nav_bg_hover',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all   ')
            ),
        );
        $this->fields_form[2]['form'] = array(
			'legend' => array(
				'title' => $this->l('Settings on sidebar/X quarter'),
                'icon'  => 'icon-cogs'
			),
			'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show profile:'),
                    'name' => 'show_profile',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'show_profile_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'show_profile_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show post counts, follower counts and following counts:'),
                    'name' => 'show_counts',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'show_counts_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'show_counts_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
					'type' => 'text',
					'label' => $this->l('The number of photos'),
					'name' => 'count_col',
                    'default_value' => 6,
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
                    'type' => 'text',
                    'label' => $this->l('Image size:'),
                    'name' => 'picture_size_col',
                    'default_value' => '',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'validation' => 'isNullOrUnsignedId',
                    'desc' => $this->l('Default 80'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Space between images:'),
                    'name' => 'padding_col',
                    'default_value' => '',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'validation' => 'isNullOrUnsignedId',
                    'desc' => $this->l('For grid layout. Leave it empty to use the default value.'),
                ),
                array(
					'type' => 'switch',
					'label' => $this->l('Hide on mobile:'),
					'name' => 'hide_mob_col',
                    'default_value' => 0,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'hide_mob_col_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'hide_mob_col_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('screen width < 768px.'),
                    'validation' => 'isBool',
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
				'title' => $this->l('   Save all   ')
			),
		);
        $this->fields_form[3]['form'] = array(
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
            $this->fields_form[3]['form']['input'][] = array(
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
        $helper->module = $this;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'save'.$this->name;
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
        $this->context->controller->addJS($this->_path.'views/js/stinstagram.js');
        if (!$this->isCached('header.tpl', $this->getCacheId()))
        {
            $custom_css = '';
            
            $group_css = '';

            if ($caption_color = Configuration::get($this->_prefix_st.'CAPTION_COLOR'))
                 $custom_css .= '.ins_image_box .ins_image_info, .ins_external{color:'.$caption_color.';}';
            if ($bg_hover_color = Configuration::get($this->_prefix_st.'BG_HOVER_COLOR'))
            {
                $custom_css .= '.ins_image_box .ins_image_info{background:'.$bg_hover_color.';}';  
                $bg_hover_color_arr = self::hex2rgb($bg_hover_color);
                if(is_array($bg_hover_color_arr))
                {
                    $bg_opacity = (float)Configuration::get($this->_prefix_st.'BG_OPACITY');
                    if($bg_opacity<0 || $bg_opacity>1)
                        $bg_opacity = 0.6;
                    $custom_css .= '.ins_image_box .ins_image_info{background:rgba('.$bg_hover_color_arr[0].','.$bg_hover_color_arr[1].','.$bg_hover_color_arr[2].','.$bg_opacity.');}';  
                }
            }

            if ($padding = Configuration::get($this->_prefix_st.'PADDING'))
                $custom_css .= '.instagram_block_center_container .ins_slider_outer{padding:0 '.$padding.'px;}.instagram_con.com_grid_view.row{margin-left: -'.$padding.'px;margin-right: -'.$padding.'px;}.instagram_con.com_grid_view.row li{padding:'.$padding.'px;}';

            if ($padding_col = Configuration::get($this->_prefix_st.'PADDING_COL'))
                $custom_css .= '.instagram_block_footer .instagram_list li{padding:'.$padding_col.'px;}';

            if ($bg_color = Configuration::get($this->_prefix_st.'BG_COLOR'))
                $group_css .= 'background-color:'.$bg_color.';';
            if ($bg_img = Configuration::get($this->_prefix_st.'BG_IMG'))
            {
                $this->fetchMediaServer($bg_img);
                $group_css .= 'background-image: url('.$bg_img.');';
            }
            elseif ($bg_pattern = Configuration::get($this->_prefix_st.'BG_PATTERN'))
            {
                $img = _MODULE_DIR_.'stthemeeditor/patterns/'.$bg_pattern.'.png';
                $img = $this->context->link->protocol_content.Tools::getMediaServer($img).$img;
                $group_css .= 'background-image: url('.$img.');';
            }
            if($group_css)
                $custom_css .= '.instagram_block_center_container{background-attachment:fixed;'.$group_css.'}.instagram_block_center_container .section .title_block, .instagram_block_center_container .nav_top_right .flex-direction-nav,.instagram_block_center_container .section .title_block a, .instagram_block_center_container .section .title_block span{background:none;}';

            if ($top_padding = (int)Configuration::get($this->_prefix_st.'TOP_PADDING'))
                $custom_css .= '.instagram_block_center_container{padding-top:'.$top_padding.'px;}';
            if ($bottom_padding = (int)Configuration::get($this->_prefix_st.'BOTTOM_PADDING'))
                $custom_css .= '.instagram_block_center_container{padding-bottom:'.$bottom_padding.'px;}';

            $top_margin = Configuration::get($this->_prefix_st.'TOP_MARGIN');
            if($top_margin || $top_margin!==null)
                $custom_css .= '.instagram_block_center_container{margin-top:'.$top_margin.'px;}';
            $bottom_margin = Configuration::get($this->_prefix_st.'BOTTOM_MARGIN');
            if($bottom_margin || $bottom_margin!==null)
                $custom_css .= '.instagram_block_center_container{margin-bottom:'.$bottom_margin.'px;}';
            $picture_size_col = Configuration::get($this->_prefix_st.'PICTURE_SIZE_COL');
            if($picture_size_col || $picture_size_col!==null)
                $custom_css .= '.instagram_list img{width:'.$picture_size_col.'px;}';

            if ($title_font_size = (int)Configuration::get($this->_prefix_st.'TITLE_FONT_SIZE'))
                 $custom_css .= '.instagram_block_center_container .title_block{font-size:'.$title_font_size.'px;line-height:150%;}';

            if ($title_color = Configuration::get($this->_prefix_st.'TITLE_COLOR'))
                $custom_css .= '.instagram_block_center_container.block .title_block a, .instagram_block_center_container.block .title_block span{color:'.$title_color.';}';

            if ($text_color = Configuration::get($this->_prefix_st.'TEXT_COLOR'))
                $custom_css .= '.ins_image_info{color:'.$text_color.';}';

            if ($direction_color = Configuration::get($this->_prefix_st.'DIRECTION_COLOR'))
                $custom_css .= '.instagram_block_center_container .products_slider .owl-theme.owl-navigation-tr .owl-controls .owl-buttons div, .instagram_block_center_container .products_slider .owl-theme.owl-navigation-lr .owl-controls .owl-buttons div{color:'.$direction_color.';}';
            if ($direction_color_hover = Configuration::get($this->_prefix_st.'DIRECTION_COLOR_HOVER'))
                $custom_css .= '.instagram_block_center_container .products_slider .owl-theme.owl-navigation-tr .owl-controls .owl-buttons div:hover, .instagram_block_center_container .products_slider .owl-theme.owl-navigation-lr .owl-controls .owl-buttons div:hover{color:'.$direction_color_hover.';}';
            if ($direction_color_disabled = Configuration::get($this->_prefix_st.'DIRECTION_COLOR_DISABLED'))
                $custom_css .= '.instagram_block_center_container .products_slider .owl-theme.owl-navigation-tr .owl-controls .owl-buttons div.disabled, .instagram_block_center_container .products_slider .owl-theme.owl-navigation-tr .owl-controls .owl-buttons div.disabled:hover, .instagram_block_center_container .products_slider .owl-theme.owl-navigation-lr .owl-controls .owl-buttons div.disabled, .instagram_block_center_container .products_slider .owl-theme.owl-navigation-lr .owl-controls .owl-buttons div.disabled:hover{color:'.$direction_color_disabled.';}';
            
            if ($direction_bg = Configuration::get($this->_prefix_st.'DIRECTION_BG'))
                $custom_css .= '.instagram_block_center_container .products_slider .owl-theme.owl-navigation-tr .owl-controls .owl-buttons div, .instagram_block_center_container .products_slider .owl-theme.owl-navigation-lr.owl-navigation-rectangle .owl-controls .owl-buttons div, .instagram_block_center_container .products_slider .owl-theme.owl-navigation-lr.owl-navigation-circle .owl-controls .owl-buttons div{background-color:'.$direction_bg.';}';
            if ($direction_hover_bg = Configuration::get($this->_prefix_st.'DIRECTION_HOVER_BG'))
                $custom_css .= '.instagram_block_center_container .products_slider .owl-theme.owl-navigation-tr .owl-controls .owl-buttons div:hover, .instagram_block_center_container .products_slider .owl-theme.owl-navigation-lr.owl-navigation-rectangle .owl-controls .owl-buttons div:hover, instagram_block_center_container .products_slider .owl-theme.owl-navigation-lr.owl-navigation-circle .owl-controls .owl-buttons div:hover{background-color:'.$direction_hover_bg.';}';
            if ($direction_disabled_bg = Configuration::get($this->_prefix_st.'DIRECTION_DISABLED_BG'))
                $custom_css .= '.instagram_block_center_container .products_slider .owl-theme.owl-navigation-tr .owl-controls .owl-buttons div.disabled,.instagram_block_center_container .products_slider .owl-theme.owl-navigation-tr .owl-controls .owl-buttons div.disabled:hover, .instagram_block_center_container .products_slider .owl-theme.owl-navigation-lr.owl-navigation-rectangle .owl-controls .owl-buttons div.disabled, .instagram_block_center_container .products_slider .owl-theme.owl-navigation-lr.owl-navigation-circle .owl-controls .owl-buttons div.disabled,.instagram_block_center_container .products_slider .owl-theme.owl-navigation-lr.owl-navigation-rectangle .owl-controls .owl-buttons div.disabled:hover, .instagram_block_center_container .products_slider .owl-theme.owl-navigation-lr.owl-navigation-circle .owl-controls .owl-buttons div.disabled:hover{background-color:'.$direction_disabled_bg.';}';
            else
                $custom_css .= '.instagram_block_center_container .products_slider .owl-theme.owl-navigation-tr .owl-controls .owl-buttons div.disabled,.instagram_block_center_container .products_slider .owl-theme.owl-navigation-tr .owl-controls .owl-buttons div.disabled:hover{background-color:transplanted;}';

            if ($pag_nav_bg = Configuration::get($this->_prefix_st.'PAG_NAV_BG'))
                $custom_css .= '.instagram_block_center_container .products_slider .owl-theme .owl-controls .owl-page span{background-color:'.$pag_nav_bg.';}';
            if ($pag_nav_bg_hover = Configuration::get($this->_prefix_st.'PAG_NAV_BG_HOVER'))
                $custom_css .= '.instagram_block_center_container .products_slider .owl-theme .owl-controls .owl-page.active span, .instagram_block_center_container .products_slider .owl-theme .owl-controls .owl-page:hover span{background-color:'.$pag_nav_bg_hover.';}';

            if($custom_css)
                $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
        }
        return $this->display(__FILE__, 'header.tpl', $this->getCacheId());
    }
    private function _prepareHook()
    {
        $this->smarty->assign(array(
            'ins_client_id'    => Configuration::get($this->_prefix_st.'CLIENT_ID') ? Configuration::get($this->_prefix_st.'CLIENT_ID') : self::$client_id,
            // 'ins_user_id'      => $fields['user_id'],
            'ins_image_size'   => (int)Configuration::get($this->_prefix_st.'IMAGE_SIZE'),
            'ins_show_caption'      => Configuration::get($this->_prefix_st.'SHOW_CAPTION'),
            'ins_show_profile'      => (int)Configuration::get($this->_prefix_st.'SHOW_PROFILE'),
            'ins_show_counts'      => (int)Configuration::get($this->_prefix_st.'SHOW_COUNTS'),
            'ins_show_likes'      => (int)Configuration::get($this->_prefix_st.'SHOW_LIKES'),
            'ins_show_comments'      => (int)Configuration::get($this->_prefix_st.'SHOW_COMMENTS'),
            'ins_show_username'      => (int)Configuration::get($this->_prefix_st.'SHOW_USERNAME'),
            'ins_show_timestamp'      => (int)Configuration::get($this->_prefix_st.'SHOW_TIMESTAMP'),            
            'ins_show_image'      => (int)Configuration::get($this->_prefix_st.'SHOW_IMAGE'),
            'ins_hash_tag'      => Configuration::get($this->_prefix_st.'HASH_TAG'),
            'ins_grid'      => (int)Configuration::get($this->_prefix_st.'GRID'),
            'ins_title_position'        => Configuration::get($this->_prefix_st.'TITLE'),
            'ins_direction_nav'         => Configuration::get($this->_prefix_st.'DIRECTION_NAV'),
            'ins_control_nav'           => Configuration::get($this->_prefix_st.'CONTROL_NAV'),
            'ins_slideshow'           => Configuration::get($this->_prefix_st.'SLIDESHOW'),
            'ins_rewind_nav'            => Configuration::get($this->_prefix_st.'REWIND_NAV'),
            'ins_slider_s_speed'            => Configuration::get($this->_prefix_st.'S_SPEED'),
            'ins_slider_a_speed'            => Configuration::get($this->_prefix_st.'A_SPEED'),
            'ins_slider_pause_on_hover'            => Configuration::get($this->_prefix_st.'PAUSE_ON_HOVER'),
            'ins_lenght_of_caption'            => Configuration::get($this->_prefix_st.'LENGHT_OF_CAPTION'),
            'ins_hover_effect'            => (int)Configuration::get($this->_prefix_st.'HOVER_EFFECT'),
            'ins_click_action'            => (int)Configuration::get($this->_prefix_st.'CLICK_ACTION'),
            'ins_move'            => (int)Configuration::get($this->_prefix_st.'MOVE'),
        ));
        return true;
    }
    public function hookDisplayHomeTop($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    public function hookDisplayHomeBottom($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__));
    }
    
    public function hookDisplayTopColumn($params)
    {
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__) ,2);
    } 
    
    public function hookDisplayBottomColumn($params)
    {
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
    
	public function hookDisplayHome($params, $hook_hash = '', $flag=0)
	{
        if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
	    if (!$this->isCached('stinstagram.tpl', $this->stGetCacheId($hook_hash)))
    	{
            if(!$this->_prepareHook())
                return false;

            $this->smarty->assign(array(
                'column_slider'         => false,
                'homeverybottom'         => ($flag==2 ? true : false),
                'hook_hash'              => $hook_hash,
                'hide_mob'              => Configuration::get($this->_prefix_st.'HIDE_ON_MOBILE'),
                'ins_count'        => Configuration::get($this->_prefix_st.'COUNT'),
                'ins_items_fw'       => (int)Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_FW'),
                'ins_items_xl'       => (int)Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_XL'),
                'ins_items_lg'       => (int)Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_LG'),
                'ins_items_md'       => (int)Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_MD'),
                'ins_items_sm'       => (int)Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_SM'),
                'ins_items_xs'       => (int)Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_XS'),
                'ins_items_xxs'      => (int)Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_XXS'),
            ));
        }
		return $this->display(__FILE__, 'stinstagram.tpl', $this->stGetCacheId($hook_hash));
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
    public function hookDisplayHomeVeryBottom($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__) ,2);
    }
    
	public function hookDisplayLeftColumn($params, $hook_hash = '')
	{
        $this->smarty->assign(array(
            'column_slider'         => true,
        ));
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));
	}
	public function hookDisplayRightColumn($params)
    {
        $this->smarty->assign(array(
            'column_slider'         => true,
        ));
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));
    }
    
    public function hookDisplayHomeSecondaryLeft($params)
    {
        $this->smarty->assign(array(
            'column_slider'         => true,
        ));
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));
    }
	public function hookDisplayHomeSecondaryRight($params)
    {
        $this->smarty->assign(array(
            'is_homepage_secondary_left' => true,
        ));
        return $this->hookDisplayHome($params, $this->getHookHash(__FUNCTION__)); 
    }
	public function hookDisplayProductSecondaryColumn($params)
	{
        $this->smarty->assign(array(
            'column_slider'         => true,
        ));
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));
	}
	public function hookDisplayStBlogLeftColumn($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
        $this->smarty->assign(array(
            'column_slider'         => true,
        ));
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));
	}
	public function hookDisplayStBlogRightColumn($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
        $this->smarty->assign(array(
            'column_slider'         => true,
        ));
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));
	}
    public function hookDisplayFooter($params, $hook_hash = '')
    {
        if (!$hook_hash)
            $hook_hash = $this->getHookHash(__FUNCTION__);
	    if (!$this->isCached('stinstagram-footer.tpl', $this->stGetCacheId($hook_hash)))
	    {
            if(!$this->_prepareHook())
                return false;

            $this->smarty->assign(array(
                'hook_hash'   => $hook_hash,
                'footer_wide' => Configuration::get($this->_prefix_st.'WIDE_ON_FOOTER'),
                'ins_count'   => Configuration::get($this->_prefix_st.'COUNT_COL'),
                'hide_mob'              => Configuration::get($this->_prefix_st.'HIDE_MOB_COL'),
            ));    
	    }
		return $this->display(__FILE__, 'stinstagram-footer.tpl', $this->stGetCacheId($hook_hash));
    }
    public function hookDisplayFooterTop($params)
    {
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));
    }
    public function hookDisplayFooterSecondary($params)
    {
        return $this->hookDisplayFooter($params, $this->getHookHash(__FUNCTION__));        
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
    private function getConfigFieldsValues()
    {
        $fields_values = array(
            'client_id'         => Configuration::get($this->_prefix_st.'CLIENT_ID'),
            'show_image'        => Configuration::get($this->_prefix_st.'SHOW_IMAGE'),
            'hash_tag'          => Configuration::get($this->_prefix_st.'HASH_TAG'),
            'count'             => Configuration::get($this->_prefix_st.'COUNT'),
            'image_size'        => Configuration::get($this->_prefix_st.'IMAGE_SIZE'),
            'hide_on_mobile'    => Configuration::get($this->_prefix_st.'HIDE_ON_MOBILE'),
            'show_likes'      => Configuration::get($this->_prefix_st.'SHOW_LIKES'),
            'show_profile'      => Configuration::get($this->_prefix_st.'SHOW_PROFILE'),
            'show_counts'      => Configuration::get($this->_prefix_st.'SHOW_COUNTS'),
            'show_comments'      => Configuration::get($this->_prefix_st.'SHOW_COMMENTS'),
            'show_timestamp'      => Configuration::get($this->_prefix_st.'SHOW_TIMESTAMP'),
            'show_username'      => Configuration::get($this->_prefix_st.'SHOW_USERNAME'),
            'show_caption'      => Configuration::get($this->_prefix_st.'SHOW_CAPTION'),
            'lenght_of_caption' => Configuration::get($this->_prefix_st.'LENGHT_OF_CAPTION'),
            'caption_color'     => Configuration::get($this->_prefix_st.'CAPTION_COLOR'),
            'caption_align'     => Configuration::get($this->_prefix_st.'CAPTION_ALIGN'),
            'bg_hover_color'    => Configuration::get($this->_prefix_st.'BG_HOVER_COLOR'),
            'bg_opacity'    => Configuration::get($this->_prefix_st.'BG_OPACITY'),
            'click_action'      => Configuration::get($this->_prefix_st.'CLICK_ACTION'),
            'title'             => Configuration::get($this->_prefix_st.'TITLE'),
            'grid'             => Configuration::get($this->_prefix_st.'GRID'),
            'padding'           => Configuration::get($this->_prefix_st.'PADDING'),
            'hover_effect'      => Configuration::get($this->_prefix_st.'HOVER_EFFECT'),
            
            'instagram_pro_per_fw'  => Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_FW'),
            'instagram_pro_per_xl'  => Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_XL'),
            'instagram_pro_per_lg'  => Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_LG'),
            'instagram_pro_per_md'  => Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_MD'),
            'instagram_pro_per_sm'  => Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_SM'),
            'instagram_pro_per_xs'  => Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_XS'),
            'instagram_pro_per_xxs' => Configuration::get($this->_prefix_stsn.'INSTAGRAM_PRO_PER_XXS'),
            
            'wide_on_footer'    => Configuration::get($this->_prefix_st.'WIDE_ON_FOOTER'),
            'hide_mob_col'      => Configuration::get($this->_prefix_st.'HIDE_MOB_COL'),
            'count_col'         => Configuration::get($this->_prefix_st.'COUNT_COL'),
            'padding_col'         => Configuration::get($this->_prefix_st.'PADDING_COL'),
            'picture_size_col'         => Configuration::get($this->_prefix_st.'PICTURE_SIZE_COL'),
            
            's_speed'            => Configuration::get($this->_prefix_st.'S_SPEED'),
            'a_speed'            => Configuration::get($this->_prefix_st.'A_SPEED'),
            'pause_on_hover'     => Configuration::get($this->_prefix_st.'PAUSE_ON_HOVER'),
            'rewind_nav'         => Configuration::get($this->_prefix_st.'REWIND_NAV'),
            'direction_nav'      => Configuration::get($this->_prefix_st.'DIRECTION_NAV'),
            'control_nav'        => Configuration::get($this->_prefix_st.'CONTROL_NAV'),
            'move'        => Configuration::get($this->_prefix_st.'MOVE'),

            'slideshow'        => Configuration::get($this->_prefix_st.'SLIDESHOW'),
            'top_padding'        => Configuration::get($this->_prefix_st.'TOP_PADDING'),
            'bottom_padding'     => Configuration::get($this->_prefix_st.'BOTTOM_PADDING'),
            'top_margin'         => Configuration::get($this->_prefix_st.'TOP_MARGIN'),
            'bottom_margin'      => Configuration::get($this->_prefix_st.'BOTTOM_MARGIN'),
            'bg_pattern'         => Configuration::get($this->_prefix_st.'BG_PATTERN'),
            'bg_img'             => Configuration::get($this->_prefix_st.'BG_IMG'),
            'bg_color'           => Configuration::get($this->_prefix_st.'BG_COLOR'),
            'speed'              => Configuration::get($this->_prefix_st.'SPEED'),

            'title_color'              => Configuration::get($this->_prefix_st.'TITLE_COLOR'),
            'title_hover_color'        => Configuration::get($this->_prefix_st.'TITLE_HOVER_COLOR'),
            'text_color'               => Configuration::get($this->_prefix_st.'TEXT_COLOR'),
            'direction_color'          => Configuration::get($this->_prefix_st.'DIRECTION_COLOR'),
            'direction_color_hover'    => Configuration::get($this->_prefix_st.'DIRECTION_COLOR_HOVER'),
            'direction_color_disabled' => Configuration::get($this->_prefix_st.'DIRECTION_COLOR_DISABLED'),
            'direction_bg'             => Configuration::get($this->_prefix_st.'DIRECTION_BG'),
            'direction_hover_bg'       => Configuration::get($this->_prefix_st.'DIRECTION_HOVER_BG'),
            'direction_disabled_bg'    => Configuration::get($this->_prefix_st.'DIRECTION_DISABLED_BG'),
            'pag_nav_bg'               => Configuration::get($this->_prefix_st.'PAG_NAV_BG'),
            'pag_nav_bg_hover'         => Configuration::get($this->_prefix_st.'PAG_NAV_BG_HOVER'),
            'title_font_size'          => Configuration::get($this->_prefix_st.'TITLE_FONT_SIZE'),
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
    public function getHookHash($func='')
    {
        if (!$func)
            return '';
        return substr(md5($func), 0, 10);
    }
    private function _grantUserInfo()
    {
        if (!Configuration::get($this->_prefix_st.'CLIENT_ID'))
        {
            return $this->displayError('Please put the Client ID firstly.');
        }
        if (Tools::getValue('act') == 'delete_access_token')
        {
            Configuration::updateValue($this->_prefix_st.'ACCESS_TOKEN', 0);
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
        }
            
        if ($access_token = Tools::getValue('access_token'))
            Configuration::updateValue($this->_prefix_st.'ACCESS_TOKEN', $access_token);
        
        if ($access_token = Configuration::get($this->_prefix_st.'ACCESS_TOKEN'))
        {
            $result = $this->getUserInfo();
            if (isset($result['error']) && $result['error'])
                return '<p style="color:#f00;">'.$result['message'].'</p>';
            if (is_array($result) && count($result) > 0)
                return '<div><img src="'.$result['data']['profile_picture'].'" /><p>'.$result['data']['username'].'<br/><a href="'.$this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&act=delete_access_token&token='.Tools::getAdminTokenLite('AdminModules').'">'.$this->l('Unlink to instagram.').'</a></p></div>';
            else
                return '<p style="color:#f00;">'.$result.'</p>';
        }
        else
        {
            $this->smarty->assign(array(
                'client_id' => Configuration::get($this->_prefix_st.'CLIENT_ID') ? Configuration::get($this->_prefix_st.'CLIENT_ID') : self::$client_id,
                'redirect_uri' => 'http://www.sunnytoo.com/instagram/',
                'response_type' => 'token',
                'action' => 'https://api.instagram.com/oauth/authorize/',
                'scope' => implode(' ', array('public_content','likes')),
            ));
            return $this->display(__FILE__, 'user-form.tpl');
        }
    }
    
    public function getUserInfo()
    {
        return $this->makeCall('users/self', null);
    }
    
    public function getUser()
    {
        return $this->makeCall('users/self/media/recent');
    }
    
    public function getHashTag()
    {
        $result = array();
        $hashtag = Configuration::get($this->_prefix_st . 'HASH_TAG');
        if (!$hashtag)
            return $result;
        foreach(explode(',', $hashtag) AS $tag)
        {
            $result = array_merge_recursive($result, $this->makeCall('tags/' . $tag . '/media/recent'));
        }
        return $result;
    }
    
    public function getMedia()
    {
        $media = '';
        return $this->makeCall('media/' . $media);
    }
    
    public function getLiked()
    {
        return $this->makeCall('users/self/media/liked');
    }
    
    public function likeMedia($id) 
    {
        return $this->makeCall('media/' . $id . '/likes', array(), 'POST');
    }
    
    public function deleteLikedMedia($id)
    {
        return $this->makeCall('media/' . $id . '/likes', array(), 'DELETE');
    }
    
    public function makeCall($function, $params = array(), $method = 'GET')
    {
        $ret = array('error' => true,'message' => '');
        $api_url = 'https://api.instagram.com/v1/';
        if (!$access_token = Configuration::get($this->_prefix_st.'ACCESS_TOKEN'))
        {
            $ret['message'] = $this->l('Access token error.');
            return $ret;
        }
        
        if (!$function)
        {
            $ret['message'] = $this->l('function error.');
            return $ret;
        }
        
        $params = (array)$params;
        if ($method == 'GET' && (!isset($params['count']) || !$params['count']))
        {
            $count = Configuration::get($this->_prefix_st.'COUNT') ? Configuration::get($this->_prefix_st.'COUNT') : '32';
            $params['count'] = $count;
        }
        
        if (is_array($params) && count($params))
          $paramString = '&' . http_build_query($params);
        else
          $paramString = null;
        
        $api_url = $api_url . trim($function, '/').'/?access_token=' . $access_token . (('GET' === $method) ? $paramString : null);
        
        try 
        {
            $curl_connection = curl_init($api_url);
            curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
            
            if ('POST' == $method)
            {
              curl_setopt($ch, CURLOPT_POST, count($params));
              curl_setopt($ch, CURLOPT_POSTFIELDS, ltrim($paramString, '&'));
            } 
            elseif ('DELETE' == $method)
            {
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            }
            
            $data = json_decode(curl_exec($curl_connection), true);
            curl_close($curl_connection);
            if (!$data)
            {
                $ret['message'] = $this->l('No data, pleaes reload this page to retry.');
                return $ret;
            }
            return $data;
        } 
        catch(Exception $e)
        {
            $ret['message'] = $this->l('Can\'t receive data from instagram.com, please reload page to retry.').$e->getMessage();
            return $ret;
        } 
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
    
    public function get_prefix()
    {
        if (isset($this->_prefix_st) && $this->_prefix_st)
            return $this->_prefix_st;
        return false;
    }
}