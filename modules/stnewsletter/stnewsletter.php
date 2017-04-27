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

include_once dirname(__FILE__).'/StNewsLetterClass.php';

class StNewsLetter extends Module
{
    public $imgtype = array('jpg', 'gif', 'jpeg', 'png');
    protected static $access_rights = 0775;
    private $_pages = array();
    private $_html = '';
    public  $fields_list;
    public $fields_form;
    public $fields_value;
    private $validation_errors = array();
    private $_prefix_st = 'ST_NW_';

    /** @var array Website domain for setcookie() */
    protected $_cookie_domain;

    /** @var array Path for setcookie() */
    protected $_cookie_path;
    public static $location = array(
        4 => array('id' =>4 , 'name' => 'Popup'),
        36 => array('id' =>36 , 'name' => 'Full width top', 'full_width' => 1),
        31 => array('id' =>31 , 'name' => 'Full width top 2', 'full_width' => 1),
        16 => array('id' =>16 , 'name' => 'Homepage top'),
        1 => array('id' =>1 , 'name' => 'Homepage'),
        17 => array('id' =>17 , 'name' => 'Homepage bottom'),
        14 => array('id' =>14 , 'name' => 'Homepage secondary left'),
        15 => array('id' =>15 , 'name' => 'Homepage secondary right'),
        29 => array('id' =>29 , 'name' => 'Homepage tertiary left'),
        30 => array('id' =>30 , 'name' => 'Homepage tertiaryRight'),
        35 => array('id' =>35 , 'name' => 'Top column'),
        28 => array('id' =>28 , 'name' => 'Bottom column'),
        37 => array('id' =>37 , 'name' => 'Full width Bottom(Home very bottom)', 'full_width' => 1),
        2 => array('id' =>2 , 'name' => 'Left column', 'column'=>1),
        10 => array('id' =>10 , 'name' => 'Right column', 'column'=>1),

        13 => array('id' =>13 , 'name' => 'Footer top (3/12 wide)', 'span' => '3'),
        38 => array('id' =>38 , 'name' => 'Footer top (2/12 wide)', 'span' => '2'),
        55 => array('id' =>55 , 'name' => 'Footer top (2.4/12 wide)', 'span' => '2-4'),
        39 => array('id' =>39 , 'name' => 'Footer top (4/12 wide)', 'span' => '4'),
        40 => array('id' =>40 , 'name' => 'Footer top (5/12 wide)', 'span' => '5'),
        41 => array('id' =>41 , 'name' => 'Footer top (6/12 wide)', 'span' => '6'),
        71 => array('id' =>71 , 'name' => 'Footer top (7/12 wide)', 'span' => '7'),
        72 => array('id' =>72 , 'name' => 'Footer top (8/12 wide)', 'span' => '8'),
        73 => array('id' =>73 , 'name' => 'Footer top (9/12 wide)', 'span' => '9'),
        74 => array('id' =>74 , 'name' => 'Footer top (10/12 wide)', 'span' => '10'),
        42 => array('id' =>42 , 'name' => 'Footer top (12/12 wide)', 'span' => '12'),

        3  => array('id' =>3 , 'name' => 'Footer (3/12 wide)', 'span' => '3'),
        43 => array('id' =>43 , 'name' => 'Footer (2/12 wide)', 'span' => '2'),
        56 => array('id' =>56 , 'name' => 'Footer (2.4/12 wide)', 'span' => '2-4'),
        44 => array('id' =>44 , 'name' => 'Footer (4/12 wide)', 'span' => '4'),
        45 => array('id' =>45 , 'name' => 'Footer (5/12 wide)', 'span' => '5'),
        46 => array('id' =>46 , 'name' => 'Footer (6/12 wide)', 'span' => '6'),
        81 => array('id' =>81 , 'name' => 'Footer (7/12 wide)', 'span' => '7'),
        82 => array('id' =>82 , 'name' => 'Footer (8/12 wide)', 'span' => '8'),
        83 => array('id' =>83 , 'name' => 'Footer (9/12 wide)', 'span' => '9'),
        84 => array('id' =>84 , 'name' => 'Footer (10/12 wide)', 'span' => '10'),
        47 => array('id' =>47 , 'name' => 'Footer (12/12 wide)', 'span' => '12'),

        12 => array('id' =>12 , 'name' => 'Footer secondary (3/12 wide)', 'span' => '3'),
        48 => array('id' =>48 , 'name' => 'Footer secondary (2/12 wide)', 'span' => '2'),
        57 => array('id' =>57 , 'name' => 'Footer secondary (2.4/12 wide)', 'span' => '2-4'),
        49 => array('id' =>49 , 'name' => 'Footer secondary (4/12 wide)', 'span' => '4'),
        50 => array('id' =>50 , 'name' => 'Footer secondary (5/12 wide)', 'span' => '5'),
        51 => array('id' =>51 , 'name' => 'Footer secondary (6/12 wide)', 'span' => '6'),
        91 => array('id' =>91 , 'name' => 'Footer secondary (7/12 wide)', 'span' => '7'),
        92 => array('id' =>92 , 'name' => 'Footer secondary (8/12 wide)', 'span' => '8'),
        93 => array('id' =>93 , 'name' => 'Footer secondary (9/12 wide)', 'span' => '9'),
        94 => array('id' =>94 , 'name' => 'Footer secondary (10/12 wide)', 'span' => '10'),
        52 => array('id' =>52 , 'name' => 'Footer secondary (12/12 wide)', 'span' => '12'),
    );
	public function __construct()
	{
		$this->name          = 'stnewsletter';
		$this->tab           = 'front_office_features';
		$this->version       = '1.3';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
		$this->bootstrap 	 = true;

		parent::__construct();
        
        $this->displayName = $this->l('Newsletter popup');
        $this->description = $this->l('Adds a block for newsletter subscription.');


        $this->_cookie_domain = $this->getDomain();
        $this->_cookie_path = trim(Context::getContext()->shop->physical_uri, '/\\').'/';
        if ($this->_cookie_path{0} != '/') $this->_cookie_path = '/'.$this->_cookie_path;
        $this->_cookie_path = rawurlencode($this->_cookie_path);
        $this->_cookie_path = str_replace('%2F', '/', $this->_cookie_path);
        $this->_cookie_path = str_replace('%7E', '~', $this->_cookie_path);
        
        if (!$this->_cookie_path)
            $this->_cookie_path = '/';
        if (!$this->_cookie_domain)
            $this->_cookie_domain = null;
        
        $this->file = 'export_'.date('YmdHis').'.csv';

        $this->initPages();
	}

    private function initPages()
    {
        $this->_pages = array(
                array(
                    'id' => 'index',
                    'val' => '1',
                    'name' => $this->l('Index')
                ),
                array(
                    'id' => 'category',
                    'val' => '2',
                    'name' => $this->l('Category')
                ),
                array(
                    'id' => 'product',
                    'val' => '4',
                    'name' => $this->l('Product')
                ),
                array(
                    'id' => 'pricesdrop',
                    'val' => '8',
                    'name' => $this->l('Prices Drop')
                ),
                array(
                    'id' => 'newproducts',
                    'val' => '16',
                    'name' => $this->l('New Products')
                ),
                array(
                    'id' => 'manufacturer',
                    'val' => '32',
                    'name' => $this->l('Manufacturer')
                ),
                array(
                    'id' => 'supplier',
                    'val' => '64',
                    'name' => $this->l('Supplier')
                ),
                array(
                    'id' => 'bestsales',
                    'val' => '128',
                    'name' => $this->l('Best Sales')
                ),
                array(
                    'id' => 'cms',
                    'val' => '256',
                    'name' => $this->l('Cms')
                ),
                array(
                    'id' => 'order',
                    'val' => '512',
                    'name' => $this->l('Shopping cart')
                ),
            );
    }
    
