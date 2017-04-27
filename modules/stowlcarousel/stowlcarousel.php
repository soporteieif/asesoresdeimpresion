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

include_once dirname(__FILE__).'/StOwlCarouselClass.php';
include_once dirname(__FILE__).'/StOwlCarouselGroup.php';
include_once dirname(__FILE__).'/StOwlCarouselFontClass.php';

class StOwlCarousel extends Module
{
    protected static $access_rights = 0775;
    public static $location = array(
        21 => array('id' =>21 , 'name' => 'Full width top', 'full_width' => 1),
        26 => array('id' =>26 , 'name' => 'Full width top 2', 'full_width' => 1),
        19 => array('id' =>19 , 'name' => 'Top column'),
        17 => array('id' =>17 , 'name' => 'HomepageTop'),
        3 => array('id' =>3 , 'name' => 'Homepage'),
        18 => array('id' =>18 , 'name' => 'HomepageBottom'),
        25 => array('id' =>25 , 'name' => 'Homepage secondary left'),
        15 => array('id' =>15 , 'name' => 'Homepage secondary right'),
        23 => array('id' =>23 , 'name' => 'Homepage tertiary left'),
        24 => array('id' =>24 , 'name' => 'Homepage tertiary right'),
        20 => array('id' =>20 , 'name' => 'Bottom column'),
        22 => array('id' =>22 , 'name' => 'Full width bottom(Home very bottom)', 'full_width' => 1),
        2 => array('id' =>2 , 'name' => 'Left column'),
        5 => array('id' =>5 , 'name' => 'Right column'),
        7 => array('id' =>7 , 'name' => 'Blog homepage top(fullwidth)', 'full_width' => 1),
        8 => array('id' =>8 , 'name' => 'Blog homepage top'),
        6 => array('id' =>6 , 'name' => 'Blog homepage'),
        9 => array('id' =>9 , 'name' => 'Blog left column'),
        10 => array('id' =>10 , 'name' => 'Blog right column'),
        11 => array('id' =>11 , 'name' => 'At bottom of product page'),
        12 => array('id' =>12 , 'name' => 'At bottom of category page'),
        16 => array('id' =>16 , 'name' => 'Product secondary column'),
    );
    public static $transition_style = array(
        array('id' =>0 , 'name' => 'fade'),
        array('id' =>1 , 'name' => 'backSlide'),
        array('id' =>2 , 'name' => 'goDown'),
        array('id' =>3 , 'name' => 'fadeUp'),
    );
    public static $_type = array(
        1 => 'location',
        2 => 'id_category',
        4 => 'id_cms',
        5 => 'id_cms_category',
        6 => 'id_manufacturer',
    );
    public static $text_position = array(
        array('id' =>'center' , 'name' => 'Middle'),
        array('id' =>'bottom' , 'name' => 'Bottom'),
        array('id' =>'top' , 'name' => 'Top'),
    );

    public static $templates = array(
        0 => array(
        ),
        1 => array(
            'child' => array(
                575,
            ),
        ),
        2 => array(     
            'child' => array(
                376,
                376,
            ),
            'banners' => true,
        ),
        3 => array(     
            'child' => array(
            ),
        ),
        /*4 => array(     
            'child' => array(
                376,
                376,
                376,
            ),
            'banners' => true,
        ),*/
    );

    public  $text_animation_group;
    public static $text_animation = array(
        0=>'',
        1=>'flash',
        2=>'shake',
        3=>'bounce',
        4=>'tada',
        5=>'swing',
        6=>'wobble',
        7=>'pulse',
        61=>'rubberBand',

        8=>'flip',
        9=>'flipInX',
        11=>'flipInY',
        13=>'fadeIn',

        14=>'fadeInUp',
        15=>'fadeInDown',
        16=>'fadeInLeft',
        17=>'fadeInRight',
        18=>'fadeInUpBig',
        19=>'fadeInDownBig',
        20=>'fadeInLeftBig',
        21=>'fadeInRightBig',

        31=>'slideInDown',
        32=>'slideInLeft',
        33=>'slideInRight',

        37=>'bounceIn',
        38=>'bounceInUp',
        39=>'bounceInDown',
        40=>'bounceInLeft',
        41=>'bounceInRight',

        47=>'rotateIn',
        48=>'rotateInUpLeft',
        49=>'rotateInDownLeft',
        50=>'rotateInUpRight',
        51=>'rotateInDownRight',
        57=>'lightSpeedIn',
        60=>'rollIn',

        62=>'zoomIn',
        63=>'zoomInDown',
        64=>'zoomInLeft',
        65=>'zoomInRight',
        66=>'zoomInUp',

        67=>'fadeUp',
    );


    public  $fields_list;
    public  $fields_list_slide;
    public  $fields_list_banner;
    public  $fields_value;
    public  $fields_form;
    public  $fields_form_slide;
	private $_html = '';
	private $spacer_size = '5';

    private $googleFonts;

	public function __construct()
	{
		$this->name          = 'stowlcarousel';
		$this->tab           = 'front_office_features';
		$this->version       = '2.2.9';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
        $this->bootstrap     = true;

		parent::__construct();
        
        $this->googleFonts = include_once(dirname(__FILE__).'/googlefonts.php');

        $this->text_animation_group = array(
            array('name'=>$this->l('Flippers'),'query'=>array(
                array('id'=>8, 'name'=>self::$text_animation[8]),
                array('id'=>9, 'name'=>self::$text_animation[9]),
                array('id'=>11, 'name'=>self::$text_animation[11]),
            )),
            array('name'=>$this->l('Fading Entrances'),'query'=>array(
                array('id'=>14, 'name'=>self::$text_animation[14]),
                array('id'=>15, 'name'=>self::$text_animation[15]),
                array('id'=>16, 'name'=>self::$text_animation[16]),
                array('id'=>17, 'name'=>self::$text_animation[17]),
                array('id'=>18, 'name'=>self::$text_animation[18]),
                array('id'=>19, 'name'=>self::$text_animation[19]),
                array('id'=>20, 'name'=>self::$text_animation[20]),
                array('id'=>21, 'name'=>self::$text_animation[21]),
                array('id'=>67, 'name'=>self::$text_animation[67]),
            )),
            array('name'=>$this->l('Zoom Entrances'),'query'=>array(
                array('id'=>62, 'name'=>self::$text_animation[62]),
                array('id'=>63, 'name'=>self::$text_animation[63]),
                array('id'=>64, 'name'=>self::$text_animation[64]),
                array('id'=>65, 'name'=>self::$text_animation[65]),
                array('id'=>66, 'name'=>self::$text_animation[66]),
            )),
            array('name'=>$this->l('Sliders'),'query'=>array(
                array('id'=>31, 'name'=>self::$text_animation[31]),
                array('id'=>32, 'name'=>self::$text_animation[32]),
                array('id'=>33, 'name'=>self::$text_animation[33]),
            )),
            array('name'=>$this->l('Bouncing Entrances'),'query'=>array(
                array('id'=>37, 'name'=>self::$text_animation[37]),
                array('id'=>38, 'name'=>self::$text_animation[38]),
                array('id'=>39, 'name'=>self::$text_animation[39]),
                array('id'=>40, 'name'=>self::$text_animation[40]),
                array('id'=>41, 'name'=>self::$text_animation[41]),
            )),
            array('name'=>$this->l('Rotating Entrances'),'query'=>array(
                array('id'=>47, 'name'=>self::$text_animation[47]),
                array('id'=>48, 'name'=>self::$text_animation[48]),
                array('id'=>49, 'name'=>self::$text_animation[49]),
                array('id'=>50, 'name'=>self::$text_animation[50]),
                array('id'=>51, 'name'=>self::$text_animation[51]),
            )),
            array('name'=>$this->l('Lightspeed'),'query'=>array(
                array('id'=>57, 'name'=>self::$text_animation[57]),
            )),
            array('name'=>$this->l('Specials'),'query'=>array(
                array('id'=>60, 'name'=>self::$text_animation[60]),
            )),
            array('name'=>$this->l('Attention seekers'),'query'=>array(
                array('id'=>1, 'name'=>self::$text_animation[1]),
                array('id'=>2, 'name'=>self::$text_animation[2]),
                array('id'=>3, 'name'=>self::$text_animation[3]),
                array('id'=>4, 'name'=>self::$text_animation[4]),
                array('id'=>5, 'name'=>self::$text_animation[5]),
                array('id'=>6, 'name'=>self::$text_animation[6]),
                array('id'=>7, 'name'=>self::$text_animation[7]),
                array('id'=>61, 'name'=>self::$text_animation[61]),
            )),
        );
              
		$this->displayName   = $this->l('Owl Carousel');
		$this->description   = $this->l('Touch enabled jQuery plugin that lets you create beautiful responsive carousel slider.');
	}
        
	public function install()
	{
		$res = parent::install() &&
			$this->installDb() &&
            $this->registerHook('displayHeader') &&
			$this->registerHook('displayLeftColumn') && 
			$this->registerHook('displayRightColumn') && 
            $this->registerHook('displayHome') &&
            $this->registerHook('displayHomeTop') &&
            $this->registerHook('displayHomeBottom') &&
            $this->registerHook('displayHomeTertiaryLeft') &&
            $this->registerHook('displayHomeTertiaryRight') &&
            $this->registerHook('displayCategoryHeader') &&
            $this->registerHook('displayManufacturerHeader') &&
			$this->registerHook('displayAnywhere') &&
			$this->registerHook('actionObjectCategoryDeleteAfter') &&
            $this->registerHook('actionObjectManufacturerDeleteAfter') &&
            $this->registerHook('displayFooterProduct') &&
            $this->registerHook('displayCategoryFooter') &&
            $this->registerHook('actionShopDataDuplication') &&
			$this->registerHook('displayStBlogHome') &&
			$this->registerHook('displayStBlogLeftColumn') && 
			$this->registerHook('displayStBlogRightColumn') &&
            $this->registerHook('displayProductSecondaryColumn') &&
            $this->registerHook('displayHomeSecondaryLeft') && 
            $this->registerHook('displayHomeSecondaryRight') && 
            $this->registerHook('displayTopColumn') && 
            $this->registerHook('displayHomeVeryBottom') && 
            $this->registerHook('displayFullWidthTop') && 
            $this->registerHook('displayFullWidthTop2') && 
            $this->registerHook('displayHomeVeryBottom');
		if ($res)
			foreach(Shop::getShops(false) as $shop)
				$res &= $this->sampleData($shop['id_shop']);
        $this->clearOwlCarouselCache();
        return $res;
	}
	
