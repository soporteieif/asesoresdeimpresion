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

class StThemeEditor extends Module
{	
    protected static $access_rights = 0775;
    public $imgtype = array('jpg', 'gif', 'jpeg', 'png');
    public $defaults;
    private $_html;
    private $_config_folder;
    private $_hooks;
    private $_category_sortby;
    private $_font_inherit = 'inherit';
    public $fields_form; 
    public $fields_value;   
    public $validation_errors = array();
    private $systemFonts = array("Helvetica","Arial","Verdana","Georgia","Tahoma","Times New Roman","sans-serif");
    private $googleFonts;
    private $lang_array = array('welcome','welcome_logged','welcome_link','copyright_text','search_label','newsletter_label');
    public $module_font = array('stadvancedbanner','steasycontent','stiosslider','stowlcarousel','stparallax');
    public static $position_right_panel = array(
		array('id' => '1_0', 'name' => 'At bottom of screen'),
		array('id' => '1_10', 'name' => 'Bottom 10%'),
		array('id' => '1_20', 'name' => 'Bottom 20%'),
		array('id' => '1_30', 'name' => 'Bottom 30%'),
		array('id' => '1_40', 'name' => 'Bottom 40%'),
		array('id' => '1_50', 'name' => 'Bottom 50%'),
		array('id' => '2_0', 'name' => 'At top of screen'),
		array('id' => '2_10', 'name' => 'Top 10%'),
		array('id' => '2_20', 'name' => 'Top 20%'),
		array('id' => '2_30', 'name' => 'Top 30%'),
		array('id' => '2_40', 'name' => 'Top 40%'),
		array('id' => '2_50', 'name' => 'Top 50%'),
    );
    
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
    public static $textTransform = array(
		array('id' => 0, 'name' => 'none'),
		array('id' => 1, 'name' => 'uppercase'),
		array('id' => 2, 'name' => 'lowercase'),
		array('id' => 3, 'name' => 'capitalize'),
    );
    public static $tabs        = array(
        array('id'  => '0,23', 'name' => 'General'),
        array('id'  => '1', 'name' => 'Category pages'),
        array('id'  => '16', 'name' => 'Product pages'),
        array('id'  => '2,26,27,28', 'name' => 'Colors'),
        array('id'  => '3', 'name' => 'Font'),
        array('id'  => '15', 'name' => 'Stickers'),
        array('id'  => '4,19', 'name' => 'Header'),
        array('id'  => '24,25', 'name' => 'Sticky header/menu'),
        array('id'  => '20,51,52,53,21', 'name' => 'Advanced megamenu'),
        //array('id'  => '5', 'name' => 'Megamenu'),
        array('id'  => '6', 'name' => 'Body'),
        array('id'  => '7', 'name' => 'Footer-Top'),
        array('id'  => '8', 'name' => 'Footer'),
        array('id'  => '9', 'name' => 'Footer-Secondary'),
        array('id'  => '10', 'name' => 'Footer-Copyright'),
        array('id'  => '11,12,13', 'name' => 'Slides'),
        array('id'  => '14', 'name' => 'Custom codes'),
        array('id'  => '17', 'name' => 'Module navigation'),
        array('id'  => '18', 'name' => 'Iphone/Ipad icons'),
    );
    public static $logo_width_map = array(
        array('id'=>1, 'name'=>'1/12'),
        array('id'=>2, 'name'=>'2/12'),
        array('id'=>3, 'name'=>'3/12'),
        array('id'=>5, 'name'=>'5/12'),
        array('id'=>6, 'name'=>'6/12'),
        array('id'=>7, 'name'=>'7/12'),
        array('id'=>8, 'name'=>'8/12'),
        array('id'=>9, 'name'=>'9/12'),
        array('id'=>10, 'name'=>'10/12'),
        array('id'=>11, 'name'=>'11/12'),
        array('id'=>12, 'name'=>'12/12'),
    );
    
    public static $border_style_map = array(
        array('id'=>0,  'name'=>'Default'),
        array('id'=>1,  'name'=>'No border'),
        array('id'=>11, 'name'=>'Full width, 1px height'),
        array('id'=>12, 'name'=>'Full width, 2px height'),
        array('id'=>13, 'name'=>'Full width, 3px height'),
        array('id'=>14, 'name'=>'Full width, 4px height'),
        array('id'=>15, 'name'=>'Full width, 5px height'),
        array('id'=>16, 'name'=>'Full width, 6px height'),
        array('id'=>17, 'name'=>'Full width, 7px height'),
        array('id'=>18, 'name'=>'Full width, 8px height'),
        array('id'=>19, 'name'=>'Full width, 9px height'),
        array('id'=>21, 'name'=>'Boxed width, 1px height'),
        array('id'=>22, 'name'=>'Boxed width, 2px height'),
        array('id'=>23, 'name'=>'Boxed width, 3px height'),
        array('id'=>24, 'name'=>'Boxed width, 4px height'),
        array('id'=>25, 'name'=>'Boxed width, 5px height'),
        array('id'=>26, 'name'=>'Boxed width, 6px height'),
        array('id'=>27, 'name'=>'Boxed width, 7px height'),
        array('id'=>28, 'name'=>'Boxed width, 8px height'),
        array('id'=>29, 'name'=>'Boxed width, 9px height'),
    );

	public function __construct()
	{
		$this->name = 'stthemeeditor';
		$this->tab = 'administration';
		$this->version = '3.2.9';
		$this->author = 'SUNNYTOO.COM';
		$this->need_instance = 0;
        $this->bootstrap = true;
        
	 	parent::__construct();

		$this->displayName = $this->l('Theme editor');
		$this->description = $this->l('Allows to change theme design');
        
        //$this->_checkGlobal();

        $this->googleFonts = include_once(dirname(__FILE__).'/googlefonts.php');

        $this->_config_folder = _PS_MODULE_DIR_.$this->name.'/config/';
        if($custom_fonts_string = Configuration::get('STSN_CUSTOM_FONTS'))
        {
            $custom_fonts_arr = explode(',', $custom_fonts_string);
            foreach ($custom_fonts_arr as $font)
                if(trim($font))
                    $this->systemFonts[] = $font;
        }
        
        $this->_category_sortby = array(
            array(
                'id' => 'price_asc',
                'val' => '1',
                'name' => $this->l('Price: Lowest first')
            ),
            array(
                'id' => 'price_desc',
                'val' => '1',
                'name' => $this->l('Price: Highest first')
            ),
            array(
                'id' => 'name_asc',
                'val' => '1',
                'name' => $this->l('Product Name: A to Z')
            ),
            array(
                'id' => 'name_desc',
                'val' => '1',
                'name' => $this->l('Product Name: Z to A')
            ),
            array(
                'id' => 'quantity_desc',
                'val' => '1',
                'name' => $this->l('In stock')
            ),
            array(
                'id' => 'reference_asc',
                'val' => '1',
                'name' => $this->l('Reference: Lowest first')
            ),
            array(
                'id' => 'reference_desc',
                'val' => '1',
                'name' => $this->l('Reference: Highest first')
            ),
        );
        
        $this->defaults = array(
            'responsive'                    => array('exp'=>1,'val'=> 1),
            'responsive_max'                => array('exp'=>1,'val'=> 1),
            'boxstyle'                      => array('exp'=>1,'val'=> 1),
            'version_switching'             => array('exp'=>1,'val'=> 0),
            'welcome'                       => array('exp'=>1,'val'=> array('1'=>'Welcome')),
            'welcome_logged'                => array('exp'=>1,'val'=> array('1'=>'Welcome')),
            'welcome_link'                  => array('exp'=>1,'val'=> array('1'=>'')),
            'product_view'                  => array('exp'=>1,'val'=> 'grid_view'),
            'copyright_text'                => array('exp'=>0,'val'=> array(1=>'&COPY; '.date('Y').' Powered by Presta Shop&trade;. All Rights Reserved'),'esc'=>1),
            'search_label'                  => array('exp'=>1,'val'=> array(1=>'Search here')),
            'newsletter_label'              => array('exp'=>1,'val'=> array(1=>'Your e-mail')),
            'footer_img'                    => array('exp'=>1,'val'=> 'img/payment-options.png'), 
		    'icon_iphone_57'                => array('exp'=>1,'val'=> 'img/touch-icon-iphone-57.png'), 
		    'icon_iphone_72'                => array('exp'=>1,'val'=> 'img/touch-icon-iphone-72.png'), 
		    'icon_iphone_114'               => array('exp'=>1,'val'=> 'img/touch-icon-iphone-114.png'), 
		    'icon_iphone_144'               => array('exp'=>1,'val'=> 'img/touch-icon-iphone-144.png'), 
		    'custom_css'                    => array('exp'=>0,'val'=> '','esc'=>1), 
		    'custom_js'                     => array('exp'=>0,'val'=> '','esc'=>1), 
            'tracking_code'                 => array('exp'=>0,'val'=> '','esc'=>1), 
		    'head_code'                     => array('exp'=>0,'val'=> '','esc'=>1), 
            'scroll_to_top'                 => array('exp'=>1,'val'=> 1),
            'addtocart_animation'           => array('exp'=>1,'val'=> 0),
            'google_rich_snippets'          => array('exp'=>1,'val'=> 1),
            'display_tax_label'             => array('exp'=>1,'val'=> 0),
            'position_right_panel'          => array('exp'=>1,'val'=> '1_40'),
            'flyout_buttons'                => array('exp'=>1,'val'=> 0),
            'length_of_product_name'        => array('exp'=>1,'val'=> 0),
            'logo_position'                 => array('exp'=>1,'val'=> 0),
            'logo_height'                   => array('exp'=>1,'val'=> 0),
            'logo_width'                    => array('exp'=>1,'val'=>4),
            'megamenu_position'             => array('exp'=>1,'val'=> 0),
            //font
    		"font_text"                     => array('exp'=>1,'val'=> ''),
    		"font_price"                    => array('exp'=>1,'val'=> ''),
    		"font_price_size"               => array('exp'=>1,'val'=> 0),
    		"font_old_price_size"           => array('exp'=>1,'val'=> 0),
    		"font_heading"                  => array('exp'=>1,'val'=> 'Fjalla One:400'),
    		"font_heading_weight"           => array('exp'=>1,'val'=> 0),
    		"font_heading_trans"            => array('exp'=>1,'val'=> 1),
    		"font_heading_size"             => array('exp'=>1,'val'=> 0),
    		"footer_heading_size"           => array('exp'=>1,'val'=> 0),
            /*
    		"font_title"                    => array('exp'=>1,'val'=> 'Fjalla One:400'),
    		"font_title_weight"             => array('exp'=>1,'val'=> 0),
    		"font_title_trans"              => array('exp'=>1,'val'=> 1),
    		"font_title_size"               => array('exp'=>1,'val'=> ''),
            */
    		"font_menu"                     => array('exp'=>1,'val'=> 'Fjalla One:400'),
    		"font_menu_weight"              => array('exp'=>1,'val'=> 0),
    		"font_menu_trans"               => array('exp'=>1,'val'=> 1),
    		"font_menu_size"                => array('exp'=>1,'val'=> 0),
    		"font_cart_btn"                 => array('exp'=>1,'val'=> 'Fjalla One:400'),
            "font_latin_support"            => array('exp'=>1,'val'=> 0),
            "font_cyrillic_support"         => array('exp'=>1,'val'=> 0),
            "font_vietnamese"               => array('exp'=>1,'val'=> 0),
            "font_greek_support"            => array('exp'=>1,'val'=> 0),
            "font_arabic_support"               => array('exp'=>1,'val'=>0),
            //style
            'display_comment_rating'        => array('exp'=>1,'val'=> 1),
            'display_category_title'        => array('exp'=>1,'val'=> 1),
            'display_category_desc'         => array('exp'=>1,'val'=> 0),
            'display_category_image'        => array('exp'=>1,'val'=> 0),
            'display_subcate'               => array('exp'=>1,'val'=> 1),
            'display_pro_attr'              => array('exp'=>1,'val'=> 0),
            'product_secondary'             => array('exp'=>1,'val'=> 1),
            'product_big_image'             => array('exp'=>1,'val'=> 0),
            'show_brand_logo'               => array('exp'=>1,'val'=> 1),
            'product_tabs'                  => array('exp'=>1,'val'=> 0),
            'display_cate_desc_full'        => array('exp'=>1,'val'=> 0),
            'show_short_desc_on_grid'       => array('exp'=>1,'val'=> 0),
            'display_color_list'            => array('exp'=>1,'val'=> 0),
            'pro_list_display_brand_name'   => array('exp'=>1,'val'=> 0),
            //footer
            'footer_border_color'           => array('exp'=>1,'val'=> ''),
            'footer_border'                 => array('exp'=>1,'val'=> 0),
            'second_footer_color'           => array('exp'=>1,'val'=> ''),
            'second_footer_link_hover_color'=> array('exp'=>1,'val'=> ''),
            'footer_color'                  => array('exp'=>1,'val'=> ''),
            'footer_link_color'             => array('exp'=>1,'val'=> ''),
            'footer_link_hover_color'       => array('exp'=>1,'val'=> ''),
            
            'footer_top_border_color'       => array('exp'=>1,'val'=> ''),
            'footer_top_border'             => array('exp'=>1,'val'=> 0),
            'footer_top_bg'                 => array('exp'=>1,'val'=> ''),
            'footer_top_con_bg'             => array('exp'=>1,'val'=> ''),
            "f_top_bg_img"                  => array('exp'=>1,'val'=> ''),
    		"f_top_bg_fixed"                => array('exp'=>1,'val'=> 0),
    		"f_top_bg_repeat"               => array('exp'=>1,'val'=> 0), 
    		"f_top_bg_position"             => array('exp'=>1,'val'=> 0), 
    		"f_top_bg_pattern"              => array('exp'=>1,'val'=> 0), 
            'footer_bg_color'               => array('exp'=>1,'val'=> ''),
            'footer_con_bg_color'           => array('exp'=>1,'val'=> ''),
    		"footer_bg_img"                 => array('exp'=>1,'val'=> ''),
            "footer_bg_fixed"               => array('exp'=>1,'val'=>0),
    		"footer_bg_repeat"              => array('exp'=>1,'val'=> 0), 
    		"footer_bg_position"            => array('exp'=>1,'val'=> 0), 
    		"footer_bg_pattern"             => array('exp'=>1,'val'=> 0), 
            'footer_secondary_bg'           => array('exp'=>1,'val'=> ''),
            'footer_secondary_con_bg'       => array('exp'=>1,'val'=> ''),
            "f_secondary_bg_img"            => array('exp'=>1,'val'=> ''),
    		"f_secondary_bg_fixed"          => array('exp'=>1,'val'=> 0),
    		"f_secondary_bg_repeat"         => array('exp'=>1,'val'=> 0), 
    		"f_secondary_bg_position"       => array('exp'=>1,'val'=> 0), 
    		"f_secondary_bg_pattern"        => array('exp'=>1,'val'=> 0), 
            'footer_info_bg'                => array('exp'=>1,'val'=> ''),
            'footer_info_con_bg'            => array('exp'=>1,'val'=> ''),
            "f_info_bg_img"                 => array('exp'=>1,'val'=> ''),
    		"f_info_bg_fixed"               => array('exp'=>1,'val'=> 0),
    		"f_info_bg_repeat"              => array('exp'=>1,'val'=> 0), 
    		"f_info_bg_position"            => array('exp'=>1,'val'=> 0), 
    		"f_info_bg_pattern"             => array('exp'=>1,'val'=> 0), 
            'footer_secondary_border_color'  => array('exp'=>1,'val'=> ''),
            'footer_secondary_border'        => array('exp'=>1,'val'=> 0),
            'footer_info_border_color'      => array('exp'=>1,'val'=> ''),
            'footer_info_border'            => array('exp'=>1,'val'=> 0),
            //header
            'header_padding'            => array('exp'=>1,'val'=>0),
            'header_bottom_spacing'            => array('exp'=>1,'val'=>10),
            'header_text_color'             => array('exp'=>1,'val'=> ''),
            'header_link_color'             => array('exp'=>1,'val'=> ''),
            'header_link_hover_color'       => array('exp'=>1,'val'=> ''),
            'header_link_hover_bg'          => array('exp'=>1,'val'=> ''),
            'dropdown_hover_color'          => array('exp'=>1,'val'=> ''),
            'dropdown_bg_color'             => array('exp'=>1,'val'=> ''),
            "header_topbar_bg"              => array('exp'=>1,'val'=> ''), 
    		"header_topbar_opacity"         => array('exp'=>1,'val'=> 1), 
    		//"header_topbar_bc"            => array('exp'=>1,'val'=> ''),
    		"header_topbar_sep"             => array('exp'=>1,'val'=> ''),
            'header_bg_color'               => array('exp'=>1,'val'=> ''),
            'header_con_bg_color'           => array('exp'=>1,'val'=> ''),
    		"header_bg_img"                 => array('exp'=>1,'val'=> ''),
    		"header_bg_repeat"              => array('exp'=>1,'val'=> 0), 
    		"header_bg_position"            => array('exp'=>1,'val'=> 0), 
            "header_bg_pattern"             => array('exp'=>1,'val'=> 0),  
            "display_banner_bg"             => array('exp'=>1,'val'=> 0),  
            "topbar_height"                 => array('exp'=>1,'val'=> 0),  
            "topbar_border"                 => array('exp'=>1,'val'=> 0),  
    		"topbar_border_color"           => array('exp'=>1,'val'=> ''),  
            "header_color"                  => array('exp'=>1,'val'=> ''), 
            "header_link_hover"             => array('exp'=>1,'val'=> ''), 
            //body
    		"body_bg_color"                 => array('exp'=>1,'val'=> ''),
            "body_con_bg_color"             => array('exp'=>1,'val'=> ''),
    		"body_bg_img"                   => array('exp'=>1,'val'=> ''),
    		"body_bg_repeat"                => array('exp'=>1,'val'=> 0), 
    		"body_bg_position"              => array('exp'=>1,'val'=> 0), 
    		"body_bg_fixed"                 => array('exp'=>1,'val'=> 0),
    		"body_bg_cover"                 => array('exp'=>1,'val'=> 0),
    		"body_bg_pattern"               => array('exp'=>1,'val'=> 0), 
            'main_con_bg_color'             => array('exp'=>1,'val'=> ''),
            //crossselling
            'cs_easing'                     => array('exp'=>1,'val'=> 0),
            'cs_slideshow'                  => array('exp'=>1,'val'=> 0),
            'cs_s_speed'                    => array('exp'=>1,'val'=> 7000),
            'cs_a_speed'                    => array('exp'=>1,'val'=> 400),
            'cs_pause_on_hover'             => array('exp'=>1,'val'=> 1),
            'cs_loop'                       => array('exp'=>1,'val'=> 0),
            'cs_move'                       => array('exp'=>1,'val'=> 0),
            'cs_title_no_bg'                => array('exp'=>1,'val'=> 0),
            'cs_per_lg_0'                   => array('exp'=>1,'val'=> 5),
            'cs_per_md_0'                   => array('exp'=>1,'val'=> 5),
            'cs_per_sm_0'                   => array('exp'=>1,'val'=> 4),
            'cs_per_xs_0'                   => array('exp'=>1,'val'=> 3),
            'cs_per_xxs_0'                  => array('exp'=>1,'val'=> 2),
            //productcategory
            'pc_easing'                     => array('exp'=>1,'val'=> 0),
            'pc_slideshow'                  => array('exp'=>1,'val'=> 0),
            'pc_s_speed'                    => array('exp'=>1,'val'=> 7000),
            'pc_a_speed'                    => array('exp'=>1,'val'=> 400),
            'pc_pause_on_hover'             => array('exp'=>1,'val'=> 1),
            'pc_loop'                       => array('exp'=>1,'val'=> 0),
            'pc_move'                       => array('exp'=>1,'val'=> 0),
            'pc_title_no_bg'                => array('exp'=>1,'val'=> 0),
            'pc_per_lg_0'                   => array('exp'=>1,'val'=> 5),
            'pc_per_md_0'                   => array('exp'=>1,'val'=> 5),
            'pc_per_sm_0'                   => array('exp'=>1,'val'=> 4),
            'pc_per_xs_0'                   => array('exp'=>1,'val'=> 3),
            'pc_per_xxs_0'                  => array('exp'=>1,'val'=> 2),
            //accessories
            'ac_easing'                     => array('exp'=>1,'val'=> 0),
            'ac_slideshow'                  => array('exp'=>1,'val'=> 0),
            'ac_s_speed'                    => array('exp'=>1,'val'=> 7000),
            'ac_a_speed'                    => array('exp'=>1,'val'=> 400),
            'ac_pause_on_hover'             => array('exp'=>1,'val'=> 1),
            'ac_loop'                       => array('exp'=>1,'val'=> 0),
            'ac_move'                       => array('exp'=>1,'val'=> 0),
            'ac_title_no_bg'                => array('exp'=>1,'val'=> 0),
            'ac_per_lg_0'                   => array('exp'=>1,'val'=> 5),
            'ac_per_md_0'                   => array('exp'=>1,'val'=> 5),
            'ac_per_sm_0'                   => array('exp'=>1,'val'=> 4),
            'ac_per_xs_0'                   => array('exp'=>1,'val'=> 3),
            'ac_per_xxs_0'                  => array('exp'=>1,'val'=> 2),
            //color
            'text_color'                    => array('exp'=>1,'val'=> ''),
            'link_color'                    => array('exp'=>1,'val'=> ''),
            'link_hover_color'              => array('exp'=>1,'val'=> ''),
            'breadcrumb_color'              => array('exp'=>1,'val'=> ''),
            'breadcrumb_hover_color'        => array('exp'=>1,'val'=> ''),
            'breadcrumb_bg'                 => array('exp'=>1,'val'=> ''),
            'price_color'                   => array('exp'=>1,'val'=> ''),
            'old_price_color'                   => array('exp'=>1,'val'=> ''),
            'icon_color'                    => array('exp'=>1,'val'=> ''),
            'icon_hover_color'              => array('exp'=>1,'val'=> ''),
            'icon_bg_color'                 => array('exp'=>1,'val'=> ''),
            'icon_hover_bg_color'           => array('exp'=>1,'val'=> ''),
            'icon_disabled_color'           => array('exp'=>1,'val'=> ''),
            'right_panel_border'            => array('exp'=>1,'val'=> ''),
            'starts_color'                  => array('exp'=>1,'val'=> ''),
            'circle_number_color'           => array('exp'=>1,'val'=> ''),
            'circle_number_bg'              => array('exp'=>1,'val'=> ''),
            'pro_grid_hover_bg'             => array('exp'=>1,'val'=> ''),
            'block_headings_color'          => array('exp'=>1,'val'=> ''),
            'headings_color'                => array('exp'=>1,'val'=> ''),
            'f_top_h_color'                 => array('exp'=>1,'val'=> ''),
            'footer_h_color'                => array('exp'=>1,'val'=> ''),
            'f_secondary_h_color'           => array('exp'=>1,'val'=> ''),
            //button
            'btn_color'                     => array('exp'=>1,'val'=> ''),
            'btn_hover_color'               => array('exp'=>1,'val'=> ''),
            'btn_bg_color'                  => array('exp'=>1,'val'=> ''),
            'btn_hover_bg_color'            => array('exp'=>1,'val'=> ''),
            'p_btn_color'                   => array('exp'=>1,'val'=> ''),
            'p_btn_hover_color'             => array('exp'=>1,'val'=> ''),
            'p_btn_bg_color'                => array('exp'=>1,'val'=> ''),
            'p_btn_hover_bg_color'          => array('exp'=>1,'val'=> ''),
            //menu
            'sticky_menu'                   => array('exp'=>1,'val'=> 2),
            'sticky_menu_bg'                => array('exp'=>1,'val'=> ''),
            'menu_color'                    => array('exp'=>1,'val'=> ''),
            'menu_bg_color'                 => array('exp'=>1,'val'=> ''),
            'menu_hover_color'              => array('exp'=>1,'val'=> ''),
            'menu_hover_bg'                 => array('exp'=>1,'val'=> ''),
            'second_menu_color'             => array('exp'=>1,'val'=> ''),
            'second_menu_hover_color'       => array('exp'=>1,'val'=> ''),
            'third_menu_color'              => array('exp'=>1,'val'=> ''),
            'third_menu_hover_color'        => array('exp'=>1,'val'=> ''),
            'menu_mob_items1_color'         => array('exp'=>1,'val'=> ''),
            'menu_mob_items2_color'         => array('exp'=>1,'val'=> ''),
            'menu_mob_items3_color'         => array('exp'=>1,'val'=> ''),
            'menu_mob_items1_bg'            => array('exp'=>1,'val'=> ''),
            'menu_mob_items2_bg'            => array('exp'=>1,'val'=> ''),
            'menu_mob_items3_bg'            => array('exp'=>1,'val'=> ''),
            //sticker
            'new_color'                     => array('exp'=>1,'val'=> ''),
            'new_style'                     => array('exp'=>1,'val'=> 0),
            'new_bg_color'                  => array('exp'=>1,'val'=> ''),
            'new_bg_img'                    => array('exp'=>1,'val'=> ''),
            'new_stickers_width'            => array('exp'=>1,'val'=> ''),
            'new_stickers_top'              => array('exp'=>1,'val'=> 25),
            'new_stickers_right'            => array('exp'=>1,'val'=> 0),
            'sale_color'                    => array('exp'=>1,'val'=> ''),
            'sale_style'                    => array('exp'=>1,'val'=> 0),
            'sale_bg_color'                 => array('exp'=>1,'val'=> ''),
            'sale_bg_img'                   => array('exp'=>1,'val'=> ''),
            'sale_stickers_width'           => array('exp'=>1,'val'=> ''),
            'sale_stickers_top'             => array('exp'=>1,'val'=> 25),
            'sale_stickers_left'            => array('exp'=>1,'val'=> 0),
            'discount_percentage'           => array('exp'=>1,'val'=> 0),
            'price_drop_border_color'       => array('exp'=>1,'val'=> ''),
            'price_drop_bg_color'           => array('exp'=>1,'val'=> ''),
            'price_drop_color'              => array('exp'=>1,'val'=> ''),
            'price_drop_bottom'             => array('exp'=>1,'val'=> 50),
            'price_drop_right'              => array('exp'=>1,'val'=> 10),
            'price_drop_width'              => array('exp'=>1,'val'=> 0),

            'sold_out'                      => array('exp'=>1,'val'=> 0),
            'sold_out_color'                => array('exp'=>1,'val'=> ''),
            'sold_out_bg_color'             => array('exp'=>1,'val'=> ''),
            'sold_out_bg_img'               => array('exp'=>1,'val'=> ''),
            //
            'cart_icon'                     => array('exp'=>1,'val'=> 0),
            'wishlist_icon'                 => array('exp'=>1,'val'=> 0),
            'compare_icon'                  => array('exp'=>1,'val'=> 0),
            'quick_view_icon'                  => array('exp'=>1,'val'=>0),
            'view_icon'                        => array('exp'=>1,'val'=>0),
            //
            'pro_tab_color'                 => array('exp'=>1,'val'=> ''),
            'pro_tab_active_color'          => array('exp'=>1,'val'=> ''),
            'pro_tab_bg'                    => array('exp'=>1,'val'=> ''),
            'pro_tab_active_bg'             => array('exp'=>1,'val'=> ''),
            'pro_tab_content_bg'            => array('exp'=>1,'val'=> ''),
            'display_pro_tags'              => array('exp'=>1,'val'=> 0),
            //
            'cate_sortby'                   => array('exp'=>1,'val'=> ''),
            'cate_sortby_name'              => array('exp'=>1,'val'=> ''),
            //
            'category_pro_per_lg_3'         => array('exp'=>1,'val'=> 3),
            'category_pro_per_md_3'         => array('exp'=>1,'val'=> 3),
            'category_pro_per_sm_3'         => array('exp'=>1,'val'=> 2),
            'category_pro_per_xs_3'         => array('exp'=>1,'val'=> 2),
            'category_pro_per_xxs_3'        => array('exp'=>1,'val'=> 1),

            'category_pro_per_lg_2'         => array('exp'=>1,'val'=> 4),
            'category_pro_per_md_2'         => array('exp'=>1,'val'=> 4),
            'category_pro_per_sm_2'         => array('exp'=>1,'val'=> 3),
            'category_pro_per_xs_2'         => array('exp'=>1,'val'=> 2),
            'category_pro_per_xxs_2'        => array('exp'=>1,'val'=> 1),
            
            'category_pro_per_lg_1'         => array('exp'=>1,'val'=> 5),
            'category_pro_per_md_1'         => array('exp'=>1,'val'=> 5),
            'category_pro_per_sm_1'         => array('exp'=>1,'val'=> 4),
            'category_pro_per_xs_1'         => array('exp'=>1,'val'=> 3),
            'category_pro_per_xxs_1'        => array('exp'=>1,'val'=> 2),

            'hometab_pro_per_lg_0'          => array('exp'=>1,'val'=> 4),
            'hometab_pro_per_md_0'          => array('exp'=>1,'val'=> 4),
            'hometab_pro_per_sm_0'          => array('exp'=>1,'val'=> 3),
            'hometab_pro_per_xs_0'          => array('exp'=>1,'val'=> 2),
            'hometab_pro_per_xxs_0'         => array('exp'=>1,'val'=> 1),

            'packitems_pro_per_lg_0'        => array('exp'=>1,'val'=> 4),
            'packitems_pro_per_md_0'        => array('exp'=>1,'val'=> 4),
            'packitems_pro_per_sm_0'        => array('exp'=>1,'val'=> 3),
            'packitems_pro_per_xs_0'        => array('exp'=>1,'val'=> 2),
            'packitems_pro_per_xxs_0'       => array('exp'=>1,'val'=> 1),

            'categories_per_lg_0'           => array('exp'=>1,'val'=> 5),
            'categories_per_md_0'           => array('exp'=>1,'val'=> 5),
            'categories_per_sm_0'           => array('exp'=>1,'val'=> 4),
            'categories_per_xs_0'           => array('exp'=>1,'val'=> 3),
            'categories_per_xxs_0'          => array('exp'=>1,'val'=> 2),
            //1.6
            'category_show_all_btn'         => array('exp'=>1,'val'=> 0),
            'zoom_type'                     => array('exp'=>1,'val'=> 2),

            'breadcrumb_width'              => array('exp'=>1,'val'=> 0),
            'breadcrumb_bg_style'           => array('exp'=>1,'val'=> 0),
            'megamenu_width'                => array('exp'=>1,'val'=> 0),
            //
            'flyout_buttons_bg'             => array('exp'=>1,'val'=> ''),
            //
            'retina'                        => array('exp'=>1,'val'=> 1),
            'yotpo_sart'                    => array('exp'=>1,'val'=> 0),   
            'retina_logo'                   => array('exp'=>0,'val'=> ''),  
            'navigation_pipe'               => array('exp'=>1,'val'=> '>','esc'=>1),
            'mail_color'                    => array('exp'=>1,'val'=> ''),
            'top_spacing'                   => array('exp'=>1,'val'=> 0),   
            'bottom_spacing'                => array('exp'=>1,'val'=> 0),   
            'display_pro_condition'         => array('exp'=>1,'val'=> 1),   
            'display_pro_reference'         => array('exp'=>1,'val'=> 1),   

            'adv_megamenu_position'              => array('exp'=>1,'val'=> 0),
            'adv_menu_sticky'                    => array('exp'=>1,'val'=> 2),
            'adv_menu_sticky_bg'                 => array('exp'=>1,'val'=> ''),
            'adv_menu_sticky_opacity'            => array('exp'=>1,'val'=> 0.95),
            'adv_st_menu_height'                 => array('exp'=>1,'val'=> 0),
            'adv_font_menu'                      => array('exp'=>1,'val'=> 'Fjalla One:400'),
            'adv_second_font_menu'               => array('exp'=>1,'val'=> ''),
            'adv_ver_font_menu'               => array('exp'=>1,'val'=> ''),
            'adv_third_font_menu'                => array('exp'=>1,'val'=> ''),
            'adv_font_menu_size'                 => array('exp'=>1,'val'=> 0),
            'adv_second_font_menu_size'          => array('exp'=>1,'val'=> 0),
            'adv_third_font_menu_size'           => array('exp'=>1,'val'=> 0),
            'adv_ver_font_menu_size'           => array('exp'=>1,'val'=> 0),
            'adv_font_menu_trans'                => array('exp'=>1,'val'=> 1),
            'adv_megamenu_width'                 => array('exp'=>1,'val'=> 1),
            'adv_menu_bg_color'                  => array('exp'=>1,'val'=> ''),
            'adv_menu_bottom_border'             => array('exp'=>1,'val'=> 0),
            'adv_menu_bottom_border_color'       => array('exp'=>1,'val'=> ''),
            'adv_menu_bottom_border_hover_color' => array('exp'=>1,'val'=> ''),
            'adv_menu_color'                     => array('exp'=>1,'val'=> ''),
            'adv_menu_hover_color'               => array('exp'=>1,'val'=> ''),
            'adv_menu_hover_bg'                  => array('exp'=>1,'val'=> ''),
            'adv_second_menu_color'              => array('exp'=>1,'val'=> ''),
            'adv_second_menu_hover_color'        => array('exp'=>1,'val'=> ''),
            'adv_third_menu_color'               => array('exp'=>1,'val'=> ''),
            'adv_third_menu_hover_color'         => array('exp'=>1,'val'=> ''),
            'adv_menu_mob_items1_color'          => array('exp'=>1,'val'=> ''),
            'adv_menu_mob_items2_color'          => array('exp'=>1,'val'=> ''),
            'adv_menu_mob_items3_color'          => array('exp'=>1,'val'=> ''),
            'adv_menu_mob_items1_bg'             => array('exp'=>1,'val'=> ''),
            'adv_menu_mob_items2_bg'             => array('exp'=>1,'val'=> ''),
            'adv_menu_mob_items3_bg'             => array('exp'=>1,'val'=> ''),
            'adv_menu_multi_bg'                  => array('exp'=>1,'val'=> ''),
            'adv_menu_multi_bg_hover'            => array('exp'=>1,'val'=> ''),
            'adv_menu_ver_open'                  => array('exp'=>1,'val'=> 0),
            'adv_menu_ver_sub_style'                  => array('exp'=>0,'val'=> 0),
            'adv_menu_ver_title_width'           => array('exp'=>1,'val'=> 0),
            'adv_menu_ver_title_align'           => array('exp'=>1,'val'=> 0),
            'adv_menu_ver_title'                 => array('exp'=>1,'val'=> ''),
            'adv_menu_ver_hover_title'           => array('exp'=>1,'val'=> ''),
            'adv_menu_ver_bg'                    => array('exp'=>1,'val'=> ''),
            'adv_menu_ver_hover_bg'              => array('exp'=>1,'val'=> ''),
            'adv_menu_ver_item_color'            => array('exp'=>1,'val'=> ''),
            'adv_menu_ver_item_hover_color'      => array('exp'=>1,'val'=> ''),
            'adv_menu_ver_item_bg'               => array('exp'=>1,'val'=> ''),
            'adv_menu_ver_item_hover_bg'         => array('exp'=>1,'val'=> ''),
            'c_menu_color'                       => array('exp'=>1,'val'=> ''),
            'c_menu_bg_color'                    => array('exp'=>1,'val'=> ''),
            'c_menu_hover_color'                 => array('exp'=>1,'val'=> ''),
            'c_menu_bg'                          => array('exp'=>1,'val'=> ''),
            'c_menu_hover_bg'                    => array('exp'=>1,'val'=> ''),
            'c_menu_border_color'                => array('exp'=>1,'val'=> ''),
            'c_menu_title_color'                 => array('exp'=>1,'val'=> ''),
            'c_menu_title_bg'                    => array('exp'=>1,'val'=> ''),
            'c_menu_font'                     => array('exp'=>1,'val'=> ''),
            'c_menu_font_size'                => array('exp'=>1,'val'=> 0),
            'c_menu_font_trans'               => array('exp'=>1,'val'=> 0),
            
            'pro_shadow_effect'               => array('exp'=>1,'val'=>1),
            'pro_h_shadow'                    => array('exp'=>1,'val'=>0),
            'pro_v_shadow'                    => array('exp'=>1,'val'=>0),
            'pro_shadow_blur'                 => array('exp'=>1,'val'=>4),
            'pro_shadow_color'                => array('exp'=>1,'val'=>'#000000'),
            'pro_shadow_opacity'              => array('exp'=>1,'val'=>0.1),
            
            'menu_title'                      => array('exp'=>1,'val'=>0),
            'adv_menu_title'                  => array('exp'=>1,'val'=>0),
            'flyout_wishlist'                 => array('exp'=>1,'val'=>0),
            'flyout_quickview'                => array('exp'=>1,'val'=>0),
            'flyout_comparison'               => array('exp'=>1,'val'=>0),
            'display_add_to_cart'             => array('exp'=>1,'val'=>0),
            
            'transparent_header'              => array('exp'=>1,'val'=>0),
            'use_view_more_instead'           => array('exp'=>1,'val'=>0),
            
            'base_border_color'                       => array('exp'=>1,'val'=>''),
            'sticky_mobile_header'                    => array('exp'=>1,'val'=>2),
            'sticky_mobile_header_height'             => array('exp'=>1,'val'=>0),
            'sticky_mobile_header_color'              => array('exp'=>1,'val'=>''),
            'sticky_mobile_header_background'         => array('exp'=>1,'val'=>''),
            'sticky_mobile_header_background_opacity' => array('exp'=>1,'val'=>0.95),
            'side_bar_background'                     => array('exp'=>1,'val'=>''),
            'use_mobile_header'                => array('exp'=>1,'val'=>1),

            'direction_color'                         => array('exp'=>1,'val'=>''),
            'direction_bg'                            => array('exp'=>1,'val'=>''),
            'direction_hover_bg'                      => array('exp'=>1,'val'=>''),
            'direction_disabled_bg'                   => array('exp'=>1,'val'=>''),

            'pagination_color'          => array('exp'=>1,'val'=>''),
            'pagination_color_hover'    => array('exp'=>1,'val'=>''),
            'pagination_bg'             => array('exp'=>1,'val'=>''),
            'pagination_bg_hover'       => array('exp'=>1,'val'=>''),
            'pagination_border'    => array('exp'=>1,'val'=>''),

            'form_bg_color'    => array('exp'=>1,'val'=>''),

            'boxed_shadow_effect'               => array('exp'=>1,'val'=>1),
            'boxed_h_shadow'                    => array('exp'=>1,'val'=>0),
            'boxed_v_shadow'                    => array('exp'=>1,'val'=>0),
            'boxed_shadow_blur'                 => array('exp'=>1,'val'=>3),
            'boxed_shadow_color'                => array('exp'=>1,'val'=>'#000000'),
            'boxed_shadow_opacity'              => array('exp'=>1,'val'=>0.1),

            'slide_lr_column'              => array('exp'=>1,'val'=>1),
            'pro_thumbnails'              => array('exp'=>1,'val'=>0),
            'custom_fonts'              => array('exp'=>1,'val'=>''),
            /*'pro_image_column_md'              => array('exp'=>1,'val'=>4),
            'pro_primary_column_md'              => array('exp'=>1,'val'=>5),
            'pro_secondary_column_md'              => array('exp'=>1,'val'=>3),
            'pro_image_column_sm'              => array('exp'=>1,'val'=>4),
            'pro_primary_column_sm'              => array('exp'=>1,'val'=>5),
            'pro_secondary_column_sm'              => array('exp'=>1,'val'=>3),*/

            'submemus_animation'              => array('exp'=>1,'val'=>0),
            'adv_submemus_animation'              => array('exp'=>1,'val'=>0),
            'menu_icon_with_text'                => array('exp'=>1,'val'=>0),  
            'pro_img_hover_scale'                => array('exp'=>1,'val'=>0),  
            'pro_show_print_btn'                => array('exp'=>1,'val'=>0),
            "font_body_size"                   => array('exp'=>1,'val'=>0),

            "font_product_name"                     => array('exp'=>1,'val'=>''),
            "font_product_name_trans"               => array('exp'=>1,'val'=>0),
            "font_product_name_size"                => array('exp'=>1,'val'=>0),  
            "font_product_name_color"                => array('exp'=>1,'val'=>0),  
            "f_top_h_align"                => array('exp'=>1,'val'=>0),  
            "footer_h_align"                => array('exp'=>1,'val'=>0),  
            "f_secondary_h_align"                => array('exp'=>1,'val'=>0),  

            'transparent_mobile_header'                  => array('exp'=>1,'val'=> 0),
            'transparent_mobile_header_color'                  => array('exp'=>1,'val'=>''),
            'transparent_mobile_header_bg'                  => array('exp'=>1,'val'=>''),
            'transparent_mobile_header_opacity'                  => array('exp'=>1,'val'=>0.4),
            'header_text_trans'                => array('exp'=>1,'val'=>0),
            'f_info_center'                => array('exp'=>1,'val'=>0),
            'pro_quantity_input'                => array('exp'=>0,'val'=>0),
        );
        
        $this->_hooks = array(
            array('displayCategoryFooter','displayCategoryFooter','Display some specific informations on the category page',1),
            array('displayCategoryHeader','displayCategoryHeader','Display some specific informations on the category page',1),
            array('displayTopSecondary','displayTopSecondary','Bottom of the header',1),
            array('displayAnywhere','displayAnywhere','It is easy to call a hook from tpl',1),
            array('displayProductSecondaryColumn','displayProductSecondaryColumn','Product secondary column',1),
            array('displayFooterTop','displayFooterTop','Footer top',1),
            array('displayFooterSecondary','displayFooterSecondary','Footer secondary',1),
            array('displayHomeSecondaryLeft','displayHomeSecondaryLeft','Home secondary left',1),
            array('displayHomeSecondaryRight','displayHomeSecondaryRight','Home secondary right',1),
            array('displayHomeTop','displayHomeTop','Home page top',1),
            array('displayHomeBottom','displayHomeBottom','Hom epage bottom',1),
            array('displayTopLeft','displayTopLeft','Top left-hand side of the page',1),
            array('displayManufacturerHeader','displayManufacturerHeader','Display some specific informations on the manufacturer page',1),
            array('displayHomeVeryBottom','displayHomeVeryBottom','Very bottom of the home page',1),
            array('displayHomeTertiaryRight','displayHomeTertiaryRight','Home tertiary right',1),
            array('displayHomeTertiaryLeft','displayHomeTertiaryLeft','Home tertiary left',1),
            array('displayFullWidthTop','displayFullWidthTop','Full width top',1),
            array('displayBottomColumn','displayBottomColumn','Bottom column',1),
            array('displayFooterBottomRight','displayFooterBottomRight','Footer bottom right',1),
            array('displayFooterBottomLeft','displayFooterBottomLeft','Footer bottom left',1),
            array('displayMobileBar','displayMobileBar','Mobile bar',1),
            array('displayMobileMenu','displayMobileMenu','Mobile menu',1),
            array('displaySideBar','displaySideBar','Side bar',1),
            array('displayFullWidthTop2','displayFullWidthTop2','Full width top 2',1),
            array('displayMainMenuWidget','displayMainMenuWidget','Menu widgets',1),
            array('displayComingSoon','displayComingSoon','Coming soon page',1),
        );
	}
	
	public function install()
	{
	    $this->_preCheckTheme();
	    if ( $this->_addHook() &&
            parent::install() && 
            $this->registerHook('header') && 
            $this->registerHook('displayAnywhere') &&
            $this->registerHook('actionShopDataDuplication') &&
            $this->registerHook('displayRightColumnProduct') &&
            $this->_useDefault()
        ){
            if ($id_hook = Hook::getIdByName('displayHeader'))
                $this->updatePosition($id_hook, 0, 1);
            $this->add_quick_access();
            $this->clear_class_index();
            return true;
        }
        return false;
	}
    
    private function _preCheckTheme()
    {
        foreach(Theme::getThemes() AS $theme)
        {
            if (strtolower($theme->name) == 'panda' || strtolower($theme->directory) == 'panda')
            {
                echo $this->displayError('Sorry, installation failed. You have installed my another theme called "Panda", you can not install them at the same time, because they have several modules with the same name. Please send an email to helloleemj@gmail.com with your FTP access, I will help you solve the problem.');
                exit;
            }
        }
    }
	
    private function _addHook()
	{
        $res = true;
        foreach($this->_hooks as $v)
        {
            if(!$res)
                break;
            if (!Validate::isHookName($v[0]))
                continue;
                
            $id_hook = Hook::getIdByName($v[0]);
    		if (!$id_hook)
    		{
    			$new_hook = new Hook();
    			$new_hook->name = pSQL($v[0]);
    			$new_hook->title = pSQL($v[1]);
    			$new_hook->description = pSQL($v[2]);
    			$new_hook->position = pSQL($v[3]);
    			$new_hook->live_edit  = 0;
    			$new_hook->add();
    			$id_hook = $new_hook->id;
    			if (!$id_hook)
    				$res = false;
    		}
            else
            {
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'hook` set `title`="'.$v[1].'", `description`="'.$v[2].'", `position`="'.$v[3].'", `live_edit`=0 where `id_hook`='.$id_hook);
            }
        }
		return $res;
	}

	private function _removeHook()
	{
	    $sql = 'DELETE FROM `'._DB_PREFIX_.'hook` WHERE ';
        foreach($this->_hooks as $v)
            $sql .= ' `name` = "'.$v[0].'" OR';
		return Db::getInstance()->execute(rtrim($sql,'OR').';');
	}
    
	public function uninstall()
	{
	    if(!parent::uninstall() ||
            !$this->_deleteConfiguration()
        )
			return false;
		return true;
	}
    
    private function _deleteConfiguration()
    {
        $res = true;
        foreach($this->defaults as $k=>$v)
            $res &= Configuration::deleteByName('STSN_'.strtoupper($k));
        return $res;
    }
	
    private function _useDefault($html = false, $id_shop_group = null, $id_shop = null)
    {
        $res = true;
        foreach($this->defaults as $k=>$v)
		    $res &= Configuration::updateValue('STSN_'.strtoupper($k), $v['val'], $html, $id_shop_group, $id_shop);
        return $res;
    }
    private function _usePredefinedStore($store = '', $file = '')
    {
        $res = true;
        
        if(!$store && !$file)
            return false;
        
        if ($file)
            $config_file = $this->_config_folder.$file;
        else
            $config_file = $this->_config_folder.'predefined_'.$store.'.xml';
        if (!file_exists($config_file))
            return $this->displayError('"'.$config_file.'"'.$this->l(' file isn\'t exists.'));
        
        $xml = @simplexml_load_file($config_file);
        
        if ($xml === false)
            return $this->displayError($this->l('Fetch configuration file content failed'));
        
        $languages = Language::getLanguages(false);
        
        $module_data = array();
                
        foreach($xml->children() as $k => $v)
        {
            if ($k == 'module_data' && $v) {
                $module_data = unserialize(base64_decode((string)$v));
            }
            if (!key_exists($k, $this->defaults))
                continue;
            if (in_array($k, $this->lang_array))
            {
                $text_lang = array();
                $default = '';
                foreach($xml->$k->children() AS $_k => $_v)
                {
                    $id_lang = str_replace('lang_', '', $_k);
                    $text_lang[$id_lang] = (string)$_v;
                    if (!$default)
                        $default = $text_lang[$id_lang];
                }
                foreach($languages AS $language)
                    if (!key_exists($language['id_lang'], $text_lang))
                        $text_lang[$language['id_lang']] = $default;
                $this->defaults[$k]['val'] = $text_lang;
            }
            else
                $this->defaults[$k]['val'] = (string)$v;
        }
        foreach($this->defaults as $k=>$v)
		    $res &= Configuration::updateValue('STSN_'.strtoupper($k), $v['val']);
            
      // Import module data.
        if ($module_data) {
            include_once(dirname(__FILE__).'/DemoStore.php');
            $demo = new DemoStore($module_data);
            $demo->import_modules();    
        }
      
        if($res)
        {
            $this->writeCss();
            Tools::clearSmartyCache();
            Media::clearCache();
        }
        return $res;
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
    private function _checkImageDir($dir)
    {
        $result = '';
        if (!file_exists($dir))
        {
            $success = @mkdir($dir, self::$access_rights, true)
						|| @chmod($dir, self::$access_rights);
            if(!$success)
                $result = $this->displayError('"'.$dir.'" '.$this->l('An error occurred during new folder creation'));
        }

        if (!is_writable($dir))
            $result = $this->displayError('"'.$dir.'" '.$this->l('directory isn\'t writable.'));
        
        return $result;
    }
    	
	public function getContent()
	{
	    $this->initFieldsForm();
		$this->context->controller->addCSS(($this->_path).'views/css/admin.css');
		$this->context->controller->addJS(($this->_path).'views/js/admin.js');
        $this->_html .= '<script type="text/javascript">var stthemeeditor_base_uri = "'.__PS_BASE_URI__.'";var stthemeeditor_refer = "'.(int)Tools::getValue('ref').'";var systemFonts = \''.implode(',',$this->systemFonts).'\'; var googleFontsString=\''.Tools::jsonEncode($this->googleFonts).'\';</script>';
        
        if (Tools::isSubmit('resetstthemeeditor'))
        {
            $this->_useDefault();
            $this->writeCss();
            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
        }
        if (Tools::isSubmit('exportstthemeeditor'))
        {
            $this->_html .= $this->export();
        }
        if (Tools::isSubmit('downloadstthemeeditor'))
        {
            $file = Tools::getValue('file');
            if (file_exists($this->_config_folder.$file))
            {
                if (ob_get_length() > 0)
					ob_end_clean();

				ob_start();
				header('Pragma: public');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Cache-Control: public');
				header('Content-Description: File Transfer');
				header('Content-type:text/xml');
				header('Content-Disposition: attachment; filename="'.$file.'"');
				ob_end_flush();
				readfile($this->_config_folder.$file);
				exit;
            }
        }
        if (Tools::isSubmit('uploadstthemeeditor'))
        {
            if (isset($_FILES['xml_config_file_field']) && $_FILES['xml_config_file_field']['tmp_name'] && !$_FILES['xml_config_file_field']['error'])
            {
                $error = '';
                $folder = $this->_config_folder;
                if (!is_dir($folder))
                    $error = $this->displayError('"'.$folder.'" '.$this->l('directory isn\'t exists.'));
                elseif (!is_writable($folder))
                    $error = $this->displayError('"'.$folder.'" '.$this->l('directory isn\'t writable.'));
                
                $file = date('YmdHis').'_'.(int)Shop::getContextShopID().'.xml';
                if (!move_uploaded_file($_FILES['xml_config_file_field']['tmp_name'], $folder.$file))
                    $error = $this->displayError($this->l('Upload config file failed.'));
                else
                {
                    $res = $this->_usePredefinedStore('', $file);
                    if ($res !== 1)
                        $this->_html .= $res;
                    else
                        $this->_html .= $this->displayConfirmation($this->l('Imported data success.'));
                }   
            }
        }
        if (Tools::isSubmit('predefineddemostorestthemeeditor') && Tools::getValue('predefineddemostorestthemeeditor'))
        {
            $res = $this->_usePredefinedStore(Tools::getValue('predefineddemostorestthemeeditor'));
            if ($res !== 1)
                $this->_html .= $this->displayError($this->l('Error occurred while import configuration:')).$res;
            else
            {
                $this->writeCss();
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=4&token='.Tools::getAdminTokenLite('AdminModules'));    
            }
        }
        if(Tools::getValue('act')=='delete_image' && $identi = Tools::getValue('identi'))
        {
            $identi = strtoupper($identi);
            $themeeditor = new StThemeEditor();
            /*20140920
            $image  = Configuration::get('STSN_'.$identi);
        	if (Configuration::get('STSN_'.$identi))
                if (file_exists(_PS_UPLOAD_DIR_.$image))
                    @unlink(_PS_UPLOAD_DIR_.$image);
                elseif(file_exists(_PS_MODULE_DIR_.'stthemeeditor/'.$image) && strpos($image, $identi) === false)
                    @unlink(_PS_MODULE_DIR_.'stthemeeditor/'.$image);
            */
        	Configuration::updateValue('STSN_'.$identi, '');
            $themeeditor->writeCss();
            $result['r'] = true;
            die(json_encode($result));
        }
        if(isset($_POST['savestthemeeditor']))
		{
            $res = true;
            if (isset($_POST['custom_css']) && $_POST['custom_css'])
                $_POST['custom_css'] = str_replace('\\', '¤', $_POST['custom_css']);
            foreach($this->fields_form as $form)
                foreach($form['form']['input'] as $field)
                    if(isset($field['validation']))
                    {
                        $ishtml = ($field['validation']=='isAnything') ? true : false;
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
                            Configuration::updateValue('STSN_'.strtoupper($field['name']), $value);
                        }
                        else
                            Configuration::updateValue('STSN_'.strtoupper($field['name']), $value, $ishtml);
                    }
            //
            if(Configuration::get('STSN_PRODUCT_BIG_IMAGE'))
                Configuration::updateValue('STSN_PRODUCT_SECONDARY', false);
            if(Configuration::get('STSN_NAVIGATION_PIPE'))
                Configuration::updateValue('PS_NAVIGATION_PIPE', Configuration::get('STSN_NAVIGATION_PIPE'));
            if(Configuration::get('STSN_MAIL_COLOR'))
                Configuration::updateValue('PS_MAIL_COLOR', Configuration::get('STSN_MAIL_COLOR'));
            
            $this->updateWelcome();
            $this->updateCopyright();
            $this->updateSearchLabel();
            $this->updateNewsletterLabel();
            $this->updateCatePerRow();
			$this->updateConfigurableModules();

            Configuration::updateValue('STSN_CART_ICON', Tools::getValue('cart_icon'));
            Configuration::updateValue('STSN_WISHLIST_ICON', Tools::getValue('wishlist_icon'));
            Configuration::updateValue('STSN_COMPARE_ICON', Tools::getValue('compare_icon'));
            Configuration::updateValue('STSN_QUICK_VIEW_ICON', Tools::getValue('quick_view_icon'));
            Configuration::updateValue('STSN_VIEW_ICON', Tools::getValue('view_icon'));
                
            $bg_array = array('body','header','f_top','footer','f_secondary','f_info','new','sale', 'sold_out');
            foreach($bg_array as $v)
            {
        			if (isset($_FILES[$v.'_bg_image_field']) && isset($_FILES[$v.'_bg_image_field']['tmp_name']) && !empty($_FILES[$v.'_bg_image_field']['tmp_name'])) 
                    {
        				if ($error = ImageManager::validateUpload($_FILES[$v.'_bg_image_field'], Tools::convertBytes(ini_get('upload_max_filesize'))))
    					   $this->validation_errors[] = Tools::displayError($error);
                        else 
                        {
                            $footer_image = $this->uploadCheckAndGetName($_FILES[$v.'_bg_image_field']['name']);
                            if(!$footer_image)
                                $this->validation_errors[] = Tools::displayError('Image format not recognized');
        					if (!move_uploaded_file($_FILES[$v.'_bg_image_field']['tmp_name'], $this->local_path.'img/'.$footer_image))
        						$this->validation_errors[] = Tools::displayError('Error move uploaded file');
                            else
                            {
        					   Configuration::updateValue('STSN_'.strtoupper($v).'_BG_IMG', 'img/'.$footer_image);
                            }
        				}
        			}
            }
            
            if (isset($_FILES['footer_image_field']) && isset($_FILES['footer_image_field']['tmp_name']) && !empty($_FILES['footer_image_field']['tmp_name'])) 
            {
                if ($error = ImageManager::validateUpload($_FILES['footer_image_field'], Tools::convertBytes(ini_get('upload_max_filesize'))))
                    $this->validation_errors[] = Tools::displayError($error);
                else 
                {
                    $this->_checkEnv();
                    $footer_image = $this->uploadCheckAndGetName($_FILES['footer_image_field']['name']);
                    if(!$footer_image)
                        $this->validation_errors[] = Tools::displayError('Image format not recognized');
                    else if (!move_uploaded_file($_FILES['footer_image_field']['tmp_name'], _PS_UPLOAD_DIR_.$footer_image))
                        $this->validation_errors[] = Tools::displayError('Error move uploaded file');
                    else
                    {
                       Configuration::updateValue('STSN_FOOTER_IMG', $footer_image);
                    }
                }
            }
            if (isset($_FILES['retina_logo_image_field']) && isset($_FILES['retina_logo_image_field']['tmp_name']) && !empty($_FILES['retina_logo_image_field']['tmp_name'])) 
            {
                if ($error = ImageManager::validateUpload($_FILES['retina_logo_image_field'], Tools::convertBytes(ini_get('upload_max_filesize'))))
                    $this->validation_errors[] = Tools::displayError($error);
                else 
                {
                    $retina_logo = $this->uploadCheckAndGetName($_FILES['retina_logo_image_field']['name']);
                    if(!$retina_logo)
                        $this->validation_errors[] = Tools::displayError('Image format not recognized');
                    else if (!move_uploaded_file($_FILES['retina_logo_image_field']['tmp_name'], $this->local_path.'img/'.$retina_logo))
                        $this->validation_errors[] = Tools::displayError('Error move uploaded file');
                    else
                    {
                       Configuration::updateValue('STSN_RETINA_LOGO', 'img/'.$retina_logo);
                    }
                }
            }
            $iphone_icon_array = array('57','72','114','144');
            foreach($iphone_icon_array as $v)
            {
        			if (isset($_FILES['icon_iphone_'.$v.'_field']) && isset($_FILES['icon_iphone_'.$v.'_field']['tmp_name']) && !empty($_FILES['icon_iphone_'.$v.'_field']['tmp_name'])) 
                    {
                        $this->_checkImageDir(_PS_MODULE_DIR_.$this->name.'/img/'.$this->context->shop->id.'/');
        				if ($error = ImageManager::validateUpload($_FILES['icon_iphone_'.$v.'_field'], Tools::convertBytes(ini_get('upload_max_filesize'))))
    					   $this->validation_errors[] = Tools::displayError($error);
                        else 
                        {
        					if (!move_uploaded_file($_FILES['icon_iphone_'.$v.'_field']['tmp_name'], $this->local_path.'img/'.$this->context->shop->id.'/touch-icon-iphone-'.$v.'.png'))
        						$this->validation_errors[] = Tools::displayError('Error move uploaded file');
                            else
                            {
        					   Configuration::updateValue('STSN_ICON_IPHONE_'.strtoupper($v), 'img/'.$this->context->shop->id.'/touch-icon-iphone-'.$v.'.png');
                            }
        				}
        			}
            }   
            
            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else
            {
                $this->writeCss();
                $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
            } 
        }
        
        if (Tools::isSubmit('deleteimagestthemeeditor'))
        {
            if($identi = Tools::getValue('identi'))
            {
                $identi = strtoupper($identi);
                $image  = Configuration::get('STSN_'.$identi);
            	if (Configuration::get('STSN_'.$identi))
                    if (file_exists(_PS_UPLOAD_DIR_.$image))
		                @unlink(_PS_UPLOAD_DIR_.$image);
                    elseif(file_exists($this->_path.$image))
                        @unlink($this->_path.$image);
            	Configuration::updateValue('STSN_'.$identi, '');
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=7&ref='.(int)Tools::getValue('ref').'&token='.Tools::getAdminTokenLite('AdminModules'));  
             }else
                $this->_html .= $this->displayError($this->l('An error occurred while delete banner.'));
        }
        $this->initDropListGroup();
		$helper = $this->initForm();
        return $this->_html.$this->initToolbarBtn().'<div class="tabbable row stthemeeditor">'.$this->initTab().'<div id="stthemeeditor" class="col-xs-12 col-lg-10 tab-content">'.$helper->generateForm($this->fields_form).'</div></div>';
	}
    
    public function initDropListGroup()
    {
        $this->fields_form[0]['form']['input']['hometab_pro_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer(4));
        $this->fields_form[1]['form']['input']['categories_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer(6));
        $this->fields_form[1]['form']['input']['category_pro_per_1']['name'] = $this->BuildDropListGroup($this->findCateProPer(1));
        $this->fields_form[1]['form']['input']['category_pro_per_2']['name'] = $this->BuildDropListGroup($this->findCateProPer(2));
        $this->fields_form[1]['form']['input']['category_pro_per_3']['name'] = $this->BuildDropListGroup($this->findCateProPer(3));
        $this->fields_form[11]['form']['input']['cs_pro_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer(7));
        $this->fields_form[12]['form']['input']['pc_pro_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer(8));
        $this->fields_form[13]['form']['input']['ac_pro_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer(9));
        $this->fields_form[16]['form']['input']['packitems_pro_per_0']['name'] = $this->BuildDropListGroup($this->findCateProPer(5));
        /*$this->fields_form[16]['form']['input']['pro_image_column']['name'] = $this->BuildDropListGroup($this->findCateProPer(10,1,11));
        $this->fields_form[16]['form']['input']['pro_primary_column']['name'] = $this->BuildDropListGroup($this->findCateProPer(11,1,11));
        $this->fields_form[16]['form']['input']['pro_secondary_column']['name'] = $this->BuildDropListGroup($this->findCateProPer(12,1,11));*/
    }
    
    public function updateWelcome() {
		$languages = Language::getLanguages(false);
		$welcome = $welcome_logged  = $welcome_link = array();
        $defaultLanguage = new Language((int)(Configuration::get('PS_LANG_DEFAULT')));
		foreach ($languages as $language)
		{
            $welcome[$language['id_lang']] = Tools::getValue('welcome_'.$language['id_lang']) ? Tools::getValue('welcome_'.$language['id_lang']) : Tools::getValue('welcome_'.$defaultLanguage->id);
			$welcome_logged[$language['id_lang']] = Tools::getValue('welcome_logged_'.$language['id_lang']) ? Tools::getValue('welcome_logged_'.$language['id_lang']) : Tools::getValue('welcome_logged_'.$defaultLanguage->id);
			$welcome_link[$language['id_lang']] = Tools::getValue('welcome_link_'.$language['id_lang']) ? Tools::getValue('welcome_link_'.$language['id_lang']) : Tools::getValue('welcome_link_'.$defaultLanguage->id);
		}
        Configuration::updateValue('STSN_WELCOME_LINK', $welcome_link);
        Configuration::updateValue('STSN_WELCOME', $welcome);
        Configuration::updateValue('STSN_WELCOME_LOGGED', $welcome_logged);
	}
    public function updateCopyright() {
		$languages = Language::getLanguages();
		$result = array();
        $defaultLanguage = new Language((int)(Configuration::get('PS_LANG_DEFAULT')));
		foreach ($languages as $language)
			$result[$language['id_lang']] = Tools::getValue('copyright_text_' . $language['id_lang']) ? Tools::getValue('copyright_text_'.$language['id_lang']) : Tools::getValue('copyright_text_'.$defaultLanguage->id);

        /*if(!$result[$defaultLanguage->id])
            $this->validation_errors[] = Tools::displayError('The field "Copyright text" is required at least in '.$defaultLanguage->name);
		else*/
            Configuration::updateValue('STSN_COPYRIGHT_TEXT', $result, true);
	}
    public function updateSearchLabel() {
		$languages = Language::getLanguages();
		$result = array();
        $defaultLanguage = new Language((int)(Configuration::get('PS_LANG_DEFAULT')));
		foreach ($languages as $language)
			$result[$language['id_lang']] = Tools::getValue('search_label_' . $language['id_lang']) ? Tools::getValue('search_label_' . $language['id_lang']) : Tools::getValue('search_label_'.$defaultLanguage->id);

        /*if(!$result[$defaultLanguage->id])
            $this->validation_errors[] = Tools::displayError('The field "Search label" is required at least in '.$defaultLanguage->name);
		else*/
            Configuration::updateValue('STSN_SEARCH_LABEL', $result);
	}        
    public function updateNewsletterLabel() {
        $languages = Language::getLanguages();
        $result = array();
        $defaultLanguage = new Language((int)(Configuration::get('PS_LANG_DEFAULT')));
        foreach ($languages as $language)
            $result[$language['id_lang']] = Tools::getValue('newsletter_label_' . $language['id_lang']) ? Tools::getValue('newsletter_label_' . $language['id_lang']) : Tools::getValue('newsletter_label_'.$defaultLanguage->id);

        /*if(!$result[$defaultLanguage->id])
            $this->validation_errors[] = Tools::displayError('The field "Newsletter label" is required at least in '.$defaultLanguage->name);
        else*/
            Configuration::updateValue('STSN_NEWSLETTER_LABEL', $result);
    }     
    public function updateCatePerRow() {
		$arr = $this->findCateProPer();
        foreach ($arr as $key => $value)
            foreach ($value as $v)
            {
                $gv = Tools::getValue($v['id']);
                if ($gv!==false)
                    Configuration::updateValue('STSN_'.strtoupper($v['id']), (int)$gv);
            }
	}
    public function initFieldsForm()
    {
		$this->fields_form[0]['form'] = array(
			'input' => array(
                array(
                    'type' => 'html',
                    'id' => '',
                    'label' => $this->l('One-click demo importer:'),
                    'name' => '<button type="button" id="import_export" class="btn btn-default"><i class="icon process-icon-new-module"></i> '.$this->l('Import/export').'</button>',
                ),
                array(
					'type' => 'switch',
					'label' => $this->l('Enable responsive layout:'),
					'name' => 'responsive',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'responsive_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'responsive_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('Enable responsive design for mobile devices.'),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Display switch back to desktop version link on mobile devices:'),
					'name' => 'version_switching',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'version_switching_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'version_switching_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('This option allows visitors to manually switch between mobile and desktop versions on mobile devices.'),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'radio',
					'label' => $this->l('Maximum Page Width:'),
					'name' => 'responsive_max',
					'values' => array(
						array(
							'id' => 'responsive_max_0',
							'value' => 0,
							'label' => $this->l('980')),
						array(
							'id' => 'responsive_max_1',
							'value' => 1,
							'label' => $this->l('1200')),
					),
                    'desc' => $this->l('Maximum width of the page'),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'radio',
					'label' => $this->l('Box style:'),
					'name' => 'boxstyle',
					'values' => array(
						array(
							'id' => 'boxstyle_on',
							'value' => 1,
							'label' => $this->l('Stretched style')),
						array(
							'id' => 'boxstyle_off',
							'value' => 2,
							'label' => $this->l('Boxed style')),
					),
                    'desc' => $this->l('You can change the shadow around the main content when in boxed style under the "Color" tab.'),
                    'validation' => 'isUnsignedInt',
				), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Slide left/right column:'),
                    'name' => 'slide_lr_column',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'slide_lr_column_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'slide_lr_column_off',
                            'value' => 0,
                            'label' => $this->l('NO')),
                    ),
                    'desc' => $this->l('Click the "Left"/"right" button to slide the left/right column out on mobile devices.'),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'text',
                    'label' => $this->l('Page top spacing:'),
                    'name' => 'top_spacing',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Page bottom spacing:'),
                    'name' => 'bottom_spacing',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ),
                'hometab_pro_per_0' => array(
                    'type' => 'html',
                    'id' => 'hometab_pro_per_0',
                    'label'=> $this->l('The number of columns for Homepage tab'),
                    'name' => '',
                ),
                array(
					'type' => 'switch',
					'label' => $this->l('Back to top button:'),
					'name' => 'scroll_to_top',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'scroll_to_top_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'scroll_to_top_off',
							'value' => 0,
							'label' => $this->l('NO')),
					),
                    'validation' => 'isBool',
				), 
                array(
                    'type' => 'fontello',
                    'label' => $this->l('Cart icon:'),
                    'name' => 'cart_icon',
                    'values' => $this->get_fontello(),
                ), 
                array(
                    'type' => 'fontello',
                    'label' => $this->l('Wishlist icon:'),
                    'name' => 'wishlist_icon',
                    'values' => $this->get_fontello(),
                ), 
                array(
                    'type' => 'fontello',
                    'label' => $this->l('Compare icon:'),
                    'name' => 'compare_icon',
                    'values' => $this->get_fontello(),
                ), 
                array(
                    'type' => 'fontello',
                    'label' => $this->l('Quick view icon:'),
                    'name' => 'quick_view_icon',
                    'values' => $this->get_fontello(),
                ), 
                array(
                    'type' => 'fontello',
                    'label' => $this->l('View icon:'),
                    'name' => 'view_icon',
                    'values' => $this->get_fontello(),
                ), 

                array(
    				'type' => 'select',
        			'label' => $this->l('Set the vertical right panel position on the screen:'),
        			'name' => 'position_right_panel',
                    'options' => array(
        				'query' => self::$position_right_panel,
        				'id' => 'id',
        				'name' => 'name',
        			),
                    'validation' => 'isGenericName',
    			),
                array(
					'type' => 'text',
					'label' => $this->l('Guest welcome message:'),
					'name' => 'welcome',
                    'size' => 64,
                    'lang' => true,
				),
                array(
					'type' => 'text',
					'label' => $this->l('Logged welcome message:'),
					'name' => 'welcome_logged',
                    'size' => 64,
                    'lang' => true,
				),
                array(
					'type' => 'text',
					'label' => $this->l('Add a link to welcome message:'),
					'name' => 'welcome_link',
                    'size' => 64,
                    'lang' => true,
				),
                array(
					'type' => 'textarea',
					'label' => $this->l('Copyright text:'),
					'name' => 'copyright_text',
                    'lang' => true,
					'cols' => 60,
					'rows' => 2,
				),
                array(
					'type' => 'text',
					'label' => $this->l('Search label:'),
					'name' => 'search_label',
                    'lang' => true,
				),
                array(
					'type' => 'text',
					'label' => $this->l('Newsletter label:'),
					'name' => 'newsletter_label',
                    'lang' => true,
				),
                /*
                array(
					'type' => 'color',
					'label' => $this->l('Iframe background:'),
					'name' => 'lb_bg_color',
			        'size' => 33,
                    'desc' => $this->l('Set iframe background if transparency is not allowed.'),
				),
                */
                'payment_icon' => array(
                    'type' => 'file',
                    'label' => $this->l('Payment icon:'),
                    'name' => 'footer_image_field',
                    'desc' => '',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Navigation pipe:'),
                    'name' => 'navigation_pipe',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Used for the navigation path: Store Name > Category Name > Product Name.'),
                    'validation' => 'isAnything',
                ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Mail color:'),
                    'name' => 'mail_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Custom fonts:'),
                    'name' => 'custom_fonts',
                    'class' => 'fixed-width-xxl',
                    'desc' => $this->l('Each font name has to be separated by a comma (","). Please refer to the Documenation to lear how to add custom fonts.'),
                    'validation' => 'isAnything',
                ),
			),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
		);
        
        $this->fields_form[23]['form'] = array(
            'legend' => array(
                'title' => $this->l('Products'),
            ),
            'description' => $this->l('You need to manually clear the Smarty cache after making changes here.'),
            'input' => array(
                array(
                    'type' => 'radio',
                    'label' => $this->l('Add to cart animation:'),
                    'name' => 'addtocart_animation',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'addtocart_animation_dialog',
                            'value' => 0,
                            'label' => $this->l('Pop-up dialog')),
                        array(
                            'id' => 'addtocart_animation_flying',
                            'value' => 1,
                            'label' => $this->l('Flying image to cart(Page scroll to top)')),
                        array(
                            'id' => 'addtocart_animation_flying_scroll',
                            'value' => 2,
                            'label' => $this->l('Flying image to cart')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'radio',
                    'label' => $this->l('Show fly-out buttons:'),
                    'name' => 'flyout_buttons',
                    'values' => array(
                        array(
                            'id' => 'flyout_buttons_on',
                            'value' => 1,
                            'label' => $this->l('Always')),
                        array(
                            'id' => 'flyout_buttons_off',
                            'value' => 0,
                            'label' => $this->l('Hover')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'radio',
                    'label' => $this->l('"Add to cart" button:'),
                    'name' => 'display_add_to_cart',
                    'values' => array(
                        array(
                            'id' => 'display_add_to_cart_on',
                            'value' => 1,
                            'label' => $this->l('Display the "add to cart" button below the product name when mouse hover over')),
                        array(
                            'id' => 'display_add_to_cart_always',
                            'value' => 2,
                            'label' => $this->l('Display the "add to cart" button below the product name')),
                        array(
                            'id' => 'display_add_to_cart_fly_out',
                            'value' => 0,
                            'label' => $this->l('Display the "add to cart" button in the fly-out button')),
                        array(
                            'id' => 'display_add_to_cart_off',
                            'value' => 3,
                            'label' => $this->l('Hide')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display a quantity input along with the "Add to cart" button:'),
                    'name' => 'pro_quantity_input',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'pro_quantity_input_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'pro_quantity_input_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('"View more" button:'),
                    'name' => 'use_view_more_instead',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'use_view_more_instead_on',
                            'value' => 1,
                            'label' => $this->l('Use the "View more" button instead of the "Add to cart" button')),
                        array(
                            'id' => 'use_view_more_instead_both',
                            'value' => 2,
                            'label' => $this->l('Display both the "View more" button and "Add to cart" button')),
                        array(
                            'id' => 'use_view_more_instead_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide the "Add to wishlist" button in the fly-out button:'),
                    'name' => 'flyout_wishlist',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'flyout_wishlist_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'flyout_wishlist_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),    
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide the "Quick view" button in the fly-out button:'),
                    'name' => 'flyout_quickview',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'flyout_quickview_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'flyout_quickview_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide the "Add to compare" button in the fly-out button:'),
                    'name' => 'flyout_comparison',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'flyout_comparison_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'flyout_comparison_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Retina:'),
                    'name' => 'retina',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'retina_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'retina_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'desc' => $this->l('Retina support for logo and product images.'),
                    'validation' => 'isBool',
                ), 
                'retina_logo_image_field' => array(
                    'type' => 'file',
                    'label' => $this->l('Retina logo:'),
                    'name' => 'retina_logo_image_field',
                    'desc' => $this->l('If your logo is 200x100, upload a 400x200 version of that logo.'),
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Show comment rating:'),
                    'name' => 'display_comment_rating',
                    'values' => array(
                        array(
                            'id' => 'display_comment_rating_off',
                            'value' => 0,
                            'label' => $this->l('NO')),
                        array(
                            'id' => 'display_comment_rating_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'display_comment_rating_on',
                            'value' => 3,
                            'label' => $this->l('Yes and show the number of ratings')),
                        array(
                            'id' => 'display_comment_rating_always',
                            'value' => 2,
                            'label' => $this->l('Show star even if no rating')),
                        array(
                            'id' => 'display_comment_rating_always',
                            'value' => 4,
                            'label' => $this->l('Show star even if no rating and show the number of ratings')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Yotpo Star Rating:'),
                    'name' => 'yotpo_sart',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'yotpo_sart_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'yotpo_sart_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'radio',
                    'label' => $this->l('Length of product names:'),
                    'name' => 'length_of_product_name',
                    'values' => array(
                        array(
                            'id' => 'length_of_product_name_normal',
                            'value' => 0,
                            'label' => $this->l('Normal(one line)')),
                        array(
                            'id' => 'length_of_product_name_long',
                            'value' => 1,
                            'label' => $this->l('Long(70 characters)')),
                        array(
                            'id' => 'length_of_product_name_full',
                            'value' => 2,
                            'label' => $this->l('Full name')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Zoom product images on hover:'),
                    'name' => 'pro_img_hover_scale',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'pro_img_hover_scale_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'pro_img_hover_scale_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show a shadow effect on mouseover:'),
                    'name' => 'pro_shadow_effect',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'pro_shadow_effect_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'pro_shadow_effect_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'text',
                    'label' => $this->l('H-shadow:'),
                    'name' => 'pro_h_shadow',
                    'validation' => 'isInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('The position of the horizontal shadow. Negative values are allowed.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('V-shadow:'),
                    'name' => 'pro_v_shadow',
                    'validation' => 'isInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('The position of the vertical shadow. Negative values are allowed.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('The blur distance of shadow:'),
                    'name' => 'pro_shadow_blur',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Shadow color:'),
                    'name' => 'pro_shadow_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Shadow opacity:'),
                    'name' => 'pro_shadow_opacity',
                    'validation' => 'isFloat',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('From 0.0 (fully transparent) to 1.0 (fully opaque).'),
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all   '),
            ),
        );
        $this->fields_form[1]['form'] = array(
			'input' => array(
                array(
					'type' => 'radio',
					'label' => $this->l('Default product listing:'),
					'name' => 'product_view',
					'values' => array(
						array(
							'id' => 'product_view_grid',
							'value' => 'grid_view',
							'label' => $this->l('Grid')),
						array(
							'id' => 'product_view_list',
							'value' => 'list_view',
							'label' => $this->l('List')),
					),
                    'validation' => 'isGenericName',
				),  
                array(
					'type' => 'switch',
					'label' => $this->l('Show category title on the category page:'),
					'name' => 'display_category_title',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'display_category_title_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'display_category_title_off',
							'value' => 0,
							'label' => $this->l('NO')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Show category descriptions on the category page:'),
					'name' => 'display_category_desc',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'display_category_desc_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'display_category_desc_off',
							'value' => 0,
							'label' => $this->l('NO')),
					),
                    'validation' => 'isBool',
				), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show full category descriptions on the category page:'),
                    'name' => 'display_cate_desc_full',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'display_cate_desc_full_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'display_cate_desc_full_off',
                            'value' => 0,
                            'label' => $this->l('NO')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
					'type' => 'switch',
					'label' => $this->l('Show category image on the category page:'),
					'name' => 'display_category_image',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'display_category_image_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'display_category_image_off',
							'value' => 0,
							'label' => $this->l('NO')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'radio',
					'label' => $this->l('Show subcategories:'),
					'name' => 'display_subcate',
					'values' => array(
						array(
							'id' => 'display_subcate_off',
							'value' => 0,
							'label' => $this->l('NO')),
						array(
							'id' => 'display_subcate_gird',
							'value' => 1,
							'label' => $this->l('Grid view')),
                        array(
                            'id' => 'display_subcate_gird_fullname',
                            'value' => 3,
                            'label' => $this->l('Grid view(Display full category name)')),
						array(
							'id' => 'display_subcate_list',
							'value' => 2,
							'label' => $this->l('List view')),
					),
                    'validation' => 'isUnsignedInt',
				),
                'cate_sortby_name' => array(
                    'type' => 'select',
                    'label' => $this->l('Show sort by:'),
                    'name' => 'cate_sortby_name',
                    'options' => array(
                        'query' => $this->_category_sortby,
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => '',
                            'label' => $this->l('Please select'),
                        ),
                    ),
                    'desc' => '',
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'cate_sortby',
                    'default_value' => '',
                    'validation' => 'isAnything',
                ),
                'categories_per_0' => array(
                    'type' => 'html',
                    'id' => 'categories_per_0',
                    'label'=> $this->l('Subcategories per row in grid view:'),
                    'name' => '',
                ),
                array(
					'type' => 'radio',
					'label' => $this->l('Show product attributes:'),
					'name' => 'display_pro_attr',
					'values' => array(
						array(
							'id' => 'display_pro_attr_off',
							'value' => 0,
							'label' => $this->l('NO')),
						array(
							'id' => 'display_pro_attr_all',
							'value' => 1,
							'label' => $this->l('All')),
						array(
							'id' => 'display_pro_attr_in_stock',
							'value' => 2,
							'label' => $this->l('In stock only')),
					),
                    'validation' => 'isUnsignedInt',
				), 
                array(
                    'type' => 'radio',
                    'label' => $this->l('Display each product short description in category grid view:'),
                    'name' => 'show_short_desc_on_grid',
                    'values' => array(
                        array(
                            'id' => 'show_short_desc_on_grid_off',
                            'value' => 0,
                            'label' => $this->l('NO')),
                        array(
                            'id' => 'show_short_desc_on_grid_on',
                            'value' => 1,
                            'label' => $this->l('Yes, 120 characters')),
                        array(
                            'id' => 'show_short_desc_on_grid_full',
                            'value' => 2,
                            'label' => $this->l('Yes, full short description')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show color list:'),
                    'name' => 'display_color_list',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'display_color_list_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'display_color_list_off',
                            'value' => 0,
                            'label' => $this->l('NO')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show manufacturer/brand name:'),
                    'name' => 'pro_list_display_brand_name',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'pro_list_display_brand_name_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'pro_list_display_brand_name_off',
                            'value' => 0,
                            'label' => $this->l('NO')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
					'type' => 'switch',
					'label' => $this->l('Display "Show all" button:'),
					'name' => 'category_show_all_btn',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'category_show_all_btn_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'category_show_all_btn_off',
							'value' => 0,
							'label' => $this->l('NO')),
					),
                    'validation' => 'isBool',
				),
                'category_pro_per_1' => array(
                    'type' => 'html',
                    'id' => 'category_pro_per_1',
                    'label'=> $this->l('1 column'),
                    'label'=> $this->l('The number of columns for one column products listing page'),
                    'name' => '',
                ),
                'category_pro_per_2' => array(
                    'type' => 'html',
                    'id' => 'category_pro_per_2',
                    'label'=> $this->l('The number of columns for two columns products listing page'),
                    'name' => '',
                ),
                'category_pro_per_3' => array(
                    'type' => 'html',
                    'id' => 'category_pro_per_3',
                    'label'=> $this->l('The number of columns for three columns products listing page'),
                    'name' => '',
                ),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
		);
        
        $this->fields_form[2]['form'] = array(
			'input' => array(
				 array(
					'type' => 'color',
					'label' => $this->l('Body font color:'),
					'name' => 'text_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('General links color:'),
					'name' => 'link_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('General link hover color:'),
					'name' => 'link_hover_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('General border color:'),
                    'name' => 'base_border_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Form background color:'),
                    'name' => 'form_bg_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
				 array(
					'type' => 'color',
					'label' => $this->l('Breadcrumb font color:'),
					'name' => 'breadcrumb_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Breadcrumb link hover color:'),
					'name' => 'breadcrumb_hover_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Breadcrumb width:'),
                    'name' => 'breadcrumb_width',
                    'values' => array(
                        array(
                            'id' => 'breadcrumb_width_fullwidth',
                            'value' => 0,
                            'label' => $this->l('Full width')),
                        array(
                            'id' => 'breadcrumb_width_normal',
                            'value' => 1,
                            'label' => $this->l('Boxed')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Breadcrumb background:'),
                    'name' => 'breadcrumb_bg_style',
                    'values' => array(
                        array(
                            'id' => 'breadcrumb_bg_style_gradient',
                            'value' => 0,
                            'label' => $this->l('Gradient color')),
                        array(
                            'id' => 'breadcrumb_bg_style_pure',
                            'value' => 1,
                            'label' => $this->l('Pure color')),
                        array(
                            'id' => 'breadcrumb_bg_style_none',
                            'value' => 2,
                            'label' => $this->l('None')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
				 array(
					'type' => 'color',
					'label' => $this->l('Breadcrumb background:'),
					'name' => 'breadcrumb_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Price color:'),
                    'name' => 'price_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
				 array(
					'type' => 'color',
					'label' => $this->l('Old price color:'),
					'name' => 'old_price_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Icon text color:'),
					'name' => 'icon_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Icon text hover color:'),
					'name' => 'icon_hover_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Icon background:'),
					'name' => 'icon_bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Icon hover background:'),
					'name' => 'icon_hover_bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Icon disabled text color:'),
					'name' => 'icon_disabled_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Right vertical panel border color:'),
					'name' => 'right_panel_border',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Starts color:'),
					'name' => 'starts_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),    
				 array(
					'type' => 'color',
					'label' => $this->l('Circle number color:'),
					'name' => 'circle_number_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),  
                 array(
                    'type' => 'color',
                    'label' => $this->l('Circle number background:'),
                    'name' => 'circle_number_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),        
				 array(
					'type' => 'color',
					'label' => $this->l('Buttons text color:'),
					'name' => 'btn_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Buttons text hover color:'),
					'name' => 'btn_hover_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                 
				 array(
					'type' => 'color',
					'label' => $this->l('Buttons background:'),
					'name' => 'btn_bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Buttons background hover:'),
                    'name' => 'btn_hover_bg_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
				 array(
					'type' => 'color',
					'label' => $this->l('Flyout Buttons background:'),
					'name' => 'flyout_buttons_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Primary buttons text color:'),
					'name' => 'p_btn_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Primary buttons text hover color:'),
					'name' => 'p_btn_hover_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                 
				 array(
					'type' => 'color',
					'label' => $this->l('Primary buttons background:'),
					'name' => 'p_btn_bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Primary buttons background hover:'),
					'name' => 'p_btn_hover_bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
        
        $this->fields_form[26]['form'] = array(
            'legend' => array(
                'title' => $this->l('Product sliders'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                 array(
                    'type' => 'color',
                    'label' => $this->l('Product grid hover background:'),
                    'name' => 'pro_grid_hover_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
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
            ),
            'submit' => array(
                'title' => $this->l('   Save all   '),
            ),
        );

        $this->fields_form[27]['form'] = array(
            'legend' => array(
                'title' => $this->l('Pagination'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                 array(
                    'type' => 'color',
                    'label' => $this->l('Color:'),
                    'name' => 'pagination_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Hover color:'),
                    'name' => 'pagination_color_hover',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Background:'),
                    'name' => 'pagination_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Hover background:'),
                    'name' => 'pagination_bg_hover',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Border color:'),
                    'name' => 'pagination_border',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all   '),
            ),
        );


        $this->fields_form[28]['form'] = array(
            'legend' => array(
                'title' => $this->l('Boxed style'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show a shadow effect:'),
                    'name' => 'boxed_shadow_effect',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'boxed_shadow_effect_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'boxed_shadow_effect_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'text',
                    'label' => $this->l('H-shadow:'),
                    'name' => 'boxed_h_shadow',
                    'validation' => 'isInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('The position of the horizontal shadow. Negative values are allowed.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('V-shadow:'),
                    'name' => 'boxed_v_shadow',
                    'validation' => 'isInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('The position of the vertical shadow. Negative values are allowed.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('The blur distance of shadow:'),
                    'name' => 'boxed_shadow_blur',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Shadow color:'),
                    'name' => 'boxed_shadow_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Shadow opacity:'),
                    'name' => 'boxed_shadow_opacity',
                    'validation' => 'isFloat',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('From 0.0 (fully transparent) to 1.0 (fully opaque).'),
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all   '),
            ),
        );

        $this->fields_form[3]['form'] = array(
			'input' => array(
                array(
					'type' => 'switch',
					'label' => $this->l('Latin extended support:'),
					'name' => 'font_latin_support',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'font_latin_support_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'font_latin_support_off',
							'value' => 0,
							'label' => $this->l('NO')),
					),
                    'desc' => $this->l('You have to check whether your selected fonts support Latin extended here').' :<a href="http://www.google.com/webfonts">www.google.com/webfonts</a>',
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'switch',
					'label' => $this->l('Cyrylic support:'),
					'name' => 'font_cyrillic_support',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'font_cyrillic_support_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'font_cyrillic_support_off',
							'value' => 0,
							'label' => $this->l('NO')),
					),
                    'desc' => $this->l('You have to check whether your selected fonts support Cyrylic here').' :<a href="http://www.google.com/webfonts">www.google.com/webfonts</a>',
                    'validation' => 'isBool',
				),  
                array(
					'type' => 'switch',
					'label' => $this->l('Vietnamese support:'),
					'name' => 'font_vietnamese',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'font_vietnamese_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'font_vietnamese_off',
							'value' => 0,
							'label' => $this->l('NO')),
					),
                    'desc' => $this->l('You have to check whether your selected fonts support Vietnamese here').' :<a href="http://www.google.com/webfonts">www.google.com/webfonts</a>',
                    'validation' => 'isBool',
				),  
                array(
					'type' => 'switch',
					'label' => $this->l('Greek support:'),
					'name' => 'font_greek_support',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'font_greek_support_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'font_greek_support_off',
							'value' => 0,
							'label' => $this->l('NO')),
					),
                    'desc' => $this->l('You have to check whether your selected fonts support Greek here').' :<a href="http://www.google.com/webfonts">www.google.com/webfonts</a>',
                    'validation' => 'isBool',
				), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Arabic support:'),
                    'name' => 'font_arabic_support',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'font_arabic_support_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'font_arabic_support_off',
                            'value' => 0,
                            'label' => $this->l('NO')),
                    ),
                    'desc' => $this->l('You have to check whether your selected fonts support Arabic here').' :<a href="http://www.google.com/webfonts">www.google.com/webfonts</a>',
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Body font:'),
                    'name' => 'font_text_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="font_text_list_example" class="fontshow">Home Fashion</p>',
                ),
                'font_text'=>array(
                    'type' => 'select',
                    'label' => $this->l('Body font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'font_text',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Body font size:'),
                    'name' => 'font_body_size',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ), 
				array(
                    'type' => 'select',
                    'label' => $this->l('Heading font:'),
                    'name' => 'font_heading_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="font_heading_list_example" class="fontshow">Sample heading</p>',
                ),
                'font_heading'=>array(
                    'type' => 'select',
                    'label' => $this->l('Heading font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'font_heading',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
				array(
					'type' => 'color',
					'label' => $this->l('Block heading color:'),
					'name' => 'block_headings_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			    ),
				array(
					'type' => 'color',
					'label' => $this->l('Heading color:'),
					'name' => 'headings_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			    ),
                array(
					'type' => 'text',
					'label' => $this->l('Headings font size:'),
					'name' => 'font_heading_size',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				), 
                array(
					'type' => 'text',
					'label' => $this->l('Footer headings font size:'),
					'name' => 'footer_heading_size',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				), 
                array(
					'type' => 'select',
        			'label' => $this->l('Headings transform:'),
        			'name' => 'font_heading_trans',
                    'options' => array(
        				'query' => self::$textTransform,
        				'id' => 'id',
        				'name' => 'name',
        			),
                    'validation' => 'isUnsignedInt',
				),
                array(
                    'type' => 'select',
                    'label' => $this->l('Price font:'),
                    'name' => 'font_price_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="font_price_list_example" class="fontshow">$12345.67890</p>',
                ),
                'font_price'=>array(
                    'type' => 'select',
                    'label' => $this->l('Price font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'font_price',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
                array(
					'type' => 'text',
					'label' => $this->l('Price font size:'),
					'name' => 'font_price_size',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				),
                array(
					'type' => 'text',
					'label' => $this->l('Old price font size:'),
					'name' => 'font_old_price_size',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				),
				
                array(
                    'type' => 'select',
                    'label' => $this->l('Add to cart button font:'),
                    'name' => 'font_cart_btn_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="font_cart_btn_list_example" class="fontshow">Add to cart</p>',
                ),
                'font_cart_btn'=>array(
                    'type' => 'select',
                    'label' => $this->l('Add to cart button font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'font_cart_btn',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
        
        $this->fields_form[19]['form'] = array(
            'legend' => array(
                'title' => $this->l('Top-bar'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                 array(
                    'type' => 'color',
                    'label' => $this->l('Topbar text color:'),
                    'name' => 'header_text_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Topbar link color:'),
                    'name' => 'header_link_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Topbar link hover color:'),
                    'name' => 'header_link_hover_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Topbar link hover background:'),
                    'name' => 'header_link_hover_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Top bar background:'),
                    'name' => 'header_topbar_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Top bar background opacity:'),
                    'name' => 'header_topbar_opacity',
                    'validation' => 'isFloat',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('From 0.0 (fully transparent) to 1.0 (fully opaque).'),
                ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Top bar divider color:'),
                    'name' => 'header_topbar_sep',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Dropdown text hover color:'),
                    'name' => 'dropdown_hover_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Dropdown background hover:'),
                    'name' => 'dropdown_bg_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Topbar height:'),
                    'name' => 'topbar_height',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Number of width must be greater than 18'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Border height:'),
                    'name' => 'topbar_border',
                    'options' => array(
                        'query' => self::$border_style_map,
                        'id' => 'id',
                        'name' => 'name',
                        'defaul_value' => 0,
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('border color:'),
                    'name' => 'topbar_border_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all   '),
            ),
        );
        $this->fields_form[4]['form'] = array(
            'legend' => array(
                'title' => $this->l('Header'),
                'icon' => 'icon-cogs'
            ),
			'input' => array(
                array(
                    'type' => 'radio',
                    'label' => $this->l('Logo position:'),
                    'name' => 'logo_position',
                    'values' => array(
                        array(
                            'id' => 'logo_position_left',
                            'value' => 0,
                            'label' => $this->l('Left')),
                        array(
                            'id' => 'logo_position_center',
                            'value' => 1,
                            'label' => $this->l('Center')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'select',
                    'label' => $this->l('Logo area width:'),
                    'name' => 'logo_width',
                    'options' => array(
                        'query' => self::$logo_width_map,
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => 4,
                            'label' => '4/12',
                        ),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                'logo_height' => array(
                    'type' => 'text',
                    'label' => $this->l('Header height:'),
                    'name' => 'logo_height',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => array(
                        $this->l('This option makes it possible to change the height of header.'),
                        $this->l('If the height of your logo is bigger than 86px then you will need to fill out this filed.'),
                        $this->l('Please make sure the value is lagger than the height of your logo. Currently the logo height is ').Configuration::get('SHOP_LOGO_HEIGHT'),
                        $this->l('Only for logo center.')
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Header padding:'),
                    'name' => 'header_padding',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('This option is used to increase the height of header.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Bottom spacing:'),
                    'name' => 'header_bottom_spacing',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ),
                 array(
					'type' => 'select',
        			'label' => $this->l('Select a pattern number:'),
        			'name' => 'header_bg_pattern',
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
				'header_bg_image_field' => array(
					'type' => 'file',
					'label' => $this->l('Upload your own pattern as background image:'),
					'name' => 'header_bg_image_field',
                    'desc' => '',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Repeat:'),
					'name' => 'header_bg_repeat',
					'values' => array(
						array(
							'id' => 'header_bg_repeat_xy',
							'value' => 0,
							'label' => $this->l('Repeat xy')),
						array(
							'id' => 'header_bg_repeat_x',
							'value' => 1,
							'label' => $this->l('Repeat x')),
						array(
							'id' => 'header_bg_repeat_y',
							'value' => 2,
							'label' => $this->l('Repeat y')),
						array(
							'id' => 'header_bg_repeat_no',
							'value' => 3,
							'label' => $this->l('No repeat')),
					),
                    'validation' => 'isUnsignedInt',
				), 
                array(
					'type' => 'radio',
					'label' => $this->l('Position:'),
					'name' => 'header_bg_position',
					'values' => array(
						array(
							'id' => 'header_bg_repeat_left',
							'value' => 0,
							'label' => $this->l('Left')),
						array(
							'id' => 'header_bg_repeat_center',
							'value' => 1,
							'label' => $this->l('Center')),
						array(
							'id' => 'header_bg_repeat_right',
							'value' => 2,
							'label' => $this->l('Right')),
					),
                    'validation' => 'isUnsignedInt',
				),
                array(
                    'type' => 'select',
                    'label' => $this->l('Header text transform:'),
                    'name' => 'header_text_trans',
                    'options' => array(
                        'query' => self::$textTransform,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Text color:'),
                    'name' => 'header_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Link hover color:'),
                    'name' => 'header_link_hover',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
				 array(
					'type' => 'color',
					'label' => $this->l('Background color:'),
					'name' => 'header_bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Container background color:'),
                    'name' => 'header_con_bg_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
				 array(
					'type' => 'color',
					'label' => $this->l('The background of the most top of the page(Above the top bar):'),
					'name' => 'display_banner_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );

        $this->fields_form[25]['form'] = array(
            'legend' => array(
                'title' => $this->l('Sticky header/menu'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'radio',
                    'label' => $this->l('Sticky:'),
                    'name' => 'adv_menu_sticky',
                    'values' => array(
                        array(
                            'id' => 'adv_menu_sticky_no',
                            'value' => 0,
                            'label' => $this->l('No')),
                        array(
                            'id' => 'adv_menu_sticky_menu',
                            'value' => 1,
                            'label' => $this->l('Sticky advanced megamenu')),
                        array(
                            'id' => 'adv_menu_sticky_menu_animation',
                            'value' => 2,
                            'label' => $this->l('Sticky advanced megamenu(with animation)')),
                        array(
                            'id' => 'adv_menu_sticky_header',
                            'value' => 3,
                            'label' => $this->l('Sticky header')),
                        array(
                            'id' => 'adv_menu_sticky_header_animation',
                            'value' => 4,
                            'label' => $this->l('Sticky header(with animation)')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),  
                array(
                    'type' => 'switch',
                    'label' => $this->l('Transparent header:'),
                    'name' => 'transparent_header',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'transparent_header_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'transparent_header_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),  
                 array(
                    'type' => 'color',
                    'label' => $this->l('Sticky header/menu background:'),
                    'name' => 'adv_menu_sticky_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Sticky header/menu background opacity:'),
                    'name' => 'adv_menu_sticky_opacity',
                    'validation' => 'isFloat',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('From 0.0 (fully transparent) to 1.0 (fully opaque).'),
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all   '),
            ),
        );
        $this->fields_form[24]['form'] = array(
            'legend' => array(
                'title' => $this->l('Mobile header'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'radio',
                    'label' => $this->l('Mobile header:'),
                    'name' => 'sticky_mobile_header',
                    'values' => array(
                        array(
                            'id' => 'sticky_mobile_header_no_center',
                            'value' => 0,
                            'label' => $this->l('Logo center')),
                        array(
                            'id' => 'sticky_mobile_header_no_left',
                            'value' => 1,
                            'label' => $this->l('Logo left')),
                        array(
                            'id' => 'sticky_mobile_header_yes_center',
                            'value' => 2,
                            'label' => $this->l('Sticky, logo center')),
                        array(
                            'id' => 'sticky_mobile_header_yes_left',
                            'value' => 3,
                            'label' => $this->l('Sticky, logo left')),
                    ),
                    'validation' => 'isUnsignedInt',
                    'desc' => $this->l('If you choose the "Logo left" or "Sticky, logo left", you have to transplant the "Advanced megamenu" module or the "Megamenu" to the displayMobileBar hook to make the menu icon show up on mobile devices.'),
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Use mobile header:'),
                    'name' => 'use_mobile_header',
                    'values' => array(
                        array(
                            'id' => 'use_mobile_header_small_devices',
                            'value' => 0,
                            'label' => $this->l('Small devices(Screen width < 992px)')),
                        array(
                            'id' => 'use_mobile_header_mobile',
                            'value' => 1,
                            'label' => $this->l('All mobile devices(Android phone and tablet, iPhone, iPad)')),
                        array(
                            'id' => 'use_mobile_header_all',
                            'value' => 2,
                            'label' => $this->l('All devices, mobile and desktop devices')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display a text "menu" along with the menu icon on mobile version:'),
                    'name' => 'menu_icon_with_text',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'menu_icon_with_text_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'menu_icon_with_text_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),  
                array(
                    'type' => 'text',
                    'label' => $this->l('Mobile header height:'),
                    'name' => 'sticky_mobile_header_height',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Text color:'),
                    'name' => 'sticky_mobile_header_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Background color:'),
                    'name' => 'sticky_mobile_header_background',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Background color opacity:'),
                    'name' => 'sticky_mobile_header_background_opacity',
                    'validation' => 'isFloat',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('From 0.0 (fully transparent) to 1.0 (fully opaque).'),
                ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Side bar background color:'),
                    'name' => 'side_bar_background',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Transparent mobile header:'),
                    'name' => 'transparent_mobile_header',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'transparent_mobile_header_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'transparent_mobile_header_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),  
                array(
                    'type' => 'color',
                    'label' => $this->l('Transparent header text color:'),
                    'name' => 'transparent_mobile_header_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Transparent header background:'),
                    'name' => 'transparent_mobile_header_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Transparent header background opacity:'),
                    'name' => 'transparent_mobile_header_opacity',
                    'validation' => 'isFloat',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('From 0.0 (fully transparent) to 1.0 (fully opaque).'),
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all   '),
            ),
        );

        $this->fields_form[20]['form'] = array(
            'legend' => array(
                'title' => $this->l('Advanced megamenu'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'radio',
                    'label' => $this->l('Megamenu position:'),
                    'name' => 'adv_megamenu_position',
                    'values' => array(
                        array(
                            'id' => 'adv_megamenu_position_left',
                            'value' => 0,
                            'label' => $this->l('Left')),
                        array(
                            'id' => 'adv_megamenu_position_center',
                            'value' => 1,
                            'label' => $this->l('Center')),
                        array(
                            'id' => 'adv_megamenu_position_right',
                            'value' => 2,
                            'label' => $this->l('Right')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Megamenu width:'),
                    'name' => 'adv_megamenu_width',
                    'values' => array(
                        array(
                            'id' => 'adv_megamenu_width_normal',
                            'value' => 0,
                            'label' => $this->l('Boxed')),
                        array(
                            'id' => 'adv_megamenu_width_fullwidth',
                            'value' => 1,
                            'label' => $this->l('Full width')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide the "title" text of menu items when mouse over:'),
                    'name' => 'adv_menu_title',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'adv_menu_title_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'adv_menu_title_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'radio',
                    'label' => $this->l('How do submenus appear:'),
                    'name' => 'adv_submemus_animation',
                    'values' => array(
                        array(
                            'id' => 'adv_submemus_animation_fadein',
                            'value' => 0,
                            'label' => $this->l('Slide in')),
                        array(
                            'id' => 'adv_submemus_animation_slidedown',
                            'value' => 1,
                            'label' => $this->l('Slide down')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Menu height:'),
                    'name' => 'adv_st_menu_height',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'desc' => 'The value of this field should be greater than 22',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Menu text transform:'),
                    'name' => 'adv_font_menu_trans',
                    'options' => array(
                        'query' => self::$textTransform,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Menu bottom border color:'),
                    'name' => 'adv_menu_bottom_border_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Menu bottom border color when mouse hovers over:'),
                    'name' => 'adv_menu_bottom_border_hover_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'text',
                    'label' => $this->l('The height of menu bottom border:'),
                    'name' => 'adv_menu_bottom_border',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Main menu color:'),
                    'name' => 'adv_menu_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Main menu hover color:'),
                    'name' => 'adv_menu_hover_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Main menu background:'),
                    'name' => 'adv_menu_bg_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Main menu hover background:'),
                    'name' => 'adv_menu_hover_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'select',
                    'label' => $this->l('Main menu font:'),
                    'name' => 'adv_font_menu_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="adv_font_menu_list_example" class="fontshow">Home Fashion</p>',
                ),
                'adv_font_menu'=>array(
                    'type' => 'select',
                    'label' => $this->l('Main menu font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'adv_font_menu',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Main menu font size:'),
                    'name' => 'adv_font_menu_size',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('2nd level color:'),
                    'name' => 'adv_second_menu_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('2nd level hover color:'),
                    'name' => 'adv_second_menu_hover_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'select',
                    'label' => $this->l('2nd level font:'),
                    'name' => 'adv_second_font_menu_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="adv_second_font_menu_list_example" class="fontshow">Home Fashion</p>',
                ),
                'adv_second_font_menu'=>array(
                    'type' => 'select',
                    'label' => $this->l('2nd level font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'adv_second_font_menu',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('2nd level font size:'),
                    'name' => 'adv_second_font_menu_size',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('3rd level color:'),
                    'name' => 'adv_third_menu_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('3rd level hover color:'),
                    'name' => 'adv_third_menu_hover_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'select',
                    'label' => $this->l('3rd level font:'),
                    'name' => 'adv_third_font_menu_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="adv_third_font_menu_list_example" class="fontshow">Home Fashion</p>',
                ),
                'adv_third_font_menu'=>array(
                    'type' => 'select',
                    'label' => $this->l('3rd level font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'adv_third_font_menu',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('3rd level font size:'),
                    'name' => 'adv_third_font_menu_size',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all   '),
            ),
        );
        $this->fields_form[51]['form'] = array(
            'legend' => array(
                'title' => $this->l('Mobile menu'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'color',
                    'label' => $this->l('Links color on mobile version:'),
                    'name' => 'adv_menu_mob_items1_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Background color on mobile version:'),
                    'name' => 'adv_menu_mob_items1_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('2nd level color on mobile version:'),
                    'name' => 'adv_menu_mob_items2_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('2nd level background color on mobile version:'),
                    'name' => 'adv_menu_mob_items2_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('3rd level color on mobile version:'),
                    'name' => 'adv_menu_mob_items3_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('3rd level background color on mobile version:'),
                    'name' => 'adv_menu_mob_items3_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all   '),
            ),
        );
        $this->fields_form[52]['form'] = array(
            'legend' => array(
                'title' => $this->l('Multi level menu'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'color',
                    'label' => $this->l('Sub menus background:'),
                    'name' => 'adv_menu_multi_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Sub menus hover background:'),
                    'name' => 'adv_menu_multi_bg_hover',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all   '),
            ),
        );
        $this->fields_form[53]['form'] = array(
            'legend' => array(
                'title' => $this->l('Dropdown vertical menu'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(                
                array(
                    'type' => 'switch',
                    'label' => $this->l('Automatically open the menu on homepage:'),
                    'name' => 'adv_menu_ver_open',
                    'is_bool' => true,
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'adv_menu_ver_open_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'adv_menu_ver_open_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('How to show sub menus:'),
                    'name' => 'adv_menu_ver_sub_style',
                    'values' => array(
                        array(
                            'id' => 'adv_menu_ver_sub_style_1',
                            'value' => 0,
                            'label' => $this->l('Normal')),
                        array(
                            'id' => 'adv_menu_ver_sub_style_2',
                            'value' => 1,
                            'label' => $this->l('Sub menus align to the top and have the same height as the vertical menu.')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'text',
                    'label' => $this->l('Width of the vertical menu title:'),
                    'name' => 'adv_menu_ver_title_width',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Vertical menu title alignment:'),
                    'name' => 'adv_menu_ver_title_align',
                    'values' => array(
                        array(
                            'id' => 'adv_menu_ver_title_align_left',
                            'value' => 0,
                            'label' => $this->l('Left')),
                        array(
                            'id' => 'adv_menu_ver_title_align_center',
                            'value' => 1,
                            'label' => $this->l('Center')),
                        array(
                            'id' => 'adv_menu_ver_title_align_right',
                            'value' => 2,
                            'label' => $this->l('Right')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'color',
                    'label' => $this->l('Vertical menu title color:'),
                    'name' => 'adv_menu_ver_title',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Vertical menu title hover color:'),
                    'name' => 'adv_menu_ver_hover_title',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Vertical menu title background:'),
                    'name' => 'adv_menu_ver_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Vertical menu title hover background:'),
                    'name' => 'adv_menu_ver_hover_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                 array(
                    'type' => 'select',
                    'label' => $this->l('Vertical menu items font:'),
                    'name' => 'adv_ver_font_menu_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="adv_ver_font_menu_list_example" class="fontshow">Home Fashion</p>',
                ),
                'adv_ver_font_menu'=>array(
                    'type' => 'select',
                    'label' => $this->l('Vertical menu items font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'adv_ver_font_menu',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Vertical menu items font size:'),
                    'name' => 'adv_ver_font_menu_size',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Vertical menu items color:'),
                    'name' => 'adv_menu_ver_item_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Vertical menu items background:'),
                    'name' => 'adv_menu_ver_item_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Vertical menu items hover color:'),
                    'name' => 'adv_menu_ver_item_hover_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Vertical menu items hover background:'),
                    'name' => 'adv_menu_ver_item_hover_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all   '),
            ),
        );

        $this->fields_form[21]['form'] = array(
            'legend' => array(
                'title' => $this->l('Left/right column menu'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Menu font:'),
                    'name' => 'c_menu_font_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="c_menu_font_list_example" class="fontshow">Home Fashion</p>',
                ),
                'c_menu_font'=>array(
                    'type' => 'select',
                    'label' => $this->l('Menu font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'c_menu_font',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Menu font size:'),
                    'name' => 'c_menu_font_size',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Menu text transform:'),
                    'name' => 'c_menu_font_trans',
                    'options' => array(
                        'query' => self::$textTransform,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Menu color:'),
                    'name' => 'c_menu_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Menu hover color:'),
                    'name' => 'c_menu_hover_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Menu background:'),
                    'name' => 'c_menu_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Menu hover background:'),
                    'name' => 'c_menu_hover_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Menu border color:'),
                    'name' => 'c_menu_border_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Heading color:'),
                    'name' => 'c_menu_title_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Heading background:'),
                    'name' => 'c_menu_title_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                   'type' => 'color',
                   'label' => $this->l('Menu block background:'),
                   'name' => 'c_menu_bg_color',
                   'class' => 'color',
                   'size' => 20,
                   'validation' => 'isColor',
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Save all   '),
            ),
        );
        
        $this->fields_form[5]['form'] = array(
			'input' => array(
                array(
                    'type' => 'radio',
                    'label' => $this->l('Sticky Menu:'),
                    'name' => 'sticky_menu',
                    'values' => array(
                        array(
                            'id' => 'sticky_menu_no',
                            'value' => 0,
                            'label' => $this->l('No')),
                        array(
                            'id' => 'sticky_menu_menu',
                            'value' => 1,
                            'label' => $this->l('Sticky menu')),
                        array(
                            'id' => 'sticky_menu_menu_animation',
                            'value' => 2,
                            'label' => $this->l('Sticky menu(with animation)')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),  
                array(
					'type' => 'radio',
					'label' => $this->l('Megamenu position:'),
					'name' => 'megamenu_position',
					'values' => array(
						array(
							'id' => 'megamenu_position_left',
							'value' => 0,
							'label' => $this->l('Left')),
						array(
							'id' => 'megamenu_position_center',
							'value' => 1,
							'label' => $this->l('Center')),
						array(
							'id' => 'megamenu_position_right',
							'value' => 2,
							'label' => $this->l('Right')),
					),
                    'validation' => 'isUnsignedInt',
				), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide the "title" text of menu items when mouse over:'),
                    'name' => 'menu_title',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'menu_title_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'menu_title_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'radio',
                    'label' => $this->l('How do submenus appear:'),
                    'name' => 'submemus_animation',
                    'values' => array(
                        array(
                            'id' => 'submemus_animation_fadein',
                            'value' => 0,
                            'label' => $this->l('Slide in')),
                        array(
                            'id' => 'submemus_animation_slidedown',
                            'value' => 1,
                            'label' => $this->l('Slide down')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Menu font:'),
                    'name' => 'font_menu_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="font_menu_list_example" class="fontshow">Home Fashion</p>',
                ),
                'font_menu'=>array(
                    'type' => 'select',
                    'label' => $this->l('Menu font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'font_menu',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
                array(
					'type' => 'text',
					'label' => $this->l('Menu font size:'),
					'name' => 'font_menu_size',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				),
                array(
					'type' => 'select',
        			'label' => $this->l('Menu text transform:'),
        			'name' => 'font_menu_trans',
                    'options' => array(
        				'query' => self::$textTransform,
        				'id' => 'id',
        				'name' => 'name',
        			),
                    'validation' => 'isUnsignedInt',
				),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Megamenu width:'),
                    'name' => 'megamenu_width',
                    'values' => array(
                        array(
                            'id' => 'megamenu_width_normal',
                            'value' => 0,
                            'label' => $this->l('Boxed')),
                        array(
                            'id' => 'megamenu_width_fullwidth',
                            'value' => 1,
                            'label' => $this->l('Full width')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
				 array(
					'type' => 'color',
					'label' => $this->l('Menu background:'),
					'name' => 'menu_bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Sticky menu background:'),
                    'name' => 'sticky_menu_bg',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
				 array(
					'type' => 'color',
					'label' => $this->l('Main menu color:'),
					'name' => 'menu_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Main menu hover color:'),
					'name' => 'menu_hover_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Main menu hover background:'),
					'name' => 'menu_hover_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('2nd level color:'),
					'name' => 'second_menu_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('2nd level hover color:'),
					'name' => 'second_menu_hover_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('3rd level color:'),
					'name' => 'third_menu_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('3rd level hover color:'),
					'name' => 'third_menu_hover_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Links color on mobile version:'),
					'name' => 'menu_mob_items1_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('2nd level color on mobile version:'),
					'name' => 'menu_mob_items2_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('3rd level color on mobile version:'),
					'name' => 'menu_mob_items3_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Background color on mobile version:'),
					'name' => 'menu_mob_items1_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('nrd level background color on mobile version:'),
					'name' => 'menu_mob_items2_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('3rd level background color on mobile version:'),
					'name' => 'menu_mob_items3_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
        
        $this->fields_form[6]['form'] = array(
			'input' => array(
                array(
					'type' => 'select',
        			'label' => $this->l('Select a pattern number:'),
        			'name' => 'body_bg_pattern',
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
				'body_bg_image_field' => array(
					'type' => 'file',
					'label' => $this->l('Upload your own pattern as background image:'),
					'name' => 'body_bg_image_field',
                    'desc' => '',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Repeat:'),
					'name' => 'body_bg_repeat',
					'values' => array(
						array(
							'id' => 'body_bg_repeat_xy',
							'value' => 0,
							'label' => $this->l('Repeat xy')),
						array(
							'id' => 'body_bg_repeat_x',
							'value' => 1,
							'label' => $this->l('Repeat x')),
						array(
							'id' => 'body_bg_repeat_y',
							'value' => 2,
							'label' => $this->l('Repeat y')),
						array(
							'id' => 'body_bg_repeat_no',
							'value' => 3,
							'label' => $this->l('No repeat')),
					),
                    'validation' => 'isUnsignedInt',
				), 
                array(
					'type' => 'radio',
					'label' => $this->l('Position:'),
					'name' => 'body_bg_position',
					'values' => array(
						array(
							'id' => 'body_bg_repeat_left',
							'value' => 0,
							'label' => $this->l('Left')),
						array(
							'id' => 'body_bg_repeat_center',
							'value' => 1,
							'label' => $this->l('Center')),
						array(
							'id' => 'body_bg_repeat_right',
							'value' => 2,
							'label' => $this->l('Right')),
					),
                    'validation' => 'isUnsignedInt',
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Fixed background:'),
					'name' => 'body_bg_fixed',
					'is_bool' => true,
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'body_bg_fixed_on',
							'value' => 1,
							'label' => $this->l('Yes')),
                        array(
                            'id' => 'body_bg_fixed_off',
                            'value' => 0,
                            'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Scale the background image:'),
                    'name' => 'body_bg_cover',
                    'is_bool' => true,
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'body_bg_cover_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'body_bg_cover_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'desc' => $this->l('Scale the background image to be as large as possible so that the window is completely covered by the background image. Some parts of the background image may not be in view within the window.'),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Body background color:'),
                    'name' => 'body_bg_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Main content area background color:'),
                    'name' => 'body_con_bg_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
				array(
					'type' => 'color',
					'label' => $this->l('Inner main content area background color:'),
					'name' => 'main_con_bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
			),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
		);
        
        
        $this->fields_form[7]['form'] = array(
			'input' => array(
                 array(
					'type' => 'select',
        			'label' => $this->l('Select a pattern number:'),
        			'name' => 'f_top_bg_pattern',
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
				'f_top_bg_image_field' => array(
					'type' => 'file',
					'label' => $this->l('Upload your own pattern as background image:'),
					'name' => 'f_top_bg_image_field',
                    'desc' => '',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Repeat:'),
					'name' => 'f_top_bg_repeat',
					'values' => array(
						array(
							'id' => 'f_top_bg_repeat_xy',
							'value' => 0,
							'label' => $this->l('Repeat xy')),
						array(
							'id' => 'f_top_bg_repeat_x',
							'value' => 1,
							'label' => $this->l('Repeat x')),
						array(
							'id' => 'f_top_bg_repeat_y',
							'value' => 2,
							'label' => $this->l('Repeat y')),
						array(
							'id' => 'f_top_bg_repeat_no',
							'value' => 3,
							'label' => $this->l('No repeat')),
					),
                    'validation' => 'isUnsignedInt',
				), 
                array(
					'type' => 'radio',
					'label' => $this->l('Position:'),
					'name' => 'f_top_bg_position',
					'values' => array(
						array(
							'id' => 'f_top_bg_repeat_left',
							'value' => 0,
							'label' => $this->l('Left')),
						array(
							'id' => 'f_top_bg_repeat_center',
							'value' => 1,
							'label' => $this->l('Center')),
						array(
							'id' => 'f_top_bg_repeat_right',
							'value' => 2,
							'label' => $this->l('Right')),
					),
                    'validation' => 'isUnsignedInt',
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Fixed background:'),
                    'name' => 'f_top_bg_fixed',
                    'is_bool' => true,
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'f_top_bg_fixed_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'f_top_bg_fixed_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Heading alignment:'),
                    'name' => 'f_top_h_align',
                    'values' => array(
                        array(
                            'id' => 'f_top_h_align_left',
                            'value' => 0,
                            'label' => $this->l('Left')),
                        array(
                            'id' => 'f_top_h_align_center',
                            'value' => 1,
                            'label' => $this->l('Center')),
                        array(
                            'id' => 'f_top_h_align_right',
                            'value' => 2,
                            'label' => $this->l('Right')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
				array(
					'type' => 'color',
					'label' => $this->l('Heading color:'),
					'name' => 'f_top_h_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			    ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Border height:'),
                    'name' => 'footer_top_border',
                    'options' => array(
                        'query' => self::$border_style_map,
                        'id' => 'id',
                        'name' => 'name',
                        'defaul_value' => 0,
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('border color:'),
                    'name' => 'footer_top_border_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
				 array(
					'type' => 'color',
					'label' => $this->l('Background color:'),
					'name' => 'footer_top_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Container background color:'),
					'name' => 'footer_top_con_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
        
        $this->fields_form[8]['form'] = array(
			'input' => array(
				 array(
					'type' => 'color',
					'label' => $this->l('Font color:'),
					'name' => 'footer_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Links color:'),
					'name' => 'footer_link_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Links hover color:'),
					'name' => 'footer_link_hover_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                 array(
					'type' => 'select',
        			'label' => $this->l('Select a pattern number:'),
        			'name' => 'footer_bg_pattern',
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
				'footer_bg_image_field' => array(
					'type' => 'file',
					'label' => $this->l('Upload your own pattern as background image:'),
					'name' => 'footer_bg_image_field',
                    'desc' => '',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Repeat:'),
					'name' => 'footer_bg_repeat',
					'values' => array(
						array(
							'id' => 'footer_bg_repeat_xy',
							'value' => 0,
							'label' => $this->l('Repeat xy')),
						array(
							'id' => 'footer_bg_repeat_x',
							'value' => 1,
							'label' => $this->l('Repeat x')),
						array(
							'id' => 'footer_bg_repeat_y',
							'value' => 2,
							'label' => $this->l('Repeat y')),
						array(
							'id' => 'footer_bg_repeat_no',
							'value' => 3,
							'label' => $this->l('No repeat')),
					),
                    'validation' => 'isUnsignedInt',
				), 
                array(
					'type' => 'radio',
					'label' => $this->l('Position:'),
					'name' => 'footer_bg_position',
					'values' => array(
						array(
							'id' => 'footer_bg_repeat_left',
							'value' => 0,
							'label' => $this->l('Left')),
						array(
							'id' => 'footer_bg_repeat_center',
							'value' => 1,
							'label' => $this->l('Center')),
						array(
							'id' => 'footer_bg_repeat_right',
							'value' => 2,
							'label' => $this->l('Right')),
					),
                    'validation' => 'isUnsignedInt',
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Fixed background:'),
                    'name' => 'footer_bg_fixed',
                    'is_bool' => true,
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'footer_bg_fixed_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'footer_bg_fixed_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Heading alignment:'),
                    'name' => 'footer_h_align',
                    'values' => array(
                        array(
                            'id' => 'footer_h_align_left',
                            'value' => 0,
                            'label' => $this->l('Left')),
                        array(
                            'id' => 'footer_h_align_center',
                            'value' => 1,
                            'label' => $this->l('Center')),
                        array(
                            'id' => 'footer_h_align_right',
                            'value' => 2,
                            'label' => $this->l('Right')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
				array(
					'type' => 'color',
					'label' => $this->l('Heading color:'),
					'name' => 'footer_h_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			    ),
				 array(
					'type' => 'color',
					'label' => $this->l('Background color:'),
					'name' => 'footer_bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Container background color:'),
					'name' => 'footer_con_bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Border height:'),
                    'name' => 'footer_border',
                    'options' => array(
                        'query' => self::$border_style_map,
                        'id' => 'id',
                        'name' => 'name',
                        'defaul_value' => 0,
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('border color:'),
                    'name' => 'footer_border_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
        
        $this->fields_form[9]['form'] = array(
			'input' => array(
                 array(
					'type' => 'select',
        			'label' => $this->l('Select a pattern number:'),
        			'name' => 'f_secondary_bg_pattern',
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
				'f_secondary_bg_image_field' => array(
					'type' => 'file',
					'label' => $this->l('Upload your own pattern as background image:'),
					'name' => 'f_secondary_bg_image_field',
                    'desc' => '',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Repeat:'),
					'name' => 'f_secondary_bg_repeat',
					'values' => array(
						array(
							'id' => 'f_secondary_bg_repeat_xy',
							'value' => 0,
							'label' => $this->l('Repeat xy')),
						array(
							'id' => 'f_secondary_bg_repeat_x',
							'value' => 1,
							'label' => $this->l('Repeat x')),
						array(
							'id' => 'f_secondary_bg_repeat_y',
							'value' => 2,
							'label' => $this->l('Repeat y')),
						array(
							'id' => 'f_secondary_bg_repeat_no',
							'value' => 3,
							'label' => $this->l('No repeat')),
					),
                    'validation' => 'isUnsignedInt',
				), 
                array(
					'type' => 'radio',
					'label' => $this->l('Position:'),
					'name' => 'f_secondary_bg_position',
					'values' => array(
						array(
							'id' => 'f_secondary_bg_repeat_left',
							'value' => 0,
							'label' => $this->l('Left')),
						array(
							'id' => 'f_secondary_bg_repeat_center',
							'value' => 1,
							'label' => $this->l('Center')),
						array(
							'id' => 'f_secondary_bg_repeat_right',
							'value' => 2,
							'label' => $this->l('Right')),
					),
                    'validation' => 'isUnsignedInt',
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Fixed background:'),
                    'name' => 'f_secondary_bg_fixed',
                    'is_bool' => true,
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'f_secondary_bg_fixed_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'f_secondary_bg_fixed_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Heading alignment:'),
                    'name' => 'f_secondary_h_align',
                    'values' => array(
                        array(
                            'id' => 'f_secondary_h_align_left',
                            'value' => 0,
                            'label' => $this->l('Left')),
                        array(
                            'id' => 'f_secondary_h_align_center',
                            'value' => 1,
                            'label' => $this->l('Center')),
                        array(
                            'id' => 'f_secondary_h_align_right',
                            'value' => 2,
                            'label' => $this->l('Right')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
				array(
					'type' => 'color',
					'label' => $this->l('Heading color:'),
					'name' => 'f_secondary_h_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			    ),
				 array(
					'type' => 'color',
					'label' => $this->l('Background color:'),
					'name' => 'footer_secondary_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Container background color:'),
					'name' => 'footer_secondary_con_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Border height:'),
                    'name' => 'footer_secondary_border',
                    'options' => array(
                        'query' => self::$border_style_map,
                        'id' => 'id',
                        'name' => 'name',
                        'defaul_value' => 0,
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('border color:'),
                    'name' => 'footer_secondary_border_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
        
        $this->fields_form[10]['form'] = array(
			'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Center layout:'),
                    'name' => 'f_info_center',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'f_info_center_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'f_info_center_off',
                            'value' => 0,
                            'label' => $this->l('NO')),
                    ),
                    'validation' => 'isBool',
                ), 
                 array(
					'type' => 'select',
        			'label' => $this->l('Select a pattern number:'),
        			'name' => 'f_info_bg_pattern',
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
				'f_info_bg_image_field' => array(
					'type' => 'file',
					'label' => $this->l('Upload your own pattern as background image:'),
					'name' => 'f_info_bg_image_field',
                    'desc' => '',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Repeat:'),
					'name' => 'f_info_bg_repeat',
					'values' => array(
						array(
							'id' => 'f_info_bg_repeat_xy',
							'value' => 0,
							'label' => $this->l('Repeat xy')),
						array(
							'id' => 'f_info_bg_repeat_x',
							'value' => 1,
							'label' => $this->l('Repeat x')),
						array(
							'id' => 'f_info_bg_repeat_y',
							'value' => 2,
							'label' => $this->l('Repeat y')),
						array(
							'id' => 'f_info_bg_repeat_no',
							'value' => 3,
							'label' => $this->l('No repeat')),
					),
                    'validation' => 'isUnsignedInt',
				), 
                array(
					'type' => 'radio',
					'label' => $this->l('Position:'),
					'name' => 'f_info_bg_position',
					'values' => array(
						array(
							'id' => 'f_info_bg_repeat_left',
							'value' => 0,
							'label' => $this->l('Left')),
						array(
							'id' => 'f_info_bg_repeat_center',
							'value' => 1,
							'label' => $this->l('Center')),
						array(
							'id' => 'f_info_bg_repeat_right',
							'value' => 2,
							'label' => $this->l('Right')),
					),
                    'validation' => 'isUnsignedInt',
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Fixed background:'),
                    'name' => 'f_info_bg_fixed',
                    'is_bool' => true,
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'f_info_bg_fixed_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'f_info_bg_fixed_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
				 array(
					'type' => 'color',
					'label' => $this->l('Background color:'),
					'name' => 'footer_info_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Container background color:'),
					'name' => 'footer_info_con_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Color:'),
                    'name' => 'second_footer_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
				 array(
					'type' => 'color',
					'label' => $this->l('Link hover color:'),
					'name' => 'second_footer_link_hover_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Border height:'),
                    'name' => 'footer_info_border',
                    'options' => array(
                        'query' => self::$border_style_map,
                        'id' => 'id',
                        'name' => 'name',
                        'defaul_value' => 0,
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('border color:'),
                    'name' => 'footer_info_border_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
        
        $this->fields_form[11]['form'] = array(
			'legend' => array(
				'title' => $this->l('Cross selling'),
			),
			'input' => array(
                'cs_pro_per_0' => array(
                    'type' => 'html',
                    'id' => 'cs_pro_per_0',
                    'label'=> $this->l('The number of columns'),
                    'name' => '',
                ),
                array(
					'type' => 'switch',
					'label' => $this->l('Autoplay:'),
					'name' => 'cs_slideshow',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'cs_slide_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'cs_slide_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'text',
					'label' => $this->l('Time:'),
					'name' => 'cs_s_speed',
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Transition period:'),
					'name' => 'cs_a_speed',
                    'desc' => $this->l('The period, in milliseconds, of the transition effect.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Pause On Hover:'),
					'name' => 'cs_pause_on_hover',
                    'default_value' => 1,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'cs_pause_on_hover_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'cs_pause_on_hover_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				),
                array(
					'type' => 'select',
        			'label' => $this->l('Easing method:'),
        			'name' => 'cs_easing',
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
					'name' => 'cs_loop',
                    'default_value' => 0,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'cs_loop_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'cs_loop_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('"No" if you want to perform the animation once; "Yes" to loop the animation'),
                    'validation' => 'isBool',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Move:'),
					'name' => 'cs_move',
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'cs_move_on',
							'value' => 1,
							'label' => $this->l('1 item')),
						array(
							'id' => 'cs_move_off',
							'value' => 0,
							'label' => $this->l('All visible items')),
					),
                    'validation' => 'isBool',
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Remove heading background:'),
                    'name' => 'cs_title_no_bg',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'cs_title_no_bg_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'cs_title_no_bg_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
        
        
        $this->fields_form[12]['form'] = array(
			'legend' => array(
				'title' => $this->l('Products category'),
			),
			'input' => array(
               'pc_pro_per_0' => array(
                    'type' => 'html',
                    'id' => 'pc_pro_per_0',
                    'label'=> $this->l('The number of columns'),
                    'name' => '',
                ),
                array(
					'type' => 'switch',
					'label' => $this->l('Autoplay:'),
					'name' => 'pc_slideshow',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'pc_slide_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'pc_slide_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'text',
					'label' => $this->l('Time:'),
					'name' => 'pc_s_speed',
                    'default_value' => 7000,
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Transition period:'),
					'name' => 'pc_a_speed',
                    'default_value' => 400,
                    'desc' => $this->l('The period, in milliseconds, of the transition effect.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Pause On Hover:'),
                    'name' => 'pc_pause_on_hover',
                    'default_value' => 1,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'pc_pause_on_hover_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'pc_pause_on_hover_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
                array(
					'type' => 'select',
        			'label' => $this->l('Easing method:'),
        			'name' => 'pc_easing',
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
					'name' => 'pc_loop',
                    'default_value' => 0,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'pc_loop_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'pc_loop_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('"No" if you want to perform the animation once; "Yes" to loop the animation'),
                    'validation' => 'isBool',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Move:'),
					'name' => 'pc_move',
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'pc_move_on',
							'value' => 1,
							'label' => $this->l('1 item')),
						array(
							'id' => 'pc_move_off',
							'value' => 0,
							'label' => $this->l('All visible items')),
					),
                    'validation' => 'isBool',
				),
                
                array(
                    'type' => 'switch',
                    'label' => $this->l('Remove heading background:'),
                    'name' => 'pc_title_no_bg',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'pc_title_no_bg_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'pc_title_no_bg_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
        
        $this->fields_form[13]['form'] = array(
			'legend' => array(
				'title' => $this->l('Accessories'),
			),
			'input' => array(
                'ac_pro_per_0' => array(
                    'type' => 'html',
                    'id' => 'ac_pro_per_0',
                    'label'=> $this->l('The number of columns'),
                    'name' => '',
                ),
                array(
					'type' => 'switch',
					'label' => $this->l('Autoplay:'),
					'name' => 'ac_slideshow',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'ac_slide_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'ac_slide_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'text',
					'label' => $this->l('Time:'),
					'name' => 'ac_s_speed',
                    'default_value' => 7000,
                    'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'text',
					'label' => $this->l('Transition period:'),
					'name' => 'ac_a_speed',
                    'default_value' => 400,
                    'desc' => $this->l('The period, in milliseconds, of the transition effect.'),
                    'validation' => 'isUnsignedInt',
                    'class' => 'fixed-width-sm'
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Pause On Hover:'),
					'name' => 'ac_pause_on_hover',
                    'default_value' => 1,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'ac_pause_on_hover_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'ac_pause_on_hover_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'validation' => 'isBool',
				),
                array(
					'type' => 'select',
        			'label' => $this->l('Easing method:'),
        			'name' => 'ac_easing',
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
					'name' => 'ac_loop',
                    'default_value' => 0,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'ac_loop_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'ac_loop_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
                    'desc' => $this->l('"No" if you want to perform the animation once; "Yes" to loop the animation'),
                    'validation' => 'isBool',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Move:'),
					'name' => 'ac_move',
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'ac_move_on',
							'value' => 1,
							'label' => $this->l('1 item')),
						array(
							'id' => 'ac_move_off',
							'value' => 0,
							'label' => $this->l('All visible items')),
					),
                    'validation' => 'isBool',
				),
                
                array(
                    'type' => 'switch',
                    'label' => $this->l('Remove heading background:'),
                    'name' => 'ac_title_no_bg',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'ac_title_no_bg_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'ac_title_no_bg_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
        
        $this->fields_form[14]['form'] = array(
			'input' => array(
                array(
					'type' => 'textarea',
					'label' => $this->l('Custom CSS Code:'),
					'name' => 'custom_css',
					'cols' => 80,
					'rows' => 20,
                    'desc' => $this->l('Override css with your custom code'),
                    'validation' => 'isAnything',
				),
                array(
					'type' => 'textarea',
					'label' => $this->l('Custom JAVASCRIPT Code:'),
					'name' => 'custom_js',
					'cols' => 80,
					'rows' => 20,
                    'desc' => $this->l('Override js with your custom code'),
                    'validation' => 'isAnything',
				),
                array(
					'type' => 'textarea',
					'label' => $this->l('Tracking code:'),
					'name' => 'tracking_code',
					'cols' => 80,
					'rows' => 20,
                    'validation' => 'isAnything',
                    'desc' => $this->l('Code added here is injected before the closing body tag on every page in your site. Turn off the "Use HTMLPurifier Library" setting on the Preferences > General page if you want to put html codes into this field.'),
				),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Head code:'),
                    'name' => 'head_code',
                    'cols' => 80,
                    'rows' => 20,
                    'desc' => $this->l('Code added here is injected into the head tag on every page in your site. Turn off the "Use HTMLPurifier Library" setting on the Preferences > General page if you want to put html tags into this field.'),
                    'validation' => 'isAnything',
                ),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
        
        $this->fields_form[15]['form'] = array(
			'input' => array(
                array(
					'type' => 'radio',
					'label' => $this->l('How to display "New" stickers:'),
					'name' => 'new_style',
					'values' => array(
						array(
							'id' => 'new_style_flag',
							'value' => 0,
							'label' => $this->l('Flag')),
                        array(
                            'id' => 'new_style_circle',
                            'value' => 1,
                            'label' => $this->l('Circle')),
                        array(
                            'id' => 'new_style_rectangle',
                            'value' => 3,
                            'label' => $this->l('Rectangle')),
                        array(
                            'id' => 'new_style_none',
                            'value' => 2,
                            'label' => $this->l('NO')),
					),
                    'validation' => 'isUnsignedInt',
				), 
				 array(
					'type' => 'color',
					'label' => $this->l('New stickers color:'),
					'name' => 'new_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('New stickers background color:'),
					'name' => 'new_bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				'new_bg_image_field' => array(
					'type' => 'file',
					'label' => $this->l('New stickers background image(only for circle stickers):'),
					'name' => 'new_bg_image_field',
                    'desc' => '',
				),
                array(
					'type' => 'text',
					'label' => $this->l('New stickers width:'),
					'name' => 'new_stickers_width',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				),
                array(
					'type' => 'text',
					'label' => $this->l('New stickers top position:'),
					'name' => 'new_stickers_top',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				),
                array(
					'type' => 'text',
					'label' => $this->l('New stickers right position:'),
					'name' => 'new_stickers_right',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('How to display "On sale" stickers:'),
					'name' => 'sale_style',
					'values' => array(
						array(
							'id' => 'sale_style_flag',
							'value' => 0,
							'label' => $this->l('Flag')),
                        array(
                            'id' => 'sale_style_circle',
                            'value' => 1,
                            'label' => $this->l('Circle')),
						array(
							'id' => 'sale_style_rectangle',
							'value' => 3,
							'label' => $this->l('Rectangle')),
                        array(
                            'id' => 'sale_style_none',
                            'value' => 2,
                            'label' => $this->l('NO')),
					),
                    'validation' => 'isUnsignedInt',
				), 
				 array(
					'type' => 'color',
					'label' => $this->l('On sale stickers color:'),
					'name' => 'sale_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('On sale stickers background color:'),
					'name' => 'sale_bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),   
				'sale_bg_image_field' => array(
					'type' => 'file',
					'label' => $this->l('On sale image sticker(only for circle stickers):'),
					'name' => 'sale_bg_image_field',
                    'desc' => '',
				),
                array(
					'type' => 'text',
					'label' => $this->l('On sale stickers width:'),
					'name' => 'sale_stickers_width',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				),
                array(
					'type' => 'text',
					'label' => $this->l('On sale stickers top position:'),
					'name' => 'sale_stickers_top',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				),
                array(
					'type' => 'text',
					'label' => $this->l('On sale stickers left position:'),
					'name' => 'sale_stickers_left',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Show price drop percentage/amount:'),
					'name' => 'discount_percentage',
					'values' => array(
						array(
							'id' => 'discount_percentage_off',
							'value' => 0,
							'label' => $this->l('No')),
						array(
							'id' => 'discount_percentage_text',
							'value' => 1,
							'label' => $this->l('Text')),
						array(
							'id' => 'discount_percentage_sticker',
							'value' => 2,
							'label' => $this->l('Sticker')),
					),
                    'validation' => 'isUnsignedInt',
				), 
				 array(
					'type' => 'color',
					'label' => $this->l('Price drop stickers text color:'),
					'name' => 'price_drop_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Price drop stickers border color:'),
					'name' => 'price_drop_border_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
				 array(
					'type' => 'color',
					'label' => $this->l('Price drop stickers background color:'),
					'name' => 'price_drop_bg_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			     ),
                array(
					'type' => 'text',
					'label' => $this->l('Price drop stickers bottom position:'),
					'name' => 'price_drop_bottom',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				),
                array(
					'type' => 'text',
					'label' => $this->l('Price drop stickers right position:'),
					'name' => 'price_drop_right',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				),  
                array(
					'type' => 'text',
					'label' => $this->l('Price drop stickers width:'),
					'name' => 'price_drop_width',
                    'validation' => 'isUnsignedInt',
                    'desc' => $this->l('Number of width must be greater than 28'),
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
				),  

                array(
                    'type' => 'radio',
                    'label' => $this->l('Sold out stickers:'),
                    'name' => 'sold_out',
                    'values' => array(
                        array(
                            'id' => 'sold_out_off',
                            'value' => 0,
                            'label' => $this->l('Normal')),
                        array(
                            'id' => 'sold_out_text',
                            'value' => 1,
                            'label' => $this->l('Text')),
                        array(
                            'id' => 'sold_out_sticker',
                            'value' => 2,
                            'label' => $this->l('Image')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'color',
                    'label' => $this->l('Sold out stickers text color:'),
                    'name' => 'sold_out_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Sold out stickers background color:'),
                    'name' => 'sold_out_bg_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                'sold_out_bg_image_field' => array(
                    'type' => 'file',
                    'label' => $this->l('Sold out stickers sticker image:'),
                    'name' => 'sold_out_bg_image_field',
                    'desc' => '',
                ),

            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
        
        
        $this->fields_form[16]['form'] = array(
			'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable big image:'),
                    'name' => 'product_big_image',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'product_big_image_on',
                            'value' => 1,
                            'label' => $this->l('Enable')),
                        array(
                            'id' => 'product_big_image_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')),
                    ),
                    'desc' => $this->l('If you set this option to YES, "Show product secondary column" option will be disabled automatically'),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show product secondary column:'),
                    'name' => 'product_secondary',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'product_secondary_on',
                            'value' => 1,
                            'label' => $this->l('Enable')),
                        array(
                            'id' => 'product_secondary_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'select',
                    'label' => $this->l('Main product name:'),
                    'name' => 'font_product_name_list',
                    'onchange' => 'handle_font_change(this);',
                    'options' => array(
                        'optiongroup' => array (
                            'query' => $this->fontOptions(),
                            'label' => 'name'
                        ),
                        'options' => array (
                            'query' => 'query',
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'default' => array(
                            'value' => 0,
                            'label' => $this->l('Use default')
                        ),
                    ),
                    'desc' => '<p id="font_product_name_list_example" class="fontshow">Sample heading</p>',
                ),
                'font_product_name'=>array(
                    'type' => 'select',
                    'label' => $this->l('Main product name font weight:'),
                    'onchange' => 'handle_font_style(this);',
                    'class' => 'fontOptions',
                    'name' => 'font_product_name',
                    'options' => array(
                        'query' => array(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isAnything',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Main product name font size:'),
                    'name' => 'font_product_name_size',
                    'validation' => 'isUnsignedInt',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                ), 
                array(
                    'type' => 'select',
                    'label' => $this->l('Main product name transform:'),
                    'name' => 'font_product_name_trans',
                    'options' => array(
                        'query' => self::$textTransform,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Main product name color:'),
                    'name' => 'font_product_name_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                /*'pro_image_column' => array(
                    'type' => 'html',
                    'id' => 'pro_image_column',
                    'label'=> $this->l('Image column width'),
                    'name' => '',
                    'desc' => $this->l('The default image type of the main product image is "large_default". When the image column width is larger that 4, "big_default" image type will be applied.'),
                ),
                'pro_primary_column' => array(
                    'type' => 'html',
                    'id' => 'pro_primary_column',
                    'label'=> $this->l('Primary column width'),
                    'name' => '',
                    'desc' => $this->l('Sum of the three columns has to be equal 12, for example: 4 + 5 + 3, or 6 + 6 + 0.'),
                ),
                'pro_secondary_column' => array(
                    'type' => 'html',
                    'id' => 'pro_secondary_column',
                    'label'=> $this->l('Secondary column width'),
                    'name' => '',
                    'desc' => $this->l('You can set them to 0 to hide the secondary column.'),
                ),*/
                array(
                    'type' => 'radio',
                    'label' => $this->l('How to display thumbnail images:'),
                    'name' => 'pro_thumbnails',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'pro_thumbnails_slider',
                            'value' => 0,
                            'label' => $this->l('Slider')),
                        array(
                            'id' => 'pro_thumbnails_list',
                            'value' => 1,
                            'label' => $this->l('List')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
					'type' => 'radio',
					'label' => $this->l('Show brand logo on product page:'),
					'name' => 'show_brand_logo',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'show_brand_logo_off',
							'value' => 0,
							'label' => $this->l('No')),
						array(
							'id' => 'show_brand_logo_on_secondary_column',
							'value' => 1,
							'label' => $this->l('Display brand logo on the product secondary column.')),
                        array(
                            'id' => 'show_brand_logo_under_product_name',
                            'value' => 2,
                            'label' => $this->l('Display brand logo under the product name.')),
                        array(
                            'id' => 'show_brand_name_under_product_name',
                            'value' => 3,
                            'label' => $this->l('Display brand name under the product name.')),
					),
                    'desc' => $this->l('Brand logo on product secondary column'),
                    'validation' => 'isUnsignedInt',
				),  
                array(
                    'type' => 'radio',
                    'label' => $this->l('Product tabs:'),
                    'name' => 'product_tabs',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'product_tabs_normal',
                            'value' => 0,
                            'label' => $this->l('Under the product info block.')),
                        array(
                            'id' => 'product_tabs_right',
                            'value' => 1,
                            'label' => $this->l('On the right side of the main product image.')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'radio',
                    'label' => $this->l('Display product tags:'),
                    'name' => 'display_pro_tags',
                    'default_value' => 0,
                    'values' => array(
                        array(
                            'id' => 'display_pro_tags_disable',
                            'value' => 0,
                            'label' => $this->l('No')),
                        array(
                            'id' => 'display_pro_tags_as_a_tab',
                            'value' => 1,
                            'label' => $this->l('Tags tab')),
                        array(
                            'id' => 'display_pro_tags_at_bottom_of_description',
                            'value' => 2,
                            'label' => $this->l('Display tags at the bottom of the descriptions.')),
                    ),
                    'validation' => 'isUnsignedInt',
                ),
                array(
					'type' => 'radio',
					'label' => $this->l('jqZoom or Fancybox:'),
					'name' => 'zoom_type',
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'zoom_type_standrad',
							'value' => 0,
							'label' => $this->l('Standard zoom')),
                        array(
                            'id' => 'zoom_type_innerzoom',
                            'value' => 1,
                            'label' => $this->l('Inner zoom')),
                        array(
                            'id' => 'zoom_type_fancybox',
                            'value' => 2,
                            'label' => $this->l('Fancybox')),
						array(
							'id' => 'zoom_type_no',
							'value' => 3,
							'label' => $this->l('No')),
					),
                    'validation' => 'isUnsignedInt',
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display product condition:'),
                    'name' => 'display_pro_condition',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'display_pro_condition_on',
                            'value' => 1,
                            'label' => $this->l('Enable')),
                        array(
                            'id' => 'display_pro_condition_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')),
                    ),
                    'desc' => $this->l('New, used, refurbished'),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display product reference code:'),
                    'name' => 'display_pro_reference',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'display_pro_reference_on',
                            'value' => 1,
                            'label' => $this->l('Enable')),
                        array(
                            'id' => 'display_pro_reference_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
					'type' => 'switch',
					'label' => $this->l('Display tax label:'),
					'name' => 'display_tax_label',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'display_tax_label_on',
							'value' => 1,
							'label' => $this->l('Enable')),
						array(
							'id' => 'display_tax_label_off',
							'value' => 0,
							'label' => $this->l('Disabled')),
					),
                    'desc' => array(
                        $this->l('Set number of products in a row for default screen resolution(980px).'),
                        $this->l('On wide screens the number of columns will be automatically increased.'),
                    ),
                    'desc' => $this->l('In order to display the tax incl label, you need to activate taxes (Localization -> taxes -> Enable tax), make sure your country displays the label (Localization -> countries -> select your country -> display tax label) and to make sure the group of the customer is set to display price with taxes (BackOffice -> customers -> groups).'),
                    'validation' => 'isBool',
				), 
                array(
					'type' => 'radio',
					'label' => $this->l('Google rich snippets:'),
					'name' => 'google_rich_snippets',
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'google_rich_snippets_disable',
							'value' => 0,
							'label' => $this->l('Disable')),
						array(
							'id' => 'google_rich_snippets_enable',
							'value' => 1,
							'label' => $this->l('Enable')),
						array(
							'id' => 'google_rich_snippets_except_for_review_aggregate',
							'value' => 2,
							'label' => $this->l('Enable except for Review-aggregate')),
					),
                    'validation' => 'isUnsignedInt',
				),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show a print button:'),
                    'name' => 'pro_show_print_btn',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'pro_show_print_btn_on',
                            'value' => 1,
                            'label' => $this->l('Enable')),
                        array(
                            'id' => 'pro_show_print_btn_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')),
                    ),
                    'validation' => 'isBool',
                ), 
				array(
					'type' => 'color',
					'label' => $this->l('Tab color:'),
					'name' => 'pro_tab_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			    ),
				array(
					'type' => 'color',
					'label' => $this->l('Active tab color:'),
					'name' => 'pro_tab_active_color',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			    ),
				array(
					'type' => 'color',
					'label' => $this->l('Tab background:'),
					'name' => 'pro_tab_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			    ),
				array(
					'type' => 'color',
					'label' => $this->l('Active tab background:'),
					'name' => 'pro_tab_active_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			    ),
				array(
					'type' => 'color',
					'label' => $this->l('Tab content background:'),
					'name' => 'pro_tab_content_bg',
					'class' => 'color',
					'size' => 20,
                    'validation' => 'isColor',
			    ),
                'packitems_pro_per_0' => array(
                    'type' => 'html',
                    'id' => 'packitems_pro_per_0',
                    'label'=> $this->l('The number of columns for Pack items'),
                    'name' => '',
                ),
			),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
		);
        
        $inputs = array();
		foreach ($this->getConfigurableModules() as $module)
		{
			$desc = '';
			if (isset($module['is_module']) && $module['is_module'])
			{
				$module_instance = Module::getInstanceByName($module['name']);
				if (Validate::isLoadedObject($module_instance) && method_exists($module_instance, 'getContent'))
					$desc = '<a class="btn btn-default" href="'.$this->context->link->getAdminLink('AdminModules', true).'&configure='.urlencode($module_instance->name).'&tab_module='.$module_instance->tab.'&module_name='.urlencode($module_instance->name).'">'.$this->l('Configure').' <i class="icon-external-link"></i></a>';
			}
			if (isset($module['desc']) && $module['desc'])
				$desc = $desc.'<p class="help-block">'.$module['desc'].'</p>';

			$inputs[] = array(
				'type' => 'switch',
				'label' => $module['label'],
				'name' => $module['name'],
				'desc' => $desc,
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
			);
		}
        
        $this->fields_form[17]['form'] = array(
            'input' => $inputs,
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
        
        $this->fields_form[18]['form'] = array(
            'input' => array(
                'icon_iphone_57_field' => array(
					'type' => 'file',
					'label' => $this->l('Iphone/iPad Favicons 57 (PNG):'),
					'name' => 'icon_iphone_57_field',
                    'desc' => '',
				),
				'icon_iphone_72_field' => array(
					'type' => 'file',
					'label' => $this->l('Iphone/iPad Favicons 72 (PNG):'),
					'name' => 'icon_iphone_72_field',
                    'desc' => '',
				),
				'icon_iphone_114_field' => array(
					'type' => 'file',
					'label' => $this->l('Iphone/iPad Favicons 114 (PNG):'),
					'name' => 'icon_iphone_114_field',
                    'desc' => '',
				),
				'icon_iphone_144_field' => array(
					'type' => 'file',
					'label' => $this->l('Iphone/iPad Favicons 144 (PNG):'),
					'name' => 'icon_iphone_144_field',
                    'desc' => '',
				),
            ),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			),
        );
    }
	
    protected function initForm()
	{
        $footer_img = Configuration::get('STSN_FOOTER_IMG');
		if ($footer_img != "") {
		    $this->fields_form[0]['form']['input']['payment_icon']['image'] = $this->getImageHtml(($footer_img!=$this->defaults["footer_img"]['val'] ? _THEME_PROD_PIC_DIR_.$footer_img : $this->_path.$footer_img),'footer_img');
		}
        if (Configuration::get('STSN_RETINA_LOGO') != "") {
            $this->fields_form[23]['form']['input']['retina_logo_image_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_RETINA_LOGO'),'retina_logo');
        }
		if (Configuration::get('STSN_ICON_IPHONE_57') != "") {
		    $this->fields_form[18]['form']['input']['icon_iphone_57_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_ICON_IPHONE_57'),'icon_iphone_57');
		}
		if (Configuration::get('STSN_ICON_IPHONE_72') != "") {
		    $this->fields_form[18]['form']['input']['icon_iphone_72_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_ICON_IPHONE_72'),'icon_iphone_72');
		}
		if (Configuration::get('STSN_ICON_IPHONE_114') != "") {
		    $this->fields_form[18]['form']['input']['icon_iphone_114_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_ICON_IPHONE_114'),'icon_iphone_114');
		}
		if (Configuration::get('STSN_ICON_IPHONE_144') != "") {
		    $this->fields_form[18]['form']['input']['icon_iphone_144_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_ICON_IPHONE_144'),'icon_iphone_144');
		}
        
		if (Configuration::get('STSN_HEADER_BG_IMG') != "") {
		    $this->fields_form[4]['form']['input']['header_bg_image_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_HEADER_BG_IMG'), 'header_bg_img');
		}
		if (Configuration::get('STSN_BODY_BG_IMG') != "") {
		    $this->fields_form[6]['form']['input']['body_bg_image_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_BODY_BG_IMG'),'body_bg_img');
		}
		if (Configuration::get('STSN_F_TOP_BG_IMG') != "") {
		    $this->fields_form[7]['form']['input']['f_top_bg_image_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_F_TOP_BG_IMG'),'f_top_bg_img');
		}
		if (Configuration::get('STSN_FOOTER_BG_IMG') != "") {
		    $this->fields_form[8]['form']['input']['footer_bg_image_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_FOOTER_BG_IMG'),'footer_bg_img');
		}
		if (Configuration::get('STSN_F_SECONDARY_BG_IMG') != "") {
		    $this->fields_form[9]['form']['input']['f_secondary_bg_image_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_F_SECONDARY_BG_IMG'),'f_secondary_bg_img');
		}
		if (Configuration::get('STSN_F_INFO_BG_IMG') != "") {
		    $this->fields_form[10]['form']['input']['f_info_bg_image_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_F_INFO_BG_IMG'),'f_info_bg_img');
		}
		if (Configuration::get('STSN_NEW_BG_IMG') != "") {
            $this->fields_form[15]['form']['input']['new_bg_image_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_NEW_BG_IMG'),'new_bg_img');
        }
        if (Configuration::get('STSN_SALE_BG_IMG') != "") {
            $this->fields_form[15]['form']['input']['sale_bg_image_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_SALE_BG_IMG'),'sale_bg_img');
        }
        if (Configuration::get('STSN_SOLD_OUT_BG_IMG') != "") {
            $this->fields_form[15]['form']['input']['sold_out_bg_image_field']['image'] = $this->getImageHtml($this->_path.Configuration::get('STSN_SOLD_OUT_BG_IMG'),'sold_out_bg_img');
        }
        if(!Configuration::get('STSN_LOGO_POSITION'))
            $this->fields_form[4]['form']['input']['logo_height']['disabled']=true;           
        

        foreach (array('font_text'=>3, 'font_heading'=>3, 'font_price'=>3, 'font_menu'=>5, 'adv_font_menu'=>20, 'adv_second_font_menu'=>20, 'adv_third_font_menu'=>20, 'c_menu_font'=>21, 'font_cart_btn'=>3, 'font_product_name'=>16, 'adv_ver_font_menu'=>53) as $font=>$wf) {
            if ($font_menu_string = Configuration::get('STSN_'.strtoupper($font))) {
                $font_menu = explode(":", $font_menu_string);
                $font_menu = $font_menu[0];
                $font_menu_key = str_replace(' ', '_', $font_menu);
            }
            else
            {
                $font_menu_key = $font_menu = $this->_font_inherit;
            }  
            if(array_key_exists($font_menu_key, $this->googleFonts))
            {
                $font_menu_array = array(
                    $font_menu.':700' => '700',
                    $font_menu.':italic' => 'italic',
                    $font_menu.':700italic' => '700italic',
                );
                foreach ($this->googleFonts[$font_menu_key]['variants'] as $g) {
                    $font_menu_array[$font_menu.':'.$g] = $g;
                }
                foreach($font_menu_array AS $value){
                    $this->fields_form[$wf]['form']['input'][$font]['options']['query'][] = array(
                            'id'=> $font_menu.':'.($value=='regular' ? '400' : $value),
                            'name'=> $value,
                        );
                }
            }
            else
            {
                $this->fields_form[$wf]['form']['input'][$font]['options']['query'] = array(
                    array('id'=> $font_menu,'name'=>'Normal'),
                    array('id'=> $font_menu.':700','name'=>'Bold'),
                    array('id'=> $font_menu.':italic','name'=>'Italic'),
                    array('id'=> $font_menu.':700italic','name'=>'Bold & Italic'),
                );
            } 
        }
        $cate_sortby_html = '';
        if(Configuration::get('STSN_CATE_SORTBY') && ($arr = explode('¤', Configuration::get('STSN_CATE_SORTBY'))))
        {
            foreach($arr AS $value)
            {
                if (!$value)
                    continue;
                $name = '';
                foreach($this->_category_sortby AS $sortby)
                {
                    if ($sortby['id'] == $value)
                    {
                        $name = $sortby['name'];
                        break;
                    }
                }
                $cate_sortby_html .= '<li id="#'.$value.'_li" class="form-control-static"><button type="button" class="delSortby btn btn-default" name="'.$value.'"><i class="icon-remove text-danger"></i></button>&nbsp;<span>'.$name.'</span></li>';
            }
                
        }
        $this->fields_form[1]['form']['input']['cate_sortby_name']['desc'] = '<a id="add_cate_sortby" class="btn btn-default btn-block fixed-width-md" href="javascript:;">Add</a><br/><p>If you didn\'t add any items here, all items will display on the front page.</p><ul id="curr_cate_sortby">'.$cate_sortby_html.'</ul>';

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
        $helper->module = $this;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestthemeeditor';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
        
		return $helper;
	}
    
    public function fontOptions() {
        $system = $google = array();
        foreach($this->systemFonts as $v)
            $system[] = array('id'=>$v,'name'=>$v);
        foreach($this->googleFonts as $v)
            $google[] = array('id'=>$v['family'],'name'=>$v['family']);
        $module = new StThemeEditor();
        return array(
            array('name'=>$module->l('System Web fonts'),'query'=>$system),
            array('name'=>$module->l('Google Web Fonts'),'query'=>$google),
        );
    }
    public function getPatterns()
    {
        $html = '';
        foreach(range(1,25) as $v)
            $html .= '<div class="parttern_wrap" style="background:url('.$this->_path.'patterns/'.$v.'.png);"><span>'.$v.'</span></div>';
        $html .= '<div>Pattern credits:<a href="http://subtlepatterns.com" target="_blank">subtlepatterns.com</a></div>';
        return $html;
    }
    public function getPatternsArray()
    {
        $arr = array();
        for($i=1;$i<=24;$i++)
            $arr[] = array('id'=>$i,'name'=>$i); 
        return $arr;   
    }
    public function writeCss()
    {
        $id_shop = (int)Shop::getContextShopID();
        $css = $res_css = '';
        $is_responsive = Configuration::get('STSN_RESPONSIVE');
        $transparent_header = Configuration::get('STSN_TRANSPARENT_HEADER');

        $fontText = $fontHeading = $fontPrice = $fontMenu = $fontAdvancedMenu = $fontAdvancedSecondMenu = $fontAdvancedThirdMenu = $fontCMenu = $fontCartBtn = $fontProductName = $fontAdvancedVerMenu = '';
        $fontTextWeight = $fontHeadingWeight = $fontPriceWeight = $fontMenuWeight = $fontAdvancedMenuWeight = $fontAdvancedSecondMenuWeight = $fontAdvancedThirdMenuWeight = $fontCMenuWeight = $fontCartBtnWeight = $fontProductNameWeight = $fontAdvancedVerMenuWeight = '';
        $fontTextStyle = $fontHeadingStyle = $fontPriceStyle = $fontMenuStyle = $fontAdvancedMenuStyle = $fontAdvancedSecondMenuStyle = $fontAdvancedThirdMenuStyle = $fontCMenuStyle = $fontCartBtnStyle = $fontProductNameStyle = $fontAdvancedVerMenuStyle = '';

        if($fontTextString = Configuration::get('STSN_FONT_TEXT'))
        {
            preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontTextString, $fontTextArr);
            $fontText = $fontTextArr[1][0];
            $fontTextArr[2][0] && $fontTextWeight = 'font-weight:'.$fontTextArr[2][0].';';
            $fontTextArr[3][0] && $fontTextStyle = 'font-style:'.$fontTextArr[3][0].';';
        }
        if($fontHeadingString = Configuration::get('STSN_FONT_HEADING'))
        {
            preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontHeadingString, $fontHeadingArr);
            $fontHeading = $fontHeadingArr[1][0];
            $fontHeadingArr[2][0] && $fontHeadingWeight = 'font-weight:'.$fontHeadingArr[2][0].';';
            $fontHeadingArr[3][0] && $fontHeadingStyle = 'font-style:'.$fontHeadingArr[3][0].';';
        }
        if($fontProductNameString = Configuration::get('STSN_FONT_PRODUCT_NAME'))
        {
            preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontProductNameString, $fontProductNameArr);
            $fontProductName = $fontProductNameArr[1][0];
            $fontProductNameArr[2][0] && $fontProductNameWeight = 'font-weight:'.$fontProductNameArr[2][0].';';
            $fontProductNameArr[3][0] && $fontProductNameStyle = 'font-style:'.$fontProductNameArr[3][0].';';
        }
        if($fontPriceString = Configuration::get('STSN_FONT_PRICE'))
        {

            preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontPriceString, $fontPriceArr);
            $fontPrice = $fontPriceArr[1][0];
            $fontPriceArr[2][0] && $fontPriceWeight = 'font-weight:'.$fontPriceArr[2][0].';';
            $fontPriceArr[3][0] && $fontPriceStyle = 'font-style:'.$fontPriceArr[3][0].';';
        }
        if($fontMenuString = Configuration::get('STSN_FONT_MENU'))
        {
            preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontMenuString, $fontMenuArr);
            $fontMenu = $fontMenuArr[1][0];
            $fontMenuArr[2][0] && $fontMenuWeight = 'font-weight:'.$fontMenuArr[2][0].';';
            $fontMenuArr[3][0] && $fontMenuStyle = 'font-style:'.$fontMenuArr[3][0].';';
        }
        if($fontAdvancedMenuString = Configuration::get('STSN_ADV_FONT_MENU'))
        {
            preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontAdvancedMenuString, $fontAdvancedMenuArr);
            $fontAdvancedMenu = $fontAdvancedMenuArr[1][0];
            $fontAdvancedMenuArr[2][0] && $fontAdvancedMenuWeight = 'font-weight:'.$fontAdvancedMenuArr[2][0].';';
            $fontAdvancedMenuArr[3][0] && $fontAdvancedMenuStyle = 'font-style:'.$fontAdvancedMenuArr[3][0].';';
        }
        if($fontAdvancedSecondMenuString = Configuration::get('STSN_ADV_SECOND_FONT_MENU'))
        {
            preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontAdvancedSecondMenuString, $fontAdvancedSecondMenuArr);
            $fontAdvancedSecondMenu = $fontAdvancedSecondMenuArr[1][0];
            $fontAdvancedSecondMenuArr[2][0] && $fontAdvancedSecondMenuWeight = 'font-weight:'.$fontAdvancedSecondMenuArr[2][0].';';
            $fontAdvancedSecondMenuArr[3][0] && $fontAdvancedSecondMenuStyle = 'font-style:'.$fontAdvancedSecondMenuArr[3][0].';';
        }
        if($fontAdvancedThirdMenuString = Configuration::get('STSN_ADV_THIRE_FONT_MENU'))
        {
            preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontAdvancedThirdMenuString, $fontAdvancedThirdMenuArr);
            $fontAdvancedThirdMenu = $fontAdvancedThirdMenuArr[1][0];
            $fontAdvancedThirdMenuArr[2][0] && $fontAdvancedThirdMenuWeight = 'font-weight:'.$fontAdvancedThirdMenuArr[2][0].';';
            $fontAdvancedThirdMenuArr[3][0] && $fontAdvancedThirdMenuStyle = 'font-style:'.$fontAdvancedThirdMenuArr[3][0].';';
        }
        if($fontCMenuString = Configuration::get('STSN_C_MENU_FONT'))
        {
            preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontCMenuString, $fontCMenuArr);
            $fontCMenu = $fontCMenuArr[1][0];
            $fontCMenuArr[2][0] && $fontCMenuWeight = 'font-weight:'.$fontCMenuArr[2][0].';';
            $fontCMenuArr[3][0] && $fontCMenuStyle = 'font-style:'.$fontCMenuArr[3][0].';';
        }
        if($fontCartBtnString = Configuration::get('STSN_FONT_CART_BTN'))
        {
            preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontCartBtnString, $fontCartBtnArr);
            $fontCartBtn = $fontCartBtnArr[1][0];
            $fontCartBtnArr[2][0] && $fontCartBtnWeight = 'font-weight:'.$fontCartBtnArr[2][0].';';
            $fontCartBtnArr[3][0] && $fontCartBtnStyle = 'font-style:'.$fontCartBtnArr[3][0].';';
        }
        if($fontAdvancedVerMenuString = Configuration::get('STSN_ADV_VER_FONT_MENU'))
        {
            preg_match_all('/^([^:]+):?(\d*)([a-z]*)$/', $fontAdvancedVerMenuString, $fontAdvancedVerMenuArr);
            $fontAdvancedVerMenu = $fontAdvancedVerMenuArr[1][0];
            $fontAdvancedVerMenuArr[2][0] && $fontAdvancedVerMenuWeight = 'font-weight:'.$fontAdvancedVerMenuArr[2][0].';';
            $fontAdvancedVerMenuArr[3][0] && $fontAdvancedVerMenuStyle = 'font-style:'.$fontAdvancedVerMenuArr[3][0].';';
        }

        if($fontText)
           $css .='body{'.($fontText != $this->_font_inherit ? 'font-family:"'.$fontText.'", Tahoma, sans-serif, Arial;' : '').$fontTextWeight.$fontTextStyle.'}';
       if(Configuration::get('STSN_FONT_BODY_SIZE'))
            $css .='body{font-size: '.Configuration::get('STSN_FONT_BODY_SIZE').'px;}';
    	if($fontPrice)
        	$css .='.price,#our_price_display,.old_price,.sale_percentage{'.($fontPrice != $this->_font_inherit && $fontPrice != $fontText ? 'font-family:"'.$fontPrice.'", Tahoma, sans-serif, Arial;' : '').$fontPriceWeight.$fontPriceStyle.'}';
        if($fontCartBtn)
            $css .='.product_list.list .ajax_add_to_cart_button, .product_list.list .view_button,#buy_block #add_to_cart .btn_primary,#create-account_form .submit .btn_primary, #login_form .submit .btn_primary, .camera_caption_box .btn_primary, .iosSlider_text .btn_primary{'.($fontCartBtn != $this->_font_inherit && $fontCartBtn != $fontText ? 'font-family:"'.$fontCartBtn.'", Tahoma, sans-serif, Arial;' : '').''.$fontCartBtnWeight.$fontCartBtnStyle.'}';
        
        $css .= '.btn-default.btn_primary, .btn-medium.btn_primary, .btn-large.btn_primary{text-transform: '.self::$textTransform[(int)Configuration::get('STSN_FONT_HEADING_TRANS')]['name'].';}';


        $css_font_heading = $fontHeadingWeight.$fontHeadingStyle.'text-transform: '.self::$textTransform[(int)Configuration::get('STSN_FONT_HEADING_TRANS')]['name'].';'.($fontHeading != $fontText && $fontHeading != $this->_font_inherit ? 'font-family: "'.$fontHeading.'";' : '');

        $css_font_heading_size = '';
        if(Configuration::get('STSN_FONT_HEADING_SIZE'))
            $css_font_heading_size .='font-size: '.Configuration::get('STSN_FONT_HEADING_SIZE').'px;';       
            
        $css_font_menu = $css_font_mobile_menu = 'text-transform: '.self::$textTransform[(int)Configuration::get('STSN_FONT_MENU_TRANS')]['name'].';';
        if($fontMenu)
        {
            $css_font_menu .= ($this->_font_inherit != $fontMenu && $fontMenu != $fontText ? 'font-family: "'.$fontMenu.'";' : '').$fontMenuWeight.$fontMenuStyle;
            $this->_font_inherit != $fontMenu && $fontMenu != $fontText && $css_font_mobile_menu .= 'font-family: "'.$fontMenu.'";';
            $css .= '.style_wide .ma_level_1{'.($this->_font_inherit != $fontMenu && $fontMenu != $fontText ? 'font-family: "'.$fontMenu.'";' : '').$fontMenuWeight.$fontMenuStyle.'}';
        }
        if(Configuration::get('STSN_FONT_MENU_SIZE'))
            $css_font_menu .='font-size: '.Configuration::get('STSN_FONT_MENU_SIZE').'px;';

        //advanced menu
        $css_font_advanced_menu = $css_font_mobile_advanced_menu = 'text-transform: '.self::$textTransform[(int)Configuration::get('STSN_ADV_FONT_MENU_TRANS')]['name'].';';
        if($fontAdvancedMenu)
        {
            $fontAdvancedMenuOn = $this->_font_inherit != $fontAdvancedMenu && $fontAdvancedMenu != $fontText;
            $css_font_advanced_menu .= ($fontAdvancedMenuOn ? 'font-family: "'.$fontAdvancedMenu.'";' : '').$fontAdvancedMenuWeight.$fontAdvancedMenuStyle;
            $fontAdvancedMenuOn && $css_font_mobile_advanced_menu .= 'font-family: "'.$fontAdvancedMenu.'";';
            $css .= '.advanced_style_wide .advanced_ma_level_1{'.($fontAdvancedMenuOn ? 'font-family: "'.$fontAdvancedMenu.'";' : '').$fontAdvancedMenuWeight.$fontAdvancedMenuStyle.'}';
        }
        if(Configuration::get('STSN_ADV_FONT_MENU_SIZE'))
            $css_font_advanced_menu .='font-size: '.Configuration::get('STSN_ADV_FONT_MENU_SIZE').'px;';
        if($menu_height = (int)Configuration::get('STSN_ADV_ST_MENU_HEIGHT'))
        {
            $ma_level_padding = ($menu_height-36)/2;
            if($menu_height>36){
                $css .='#st_advanced_menu_wrap .advanced_ma_level_0{height: '.$menu_height.'px;padding-top: '.floor($ma_level_padding).'px;padding-bottom: '.ceil($ma_level_padding).'px;}';
                $css .= '.advanced_ma_level_0 .icon-down-dir-2{top:'.floor(($menu_height-16)/2/$menu_height*100).'%;}';
                $css .= '#main_menu_widgets{padding-top:'.floor(($menu_height-34)/2).'px;padding-bottom:'.floor(($menu_height-34)/2).'px;}';
            }
            elseif($menu_height<36){
                $css .='#st_advanced_menu_wrap .advanced_ma_level_0{height: '.$menu_height.'px;line-height: '.$menu_height.'px;}';
                $css .= '#main_menu_widgets #search_block_top{height:'.($menu_height-2).'px;}';
            }
            $css .='#st_advanced_menu_wrap .advanced_ma_level_0 .cate_label{top: '.(floor($ma_level_padding)-6).'px;}';
        }
        //Removed this code .block a.title_block, .block .title_block a, from the line below, cause that code makes heading size changes in each module do not take effect.
        $css .= '.block .title_block, .idTabs a,.product_accordion_title,.heading,.page-heading,.page-subheading,.pc_slider_tabs a, #home-page-tabs li a, #home-page-tabs li span,.product_main_name{'.$css_font_heading.$css_font_heading_size.'}';
        $css .= '#st_mega_menu .ma_level_0{'.$css_font_menu.'}'; 
        $css .= '#stmobilemenu .ma_level_0{'.$css_font_mobile_menu.'}'; 
        $css .= '.style_wide .ma_level_1{text-transform: '.self::$textTransform[(int)Configuration::get('STSN_FONT_MENU_TRANS')]['name'].';}';

        if($fontProductName)
            $css .='.product_main_name{'.($fontProductName != $this->_font_inherit && $fontProductName != $fontText ? 'font-family:"'.$fontProductName.'";' : '').$fontProductNameWeight.$fontProductNameStyle.'}';

        if(Configuration::get('STSN_FONT_PRODUCT_NAME_SIZE'))
            $css .='.product_main_name{font-size: '.Configuration::get('STSN_FONT_PRODUCT_NAME_SIZE').'px;}';
        if(Configuration::get('STSN_FONT_PRODUCT_NAME_TRANS'))
            $css .='.product_main_name{text-transform: '.self::$textTransform[(int)Configuration::get('STSN_FONT_PRODUCT_NAME_TRANS')]['name'].';}';
        if(Configuration::get('STSN_FONT_PRODUCT_NAME_COLOR'))
            $css .='.product_main_name{color: '.Configuration::get('STSN_FONT_PRODUCT_NAME_COLOR').';}';

        //advanced menu
        $css .= '#st_advanced_menu_wrap .advanced_ma_level_0, .mobile_bar_tri_text, #st_advanced_menu_column_mobile{'.$css_font_advanced_menu.'}'; 
        $css .= '#stmobileadvancedmenu .mo_advanced_ma_level_0{'.$css_font_mobile_advanced_menu.'}'; 
        $css .= '.advanced_style_wide .advanced_ma_level_1{text-transform: '.self::$textTransform[(int)Configuration::get('STSN_ADV_FONT_MENU_TRANS')]['name'].';}'; 
        
        if($fontAdvancedSecondMenu)
             $css .= '.advanced_style_wide .advanced_ma_level_1{'.($fontAdvancedSecondMenu != $fontText && $this->_font_inherit != $fontAdvancedSecondMenu ? 'font-family: "'.$fontAdvancedSecondMenu.'";' : '').$fontAdvancedSecondMenuWeight.$fontAdvancedSecondMenuStyle.'}';
        if(Configuration::get('STSN_ADV_SECOND_FONT_MENU_SIZE'))
            $css .= '.advanced_style_wide .advanced_ma_level_1{font-size: '.Configuration::get('STSN_ADV_SECOND_FONT_MENU_SIZE').'px;}';
        if($fontAdvancedThirdMenu)
             $css .= '.style_wide .mu_level_2 a.advanced_ma_item, .stmenu_multi_level a.advanced_ma_item,.mo_sub_a{'.($fontAdvancedThirdMenu != $fontText && $this->_font_inherit != $fontAdvancedThirdMenu ? 'font-family: "'.$fontAdvancedThirdMenu.'";' : '').$fontAdvancedThirdMenuWeight.$fontAdvancedThirdMenuStyle.'}';
        if(Configuration::get('STSN_ADV_THIRD_FONT_MENU_SIZE'))
            $css .= '.style_wide .mu_level_2 a.advanced_ma_item, .stmenu_multi_level a.advanced_ma_item{font-size: '.Configuration::get('STSN_ADV_THIRD_FONT_MENU_SIZE').'px;}';
        
        if($fontAdvancedVerMenu)
             $css .= '.advanced_mv_item{'.($fontAdvancedVerMenu != $fontText && $this->_font_inherit != $fontAdvancedVerMenu ? 'font-family: "'.$fontAdvancedVerMenu.'";' : '').$fontAdvancedVerMenuWeight.$fontAdvancedVerMenuStyle.'}';
        if(Configuration::get('STSN_ADV_VER_FONT_MENU_SIZE'))
            $css .= '.advanced_mv_item{font-size: '.Configuration::get('STSN_ADV_VER_FONT_MENU_SIZE').'px;}';

        $css_font_c_menu = 'text-transform: '.self::$textTransform[(int)Configuration::get('STSN_C_MENU_FONT_TRANS')]['name'].';';
        if($fontCMenu)
        {
            $css_font_c_menu .= ($this->_font_inherit != $fontCMenu && $fontCMenu != $fontText ? 'font-family: "'.$fontCMenu.'";' : '').$fontCMenuWeight.$fontCMenuStyle;
        }
        if(Configuration::get('STSN_C_MENU_FONT_SIZE'))
            $css_font_c_menu .='font-size: '.Configuration::get('STSN_C_MENU_FONT_SIZE').'px;';
        $css .='#st_advanced_menu_column_desktop .advanced_ma_level_0{'.$css_font_c_menu.';}';

        if(Configuration::get('STSN_FONT_PRICE_SIZE'))
            $css .='.price_container .price{font-size: '.Configuration::get('STSN_FONT_PRICE_SIZE').'px;}';  
        if(Configuration::get('STSN_FONT_OLD_PRICE_SIZE'))
            $css .='.price_container .old_price{font-size: '.Configuration::get('STSN_FONT_OLD_PRICE_SIZE').'px;}';     
            
        if(Configuration::get('STSN_FOOTER_HEADING_SIZE'))
            $css .='#footer .title_block{font-size: '.Configuration::get('STSN_FOOTER_HEADING_SIZE').'px;}';
            
        if(Configuration::get('STSN_BLOCK_HEADINGS_COLOR'))
            $css .='.block .title_block, .block a.title_block, .block .title_block a, #home-page-tabs li a, #home-page-tabs li span{color: '.Configuration::get('STSN_BLOCK_HEADINGS_COLOR').';}';
        if(Configuration::get('STSN_HEADINGS_COLOR'))
            $css .='.heading,.page-heading,.page-subheading, a.heading,a.page-heading,a.page-subheading,#home-page-tabs > li a{color: '.Configuration::get('STSN_HEADINGS_COLOR').';}';
            

        if(Configuration::get('STSN_F_TOP_H_COLOR'))
            $css .='#footer-top .block .title_block, #footer-top .block a.title_block, #footer-top .block .title_block a{color: '.Configuration::get('STSN_F_TOP_H_COLOR').';}';
        if(Configuration::get('STSN_F_TOP_H_ALIGN'))
            $css .= '#footer-top .title_block{ text-align: '.(Configuration::get('STSN_F_TOP_H_ALIGN')==1 ? 'center' : 'right').'; }';
        if(Configuration::get('STSN_FOOTER_H_COLOR'))
            $css .='#footer-primary .block .title_block, #footer-primary .block a.title_block, #footer-primary .block .title_block a{color: '.Configuration::get('STSN_FOOTER_H_COLOR').';}';
        if(Configuration::get('STSN_FOOTER_H_ALIGN'))
            $css .= '#footer-primary .title_block{ text-align: '.(Configuration::get('STSN_FOOTER_H_ALIGN')==1 ? 'center' : 'right').'; }';
        if(Configuration::get('STSN_F_SECONDARY_H_COLOR'))
            $css .='#footer-secondary .block .title_block, #footer-secondary .block a.title_block, #footer-secondary .block .title_block a{color: '.Configuration::get('STSN_F_SECONDARY_H_COLOR').';}';
        if(Configuration::get('STSN_F_SECONDARY_H_ALIGN'))
            $css .= '#footer-secondary .title_block{ text-align: '.(Configuration::get('STSN_F_SECONDARY_H_ALIGN')==1 ? 'center' : 'right').'; }';
            
        //color
        if(Configuration::get('STSN_TEXT_COLOR'))
            $css .='body{color: '.Configuration::get('STSN_TEXT_COLOR').';}';
        if(Configuration::get('STSN_LINK_COLOR'))
            $css .='a,div.pagination .showall .show_all_products{color: '.Configuration::get('STSN_LINK_COLOR').';}';
        if(Configuration::get('STSN_LINK_HOVER_COLOR'))
        {
            $css .='a:active,a:hover,
            #layered_block_left ul li a:hover,
            #product_comments_block_extra a:hover,
            .breadcrumb a:hover,
            a.color_666:hover,
            .pc_slider_tabs a.selected,
            #footer_info a:hover,
            .blog_info a:hover,
            .block .title_block a:hover,
            div.pagination .showall .show_all_products:hover,
            .content_sortPagiBar .display li.selected a, .content_sortPagiBar .display_m li.selected a,
            .content_sortPagiBar .display li a:hover, .content_sortPagiBar .display_m li a:hover,
            #home-page-tabs > li.active a, #home-page-tabs li a:hover,
            .fancybox-skin .fancybox-close:hover{color: '.Configuration::get('STSN_LINK_HOVER_COLOR').';}';
        }

        if(Configuration::get('STSN_PRICE_COLOR'))
            $css .='.price, #our_price_display, .sale_percentage{color: '.Configuration::get('STSN_PRICE_COLOR').';}';
        if(Configuration::get('STSN_OLD_PRICE_COLOR'))
            $css .='.old_price,#old_price_display{color: '.Configuration::get('STSN_OLD_PRICE_COLOR').';}';
        if(Configuration::get('STSN_BREADCRUMB_COLOR'))
            $css .='.breadcrumb, .breadcrumb a{color: '.Configuration::get('STSN_BREADCRUMB_COLOR').';}';
        if(Configuration::get('STSN_BREADCRUMB_HOVER_COLOR'))
            $css .='.breadcrumb a:hover{color: '.Configuration::get('STSN_BREADCRUMB_HOVER_COLOR').';}';

        $breadcrumb_bg_style=Configuration::get('STSN_BREADCRUMB_BG_STYLE');
        if($breadcrumb_bg_style==2)
            $css .='#breadcrumb_wrapper{padding:0;background:transparent;}';

        if($breadcrumb_bg_style!=2 && ($breadcrumb_bg_hex = Configuration::get('STSN_BREADCRUMB_BG')))
        {
            if($breadcrumb_bg_style==1)
            {
                $css .='#breadcrumb_wrapper{
    background: '.$breadcrumb_bg_hex.';
    background: -webkit-linear-gradient(none);
    background: -moz-linear-gradient(none);
    background: -o-linear-gradient(none);
    background: linear-gradient(none);
                    }';
            }
            else{
                $breadcrumb_bg = self::hex2rgb($breadcrumb_bg_hex);
                if(is_array($breadcrumb_bg))
                {
                    $breadcrumb_bg_star = ($breadcrumb_bg[0]-16).','.($breadcrumb_bg[1]-16).','.($breadcrumb_bg[2]-16);
                    $breadcrumb_bg_end = implode(',',$breadcrumb_bg);
                    $css .='#breadcrumb_wrapper{
    background: '.$breadcrumb_bg_hex.';
    background: -webkit-linear-gradient(top, rgb('.$breadcrumb_bg_star.') , rgb('.$breadcrumb_bg_end.') 5%, rgb('.$breadcrumb_bg_end.') 95%, rgb('.$breadcrumb_bg_star.'));
    background: -moz-linear-gradient(top, rgb('.$breadcrumb_bg_star.'), rgb('.$breadcrumb_bg_end.') 5%, rgb('.$breadcrumb_bg_end.') 95%, rgb('.$breadcrumb_bg_star.'));
    background: -o-linear-gradient(top, rgb('.$breadcrumb_bg_star.'), rgb('.$breadcrumb_bg_end.') 5%, rgb('.$breadcrumb_bg_end.') 95%, rgb('.$breadcrumb_bg_star.'));
    background: linear-gradient(top, rgb('.$breadcrumb_bg_star.'), rgb('.$breadcrumb_bg_end.') 5%, rgb('.$breadcrumb_bg_end.') 95%, rgb('.$breadcrumb_bg_star.'));
                    }';
                }
            }
        }
        
        if (Configuration::get('STSN_CS_TITLE_NO_BG'))
            $css .= '#crossselling-products_block_center .title_block, #crossselling-products_block_center .nav_top_right .flex-direction-nav, #crossselling-products_block_center .title_block span{background:none;}';
        if (Configuration::get('STSN_PC_TITLE_NO_BG'))
            $css .= '#productscategory-products_block_center .title_block, #productscategory-products_block_center .nav_top_right .flex-direction-nav, #productscategory-products_block_center .title_block span{background:none;}';
        if (Configuration::get('STSN_AC_TITLE_NO_BG'))
            $css .= '#accessories_block .title_block, #accessories_block .nav_top_right .flex-direction-nav, #accessories_block .title_block span{background:none;}';
        
        if(Configuration::get('STSN_ICON_COLOR'))
            $css .='a.icon_wrap, .icon_wrap,.shopping_cart .ajax_cart_right{color: '.Configuration::get('STSN_ICON_COLOR').';}';
        if(Configuration::get('STSN_ICON_HOVER_COLOR'))
            $css .='a.icon_wrap.active,.icon_wrap.active,a.icon_wrap:hover,.icon_wrap:hover,.searchbox_inner.active .submit_searchbox.icon_wrap,#search_block_top.quick_search_simple .searchbox_inner.active .submit_searchbox.icon_wrap, #search_block_top.quick_search_simple .searchbox_inner:hover .submit_searchbox.icon_wrap,.shopping_cart:hover .icon_wrap,.shopping_cart_style_1 .shopping_cart:hover .icon_wrap,.shopping_cart.active .icon_wrap,.myaccount-link-list a:hover .icon_wrap{color: '.Configuration::get('STSN_ICON_HOVER_COLOR').';}';
        if($icon_bg_color = Configuration::get('STSN_ICON_BG_COLOR'))
            $css .='a.icon_wrap, .icon_wrap,.shopping_cart .ajax_cart_right,#rightbar{background-color: '.$icon_bg_color.';}';    
        if($icon_hover_bg_color = Configuration::get('STSN_ICON_HOVER_BG_COLOR'))
        {
            $css .='a.icon_wrap.active,.icon_wrap.active,a.icon_wrap:hover,.icon_wrap:hover,.searchbox_inner.active .submit_searchbox.icon_wrap,#search_block_top.quick_search_simple .searchbox_inner.active .submit_searchbox.icon_wrap, #search_block_top.quick_search_simple .searchbox_inner:hover .submit_searchbox.icon_wrap,.shopping_cart:hover .icon_wrap,.shopping_cart.active .icon_wrap,.myaccount-link-list a:hover .icon_wrap{background-color: '.$icon_hover_bg_color.';}';    
            $css .='.submit_searchbox:hover,.searchbox_inner.active .search_query,.searchbox_inner.active .submit_searchbox.icon_wrap,#search_block_top.quick_search_simple .searchbox_inner.active .submit_searchbox.icon_wrap, #search_block_top.quick_search_simple .searchbox_inner:hover .submit_searchbox.icon_wrap,.shopping_cart.active .icon_wrap,.shopping_cart:hover .icon_wrap{border-color:'.$icon_hover_bg_color.';}';
        }
        if(Configuration::get('STSN_ICON_DISABLED_COLOR'))
            $css .='a.icon_wrap.disabled,.icon_wrap.disabled{color: '.Configuration::get('STSN_ICON_DISABLED_COLOR').';}';
        if(Configuration::get('STSN_RIGHT_PANEL_BORDER'))
            $css .='#rightbar,.rightbar_wrap a.icon_wrap,#to_top_wrap a.icon_wrap,#switch_left_column_wrap a.icon_wrap,#switch_right_column_wrap a.icon_wrap{border-color: '.Configuration::get('STSN_RIGHT_PANEL_BORDER').';}';
        if(Configuration::get('STSN_STARTS_COLOR'))
            $css .='div.star.star_on:after,div.star.star_hover:after,.rating_box i.light{color: '.Configuration::get('STSN_STARTS_COLOR').';}';
        if(Configuration::get('STSN_CIRCLE_NUMBER_COLOR'))
            $css .='.amount_circle{color: '.Configuration::get('STSN_CIRCLE_NUMBER_COLOR').';}';
        if(Configuration::get('STSN_CIRCLE_NUMBER_BG'))
            $css .='.amount_circle{background-color: '.Configuration::get('STSN_CIRCLE_NUMBER_BG').';}';
            
        if($percent_of_screen = Configuration::get('STSN_POSITION_RIGHT_PANEL'))
        {
            $percent_of_screen_arr = explode('_',$percent_of_screen);
            $css .='#rightbar{top:'.($percent_of_screen_arr[0]==2 ? $percent_of_screen_arr[1].'%' : 'auto').'; bottom:'.($percent_of_screen_arr[0]==1 ? $percent_of_screen_arr[1].'%' : 'auto').';}';
        }
        //button  
        $button_css = $button_hover_css = $primary_button_css = $primary_button_hover_css = '';   
        if(Configuration::get('STSN_BTN_COLOR'))   
            $button_css .='color: '.Configuration::get('STSN_BTN_COLOR').';';
        if(Configuration::get('STSN_BTN_HOVER_COLOR'))   
            $button_hover_css .='color: '.Configuration::get('STSN_BTN_HOVER_COLOR').';';
        if(Configuration::get('STSN_BTN_BG_COLOR'))   
            $button_css .='background-color: '.Configuration::get('STSN_BTN_BG_COLOR').';border-color:'.Configuration::get('STSN_BTN_BG_COLOR').';';
        if(Configuration::get('STSN_BTN_HOVER_BG_COLOR'))   
            $button_hover_css .='background-color: '.Configuration::get('STSN_BTN_HOVER_BG_COLOR').';border-color:'.Configuration::get('STSN_BTN_HOVER_BG_COLOR').';';
        if(Configuration::get('STSN_P_BTN_COLOR'))   
        {
            $primary_button_css .='color: '.Configuration::get('STSN_P_BTN_COLOR').';';
            $css .= '.hover_fly a,.hover_fly a:hover,.hover_fly a:first-child,.hover_fly a:first-child:hover{color:'.Configuration::get('STSN_P_BTN_COLOR').'!important;}.itemlist_action a.ajax_add_to_cart_button,.itemlist_action a.ajax_add_to_cart_button:hover,.itemlist_action a.view_button,.itemlist_action a.view_button:hover{color:'.Configuration::get('STSN_P_BTN_COLOR').';}';
        }
        if(Configuration::get('STSN_P_BTN_HOVER_COLOR'))   
            $primary_button_hover_css .='color: '.Configuration::get('STSN_P_BTN_HOVER_COLOR').';';
        if(Configuration::get('STSN_P_BTN_BG_COLOR'))   
        {
            $primary_button_css .='background-color: '.Configuration::get('STSN_P_BTN_BG_COLOR').';border-color:'.Configuration::get('STSN_P_BTN_BG_COLOR').';';
            $css .= '.hover_fly a:first-child{background-color: '.Configuration::get('STSN_P_BTN_BG_COLOR').';}.hover_fly a:hover{background-color: '.Configuration::get('STSN_P_BTN_BG_COLOR').'!important;}.itemlist_action a.ajax_add_to_cart_button,.itemlist_action a.ajax_add_to_cart_button:hover,.itemlist_action a.view_button,.itemlist_action a.view_button:hover{background-color: '.Configuration::get('STSN_P_BTN_BG_COLOR').';}';
        }
        if(Configuration::get('STSN_P_BTN_HOVER_BG_COLOR'))
            $primary_button_hover_css .='background-color: '.Configuration::get('STSN_P_BTN_HOVER_BG_COLOR').';border-color:'.Configuration::get('STSN_P_BTN_HOVER_BG_COLOR').';';
            
        if($button_css)
            $css .= '.btn-default, .btn-medium, .btn-large,
                input.button_mini,
                input.button_small,
                input.button,
                input.button_large,
                input.button_mini_disabled,
                input.button_small_disabled,
                input.button_disabled,
                input.button_large_disabled,
                input.exclusive_mini,
                input.exclusive_small,
                input.exclusive,
                input.exclusive_large,
                input.exclusive_mini_disabled,
                input.exclusive_small_disabled,
                input.exclusive_disabled,
                input.exclusive_large_disabled,
                a.button_mini,
                a.button_small,
                a.button,
                a.button_large,
                a.exclusive_mini,
                a.exclusive_small,
                a.exclusive,
                a.exclusive_large,
                span.button_mini,
                span.button_small,
                span.button,
                span.button_large,
                span.exclusive_mini,
                span.exclusive_small,
                span.exclusive,
                span.exclusive_large,
                span.exclusive_large_disabled,
                .itemlist_action .ajax_add_to_cart_button, 
                .itemlist_action .view_button{'.$button_css.'}';
        if($button_hover_css)
            $css .= '.btn-default:hover, .btn-default.active, 
                .btn-medium:hover, .btn-medium.active, 
                .btn-large:hover, .btn-large.active,
                input.button_mini:hover,
                input.button_small:hover,
                input.button:hover,
                input.button_large:hover,
                input.exclusive_mini:hover,
                input.exclusive_small:hover,
                input.exclusive:hover,
                input.exclusive_large:hover,
                a.button_mini:hover,
                a.button_small:hover,
                a.button:hover,
                a.button_large:hover,
                a.exclusive_mini:hover,
                a.exclusive_small:hover,
                a.exclusive:hover,
                a.exclusive_large:hover,
                input.button_mini:active,
                input.button_small:active,
                input.button:active,
                input.button_large:active,
                input.exclusive_mini:active,
                input.exclusive_small:active,
                input.exclusive:active,
                input.exclusive_large:active,
                a.button_mini:active,
                a.button_small:active,
                a.button:active,
                a.button_large:active,
                a.exclusive_mini:active,
                a.exclusive_small:active,
                a.exclusive:active,
                a.exclusive_large:active,
                .itemlist_action .ajax_add_to_cart_button:hover, 
                .itemlist_action .ajax_add_to_cart_button.active, 
                .itemlist_action .view_button:hover,
                .itemlist_action .view_button.active{'.$button_hover_css.'}';
        if($primary_button_css)
            $css .= '.product_list.list .button.ajax_add_to_cart_button, .btn-default.btn_primary, .btn-medium.btn_primary, .btn-large.btn_primary {'.$primary_button_css.'}';
        if($primary_button_hover_css)
            $css .= '.product_list.list .button.ajax_add_to_cart_button:hover,.product_list.list .button.ajax_add_to_cart_button.active,
                .itemlist_action a.ajax_add_to_cart_button:hover,.itemlist_action a.ajax_add_to_cart_button.active,
                .btn-default.btn_primary:hover, .btn-default.btn_primary.active, 
                .btn-medium.btn_primary:hover, .btn-medium.btn_primary.active, 
                .btn-large.btn_primary:hover, .btn-large.btn_primary.active{'.$primary_button_hover_css.'}';
           
        if(Configuration::get('STSN_FLYOUT_BUTTONS_BG'))   
            $css .='.hover_fly, .hover_fly a, .hover_fly:hover a:first-child{background-color: '.Configuration::get('STSN_FLYOUT_BUTTONS_BG').';}';
    
        //header
        if(Configuration::get('STSN_HEADER_TEXT_COLOR'))
            $css .='#top_bar .header_item{color:'.Configuration::get('STSN_HEADER_TEXT_COLOR').';}';
        if(Configuration::get('STSN_HEADER_LINK_COLOR'))
            $css .='#top_bar .header_item,#top_bar a.header_item, #top_bar .dropdown_tri_inner, #top_bar .shopping_cart .icon_wrap, #top_bar .shopping_cart .ajax_cart_right{color:'.Configuration::get('STSN_HEADER_LINK_COLOR').';}#top_bar .dropdown_tri_inner b{border-color: '.Configuration::get('STSN_HEADER_LINK_COLOR').' transparent transparent;}';
        if(Configuration::get('STSN_HEADER_LINK_HOVER_COLOR'))
            $css .='#top_bar a.header_item:hover,#top_bar .open .dropdown_tri_inner, #top_bar .shopping_cart_style_1 .shopping_cart:hover .icon_wrap, #top_bar .shopping_cart_style_1 .shopping_cart.active .icon_wrap, #top_bar .shopping_cart_style_1 .shopping_cart:hover .ajax_cart_right{color:'.Configuration::get('STSN_HEADER_LINK_HOVER_COLOR').';}#top_bar .open .dropdown_tri_inner b{border-color: '.Configuration::get('STSN_HEADER_LINK_HOVER_COLOR').' transparent transparent;}';
        if(Configuration::get('STSN_HEADER_LINK_HOVER_BG'))
            $css .='#top_bar a.header_item:hover,#top_bar .open .dropdown_tri_inner{background-color:'.Configuration::get('STSN_HEADER_LINK_HOVER_BG').';}';
        if(Configuration::get('STSN_DROPDOWN_HOVER_COLOR'))
            $css .='.dropdown_list li a:hover{color:'.Configuration::get('STSN_DROPDOWN_HOVER_COLOR').';}';   
        if(Configuration::get('STSN_DROPDOWN_BG_COLOR'))
            $css .='.dropdown_list li a:hover{background-color:'.Configuration::get('STSN_DROPDOWN_BG_COLOR').';}'; 
        if($header_topbar_bg = Configuration::get('STSN_HEADER_TOPBAR_BG'))
        {
            $css .='#top_bar{background-color:'.$header_topbar_bg.';}'; 
            $header_topbar_opacity = (float)Configuration::get('STSN_HEADER_TOPBAR_OPACITY');
            if($header_topbar_opacity>=0 && $header_topbar_opacity<1)
            {
                $header_topbar_bg_hex = self::hex2rgb($header_topbar_bg);
                $css .= 'body#index #page_header #top_bar{background:rgba('.$header_topbar_bg_hex[0].','.$header_topbar_bg_hex[1].','.$header_topbar_bg_hex[2].','.$header_topbar_opacity.');}';      
                $css .= 'body#index.mobile_device #page_header #top_bar{background-color:'.$header_topbar_bg.';}';
            }
        }

        if(Configuration::get('STSN_HEADER_TOPBAR_SEP'))
            $css .='#top_bar #header_user_info a, #top_bar #header_user_info span, #stsocial_list_topbar li a, #contact-link a, .shop-phone, #top_bar .dropdown_tri_inner,#top_bar .shopping_cart_style_1 .shopping_cart, .currency_selector, .language_selector{border-color:'.Configuration::get('STSN_HEADER_TOPBAR_SEP').';}'; 
        if(($topbar_height = (int)Configuration::get('STSN_TOPBAR_HEIGHT')) && $topbar_height>18)
        {
            $css .='#top_bar #header_user_info a, #top_bar #header_user_info span, #stsocial_list_topbar li a, #contact-link a, .shop-phone, #top_bar .dropdown_tri_inner, #top_bar .shopping_cart_style_1 .shopping_cart, .currency_selector, .language_selector{padding-top:'.floor(($topbar_height-18)/2).'px;padding-bottom:'.floor(($topbar_height-18)/2).'px;}';
            $css .='#page_header .blockcart_wrap .cart_block{top:'.$topbar_height.'px;}';
        }
        $footer_border = Configuration::get('STSN_TOPBAR_BORDER');
        if($footer_border>1)
            $css .='#top_bar '.($footer_border>20 ? '.container' : '').'{border-bottom-width:'.($footer_border%10).'px;border-bottom-style: solid;}';
        elseif($footer_border==1)
            $css .='#top_bar, #top_bar .container{border-bottom: none;}';
        if(Configuration::get('STSN_TOPBAR_BORDER_COLOR'))
            $css .='#top_bar, #top_bar .container{border-bottom-color:'.Configuration::get('STSN_TOPBAR_BORDER_COLOR').';}';

        if(Configuration::get('STSN_HEADER_TEXT_TRANS'))
            $css .='#page_header .header_item, #page_header .dropdown_tri_inner{text-transform: '.self::$textTransform[(int)Configuration::get('STSN_HEADER_TEXT_TRANS')]['name'].';}';
        if(Configuration::get('STSN_HEADER_COLOR'))
            $css .='#header, #header .header_item,#header a.header_item, #header .dropdown_tri_inner, #header .shopping_cart_style_1 .shopping_cart .icon_wrap, #header .shopping_cart_style_1 .shopping_cart .ajax_cart_right, #header .quick_search_simple .icon_wrap, .quick_search_simple .submit_searchbox{color:'.Configuration::get('STSN_HEADER_COLOR').';}#header .dropdown_tri_inner b{border-color: '.Configuration::get('STSN_HEADER_COLOR').' transparent transparent;}';
        if(Configuration::get('STSN_HEADER_LINK_HOVER'))
            $css .='#header a.header_item:hover,#header .open .dropdown_tri_inner, #header .shopping_cart_style_1 .shopping_cart:hover .icon_wrap, #header .shopping_cart_style_1 .shopping_cart.active .icon_wrap, #header .shopping_cart_style_1 .shopping_cart:hover .ajax_cart_right{color:'.Configuration::get('STSN_HEADER_LINK_HOVER').';}#header .open .dropdown_tri_inner b{border-color: '.Configuration::get('STSN_HEADER_LINK_HOVER').' transparent transparent;}';
        //menu
        if($menu_color = Configuration::get('STSN_MENU_COLOR'))
            $css .='.ma_level_0{color:'.$menu_color.';}#main_menu_widgets #search_block_top.quick_search_simple .button-search,#main_menu_widgets .header_item, #main_menu_widgets a.header_item, #main_menu_widgets .header_item a{color:'.$menu_color.';}'; 
        if(Configuration::get('STSN_MENU_HOVER_COLOR'))
            $css .='.sttlevel0.current .ma_level_0, .sttlevel0.active .ma_level_0{color:'.Configuration::get('STSN_MENU_HOVER_COLOR').';}'; 
        if(Configuration::get('STSN_MENU_HOVER_BG'))
            $css .='.sttlevel0.current .ma_level_0, .sttlevel0.active .ma_level_0{background-color:'.Configuration::get('STSN_MENU_HOVER_BG').';}'; 
        $menu_bg_color = Configuration::get('STSN_MENU_BG_COLOR');
        if($menu_bg_color )
        {
            if(Configuration::get('STSN_MEGAMENU_WIDTH'))
                $css .='#st_mega_menu_container{background-color:'.$menu_bg_color.';padding-bottom:0;}'; 
            else
                $css .='#st_mega_menu{background-color:'.$menu_bg_color.';}'; 
        }

        $megamenu_bg = array();
        if(Configuration::get('STSN_STICKY_MENU_BG'))
            $megamenu_bg = self::hex2rgb(Configuration::get('STSN_STICKY_MENU_BG'));
        elseif($menu_bg_color)
            $megamenu_bg = self::hex2rgb($menu_bg_color);
        if(is_array($megamenu_bg) && count($megamenu_bg))
            $css .='#st_mega_menu_container.sticky{background: '.$menu_bg_color .';background:rgba('.$megamenu_bg[0].','.$megamenu_bg[1].','.$megamenu_bg[2].',0.9);}';

        if(Configuration::get('STSN_SECOND_MENU_COLOR'))
            $css .='.ma_level_1,.stmenu_sub.style_classic .ma_level_1{color:'.Configuration::get('STSN_SECOND_MENU_COLOR').';}'; 
        if(Configuration::get('STSN_SECOND_MENU_HOVER_COLOR'))
            $css .='.ma_level_1:hover,.stmenu_sub.style_classic .show .ma_level_1{color:'.Configuration::get('STSN_SECOND_MENU_HOVER_COLOR').';}'; 
        if(Configuration::get('STSN_THIRD_MENU_COLOR'))
            $css .='.ma_level_2{color:'.Configuration::get('STSN_THIRD_MENU_COLOR').';}'; 
        if(Configuration::get('STSN_THIRD_MENU_HOVER_COLOR'))
            $css .='.ma_level_2:hover{color:'.Configuration::get('STSN_THIRD_MENU_HOVER_COLOR').';}'; 
        if(Configuration::get('STSN_MENU_MOB_ITEMS1_COLOR'))
            $css .='#stmobilemenu .ma_level_0,#stmobilemenu a.ma_level_0{color:'.Configuration::get('STSN_MENU_MOB_ITEMS1_COLOR').';}';
        if(Configuration::get('STSN_MENU_MOB_ITEMS2_COLOR'))
            $css .='#stmobilemenu .ma_level_1,#stmobilemenu a.ma_level_1{color:'.Configuration::get('STSN_MENU_MOB_ITEMS2_COLOR').';}';
        if(Configuration::get('STSN_MENU_MOB_ITEMS3_COLOR'))
            $css .='#stmobilemenu .ma_level_2,#stmobilemenu a.ma_level_2{color:'.Configuration::get('STSN_MENU_MOB_ITEMS3_COLOR').';}';
        if(Configuration::get('STSN_MENU_MOB_ITEMS1_BG'))
            $css .='#stmobilemenu .stmlevel0{background-color:'.Configuration::get('STSN_MENU_MOB_ITEMS1_BG').';}';
        if(Configuration::get('STSN_MENU_MOB_ITEMS2_BG'))
            $css .='#stmobilemenu .stmlevel1 > li{background-color:'.Configuration::get('STSN_MENU_MOB_ITEMS2_BG').';}';
        if(Configuration::get('STSN_MENU_MOB_ITEMS3_BG'))
            $css .='#stmobilemenu .stmlevel2 > li{background-color:'.Configuration::get('STSN_MENU_MOB_ITEMS3_BG').';}';
        

        //advancedmenu
        if($adv_menu_color = Configuration::get('STSN_ADV_MENU_COLOR'))
            $css .='#st_advanced_menu_wrap .advanced_ma_level_0{color:'.$adv_menu_color.';}#main_menu_widgets #search_block_top.quick_search_simple .submit_searchbox,#main_menu_widgets a.header_item,#main_menu_widgets .shopping_cart .icon_wrap, #main_menu_widgets .shopping_cart, #main_menu_widgets .shopping_cart .ajax_cart_right{color:'.$adv_menu_color.';}'; 
        if($menu_hover_color = Configuration::get('STSN_ADV_MENU_HOVER_COLOR'))
            $css .='#st_advanced_menu_wrap .advanced_ml_level_0.current .advanced_ma_level_0,#st_advanced_menu_wrap .advanced_ma_level_0:hover,#main_menu_widgets a.header_item:hover,#main_menu_widgets .shopping_cart:hover .icon_wrap,#main_menu_widgets .shopping_cart.active .icon_wrap, #main_menu_widgets .shopping_cart:hover, #main_menu_widgets .shopping_cart.active, #main_menu_widgets .shopping_cart:hover .ajax_cart_right, #main_menu_widgets .shopping_cart.active .ajax_cart_right{color:'.$menu_hover_color.';border-bottom-color:'.$menu_hover_color.';}'; 
        if(Configuration::get('STSN_ADV_MENU_HOVER_BG'))
            $css .='#st_advanced_menu_wrap .advanced_ml_level_0.current .advanced_ma_level_0{background-color:'.Configuration::get('STSN_ADV_MENU_HOVER_BG').';}'; 
        $menu_bg_color = Configuration::get('STSN_ADV_MENU_BG_COLOR');
        if($menu_bg_color)
        {
            if(Configuration::get('STSN_ADV_MEGAMENU_WIDTH'))
            {
                $css .='#st_advanced_menu_container{background-color:'.$menu_bg_color.';}'; 
            }
            else
                $css .='#st_advanced_menu_container .container_inner{background-color:'.$menu_bg_color.';}'; 
        }
        $megamenu_bg = array();
        $adv_menu_sticky_opacity = Configuration::get('STSN_ADV_MENU_STICKY_OPACITY');
        if($adv_menu_sticky_bg = Configuration::get('STSN_ADV_MENU_STICKY_BG'))
        {
            $megamenu_bg = self::hex2rgb($adv_menu_sticky_bg);
            $css .='#page_header #header.sticky{background: '.$adv_menu_sticky_bg .';background:rgba('.$megamenu_bg[0].','.$megamenu_bg[1].','.$megamenu_bg[2].','.$adv_menu_sticky_opacity.');}';
        }
        elseif($menu_bg_color)
            $megamenu_bg = self::hex2rgb($menu_bg_color);
        if(is_array($megamenu_bg) && count($megamenu_bg))
            $css .='#st_advanced_menu_container.sticky{background: '.$menu_bg_color .';background:rgba('.$megamenu_bg[0].','.$megamenu_bg[1].','.$megamenu_bg[2].','.$adv_menu_sticky_opacity.');}';

        if(!Configuration::get('STSN_TRANSPARENT_HEADER'))
        {
            $adv_menu_sticky = (int)Configuration::get('STSN_ADV_MENU_STICKY');
            $logo_height = (int)Configuration::get('STSN_LOGO_HEIGHT');
            $shop_logo_height = (int)Configuration::get('SHOP_LOGO_HEIGHT');

            if($adv_menu_sticky==1 || $adv_menu_sticky==2)
                $css .= '#page_header.has_sticky{padding-bottom:'.($menu_height ? $menu_height : 36).'px;}';
            elseif($adv_menu_sticky==3 || $adv_menu_sticky==4)
                $css .= '#page_header.has_sticky{padding-bottom:'.((Configuration::get('STSN_LOGO_POSITION') && $logo_height) ? $logo_height : $shop_logo_height+40).'px;}';
        }

        $menu_bottom_border = (int)Configuration::get('STSN_ADV_MENU_BOTTOM_BORDER');
        $css .='#st_advanced_menu_wrap .stadvancedmenu_sub{border-top-width:'.$menu_bottom_border.'px;}#st_advanced_menu_wrap .advanced_ma_level_0{margin-bottom:-'.$menu_bottom_border.'px;border-bottom-width:'.$menu_bottom_border.'px;}'; 
        if(Configuration::get('STSN_ADV_MEGAMENU_WIDTH'))
            $css .='#st_advanced_menu_container{border-bottom-width:'.$menu_bottom_border.'px;}'; 
        else
            $css .='.boxed_advancedmenu #st_advanced_menu_wrap{border-bottom-width:'.$menu_bottom_border.'px;}'; 

        if($menu_bottom_border_color = Configuration::get('STSN_ADV_MENU_BOTTOM_BORDER_COLOR'))
            $css .='#st_advanced_menu_wrap .stadvancedmenu_sub{border-top-color:'.$menu_bottom_border_color.';}#st_advanced_menu_container, .boxed_advancedmenu #st_advanced_menu_wrap{border-bottom-color:'.$menu_bottom_border_color.';}'; 
        
        if($menu_bottom_border_hover_color = Configuration::get('STSN_ADV_MENU_BOTTOM_BORDER_HOVER_COLOR'))
            $css .='#st_advanced_menu_wrap .advanced_ml_level_0.current .advanced_ma_level_0,#st_advanced_menu_wrap .advanced_ma_level_0:hover{border-bottom-color:'.$menu_bottom_border_hover_color.';}'; 
        
        if(Configuration::get('STSN_ADV_SECOND_MENU_COLOR'))
            $css .='.advanced_ma_level_1{color:'.Configuration::get('STSN_ADV_SECOND_MENU_COLOR').';}'; 
        if(Configuration::get('STSN_ADV_SECOND_MENU_HOVER_COLOR'))
            $css .='.advanced_ma_level_1:hover{color:'.Configuration::get('STSN_ADV_SECOND_MENU_HOVER_COLOR').';}'; 
        if(Configuration::get('STSN_ADV_THIRD_MENU_COLOR'))
            $css .='.advanced_ma_level_2, .advanced_mu_level_3 a.advanced_ma_item{color:'.Configuration::get('STSN_ADV_THIRD_MENU_COLOR').';}'; 
        if(Configuration::get('STSN_ADV_THIRD_MENU_HOVER_COLOR'))
            $css .='.advanced_ma_level_2:hover, .advanced_mu_level_3 a.advanced_ma_item:hover{color:'.Configuration::get('STSN_ADV_THIRD_MENU_HOVER_COLOR').';}'; 
        if(Configuration::get('STSN_ADV_MENU_MOB_ITEMS1_COLOR'))
            $css .='#stmobileadvancedmenu .mo_advanced_ma_level_0,#stmobileadvancedmenu a.mo_advanced_ma_level_0{color:'.Configuration::get('STSN_ADV_MENU_MOB_ITEMS1_COLOR').';}';
        if(Configuration::get('STSN_ADV_MENU_MOB_ITEMS2_COLOR'))
            $css .='#stmobileadvancedmenu .mo_advanced_ma_level_1,#stmobileadvancedmenu a.mo_advanced_ma_level_1{color:'.Configuration::get('STSN_ADV_MENU_MOB_ITEMS2_COLOR').';}';
        if(Configuration::get('STSN_ADV_MENU_MOB_ITEMS3_COLOR'))
            $css .='#stmobileadvancedmenu .mo_advanced_ma_level_2,#stmobileadvancedmenu a.mo_advanced_ma_level_2{color:'.Configuration::get('STSN_ADV_MENU_MOB_ITEMS3_COLOR').';}';
        if(Configuration::get('STSN_ADV_MENU_MOB_ITEMS1_BG'))
            $css .='#stmobileadvancedmenu .mo_advanced_ml_level_0{background-color:'.Configuration::get('STSN_ADV_MENU_MOB_ITEMS1_BG').';}';
        if(Configuration::get('STSN_ADV_MENU_MOB_ITEMS2_BG'))
            $css .='#stmobileadvancedmenu .mo_advanced_mu_level_1 > li{background-color:'.Configuration::get('STSN_ADV_MENU_MOB_ITEMS2_BG').';}';
        if(Configuration::get('STSN_ADV_MENU_MOB_ITEMS3_BG'))
            $css .='#stmobileadvancedmenu .mo_advanced_mu_level_2 > li{background-color:'.Configuration::get('STSN_ADV_MENU_MOB_ITEMS3_BG').';}';
        //Multi menu
        if(Configuration::get('STSN_ADV_MENU_MULTI_BG'))
            $css .='.stadvancedmenu_multi_level .advanced_ma_item{background-color:'.Configuration::get('STSN_ADV_MENU_MULTI_BG').';}';
        if(Configuration::get('STSN_ADV_MENU_MULTI_BG_HOVER'))
            $css .='.stadvancedmenu_multi_level .advanced_ma_item:hover{background-color:'.Configuration::get('STSN_ADV_MENU_MULTI_BG_HOVER').';}';
        //Ver menu
        if(Configuration::get('STSN_ADV_MENU_VER_TITLE_WIDTH'))
            $css .= '#st_advanced_ma_0{ width: '.Configuration::get('STSN_ADV_MENU_VER_TITLE_WIDTH').'px; }';
        if(Configuration::get('STSN_ADV_MENU_VER_TITLE_ALIGN'))
            $css .= '#st_advanced_ma_0{ text-align: '.(Configuration::get('STSN_ADV_MENU_VER_TITLE_ALIGN')==1 ? 'center' : 'right').'; }';
        if(Configuration::get('STSN_ADV_MENU_VER_TITLE'))
            $css .='#st_advanced_menu_wrap #st_advanced_ma_0{color:'.Configuration::get('STSN_ADV_MENU_VER_TITLE').';}'; 
        if(Configuration::get('STSN_ADV_MENU_VER_HOVER_TITLE'))
            $css .='#st_advanced_menu_wrap #advanced_st_menu_0.current #st_advanced_ma_0,#st_advanced_menu_wrap #st_advanced_ma_0:hover{color:'.Configuration::get('STSN_ADV_MENU_VER_HOVER_TITLE').';}'; 
        if(Configuration::get('STSN_ADV_MENU_VER_BG'))
            $css .='#st_advanced_menu_wrap #st_advanced_ma_0{background-color:'.Configuration::get('STSN_ADV_MENU_VER_BG').';}'; 
        if(Configuration::get('STSN_ADV_MENU_VER_HOVER_BG'))
            $css .='#st_advanced_menu_wrap #advanced_st_menu_0.current #st_advanced_ma_0,#st_advanced_menu_wrap #st_advanced_ma_0:hover{background-color:'.Configuration::get('STSN_ADV_MENU_VER_HOVER_BG').';}'; 
        if(Configuration::get('STSN_ADV_MENU_VER_ITEM_COLOR'))
            $css .='.advanced_mv_item{color:'.Configuration::get('STSN_ADV_MENU_VER_ITEM_COLOR').';}'; 
        if(Configuration::get('STSN_ADV_MENU_VER_ITEM_BG'))
            $css .='.advanced_mv_level_1{background-color:'.Configuration::get('STSN_ADV_MENU_VER_ITEM_BG').';}'; 
        if(Configuration::get('STSN_ADV_MENU_VER_ITEM_HOVER_COLOR'))
            $css .='.advanced_mv_item:hover{color:'.Configuration::get('STSN_ADV_MENU_VER_ITEM_HOVER_COLOR').';}'; 
        if(Configuration::get('STSN_ADV_MENU_VER_ITEM_HOVER_BG'))
            $css .='.advanced_mv_level_1:hover{background-color:'.Configuration::get('STSN_ADV_MENU_VER_ITEM_HOVER_BG').';}'; 
        
        //Side menu
        if(Configuration::get('STSN_C_MENU_COLOR'))
            $css .='#st_advanced_menu_column_desktop .advanced_ma_level_0{color:'.Configuration::get('STSN_C_MENU_COLOR').';}'; 
        if($menu_hover_color = Configuration::get('STSN_C_MENU_HOVER_COLOR'))
            $css .='#st_advanced_menu_column_desktop .advanced_ml_level_0.current .advanced_ma_level_0,#st_advanced_menu_column_desktop .advanced_ma_level_0:hover{color:'.$menu_hover_color.';}'; 
        if(Configuration::get('STSN_C_MENU_BG'))
            $css .='#st_advanced_menu_column_block{background-color:'.Configuration::get('STSN_C_MENU_BG').';}'; 
        if(Configuration::get('STSN_C_MENU_HOVER_BG'))
            $css .='#st_advanced_menu_column_desktop .advanced_ml_level_0.current .advanced_ma_level_0{background-color:'.Configuration::get('STSN_C_MENU_HOVER_BG').';}'; 
        if(Configuration::get('STSN_C_MENU_BG_COLOR'))
                $css .='#st_advanced_menu_column_desktop{background-color:'.Configuration::get('STSN_C_MENU_BG_COLOR').';}'; 

        if($c_menu_border_color = Configuration::get('STSN_C_MENU_BORDER_COLOR'))
        {
            $css .='#st_advanced_menu_column_desktop,#st_advanced_menu_column_mobile{border:1px solid '.$c_menu_border_color.';}'; 
            $css .='#st_advanced_menu_column_desktop .advanced_ml_level_0, #st_advanced_menu_column_mobile .mo_advanced_ml_level_0, #st_advanced_menu_column_mobile .mo_advanced_sub_li{border-bottom:1px solid '.$c_menu_border_color.';}#st_advanced_menu_column_desktop .advanced_ml_level_0:last-child,#st_advanced_menu_column_mobile .mo_advanced_ml_level_0:last-child, #st_advanced_menu_column_mobile .mo_advanced_sub_li:last-child{border-bottom:none;}'; 
        }
        
        if(Configuration::get('STSN_C_MENU_TITLE_COLOR'))
            $css .='#st_advanced_menu_column .title_block{color:'.Configuration::get('STSN_C_MENU_TITLE_COLOR').';}'; 
        if(Configuration::get('STSN_C_MENU_TITLE_BG'))
            $css .='#st_advanced_menu_column .title_block{padding:10px 0 10px 8px;margin-bottom:0;background-color:'.Configuration::get('STSN_C_MENU_TITLE_BG').';}.is_rtl #st_advanced_menu_column .title_block{padding-right:8px;padding-left:0;}'; 

        //footer
        $footer_border = Configuration::get('STSN_FOOTER_BORDER');
        if($footer_border>1)
            $css .='#footer-primary '.($footer_border>20 ? '.container' : '').'{border-top-width:'.($footer_border%10).'px;border-top-style: solid;}';
        elseif($footer_border==1)
            $css .='#footer-primary, #footer-primary .container{border-top: none;}';
        if(Configuration::get('STSN_FOOTER_BORDER_COLOR'))
            $css .='#footer-primary, #footer-primary .container{border-top-color:'.Configuration::get('STSN_FOOTER_BORDER_COLOR').';}';

        if(Configuration::get('STSN_SECOND_FOOTER_COLOR')) 
            $css .='.footer-container #footer_info,.footer-container #footer_info a{color:'.Configuration::get('STSN_SECOND_FOOTER_COLOR').';}';   
        if(Configuration::get('STSN_SECOND_FOOTER_LINK_HOVER_COLOR')) 
            $css .='.footer-container #footer_info a:hover{color:'.Configuration::get('STSN_SECOND_FOOTER_LINK_HOVER_COLOR').';}';   
        
        if(Configuration::get('STSN_FOOTER_COLOR')) 
            $css .='#footer{color:'.Configuration::get('STSN_FOOTER_COLOR').';}'; 
        if(Configuration::get('STSN_FOOTER_LINK_COLOR')) 
            $css .='#footer a{color:'.Configuration::get('STSN_FOOTER_LINK_COLOR').';}'; 
        if(Configuration::get('STSN_FOOTER_LINK_HOVER_COLOR')) 
            $css .='#footer a:hover{color:'.Configuration::get('STSN_FOOTER_LINK_HOVER_COLOR').';}';    
        
        
        if (Configuration::get('STSN_BODY_BG_COLOR'))
        {
            $css .= 'body, body.content_only{background-color:'.Configuration::get('STSN_BODY_BG_COLOR').';}';
            if (!Configuration::get('STSN_BODY_CON_BG_COLOR'))
                $css .= '.section .title_block span, .section .title_block a,.nav_top_right .flex-direction-nav, #home-page-tabs li a, #home-page-tabs li span{background-color:'.Configuration::get('STSN_BODY_BG_COLOR').';}';
        }
        if (Configuration::get('STSN_BODY_CON_BG_COLOR'))
			$css .= '.main_content_area,.main_content_area_top,.main_content_area_footer,.section .title_block span, .section .title_block a,.nav_top_right .flex-direction-nav, #home-page-tabs li a, #home-page-tabs li span{background-color:'.Configuration::get('STSN_BODY_CON_BG_COLOR').';}';
        if (Configuration::get('STSN_MAIN_CON_BG_COLOR'))
            $css .= '.main_content_area > .wide_container,.main_content_area_top .wide_container,.main_content_area_footer .wide_container,.section .title_block span, .section .title_block a,.nav_top_right .flex-direction-nav, #home-page-tabs li a, #home-page-tabs li span{background-color:'.Configuration::get('STSN_MAIN_CON_BG_COLOR').';}';
        if (Configuration::get('STSN_BODY_BG_PATTERN') && (Configuration::get('STSN_BODY_BG_IMG')==""))
			$css .= 'body{background-image: url('._MODULE_DIR_.'stthemeeditor/patterns/'.Configuration::get('STSN_BODY_BG_PATTERN').'.png);}';
        if (Configuration::get('STSN_BODY_BG_IMG'))
			$css .= 'body{background-image:url(../../'.Configuration::get('STSN_BODY_BG_IMG').');}';
		if (Configuration::get('STSN_BODY_BG_REPEAT')) {
			switch(Configuration::get('STSN_BODY_BG_REPEAT')) {
				case 1 :
					$repeat_option = 'repeat-x';
					break;
				case 2 :
					$repeat_option = 'repeat-y';
					break;
				case 3 :
					$repeat_option = 'no-repeat';
					break;
				default :
					$repeat_option = 'repeat';
			}
			$css .= 'body{background-repeat:'.$repeat_option.';}';
		}
		if (Configuration::get('STSN_BODY_BG_POSITION')) {
			switch(Configuration::get('STSN_BODY_BG_POSITION')) {
				case 1 :
					$position_option = 'center top';
					break;
				case 2 :
					$position_option = 'right top';
					break;
				default :
					$position_option = 'left top';
			}
			$css .= 'body{background-position: '.$position_option.';}';
		}
		if (Configuration::get('STSN_BODY_BG_FIXED')) {
			$css .= 'body{background-attachment: fixed;}';
		}
        if (Configuration::get('STSN_BODY_BG_COVER')) {
            $css .= 'body{background-size: cover;}';
        }
        $header_bg_color = Configuration::get('STSN_HEADER_BG_COLOR');
        if ($header_bg_color)
        {
            $css .= '#page_header{background-color:'.$header_bg_color.';}'; 
            if($transparent_header)
                $css .= 'body#index #page_header.transparent_header{background-color:transparent;}';

            $css .= 'body#index.mobile_device #page_header.transparent_header{background-color:'.$header_bg_color.';}';
            if($is_responsive)
                $css .= '@media only screen and (max-width: 991px) {body#index #page_header.transparent_header{background-color:'.$header_bg_color.';}}';
        }

        if (Configuration::get('STSN_HEADER_CON_BG_COLOR'))
			$css .= '#header .wide_container,#top_extra .wide_container{background-color:'.Configuration::get('STSN_HEADER_CON_BG_COLOR').';}';
        if (Configuration::get('STSN_HEADER_BG_PATTERN') && (Configuration::get('STSN_HEADER_BG_IMG')==""))
			$css .= '#page_header{background-image: url('._MODULE_DIR_.'stthemeeditor/patterns/'.Configuration::get('STSN_HEADER_BG_PATTERN').'.png);}';
        if (Configuration::get('STSN_HEADER_BG_IMG'))
			$css .= '#page_header{background-image:url(../../'.Configuration::get('STSN_HEADER_BG_IMG').');}';
		if (Configuration::get('STSN_HEADER_BG_REPEAT')) {
			switch(Configuration::get('STSN_HEADER_BG_REPEAT')) {
				case 1 :
					$repeat_option = 'repeat-x';
					break;
				case 2 :
					$repeat_option = 'repeat-y';
					break;
				case 3 :
					$repeat_option = 'no-repeat';
					break;
				default :
					$repeat_option = 'repeat';
			}
			$css .= '#page_header{background-repeat:'.$repeat_option.';}';
		}
		if (Configuration::get('STSN_HEADER_BG_POSITION')) {
			switch(Configuration::get('STSN_HEADER_BG_POSITION')) {
				case 1 :
					$position_option = 'center top';
					break;
				case 2 :
					$position_option = 'right top';
					break;
				default :
					$position_option = 'left top';
			}
			$css .= '#page_header{background-position: '.$position_option.';}';
		}
        if (Configuration::get('STSN_DISPLAY_BANNER_BG'))
            $css .= 'header .banner{background-color:'.Configuration::get('STSN_DISPLAY_BANNER_BG').';}';

                            
        if (Configuration::get('STSN_F_TOP_BG_PATTERN') && (Configuration::get('STSN_F_TOP_BG_IMG')==""))
			$css .= '#footer-top{background-image: url('._MODULE_DIR_.'stthemeeditor/patterns/'.Configuration::get('STSN_F_TOP_BG_PATTERN').'.png);}';
        if (Configuration::get('STSN_F_TOP_BG_IMG'))
			$css .= '#footer-top{background-image:url(../../'.Configuration::get('STSN_F_TOP_BG_IMG').');}';
		if (Configuration::get('STSN_FOOTER_BG_REPEAT')) {
			switch(Configuration::get('STSN_FOOTER_BG_REPEAT')) {
				case 1 :
					$repeat_option = 'repeat-x';
					break;
				case 2 :
					$repeat_option = 'repeat-y';
					break;
				case 3 :
					$repeat_option = 'no-repeat';
					break;
				default :
					$repeat_option = 'repeat';
			}
			$css .= '#footer-top{background-repeat:'.$repeat_option.';}';
		}
		if (Configuration::get('STSN_F_TOP_BG_PATTERN')) {
			switch(Configuration::get('STSN_F_TOP_BG_PATTERN')) {
				case 1 :
					$position_option = 'center top';
					break;
				case 2 :
					$position_option = 'right top';
					break;
				default :
					$position_option = 'left top';
			}
			$css .= '#footer-top{background-position: '.$position_option.';}';
		}
        if (Configuration::get('STSN_F_TOP_BG_FIXED')) {
            $css .= '#footer-top{background-attachment: fixed;}';
        }
        $footer_border = Configuration::get('STSN_FOOTER_TOP_BORDER');
        if($footer_border>1)
            $css .='#footer-top '.($footer_border>20 ? '.container' : '').'{border-top-width:'.($footer_border%10).'px;border-top-style: solid;}';
        elseif($footer_border==1)
            $css .='#footer-top, #footer-top .container{border-top: none;}';
        if(Configuration::get('STSN_FOOTER_TOP_BORDER_COLOR'))
            $css .='#footer-top, #footer-top .container{border-top-color:'.Configuration::get('STSN_FOOTER_TOP_BORDER_COLOR').';}';

        if (Configuration::get('STSN_FOOTER_TOP_BG'))
			$css .= '#footer-top{background-color:'.Configuration::get('STSN_FOOTER_TOP_BG').';}';
        if (Configuration::get('STSN_FOOTER_TOP_CON_BG'))
			$css .= '#footer-top .wide_container{background-color:'.Configuration::get('STSN_FOOTER_TOP_CON_BG').';}';
            
        if (Configuration::get('STSN_FOOTER_BG_PATTERN') && (Configuration::get('STSN_FOOTER_BG_IMG')==""))
			$css .= '#footer-primary{background-image: url('._MODULE_DIR_.'stthemeeditor/patterns/'.Configuration::get('STSN_FOOTER_BG_PATTERN').'.png);}';
        if (Configuration::get('STSN_FOOTER_BG_IMG'))
			$css .= '#footer-primary{background-image:url(../../'.Configuration::get('STSN_FOOTER_BG_IMG').');}';
		if (Configuration::get('STSN_FOOTER_BG_REPEAT')) {
			switch(Configuration::get('STSN_FOOTER_BG_REPEAT')) {
				case 1 :
					$repeat_option = 'repeat-x';
					break;
				case 2 :
					$repeat_option = 'repeat-y';
					break;
				case 3 :
					$repeat_option = 'no-repeat';
					break;
				default :
					$repeat_option = 'repeat';
			}
			$css .= '#footer-primary{background-repeat:'.$repeat_option.';}';
		}
		if (Configuration::get('STSN_FOOTER_BG_POSITION')) {
			switch(Configuration::get('STSN_FOOTER_BG_POSITION')) {
				case 1 :
					$position_option = 'center top';
					break;
				case 2 :
					$position_option = 'right top';
					break;
				default :
					$position_option = 'left top';
			}
			$css .= '#footer-primary{background-position: '.$position_option.';}';
		}
        if (Configuration::get('STSN_FOOTER_BG_FIXED')) {
            $css .= '#footer-primary{background-attachment: fixed;}';
        }
        if (Configuration::get('STSN_FOOTER_BG_COLOR'))
			$css .= '#footer-primary{background-color:'.Configuration::get('STSN_FOOTER_BG_COLOR').';}';
        if (Configuration::get('STSN_FOOTER_CON_BG_COLOR'))
			$css .= '#footer-primary .wide_container{background-color:'.Configuration::get('STSN_FOOTER_CON_BG_COLOR').';}';
            
        if (Configuration::get('STSN_F_SECONDARY_BG_PATTERN') && (Configuration::get('STSN_F_SECONDARY_BG_IMG')==""))
			$css .= '#footer-secondary{background-image: url('._MODULE_DIR_.'stthemeeditor/patterns/'.Configuration::get('STSN_F_SECONDARY_BG_PATTERN').'.png);}';
        if (Configuration::get('STSN_F_SECONDARY_BG_IMG'))
			$css .= '#footer-secondary{background-image:url(../../'.Configuration::get('STSN_F_SECONDARY_BG_IMG').');}';
		if (Configuration::get('STSN_F_SECONDARY_BG_REPEAT')) {
			switch(Configuration::get('STSN_F_SECONDARY_BG_REPEAT')) {
				case 1 :
					$repeat_option = 'repeat-x';
					break;
				case 2 :
					$repeat_option = 'repeat-y';
					break;
				case 3 :
					$repeat_option = 'no-repeat';
					break;
				default :
					$repeat_option = 'repeat';
			}
			$css .= '#footer-secondary{background-repeat:'.$repeat_option.';}';
		}
		if (Configuration::get('STSN_F_SECONDARY_BG_POSITION')) {
			switch(Configuration::get('STSN_F_SECONDARY_BG_POSITION')) {
				case 1 :
					$position_option = 'center top';
					break;
				case 2 :
					$position_option = 'right top';
					break;
				default :
					$position_option = 'left top';
			}
			$css .= '#footer-secondary{background-position: '.$position_option.';}';
		}
        if (Configuration::get('STSN_F_SECONDARY_BG_FIXED')) {
            $css .= '#footer-secondary{background-attachment: fixed;}';
        }
        if (Configuration::get('STSN_FOOTER_SECONDARY_BG'))
			$css .= '#footer-secondary{background-color:'.Configuration::get('STSN_FOOTER_SECONDARY_BG').';}';
        if (Configuration::get('STSN_FOOTER_SECONDARY_CON_BG'))
			$css .= '#footer-secondary .wide_container{background-color:'.Configuration::get('STSN_FOOTER_SECONDARY_CON_BG').';}';
        $footer_border = Configuration::get('STSN_FOOTER_SECONDARY_BORDER');
        if($footer_border>1)
            $css .='#footer-secondary '.($footer_border>20 ? '.container' : '').'{border-top-width:'.($footer_border%10).'px;border-top-style: solid;}';
        elseif($footer_border==1)
            $css .='#footer-secondary, #footer-secondary .container{border-top: none;}';
        if(Configuration::get('STSN_FOOTER_SECONDARY_BORDER_COLOR'))
            $css .='#footer-secondary, #footer-secondary .container{border-top-color:'.Configuration::get('STSN_FOOTER_SECONDARY_BORDER_COLOR').';}';

                        
        if (Configuration::get('STSN_F_INFO_BG_PATTERN') && (Configuration::get('STSN_F_INFO_BG_IMG')==""))
			$css .= '.footer-container #footer_info{background-image: url('._MODULE_DIR_.'stthemeeditor/patterns/'.Configuration::get('STSN_F_INFO_BG_PATTERN').'.png);}';
        if (Configuration::get('STSN_F_INFO_BG_IMG'))
			$css .= '.footer-container #footer_info{background-image:url(../../'.Configuration::get('STSN_F_INFO_BG_IMG').');}';
		if (Configuration::get('STSN_F_INFO_BG_REPEAT')) {
			switch(Configuration::get('STSN_F_INFO_BG_REPEAT')) {
				case 1 :
					$repeat_option = 'repeat-x';
					break;
				case 2 :
					$repeat_option = 'repeat-y';
					break;
				case 3 :
					$repeat_option = 'no-repeat';
					break;
				default :
					$repeat_option = 'repeat';
			}
			$css .= '.footer-container #footer_info{background-repeat:'.$repeat_option.';}';
		}
		if (Configuration::get('STSN_F_INFO_BG_POSITION')) {
			switch(Configuration::get('STSN_F_INFO_BG_POSITION')) {
				case 1 :
					$position_option = 'center top';
					break;
				case 2 :
					$position_option = 'right top';
					break;
				default :
					$position_option = 'left top';
			}
			$css .= '.footer-container #footer_info{background-position: '.$position_option.';}';
		}
        if (Configuration::get('STSN_F_INFO_BG_FIXED')) {
            $css .= '.footer-container #footer_info{background-attachment: fixed;}';
        }
        if (Configuration::get('STSN_FOOTER_INFO_BG'))
			$css .= '.footer-container #footer_info{background-color:'.Configuration::get('STSN_FOOTER_INFO_BG').';}';
        if (Configuration::get('STSN_FOOTER_INFO_CON_BG'))
			$css .= '.footer-container #footer_info .wide_container{background-color:'.Configuration::get('STSN_FOOTER_INFO_CON_BG').';}';
        $footer_border = Configuration::get('STSN_FOOTER_INFO_BORDER');
        if($footer_border>1)
            $css .='.footer-container #footer_info '.($footer_border>20 ? '.container' : '').'{border-top-width:'.($footer_border%10).'px;border-top-style: solid;}';
        elseif($footer_border==1)
            $css .='.footer-container #footer_info, .footer-container #footer_info .container{border-top: none;}';
        if(Configuration::get('STSN_FOOTER_INFO_BORDER_COLOR'))
            $css .='.footer-container #footer_info, .footer-container #footer_info .container{border-top-color:'.Configuration::get('STSN_FOOTER_INFO_BORDER_COLOR').';}';

        if(!Configuration::get('STSN_RESPONSIVE'))    
			$css .= '#body_wrapper{min-width:970px;margin-right:auto;margin-left:auto;}';
        
        if(Configuration::get('STSN_NEW_COLOR'))
            $css .='span.new i{color: '.Configuration::get('STSN_NEW_COLOR').';}';
        $new_style = (int)Configuration::get('STSN_NEW_STYLE');
		if($new_style==1)
        {
            $css .= 'span.new{border:none;width:40px;height:40px;line-height:40px;top:0;}span.new i{position:static;left:auto;}';
            if(!Configuration::get('STSN_NEW_BG_IMG'))
                $css .= 'span.new{-webkit-border-radius: 500px;-moz-border-radius: 500px;border-radius: 500px;}';
        }elseif($new_style==3){
            $css .= 'span.new{border-width:1px;width:auto;height:auto;line-height:100%;padding:1px 2px;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;}span.new i{position:static;left:auto;}';
        }                    
        $new_bg_color = Configuration::get('STSN_NEW_BG_COLOR');
        if($new_bg_color)
        {
            if($new_style==1)
                $css .= 'span.new{background-color:'.$new_bg_color.';}';
            elseif($new_style==3)
                $css .= 'span.new{background-color:'.$new_bg_color.';border-color:'.$new_bg_color.';}';
            else
                $css .='span.new{color: '.$new_bg_color.';border-color:'.$new_bg_color.';border-left-color:transparent;}.is_rtl span.new{border-color:'.$new_bg_color.';border-right-color:transparent;}';
        }  
        elseif(!$new_bg_color && ($new_style==1 || $new_style==3)) 
            $css .= 'span.new{background-color:#00A161;border-color:#00A161;}';
        
        if($new_stickers_width = Configuration::get('STSN_NEW_STICKERS_WIDTH'))
        {
            if($new_style==1)
                $css .= 'span.new{width:'.$new_stickers_width.'px;height:'.$new_stickers_width.'px;line-height:'.$new_stickers_width.'px;}';
            elseif ($new_style==3)
                $css .= 'span.new{width:'.$new_stickers_width.'px;}';
            else
            {
                $css .= 'span.new{border-right-width:'.$new_stickers_width.'px;}.is_rtl span.new{border-right-width:10px;border-left-width:'.$new_stickers_width.'px;}';
                $css .= '.is_rtl span.new i{left:-'.($new_stickers_width-7).'px;}';
            }
        }
		if(Configuration::get('STSN_NEW_STICKERS_TOP')!==false)
			$css .= 'span.new{top:'.(int)Configuration::get('STSN_NEW_STICKERS_TOP').'px;}';
		if(Configuration::get('STSN_NEW_STICKERS_RIGHT')!==false)
			$css .= 'span.new{right:'.(int)Configuration::get('STSN_NEW_STICKERS_RIGHT').'px;}.is_rtl span.new{right: auto;left: '.(int)Configuration::get('STSN_NEW_STICKERS_RIGHT').'px;}';
		if($new_style==1 && Configuration::get('STSN_NEW_BG_IMG'))
			$css .= 'span.new{background:url(../../'.Configuration::get('STSN_NEW_BG_IMG').') no-repeat center center transparent;}span.new i{display:none;}';
            
        if(Configuration::get('STSN_SALE_COLOR'))
            $css .='span.on_sale i{color: '.Configuration::get('STSN_SALE_COLOR').';}';
        $sale_style = (int)Configuration::get('STSN_SALE_STYLE');
        if($sale_style==1)  
        {
            $css .= 'span.on_sale{border:none;width:40px;height:40px;line-height:40px;top:0;}span.on_sale i{position:static;left:auto;}';
            if(!Configuration::get('STSN_SALE_BG_IMG'))
                $css .= 'span.on_sale{-webkit-border-radius: 500px;-moz-border-radius: 500px;border-radius: 500px;}';
        }elseif($sale_style==3){
            $css .= 'span.on_sale{border-width:1px;width:auto;height:auto;line-height:100%;padding:1px 2px;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;}span.on_sale i{position:static;left:auto;}';
        }       
        $sale_bg_color = Configuration::get('STSN_SALE_BG_COLOR');
        if($sale_bg_color)
        {
            if($sale_style==1)
                $css .= 'span.on_sale{background-color:'.$sale_bg_color.';}';
            elseif($new_style==3)
                $css .= 'span.on_sale{background-color:'.$sale_bg_color.';border-color:'.$sale_bg_color.';}';
            else
                $css .='span.on_sale{color: '.$sale_bg_color.';border-color: '.$sale_bg_color.';border-right-color:transparent;}.is_rtl span.on_sale{border-color: '.$sale_bg_color.';border-left-color:transparent;}';
        }
        elseif(!$sale_bg_color && ($sale_style==1 || $sale_style==3))
            $css .= 'span.on_sale{background-color:#ff8a00;border-color:#ff8a00;}';        

		if($sale_stickers_width = Configuration::get('STSN_SALE_STICKERS_WIDTH'))
        {
            if($sale_style==1)
                $css .= 'span.on_sale{width:'.$sale_stickers_width.'px;height:'.$sale_stickers_width.'px;line-height:'.$sale_stickers_width.'px;}';
            elseif ($sale_style==3)
                $css .= 'span.on_sale{width:'.$sale_stickers_width.'px;}';
            else
            {
    			$css .= 'span.on_sale{border-left-width:'.$sale_stickers_width.'px;}.is_rtl span.on_sale{border-left-width:10px;border-right-width:'.$new_stickers_width.'px;}';
    			$css .= 'span.on_sale i{left:-'.($sale_stickers_width-7).'px;}.is_rtl span.on_sale i{left:7px;}';
            }
        }
		if(Configuration::get('STSN_SALE_STICKERS_TOP')!==false)
			$css .= 'span.on_sale{top:'.(int)Configuration::get('STSN_SALE_STICKERS_TOP').'px;}';
		if(Configuration::get('STSN_SALE_STICKERS_LEFT')!==false)
			$css .= 'span.on_sale{left:'.(int)Configuration::get('STSN_SALE_STICKERS_LEFT').'px;}.is_rtl span.on_sale{left: auto;right: '.(int)Configuration::get('STSN_SALE_STICKERS_LEFT').'px;}';
		if($sale_style==1 && Configuration::get('STSN_SALE_BG_IMG'))
			$css .= 'span.on_sale{background:url(../../'.Configuration::get('STSN_SALE_BG_IMG').') no-repeat center center transparent;}span.on_sale i{display:none;}';
             
        if(Configuration::get('STSN_PRICE_DROP_COLOR'))
    	    $css .= 'span.sale_percentage_sticker{color: '.Configuration::get('STSN_PRICE_DROP_COLOR').';}';
        if(Configuration::get('STSN_PRICE_DROP_BORDER_COLOR'))
    	    $css .= 'span.sale_percentage_sticker{border-color: '.Configuration::get('STSN_PRICE_DROP_BORDER_COLOR').';}';
        if(Configuration::get('STSN_PRICE_DROP_BG_COLOR'))
    	    $css .= 'span.sale_percentage_sticker{background-color: '.Configuration::get('STSN_PRICE_DROP_BG_COLOR').';}';
        if(Configuration::get('STSN_PRICE_DROP_BOTTOM')!==false)
    	    $css .= 'span.sale_percentage_sticker{bottom: '.(int)Configuration::get('STSN_PRICE_DROP_BOTTOM').'px;}';
        if(Configuration::get('STSN_PRICE_DROP_RIGHT')!==false)
    	    $css .= 'span.sale_percentage_sticker{right: '.(int)Configuration::get('STSN_PRICE_DROP_RIGHT').'px;}';
        $price_drop_width = (int)Configuration::get('STSN_PRICE_DROP_WIDTH');
        if($price_drop_width>28)
        {
            $price_drop_padding = round(($price_drop_width-28-8)/2,3);
    	    $css .= 'span.sale_percentage_sticker{width: '.$price_drop_width.'px;height: '.$price_drop_width.'px;padding:'.$price_drop_padding.'px 0;}';
        }

        $css .= 'span.sold_out{font-family: "'.$fontHeading.'";}';
        if(Configuration::get('STSN_SOLD_OUT_COLOR'))
            $css .= 'span.sold_out{color: '.Configuration::get('STSN_SOLD_OUT_COLOR').';}';
        if(Configuration::get('STSN_SOLD_OUT_BG_COLOR'))
            $css .= 'span.sold_out{background-color: '.Configuration::get('STSN_SOLD_OUT_BG_COLOR').';}';
        if(Configuration::get('STSN_SOLD_OUT')==2 && Configuration::get('STSN_SOLD_OUT_BG_IMG'))
            $css .= 'span.sold_out{background:url(../../'.Configuration::get('STSN_SOLD_OUT_BG_IMG').') no-repeat center center transparent;top:0;padding:0;margin:0;height:100%;border:none;text-indent:-10000px;overflow:hidden;}';
             
        if(Configuration::get('STSN_LOGO_POSITION') && Configuration::get('STSN_LOGO_HEIGHT'))
    	    $css .= '.logo_center #header_left,.logo_center #logo_wrapper,.logo_center #header_right{height: '.(int)Configuration::get('STSN_LOGO_HEIGHT').'px;}';   
        if($megamenu_position = Configuration::get('STSN_MEGAMENU_POSITION'))
    	{
            $css .= '#st_mega_menu{text-align: '.($megamenu_position==1 ? 'center' : 'right').';}.sttlevel0{float:none;display:inline-block;vertical-align:middle;}';
            if($adv_megamenu_position==2)
                $css .= '.is_rtl #st_mega_menu{text-align: left;}';
        }   
       if($adv_megamenu_position = Configuration::get('STSN_ADV_MEGAMENU_POSITION'))
       {
            $css .= '#st_advanced_menu_wrap .st_advanced_menu{text-align: '.($adv_megamenu_position==1 ? 'center' : 'right').';}#st_advanced_menu_wrap .advanced_ml_level_0{float:none;display:inline-block;vertical-align:middle;}';   
            if($adv_megamenu_position==2)
                $css .= '.is_rtl #st_advanced_menu_wrap .st_advanced_menu{text-align: left;}';
       }
            
        if(Configuration::get('STSN_CART_ICON'))
            $css .= '.icon-basket.icon_btn:before,.box-info-product .exclusive span:before{ content: "\\'.dechex(Configuration::get('STSN_CART_ICON')).'"; }';
        if(Configuration::get('STSN_WISHLIST_ICON'))
            $css .= '.icon-heart.icon_btn:before{ content: "\\'.dechex(Configuration::get('STSN_WISHLIST_ICON')).'"; }';
        if(Configuration::get('STSN_COMPARE_ICON'))
            $css .= '.icon-ajust.icon_btn:before{ content: "\\'.dechex(Configuration::get('STSN_COMPARE_ICON')).'"; }';
        if(Configuration::get('STSN_QUICK_VIEW_ICON'))
            $css .= '.icon-search-1.icon_btn:before{ content: "\\'.dechex(Configuration::get('STSN_QUICK_VIEW_ICON')).'"; }';
        if(Configuration::get('STSN_VIEW_ICON'))
            $css .= '.icon-eye-2.icon_btn:before{ content: "\\'.dechex(Configuration::get('STSN_VIEW_ICON')).'"; }';

            
        if(Configuration::get('STSN_PRO_TAB_COLOR'))  
            $css .= '#more_info_tabs a, .product_accordion_title{ color: '.Configuration::get('STSN_PRO_TAB_COLOR').'; }';
        if(Configuration::get('STSN_PRO_TAB_ACTIVE_COLOR'))  
            $css .= '#more_info_tabs a.selected,#more_info_tabs a:hover{ color: '.Configuration::get('STSN_PRO_TAB_ACTIVE_COLOR').'; }';
        if(Configuration::get('STSN_PRO_TAB_BG'))  
            $css .= '#more_info_tabs a, .product_accordion_title{ background-color: '.Configuration::get('STSN_PRO_TAB_BG').'; }';
        if(Configuration::get('STSN_PRO_TAB_ACTIVE_BG'))  
            $css .= '#more_info_tabs a.selected{ background-color: '.Configuration::get('STSN_PRO_TAB_ACTIVE_BG').'; }';
        if(Configuration::get('STSN_PRO_TAB_CONTENT_BG'))  
            $css .= '#more_info_sheets, #right_more_info_block .product_accordion .pa_content{ background-color: '.Configuration::get('STSN_PRO_TAB_CONTENT_BG').'; }';
        
        //Top and bottom spacing
        if(Configuration::get('STSN_TOP_SPACING'))  
            $css .= '#body_wrapper{ padding-top: '.Configuration::get('STSN_TOP_SPACING').'px; }';

        $header_bottom_spacing = (int)Configuration::get('STSN_HEADER_BOTTOM_SPACING');
            $css .= '.main_content_area{ padding-top: '.($header_bottom_spacing+16).'px; }body#index .main_content_area,body#module-stblog-default .main_content_area{ padding-top: '.$header_bottom_spacing.'px; }';
        if($transparent_header)
        {
            if($is_responsive)
                $css .= '@media only screen and (min-width: 992px) {body#index .main_content_area{ padding-top: 0px; }}';
            else
                $css .= 'body#index .main_content_area{ padding-top: 0px; }';
        }

        if(Configuration::get('STSN_BOTTOM_SPACING'))  
            $css .= '#body_wrapper{ padding-bottom: '.Configuration::get('STSN_BOTTOM_SPACING').'px; }';
        
        if($header_padding = Configuration::get('STSN_HEADER_PADDING'))
        {
            $css .= '#header .wide_container{ padding-top: '.$header_padding.'px; padding-bottom: '.$header_padding.'px; }';
            $res_css .= '@media only screen and (max-width: 991px) {#header .wide_container{ padding-top: 0; padding-bottom: 0; }}';
        }

        //Shadow
        if(Configuration::get('STSN_PRO_SHADOW_EFFECT'))
        {
            $pro_shadow_color = Configuration::get('STSN_PRO_SHADOW_COLOR');
            if(!Validate::isColor($pro_shadow_color))
                $pro_shadow_color = '#000000';

            $pro_shadow_color_arr = self::hex2rgb($pro_shadow_color);
            if(is_array($pro_shadow_color_arr))
            {
                $pro_shadow_opacity = (float)Configuration::get('STSN_PRO_SHADOW_OPACITY');
                if($pro_shadow_opacity<0 || $pro_shadow_opacity>1)
                    $pro_shadow_opacity = 0.1;

                $pro_shadow_css = (int)Configuration::get('STSN_PRO_H_SHADOW').'px '.(int)Configuration::get('STSN_PRO_V_SHADOW').'px '.(int)Configuration::get('STSN_PRO_SHADOW_BLUR').'px rgba('.$pro_shadow_color_arr[0].','.$pro_shadow_color_arr[1].','.$pro_shadow_color_arr[2].','.$pro_shadow_opacity.')';
                $css .= '.products_slider .ajax_block_product:hover .pro_outer_box, .product_list.grid .ajax_block_product:hover .pro_outer_box, .product_list.list .ajax_block_product:hover{-webkit-box-shadow: '.$pro_shadow_css .'; -moz-box-shadow: '.$pro_shadow_css .'; box-shadow: '.$pro_shadow_css .'; }';
            }
        }
        if(Configuration::get('STSN_PRO_GRID_HOVER_BG'))  
            $css .= '.products_slider .ajax_block_product:hover .pro_second_box,.product_list.grid .ajax_block_product:hover .pro_second_box{ background-color: '.Configuration::get('STSN_PRO_GRID_HOVER_BG').'; }';

        //Boxed style shadow
        if(Configuration::get('STSN_BOXED_SHADOW_EFFECT'))
        {
            $boxed_shadow_color = Configuration::get('STSN_BOXED_SHADOW_COLOR');
            if(!Validate::isColor($boxed_shadow_color))
                $boxed_shadow_color = '#000000';

            $boxed_shadow_color_arr = self::hex2rgb($boxed_shadow_color);
            if(is_array($boxed_shadow_color_arr))
            {
                $boxed_shadow_opacity = (float)Configuration::get('STSN_BOXED_SHADOW_OPACITY');
                if($boxed_shadow_opacity<0 || $boxed_shadow_opacity>1)
                    $boxed_shadow_opacity = 0.1;

                $boxed_shadow_css = (int)Configuration::get('STSN_BOXED_H_SHADOW').'px '.(int)Configuration::get('STSN_BOXED_V_SHADOW').'px '.(int)Configuration::get('STSN_BOXED_SHADOW_BLUR').'px rgba('.$boxed_shadow_color_arr[0].','.$boxed_shadow_color_arr[1].','.$boxed_shadow_color_arr[2].','.$boxed_shadow_opacity.')';
                $css .= '#page_wrapper{-webkit-box-shadow: '.$boxed_shadow_css .'; -moz-box-shadow: '.$boxed_shadow_css .'; box-shadow: '.$boxed_shadow_css .'; }';
            }
        }
        else
            $css .= '#page_wrapper{box-shadow:none;-webkit-box-shadow:none;-moz-box-shadow:none;}';


        //
        if($base_border_color = Configuration::get('STSN_BASE_BORDER_COLOR'))
        {
            $css .= '.pro_column_list li, .pro_column_box,
                    .categories_tree_block li,
                    #create-account_form section, #login_form section,
                    .box,
                    .top-pagination-content,
                    .content_sortPagiBar .sortPagiBar,
                    .content_sortPagiBar .sortPagiBar.sortPagiBarBottom,
                    .bottom-pagination-content,
                    ul.product_list.grid > li,ul.product_list.list > li,
                    .pb-center-column #buy_block .box-info-product,
                    .box-cart-bottom .qt_cart_box,
                    .product_extra_info_wrap,
                    #blog_list_large .block_blog, #blog_list_medium .block_blog,
                    #product_comments_block_tab div.comment,
                    .table-bordered > thead > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > tfoot > tr > td,
                    ul.footer_links,
                    #product p#loyalty,
                    #subcategories .inline_list li a.img{ border-color: '.$base_border_color.'; }';
            $res_css .= '@media (max-width: 767px) {#footer .title_block,#footer .open .footer_block_content{ border-color: '.$base_border_color.'; }}';
        }
        if($form_bg_color = Configuration::get('STSN_FORM_BG_COLOR'))
            $css .= '.box{background-color:'.$form_bg_color.';}';

        if($sticky_mobile_header_height = Configuration::get('STSN_STICKY_MOBILE_HEADER_HEIGHT'))
        {
            $css .= '#mobile_bar_container{ height: '.$sticky_mobile_header_height.'px;}#mobile_header_logo img{max-height: '.$sticky_mobile_header_height.'px;}';
            $res_css .= '@media only screen and (max-width: 991px) {#page_header.sticky_mh{ padding-bottom: '.$sticky_mobile_header_height.'px;}}';
        }

        if($sticky_mobile_header_color = Configuration::get('STSN_STICKY_MOBILE_HEADER_COLOR'))
            $css .= '#page_header .mobile_bar_tri,#page_header.transparent-mobile-header.sticky_mh .mobile_bar_tri{ color: '.$sticky_mobile_header_color.';}';
        if($sticky_mobile_header_background = Configuration::get('STSN_STICKY_MOBILE_HEADER_BACKGROUND'))
        {
            $css .= '#page_header #mobile_bar,#page_header.sticky_mh #mobile_bar{ background-color: '.$sticky_mobile_header_background.';}';
            if(Configuration::get('STSN_TRANSPARENT_MOBILE_HEADER') && !Configuration::get('STSN_TRANSPARENT_MOBILE_HEADER_BG'))
                $css .= 'body#index #page_header.transparent-mobile-header #mobile_bar{ background-color: transparent;}';

            $sticky_mobile_header_background_opacity = (float)Configuration::get('STSN_STICKY_MOBILE_HEADER_BACKGROUND_OPACITY');
            if($sticky_mobile_header_background_opacity>=0 && $sticky_mobile_header_background_opacity<1)
            {
                $sticky_mobile_header_background_hex = self::hex2rgb($sticky_mobile_header_background);
                $css .= '#page_header.sticky_mh #mobile_bar, body#index #page_header.transparent-mobile-header.sticky_mh #mobile_bar{background-color: '.$sticky_mobile_header_background.';background:rgba('.$sticky_mobile_header_background_hex[0].','.$sticky_mobile_header_background_hex[1].','.$sticky_mobile_header_background_hex[2].','.$sticky_mobile_header_background_opacity.');}';      
            }
        }
        if($transparent_mobile_header_color = Configuration::get('STSN_TRANSPARENT_MOBILE_HEADER_COLOR'))
            $css .= '#page_header.transparent-mobile-header .mobile_bar_tri{ color: '.$transparent_mobile_header_color.';}';

        if(Configuration::get('STSN_TRANSPARENT_MOBILE_HEADER'))
        {
            if($transparent_header_mobile_bg = Configuration::get('STSN_TRANSPARENT_MOBILE_HEADER_BG'))
            {
                $transparent_header_mobile_opacity = (float)Configuration::get('STSN_TRANSPARENT_MOBILE_HEADER_OPACITY');
                if($transparent_header_mobile_opacity<0 || $transparent_header_mobile_opacity>1)
                    $transparent_header_mobile_opacity = 0.4;

                $transparent_header_mobile_bg_hex = self::hex2rgb($transparent_header_mobile_bg);
                $css .= 'body#index #page_header.transparent-mobile-header #mobile_bar{background:rgba('.$transparent_header_mobile_bg_hex[0].','.$transparent_header_mobile_bg_hex[1].','.$transparent_header_mobile_bg_hex[2].','.$transparent_header_mobile_opacity.');}';      
                //$css .= 'body#index.mobile_device .header-container.transparent-header #header{background-color:'.$transparent_header_bg.';}';
            }
        }

        if($side_bar_background = Configuration::get('STSN_SIDE_BAR_BACKGROUND'))
            $css .= '.st-side{ background-color: '.$side_bar_background.';}';

        if ($direction_color = Configuration::get('STSN_DIRECTION_COLOR'))
            $css .= '.nav_top_right .flex-direction-nav a, .nav_left_right .flex-direction-nav a{color:'.$direction_color.';}';
        if ($direction_bg = Configuration::get('STSN_DIRECTION_BG'))
            $css .= '.nav_top_right .flex-direction-nav a, .nav_left_right .flex-direction-nav a{background-color:'.$direction_bg.';}';
        if ($direction_hover_bg = Configuration::get('STSN_DIRECTION_HOVER_BG'))
            $css .= '.nav_top_right .flex-direction-nav a:hover, .nav_left_right .flex-direction-nav a:hover{background-color:'.$direction_hover_bg.';}';
        if ($direction_disabled_bg = Configuration::get('STSN_DIRECTION_DISABLED_BG'))
            $css .= '.nav_top_right .flex-direction-nav a.flex-disabled, .nav_left_right .flex-direction-nav a.flex-disabled{background-color:'.$direction_disabled_bg.';}';

        if(Configuration::get('STSN_PAGINATION_COLOR'))  
            $css .= '.top-pagination-content ul.pagination li > a, .top-pagination-content ul.pagination li > span, .bottom-pagination-content ul.pagination li > a, .bottom-pagination-content ul.pagination li > span, .bottom-blog-pagination ul.pagination li > a, .bottom-blog-pagination ul.pagination li > span, .bottom-blog-mycomments-pagination ul.pagination li > a, .bottom-blog-mycomments-pagination ul.pagination li > span { color: '.Configuration::get('STSN_PAGINATION_COLOR').'; }';
        if(Configuration::get('STSN_PAGINATION_COLOR_HOVER'))  
            $css .= '.top-pagination-content ul.pagination li > a:hover, .bottom-pagination-content ul.pagination li > a:hover, .bottom-blog-pagination ul.pagination li > a:hover, .bottom-blog-mycomments-pagination ul.pagination li > a:hover, .top-pagination-content ul.pagination .current > a, .top-pagination-content ul.pagination .current > span, .bottom-pagination-content ul.pagination .current > a, .bottom-pagination-content ul.pagination .current > span, .bottom-blog-pagination ul.pagination .current > a, .bottom-blog-pagination ul.pagination .current > span, .bottom-blog-mycomments-pagination ul.pagination .current > a, .bottom-blog-mycomments-pagination ul.pagination .current > span{ color: '.Configuration::get('STSN_PAGINATION_COLOR_HOVER').'; }';

        if(Configuration::get('STSN_PAGINATION_BG'))  
            $css .= '.top-pagination-content ul.pagination li > a, .top-pagination-content ul.pagination li > span, .bottom-pagination-content ul.pagination li > a, .bottom-pagination-content ul.pagination li > span, .bottom-blog-pagination ul.pagination li > a, .bottom-blog-pagination ul.pagination li > span, .bottom-blog-mycomments-pagination ul.pagination li > a, .bottom-blog-mycomments-pagination ul.pagination li > span { background-color: '.Configuration::get('STSN_PAGINATION_BG').'; }';
        if(Configuration::get('STSN_PAGINATION_BG_HOVER'))  
            $css .= '.top-pagination-content ul.pagination li > a:hover, .bottom-pagination-content ul.pagination li > a:hover, .bottom-blog-pagination ul.pagination li > a:hover, .bottom-blog-mycomments-pagination ul.pagination li > a:hover, .top-pagination-content ul.pagination .current > a, .top-pagination-content ul.pagination .current > span, .bottom-pagination-content ul.pagination .current > a, .bottom-pagination-content ul.pagination .current > span, .bottom-blog-pagination ul.pagination .current > a, .bottom-blog-pagination ul.pagination .current > span, .bottom-blog-mycomments-pagination ul.pagination .current > a, .bottom-blog-mycomments-pagination ul.pagination .current > span{ background-color: '.Configuration::get('STSN_PAGINATION_BG_HOVER').'; }';
        if(Configuration::get('STSN_PAGINATION_BORDER')) 
            $css .= '.top-pagination-content ul.pagination li > a, .top-pagination-content ul.pagination li > span, .bottom-pagination-content ul.pagination li > a, .bottom-pagination-content ul.pagination li > span, .bottom-blog-pagination ul.pagination li > a, .bottom-blog-pagination ul.pagination li > span, .bottom-blog-mycomments-pagination ul.pagination li > a, .bottom-blog-mycomments-pagination ul.pagination li > span, .top-pagination-content ul.pagination .current > a, .top-pagination-content ul.pagination .current > span, .bottom-pagination-content ul.pagination .current > a, .bottom-pagination-content ul.pagination .current > span, .bottom-blog-pagination ul.pagination .current > a, .bottom-blog-pagination ul.pagination .current > span, .bottom-blog-mycomments-pagination ul.pagination .current > a, .bottom-blog-mycomments-pagination ul.pagination .current > span{ border-color: '.Configuration::get('STSN_PAGINATION_BORDER').'; }';
        
        if($shop_logo_width = Configuration::get('SHOP_LOGO_WIDTH'))
        {
            $css .= '#mobile_header_logo img{max-width: '.($shop_logo_width>600 ? '600px' : $shop_logo_width.'px').';}.mobile_bar_left_layout #mobile_header_logo img{max-width: '.($shop_logo_width>530 ? '530px' : $shop_logo_width.'px').';}';
            $res_css .= '@media (max-width: 767px) {#mobile_header_logo img{max-width: '.($shop_logo_width>330 ? '330px' : $shop_logo_width.'px').';}.mobile_bar_left_layout #mobile_header_logo img{max-width: '.($shop_logo_width>238 ? '238px' : $shop_logo_width.'px').';}}';
            $res_css .= '@media (max-width: 480px) {#mobile_header_logo img{max-width: '.($shop_logo_width>180 ? '180px' : $shop_logo_width.'px').';}.mobile_bar_left_layout #mobile_header_logo img{max-width: '.($shop_logo_width>106 ? '106px' : $shop_logo_width.'px').';}}';
        }
        
        $css .= $res_css;
        if (Configuration::get('STSN_CUSTOM_CSS') != "")
			$css .= html_entity_decode(str_replace('¤', '\\', Configuration::get('STSN_CUSTOM_CSS')));
        
        if (Shop::getContext() == Shop::CONTEXT_SHOP)
        {
            $cssFile = $this->local_path."views/css/customer-s".(int)$this->context->shop->getContextShopID().".css";
    		$write_fd = fopen($cssFile, 'w') or die('can\'t open file "'.$cssFile.'"');
    		fwrite($write_fd, $css);
    		fclose($write_fd);
        }
        if (Configuration::get('STSN_CUSTOM_JS') != "")
		{
		    $jsFile = $this->local_path."views/js/customer".$id_shop.".js";
    		$write_fd = fopen($jsFile, 'w') or die('can\'t open file "'.$jsFile.'"');
    		fwrite($write_fd, html_entity_decode(Configuration::get('STSN_CUSTOM_JS')));
    		fclose($write_fd);
		}
        else
            if(file_exists($this->local_path.'views/js/customer'.$id_shop.'.js'))
                unlink($this->local_path.'views/js/customer'.$id_shop.'.js');
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
    
	public function hookActionShopDataDuplication($params)
	{
	    $this->_useDefault(false,shop::getGroupFromShop($params['new_id_shop']),$params['new_id_shop']);
	}
    public function hookHeader($params)
	{
        $id_shop = (int)Shop::getContextShopID();
	    $theme_font = array();
    	$theme_font[] = Configuration::get('STSN_FONT_TEXT');
        $theme_font[] = Configuration::get('STSN_FONT_HEADING');
        $theme_font[] = Configuration::get('STSN_FONT_PRODUCT_NAME');
        $theme_font[] = Configuration::get('STSN_FONT_PRICE');
        $theme_font[] = Configuration::get('STSN_FONT_MENU');
        $theme_font[] = Configuration::get('STSN_ADV_FONT_MENU');
        $theme_font[] = Configuration::get('STSN_ADV_SECOND_FONT_MENU');
        $theme_font[] = Configuration::get('STSN_ADV_THIRD_FONT_MENU');
    	$theme_font[] = Configuration::get('STSN_FONT_CART_BTN');
    	$theme_font[] = Configuration::get('STSN_FONT_TITLE');
        $theme_font[] = Configuration::get('STSN_C_MENU_FONT');
        $theme_font[] = Configuration::get('STSN_ADV_VER_FONT_MENU');
            
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
        
        foreach($this->module_font AS $module) {
            if ($module_font = Configuration::get('STSN_FONT_MODULE_'.strtoupper($module))) {
                foreach(explode('|', $module_font) AS $font) {
                    $theme_font[] = $font;
                }
            }
        }
        $theme_font = array_unique($theme_font);
        if(is_array($theme_font) && count($theme_font)) {
            $fonts = array();
            foreach($theme_font as $v) {
                $arr = explode(':', $v);
                if(!isset($arr[0]) || !$arr[0] || $arr[0] == $this->_font_inherit || in_array($arr[0], $this->systemFonts))
                    continue;
                $gf_key = preg_replace('/\s/iS','_',$arr[0]);
                if (isset($arr[1]) && !in_array($arr[1], $this->googleFonts[$gf_key]['variants']))
                    $v = $arr[0];
                $fonts[] = str_replace(' ', '+', $v).($font_support ? rtrim($font_support,',') : '');
            }
            if ($fonts) {
                $this->context->controller->addCSS($this->context->link->protocol_content."fonts.googleapis.com/css?family=".implode('|', $fonts), 'all');
            }  
        }
            
	    $footer_img_src = '';
	    if(Configuration::get('STSN_FOOTER_IMG') !='' )
	       $footer_img_src = (Configuration::get('STSN_FOOTER_IMG')==$this->defaults["footer_img"]['val'] ? _MODULE_DIR_.$this->name.'/' : _THEME_PROD_PIC_DIR_).Configuration::get('STSN_FOOTER_IMG');

        $mobile_detect = $this->context->getMobileDetect();
        $mobile_device = $mobile_detect->isMobile() || $mobile_detect->isTablet();
        
        $enabled_version_swithing = Configuration::get('STSN_VERSION_SWITCHING') && $mobile_device;
        $version_switching = isset($this->context->cookie->version_switching) ? (int)$this->context->cookie->version_switching : 0;
        
        $zoom_type = (int)Configuration::get('STSN_ZOOM_TYPE');
        $is_responsive = Configuration::get('STSN_RESPONSIVE');
	    $theme_settings = array(
            'theme_version' => $this->version,
            'boxstyle' => (int)Configuration::get('STSN_BOXSTYLE'),
            'footer_img_src' => $footer_img_src, 
            'copyright_text' => html_entity_decode(Configuration::get('STSN_COPYRIGHT_TEXT', $this->context->language->id)),
            'search_label' => Configuration::get('STSN_SEARCH_LABEL', $this->context->language->id),
            'newsletter_label' => Configuration::get('STSN_NEWSLETTER_LABEL', $this->context->language->id),
            'icon_iphone_57' => Configuration::get('STSN_ICON_IPHONE_57') ? $this->_path.Configuration::get('STSN_ICON_IPHONE_57') : '',
            'icon_iphone_72' => Configuration::get('STSN_ICON_IPHONE_72') ? $this->_path.Configuration::get('STSN_ICON_IPHONE_72') : '',
            'icon_iphone_114' => Configuration::get('STSN_ICON_IPHONE_114') ? $this->_path.Configuration::get('STSN_ICON_IPHONE_114') : '',
            'icon_iphone_144' => Configuration::get('STSN_ICON_IPHONE_144') ? $this->_path.Configuration::get('STSN_ICON_IPHONE_144') : '',
            'retina_logo' => Configuration::get('STSN_RETINA_LOGO') ? Configuration::get('STSN_RETINA_LOGO') : '',
            'show_cate_header' => Configuration::get('STSN_SHOW_CATE_HEADER'),
            'responsive' => $is_responsive,
            'enabled_version_swithing' => $enabled_version_swithing,
            'version_switching' => $version_switching,
            'responsive_max' => Configuration::get('STSN_RESPONSIVE_MAX'),
            'scroll_to_top' => Configuration::get('STSN_SCROLL_TO_TOP'),
            'addtocart_animation' => Configuration::get('STSN_ADDTOCART_ANIMATION'),
            'google_rich_snippets' => Configuration::get('STSN_GOOGLE_RICH_SNIPPETS'),
            'display_tax_label' => Configuration::get('STSN_DISPLAY_TAX_LABEL'),
            'discount_percentage' => Configuration::get('STSN_DISCOUNT_PERCENTAGE'),
            'flyout_buttons' => Configuration::get('STSN_FLYOUT_BUTTONS'),
            'length_of_product_name' => Configuration::get('STSN_LENGTH_OF_PRODUCT_NAME'),
            'logo_position' => Configuration::get('STSN_LOGO_POSITION'),
            'body_has_background' => (Configuration::get('STSN_BODY_BG_COLOR') || Configuration::get('STSN_BODY_BG_PATTERN') || Configuration::get('STSN_BODY_BG_IMG')),
            'tracking_code' => html_entity_decode(Configuration::get('STSN_TRACKING_CODE')),
            'head_code' =>  html_entity_decode(Configuration::get('STSN_HEAD_CODE')),
            'display_cate_desc_full' => Configuration::get('STSN_DISPLAY_CATE_DESC_FULL'), 
            'display_pro_tags' => Configuration::get('STSN_DISPLAY_PRO_TAGS'), 
            'zoom_type' => $zoom_type, 
            'sticky_menu' => Configuration::get('STSN_STICKY_MENU'), 
            'sticky_adv' => Configuration::get('STSN_ADV_MENU_STICKY'), 
            'is_rtl' => $this->context->language->is_rtl, 
            'categories_per_lg' => Configuration::get('STSN_CATEGORIES_PER_LG_0'),
            'categories_per_md' => Configuration::get('STSN_CATEGORIES_PER_MD_0'),
            'categories_per_sm' => Configuration::get('STSN_CATEGORIES_PER_SM_0'),
            'categories_per_xs' => Configuration::get('STSN_CATEGORIES_PER_XS_0'),
            'categories_per_xxs' => Configuration::get('STSN_CATEGORIES_PER_XXS_0'),
            'product_big_image' => Configuration::get('STSN_PRODUCT_BIG_IMAGE'), 
            'breadcrumb_width' => Configuration::get('STSN_BREADCRUMB_WIDTH'), 
            'welcome' => Configuration::get('STSN_WELCOME', $this->context->language->id),
            'welcome_logged' => Configuration::get('STSN_WELCOME_LOGGED', $this->context->language->id),
            'welcome_link' => Configuration::get('STSN_WELCOME_LINK', $this->context->language->id),
            'is_mobile_device' => $mobile_device && $is_responsive,
            'customer_group_without_tax' => Group::getPriceDisplayMethod($this->context->customer->id_default_group),
            'retina' => Configuration::get('STSN_RETINA'),
            'logo_width' => Configuration::get('STSN_LOGO_WIDTH') ? Configuration::get('STSN_LOGO_WIDTH') : 4,
            //In case someone who forgot to disable the default moblie theme
            'st_logo_image_width' => Configuration::get('SHOP_LOGO_WIDTH'),
            'st_logo_image_height' => Configuration::get('SHOP_LOGO_HEIGHT'),
            'transparent_header' => Configuration::get('STSN_TRANSPARENT_HEADER'),
            'sticky_mobile_header' => (int)Configuration::get('STSN_STICKY_MOBILE_HEADER'),
            'sticky_mobile_header_height' => (int)Configuration::get('STSN_STICKY_MOBILE_HEADER_HEIGHT'),
        );

        
        Media::addJsDef(array(
            'st_submemus_animation' =>(int)Configuration::get('STSN_SUBMEMUS_ANIMATION'),
            'st_adv_submemus_animation' =>(int)Configuration::get('STSN_ADV_SUBMEMUS_ANIMATION'),
        ));

        $this->context->controller->addJS($this->_path.'views/js/global.js');
        $this->context->controller->addJS($this->_path.'views/js/owl.carousel.js');
        $this->context->controller->addJS($this->_path.'views/js/jquery.parallax-1.1.3.js');
        if($zoom_type<2)
            $this->context->controller->addJqueryPlugin('jqzoom');

        if(file_exists($this->local_path.'views/js/customer'.$id_shop.'.js'))
		  $theme_settings['custom_js'] = $this->_path.'views/js/customer'.$id_shop.'.js';
        
        if (Shop::getContext() == Shop::CONTEXT_SHOP)
        {
            if(!file_exists($this->local_path.'views/css/customer-s'.$this->context->shop->getContextShopID().'.css'))
                $this->writeCss();
            $theme_settings['custom_css'] = $this->_path.'views/css/customer-s'.$this->context->shop->getContextShopID().'.css?'.substr(md5($this->version), 0, 10);
        }
        $theme_settings['custom_css_media'] = 'all';

		if($is_responsive && (!$enabled_version_swithing || $version_switching==0))
        {
            $this->context->controller->addCSS(_THEME_CSS_DIR_.'responsive.css', 'all');
            if ($this->context->language->is_rtl)
                $this->context->controller->addCSS(_THEME_CSS_DIR_.'rtl-responsive.css', 'all');

            if(Configuration::get('STSN_RESPONSIVE_MAX'))
                $this->context->controller->addCSS(_THEME_CSS_DIR_.'responsive-md.css', 'all');
            else
                $this->context->controller->addCSS(_THEME_CSS_DIR_.'responsive-md-max.css', 'all');
        }else{
            $this->context->controller->addCSS(_THEME_CSS_DIR_.'responsiveness.css', 'all');
        }

        if($is_responsive && (!$enabled_version_swithing || $version_switching==0))
        {
            if(Configuration::get('STSN_RESPONSIVE_MAX'))
            {
                for($i=1;$i<=Configuration::get('STSN_RESPONSIVE_MAX');$i++)
                    $this->context->controller->addCSS(_THEME_CSS_DIR_.'responsive-'.$i.'.css', 'all');
            }
        }
        //
        $this->context->controller->addCSS($this->_path.'views/css/animate.min.css', 'all');
        $this->context->controller->addJqueryPlugin('hoverIntent');
        $this->context->controller->addJqueryPlugin('fancybox');
        $this->context->controller->addCSS(_THEME_CSS_DIR_.'product_list.css');
        //Make sure ui slider css got loaded
        $ui_slider_path = Media::getJqueryUIPath('ui.slider', 'base', true);
        $this->context->controller->addCSS($ui_slider_path['css'], 'all', false);

		$this->context->smarty->assign('sttheme', $theme_settings);

		return $this->display(__FILE__, 'stthemeeditor-header.tpl');
	}
    
    public function getProductRatingAverage($id_product)
    {
        if(Configuration::get('STSN_DISPLAY_COMMENT_RATING') && Module::isInstalled('productcomments') && Module::isEnabled('productcomments'))
        {
            if (!file_exists(_PS_MODULE_DIR_.'productcomments/ProductComment.php'))
                return false;
            include_once(_PS_MODULE_DIR_.'productcomments/ProductComment.php');
            $averageGrade = ProductComment::getAverageGrade($id_product);

            $config_display = Configuration::get('STSN_DISPLAY_COMMENT_RATING');
            if(($config_display==1 || $config_display==3) && !$averageGrade['grade'])
                return ;

            if($config_display==3 || $config_display==4)
                $this->context->smarty->assign('commentNbr', ProductComment::getCommentNumber($id_product));
             $this->context->smarty->assign(array(
                'ratings' => ProductComment::getRatings($id_product),
                'ratingAverage' => round($averageGrade['grade']),
            ));

            return $this->display(__FILE__, 'product_rating_average.tpl');
        }
        return false;
    }
    public function getProductAttributes($id_product)
    {
        if(!$show_pro_attr = Configuration::get('STSN_DISPLAY_PRO_ATTR'))
            return false;
        $product = new Product($id_product);
		if (!isset($product) || !Validate::isLoadedObject($product))
            return false;
		$groups = array();
		$attributes_groups = $product->getAttributesGroups($this->context->language->id);
        if (is_array($attributes_groups) && $attributes_groups)
		{
            foreach ($attributes_groups as $k => $row)
			{
			     if (!isset($groups[$row['id_attribute_group']]))
					$groups[$row['id_attribute_group']] = array(
						'name' => $row['public_group_name'],
						'group_type' => $row['group_type'],
						'default' => -1,
					);
                $groups[$row['id_attribute_group']]['attributes'][$row['id_attribute']] = $row['attribute_name'];
				if (!isset($groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']]))
					$groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] = 0;
				$groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] += (int)$row['quantity'];
			}
            $this->context->smarty->assign(array(
				'st_groups' => $groups,
                'show_pro_attr' => $show_pro_attr,
            ));
            return $this->display(__FILE__, 'product_attributes.tpl');
        }
        return false;
    }
    public function getAddToWhishlistButton($id_product,$show_icon)
    {
        if(Module::isInstalled('blockwishlist') && Module::isEnabled('blockwishlist'))
        {
            $this->context->smarty->assign(array(
                'id_product' => $id_product,
                'show_icon' => $show_icon,
            ));
            return $this->display(__FILE__, 'product_add_to_wishlist.tpl');
        }
    }
    public function isInstalledWishlist()
    {
        return (Module::isInstalled('blockwishlist') && Module::isEnabled('blockwishlist')) ? true: false;
    }
    public function getYotpoDomain()
    {
        if(Module::isInstalled('yotpo') && Module::isEnabled('yotpo'))
            return Tools::getShopDomain(false,false);
        return '';
    }
    public function getYotpoLanguage()
    {
        if(Module::isInstalled('yotpo') && Module::isEnabled('yotpo'))
        {
            $language = Configuration::get('yotpo_language');
            if (Configuration::get('yotpo_language_as_site') == true) {
                if (isset($this->context->language) && isset($this->context->language->iso_code)) {
                    $language = $this->context->language->iso_code;
                }
                else {
                    $language = Language::getIsoById( (int)$this->context->cookie->id_lang );
                }   
            }  
            return $language;         
        }
        return '';

    }
    public function getManufacturerLink($id_manufacturer)
    {
	    if (!$this->isCached('manufacturer_link.tpl', $this->stGetCacheId($id_manufacturer . '-manufacturer_link')))
        {
		  	$this->context->smarty->assign(array(
              'product_manufacturer' => new Manufacturer((int)$id_manufacturer, $this->context->language->id),
            ));
        }
         
        return $this->display(__FILE__, 'manufacturer_link.tpl',$this->stGetCacheId($id_manufacturer . '-manufacturer_link'));
    }
    public function getCarouselJavascript($identify)
    {
	    if (!$this->isCached('carousel_javascript.tpl', $this->stGetCacheId($identify)))
        {
            if($identify=='crossselling')
                $pre = 'STSN_CS';
            else if($identify=='accessories')
                $pre = 'STSN_AC';
            else if($identify=='productscategory')
                $pre = 'STSN_PC';
            if(!isset($pre))
                return false;
            $this->context->smarty->assign(array(
                'identify' => $identify,
                'easing' => self::$easing[Configuration::get($pre.'_EASING')]['name'],
                'slideshow' => Configuration::get($pre.'_SLIDESHOW'),
                's_speed' => Configuration::get($pre.'_S_SPEED'),
                'a_speed' => Configuration::get($pre.'_A_SPEED'),
                'pause_on_hover' => Configuration::get($pre.'_PAUSE_ON_HOVER'),
                'loop' => Configuration::get($pre.'_LOOP'),
                'move' => Configuration::get($pre.'_MOVE'),
                'pro_per_lg'       => (int)Configuration::get($pre.'_PER_LG_0'),
                'pro_per_md'       => (int)Configuration::get($pre.'_PER_MD_0'),
                'pro_per_sm'       => (int)Configuration::get($pre.'_PER_SM_0'),
                'pro_per_xs'       => (int)Configuration::get($pre.'_PER_XS_0'),
                'pro_per_xxs'       => (int)Configuration::get($pre.'_PER_XXS_0'),
            ));
        }
        return $this->display(__FILE__, 'carousel_javascript.tpl',$this->stGetCacheId($identify));
    }
    
	protected function stGetCacheId($key,$name = null)
	{
		$cache_id = parent::getCacheId($name);
		return $cache_id.'_'.$key;
	}
    
    public function hookDisplayAnywhere($params)
    {
	    if(!isset($params['caller']) || $params['caller']!=$this->name)
            return false;
        if(isset($params['function']) && method_exists($this,$params['function']))
        {
            if($params['function']=='getProductRatingAverage')
                return call_user_func_array(array($this,$params['function']),array($params['id_product']));
            elseif($params['function']=='getAddToWhishlistButton')
                return call_user_func_array(array($this,$params['function']),array($params['id_product'],$params['show_icon']));
            elseif($params['function']=='getCarouselJavascript')
                return call_user_func_array(array($this,$params['function']),array($params['identify']));
            elseif($params['function']=='getProductAttributes')
                return call_user_func_array(array($this,$params['function']),array($params['id_product']));
            elseif($params['function']=='getManufacturerLink')
                return call_user_func_array(array($this,$params['function']),array($params['id_manufacturer']));
            elseif($params['function']=='getFlyoutButtonsClass')
                return call_user_func(array($this,$params['function']));
            elseif($params['function']=='getProductNameClass')
                return call_user_func(array($this,$params['function']));
            elseif($params['function']=='getSaleStyleFlag')
                return call_user_func_array(array($this,$params['function']),array($params['percentage_amount'],$params['reduction'],$params['price_without_reduction'],$params['price']));
            elseif($params['function']=='getSaleStyleCircle')
                return call_user_func_array(array($this,$params['function']),array($params['percentage_amount'],$params['reduction'],$params['price_without_reduction'],$params['price']));
            elseif($params['function']=='getLengthOfProductName')
                return call_user_func_array(array($this,$params['function']),array($params['product_name']));
            elseif($params['function']=='getProductsPerRow')
                return call_user_func_array(array($this,$params['function']),array($params['for_w'], $params['devices']));
            elseif($params['function']=='setColumnsNbr')
                return call_user_func_array(array($this,$params['function']),array($params['columns_nbr'], $params['page_name']));
            elseif($params['function']=='getShortDescOnGrid')
                return call_user_func(array($this,$params['function']));
            elseif($params['function']=='getDisplayColorList')
                return call_user_func(array($this,$params['function']));
            elseif($params['function']=='getCategoryDefaultView')
                return call_user_func(array($this,$params['function']));
            elseif($params['function']=='isInstalledWishlist')
                return call_user_func(array($this,$params['function']));
            elseif($params['function']=='getYotpoDomain')
                return call_user_func(array($this,$params['function']));
            elseif($params['function']=='getYotpoLanguage')
                return call_user_func(array($this,$params['function']));
            else
                return false;
        }
        return false;
    }
    public function hookDisplayRightColumnProduct($params)
    {        
	    if(!Module::isInstalled('blockviewed') || !Module::isEnabled('blockviewed'))
            return false;
            
		$id_product = (int)Tools::getValue('id_product');
        if(!$id_product)
            return false;
            
		$productsViewed = (isset($params['cookie']->viewed) && !empty($params['cookie']->viewed)) ? array_slice(array_reverse(explode(',', $params['cookie']->viewed)), 0, Configuration::get('PRODUCTS_VIEWED_NBR')) : array();

		if ($id_product && !in_array($id_product, $productsViewed))
		{
			if(isset($params['cookie']->viewed) && !empty($params['cookie']->viewed))
		  		$params['cookie']->viewed .= ',' . (int)$id_product;
			else
		  		$params['cookie']->viewed = (int)$id_product;
		}
        return false;
    }
    public function getFlyoutButtonsClass()
    {
        return Configuration::get('STSN_FLYOUT_BUTTONS') ? ' hover_fly_static ' : '';
    }
    
    public function getProductNameClass()
    {
        return Configuration::get('STSN_LENGTH_OF_PRODUCT_NAME') ? ' nohidden ' : '';
    }
    
    public function getSaleStyleFlag($percentage_amount,$reduction,$price_without_reduction,$price)
    {
        if(Configuration::get('STSN_DISCOUNT_PERCENTAGE')!=1)
            return false;
        $this->context->smarty->assign(array(
            'percentage_amount'  => $percentage_amount,
            'reduction'  => $reduction,
            'price_without_reduction'  => $price_without_reduction,
			'price' => $price,
        ));    
		return $this->display(__FILE__, 'sale_style_flag.tpl');
    }
    public function getSaleStyleCircle($percentage_amount,$reduction,$price_without_reduction,$price)
    {
        if(Configuration::get('STSN_DISCOUNT_PERCENTAGE')!=2)
            return false;
        $this->context->smarty->assign(array(
            'percentage_amount'  => $percentage_amount,
            'reduction'  => $reduction,
            'price_without_reduction'  => $price_without_reduction,
			'price' => $price,
        ));    
		return $this->display(__FILE__, 'sale_style_circle.tpl');
    }
    public function getLengthOfProductName($product_name)
    {
        $length_of_product_name = Configuration::get('STSN_LENGTH_OF_PRODUCT_NAME');
        $this->context->smarty->assign(array(
            'product_name_full' => $length_of_product_name==2,
            'length_of_product_name'  => ($length_of_product_name==1 ? 70 : 35),
			'product_name' => $product_name,
        ));    
		return $this->display(__FILE__, 'lenght_of_product_name.tpl');
    }
    public function initTab()
    {
        $html = '<div class="sidebar col-xs-12 col-lg-2"><ul class="nav nav-tabs">';
        foreach(self::$tabs AS $tab)
            $html .= '<li class="nav-item"><a href="javascript:;" title="'.$this->l($tab['name']).'" data-fieldset="'.$tab['id'].'">'.$this->l($tab['name']).'</a></li>';
        $html .= '</ul></div>';
        return $html;
    }
    public function initToolbarBtn()
    {
        $token = Tools::getAdminTokenLite('AdminModules');
        $toolbar_btn = array(
            'demo_1' => array(
                'desc' => $this->l('Demo 1'),
                'class' => 'icon-plus-sign',
                'js' => 'if (confirm(\''.$this->l('Importing demo store 1, are your sure?').'\')){return true;}else{event.preventDefault();}',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&predefineddemostore'.$this->name.'=demo_1&token='.$token,
            ),
            'demo_2' => array(
                'desc' => $this->l('Demo 2'),
                'class' => 'icon-plus-sign',
                'js' => 'if (confirm(\''.$this->l('Importing demo store 2, are your sure?').'\')){return true;}else{event.preventDefault();}',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&predefineddemostore'.$this->name.'=demo_2&token='.$token,
            ),
            'demo_3' => array(
                'desc' => $this->l('Demo 3'),
                'class' => 'icon-plus-sign',
                'js' => 'if (confirm(\''.$this->l('Importing demo store 3, are your sure?').'\')){return true;}else{event.preventDefault();}',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&predefineddemostore'.$this->name.'=demo_3&token='.$token,
            ),
            'demo_4' => array(
                'desc' => $this->l('Demo 4'),
                'class' => 'icon-plus-sign',
                'js' => 'if (confirm(\''.$this->l('Importing demo store 4, are your sure?').'\')){return true;}else{event.preventDefault();}',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&predefineddemostore'.$this->name.'=demo_4&token='.$token,
            ),
            'demo_5' => array(
                'desc' => $this->l('Demo 5'),
                'class' => 'icon-plus-sign',
                'js' => 'if (confirm(\''.$this->l('Importing demo store 5, are your sure?').'\')){return true;}else{event.preventDefault();}',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&predefineddemostore'.$this->name.'=demo_5&token='.$token,
            ),
            'demo_6' => array(
                'desc' => $this->l('Demo 6'),
                'class' => 'icon-plus-sign',
                'js' => 'if (confirm(\''.$this->l('Importing demo store 6, are your sure?').'\')){return true;}else{event.preventDefault();}',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&predefineddemostore'.$this->name.'=demo_6&token='.$token,
            ),
            'demo_7' => array(
                'desc' => $this->l('Demo 7'),
                'class' => 'icon-plus-sign',
                'js' => 'if (confirm(\''.$this->l('Importing demo store 7, are your sure?').'\')){return true;}else{event.preventDefault();}',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&predefineddemostore'.$this->name.'=demo_7&token='.$token,
            ),
            'demo_8' => array(
                'desc' => $this->l('Demo 8'),
                'class' => 'icon-plus-sign',
                'js' => 'if (confirm(\''.$this->l('Importing demo store 8, are your sure?').'\')){return true;}else{event.preventDefault();}',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&predefineddemostore'.$this->name.'=demo_8&token='.$token,
            ),
            'demo_9' => array(
                'desc' => $this->l('Demo 9'),
                'class' => 'icon-plus-sign',
                'js' => 'if (confirm(\''.$this->l('Importing demo store 9, are your sure?').'\')){return true;}else{event.preventDefault();}',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&predefineddemostore'.$this->name.'=demo_9&token='.$token,
            ),
            'demo_10' => array(
                'desc' => $this->l('Demo 10'),
                'class' => 'icon-plus-sign',
                'js' => 'if (confirm(\''.$this->l('Importing demo store 10, are your sure?').'\')){return true;}else{event.preventDefault();}',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&predefineddemostore'.$this->name.'=demo_10&token='.$token,
            ),
            'demo_12' => array(
                'desc' => $this->l('Demo 12'),
                'class' => 'icon-plus-sign',
                'js' => 'if (confirm(\''.$this->l('Importing demo store 12, are your sure?').'\')){return true;}else{event.preventDefault();}',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&predefineddemostore'.$this->name.'=demo_12&token='.$token,
            ),
            'demo_13' => array(
                'desc' => $this->l('Demo 13'),
                'class' => 'icon-plus-sign',
                'js' => 'if (confirm(\''.$this->l('Importing demo store 13, are your sure?').'\')){return true;}else{event.preventDefault();}',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&predefineddemostore'.$this->name.'=demo_13&token='.$token,
            ),
            'demo_14' => array(
                'desc' => $this->l('Demo 14'),
                'class' => 'icon-plus-sign',
                'js' => 'if (confirm(\''.$this->l('Importing demo store 14, are your sure?').'\')){return true;}else{event.preventDefault();}',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&predefineddemostore'.$this->name.'=demo_14&token='.$token,
            ),
            'export' => array(
                'desc' => $this->l('Export'),
                'class' => 'icon-share',
                'js' => '',
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&export'.$this->name.'&token='.$token,
            ),
        );
        $html = '<div class="panel st_toolbtn clearfix">';
        foreach($toolbar_btn AS $k => $btn)
        {
            $html .= '
            <a id="desc-configuration-'.$k.'" class="boolbtn-'.$k.' btn btn-default" onclick="'.$btn['js'].'" href="'.$btn['href'].'" title="'.$btn['desc'].'">
            <span>
            <i class="'.$btn['class'].'"></i> '.$btn['desc'].'</span></a>';
        }
        $html .= '<form class="defaultForm form-horizontal" action="'.AdminController::$currentIndex.'&configure='.$this->name.'&upload'.$this->name.'&token='.$token.'" method="post" enctype="multipart/form-data">
            <div class="form-group">
            <label class="control-label col-lg-2">'.$this->l('Upload a custom configuration file:').'</label>
            <div class="col-lg-10">
            <div class="form-group">
            	<div class="col-sm-6">
            		<input id="xml_config_file_field" type="file" name="xml_config_file_field" class="hide">
            		<div class="dummyfile input-group">
            			<span class="input-group-addon"><i class="icon-file"></i></span>
            			<input id="xml_config_file_field-name" type="text" name="filename" readonly="">
            			<span class="input-group-btn">
            				<button id="xml_config_file_field-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
            					<i class="icon-folder-open"></i> '.$this->l('Choose a .xml file').'</button>
            			</span>
            		</div>
                    <button type="submit" value="1" name="uploadconfig" id="uploadconfig" class="btn btn-default" data="'.$this->l('Your current module settings will be overrided, are your sure?').'"><i class="icon icon-upload"></i> '.$this->l('Upload and import the file').'</button>
            	</div>
            </div>
            </div>
            </div>
            </form>
            <div class="alert alert-info"><p>'.$this->l('1. IMPORTANT Upload files and folders in the "Sample data" folder in the main .zip archieve to the /upload/ folder before importing.').'</p><p>'.$this->l('2. Click "Demo x" buttons to import predefined demos.').'</p><p>'.$this->l('3. Demo 1, 4, 8, 9, 13, 15 and 16 have Revolustion slider, if you are going to import them, make sure the Revolustion slider module is installed. A sample revolution slider will be imported. Other sliders showing one the demo are located in the "/Slider revolution v5/examples" in the main .zip archieve.').'</p><p>'.$this->l('4. "Featured categories slider" module and "Product slider for each category" module can not be expored/imported, because of categories are differnt from site to site.').'</p><p>'.$this->l('5. Sample accounts will be appied to these modules "Facebook page plugin", "Twitter Embedded Timelines" and "Instagram block", so you are going to use your account instead after importing.').'</p></div>';
        $html .= '</div>';
        return $html;
    }
    private function getConfigFieldsValues()
    {
        $fields_values = array();
        foreach($this->defaults as $k=>$v)
        {
            $fields_values[$k] = Configuration::get('STSN_'.strtoupper($k));
            if (isset($v['esc']) && $v['esc'])
                $fields_values[$k] = html_entity_decode($fields_values[$k]);
        }
        
        if (isset($fields_values['custom_css']) && $fields_values['custom_css'])
            $fields_values['custom_css'] = str_replace('¤', '\\', $fields_values['custom_css']);    
        
        $languages = Language::getLanguages(false);    
		foreach ($languages as $language)
        {
            $fields_values['welcome'][$language['id_lang']] = Configuration::get('STSN_WELCOME', $language['id_lang']);
            $fields_values['welcome_logged'][$language['id_lang']] = Configuration::get('STSN_WELCOME_LOGGED', $language['id_lang']);
            $fields_values['welcome_link'][$language['id_lang']] = Configuration::get('STSN_WELCOME_LINK', $language['id_lang']);
            $fields_values['copyright_text'][$language['id_lang']] = Configuration::get('STSN_COPYRIGHT_TEXT', $language['id_lang']);
            $fields_values['search_label'][$language['id_lang']] = Configuration::get('STSN_SEARCH_LABEL', $language['id_lang']);
            $fields_values['newsletter_label'][$language['id_lang']] = Configuration::get('STSN_NEWSLETTER_LABEL', $language['id_lang']);
        }
        
        foreach ($this->getConfigurableModules() as $module)
			$fields_values[$module['name']] = $module['value'];
        
        //
        $font_text_string = Configuration::get('STSN_FONT_TEXT');
        $font_text_string && $font_text_string = explode(":", $font_text_string);
        $fields_values['font_text_list'] = $font_text_string ? $font_text_string[0] : '';
        
        $font_heading_string = Configuration::get('STSN_FONT_HEADING');
        $font_heading_string && $font_heading_string = explode(":", $font_heading_string);
        $fields_values['font_heading_list'] = $font_heading_string ? $font_heading_string[0] : '';
        
        $font_product_name_string = Configuration::get('STSN_FONT_PRODUCT_NAME');
        $font_product_name_string && $font_product_name_string = explode(":", $font_product_name_string);
        $fields_values['font_product_name_list'] = $font_product_name_string ? $font_product_name_string[0] : '';

        $font_price_string = Configuration::get('STSN_FONT_PRICE');
        $font_price_string && $font_price_string = explode(":", $font_price_string);
        $fields_values['font_price_list'] = $font_price_string ? $font_price_string[0] : '';
        
        $font_menu_string = Configuration::get('STSN_FONT_MENU');
        $font_menu_string && $font_menu_string = explode(":", $font_menu_string);
        $fields_values['font_menu_list'] = $font_menu_string ? $font_menu_string[0] : '';

        $adv_font_menu_string = Configuration::get('STSN_ADV_FONT_MENU');
        $adv_font_menu_string && $adv_font_menu_string = explode(":", $adv_font_menu_string);
        $fields_values['adv_font_menu_list'] = $adv_font_menu_string ? $adv_font_menu_string[0] : '';
        
        $adv_second_font_menu_string = Configuration::get('STSN_ADV_SECOND_FONT_MENU');
        $adv_second_font_menu_string && $adv_second_font_menu_string = explode(":", $adv_second_font_menu_string);
        $fields_values['adv_second_font_menu_list'] = $adv_second_font_menu_string ? $adv_second_font_menu_string[0] : '';
        
        $adv_third_font_menu_string = Configuration::get('STSN_ADV_THIRD_FONT_MENU');
        $adv_third_font_menu_string && $adv_third_font_menu_string = explode(":", $adv_third_font_menu_string);
        $fields_values['adv_third_font_menu_list'] = $adv_third_font_menu_string ? $adv_third_font_menu_string[0] : '';

        $c_menu_font_string = Configuration::get('STSN_C_MENU_FONT');
        $c_menu_font_string && $c_menu_font_string = explode(":", $c_menu_font_string);
        $fields_values['c_menu_font_list'] = $c_menu_font_string ? $c_menu_font_string[0] : '';
        
        $font_cart_btn_string = Configuration::get('STSN_FONT_CART_BTN');
        $font_cart_btn_string && $font_cart_btn_string = explode(":", $font_cart_btn_string);
        $fields_values['font_cart_btn_list'] = $font_cart_btn_string ? $font_cart_btn_string[0] : '';
        
        $adv_ver_font_menu_string = Configuration::get('STSN_ADV_VER_FONT_MENU');
        $adv_ver_font_menu_string && $adv_ver_font_menu_string = explode(":", $adv_ver_font_menu_string);
        $fields_values['adv_ver_font_menu_list'] = $adv_ver_font_menu_string ? $adv_ver_font_menu_string[0] : '';

        return $fields_values;
    }

    public function getShortDescOnGrid()
    {  
        return Configuration::get('STSN_SHOW_SHORT_DESC_ON_GRID') ? 'display_sd' : '';
    }
    public function getDisplayColorList()
    {
        return Configuration::get('STSN_DISPLAY_COLOR_LIST') ? '' : 'hidden';
    }
    public function getCategoryDefaultView()
    {
        return Configuration::get('STSN_PRODUCT_VIEW')=='list_view' ? 'list' : 'grid';
    }
    public function getProductsPerRow($for_w, $devices)
    {
        switch ($for_w) {
            case 'category':
            case 'prices-drop':
            case 'best-sales':
            case 'manufacturer':
            case 'supplier':
            case 'new-products':
            case 'search':
                $columns_nbr = $this->context->cookie->st_category_columns_nbr;
                $nbr = Configuration::get('STSN_CATEGORY_PRO_PER_'.strtoupper($devices).'_'.$columns_nbr);
                break;  
            case 'hometab':
                $nbr = Configuration::get('STSN_'.strtoupper($for_w).'_PRO_PER_'.strtoupper($devices).'_0');
                break;           
            case 'packitems':
                $nbr = Configuration::get('STSN_'.strtoupper($for_w).'_PRO_PER_'.strtoupper($devices).'_0');
                break;       
            case 'homenew':
                $nbr = Configuration::get('STSN_'.strtoupper($for_w).'_PRO_PER_'.strtoupper($devices).'_0');
                break;  
            case 'featured':
                $nbr = Configuration::get('STSN_'.strtoupper($for_w).'_PRO_PER_'.strtoupper($devices).'_0');
                break;  
            case 'special':
                $nbr = Configuration::get('STSN_'.strtoupper($for_w).'_PRO_PER_'.strtoupper($devices).'_0');
                break;  
            case 'pro_cate':
                $nbr = Configuration::get('STSN_'.strtoupper($for_w).'_PRO_PER_'.strtoupper($devices).'_0');
                break; 
            case 'sellers':
                $nbr = Configuration::get('STSN_'.strtoupper($for_w).'_PRO_PER_'.strtoupper($devices).'_0');
                break;        
            default:
                $nbr = 3;
                break;
        }
        return $nbr ? $nbr : 3;
    }
    public function setColumnsNbr($columns_nbr, $page_name)
    {
        $this->context->cookie->st_category_columns_nbr = (int)$columns_nbr;
    }
    public function BuildDropListGroup($group,$start=1,$end=6)
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
            
            for ($i=$start; $i <= $end; $i++){
                $html .= '<option value="'.$i.'" '.(Configuration::get('STSN_'.strtoupper($k['id'])) == $i ? ' selected="selected"':'').'>'.$i.'</option>';
            }
                                
            $html .= '</select></div>';
        }
        return $html.'</div>';
    }
    public function findCateProPer($k=null)
    {
        $proper = array(
            1 => array(
                array(
                    'id' => 'category_pro_per_lg_1',
                    'label' => $this->l('Large devices'),
                    'tooltip' => $this->l('Desktops (>1200px)'),
                ),
                array(
                    'id' => 'category_pro_per_md_1',
                    'label' => $this->l('Medium devices'),
                    'tooltip' => $this->l('Desktops (>992px)'),
                ),
                array(
                    'id' => 'category_pro_per_sm_1',
                    'label' => $this->l('Small devices'),
                    'tooltip' => $this->l('Tablets (>768px)'),
                ),
                array(
                    'id' => 'category_pro_per_xs_1',
                    'label' => $this->l('Extra small devices'),
                    'tooltip' => $this->l('Phones (>480px)'),
                ),
                array(
                    'id' => 'category_pro_per_xxs_1',
                    'label' => $this->l('Extra extra small devices'),
                    'tooltip' => $this->l('Phones (<480px)'),
                ),
            ),
            2 => array(
                array(
                    'id' => 'category_pro_per_lg_2',
                    'label' => $this->l('Large devices'),
                    'tooltip' => $this->l('Desktops (>1200px)'),
                ),
                array(
                    'id' => 'category_pro_per_md_2',
                    'label' => $this->l('Medium devices'),
                    'tooltip' => $this->l('Desktops (>992px)'),
                ),
                array(
                    'id' => 'category_pro_per_sm_2',
                    'label' => $this->l('Small devices'),
                    'tooltip' => $this->l('Tablets (>768px)'),
                ),
                array(
                    'id' => 'category_pro_per_xs_2',
                    'label' => $this->l('Extra small devices'),
                    'tooltip' => $this->l('Phones (>480px)'),
                ),
                array(
                    'id' => 'category_pro_per_xxs_2',
                    'label' => $this->l('Extra extra small devices'),
                    'tooltip' => $this->l('Phones (<480px)'),
                ),
            ),
            3 => array(
                array(
                    'id' => 'category_pro_per_lg_3',
                    'label' => $this->l('Large devices'),
                    'tooltip' => $this->l('Desktops (>1200px)'),
                ),
                array(
                    'id' => 'category_pro_per_md_3',
                    'label' => $this->l('Medium devices'),
                    'tooltip' => $this->l('Desktops (>992px)'),
                ),
                array(
                    'id' => 'category_pro_per_sm_3',
                    'label' => $this->l('Small devices'),
                    'tooltip' => $this->l('Tablets (>768px)'),
                ),
                array(
                    'id' => 'category_pro_per_xs_3',
                    'label' => $this->l('Extra small devices'),
                    'tooltip' => $this->l('Phones (>480px)'),
                ),
                array(
                    'id' => 'category_pro_per_xxs_3',
                    'label' => $this->l('Extra extra small devices'),
                    'tooltip' => $this->l('Phones (<480px)'),
                ),
            ),
            4 => array(
                array(
                    'id' => 'hometab_pro_per_lg_0',
                    'label' => $this->l('Large devices'),
                    'tooltip' => $this->l('Desktops (>1200px)'),
                ),
                array(
                    'id' => 'hometab_pro_per_md_0',
                    'label' => $this->l('Medium devices'),
                    'tooltip' => $this->l('Desktops (>992px)'),
                ),
                array(
                    'id' => 'hometab_pro_per_sm_0',
                    'label' => $this->l('Small devices'),
                    'tooltip' => $this->l('Tablets (>768px)'),
                ),
                array(
                    'id' => 'hometab_pro_per_xs_0',
                    'label' => $this->l('Extra small devices'),
                    'tooltip' => $this->l('Phones (>480px)'),
                ),
                array(
                    'id' => 'hometab_pro_per_xxs_0',
                    'label' => $this->l('Extra extra small devices'),
                    'tooltip' => $this->l('Phones (<480px)'),
                ),
            ),
            5 => array(
                array(
                    'id' => 'packitems_pro_per_lg_0',
                    'label' => $this->l('Large devices'),
                    'tooltip' => $this->l('Desktops (>1200px)'),
                ),
                array(
                    'id' => 'packitems_pro_per_md_0',
                    'label' => $this->l('Medium devices'),
                    'tooltip' => $this->l('Desktops (>992px)'),
                ),
                array(
                    'id' => 'packitems_pro_per_sm_0',
                    'label' => $this->l('Small devices'),
                    'tooltip' => $this->l('Tablets (>768px)'),
                ),
                array(
                    'id' => 'packitems_pro_per_xs_0',
                    'label' => $this->l('Extra small devices'),
                    'tooltip' => $this->l('Phones (<768px)'),
                ),
                array(
                    'id' => 'packitems_pro_per_xxs_0',
                    'label' => $this->l('Extra extra small devices'),
                    'tooltip' => $this->l('Phones (>480px)'),
                ),
            ),
            6 => array(
                array(
                    'id' => 'categories_per_lg_0',
                    'label' => $this->l('Large devices'),
                    'tooltip' => $this->l('Desktops (>1200px)'),
                ),
                array(
                    'id' => 'categories_per_md_0',
                    'label' => $this->l('Medium devices'),
                    'tooltip' => $this->l('Desktops (>992px)'),
                ),
                array(
                    'id' => 'categories_per_sm_0',
                    'label' => $this->l('Small devices'),
                    'tooltip' => $this->l('Tablets (>768px)'),
                ),
                array(
                    'id' => 'categories_per_xs_0',
                    'label' => $this->l('Extra small devices'),
                    'tooltip' => $this->l('Phones (<768px)'),
                ),
                array(
                    'id' => 'categories_per_xxs_0',
                    'label' => $this->l('Extra extra small devices'),
                    'tooltip' => $this->l('Phones (>480px)'),
                ),
            ),
            7 => array(
                array(
                    'id' => 'cs_per_lg_0',
                    'label' => $this->l('Large devices'),
                    'tooltip' => $this->l('Desktops (>1200px)'),
                ),
                array(
                    'id' => 'cs_per_md_0',
                    'label' => $this->l('Medium devices'),
                    'tooltip' => $this->l('Desktops (>992px)'),
                ),
                array(
                    'id' => 'cs_per_sm_0',
                    'label' => $this->l('Small devices'),
                    'tooltip' => $this->l('Tablets (>768px)'),
                ),
                array(
                    'id' => 'cs_per_xs_0',
                    'label' => $this->l('Extra small devices'),
                    'tooltip' => $this->l('Phones (<768px)'),
                ),
                array(
                    'id' => 'cs_per_xxs_0',
                    'label' => $this->l('Extra extra small devices'),
                    'tooltip' => $this->l('Phones (>480px)'),
                ),
            ),
            8 => array(
                array(
                    'id' => 'pc_per_lg_0',
                    'label' => $this->l('Large devices'),
                    'tooltip' => $this->l('Desktops (>1200px)'),
                ),
                array(
                    'id' => 'pc_per_md_0',
                    'label' => $this->l('Medium devices'),
                    'tooltip' => $this->l('Desktops (>992px)'),
                ),
                array(
                    'id' => 'pc_per_sm_0',
                    'label' => $this->l('Small devices'),
                    'tooltip' => $this->l('Tablets (>768px)'),
                ),
                array(
                    'id' => 'pc_per_xs_0',
                    'label' => $this->l('Extra small devices'),
                    'tooltip' => $this->l('Phones (<768px)'),
                ),
                array(
                    'id' => 'pc_per_xxs_0',
                    'label' => $this->l('Extra extra small devices'),
                    'tooltip' => $this->l('Phones (>480px)'),
                ),
            ),
            9 => array(
                array(
                    'id' => 'ac_per_lg_0',
                    'label' => $this->l('Large devices'),
                    'tooltip' => $this->l('Desktops (>1200px)'),
                ),
                array(
                    'id' => 'ac_per_md_0',
                    'label' => $this->l('Medium devices'),
                    'tooltip' => $this->l('Desktops (>992px)'),
                ),
                array(
                    'id' => 'ac_per_sm_0',
                    'label' => $this->l('Small devices'),
                    'tooltip' => $this->l('Tablets (>768px)'),
                ),
                array(
                    'id' => 'ac_per_xs_0',
                    'label' => $this->l('Extra small devices'),
                    'tooltip' => $this->l('Phones (<768px)'),
                ),
                array(
                    'id' => 'ac_per_xxs_0',
                    'label' => $this->l('Extra extra small devices'),
                    'tooltip' => $this->l('Phones (>480px)'),
                ),
            ),
            10 => array(
                array(
                    'id' => 'pro_image_column_md',
                    'label' => $this->l('Medium devices'),
                    'tooltip' => $this->l('Desktops (>992px)'),
                ),
                array(
                    'id' => 'pro_image_column_sm',
                    'label' => $this->l('Small devices'),
                    'tooltip' => $this->l('Tablets (>768px) and (<=992px)'),
                ),
            ),
            11 => array(
                array(
                    'id' => 'pro_primary_column_md',
                    'label' => $this->l('Medium devices'),
                    'tooltip' => $this->l('Desktops (>992px)'),
                ),
                array(
                    'id' => 'pro_primary_column_sm',
                    'label' => $this->l('Small devices'),
                    'tooltip' => $this->l('Tablets (>768px) and (<=992px)'),
                ),
            ),
            12 => array(
                array(
                    'id' => 'pro_secondary_column_md',
                    'label' => $this->l('Medium devices'),
                    'tooltip' => $this->l('Desktops (>992px)'),
                ),
                array(
                    'id' => 'pro_secondary_column_sm',
                    'label' => $this->l('Small devices'),
                    'tooltip' => $this->l('Tablets (>768px) and (<=992px)'),
                ),
            ),
        );
        return ($k!==null && isset($proper[$k])) ? $proper[$k] : $proper;
    }
    
    protected function updateConfigurableModules()
    {
        foreach ($this->getConfigurableModules() as $module)
		{
			if (!isset($module['is_module']) || !$module['is_module'] || !Validate::isModuleName($module['name']) || !Tools::isSubmit($module['name']))
				continue;

			$module_instance = Module::getInstanceByName($module['name']);
			if ($module_instance === false || !is_object($module_instance))
				continue;

			$is_installed = (int)Validate::isLoadedObject($module_instance);
			if ($is_installed)
			{
				if (($active = (int)Tools::getValue($module['name'])) == $module_instance->active)
					continue;

				if ($active)
					$module_instance->enable();
				else
					$module_instance->disable();
			}
			else
				if ((int)Tools::getValue($module['name']))
					$module_instance->install();
            Cache::clean('Module::isEnabled'.$module['name']); 
		}
        Configuration::updateValue('PS_QUICK_VIEW', (int)Tools::getValue('quick_view'));
    }
    
    protected function getConfigurableModules()
	{
		return array(
            array(
                'label' => $this->l('Hover image'),
                'name' => 'sthoverimage',
                'value' => (int)Module::isEnabled('sthoverimage'),
                'is_module' => true,
                'desc' => $this->l('Display second product image on mouse hover.') 
            ),
			array(
				'label' => $this->l('Add this button'),
				'name' => 'staddthisbutton',
				'value' => (int)Module::isEnabled('staddthisbutton'),
				'is_module' => true,
                'desc' => $this->l('Display add this button on product page, article page.')
			),
            array(
                'label' => $this->l('Enable quick view'),
                'name' => 'quick_view',
                'value' => (int)Tools::getValue('PS_QUICK_VIEW', Configuration::get('PS_QUICK_VIEW'))
            ),
            array(
                'label' => $this->l('Products Comparison'),
                'name' => 'stcompare',
                'value' => (int)Module::isEnabled('stcompare'),
                'is_module' => true,
                'desc' => $this->l('Display products comparison button on right bar')
            ),
			array(
				'label' => $this->l('Facebook Like Box'),
				'name' => 'stfblikebox',
				'value' => (int)Module::isEnabled('stfblikebox'),
				'is_module' => true,
                'desc' => $this->l('Display facebook like box on page footer') 
			),
            array(
                'label' => $this->l('Twitter Embedded Timelines'),
                'name' => 'sttwitterembeddedtimelines',
                'value' => (int)Module::isEnabled('sttwitterembeddedtimelines'),
                'is_module' => true,
                'desc' => $this->l('Enable twitter embedded timelines')
            ),
			array(
				'label' => $this->l('Right bar cart block'),
				'name' => 'strightbarcart',
				'value' => (int)Module::isEnabled('strightbarcart'),
				'is_module' => true,
                'desc' => $this->l('Display cart button on page right bar.')
			),
			array(
				'label' => $this->l('Social networking block'),
				'name' => 'stsocial',
				'value' => (int)Module::isEnabled('stsocial'),
				'is_module' => true,
                'desc' => 'Display links to your store\'s social accounts (Twitter, Facebook, etc.)'
			),
            array(
				'label' => $this->l('Display social sharing buttons on the products page'),
				'name' => 'socialsharing',
				'value' => (int)Module::isEnabled('socialsharing'),
				'is_module' => true,
			),
			array(
				'label' => $this->l('Enable top banner'),
				'name' => 'blockbanner',
				'value' => (int)Module::isEnabled('blockbanner'),
				'is_module' => true,
			),
			array(
				'label' => $this->l('Display your product payment logos'),
				'name' => 'productpaymentlogos',
				'value' => (int)Module::isEnabled('productpaymentlogos'),
				'is_module' => true,
			),
            array(
                'label' => $this->l('Next and previous links on product'),
                'name' => 'stproductlinknav',
                'value' => (int)Module::isEnabled('stproductlinknav'),
                'is_module' => true,
                'desc' => $this->l('Display next and previous links on product page') 
            ),
            array(
                'label' => $this->l('Next and previous links on blog'),
                'name' => 'stbloglinknav',
                'value' => (int)Module::isEnabled('stbloglinknav'),
                'is_module' => true,
                'desc' => $this->l('Display next and previous links on blog article page') 
            ),
		);
	}
    
    public function getImageHtml($src, $id)
    {
        $html = '';
        if ($src && $id)
            $html .= '
			<img src="'.$src.'" class="img_preview">
            <p>
                <a id="'.$id.'" href="javascript:;" class="btn btn-default st_delete_image"><i class="icon-trash"></i> Delete</a>
			</p>
            ';
        return $html;    
    }
    
    public function export()
    {
        $result = '';
        $exports = array();
        
        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP)
            return $this->displayError($this->l('Please select a store to export configurations.'));
        
        $folder = $this->_config_folder;
        if (!is_dir($folder))
            return $this->displayError('"'.$folder.'" '.$this->l('directory isn\'t exists.'));
        elseif (!is_writable($folder))
            return $this->displayError('"'.$folder.'" '.$this->l('directory isn\'t writable.'));
        
        $file = date('YmdH').'_'.(int)Shop::getContextShopID().'.xml';
        
        foreach($this->defaults AS $k => $value)
            if (is_array($value) && isset($value['exp']) && $value['exp'] == 1)
                $exports[$k] = Configuration::get('STSN_'.strtoupper($k));
        
        $languages = Language::getLanguages(false);
        foreach($this->lang_array AS $value)
            if (key_exists($value, $exports))
                foreach ($languages as $language)
                    $exports[$value][$language['id_lang']] = Configuration::get('STSN_'.strtoupper($value), $language['id_lang']);
        
        $editor = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><!-- Copyright Sunnytoo.com --><stthemeeditor></stthemeeditor>');
        foreach($exports AS $key => $value)
        {
            if (in_array($key, $this->lang_array) && is_array($value))
            {
                $lang_text = $editor->addChild($key);
                foreach($value AS $id_lang => $v)
                    $lang_text->addChild('lang_'.$id_lang, Tools::htmlentitiesUTF8($v));
            }
            else
                $editor->addChild($key, $value);
        }
        
        // Export module settings.
        include_once(dirname(__FILE__).'/DemoStore.php');
        $demo = new DemoStore();
        $module_data = $demo->export_modules();
        if ($module_data) {
            $editor->addChild('module_data', base64_encode(serialize($module_data)));
        }
        
        $content = $editor->asXML();
        if (!file_put_contents($folder.$file, $content))
            return $this->displayError($this->l('Create config file failed.'));
        else
        {
            $link = '<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&download'.$this->name.'&file='.$file.'">'._MODULE_DIR_.$this->name.'/config/'.$file.'</a>';
            return $this->displayConfirmation($this->l('Generate config file successfully, Click the link to download : ').$link);
        }   
    }
    public function get_fontello()
    {
        $res= array(
            'css' => '',
            'theme_name' => '',
            'module_name' => $this->_path,
            'classes' => array(),
        );

        $theme_path = _PS_THEME_DIR_;

        $shop = new Shop((int)Context::getContext()->shop->id);
        $theme_name = $shop->getTheme();
        $res['theme_name'] = $theme_name;

        if (_THEME_NAME_ != $theme_name)
            $theme_path = _PS_ROOT_DIR_.'/themes/'.$theme_name.'/';

        if (file_exists($theme_path.'font/config.json'))
        {
            $icons = Tools::jsonDecode(Tools::file_get_contents($theme_path.'font/config.json'));
            if($icons && is_array($icons->glyphs))
                foreach ($icons->glyphs as $icon) {
                    $res['classes'][$icon->code] = 'icon-'.$icon->css;
                }
        }
        if (file_exists($theme_path.'sass/font-fontello/_icons.scss'))
        {
            $icons_css = Tools::file_get_contents($theme_path.'sass/font-fontello/_icons.scss');
            $icons_css = str_replace('.icon-','.fontello_wrap .icon-',$icons_css);
            $res['css'] .= $icons_css;
        }

        return $res;
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
    public function add_quick_access()
    {
        if(!Db::getInstance()->getRow('SELECT id_quick_access FROM '._DB_PREFIX_.'quick_access WHERE link LIKE "%configure=stthemeeditor%"') && class_exists('QuickAccess'))
        {
            $quick_access = new QuickAccess();
            $quick_access->link = 'index.php?controller=AdminModules&configure=stthemeeditor';
            $quick_access->new_window = 0;
            foreach (Language::getLanguages(false) as $lang)
            {
                $quick_access->name[$lang['id_lang']] = $this->l('Theme editor');
            }
            $quick_access->add();
        }
        return true;
    }
    public function clear_class_index()
    {
        $file = _PS_CACHE_DIR_.'class_index.php';
        file_exists($file) && @unlink($file);
        return true;    
    }
    private function _checkGlobal()
    {
        // Check thickbox_default_2x images
        $defaultLanguage = new Language((int)(Configuration::get('PS_LANG_DEFAULT')));
        $img_name = $defaultLanguage->iso_code.'-default-thickbox_default_2x';
        
        if (!file_exists(_PS_IMG_DIR_.'/p/'.$img_name.'.jpg') && !file_exists(_PS_IMG_DIR_.'/p/'.$img_name.'.png') && !file_exists(_PS_IMG_DIR_.'/p/'.$img_name.'.gif'))
            $this->warning = $this->l('You need to regenerate product thumbnails for the new image type "thickbox_default_2x" to make popup product images look sharp on iPad.');
    }
}