	public function install()
	{
        $res = parent::install() &&
            $this->installDB() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayAnywhere') &&
            $this->registerHook('displayFullWidthTop') &&
            $this->registerHook('displayFullWidthTop2') &&
            $this->registerHook('displayHomeTop') &&
            $this->registerHook('displayHome') &&
            $this->registerHook('displayHomeTertiaryLeft') &&
            $this->registerHook('displayHomeTertiaryRight') &&
            $this->registerHook('displayHomeSecondaryLeft') &&
            $this->registerHook('displayHomeSecondaryRight') &&
            $this->registerHook('displayHomeBottom') &&
            $this->registerHook('displayHomeVeryBottom') &&
            $this->registerHook('displayLeftColumn') &&
            $this->registerHook('displayRightColumn') &&
            $this->registerHook('displayFooterTop') &&
            $this->registerHook('displayFooter') &&
            $this->registerHook('displayFooterSecondary') &&
            $this->registerHook('displayTopColumn')  &&
            $this->registerHook('displayBottomColumn') &&
            Configuration::updateValue($this->_prefix_st.'VERIFICATION_EMAIL', 0) &&
            Configuration::updateValue($this->_prefix_st.'CONFIRMATION_EMAIL', 0) &&
            Configuration::updateValue($this->_prefix_st.'VOUCHER_CODE', '') &&
            Configuration::updateValue('NW_SALT', Tools::passwdGen(16));
        
        if ($res)
            foreach(Shop::getShops(false) as $shop)
                $res &= $this->sampleData($shop['id_shop']);
        $this->clearStNewsLetterCache();
        return $res;
	}
    public function installDB()
    {
        $return = (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_news_letter` (
                `id_st_news_letter` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `location` int(10) unsigned NOT NULL DEFAULT 0, 
                `hide_on_mobile` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `position` int(10) unsigned NOT NULL DEFAULT 0,
                `item_k` tinyint(2) unsigned NOT NULL DEFAULT 0,  
                `item_v` varchar(255) DEFAULT NULL,  

                `popup_width` int(10) unsigned NOT NULL DEFAULT 600,

                `content_text_color` varchar(7) DEFAULT NULL,
                `content_link_color` varchar(7) DEFAULT NULL,
                `content_link_hover` varchar(7) DEFAULT NULL,

                `bg_color` varchar(7) DEFAULT NULL,
                `bg_pattern` tinyint(2) unsigned NOT NULL DEFAULT 0, 
                `bg_img` varchar(255) DEFAULT NULL,
                `top_spacing` int(10) unsigned NOT NULL DEFAULT 50,
                `bottom_spacing` int(10) unsigned NOT NULL DEFAULT 50,
                `right_spacing` int(10) unsigned NOT NULL DEFAULT 0,
                `left_spacing` int(10) unsigned NOT NULL DEFAULT 0,
                `text_align` tinyint(1) unsigned NOT NULL DEFAULT 0,

                `input_width` int(10) unsigned NOT NULL DEFAULT 360,
                `input_height` int(10) unsigned NOT NULL DEFAULT 35,
                `input_color` varchar(7) DEFAULT NULL,
                `input_bg` varchar(7) DEFAULT NULL,
                `input_border` varchar(7) DEFAULT NULL,

                `btn_color` varchar(7) DEFAULT NULL,
                `btn_bg` varchar(7) DEFAULT NULL,
                `btn_hover_color` varchar(7) DEFAULT NULL,
                `btn_hover_bg` varchar(7) DEFAULT NULL,

                `show_popup` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `show_newsletter` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `cookies_time` int(10) unsigned NOT NULL DEFAULT 0,
                `delay_popup` int(10) unsigned NOT NULL DEFAULT 2,
                `subscribed` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `start_time` datetime DEFAULT NULL,
                `stop_time` datetime DEFAULT NULL,

                `display_on` int(10) unsigned NOT NULL DEFAULT 0,

                PRIMARY KEY (`id_st_news_letter`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
        
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_news_letter_lang` (
                `id_st_news_letter` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_lang` int(10) unsigned NOT NULL,
                `content` text NOT NULL,
                PRIMARY KEY (`id_st_news_letter`, `id_lang`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
        
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_news_letter_shop` (
                `id_st_news_letter` int(10) UNSIGNED NOT NULL,
                `id_shop` int(11) NOT NULL,      
                PRIMARY KEY (`id_st_news_letter`,`id_shop`),    
                KEY `id_shop` (`id_shop`)   
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
            
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'newsletter` (
               `id` int(6) NOT NULL AUTO_INCREMENT,
               `id_shop` int(10) unsigned NOT NULL DEFAULT 1,
               `id_shop_group` int(10) unsigned NOT NULL DEFAULT 1,
               `email` varchar(255) NOT NULL,
               `newsletter_date_add` datetime DEFAULT NULL,
               `ip_registration_newsletter` varchar(15) NOT NULL,
               `http_referer` varchar(255) DEFAULT NULL,
               `active` tinyint(1) NOT NULL DEFAULT 0,
               PRIMARY KEY (`id`)   
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
            
        return $return;
    }
    public function sampleData($id_shop)
    {
        $return = true;
        $samples = array(
            array(
                'location'      => 3,
                'active'        => 1,
                'hide_on_mobile'=> 0,
                'text_align'    => 1,
                'input_width'   => 258,
                'input_height'  => 35,
                'show_newsletter' => 1,
                'subscribed'    => 1,
                'text'          => '<p>Subscribe to our newsletter.</p>',
            )
        );
        
        foreach($samples as $k=>$sample)
        {
            $module = new StNewsLetterClass();
            foreach (Language::getLanguages(false) as $lang)
            {
				$module->content[$lang['id_lang']] = $sample['text'];
            }
            $module->location       = $sample['location'];
            $module->active         = $sample['active'];
            $module->hide_on_mobile = $sample['hide_on_mobile'];
            $module->text_align     = $sample['text_align'];
            $module->input_width    = $sample['input_width'];
            $module->input_height   = $sample['input_height'];
            $module->show_newsletter= $sample['show_newsletter'];
            $module->subscribed     = $sample['subscribed'];
            $module->position       = $k;
            $return &= $module->add();
            if($return && $module->id)
            {
                Db::getInstance()->insert('st_news_letter_shop', array(
                    'id_st_news_letter' => (int)$module->id,
                    'id_shop' => (int)$id_shop,
                ));
            }
        }
        return $return;
    }

    public function uninstall()
    {
        $this->clearStNewsLetterCache();
        // Delete configuration
        return $this->uninstallDB() && parent::uninstall();
    }

    public function uninstallDB()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_news_letter`,`'._DB_PREFIX_.'st_news_letter_lang`,`'._DB_PREFIX_.'st_news_letter_shop`');
    }
    protected function stGetCacheId($key,$type='location',$name = null)
    {
        $cache_id = parent::getCacheId($name);
        return $cache_id.'_'.$key.'_'.$type;
    }
    private function clearStNewsLetterCache()
    {
        $this->_clearCache('*');
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
            
        if (!is_writable(_PS_MODULE_DIR_.$this->name.'/views/css'))
            $this->_html .= $this->displayError('"'._PS_MODULE_DIR_.$this->name.'/views/css'.'" '.$this->l('directory isn\'t writable.'));
        
        return $result;
    }

    public function getContent()
    {
        $check_result = $this->_checkImageDir();
        $this->context->controller->addCSS(($this ->_path).'views/css/admin.css');
        $this->context->controller->addJS($this->_path. 'views/js/admin.js');
        
        $id_st_news_letter = (int)Tools::getValue('id_st_news_letter');
        if ((Tools::isSubmit('statusstnewsletter')))
        {
            $news_letter = new StNewsLetterClass((int)$id_st_news_letter);
            if($news_letter->id && $news_letter->toggleStatus())
            {
                $this->clearStNewsLetterCache();
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
        if(Tools::getValue('act')=='delete_image' && $identi = Tools::getValue('identi'))
        {
            $result = array(
                'r' => false,
                'm' => '',
                'd' => ''
            );
            $news_letter = new StNewsLetterClass((int)(int)$identi);
            if(Validate::isLoadedObject($news_letter))
            {
                $news_letter->bg_img = '';
                if($news_letter->save())
                {
                    $result['r'] = true;
                }
            }
            die(json_encode($result));
        }
        if ((Tools::isSubmit('groupdeleteimagestnewsletter')))
        {
            $news_letter = new StNewsLetterClass($id_st_news_letter);
            if($news_letter->id)
            {
                @unlink(_PS_ROOT_DIR_._THEME_PROD_PIC_DIR_.$this->name.'/'.$news_letter->bg_img);
                $news_letter->bg_img = '';
                if ($news_letter->save())
                {
                    //$this->_html .= $this->displayConfirmation($this->l('The image was deleted successfully.'));  
                    $this->clearStNewsLetterCache();
                    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=7&updatestnewsletter&id_st_news_letter='.(int)$news_letter->id.'&token='.Tools::getAdminTokenLite('AdminModules'));   
                }else
                    $this->_html .= $this->displayError($this->l('An error occurred while delete image.'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while delete image.'));
        }
        if (isset($_POST['savestnewsletter']) || isset($_POST['savestnewsletterAndStay']))
        {
            if ($id_st_news_letter)
                $news_letter = new StNewsLetterClass((int)$id_st_news_letter);
            else
                $news_letter = new StNewsLetterClass();
            
            $error = array();
            $news_letter->copyFromPost();
            
            if(!$news_letter->location)
                $error[] = $this->displayError($this->l('The field "Show on" is required'));

            if(!count($error))
            {
                if (isset($_FILES['bg_img']) && isset($_FILES['bg_img']['tmp_name']) && !empty($_FILES['bg_img']['tmp_name'])) 
                {
                    if ($vali = ImageManager::validateUpload($_FILES['bg_img'], Tools::convertBytes(ini_get('upload_max_filesize'))))
                       $error[] = Tools::displayError($vali);
                    else 
                    {
                        $bg_image = $this->uploadCheckAndGetName($_FILES['bg_img']['name']);
                        if(!$bg_image)
                            $error[] = Tools::displayError('Image format not recognized');
                        if (!move_uploaded_file($_FILES['bg_img']['tmp_name'], _PS_UPLOAD_DIR_.$this->name.'/'.$bg_image))
                            $error[] = Tools::displayError('Error move uploaded file');
                        else
                           $news_letter->bg_img = $this->name.'/'.$bg_image;
                    }
                }
            }

            $display_on = 0;
            foreach($this->_pages as $v)
                $display_on += (int)Tools::getValue('display_on_'.$v['id']);
                
            $news_letter->display_on = $display_on;

            if (!count($error) && $news_letter->validateFields(false) && $news_letter->validateFieldsLang(false))
            {
                if($news_letter->save())
                {
                    Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'st_news_letter_shop WHERE id_st_news_letter='.(int)$news_letter->id);
                    if (!Shop::isFeatureActive())
                    {
                        Db::getInstance()->insert('st_news_letter_shop', array(
                            'id_st_news_letter' => (int)$news_letter->id,
                            'id_shop' => (int)Context::getContext()->shop->id,
                        ));
                    }
                    else
                    {
                        $assos_shop = Tools::getValue('checkBoxShopAsso_st_news_letter');
                        if (empty($assos_shop))
                            $assos_shop[(int)Context::getContext()->shop->id] = Context::getContext()->shop->id;
                        foreach ($assos_shop as $id_shop => $row)
                            Db::getInstance()->insert('st_news_letter_shop', array(
                                'id_st_news_letter' => (int)$news_letter->id,
                                'id_shop' => (int)$id_shop,
                            ));
                    }
                    $this->clearStNewsLetterCache();
                    if(isset($_POST['savestnewsletterAndStay']) || Tools::getValue('fr') == 'view')
                    {
                        $rd_str = isset($_POST['savestnewsletterAndStay']) && Tools::getValue('fr') == 'view' ? 'fr=view&update' : (isset($_POST['savestnewsletterAndStay']) ? 'update' : 'view');
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_news_letter='.$news_letter->id.'&conf='.($id_st_news_letter?4:3).'&'.$rd_str.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules')); 
                    }    
                    else
                        $this->_html .= $this->displayConfirmation($this->l('Slideshow').' '.($id_st_news_letter ? $this->l('updated') : $this->l('added')));
                }                    
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during slideshow').' '.($id_st_news_letter ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }
        if (isset($_POST['savesettingstnewsletter']) || isset($_POST['savesettingstnewsletterAndStay']))
        {
            $this->initFieldsForm();
            
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
            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else 
            {
                $this->clearStNewsLetterCache();
                if(isset($_POST['savesettingstnewsletterAndStay']))
                    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=4&token='.Tools::getAdminTokenLite('AdminModules')); 
                else
                    $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        if(Tools::isSubmit('addstnewsletter') || (Tools::isSubmit('updatestnewsletter') && $id_st_news_letter))
        {
            $helper = $this->initForm();
            return $this->_html.$helper->generateForm($this->fields_form);
        }
        else if (Tools::isSubmit('deletestnewsletter') && $id_st_news_letter)
        {
            $news_letter = new StNewsLetterClass($id_st_news_letter);
            $news_letter->delete();
            $this->clearStNewsLetterCache();
            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
        }
        elseif (Tools::isSubmit('settingstnewsletter'))
		{
		    $this->initFieldsForm();
			$helper = $this->initSettingForm();
            
			return $this->_html.$helper->generateForm($this->fields_form);
		}
        else
        {
            if (Tools::isSubmit('submitExport') && $action = Tools::getValue('action'))
			     $this->export_csv();
            $helper = $this->initList();
            $this->_html .= $helper->generateList(StNewsLetterClass::getAll((int)$this->context->language->id), $this->fields_list);
            $this->initFieldsForm();
			$helper = $this->initSettingForm();
            $this->_html .= $helper->generateForm($this->fields_form);
            $this->_html .= $this->renderExportForm();
            return $this->_html;
        }
    }
    
    public function renderExportForm()
	{
		// Getting data...
		$countries = Country::getCountries($this->context->language->id);

		// ...formatting array
		$countries_list = array(array('id' => 0, 'name' => $this->l('All countries')));
		foreach ($countries as $country)
			$countries_list[] = array('id' => $country['id_country'], 'name' => $country['name']);

		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Export customers\' addresses'),
					'icon' => 'icon-envelope'
				),
				'input' => array(
					array(
						'type' => 'select',
						'label' => $this->l('Customers\' country'),
						'desc' => $this->l('Filter customers by country.'),
						'name' => 'COUNTRY',
						'required' => false,
						'default_value' => (int)$this->context->country->id,
						'options' => array(
							'query' => $countries_list,
							'id' => 'id',
							'name' => 'name',
						)
					),
					array(
						'type' => 'select',
						'label' => $this->l('Newsletter subscribers'),
						'desc' => $this->l('Filter customers who have subscribed to the newsletter or not, and who have an account or not.'),
						'hint' => $this->l('Customers can subscribe to your newsletter when registering, or by entering their email in the newsletter popup.'),
						'name' => 'SUSCRIBERS',
						'required' => false,
						'default_value' => (int)$this->context->country->id,
						'options' => array(
							'query' => array(
								array('id' => 0, 'name' => $this->l('All subscribers')),
								array('id' => 1, 'name' => $this->l('Subscribers with account')),
								array('id' => 2, 'name' => $this->l('Subscribers without account')),
								array('id' => 3, 'name' => $this->l('Non-subscribers'))
							),
							'id' => 'id',
							'name' => 'name',
						)
					),
					array(
						'type' => 'select',
						'label' => $this->l('Opt-in subscribers'),
						'desc' => $this->l('Filter customers who have agreed to receive your partners\' offers or not.'),
						'hint' => $this->l('Opt-in subscribers have agreed to receive your partners\' offers.'),
						'name' => 'OPTIN',
						'required' => false,
						'default_value' => (int)$this->context->country->id,
						'options' => array(
							'query' => array(
								array('id' => 0, 'name' => $this->l('All customers')),
								array('id' => 2, 'name' => $this->l('Opt-in subscribers')),
								array('id' => 1, 'name' => $this->l('Opt-in non-subscribers'))
							),
							'id' => 'id',
							'name' => 'name',
						)
					),
                    array(
        				'type' => 'html',
                        'id' => 'a_go',
        				'label' => '',
        				'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure=blocknewsletter&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-right"></i> View subscribers </a>',                  
        			),
					array(
						'type' => 'hidden',
						'name' => 'action',
					)
				),
				'submit' => array(
					'title' => $this->l('Export .CSV file'),
					'class' => 'btn btn-default pull-right',
					'name' => 'submitExport',
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->id = (int)Tools::getValue('id_carrier');
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'btnSubmit';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper->generateForm(array($fields_form));
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
    protected function stUploadImage($item)
    {
        $result = array(
            'error' => array(),
            'image' => '',
            'thumb' => '',
        );
        if (isset($_FILES[$item]) && isset($_FILES[$item]['tmp_name']) && !empty($_FILES[$item]['tmp_name']))
        {
            $type = strtolower(substr(strrchr($_FILES[$item]['name'], '.'), 1));
            $imagesize = array();
            $imagesize = @getimagesize($_FILES[$item]['tmp_name']);
            if (!empty($imagesize) &&
                in_array(strtolower(substr(strrchr($imagesize['mime'], '/'), 1)), array('jpg', 'gif', 'jpeg', 'png')) &&
                in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
            {
                $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                $salt = sha1(microtime());
                $c_name = Tools::encrypt($_FILES[$item]['name'].$salt);
                $c_name_thumb = $c_name.'_thumb';
                if ($upload_error = ImageManager::validateUpload($_FILES[$item]))
                    $result['error'][] = $upload_error;
                elseif (!$temp_name || !move_uploaded_file($_FILES[$item]['tmp_name'], $temp_name))
                    $result['error'][] = $this->displayError($this->l('An error occurred during move image.'));
                else{
                   $infos = getimagesize($temp_name);
                   $ratio_y = 72;
                   $ratio_x = $infos[0] / ($infos[1] / $ratio_y);
                   if(!ImageManager::resize($temp_name, _PS_UPLOAD_DIR_.$this->name.'/'.$c_name.'.'.$type, null, null, $type) || !ImageManager::resize($temp_name, _PS_UPLOAD_DIR_.$this->name.'/'.$c_name_thumb.'.'.$type, $ratio_x, $ratio_y, $type))
                       $result['error'][] = $this->displayError($this->l('An error occurred during the image upload.'));
                } 
                if (isset($temp_name))
                    @unlink($temp_name);
                    
                if(!count($result['error']))
                {
                    $result['image'] = $this->name.'/'.$c_name.'.'.$type;
                    $result['thumb'] = $this->name.'/'.$c_name_thumb.'.'.$type;
                }
                return $result;
            }
        }
        else
            return $result;
    }
    

    public static function showApplyTo($value,$row)
    {
        return isset(self::$location[$value]) ? self::$location[$value]['name'] : '--';
    }
    protected function initList()
    {
        $this->fields_list = array(
            'id_st_news_letter' => array(
                'title' => $this->l('Id'),
                'width' => 120,
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'location' => array(
                'title' => $this->l('Show on'),
                'width' => 200,
                'type' => 'text',
                'callback' => 'showApplyTo',
                'callback_object' => 'StNewsLetter',
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'width' => 25,
                'search' => false,
                'orderby' => false
            ),
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_st_news_letter';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->imageType = 'jpg';
        $helper->toolbar_btn['new'] =  array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstnewsletter&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Add a block'),
        );
        $helper->toolbar_btn['export'] =  array(
			'href' => AdminController::$currentIndex.'&configure=blocknewsletter&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('View subscribers'),
		);

        $helper->title = $this->displayName;
        $helper->table = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        return $helper;
    }

    protected function initForm()
    {        
        $location_temp = self::$location;
        unset($location_temp[4]);
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Configuration'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                'location' => array(
                    'type' => 'select',
                    'label' => $this->l('Show on:'),
                    'name' => 'location',
                    'options' => array(
                        'query' => $location_temp,
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => 4,
                            'label' => $this->l('Popup'),
                        ),
                    ),
                    'required'  => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Top spacing:'),
                    'name' => 'top_spacing',
                    'default_value' => 50,
                    'class' => 'fixed-width-sm',  
                    'suffix' => 'px'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Bottom spacing:'),
                    'name' => 'bottom_spacing',
                    'default_value' => 50,
                    'class' => 'fixed-width-sm',  
                    'suffix' => 'px'
                ),

                array(
                    'type' => 'select',
                    'label' => $this->l('Right spacing:'),
                    'name' => 'right_spacing',
                    'options' => array(
                        'query' => array(
                                array('id' => 5, 'name'=>'5%'),
                                array('id' => 10, 'name'=>'10%'),
                                array('id' => 20, 'name'=>'20%'),
                                array('id' => 30, 'name'=>'30%'),
                                array('id' => 40, 'name'=>'40%'),
                                array('id' => 50, 'name'=>'50%'),
                                array('id' => 60, 'name'=>'60%'),
                                array('id' => 70, 'name'=>'70%'),
                            ),
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => '0',
                            'label' => $this->l('0')
                        )
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Left spacing:'),
                    'name' => 'left_spacing',
                    'options' => array(
                        'query' => array(
                                array('id' => 5, 'name'=>'5%'),
                                array('id' => 10, 'name'=>'10%'),
                                array('id' => 20, 'name'=>'20%'),
                                array('id' => 30, 'name'=>'30%'),
                                array('id' => 40, 'name'=>'40%'),
                                array('id' => 50, 'name'=>'50%'),
                                array('id' => 60, 'name'=>'60%'),
                                array('id' => 70, 'name'=>'70%'),
                            ),
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => '0',
                            'label' => $this->l('0')
                        )
                    ),
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Alignment:'),
                    'name' => 'text_align',
                    'default_value' => 2,
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
                ), 

                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide on mobile:'),
                    'name' => 'hide_on_mobile',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'hide_on_mobile_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'hide_on_mobile_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'desc' => $this->l('screen width < 768px.'),
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
                    'title'=> $this->l(' Save all '),
                    'icon' => 'process-icon-save',
                    'class'=> 'pull-right'
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save and stay'),
                'stay' => true
            ),
        );

        $this->fields_form[1]['form'] = array(
            'legend' => array(
                'title' => $this->l('Content'),
                'icon'  => 'icon-cogs'
            ),
            'input' => array( 
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Content:'),
                    'lang' => true,
                    'name' => 'content',
                    'cols' => 40,
                    'rows' => 10,
                    'autoload_rte' => true,
                    'desc' => '<p>Format your entry with some basic HTML. Click <span style="color:#ff8230;">Flash</span> button to use predefined templates.</p>
                    <strong>Headings</strong>
                    <p>Headings are defined with the &lt;h1&gt; to &lt;h6&gt; tags.</p>
                    <ul>
                        <li>&lt;h2&gt;Big Heading 1&lt;/h2&gt;</li>
                        <li>&lt;h5&gt;Samll Heading 1&lt;/h5&gt;</li>
                    </ul>
                    <strong>Buttons</strong>
                    <p>You can click the <span style="color:#ff8230;">Flash</span> button in the toolbar of text editor to add buttons.</p>
                    <ul>
                        <li>&lt;a href="#" class="btn btn-small"&gt;Small Button&lt;/a&gt;</li>
                        <li>&lt;a href="#" class="btn btn-default"&gt;Button&lt;/a&gt;</li>
                        <li>&lt;a href="#" class="btn btn-medium"&gt;Medium Button&lt;/a&gt;</li>
                        <li>&lt;a href="#" class="btn btn-large"&gt;Large Button&lt;/a&gt;</li>
                    </ul>
                    <strong>Usefull class names</strong>
                    <ul>
                    <li>closer: &lt;h2 class="closer"&gt;Sample&lt;/h2&gt;</li>
                    <li>spacer: &lt;div class="spacer"&gt;&nbsp;&lt;/div&gt;</li>
                    <li>width_50 to width_90: &lt;div class="width_70"&gt;Sample&lt;/div&gt;</li>
                    <li>center_width_50 to center_width_90: &lt;div class="center_width_80"&gt;Sample&lt;/div&gt;</li>
                    <li>fs_sm fs_md fs_lg fs_xl fs_xxl fs_xxxl fs_xxxxl: &lt;p class="fs_lg"&gt;Sample&lt;/p&gt;</li>
                    <li>icon_line: &lt;div class="icon_line_wrap"&gt;&lt;div class="icon_line"&gt;Sample&lt;/div&gt;&lt;/div&gt;</li>
                    <li>line, line_white, line_black: &lt;p class="line_white"&gt;Sample&lt;/p&gt;</li>
                    <li>&lt;p class="uppercase"&gt;SAMPLE&lt;/p&gt;</li>
                    <li>color_000,color_333,color_444,color_666,color_999,color_ccc,color_fff: <span style="color:#999">&lt;p class="color_999"&gt;Sample&lt;/p&gt;</span></li>
                    </ul>',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Color:'),
                    'name' => 'content_text_color',
                    'size' => 33,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Link color:'),
                    'name' => 'content_link_color',
                    'size' => 33,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Link hover color:'),
                    'name' => 'content_link_hover',
                    'size' => 33,
                ),


                array(
                    'type' => 'text',
                    'label' => $this->l('Input width:'),
                    'name' => 'input_width',
                    'default_value' => 0,
                    'class' => 'fixed-width-sm',  
                    'suffix' => 'px'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Input height:'),
                    'name' => 'input_height',
                    'default_value' => 0,
                    'class' => 'fixed-width-sm',  
                    'suffix' => 'px'
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Input text color:'),
                    'name' => 'input_color',
                    'size' => 33,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Input background color:'),
                    'name' => 'input_bg',
                    'size' => 33,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Input border color:'),
                    'name' => 'input_border',
                    'size' => 33,
                ),


                array(
                    'type' => 'color',
                    'label' => $this->l('Button color:'),
                    'name' => 'btn_color',
                    'size' => 33,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Button background color:'),
                    'name' => 'btn_bg',
                    'size' => 33,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Button hover color:'),
                    'name' => 'btn_hover_color',
                    'size' => 33,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Button hover background color:'),
                    'name' => 'btn_hover_bg',
                    'size' => 33,
                ),

                array(
                    'type' => 'color',
                    'label' => $this->l('Background color:'),
                    'name' => 'bg_color',
                    'size' => 33,
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
                    'type' => 'html',
                    'id' => 'a_cancel',
                    'label' => '',
                    'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
                ),
            ),
            'buttons' => array(
                array(
                    'type' => 'submit',
                    'title'=> $this->l(' Save all '),
                    'icon' => 'process-icon-save',
                    'class'=> 'pull-right'
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save and stay'),
                'stay' => true
            ),
        );
                
        $this->fields_form[2]['form'] = array(
            'legend' => array(
                'title' => $this->l('Popup'),
                'icon'  => 'icon-cogs'
            ),
            'input' => array( 
                array(
                    'type' => 'checkbox',
                    'label' => $this->l('Display on'),
                    'name' => 'display_on',
                    'lang' => true,
                    'values' => array(
                        'query' => $this->_pages,
                        'id' => 'id',
                        'name' => 'name'
                    ),
                    'desc' => $this->l('This option is for Popup windows.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display newsletter form:'),
                    'name' => 'show_newsletter',
                    'is_bool' => true,
                    'default_value' => 1,
                    'values' => array(
                        array(
                            'id' => 'show_newsletter_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'show_newsletter_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Width:'),
                    'name' => 'popup_width',
                    'default_value' => 600,
                    'class' => 'fixed-width-sm',  
                    'suffix' => 'px',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('How to show this popup:'),
                    'name' => 'show_popup',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'image',
                            'value' => 0,
                            'label' => $this->l('At all time with a do not show option')),
                        array(
                            'id' => 'hosted',
                            'value' => 1,
                            'label' => $this->l('At all time')),
                        array(
                            'id' => 'youtube',
                            'value' => 2,
                            'label' => $this->l('First time only')),
                    ),
                ),  

                array(
                    'type' => 'text',
                    'label' => $this->l('Do not show again time period:'),
                    'name' => 'cookies_time',
                    'default_value' => 7,
                    'class' => 'fixed-width-sm',  
                    'suffix' => 'days',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Delay:'),
                    'name' => 'delay_popup',
                    'default_value' => 2,
                    'class' => 'fixed-width-sm',  
                    'suffix' => 's',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Do not show this popup if already subscribed:'),
                    'name' => 'subscribed',
                    'is_bool' => true,
                    'default_value' => 1,
                    'values' => array(
                        array(
                            'id' => 'subscribed_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'subscribed_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
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
                    'title'=> $this->l(' Save all '),
                    'icon' => 'process-icon-save',
                    'class'=> 'pull-right'
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save and stay'),
                'stay' => true
            ),
        );
              
                
        if (Shop::isFeatureActive())
        {
            $this->fields_form[0]['form']['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association:'),
                'name' => 'checkBoxShopAsso',
            );
        }
        
        $this->fields_form[0]['form']['input'][] = array(
            'type' => 'html',
            'id' => 'a_cancel',
            'label' => '',
            'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
        );
        
        $id_st_news_letter = (int)Tools::getValue('id_st_news_letter');
        $news_letter = new StNewsLetterClass($id_st_news_letter);
        
        if($news_letter->id)
        {
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_news_letter');
            
            if ($news_letter->bg_img)
            {
                StNewsLetterClass::fetchMediaServer($news_letter->bg_img);
                $this->fields_form[1]['form']['input']['bg_img_field']['image'] = '<img width=200 src="'.($news_letter->bg_img).'" /><p><a class="btn btn-default st_delete_image" href="javascript:;" data-id-group="'.(int)$news_letter->id.'"><i class="icon-trash"></i> Delete</a></p>';
            }
        }
        
        
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->id = (int)$news_letter->id;
        $helper->module = $this;
        $helper->table =  'st_news_letter';
        $helper->identifier = 'id_st_news_letter';
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->submit_action = 'savestnewsletter';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getFieldsValueSt($news_letter),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        
        $helper->title = $this->displayName; 
        
        foreach($this->_pages as $v)
            $helper->tpl_vars['fields_value']['display_on_'.$v['id']] = (int)$v['val']&(int)$news_letter->display_on;
        
        return $helper;
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
						'type' => 'switch',
						'label' => $this->l('Would you like to send a verification email after subscription?'),
						'name' => 'verification_email',
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
                        'validation' => 'isBool',
					),
					array(
					'type' => 'switch',
					'label' => $this->l('Would you like to send a confirmation email after subscription?'),
					'name' => 'confirmation_email',
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Yes')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('No')
						)
					),
                    'validation' => 'isBool',
				),
                array(
						'type' => 'text',
						'label' => $this->l('Welcome voucher code'),
						'name' => 'voucher_code',
						'class' => 'fixed-width-md',
						'desc' => $this->l('Leave blank to disable by default.'),
                        'validation' => 'isString',
					),
            ),
			'submit' => array(
				'title' => $this->l('Save'),
                'stay' => true
			),
        );
    }
    
    protected function initSettingForm()
	{
	    $helper = new HelperForm();
		$helper->show_toolbar = false;
        $helper->module = $this;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savesettingstnewsletter';
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
        $fields_values = array(
            'verification_email'          => Configuration::get($this->_prefix_st.'VERIFICATION_EMAIL'),
            'confirmation_email'          => Configuration::get($this->_prefix_st.'CONFIRMATION_EMAIL'),
            'voucher_code'                => Configuration::get($this->_prefix_st.'VOUCHER_CODE'),
            'COUNTRY'                     => Tools::getValue('COUNTRY'),
			'SUSCRIBERS'                  => Tools::getValue('SUSCRIBERS'),
			'OPTIN'                       => Tools::getValue('OPTIN'),
            'action'                      => 'customers',
        );
        return $fields_values;
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

    public function hookDisplayHeader($params)
    {
        $this->context->controller->addJS(($this->_path).'views/js/stnewsletter.js');
        $this->context->controller->addJqueryPlugin('cooki-plugin');

        if (!$this->isCached('header.tpl', $this->getCacheId()))
        {
            $custom_css = '';
            $options = StNewsLetterClass::getOptions();
            if(is_array($options) && count($options))
                foreach($options as $v)    
                {
                    $classname = (isset(self::$location[$v['location']]['full_width']) ? '#st_news_letter_container_'.$v['id_st_news_letter'].' ' : '#st_news_letter_'.$v['id_st_news_letter'].' ');
                    
                    $group_css = '';
                    if ($v['bg_color'])
                        $group_css .= 'background-color:'.$v['bg_color'].';';
                    if ($v['bg_img'])
                    {
                        $img = _THEME_PROD_PIC_DIR_.$v['bg_img'];
                        $img = $this->context->link->protocol_content.Tools::getMediaServer($img).$img;
                        $group_css .= 'background-image: url('.$img.');';
                    }
                    elseif ($v['bg_pattern'])
                    {
                        $img = _MODULE_DIR_.'stthemeeditor/patterns/'.$v['bg_pattern'].'.png';
                        $img = $this->context->link->protocol_content.Tools::getMediaServer($img).$img;
                        $group_css .= 'background-image: url('.$img.');';
                    }
                    if($group_css)
                        $custom_css .= $classname.'{'.$group_css.'}';

                    if($v['location']==4)
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].'.st_news_letter_popup{width:'.($v['popup_width'] ? (int)$v['popup_width'] : 680).'px;}';

                    if ($v['content_text_color'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].'{color:'.$v['content_text_color'].';}';
                    if ($v['content_link_color'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' a{color:'.$v['content_link_color'].';}';
                    if ($v['content_link_hover'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' a:hover{color:'.$v['content_link_hover'].';}';

                    if ($v['input_width'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_form_inner{width:'.$v['input_width'].'px;}';
                    if ($v['input_height'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_input{height:'.$v['input_height'].'px;}#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_submit{height:'.$v['input_height'].'px;line-height:'.($v['input_height']>22 ? ((int)$v['input_height']-4) : $v['input_height']).'px;}';

                    if ($v['input_color'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_input{color:'.$v['input_color'].';}';
                    if ($v['input_bg'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_input{background-color:'.$v['input_bg'].';}';
                    if ($v['input_border'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_input, #st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_submit{border-color:'.$v['input_border'].';}';

                    if ($v['btn_color'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_submit{color:'.$v['btn_color'].';}';
                    if ($v['btn_bg'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_submit{background-color:'.$v['btn_bg'].';border-color:'.$v['btn_bg'].';}';
                    if ($v['btn_hover_color'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_submit:hover{color:'.$v['btn_hover_color'].';}';
                    if ($v['btn_hover_bg'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_submit:hover{background-color:'.$v['btn_hover_bg'].';border-color:'.$v['btn_hover_bg'].';}';
                    

                    if ($v['top_spacing'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_box{padding-top:'.$v['top_spacing'].'px;}';
                    if ($v['bottom_spacing'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_box{padding-bottom:'.$v['bottom_spacing'].'px;}';
                    if ($v['right_spacing'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_box{padding-right:'.$v['right_spacing'].'%;}';
                    if ($v['left_spacing'])
                        $custom_css .= '#st_news_letter_'.$v['id_st_news_letter'].' .st_news_letter_box{padding-left:'.$v['left_spacing'].'%;}';
                }
            if($custom_css)
                $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
        }
        return $this->display(__FILE__, 'header.tpl', $this->getCacheId());
    }
    protected function getDomain($shared_urls = null)
    {
        $r = '!(?:(\w+)://)?(?:(\w+)\:(\w+)@)?([^/:]+)?(?:\:(\d*))?([^#?]+)?(?:\?([^#]+))?(?:#(.+$))?!i';

        if (!preg_match ($r, Tools::getHttpHost(false, false), $out) || !isset($out[4]))
            return false;

        if (preg_match('/^(((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]{1}[0-9]|[1-9]).)'.
            '{1}((25[0-5]|2[0-4][0-9]|[1]{1}[0-9]{2}|[1-9]{1}[0-9]|[0-9]).)'.
            '{2}((25[0-5]|2[0-4][0-9]|[1]{1}[0-9]{2}|[1-9]{1}[0-9]|[0-9]){1}))$/', $out[4]))
            return false;
        if (!strstr(Tools::getHttpHost(false, false), '.'))
            return false;

        $domain = false;
        if ($shared_urls !== null)
        {
            foreach ($shared_urls as $shared_url)
            {
                if ($shared_url != $out[4])
                    continue;
                if (preg_match('/^(?:.*\.)?([^.]*(?:.{2,4})?\..{2,3})$/Ui', $shared_url, $res))
                {
                    $domain = '.'.$res[1];
                    break;
                }
            }
        }
        if (!$domain)
            $domain = $out[4];
        return $domain;
    }

    private function setCookie($name, $value = null, $time = null)
    {
        if (PHP_VERSION_ID <= 50200) /* PHP version > 5.2.0 */
            return setcookie($name, $value, ($time ? time()+$time*86400 : time()+30*86400), $this->_cookie_path, $this->_cookie_domain, 0);
        else
            return setcookie($name, $value, ($time ? time()+$time*86400 : time()+30*86400), $this->_cookie_path, $this->_cookie_domain, 0, true);
    }

    private function _prepareHook($identify,$type=1)
    {        
        $news_letter_array = StNewsLetterClass::getNewsLetter($this->context->language->id, $identify, $type);

        if(!is_array($news_letter_array) || !count($news_letter_array))
            return false;
        $page = Dispatcher::getInstance()->getController();

        foreach($news_letter_array as $k => &$v)
        {
            if($v['location']==4)
            {
                $page_array = $this->getDisplayOn((int)$v['display_on']);
                if (($page!='order' && !in_array($page, $page_array)) || ($page=='order' && Tools::getValue('step')))
                {
                    unset($news_letter_array[$k]);
                    continue;
                }

                if ($v['subscribed'] && isset($this->context->cookie->email) && Db::getInstance()->getRow('SELECT count(0) FROM `'._DB_PREFIX_.'newsletter` WHERE `email` = \''.$this->context->cookie->email.'\''))
                {
                    unset($news_letter_array[$k]);
                    continue;
                }
                
                if(isset($_COOKIE['st_popup_do_not_show_'.$v['id_st_news_letter']]) && $_COOKIE['st_popup_do_not_show_'.$v['id_st_news_letter']]==$v['show_popup'])
                {
                    unset($news_letter_array[$k]);
                    continue;
                }
                //if($v['show_popup']==2)
                //    $this->setCookie('st_popup_do_not_show_'.$v['id_st_news_letter'], $v['show_popup'], ($v['cookies_time'] ? (int)$v['cookies_time'] : 30));
            }
            $v['is_full_width'] = isset(self::$location[$v['location']]['full_width']) ? true : false;
            $v['is_column'] = isset(self::$location[$v['location']]['column']) ? true : false;
            $v['span'] = isset(self::$location[$v['location']]['span']) ? self::$location[$v['location']]['span'] : 0;
        }
        $this->smarty->assign(array(
            'news_letter_array' => $news_letter_array,
            'news_letter_cookie_domain' => $this->_cookie_domain,
            'news_letter_cookie_path' => $this->_cookie_path,
        ));
        return true;
    }

    public function hookDisplayAnywhere($params)
    {
        if(!isset($params['caller']) || $params['caller']!=$this->name)
            return false;

        if(!$this->_prepareHook(4))
            return false;
        return $this->display(__FILE__, 'stnewsletter.tpl');
    }

    public function hookDisplayFullWidthTop($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(36)))
            if(!$this->_prepareHook(36))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(36));
    }
    public function hookDisplayFullWidthTop2($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(31)))
            if(!$this->_prepareHook(31))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(31));
    }
    public function hookDisplayHomeTop($params)
    {
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(16)))
            if(!$this->_prepareHook(16))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(16));
    }
    public function hookDisplayHome($params)
    {
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(1)))
            if(!$this->_prepareHook(1))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(1));
    }
    public function hookDisplayHomeSecondaryLeft($params)
    {
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(14)))
            if(!$this->_prepareHook(14))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(14));
    }

    public function hookDisplayHomeSecondaryRight($params)
    {
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(15)))
            if(!$this->_prepareHook(15))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(15));
    }

    public function hookDisplayHomeTertiaryLeft($params)
    {
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(29)))
            if(!$this->_prepareHook(29))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(29));
    }

    public function hookDisplayHomeTertiaryRight($params)
    {
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(30)))
            if(!$this->_prepareHook(30))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(30));
    }

    public function hookDisplayHomeFirstQuarter($params)
    {
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(53)))
            if(!$this->_prepareHook(53))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(53));
    }
    public function hookDisplayHomeSecondQuarter($params)
    {
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(54)))
            if(!$this->_prepareHook(54))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(54));
    }

    public function hookDisplayHomeThirdQuarter($params)
    {
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(58)))
            if(!$this->_prepareHook(58))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(58));
    }
    public function hookDisplayHomeFourthQuarter($params)
    {
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(59)))
            if(!$this->_prepareHook(59))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(59));
    }
    public function hookDisplayHomeBottom($params)
    {
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(17)))
            if(!$this->_prepareHook(17))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(17));        
    }
    public function hookDisplayHomeVeryBottom($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(37)))
            if(!$this->_prepareHook(37))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(37));
    }
    public function hookDisplayLeftColumn($params)
    {
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(2)))
            if(!$this->_prepareHook(2))
                return false;
        return $this->display(__FILE__, 'stnewsletter-column.tpl', $this->stGetCacheId(2));
    }
    
    public function hookDisplayRightColumn($params)
    {
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(10)))
            if(!$this->_prepareHook(10))
                return false;
        return $this->display(__FILE__, 'stnewsletter-column.tpl', $this->stGetCacheId(10));
    }

    public function hookDisplayFooterTop($params)
    {
        if (!$this->isCached('stnewsletter-footer.tpl', $this->stGetCacheId(13)))
            if(!$this->_prepareHook(array(13, 38, 39, 40, 41,71,72,73,74, 42,55)))
                return false;
        return $this->display(__FILE__, 'stnewsletter-footer.tpl', $this->stGetCacheId(13));
    }

    public function hookDisplayFooter($params)
    {
        if (!$this->isCached('stnewsletter-footer.tpl', $this->stGetCacheId(3)))
            if(!$this->_prepareHook(array(3, 43, 44, 45, 46,81,82,83,84, 47,56)))
                return false;
        return $this->display(__FILE__, 'stnewsletter-footer.tpl', $this->stGetCacheId(3));
    }

    public function hookDisplayFooterSecondary($params)
    {
        if (!$this->isCached('stnewsletter-footer.tpl', $this->stGetCacheId(12)))
            if(!$this->_prepareHook(array(12, 48, 49, 50, 51,91,92,93,94, 52,57)))
                return false;
        return $this->display(__FILE__, 'stnewsletter-footer.tpl', $this->stGetCacheId(12));
    }
    public function hookDisplayTopColumn($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(35)))
            if(!$this->_prepareHook(35))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(35));
    }
    public function hookDisplayBottomColumn($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;
        
        if (!$this->isCached('stnewsletter.tpl', $this->stGetCacheId(28)))
            if(!$this->_prepareHook(28))
                return false;
        return $this->display(__FILE__, 'stnewsletter.tpl', $this->stGetCacheId(28));
    }

    public function hookActionShopDataDuplication($params)
    {
        return $this->sampleData($params['new_id_shop']);
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
            
    public function processUpdatePositions()
    {
        if (Tools::getValue('action') == 'updatePositions' && Tools::getValue('ajax'))
        {
            $way = (int)(Tools::getValue('way'));
            $id = (int)(Tools::getValue('id'));
            $positions = Tools::getValue('id_st_news_letter');
            $msg = '';
            if (is_array($positions))
                foreach ($positions as $position => $value)
                {
                    $pos = explode('_', $value);

                    if ((isset($pos[2])) && ((int)$pos[2] === $id))
                    {
                        if ($object = new StNewsLetterClass((int)$pos[2]))
                            if (isset($position) && $object->updatePosition($way, $position))
                                $msg = 'ok position '.(int)$position.' for ID '.(int)$pos[2]."\r\n";    
                            else
                                $msg = '{"hasError" : true, "errors" : "Can not update position"}';
                        else
                            $msg = '{"hasError" : true, "errors" : "This object ('.(int)$id.') can t be loaded"}';

                        break;
                    }
                }
                die($msg);
        }
    }
    
    public function ajaxCall()
    {
        $ret = $this->newsletterRegistration();
        return Tools::jsonEncode($ret);
    }
    
    /**
	 * Register in block newsletter
	 */
	public function newsletterRegistration()
	{
	    $error = $valid = '';
        $newsletterClass = new StNewsLetterClass();
		if (empty($_POST['email']) || !Validate::isEmail($_POST['email']))
			$error = $this->l('Invalid email address.');
		/* Unsubscription */
		else if ($_POST['action'] == '1')
		{
			$register_status = $newsletterClass->isNewsletterRegistered($_POST['email']);

			if ($register_status < 1)
				$error = $this->l('This email address is not registered.');
			elseif (!$newsletterClass->unregister($_POST['email'], $register_status))
				$error = $this->l('An error occurred while attempting to unsubscribe.');
			else
                $valid = $this->l('Unsubscription successful.');
		}
		/* Subscription */
		else if ($_POST['action'] == '0')
		{
		    $email = pSQL($_POST['email']);
			$register_status = $newsletterClass->isNewsletterRegistered($email, true);
            if ($register_status > 0)
                $error = $this->l('This email address is already registered.');
            
			if (!$error && !$newsletterClass->isRegistered($register_status))
			{
			    $register_status = $newsletterClass->isNewsletterRegistered($email);
    			if ($register_status > 0)
                {
                    $newsletterClass->unregister($email, $register_status);
                    // Get the new status once more.
                    $register_status = $newsletterClass->isNewsletterRegistered($email);
                }
				if (Configuration::get('ST_NW_VERIFICATION_EMAIL'))
				{
					// create an unactive entry in the newsletter database
					if ($register_status == StNewsLetterClass::GUEST_NOT_REGISTERED)
						$newsletterClass->registerGuest($email, false);

					if (!$token = $newsletterClass->getToken($email, $register_status))
						$error = $this->l('An error occurred during the subscription process.');
					else
                        $this->sendVerificationEmail($email, $token);

					$valid = $this->l('A verification email has been sent. Please check your inbox.');
				}
				else
				{
					if ($newsletterClass->register($email, $register_status))
						$valid = $this->l('You have successfully subscribed to this newsletter.');
					else
						$error = $this->l('An error occurred during the subscription process.');

                    if (!$error)
                    {
                        if ($code = Configuration::get('ST_NW_VOUCHER_CODE'))
    						$this->sendVoucher($email, $code);
    
    					if (Configuration::get('ST_NW_CONFIRMATION_EMAIL'))
    						$this->sendConfirmationEmail($email);    
                    }
				}
			}
		}
        $ret = array(
            'hasError' => $error,
            'message'  => $valid
        );
        return $ret;
	}
    
    /**
	 * Ends the registration process to the newsletter
	 *
	 * @param string $token
	 *
	 * @return string
	 */
	public function confirmEmail($token)
	{
		$activated = false;
        
        $newsletterClass = new StNewsLetterClass();
        
		if ($email = $newsletterClass->getGuestEmailByToken($token))
			$activated = $newsletterClass->activateGuest($email);
		else if ($email = $newsletterClass->getUserEmailByToken($token))
			$activated = $newsletterClass->registerUser($email);

		if (!$activated)
			return $this->l('This email is already registered and/or invalid.');

		if ($discount = Configuration::get('ST_NW_VOUCHER_CODE'))
			$this->sendVoucher($email, $discount);

		if (Configuration::get('NW_CONFIRMATION_EMAIL'))
			$this->sendConfirmationEmail($email);

		return $this->l('Thank you for subscribing to our newsletter.');
	}

	/**
	 * Send the confirmation mails to the given $email address if needed.
	 *
	 * @param string $email Email where to send the confirmation
	 *
	 * @note the email has been verified and might not yet been registered. Called by AuthController::processCustomerNewsletter
	 *
	 */
	public function confirmSubscription($email)
	{
		if ($email)
		{
			if ($discount = Configuration::get('ST_NW_VOUCHER_CODE'))
				$this->sendVoucher($email, $discount);

			if (Configuration::get('ST_NW_CONFIRMATION_EMAIL'))
				$this->sendConfirmationEmail($email);
		}
	}

	/**
	 * Send an email containing a voucher code
	 *
	 * @param $email
	 * @param $code
	 *
	 * @return bool|int
	 */
	public function sendVoucher($email, $code)
	{
		return Mail::Send(Context::getContext()->language->id, 'stnewsletter_voucher', Mail::l('Newsletter voucher', Context::getContext()->language->id), array('{discount}' => $code), $email, null, null, null, null, null, dirname(__FILE__).'/mails/', false, Context::getContext()->shop->id);
	}

	/**
	 * Send a confirmation email
	 *
	 * @param string $email
	 *
	 * @return bool
	 */
	public function sendConfirmationEmail($email)
	{
		return Mail::Send(Context::getContext()->language->id, 'stnewsletter_conf', Mail::l('Newsletter confirmation', Context::getContext()->language->id), array(), pSQL($email), null, null, null, null, null, dirname(__FILE__).'/mails/', false, Context::getContext()->shop->id);
	}

	/**
	 * Send a verification email
	 *
	 * @param string $email
	 * @param string $token
	 *
	 * @return bool
	 */
	public function sendVerificationEmail($email, $token)
	{
		$verif_url = Context::getContext()->link->getModuleLink(
			'stnewsletter', 'verification', array(
				'token' => $token,
			)
		);

		return Mail::Send(Context::getContext()->language->id, 'stnewsletter_verif', Mail::l('Email verification', Context::getContext()->language->id), array('{verif_url}' => $verif_url), $email, null, null, null, null, null, dirname(__FILE__).'/mails/', false, Context::getContext()->shop->id);
	}
    
    public function export_csv()
	{
		if (!isset($this->context))
			$this->context = Context::getContext();

		$result = $this->getCustomers();

		if ($result)
		{
			if (!$nb = count($result))
				$this->_html .= $this->displayError($this->l('No customers found with these filters!'));
			elseif ($fd = @fopen(dirname(__FILE__).'/'.strval(preg_replace('#\.{2,}#', '.', Tools::getValue('action'))).'_'.$this->file, 'w'))
			{
				$header = array('id', 'shop_name', 'gender', 'lastname', 'firstname', 'email', 'subscribed', 'subscribed_on');
				$array_to_export = array_merge(array($header), $result);
				foreach ($array_to_export as $tab)
					$this->myFputCsv($fd, $tab);
				fclose($fd);
				$this->_html .= $this->displayConfirmation(
					sprintf($this->l('The .CSV file has been successfully exported: %d customers found.'), $nb).'<br />
				<a href="'.$this->context->shop->getBaseURI().'modules/'.$this->name.'/'.Tools::safeOutput(strval(Tools::getValue('action'))).'_'.$this->file.'">
				<b>'.$this->l('Download the file').' '.$this->file.'</b>
				</a>
				<br />
				<ol style="margin-top: 10px;">
					<li style="color: red;">'.
					$this->l('WARNING: When opening this .csv file with Excel, choose UTF-8 encoding to avoid strange characters.').
					'</li>
				</ol>');
			}
			else
				$this->_html .= $this->displayError($this->l('Error: Write access limited').' '.dirname(__FILE__).'/'.strval(Tools::getValue('action')).'_'.$this->file.' !');
		}
		else
			$this->_html .= $this->displayError($this->l('No result found!'));
	}

	private function getCustomers()
	{
		$id_shop = false;

		// Get the value to know with subscrib I need to take 1 with account 2 without 0 both 3 not subscrib
		$who = (int)Tools::getValue('SUSCRIBERS');

		// get optin 0 for all 1 no optin 2 with optin
		$optin = (int)Tools::getValue('OPTIN');

		$country = (int)Tools::getValue('COUNTRY');

		if (Context::getContext()->cookie->shopContext)
			$id_shop = (int)Context::getContext()->shop->id;

		$customers = array();
		if ($who == 1 || $who == 0 || $who == 3)
		{
			$dbquery = new DbQuery();
			$dbquery->select('c.`id_customer` AS `id`, s.`name` AS `shop_name`, gl.`name` AS `gender`, c.`lastname`, c.`firstname`, c.`email`, c.`newsletter` AS `subscribed`, c.`newsletter_date_add`');
			$dbquery->from('customer', 'c');
			$dbquery->leftJoin('shop', 's', 's.id_shop = c.id_shop');
			$dbquery->leftJoin('gender', 'g', 'g.id_gender = c.id_gender');
			$dbquery->leftJoin('gender_lang', 'gl', 'g.id_gender = gl.id_gender AND gl.id_lang = '.$this->context->employee->id_lang);
			$dbquery->where('c.`newsletter` = '.($who == 3 ? 0 : 1));
			if ($optin == 2 || $optin == 1)
				$dbquery->where('c.`optin` = '.($optin == 1 ? 0 : 1));
			if ($country)
				$dbquery->where('(SELECT COUNT(a.`id_address`) as nb_country
													FROM `'._DB_PREFIX_.'address` a
													WHERE a.deleted = 0
													AND a.`id_customer` = c.`id_customer`
													AND a.`id_country` = '.$country.') >= 1');
			if ($id_shop)
				$dbquery->where('c.`id_shop` = '.$id_shop);

			$customers = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dbquery->build());
		}

		$non_customers = array();
		if (($who == 0 || $who == 2) && (!$optin || $optin == 2) && !$country)
		{
			$dbquery = new DbQuery();
			$dbquery->select('CONCAT(\'N\', n.`id`) AS `id`, s.`name` AS `shop_name`, NULL AS `gender`, NULL AS `lastname`, NULL AS `firstname`, n.`email`, n.`active` AS `subscribed`, n.`newsletter_date_add`');
			$dbquery->from('newsletter', 'n');
			$dbquery->leftJoin('shop', 's', 's.id_shop = n.id_shop');
			$dbquery->where('n.`active` = 1');
			if ($id_shop)
				$dbquery->where('n.`id_shop` = '.$id_shop);
			$non_customers = Db::getInstance()->executeS($dbquery->build());
		}

		$subscribers = array_merge($customers, $non_customers);

		return $subscribers;
	}

	private function myFputCsv($fd, $array)
	{
		$line = implode(';', $array);
		$line .= "\n";
		if (!fwrite($fd, $line, 4096))
			$this->post_errors[] = $this->l('Error: Write access limited').' '.dirname(__FILE__).'/'.$this->file.' !';
	}
    private function getDisplayOn($value = 0)
    {
        $ret = array();
        if (!$value)
            return $ret;
        foreach($this->_pages AS $v)
            if ((int)$v['val']&(int)$value)
                $ret[] = $v['id'];
        return $ret;
    }
}