	/**
	 * Creates tables
	 */
	public function installDb()
	{
		/* Slides */
		$return = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_owl_carousel` (
				`id_st_owl_carousel` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_st_owl_carousel_group` int(10) unsigned NOT NULL,
                `new_window` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `text_position` varchar(32) DEFAULT NULL,
                `text_align` tinyint(1) unsigned NOT NULL DEFAULT 2,
                `text_color` varchar(7) DEFAULT NULL,
                `text_bg` varchar(7) DEFAULT NULL,
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `position` int(10) unsigned NOT NULL DEFAULT 0,
                `isbanner` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `hide_text_on_mobile` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `text_animation` tinyint(2) unsigned NOT NULL DEFAULT 0,
                `btn_color` varchar(7) DEFAULT NULL,
                `btn_bg` varchar(7) DEFAULT NULL,
                `btn_hover_color` varchar(7) DEFAULT NULL,
                `btn_hover_bg` varchar(7) DEFAULT NULL,
                `text_width` tinyint(2) unsigned NOT NULL DEFAULT 0,
				PRIMARY KEY (`id_st_owl_carousel`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		/* Slides lang configuration */
		$return && $return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_owl_carousel_lang` (
				`id_st_owl_carousel` int(10) UNSIGNED NOT NULL,
				`id_lang` int(10) unsigned NOT NULL ,
    			`url` varchar(255) DEFAULT NULL,
                `description` text,
                `image_multi_lang` varchar(255) DEFAULT NULL,
                `thumb_multi_lang` varchar(255) DEFAULT NULL,
                `title` varchar(255) DEFAULT NULL,
                `width` int(10) unsigned NOT NULL DEFAULT 0,
                `height` int(10) unsigned NOT NULL DEFAULT 0,
				PRIMARY KEY (`id_st_owl_carousel`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
            
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_owl_carousel_font` (
                `id_st_owl_carousel` int(10) unsigned NOT NULL,
                `font_name` varchar(255) NOT NULL
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		/* Slides group */
		$return && $return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_owl_carousel_group` (
				`id_st_owl_carousel_group` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,       
                `name` varchar(255) DEFAULT NULL,
                `location` int(10) unsigned NOT NULL DEFAULT 0,
                `templates` tinyint(2) unsigned NOT NULL DEFAULT 0, 
                `items_huge` tinyint(2) unsigned NOT NULL DEFAULT 1, 
                `items_xxlg` tinyint(2) unsigned NOT NULL DEFAULT 1, 
                `items_xlg` tinyint(2) unsigned NOT NULL DEFAULT 1, 
                `items_lg` tinyint(2) unsigned NOT NULL DEFAULT 1, 
                `items_md` tinyint(2) unsigned NOT NULL DEFAULT 1, 
                `items_sm` tinyint(2) unsigned NOT NULL DEFAULT 1, 
                `items_xs` tinyint(2) unsigned NOT NULL DEFAULT 1, 
                `items_xxs` tinyint(2) unsigned NOT NULL DEFAULT 1, 
                `id_category` int(10) unsigned NOT NULL DEFAULT 0,
                `id_manufacturer` int(10) unsigned NOT NULL DEFAULT 0,
                `id_cms` int(10) unsigned NOT NULL DEFAULT 0,
                `id_cms_category` int(10) unsigned NOT NULL DEFAULT 0,  
                `trans_period` int(10) unsigned NOT NULL DEFAULT 1000,
                `auto_advance` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `time` int(10) unsigned NOT NULL DEFAULT 7000,
                `auto_height` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `pause` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `pag_nav` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `pag_nav_bg` varchar(7) DEFAULT NULL,
                `pag_nav_bg_active` varchar(7) DEFAULT NULL,
                `prev_next` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `prev_next_color` varchar(7) DEFAULT NULL,
                `prev_next_hover` varchar(7) DEFAULT NULL,
                `prev_next_bg` varchar(7) DEFAULT NULL,
                `prev_next_bg_hover` varchar(7) DEFAULT NULL,
                `hide_on_mobile` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `progress_bar` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `prog_bar_color` varchar(7) DEFAULT NULL,
                `prog_bar_bg` varchar(7) DEFAULT NULL, 
                `mouse_drag` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `transition_style` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `rewind_nav` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `position` int(10) unsigned NOT NULL DEFAULT 0, 
                `top_spacing` varchar(10) DEFAULT NULL,
                `bottom_spacing` varchar(10) DEFAULT NULL,
                `slider_spacing` varchar(10) DEFAULT NULL,
                `show_on_sub` tinyint(1) unsigned NOT NULL DEFAULT 1,
				PRIMARY KEY (`id_st_owl_carousel_group`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		/* Slides group shop */
		$return && $return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_owl_carousel_group_shop` (
				`id_st_owl_carousel_group` int(10) UNSIGNED NOT NULL,
                `id_shop` int(11) NOT NULL,      
                PRIMARY KEY (`id_st_owl_carousel_group`,`id_shop`),    
                KEY `id_shop` (`id_shop`)   
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		return $return;
	}
    
    public function sampleData($id_shop)
    {
        $return = true;
        $path = _MODULE_DIR_.$this->name;
        $samples = array(
            array('id_st_owl_carousel_group' => '', 
                'name' => 'Main slideshow',
                'location' => 21, 
                'template' => 0, 
                'prev_next' => 4, 
                'pag_nav' => 0,
                'trans_period' => 400,
                'time' => 7000,
                'auto_advance' => 1,
                'rewind_nav' => 1,
                'progress_bar' => 2,
                'transition_style' => 3,
                'child' => array(
                    array(
                        'text_position' => 'center', 
                        'text_align' => 2, 
                        'text_color' => '', 
                        'url' => '', 
                        'description' => '', 
                        'image_multi_lang' => $path.'/views/img/sample_3.jpg', 
                        'thumb_multi_lang' => $path.'/views/img/sample_3_thumb.jpg',
                        'width' => 1900, 
                        'height' => 550, 
                    ),
                    array(
                        'text_position' => 'center', 
                        'text_align' => 2, 
                        'text_color' => '', 
                        'url' => '', 
                        'description' => '', 
                        'image_multi_lang' => $path.'/views/img/sample_4.jpg', 
                        'thumb_multi_lang' => $path.'/views/img/sample_4_thumb.jpg',
                        'width' => 1900, 
                        'height' => 550, 
                    ),
                ),
            ),
            array('id_st_owl_carousel_group' => '', 
                'name' => 'Leftcolumn',
                'location' => 2, 
                'template' => 0, 
                'prev_next' => 2, 
                'pag_nav' => 0,
                'trans_period' => 400,
                'time' => 7000,
                'auto_advance' => 1,
                'rewind_nav' => 1,
                'progress_bar' => 0,
                'transition_style' => 3,
                'child' => array(
                    array(
                        'text_position' => 'center', 
                        'text_align' => 2, 
                        'text_color' => '#ffffff', 
                        'url' => '', 
                        'description' => '<h3 class="closer" style="font-family:\'Fjalla One\';">SALE</h3><div class="line_white"> </div><h6 class="closer" style="font-family:\'Fjalla One\';">AUTOMON LOOK</h6>', 
                        'image_multi_lang' => $path.'/views/img/sample_1.jpg', 
                        'thumb_multi_lang' => $path.'/views/img/sample_1_thumb.jpg',
                        'width' => 270, 
                        'height' => 320, 
                    ),
                    array(
                        'text_position' => 'center', 
                        'text_align' => 2, 
                        'text_color' => '#ffffff', 
                        'url' => '', 
                        'description' => '<h3 class="closer" style="font-family:\'Fjalla One\';">NEW</h3><div class="line_white"> </div><h6 class="closer uppercase" style="font-family:\'Fjalla One\';">New arrivals</h6>', 
                        'image_multi_lang' => $path.'/views/img/sample_2.jpg', 
                        'thumb_multi_lang' => $path.'/views/img/sample_2_thumb.jpg',
                        'width' => 270, 
                        'height' => 320, 
                    ),
                ),
            ),
        );
        foreach($samples as $k=>&$sample)
        {
            $module = new StOwlCarouselGroup();
            $module->name             = $sample['name'];
            $module->location         = $sample['location'];
            $module->template         = $sample['template'];
            $module->prev_next        = $sample['prev_next'];
            $module->pag_nav          = $sample['pag_nav'];
            $module->auto_advance     = $sample['auto_advance'];
            $module->rewind_nav       = $sample['rewind_nav'];
            $module->progress_bar     = $sample['progress_bar'];
            $module->transition_style = $sample['transition_style'];
            $module->trans_period     = $sample['trans_period'];
            $module->time             = $sample['time'];                
            $module->active           = 1;
            $module->position         = $k;
            $return &= $module->add();
            //
            if($return && $module->id)
            {
                $sample['id_st_owl_carousel_group'] = $module->id;
                Db::getInstance()->insert('st_owl_carousel_group_shop', array(
                    'id_st_owl_carousel_group' => (int)$module->id,
                    'id_shop' => (int)$id_shop,
                ));
            }
        }
        
        foreach($samples as $sp)
        {
            if(!$sp['id_st_owl_carousel_group'] || !isset($sp['child']) || !count($sp['child']))
                continue;
            foreach($sp['child'] as $k=>$v)
            {
                $module = new StOwlCarouselClass();
                $module->id_st_owl_carousel_group = $sp['id_st_owl_carousel_group'];
                $module->text_position = $v['text_position'];
                $module->text_align = $v['text_align'];
                $module->text_color = $v['text_color'];
                $module->hide_text_on_mobile = 1;
                $module->active = 1;
                $module->position = $k;
                
                foreach (Language::getLanguages(false) as $lang)
                {
                    $module->url[$lang['id_lang']] = $v['url'];
                    $module->description[$lang['id_lang']] = $v['description'];
                    $module->image_multi_lang[$lang['id_lang']] = $v['image_multi_lang'];
                    $module->thumb_multi_lang[$lang['id_lang']] = $v['thumb_multi_lang'];
                    $module->width[$lang['id_lang']] = $v['width'];
                    $module->height[$lang['id_lang']] = $v['height'];
                }
                
                $return &= $module->add();
            }
        }
        return $return;
    }
     
	public function uninstall()
	{
	    $this->clearOwlCarouselCache();
		// Delete configuration
		return $this->uninstallDb() &&
			parent::uninstall();
	}

	/**
	 * deletes tables
	 */
	public function uninstallDb()
	{
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_owl_carousel`,`'._DB_PREFIX_.'st_owl_carousel_lang`,`'._DB_PREFIX_.'st_owl_carousel_font`,`'._DB_PREFIX_.'st_owl_carousel_group`,`'._DB_PREFIX_.'st_owl_carousel_group_shop`');
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
		$this->context->controller->addCSS(($this ->_path).'views/css/admin.css');
		$this->context->controller->addJS(($this->_path).'views/js/admin.js');
        
        $this->_html .= '<script type="text/javascript">var googleFontsString=\''.Tools::jsonEncode($this->googleFonts).'\';</script>';
        
        $check_result = $this->_checkImageDir();
        
        $id_st_owl_carousel_group = (int)Tools::getValue('id_st_owl_carousel_group');
        $id_st_owl_carousel = (int)Tools::getValue('id_st_owl_carousel');
	    if ((Tools::isSubmit('groupstatusstowlcarousel')))
        {
            $slide_group = new StOwlCarouselGroup((int)$id_st_owl_carousel_group);
            if($slide_group->id && $slide_group->toggleStatus())
            {
                $this->clearOwlCarouselCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
	    if ((Tools::isSubmit('slidestatusstowlcarousel')))
        {
            $slide = new StOwlCarouselClass((int)$id_st_owl_carousel);
            if($slide->id && $slide->toggleStatus())
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));  
                $this->clearOwlCarouselCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_owl_carousel_group='.$slide->id_st_owl_carousel_group.'&viewstowlcarousel&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
        if (Tools::isSubmit('way') && Tools::isSubmit('id_st_owl_carousel') && (Tools::isSubmit('position')))
		{
		    $slide = new StOwlCarouselClass((int)$id_st_owl_carousel);
            if($slide->id && $slide->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position')))
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));  
                $this->clearOwlCarouselCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_owl_carousel_group='.$slide->id_st_owl_carousel_group.'&viewstowlcarousel&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            else
                $this->_html .= $this->displayError($this->l('Failed to update the position.'));
		}
        if (Tools::isSubmit('copystowlcarousel'))
        {
            if($this->processCopyOwlCarousel($id_st_owl_carousel_group))
            {
                $this->clearOwlCarouselCache();
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=19&token='.Tools::getAdminTokenLite('AdminModules'));
            }  
            else
                $this->_html .= $this->displayError($this->l('An error occurred while copy Owl Carousel.'));
        }
        if (Tools::getValue('action') == 'updatePositions')
        {
            $this->processUpdatePositions();
        }
		if (isset($_POST['savestowlcarouselgroup']) || isset($_POST['savestowlcarouselgroupAndStay']))
		{
            if ($id_st_owl_carousel_group)
				$slide_group = new StOwlCarouselGroup((int)$id_st_owl_carousel_group);
			else
				$slide_group = new StOwlCarouselGroup();
            
            $error = array();
    		$slide_group->copyFromPost();
            
            if(!$slide_group->name)
                $error[] = $this->displayError($this->l('The field "Group name" is required'));
            
            if($slide_group->location)
            {
                $item_arr = explode('-',$slide_group->location);
                if(count($item_arr)==2)
                {
                    foreach(self::$_type as $k=>$v)
                    {
                        if($k==$item_arr[0])
                            $slide_group->$v = (int)$item_arr[1];
                        else
                            $slide_group->$v = 0;
                    }
                }
            }  
            
			if (!count($error) && $slide_group->validateFields(false) && $slide_group->validateFieldsLang(false))
            {
                if($slide_group->save())
                {
		            Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'st_owl_carousel_group_shop WHERE id_st_owl_carousel_group='.(int)$slide_group->id);
                    if (!Shop::isFeatureActive())
            		{
            			Db::getInstance()->insert('st_owl_carousel_group_shop', array(
            				'id_st_owl_carousel_group' => (int)$slide_group->id,
            				'id_shop' => (int)Context::getContext()->shop->id,
            			));
            		}
            		else
            		{
            			$assos_shop = Tools::getValue('checkBoxShopAsso_st_owl_carousel_group');
            			if (empty($assos_shop))
            				$assos_shop[(int)Context::getContext()->shop->id] = Context::getContext()->shop->id;
            			foreach ($assos_shop as $id_shop => $row)
            				Db::getInstance()->insert('st_owl_carousel_group_shop', array(
            					'id_st_owl_carousel_group' => (int)$slide_group->id,
            					'id_shop' => (int)$id_shop,
            				));
            		}
                    
                    $this->clearOwlCarouselCache();
                    if(isset($_POST['savestowlcarouselgroupAndStay']) || Tools::getValue('fr') == 'view')
                    {
                        $rd_str = isset($_POST['savestowlcarouselgroupAndStay']) && Tools::getValue('fr') == 'view' ? 'fr=view&update' : (isset($_POST['savestowlcarouselgroupAndStay']) ? 'update' : 'view');
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_owl_carousel_group='.$slide_group->id.'&conf='.($id_st_owl_carousel_group?4:3).'&'.$rd_str.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                    }
                        
                    else
                        $this->_html .= $this->displayConfirmation($this->l('Slideshow').' '.($id_st_owl_carousel_group ? $this->l('updated') : $this->l('added')));
                }                    
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during slideshow').' '.($id_st_owl_carousel_group ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }
		if (isset($_POST['savestowlcarousel']) || isset($_POST['savestowlcarouselAndStay']))
		{
            if ($id_st_owl_carousel)
				$slide = new StOwlCarouselClass((int)$id_st_owl_carousel);
			else
				$slide = new StOwlCarouselClass();
            /**/
            
            $error = array();
            
            $languages = Language::getLanguages(false);
            $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
            if (!Tools::isSubmit('has_image_'.$default_lang) && (!isset($_FILES['image_multi_lang_'.$default_lang]) || empty($_FILES['image_multi_lang_'.$default_lang]['tmp_name'])))
			{
                $defaultLanguage = new Language($default_lang);
			    $error[] = $this->displayError($this->l('Image is required at least in ').$defaultLanguage->name);
			}
            else
            {
			    $slide->copyFromPost();
                if(!$slide->id_st_owl_carousel_group)
                    $error[] = $this->displayError($this->l('The field "Slideshow" is required'));
                else
                {
                    $res = $this->stUploadImage('image_multi_lang_'.$default_lang);
                    if(count($res['error']))
                        $error = array_merge($error,$res['error']);
                    elseif($res['image'] && $res['thumb'])
                    {
                        $slide->image_multi_lang[$default_lang] = $res['image'];
                        $slide->thumb_multi_lang[$default_lang] = $res['thumb'];
                        $slide->width[$default_lang] = $res['width'];
                        $slide->height[$default_lang] = $res['height'];
                    }
                    elseif(!Tools::isSubmit('has_image_'.$default_lang) && !$res['image'] && !$res['thumb'])
                    {
                        $defaultLanguage = new Language($default_lang);
                        $error[] = $this->displayError($this->l('Image is required at least in ').$defaultLanguage->name);
                    }
                    
                    if($slide->image_multi_lang[$default_lang] && $slide->thumb_multi_lang[$default_lang])
                    {
                        foreach ($languages as $lang)
                        {
                            if($lang['id_lang']==$default_lang)
                                continue;
                            $res = $this->stUploadImage('image_multi_lang_'.$lang['id_lang']);
                            if(count($res['error']))
                                $error = array_merge($error,$res['error']);
                            elseif($res['image'] && $res['thumb'])
                            {
                                $slide->image_multi_lang[$lang['id_lang']] = $res['image'];
                                $slide->thumb_multi_lang[$lang['id_lang']] = $res['thumb'];
                                $slide->width[$lang['id_lang']] = $res['width'];
                                $slide->height[$lang['id_lang']] = $res['height'];
                            }
                            elseif(!Tools::isSubmit('has_image_'.$lang['id_lang']) && !$res['image'] && !$res['thumb'])
                            {
                                $slide->image_multi_lang[$lang['id_lang']] = $slide->image_multi_lang[$default_lang];
                                $slide->thumb_multi_lang[$lang['id_lang']] = $slide->thumb_multi_lang[$default_lang];
                                $slide->width[$lang['id_lang']] = $slide->width[$default_lang];
                                $slide->height[$lang['id_lang']] = $slide->height[$default_lang];
                            }
                        }
                    }
                }
            }
                
			if (!count($error) && $slide->validateFields(false) && $slide->validateFieldsLang(false))
            {
                /*position*/
                $slide->position = $slide->checkPosition();
                
                if($slide->save())
                {
                    $jon = trim(Tools::getValue('google_font_name'),'¤');
                    StOwlCarouselFontClass::deleteBySlider($slide->id);
                    $jon_arr = array_unique(explode('¤', $jon));
                    if (count($jon_arr))
                        StOwlCarouselFontClass::changeSliderFont($slide->id, $jon_arr);

                    $this->clearOwlCarouselCache();
                    //$this->_html .= $this->displayConfirmation($this->l('Slide').' '.($id_st_owl_carousel ? $this->l('updated') : $this->l('added')));
                    
                    if(isset($_POST['savestowlcarouselAndStay']))
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_owl_carousel='.$slide->id.'&conf='.($id_st_owl_carousel?4:3).'&update'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));  
                    else
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_owl_carousel_group='.$slide->id_st_owl_carousel_group.'&viewstowlcarousel&token='.Tools::getAdminTokenLite('AdminModules'));
                }
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during slide').' '.($id_st_owl_carousel ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }
        
		if (Tools::isSubmit('addstowlcarouselgroup') || (Tools::isSubmit('updatestowlcarousel') && $id_st_owl_carousel_group))
		{
            $helper = $this->initForm();
            return $helper->generateForm($this->fields_form);
		}
        elseif(Tools::isSubmit('addstowlcarousel') || (Tools::isSubmit('updatestowlcarousel') && $id_st_owl_carousel))
        {
            $helper = $this->initFormSlide(0);
            return $this->_html.$helper->generateForm($this->fields_form_slide);
        }
        elseif(Tools::isSubmit('addstowlcarouselbanner') || (Tools::isSubmit('updatestowlcarousel') && $id_st_owl_carousel))
        {
            $helper = $this->initFormSlide(1);
            return $this->_html.$helper->generateForm($this->fields_form_slide);
        }
        elseif(Tools::isSubmit('viewstowlcarousel'))
        {
            $this->_html .= '<script type="text/javascript">var currentIndex="'.AdminController::$currentIndex.'&configure='.$this->name.'";</script>';
			$slide_group = new StOwlCarouselGroup($id_st_owl_carousel_group);
            if(!$slide_group->isAssociatedToShop())
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
			$helper = $this->initListSlide();
            if(isset(self::$templates[$slide_group->templates]['banners']) && self::$templates[$slide_group->templates]['banners'])
                $helper_banner = $this->initListBanner();
			return $this->_html.$helper->generateList(StOwlCarouselClass::getAll($id_st_owl_carousel_group,(int)$this->context->language->id,0,0), $this->fields_list).(isset($helper_banner) ? $helper_banner->generateList(StOwlCarouselClass::getAll($id_st_owl_carousel_group,(int)$this->context->language->id,0,1), $this->fields_list_banner) : '');
        }
		else if (Tools::isSubmit('deletestowlcarousel') && $id_st_owl_carousel)
		{
			$slide = new StOwlCarouselClass($id_st_owl_carousel);
            $slide->delete();
            $this->clearOwlCarouselCache();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_owl_carousel_group='.$slide->id_st_owl_carousel_group.'&viewstowlcarousel&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else if (Tools::isSubmit('deletestowlcarousel') && $id_st_owl_carousel_group)
		{
			$slide_group = new StOwlCarouselGroup($id_st_owl_carousel_group);
            $slide_group->delete();
            $this->clearOwlCarouselCache();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else
		{
			$helper = $this->initList();
			return $this->_html.$helper->generateList(StOwlCarouselGroup::getAll(), $this->fields_list);
		}
	}
    public static function getType($row)
    {
        $type = array_flip(self::$_type);
        if($row['location'])
            return $type['location'];
        if($row['id_category'])
            return $type['id_category'];
        if($row['id_manufacturer'])
            return $type['id_manufacturer'];
        if($row['id_cms'])
            return $type['id_cms'];
        if($row['id_cms_category'])
            return $type['id_cms_category'];
        return false;
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
            $name = str_replace(strrchr($_FILES[$item]['name'], '.'), '', $_FILES[$item]['name']);
			$imagesize = array();
			$imagesize = @getimagesize($_FILES[$item]['tmp_name']);
			if (!empty($imagesize) &&
				in_array(strtolower(substr(strrchr($imagesize['mime'], '/'), 1)), array('jpg', 'gif', 'jpeg', 'png')) &&
				in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
			{
			    $this->_checkEnv();
				$temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
				$salt = $name ? Tools::str2url($name) : sha1(microtime());
                $c_name = $salt;
                $c_name_thumb = $c_name.'-thumb';
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
                    $result['width'] = $imagesize[0];
                    $result['height'] = $imagesize[1];
                }
                return $result;
			}
        }
        else
            return $result;
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
    public static function getApplyTo()
    {
        $module = new StOwlCarousel();
        $location = array();
        foreach(self::$location as $v)
            $location[] = array('id'=>'1-'.$v['id'],'name'=>$v['name']);
            
        $root_category = Category::getRootCategory();
        $category_arr = array();
        $module->getCategoryOption($category_arr,$root_category->id);
        //unset root category
        if(isset($category_arr[$root_category->id]))
            unset($category_arr[$root_category->id]);
            
        $manufacturer_arr = array();
		$manufacturers = Manufacturer::getManufacturers(false, Context::getContext()->language->id);
		foreach ($manufacturers as $manufacturer)
            $manufacturer_arr[] = array('id'=>'6-'.$manufacturer['id_manufacturer'],'name'=>$manufacturer['name']);
            
        $cms_arr = array();
		$module->getCMSOptions($cms_arr, 0, 1);
                
        return array(
            array('name'=>$module->l('Hook'),'query'=>$location),
            array('name'=>$module->l('Category'),'query'=>$category_arr),
            array('name'=>$module->l('CMS'),'query'=>$cms_arr),
            array('name'=>$module->l('Manufacturers'),'query'=>$manufacturer_arr),
        );
    }
    
    private function getCMSOptions(&$cms_arr, $parent = 0, $depth = 1, $id_lang = false)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		//$categories = $this->getCMSCategories(false, (int)$parent, (int)$id_lang);
		$pages = $this->getCMSPages((int)$parent, false, (int)$id_lang);

		$spacer = str_repeat('&nbsp;', $this->spacer_size * (int)$depth);

		/*foreach ($categories as $category)
		{
            $cms_arr[] = array('id'=>'5-'.$category['id_cms_category'],'name'=>$spacer.$category['name']);
			$this->getCMSOptions($cms_arr, $category['id_cms_category'], (int)$depth + 1, (int)$id_lang);
		}*/

		foreach ($pages as $page)
            $cms_arr[] = array('id'=>'4-'.$page['id_cms'],'name'=>$spacer.$page['meta_title']);
	}

    private function getCMSCategories($recursive = false, $parent = 1, $id_lang = false, $id_shop = false)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;

		if ($recursive === false)
        {
            if(version_compare(_PS_VERSION_, '1.6.0.12', '>='))
                $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
                FROM `'._DB_PREFIX_.'cms_category` bcp
                INNER JOIN `'._DB_PREFIX_.'cms_category_shop` cs
                ON (bcp.`id_cms_category` = cs.`id_cms_category`)
                INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
                ON (bcp.`id_cms_category` = cl.`id_cms_category`)
                WHERE cl.`id_lang` = '.(int)$id_lang.'
                AND cs.`id_shop` = '.(int)$id_shop.'
                AND cl.`id_shop` = '.(int)$id_shop.'
                AND bcp.`id_parent` = '.(int)$parent;
            else
                $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
                FROM `'._DB_PREFIX_.'cms_category` bcp
                INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
                ON (bcp.`id_cms_category` = cl.`id_cms_category`)
                WHERE cl.`id_lang` = '.(int)$id_lang.'
                AND bcp.`id_parent` = '.(int)$parent;

            return Db::getInstance()->executeS($sql);
        }
        else
        {
            if(version_compare(_PS_VERSION_, '1.6.0.12', '>='))
                $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
                FROM `'._DB_PREFIX_.'cms_category` bcp
                INNER JOIN `'._DB_PREFIX_.'cms_category_shop` cs
                ON (bcp.`id_cms_category` = cs.`id_cms_category`)
                INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
                ON (bcp.`id_cms_category` = cl.`id_cms_category`)
                WHERE cl.`id_lang` = '.(int)$id_lang.'
                AND cs.`id_shop` = '.(int)$id_shop.'
                AND cl.`id_shop` = '.(int)$id_shop.'
                AND bcp.`id_parent` = '.(int)$parent;
            else
                $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
                FROM `'._DB_PREFIX_.'cms_category` bcp
                INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
                ON (bcp.`id_cms_category` = cl.`id_cms_category`)
                WHERE cl.`id_lang` = '.(int)$id_lang.'
                AND bcp.`id_parent` = '.(int)$parent;

			$results = Db::getInstance()->executeS($sql);
			foreach ($results as $result)
			{
				$sub_categories = $this->getCMSCategories(true, $result['id_cms_category'], (int)$id_lang);
				if ($sub_categories && count($sub_categories) > 0)
					$result['sub_categories'] = $sub_categories;
				$categories[] = $result;
			}

			return isset($categories) ? $categories : false;
		}

	}

	private function getCMSPages($id_cms_category, $id_shop = false, $id_lang = false)
	{
		$id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		$sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
			FROM `'._DB_PREFIX_.'cms` c
			INNER JOIN `'._DB_PREFIX_.'cms_shop` cs
			ON (c.`id_cms` = cs.`id_cms`)
			INNER JOIN `'._DB_PREFIX_.'cms_lang` cl
			ON (c.`id_cms` = cl.`id_cms`)
			WHERE '.($id_cms_category?'c.`id_cms_category` = '.(int)$id_cms_category:'1').'
            AND cs.`id_shop` = '.(int)$id_shop.
            (version_compare(_PS_VERSION_, '1.6.0.12', '>=') ? ' AND cl.`id_shop` = '.(int)$id_shop : '' ).' 
			AND cl.`id_lang` = '.(int)$id_lang.'
			AND c.`active` = 1
			ORDER BY `position`';

		return Db::getInstance()->executeS($sql);
	}
    
    private function getCategoryOption(&$category_arr,$id_category = 1, $id_lang = false, $id_shop = false, $recursive = true)
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
		$category_arr[$category->id] = array(
            'id' => '2-'.$category->id,
            'name' => (isset($spacer) ? $spacer : '').$category->name.' ('.$shop->name.')',
        );
        
		if (isset($children) && count($children))
			foreach ($children as $child)
			{
				$this->getCategoryOption($category_arr,(int)$child['id_category'], (int)$id_lang, (int)$child['id_shop'],$recursive);
			}
	}
        
	protected function initForm()
	{        
        $id_st_owl_carousel_group = (int)Tools::getValue('id_st_owl_carousel_group');
        $slide_group = new StOwlCarouselGroup($id_st_owl_carousel_group);

		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Group configuration'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Group name:'),
					'name' => 'name',
                    'size' => 64,
                    'required'  => true,
				),
                array(
					'type' => 'select',
        			'label' => $this->l('Show on:'),
        			'name' => 'location',
                    'options' => array(
                        'optiongroup' => array (
							'query' => $this->getApplyTo(),
							'label' => 'name'
						),
						'options' => array (
							'query' => 'query',
							'id' => 'id',
							'name' => 'name'
						),
						'default' => array(
							'value' => 0,
							'label' => $this->l('--')
						)
        			),
                    'desc' => '<div class="alert alert-info"><a href="javascript:;" onclick="$(\'#des_page_layout\').toggle();return false;">'.$this->l('Click here to see hook position').'</a>'.
                        '<div id="des_page_layout" style="display:none;"><img src="'._MODULE_DIR_.'stthemeeditor/img/hook_into_hint.jpg" /></div></div>',
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Show on subcategories:'),
					'name' => 'show_on_sub',
                    'default_value' => 1,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'show_on_sub_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'show_on_sub_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('Actually just apply to categories.')
				),
                'templates' => array(
                    'type' => 'html',
                    'id' => 'style',
                    'label' => $this->l('templates:'),
                    'name' => $this->BuildRadioUI('templates', $slide_group->templates ? $slide_group->templates : 0),
                    'desc' => '',
                ),
                array(
                    'type' => 'html',
                    'id' => 'items',
                    'label'=> $this->l('Items'),
                    'name' => $this->BuildDropListGroup($slide_group->templates==3, $slide_group),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Space between slidrs:'),
                    'name' => 'slider_spacing',
                    'default_value' => '',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Leave it empty to use the default value.'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Transition style:'),
                    'name' => 'transition_style',
                    'options' => array(
                        'query' => self::$transition_style,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('Works only with one item on screen.'),
                    'validation' => 'isUnsignedInt',
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
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Auto height:'),
                    'name' => 'auto_height',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'auto_height_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'auto_height_off',
                            'value' => 0,
                            'label' => $this->l('No')),
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
					'type' => 'text',
					'label' => $this->l('Position:'),
					'name' => 'position',
                    'default_value' => 0,
                    'class' => 'fixed-width-sm'                  
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
				'title' => $this->l('Advanced settings'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Top spacing:'),
                    'name' => 'top_spacing',
                    'default_value' => '',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Leave it empty to use the default value.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Bottom spacing:'),
                    'name' => 'bottom_spacing',
                    'default_value' => '',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Leave it empty to use the default value.'),
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Display "next" and "prev" buttons:'),
                    'name' => 'prev_next',
                    'is_bool' => true,
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'none',
                            'value' => 0,
                            'label' => $this->l('None')),
                        array(
                            'id' => 'left-right',
                            'value' => 1,
                            'label' => $this->l('Full height')),
                        array(
                            'id' => 'rectangle',
                            'value' => 2,
                            'label' => $this->l('Rectangle')),
                        array(
                            'id' => 'circle',
                            'value' => 3,
                            'label' => $this->l('Circle')),
                        array(
                            'id' => 'square',
                            'value' => 4,
                            'label' => $this->l('Square')),
                    ),
                ),
                array(
					'type' => 'color',
					'label' => $this->l('Prev/next buttons color:'),
					'name' => 'prev_next_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				array(
					'type' => 'color',
					'label' => $this->l('Prev/next buttons hover color:'),
					'name' => 'prev_next_hover',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ), 
                array(
					'type' => 'color',
					'label' => $this->l('Prev/next buttons background color:'),
					'name' => 'prev_next_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				/*array(
					'type' => 'color',
					'label' => $this->l('Prev/next buttons background hover color:'),
					'name' => 'prev_next_bg_hover',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),*/
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show navigation:'),
                    'name' => 'pag_nav',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'pag_nav_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'pag_nav_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
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
					'name' => 'pag_nav_bg_active',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Autoplay:'),
                    'name' => 'auto_advance',
                    'is_bool' => true,
                    'default_value' => 1,
                    'values' => array(
                        array(
                            'id' => 'auto_advance_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'auto_advance_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'desc' => $this->l('Automatically play animation.'),
                    'class' => 'fixed-width-sm'
                ), 
                array(
					'type' => 'text',
					'label' => $this->l('Time:'),
					'name' => 'time',
                    'default_value' => 7000,
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
                    'required'  => true,
                    'class' => 'fixed-width-sm' 
				),
                array(
					'type' => 'text',
					'label' => $this->l('Transition period:'),
					'name' => 'trans_period',
                    'default_value' => 400,
                    'desc' => $this->l('The period, in milliseconds, of the transition effect.'),
                    'required'  => true,
                    'class' => 'fixed-width-sm' 
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Pause On Hover:'),
                    'name' => 'pause',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'pause_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'pause_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Progress bar:'),
                    'name' => 'progress_bar',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'none',
                            'value' => 0,
                            'label' => $this->l('None')),
                        array(
                            'id' => 'top',
                            'value' => 1,
                            'label' => $this->l('Top')),
                        array(
                            'id' => 'bottom',
                            'value' => 2,
                            'label' => $this->l('Bottom')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
					'type' => 'color',
					'label' => $this->l('Progress bar color:'),
					'name' => 'prog_bar_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				array(
					'type' => 'color',
					'label' => $this->l('Progress bar background color:'),
					'name' => 'prog_bar_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Mouse drag:'),
                    'name' => 'mouse_drag',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'mouse_drag_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'mouse_drag_off',
                            'value' => 0,
                            'label' => $this->l('No')),
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
        
        if($slide_group->id)
        {
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_owl_carousel_group');
        }
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
        /** mutishop begin **/
        $helper->id = (int)$slide_group->id;
		$helper->table =  'st_owl_carousel_group';
		$helper->identifier = 'id_st_owl_carousel_group';
        /** mutishop end **/
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->submit_action = 'savestowlcarouselgroup';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($slide_group),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
        
        if($slide_group->id)
        {
            $type  = self::getType(get_object_vars($slide_group));
            if($type && isset(self::$_type[$type]))
            {
                $field = self::$_type[$type];
                $helper->tpl_vars['fields_value']['location'] = $type.'-'.$slide_group->$field;
            }
        }
        
		return $helper;
	}
    
    public function BuildRadioUI($name, $checked_value = 0)
    {
        $html = '';
        foreach(self::$templates AS $key => $value)
        {
            $html .= '<label><input type="radio"'.($checked_value==$key ? ' checked="checked"' : '').' value="'.$key.'" id="'.$name.'_'.$key.'" name="'.$name.'">'.$key.'<img src="'.$this->_path.'views/img/'.$key.'.jpg" />'.'</label>';
            if (($key+1) % 6 == 0)
                $html .= '<br />';
        }
        return $html;
    }

    public function BuildDropListGroup($display, $slide_group)
    {
        $group = array(
            array(
                'id' => 'items_huge',
                'label' => $this->l('Extremely large devices'),
                'tooltip' => $this->l('Desktops (>1900px)'),
            ),
            array(
                'id' => 'items_xxlg',
                'label' => $this->l('Extremely large devices'),
                'tooltip' => $this->l('Desktops (>1600px)'),
            ),
            array(
                'id' => 'items_xlg',
                'label' => $this->l('Extra large devices'),
                'tooltip' => $this->l('Desktops (>1400px)'),
            ),
            array(
                'id' => 'items_lg',
                'label' => $this->l('Large devices'),
                'tooltip' => $this->l('Desktops (>1200px)'),
            ),
            array(
                'id' => 'items_md',
                'label' => $this->l('Medium devices'),
                'tooltip' => $this->l('Desktops (>992px)'),
            ),
            array(
                'id' => 'items_sm',
                'label' => $this->l('Small devices'),
                'tooltip' => $this->l('Tablets (>768px)'),
            ),
            array(
                'id' => 'items_xs',
                'label' => $this->l('Extra small devices'),
                'tooltip' => $this->l('Phones (>480px)'),
            ),
            array(
                'id' => 'items_xxs',
                'label' => $this->l('Extremely small devices'),
                'tooltip' => $this->l('Phones (<480px)'),
            ),
        );

        $html = '<div id="items_box" '.($display ? '' : 'style="display:none;"').'>';
        $html .= '<div class="row">';
        foreach($group AS $key => $k)
        {
             if($key%3==0)
                 $html .= '</div><div class="row">';

             $html .= '<div class="col-xs-4 col-sm-3"><label '.(isset($k['tooltip']) ? ' data-html="true" data-toggle="tooltip" class="label-tooltip" data-original-title="'.$k['tooltip'].'" ':'').'>'.$k['label'].'</label>'.
             '<select name="'.$k['id'].'" 
             id="'.$k['id'].'" 
             class="'.(isset($k['class']) ? $k['class'] : 'fixed-width-md').'"'.
             (isset($k['onchange']) ? ' onchange="'.$k['onchange'].'"':'').' >';
            
            for ($i=1; $i < 13; $i++){
                $html .= '<option value="'.$i.'" '.($slide_group->$k['id'] == $i ? ' selected="selected"':'').'>'.$i.'</option>';
            }
                                
            $html .= '</select></div>';
        }
        return $html.'</div></div>';
    }
	protected function initFormSlide($isbanner=0)
	{
        $id_st_owl_carousel = (int)Tools::getValue('id_st_owl_carousel');
        $id_st_owl_carousel_group = (int)Tools::getValue('id_st_owl_carousel_group');
		$slide = new StOwlCarouselClass($id_st_owl_carousel);

        $google_font_name_html = $google_font_name =  $google_font_link = '';
        if(Validate::isLoadedObject($slide)){
            $jon_arr = StOwlCarouselFontClass::getBySlider($slide->id);
            if(is_array($jon_arr) && count($jon_arr))
                foreach ($jon_arr as $key => $value) {
                    $google_font_name_html .= '<li id="#'.str_replace(' ', '_', strtolower($value['font_name'])).'_li" class="form-control-static"><button type="button" class="delGoogleFont btn btn-default" name="'.$value['font_name'].'"><i class="icon-remove text-danger"></i></button>&nbsp;<span style="'.$this->fontstyles($value['font_name']).'\'">style="'.$this->fontstyles($value['font_name']).'"</span></li>';

                    $google_font_name .= $value['font_name'].'¤';

                    $google_font_link .= '<link id="'.str_replace(' ', '_', strtolower($value['font_name'])).'_link" rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='.str_replace(' ', '+', $value['font_name']).'" />';
                }
        }

		$this->fields_form_slide[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Item'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'select',
        			'label' => $this->l('Slideshow:'),
        			'name' => 'id_st_owl_carousel_group',
                    'required'  => true,
                    'options' => array(
        				'query' => StOwlCarouselGroup::getAll(),
        				'id' => 'id_st_owl_carousel_group',
        				'name' => 'name',
						'default' => array(
							'value' => 0,
							'label' => $this->l('Please select')
						)
        			)
				),
                array(
					'type' => 'text',
					'label' => $this->l('Title:'),
					'name' => 'title',
                    'size' => 64,
                    'lang' => true,
				),
                array(
					'type' => 'text',
					'label' => $this->l('Link:'),
					'name' => 'url',
                    'size' => 64,
                    'lang' => true,
                    'desc' => '<strong>'.$this->l('If this field is filled in, whole image will become clickable. You can not put any other hyperlinks or buttons into caption, otherwise, unexpected errors will happen.').'</strong>',
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Open in a new window:'),
					'name' => 'new_window',
					'is_bool' => true,
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'new_window_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'new_window_off',
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
					'name' => 'isbanner',
                    'default_value' => 0,
                    'validation' => 'isBool',                   
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
        //if(!$isbanner)
		$this->fields_form_slide[1]['form'] = array(
			'legend' => array(
				'title' => $this->l('More options'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Caption:'),
                    'lang' => true,
                    'name' => 'description',
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
                    </ul>
                    <div class="alert alert-info"><a href="javascript:;" onclick="$(\'#how_to_use_gf\').toggle();return false;">'.$this->l('How to use google fonts? Click here.').'</a>'.
                        '<div id="how_to_use_gf" style="display:none;"><img src="'.$this->_path.'views/img/how_to_use_gf.jpg" /></div></div>',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Google fonts:'),
                    'name' => 'google_font_select',
                    'onchange' => 'handle_font_change(this);',
                    'class' => 'fontOptions',
                    'options' => array(
                        'query' => $this->fontOptions(),
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default'),
                        ),
                    ),
                ),
                'font_text'=>array(
                    'type' => 'select',
                    'label' => $this->l('Font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'google_font_weight',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                    'desc' => '<p>'.$this->l('Once a font has been added, you can use it everywhere without adding it again.').'</p><a id="add_google_font" class="btn btn-default btn-block fixed-width-md" href="javascript:;">Add</a><br/><p id="google_font_example" class="fontshow">Example Title</p><ul id="curr_google_font_name">'.$google_font_name_html.'</ul>'.$google_font_link,
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'google_font_name',
                    'default_value' => '',
                ),
                /*'text_animation' => array(
                    'type' => 'select',
                    'label' => $this->l('Animation:'),
                    'name' => 'text_animation',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->text_animation_group,
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 13,
                            'label' => self::$text_animation[13],
                        ),
                    ),
                    'desc' => 'Animate.css - http://daneden.me/animate',
                ),*/
                array(
                    'type' => 'select',
                    'label' => $this->l('Position:'),
                    'name' => 'text_position',
                    'options' => array(
                        'query' => self::$text_position,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide caption on mobile:'),
                    'name' => 'hide_text_on_mobile',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'hide_text_on_mobile_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'hide_text_on_mobile_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'desc' => $this->l('screen width < 768px.'),
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
                    'type' => 'select',
                    'label' => $this->l('Caption width:'),
                    'name' => 'text_width',
                    'options' => array(
                        'query' => array(
                                array('id' => 1, 'name'=>'Center10%'),
                                array('id' => 2, 'name'=>'Center20%'),
                                array('id' => 3, 'name'=>'Center30%'),
                                array('id' => 4, 'name'=>'Center40%'),
                                array('id' => 5, 'name'=>'Center50%'),
                                array('id' => 6, 'name'=>'Center60%'),
                                array('id' => 7, 'name'=>'Center70%'),
                                array('id' => 8, 'name'=>'Center80%'),
                                array('id' => 9, 'name'=>'Center90%'),
                                array('id' => 11, 'name'=>'Left10%'),
                                array('id' => 12, 'name'=>'Left20%'),
                                array('id' => 13, 'name'=>'Left30%'),
                                array('id' => 14, 'name'=>'Left40%'),
                                array('id' => 15, 'name'=>'Left50%'),
                                array('id' => 16, 'name'=>'Left60%'),
                                array('id' => 17, 'name'=>'Left70%'),
                                array('id' => 18, 'name'=>'Left80%'),
                                array('id' => 19, 'name'=>'Left90%'),
                                array('id' => 21, 'name'=>'Right10%'),
                                array('id' => 22, 'name'=>'Right20%'),
                                array('id' => 23, 'name'=>'Right30%'),
                                array('id' => 24, 'name'=>'Right40%'),
                                array('id' => 25, 'name'=>'Right50%'),
                                array('id' => 26, 'name'=>'Right60%'),
                                array('id' => 27, 'name'=>'Right70%'),
                                array('id' => 28, 'name'=>'Right80%'),
                                array('id' => 29, 'name'=>'Right90%'),
                            ),
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => '0',
                            'label' => $this->l('100%')
                        )
                    ),
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Caption color:'),
                    'name' => 'text_color',
                    'size' => 33,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Caption background:'),
                    'name' => 'text_bg',
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
					'type' => 'html',
                    'id' => 'a_cancel',
					'label' => '',
					'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&id_st_owl_carousel_group='.$slide->id_st_owl_carousel_group.'&viewstowlcarousel&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
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
                    
        $languages = Language::getLanguages(true);
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		foreach ($languages as $lang)
        {
            $this->fields_form_slide[0]['form']['input']['image_multi_lang_'.$lang['id_lang']] = array(
                    'type' => 'file',
					'label' => $this->l('Image').' - '.$lang['name'].($default_lang == $lang['id_lang'] ? '('.$this->l('default language').')' : '').':',
					'name' => 'image_multi_lang_'.$lang['id_lang'],
                    'required'  => ($default_lang == $lang['id_lang']),
                    'desc' => $this->l('Please ensure the image name is unique, or it will override the same name files.').'<br/>',
                );
        }
        $this->fields_form_slide[0]['form']['input'][] = array(
			'type' => 'html',
            'id' => 'a_cancel',
			'label' => '',
			'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&id_st_owl_carousel_group='.$slide->id_st_owl_carousel_group.'&viewstowlcarousel&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
		);
        if(Validate::isLoadedObject($slide))
        {
            $this->fields_form_slide[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_owl_carousel');
            foreach ($languages as $lang)
                if($slide->image_multi_lang[$lang['id_lang']])
                {
                    StOwlCarouselClass::fetchMediaServer($slide->thumb_multi_lang[$lang['id_lang']]);
                    $this->fields_form_slide[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'has_image_'.$lang['id_lang'], 'default_value'=>1);
                    $this->fields_form_slide[0]['form']['input']['image_multi_lang_'.$lang['id_lang']]['required'] = false;
                    $this->fields_form_slide[0]['form']['input']['image_multi_lang_'.$lang['id_lang']]['desc'] .= '<img src="'.$slide->thumb_multi_lang[$lang['id_lang']].'"/>';
                }
        }
        elseif($id_st_owl_carousel_group)
            $slide->id_st_owl_carousel_group = $id_st_owl_carousel_group;
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
        $helper->module = $this;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestowlcarousel';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($slide,"fields_form_slide"),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
        $helper->tpl_vars['fields_value']['isbanner'] = ($isbanner || (Validate::isLoadedObject($slide) && $slide->isbanner))? 1 : 0;
        $helper->tpl_vars['fields_value']['google_font_name'] = $google_font_name;
		
		return $helper;
	}

    public function fontOptions() {
        $google = array();
        foreach($this->googleFonts as $v)
            $google[] = array('id'=>$v['family'],'name'=>$v['family']);
        return $google;
    }
    
    public function fontstyles($font_name = null)
    {
        $style = '';
        if (!$font_name)
            return $style;
        
        $name = $variant = '';
        if (strpos($font_name, ':') !== false)
            list($name, $variant) = explode(':', $font_name);
        else
            $name = $font_name;
        
        $style .= 'font-family:\''.$name.'\';';
        
        if ($variant == 'regular')
            $style .= 'font-weight:400;';
        elseif ($variant)
        {
            if (preg_match('/(\d+)/iS', $variant, $math))
            {
                if (!isset($math[1]))
                    $math[1] = '400';
                $style .= 'font-weight:'.$math[1].';';
            }
            if (preg_match('/([^\d]+)/iS', $variant, $math))
            {
                if (!isset($math[1]))
                    $math[1] = 'normal';
                $style .= 'font-style:'.$math[1].';';
            }
        }
        return $style;
    }
    
    public static function showApplyTo($value,$row)
    {
	    if($value)
		   $result = isset(self::$location[$value]) ? self::$location[$value]['name'] : '';
        elseif($row['id_category'])
        {
            $category = new Category($row['id_category'],(int)Context::getContext()->language->id);
            if($category->id)
            {
                $module = new StOwlCarousel();
                $result = $category->name.'('.$module->l('Category').')';
            }
        }
        elseif($row['id_manufacturer'])
        {
            $id_lang = (int)Context::getContext()->language->id;
            $manufacturer = Manufacturer::getNameById((int)$row['id_manufacturer']);
    		$result = (string)$manufacturer;
        }
        elseif($row['id_cms'])
        {
            $cms = new CMS((int)$row['id_cms'], (int)Context::getContext()->language->id);
            if ($cms->id)
            {
                $module = new StOwlCarousel();
                $result = $cms->meta_title.'('.$module->l('CMS').')';
            }
        }
        else
        {
            $module = new StOwlCarousel();
            $result = $module->l('--');
        }
        return $result;
    }
	protected function initList()
	{
		$this->fields_list = array(
			'id_st_owl_carousel_group' => array(
				'title' => $this->l('Id'),
				'width' => 120,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'name' => array(
				'title' => $this->l('Name'),
				'width' => 200,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'location' => array(
				'title' => $this->l('Hook into'),
				'width' => 200,
				'type' => 'text',
				'callback' => 'showApplyTo',
				'callback_object' => 'StOwlCarousel',
                'search' => false,
                'orderby' => false
			),
            'position' => array(
                'title' => $this->l('Position'),
                'align' => 'center',
                'class'=>'fixed-width-xl',
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'active' => array(
				'title' => $this->l('Status'),
				'align' => 'center',
				'active' => 'groupstatus',
				'type' => 'bool',
				'width' => 25,
                'search' => false,
                'orderby' => false
            ),
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
        $helper->module = $this;
		$helper->identifier = 'id_st_owl_carousel_group';
		$helper->actions = array('view', 'edit', 'delete','duplicate');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstowlcarouselgroup&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add a slideshow'),
		);

		$helper->title = $this->displayName;
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    public function displayDuplicateLink($token, $id, $name)
    {
        return '<li class="divider"></li><li><a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&copy'.$this->name.'&id_st_owl_carousel_group='.(int)$id.'&token='.$token.'"><i class="icon-copy"></i>'.$this->l(' Duplicate ').'</a></li>';
    }
    public static function showSlideGroupName($value,$row)
    {
        $slide_group = new StOwlCarouselGroup((int)$value);
        return $slide_group->id ? $slide_group->name : '-';
    }
    public static function showSlideImage($value,$row)
    {
        return '<img src="'.$value.'" />';
    }
	protected function initListSlide()
	{
		$this->fields_list = array(
			'id_st_owl_carousel' => array(
				'title' => $this->l('Id'),
				'width' => 120,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'id_st_owl_carousel_group' => array(
				'title' => $this->l('Group name'),
				'width' => 120,
				'type' => 'text',
				'callback' => 'showSlideGroupName',
				'callback_object' => 'StOwlCarousel',
                'search' => false,
                'orderby' => false
			),
            'thumb_multi_lang' => array(
				'title' => $this->l('Image'),
				'type' => 'text',
				'callback' => 'showSlideImage',
				'callback_object' => 'StOwlCarousel',
                'width' => 300,
                'search' => false,
                'orderby' => false
            ),
            'position' => array(
				'title' => $this->l('Position'),
				'width' => 40,
				'position' => 'position',
				'align' => 'center',
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
				'title' => $this->l('Status'),
				'align' => 'center',
				'active' => 'slidestatus',
				'type' => 'bool',
				'width' => 25,
                'search' => false,
                'orderby' => false
            ),
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_st_owl_carousel';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstowlcarousel&id_st_owl_carousel_group='.(int)Tools::getValue('id_st_owl_carousel_group').'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add an item')
		);
        $helper->toolbar_btn['edit'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&update'.$this->name.'&id_st_owl_carousel_group='.(int)Tools::getValue('id_st_owl_carousel_group').'&fr=view&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Edit group'),
		);
		$helper->toolbar_btn['back'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Back to list')
		);

		$helper->title = $this->l('Slides');
		$helper->table = $this->name;
		$helper->orderBy = 'position';
		$helper->orderWay = 'ASC';
	    $helper->position_identifier = 'id_st_owl_carousel';
        
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
	protected function initListBanner()
	{
		$this->fields_list_banner = array(
			'id_st_owl_carousel' => array(
				'title' => $this->l('Id'),
				'width' => 120,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'id_st_owl_carousel_group' => array(
				'title' => $this->l('Group'),
				'width' => 120,
				'type' => 'text',
				'callback' => 'showSlideGroupName',
				'callback_object' => 'StOwlCarousel',
                'search' => false,
                'orderby' => false
			),
            'thumb_multi_lang' => array(
				'title' => $this->l('Image'),
				'type' => 'text',
				'callback' => 'showSlideImage',
				'callback_object' => 'StOwlCarousel',
                'width' => 300,
                'search' => false,
                'orderby' => false
            ),
            'position' => array(
				'title' => $this->l('Position'),
				'width' => 40,
				'position' => 'left',
				'align' => 'left',
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
				'title' => $this->l('Status'),
				'align' => 'center',
				'active' => 'slidestatus',
				'type' => 'bool',
				'width' => 25,
                'search' => false,
                'orderby' => false
            ),
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_st_owl_carousel';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstowlcarouselbanner&id_st_owl_carousel_group='.(int)Tools::getValue('id_st_owl_carousel_group').'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add a banner')
		);
		$helper->toolbar_btn['back'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Back to list')
		);

		$helper->title = $this->l('Banners');
		$helper->table = $this->name;
		$helper->orderBy = 'position';
		$helper->orderWay = 'ASC';
	    $helper->position_identifier = 'id_st_owl_carousel';
        
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    private function _prepareHook($identify,$type=1)
    {        
        $slide_group = StOwlCarouselGroup::getSlideGroup($identify,$type);
        if(!is_array($slide_group) || !count($slide_group))
            return false;
        foreach($slide_group as &$v)
        {
            $v['is_full_width'] = ($type==1 &&  isset(self::$location[$v['location']]['full_width'])) ? true : false;
            $slide = StOwlCarouselClass::getAll($v['id_st_owl_carousel_group'],$this->context->language->id,1,0);
            if(is_array($slide) && $slide_nbr=count($slide))
            {
               $v['slide'] = $slide;
            }
            if(isset(self::$templates[$v['templates']]['banners']) && self::$templates[$v['templates']]['banners'])
            {
               $banners = StOwlCarouselClass::getAll($v['id_st_owl_carousel_group'],$this->context->language->id,1,1);
               if(is_array($banners) && count($banners))
               {
                   $v['banners'] = $banners;
               }
            }
        }
        
	    $this->smarty->assign(array(
            'slide_group' => $slide_group,
            'transition_style' => self::$transition_style,
        ));
        return true;
    }
    public function hookDisplayHeader($params)
    {
        /*$data = StOwlCarouselFontClass::getAll(1);
        if(is_array($data) && count($data))
        {
            $slide_font = array();
            foreach ($data as $value) {
                $slide_font[] = $value['font_name'];
            }

            $slide_font = array_unique($slide_font); 
            $font_latin_support = Configuration::get('STSN_FONT_LATIN_SUPPORT');
            $font_cyrillic_support = Configuration::get('STSN_FONT_CYRILLIC_SUPPORT');
            $font_vietnamese = Configuration::get('STSN_FONT_VIETNAMESE');
            $font_greek_support = Configuration::get('STSN_FONT_GREEK_SUPPORT');
            $font_arabic_support = Configuration::get('STSN_FONT_ARABIC_SUPPORT');
            $font_support = ($font_latin_support || $font_cyrillic_support || $font_vietnamese || $font_greek_support || $font_arabic_support) ? '&subset=' : '';
            $font_latin_support && $font_support .= 'latin,latin-ext,';
            $font_cyrillic_support && $font_support .= 'cyrillic,cyrillic-ext,';
            $font_vietnamese && $font_support .= 'vietnamese,';
            $font_greek_support && $font_support .= 'greek,greek-ext,';
            $font_arabic_support && $font_support .= 'arabic,';
            if(is_array($slide_font) && count($slide_font))
                foreach($slide_font as $x)
                {
                    if(!$x)
                        continue;
                    $this->context->controller->addCSS($this->context->link->protocol_content."fonts.googleapis.com/css?family=".str_replace(' ', '+', $x).($font_support ? rtrim($font_support,',') : ''));
                }
        }*/    

        if (!$this->isCached('header.tpl', $this->getCacheId()))
        {
            $custom_css = '';
            $custom_css_arr = StOwlCarouselClass::getOptions();
            if (is_array($custom_css_arr) && count($custom_css_arr)) {
                foreach ($custom_css_arr as $v) {
                    $classname = '.st_owl_carousel_block_'.$v['id_st_owl_carousel'].' ';
                    $v['text_color'] && $custom_css .= $classname.'.style_content,
                    '.$classname.'.style_content a{color:'.$v['text_color'].';}
                    '.$classname.'.icon_line:after, '.$classname.'.icon_line:before{background-color:'.$v['text_color'].';}
                    '.$classname.'.line, '.$classname.'.btn{border-color:'.$v['text_color'].';}';
                    if($v['text_bg'])
                        $custom_css .= '#st_owl_carousel_block_'.$v['id_st_owl_carousel'].' .style_content h1, #st_owl_carousel_block_'.$v['id_st_owl_carousel'].' .style_content h2, #st_owl_carousel_block_'.$v['id_st_owl_carousel'].' .style_content h3, #st_owl_carousel_block_'.$v['id_st_owl_carousel'].' .style_content h4, #st_owl_carousel_block_'.$v['id_st_owl_carousel'].' .style_content h5, #st_owl_carousel_block_'.$v['id_st_owl_carousel'].' .style_content h6, #st_owl_carousel_block_'.$v['id_st_owl_carousel'].' .style_content p{padding: 6px 0.3em 4px;line-height:120%;background-color:'.$v['text_bg'].';background-color:rgba('.self::hex2rgb($v['text_bg']).',0.4);}#st_owl_carousel_block_'.$v['id_st_owl_carousel'].' .style_content p{padding: 6px 0.6em;}';
                    if($v['btn_color'])
                        $custom_css .= $classname.'.style_content .btn{color:'.$v['btn_color'].';}';
                    if($v['btn_color'] && !$v['btn_bg'])
                        $custom_css .= $classname.'.style_content .btn{border-color:'.$v['btn_color'].';}';
                    if($v['btn_bg'])
                        $custom_css .= $classname.'.style_content .btn{background-color:'.$v['btn_bg'].';border-color:'.$v['btn_bg'].';}';
                    if($v['btn_hover_color'])
                        $custom_css .= $classname.'.style_content .btn:hover{color:'.$v['btn_hover_color'].';}';
                    if ($v['btn_hover_bg'])
                        $custom_css .= $classname.'.style_content .btn:hover{background-color: '.$v['btn_hover_bg'].';}';
                }
            } 

            $custom_css_arr = StOwlCarouselGroup::getOptions();
            if (is_array($custom_css_arr) && count($custom_css_arr)) {
                foreach ($custom_css_arr as $v) {
                    $v['prog_bar_color'] && $custom_css .= '#st_owl_carousel-'.$v['id_st_owl_carousel_group'].' .owl_bar{background-color:'.$v['prog_bar_color'].';}';
                    $v['prog_bar_bg'] && $custom_css .= '#st_owl_carousel-'.$v['id_st_owl_carousel_group'].' .owl_progressBar{background-color:'.$v['prog_bar_bg'].';}';
                    $v['pag_nav_bg'] && $custom_css .= '#st_owl_carousel-'.$v['id_st_owl_carousel_group'].'.owl-theme .owl-controls .owl-page span{background-color:'.$v['pag_nav_bg'].';}';
                    $v['pag_nav_bg_active'] && $custom_css .= '#st_owl_carousel-'.$v['id_st_owl_carousel_group'].'.owl-theme .owl-controls .owl-page.active span{background-color:'.$v['pag_nav_bg_active'].';}';
                    $v['prev_next_color'] && $custom_css .= '#st_owl_carousel-'.$v['id_st_owl_carousel_group'].'.owl-theme.owl-navigation-lr .owl-controls .owl-buttons div{color:'.$v['prev_next_color'].';}';
                    $v['prev_next_hover'] && $custom_css .= '#st_owl_carousel-'.$v['id_st_owl_carousel_group'].'.owl-theme.owl-navigation-lr .owl-controls .owl-buttons div:hover{color:'.$v['prev_next_hover'].';}';
                    if($v['prev_next_bg'])
                        $custom_css .= '#st_owl_carousel-'.$v['id_st_owl_carousel_group'].'.owl-theme.owl-navigation-lr .owl-controls .owl-buttons div{background-color:'.$v['prev_next_bg'].';}';
                    if($v['prev_next_bg'])
                    {
                        $prev_next_bg = self::hex2rgb($v['prev_next_bg'] );
                        $custom_css .= '#st_owl_carousel-'.$v['id_st_owl_carousel_group'].'.owl-theme.owl-navigation-lr .owl-controls .owl-buttons div{background-color:rgba('.$prev_next_bg.',0.4);}#st_owl_carousel-'.$v['id_st_owl_carousel_group'].'.owl-theme.owl-navigation-lr .owl-controls .owl-buttons div:hover{background-color:rgba('.$prev_next_bg.',0.8);}';
                    }
                    /*
                    if($v['prev_next_bg_hover'])
                        $custom_css .= '#st_owl_carousel-'.$v['id_st_owl_carousel_group'].'.owl-theme.owl-navigation-lr .owl-controls .owl-buttons div:hover{background-color:'.$v['prev_next_bg_hover'].';}';
                    */
                    $classname = (isset(self::$location[$v['location']]['full_width']) ? '#owl_carousel_container_'.$v['id_st_owl_carousel_group'].' ' : '#st_owl_carousel_'.$v['id_st_owl_carousel_group']);
                    if(isset($v['top_spacing']) && ($v['top_spacing'] || $v['top_spacing']==='0'))
                        $custom_css .= $classname.'{margin-top:'.(int)$v['top_spacing'].'px;}';
                    if(isset($v['bottom_spacing']) && ($v['bottom_spacing'] || $v['bottom_spacing']==='0'))
                        $custom_css .= $classname.'{margin-bottom:'.(int)$v['bottom_spacing'].'px;}';
                    if(isset($v['slider_spacing']) && ($v['slider_spacing'] || $v['slider_spacing']===0))
                        $custom_css .= $classname.'.st_owl_carousel_3 .owl-carousel .owl-item{padding-right:'.(int)$v['slider_spacing'].'px;padding-left:'.(int)$v['slider_spacing'].'px;}';
                }
            }                 
            $this->smarty->assign('custom_css', preg_replace('/\s\s+/', ' ', $custom_css));
        }
        return $this->display(__FILE__, 'header.tpl', $this->getCacheId());
    }
	public function hookDisplayLeftColumn($params)
	{
		if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(2)))
            if(!$this->_prepareHook(2,1))
                return false;
		return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(2));
	}
	public function hookDisplayRightColumn($params)
	{
		if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(5)))
            if(!$this->_prepareHook(5,1))
                return false;
		return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(5));
	}
 
    public function hookDisplayHome($params)
    {
        if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(3)))
            if(!$this->_prepareHook(3,1))
                return false;
        return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(3));
    }
    
    public function hookDisplayHomeTop($params)
    {
        if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(17)))
            if(!$this->_prepareHook(17,1))
                return false;
        return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(17));
    }

    public function hookDisplayHomeBottom($params)
    {
        if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(18)))
            if(!$this->_prepareHook(18,1))
                return false;
        return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(18));
    }

    public function hookDisplayHomeTertiaryLeft($params)
    {
        if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(23)))
            if(!$this->_prepareHook(23,1))
                return false;
        return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(23));
    }

    public function hookDisplayHomeTertiaryRight($params)
    {
        if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(24)))
            if(!$this->_prepareHook(24,1))
                return false;
        return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(24));
    }
    public function hookDisplayTopColumn($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        if(!$this->_prepareHook(19,1))
                return false;
        return $this->display(__FILE__, 'stowlcarousel.tpl');
    }
    public function hookDisplayBottomColumn($params)
    {
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        if(!$this->_prepareHook(20,1))
                return false;
        return $this->display(__FILE__, 'stowlcarousel.tpl');
    }
    public function hookDisplayFullWidthTop($params)
    {
        $page_name = Context::getContext()->smarty->getTemplateVars('page_name');
        if($page_name=='index')
        {
            if(!$this->_prepareHook(21,1))
                    return false;
            return $this->display(__FILE__, 'stowlcarousel.tpl');
        }
        elseif($page_name=='module-stblog-default')
        {
            if(!$this->_prepareHook(array(7,8),1))
                    return false;
            return $this->display(__FILE__, 'stowlcarousel.tpl');
        }
        return false;
    }
    public function hookDisplayFullWidthTop2($params)
    {
        $page_name = Context::getContext()->smarty->getTemplateVars('page_name');
        if($page_name=='index')
        {
            if(!$this->_prepareHook(26,1))
                    return false;
            return $this->display(__FILE__, 'stowlcarousel.tpl');
        }
        return false;
    }
	public function hookDisplayHomeVeryBottom($params)
	{
        if(Dispatcher::getInstance()->getController()!='index')
            return false;

        if(!$this->_prepareHook(22,1))
                return false;
        return $this->display(__FILE__, 'stowlcarousel.tpl');
	}
    
	public function hookDisplayStBlogHome($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(6)))
            if(!$this->_prepareHook(6,1))
                return false;
		return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(6));
	}
	public function hookDisplayStBlogLeftColumn($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(9)))
            if(!$this->_prepareHook(9,1))
                return false;
		return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(9));
	}
	public function hookDisplayStBlogRightColumn($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(10)))
            if(!$this->_prepareHook(10,1))
                return false;
		return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(10));
	}
    	
    public function hookDisplayCategoryHeader($params)
    {
        $id_category = (int)Tools::getValue('id_category');
        if(!$id_category)
            return false;
		//if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId($id_category,'category-header','stowlcarousel')))
            if(!$this->_prepareHook($id_category,2))
                return false;
            return $this->display(__FILE__, 'stowlcarousel.tpl');
		//return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId($id_category,'category-header','stowlcarousel'));
    }
    public function hookDisplayManufacturerHeader($params)
    {
        $id_manufacturer = (int)Tools::getValue('id_manufacturer');
        if(!$id_manufacturer)
            return false;
		if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId($id_manufacturer,'manufacturer')))
            if(!$this->_prepareHook($id_manufacturer,6))
                return false;
		return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId($id_manufacturer,'manufacturer'));
    }
    public function hookDisplayCategoryFooter($params)
    {
        if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(12)))
            if(!$this->_prepareHook(12,1))
                return false;
		return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(12));
    }
    
    public function hookDisplayFooterProduct($params)
    {
        if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(11)))
            if(!$this->_prepareHook(11,1))
                return false;
		return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(11));
    }
    
    public function hookDisplayHomeSecondaryLeft($params)
    {
        if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(25)))
            if(!$this->_prepareHook(25,1))
                return false;
        return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(25));
    }
    
    public function hookDisplayHomeSecondaryRight($params)
	{
		if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(15)))
            if(!$this->_prepareHook(15,1))
                return false;
		return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(15));
	}
    
    public function hookDisplayProductSecondaryColumn($params)
    {
		if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId(16)))
            if(!$this->_prepareHook(16,1))
                return false;
		return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId(16));
    }
    
	public function hookDisplayAnywhere($params)
	{
	    if(!isset($params['caller']) || $params['caller']!=$this->name)
            return false;
	   if(isset($params['function']) && method_exists($this,$params['function']))
        {
            if($params['function']=='displayBySlideId')
                return call_user_func_array(array($this,$params['function']),array($params['identify']));
            elseif($params['function']=='displayCmsMainSlide')
                return call_user_func(array($this,$params['function']),array($params['identify']));
            elseif($params['function']=='displayCmsCategoryMainSlide')
                return call_user_func(array($this,$params['function']),array($params['identify']));
            else
                return false;
        }
        return false;
    }
    public function displayCmsMainSlide($identify)
    {
        if(!$identify || !$this->_prepareHook($identify, 4))
                return false;
		return $this->display(__FILE__, 'stowlcarousel.tpl');
    }
    public function displayCmsCategoryMainSlide($identify)
    {
        if(!$identify || !$this->_prepareHook($identify, 5))
                return false;
		return $this->display(__FILE__, 'stowlcarousel.tpl');
    }
    public function displayBySlideId($identify)
    {
        if(!Validate::isUnsignedInt($identify))
            return false;
            
        $slide_group_obj = new StOwlCarouselGroup($identify);
        if(!$slide_group_obj->id || !$slide_group_obj->active)
            return false;
		if (!$this->isCached('stowlcarousel.tpl', $this->stGetCacheId($slide_group_obj->id,'id')))
            if(!$this->_prepareHook($identify,3))
                return false;
		return $this->display(__FILE__, 'stowlcarousel.tpl', $this->stGetCacheId($slide_group_obj->id,'id'));
    }
    public function hookActionObjectCategoryDeleteAfter($params)
    {
        if(!$params['object']->id)
            return ;
        
        $slide_group = StOwlCarouselGroup::getSlideGroup($params['object']->id,2);
        if(!is_array($slide_group) || !count($slide_group))
            return ;
        $res = true;
        foreach($slide_group as $v)
        {
            $slide_group = new StOwlCarouselGroup($v['id_st_owl_carousel_group']);
            $res &= $slide_group->delete();
        }
        
        return $res;
    }
    public function hookActionObjectManufacturerDeleteAfter($params)
    {
        if(!$params['object']->id)
            return ;
        
        $slide_group = StOwlCarouselGroup::getSlideGroup($params['object']->id,6);
        if(!is_array($slide_group) || !count($slide_group))
            return ;
        $res = true;
        foreach($slide_group as $v)
        {
            $slide_group = new StOwlCarouselGroup($v['id_st_owl_carousel_group']);
            $res &= $slide_group->delete();
        }
        
        return $res;
    }
	public function hookActionShopDataDuplication($params)
	{
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'st_owl_carousel_group_shop (id_st_owl_carousel_group, id_shop)
		SELECT id_st_owl_carousel_group, '.(int)$params['new_id_shop'].'
		FROM '._DB_PREFIX_.'st_owl_carousel_group_shop
		WHERE id_shop = '.(int)$params['old_id_shop']);
        $this->clearOwlCarouselCache();
    }
	protected function stGetCacheId($key,$type='location',$name = null)
	{
		$cache_id = parent::getCacheId($name);
		return $cache_id.'_'.$key.'_'.$type;
	}
	private function clearOwlCarouselCache()
	{
        $this->_clearCache('*');
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
       return implode(",", $rgb); // returns the rgb values separated by commas
       //return $rgb;
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
    
    public function processCopyOwlCarousel($id_st_owl_carousel_group = 0)
    {
        if (!$id_st_owl_carousel_group)
            return false;
            
        $group = new StOwlCarouselGroup($id_st_owl_carousel_group);
        
        $group2 = clone $group;
        $group2->id = 0;
        $group2->id_st_owl_carousel_group = 0;
        $ret = $group2->add();
        
        if (!Shop::isFeatureActive())
        {
            Db::getInstance()->insert('st_owl_carousel_group_shop', array(
                'id_st_owl_carousel_group' => (int)$group2->id,
                'id_shop' => (int)Context::getContext()->shop->id,
            ));
        }
        else
        {
            $assos_shop = Tools::getValue('checkBoxShopAsso_st_advanced_banner_group');
            if (empty($assos_shop))
                $assos_shop[(int)Context::getContext()->shop->id] = Context::getContext()->shop->id;
            foreach ($assos_shop as $id_shop => $row)
                Db::getInstance()->insert('st_owl_carousel_group_shop', array(
                    'id_st_owl_carousel_group' => (int)$group2->id,
                    'id_shop' => (int)$id_shop,
                ));
        }
        
        foreach(Db::getInstance()->executeS('SELECT id_st_owl_carousel FROM '._DB_PREFIX_.'st_owl_carousel WHERE id_st_owl_carousel_group='.(int)$group->id) AS $row)
        {
            $slider = new StOwlCarouselClass($row['id_st_owl_carousel']);
            $slider->id = 0;
            $slider->id_st_owl_carousel = 0;
            $slider->id_st_owl_carousel_group = (int)$group2->id;
            $ret &= $slider->add();
        }
        return $ret;
    }
        
    public function processUpdatePositions()
	{
		if (Tools::getValue('action') == 'updatePositions' && Tools::getValue('ajax'))
		{
			$way = (int)(Tools::getValue('way'));
			$id = (int)(Tools::getValue('id'));
			$positions = Tools::getValue('st_owl_carousel');
            $msg = '';
			if (is_array($positions))
				foreach ($positions as $position => $value)
				{
					$pos = explode('_', $value);

					if ((isset($pos[2])) && ((int)$pos[2] === $id))
					{
						if ($object = new StOwlCarouselClass((int)$pos[2]))
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
}
