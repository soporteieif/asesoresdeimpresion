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

include_once dirname(__FILE__).'/StCameraSlideshowClass.php';
include_once dirname(__FILE__).'/StCameraSlideshowGroup.php';

class StCameraSlideshow extends Module
{
    protected static $access_rights = 0775;
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
    public static $caption_effect = array(
		array('id' => '', 'name' => 'None'),
		array('id' => 'fadeIn', 'name' => 'Fade in'),
		array('id' => 'moveFromLeft', 'name' => 'Move from left'),
		array('id' => 'moveFromRight', 'name' => 'Move from right'),
		array('id' => 'moveFromTop', 'name' => 'Move from top'),
		array('id' => 'moveFromBottom', 'name' => 'Move from bottom'),
		array('id' => 'fadeFromLeft', 'name' => 'Fade from left'),
		array('id' => 'fadeFromRight', 'name' => 'Fade from right'),
		array('id' => 'fadeFromTop', 'name' => 'Fade from top'),
		array('id' => 'fadeFromBottom', 'name' => 'Fade from bottom'),
	);
    public static $location = array(
        1 => array('id' =>1 , 'name' => 'Full width top'),
        4 => array('id' =>4 , 'name' => 'Full width top(boxed)'),
        13 => array('id' =>13 , 'name' => 'Full width top(with two banners)'),
        14 => array('id' =>14 , 'name' => 'Full width top(with three banners)'),
        3 => array('id' =>3 , 'name' => 'Homepage'),
        17 => array('id' =>17 , 'name' => 'HomepageTop'),
        18 => array('id' =>18 , 'name' => 'HomepageBottom'),
        19 => array('id' =>19 , 'name' => 'Very bootom of homepage(fullwidth)'),
        20 => array('id' =>20 , 'name' => 'Very bootom of homepage(boxed)'),
        2 => array('id' =>2 , 'name' => 'Left column'),
        5 => array('id' =>5 , 'name' => 'Right column'),
        7 => array('id' =>7 , 'name' => 'Blog homepage top(fullwidth)'),
        8 => array('id' =>8 , 'name' => 'Blog homepage top'),
        6 => array('id' =>6 , 'name' => 'Blog homepage'),
        9 => array('id' =>9 , 'name' => 'Blog left column'),
        10 => array('id' =>10 , 'name' => 'Blog right column'),
        11 => array('id' =>11 , 'name' => 'At bottom of product page'),
        12 => array('id' =>12 , 'name' => 'At bottom of category page'),
        15 => array('id' =>15 , 'name' => 'Homepage secondary right'),
        16 => array('id' =>16 , 'name' => 'Product secondary column'),
    );
    public static $pie_position = array(
        array('id' =>'leftTop' , 'name' => 'Left top'),
        array('id' =>'rightTop' , 'name' => 'Right top'),
        /*
        array('id' =>'leftBottom' , 'name' => 'Left bottom'),
        array('id' =>'rightBottom' , 'name' => 'Right bottom'),
        */
    );
    public static $bar_position = array(
        array('id' =>'top' , 'name' => 'Top'),
        array('id' =>'bottom' , 'name' => 'Bottom'),
    );
    public static $text_position = array(
        array('id' =>'left_center' , 'name' => 'Left center'),
        array('id' =>'right_center' , 'name' => 'Right center'),
        array('id' =>'left_bottom' , 'name' => 'Left bottom'),
        array('id' =>'right_bottom' , 'name' => 'Right bottom'),
        array('id' =>'left_top' , 'name' => 'Left top'),
        array('id' =>'right_top' , 'name' => 'Right top'),
        array('id' =>'center_center' , 'name' => 'Center'),
    );
    public static $effects = array(
        array('id' => 'random' , 'name' => 'Random'),
        array('id' => 'simpleFade' , 'name' => 'simpleFade'),
        array('id' => 'curtainTopLeft' , 'name' => 'curtainTopLeft'),
        array('id' => 'curtainTopRight' , 'name' => 'curtainTopRight'),
        array('id' => 'curtainBottomLeft' , 'name' => 'curtainBottomLeft'),
        array('id' => 'curtainBottomRight' , 'name' => 'curtainBottomRight'),
        array('id' => 'curtainSliceLeft' , 'name' => 'curtainSliceLeft'),
        array('id' => 'curtainSliceRight' , 'name' => 'curtainSliceRight'),
        array('id' => 'blindCurtainTopLeft' , 'name' => 'blindCurtainTopLeft'),
        array('id' => 'blindCurtainTopRight' , 'name' => 'blindCurtainTopRight'),
        array('id' => 'blindCurtainBottomLeft' , 'name' => 'blindCurtainBottomLeft'),
        array('id' => 'blindCurtainBottomRight' , 'name' => 'blindCurtainBottomRight'),
        array('id' => 'blindCurtainSliceBottom' , 'name' => 'blindCurtainSliceBottom'),
        array('id' => 'blindCurtainSliceTop' , 'name' => 'blindCurtainSliceTop'),
        array('id' => 'stampede' , 'name' => 'stampede'),
        array('id' => 'mosaic' , 'name' => 'mosaic'),
        array('id' => 'mosaicReverse' , 'name' => 'mosaicReverse'),
        array('id' => 'mosaicRandom' , 'name' => 'mosaicRandom'),
        array('id' => 'mosaicSpiral' , 'name' => 'mosaicSpiral'),
        array('id' => 'mosaicSpiralReverse' , 'name' => 'mosaicSpiralReverse'),
        array('id' => 'topLeftBottomRight' , 'name' => 'topLeftBottomRight'),
        array('id' => 'bottomRightTopLeft' , 'name' => 'bottomRightTopLeft'),
        array('id' => 'bottomLeftTopRight' , 'name' => 'bottomLeftTopRight'),
        array('id' => 'topRightBottomLeft' , 'name' => 'topRightBottomLeft'),
        array('id' => 'scrollLeft' , 'name' => 'scrollLeft'),
        array('id' => 'scrollRight' , 'name' => 'scrollRight'),
        array('id' => 'scrollTop' , 'name' => 'scrollTop'),
        array('id' => 'scrollBottom' , 'name' => 'scrollBottom'),
        array('id' => 'scrollHorz' , 'name' => 'scrollHorz'),
    );
    public static $_type = array(
        1 => 'location',
        2 => 'id_category',
        4 => 'id_cms',
        5 => 'id_cms_category',
    );
    private $systemFonts = array("Helvetica","Arial","Verdana","Georgia","Tahoma","Times New Roman","sans-serif");
    private $googleFonts = array('ABeeZee','Abel','Abril Fatface','Aclonica','Acme','Actor','Adamina','Advent Pro','Aguafina Script','Akronim','Aladin','Aldrich','Alef','Alegreya','Alegreya Sans SC','Alegreya SC','Alex Brush','Alfa Slab One','Alice','Alike','Alike Angular','Allan','Allerta','Allerta Stencil','Allura','Almendra','Almendra Display','Almendra SC','Amarante','Amaranth','Amatic SC','Amethysta','Anaheim','Andada','Andika','Angkor','Annie Use Your Telescope','Anonymous Pro','Antic','Antic Didone','Antic Slab','Anton','Arapey','Arbutus','Arbutus Slab','Architects Daughter','Archivo Black','Archivo Narrow','Arimo','Arizonia','Armata','Artifika','Arvo','Asap','Asset','Astloch','Asul','Atomic Age','Aubrey','Audiowide','Autour One','Average','Average Sans','Averia Gruesa Libre','Averia Libre','Averia Sans Libre','Averia Serif Libre','Bad Script','Balthazar','Bangers','Basic','Battambang','Baumans','Bayon','Belgrano','Belleza','BenchNine','Bentham','Berkshire Swash','Bevan','Bigelow Rules','Bigshot One','Bilbo','Bilbo Swash Caps','Bitter','Black Ops One','Bokor','Bonbon','Boogaloo','Bowlby One','Bowlby One SC','Brawler','Bree Serif','Bubblegum Sans','Bubbler One','Buda','Buenard','Butcherman','Butterfly Kids','Cabin','Cabin Condensed','Cabin Sketch','Caesar Dressing','Cagliostro','Calligraffitti','Cambo','Candal','Cantarell','Cantata One','Cantora One','Capriola','Cardo','Carme','Carrois Gothic','Carrois Gothic SC','Carter One','Caudex','Cedarville Cursive','Ceviche One','Changa One','Chango','Chau Philomene One','Chela One','Chelsea Market','Chenla','Cherry Cream Soda','Cherry Swash','Chewy','Chicle','Chivo','Cinzel','Cinzel Decorative','Clicker Script','Coda','Coda Caption','Codystar','Combo','Comfortaa','Coming Soon','Concert One','Condiment','Content','Contrail One','Convergence','Cookie','Copse','Corben','Courgette','Cousine','Coustard','Covered By Your Grace','Crafty Girls','Creepster','Crete Round','Crimson Text','Croissant One','Crushed','Cuprum','Cutive','Cutive Mono','Damion','Dancing Script','Dangrek','Dawning of a New Day','Days One','Delius','Delius Swash Caps','Delius Unicase','Della Respira','Denk One','Devonshire','Didact Gothic','Diplomata','Diplomata SC','Domine','Donegal One','Doppio One','Dorsa','Dosis','Dr Sugiyama','Droid Sans','Droid Sans Mono','Droid Serif','Duru Sans','Dynalight','EB Garamond','Eagle Lake','Eater','Economica','Electrolize','Elsie','Elsie Swash Caps','Emblema One','Emilys Candy','Engagement','Englebert','Enriqueta','Erica One','Esteban','Euphoria Script','Ewert','Exo','Expletus Sans','Fanwood Text','Fascinate','Fascinate Inline','Faster One','Fasthand','Federant','Federo','Felipa','Fenix','Finger Paint','Fjalla One','Fjord One','Flamenco','Flavors','Fondamento','Fontdiner Swanky','Forum','Francois One','Freckle Face','Fredericka the Great','Fredoka One','Freehand','Fresca','Frijole','Fruktur','Fugaz One','GFS Didot','GFS Neohellenic','Gabriela','Gafata','Galdeano','Galindo','Gentium Basic','Gentium Book Basic','Geo','Geostar','Geostar Fill','Germania One','Gilda Display','Give You Glory','Glass Antiqua','Glegoo','Gloria Hallelujah','Goblin One','Gochi Hand','Gorditas','Goudy Bookletter 1911','Graduate','Grand Hotel','Gravitas One','Great Vibes','Griffy','Gruppo','Gudea','Habibi','Hammersmith One','Hanalei','Hanalei Fill','Handlee','Hanuman','Happy Monkey','Headland One','Henny Penny','Herr Von Muellerhoff','Holtwood One SC','Homemade Apple','Homenaje','IM Fell DW Pica','IM Fell DW Pica SC','IM Fell Double Pica','IM Fell Double Pica SC','IM Fell English','IM Fell English SC','IM Fell French Canon','IM Fell French Canon SC','IM Fell Great Primer','IM Fell Great Primer SC','Iceberg','Iceland','Imprima','Inconsolata','Inder','Indie Flower','Inika','Irish Grover','Istok Web','Italiana','Italianno','Jacques Francois','Jacques Francois Shadow','Jim Nightshade','Jockey One','Jolly Lodger','Josefin Sans','Josefin Slab','Joti One','Judson','Julee','Julius Sans One','Junge','Jura','Just Another Hand','Just Me Again Down Here','Kameron','Karla','Kaushan Script','Kavoon','Keania One','Kelly Slab','Kenia','Khmer','Kite One','Knewave','Kotta One','Koulen','Kranky','Kreon','Kristi','Krona One','La Belle Aurore','Lancelot','Lato','League Script','Leckerli One','Ledger','Lekton','Lemon','Libre Baskerville','Life Savers','Lilita One','Limelight','Linden Hill','Lobster','Lobster Two','Londrina Outline','Londrina Shadow','Londrina Sketch','Londrina Solid','Lora','Love Ya Like A Sister','Loved by the King','Lovers Quarrel','Luckiest Guy','Lusitana','Lustria','Macondo','Macondo Swash Caps','Magra','Maiden Orange','Mako','Marcellus','Marcellus SC','Marck Script','Margarine','Marko One','Marmelad','Marvel','Mate','Mate SC','Maven Pro','McLaren','Meddon','MedievalSharp','Medula One','Megrim','Meie Script','Merienda','Merienda One','Merriweather','Merriweather Sans','Metal','Metal Mania','Metamorphous','Metrophobic','Michroma','Milonga','Miltonian','Miltonian Tattoo','Miniver','Miss Fajardose','Modern Antiqua','Molengo','Molle','Monda','Monofett','Monoton','Monsieur La Doulaise','Montaga','Montez','Montserrat','Montserrat Alternates','Montserrat Subrayada','Moul','Moulpali','Mountains of Christmas','Mouse Memoirs','Mr Bedfort','Mr Dafoe','Mr De Haviland','Mrs Saint Delafield','Mrs Sheppards','Muli','Mystery Quest','Neucha','Neuton','New Rocker','News Cycle','Niconne','Nixie One','Nobile','Nokora','Norican','Nosifer','Nothing You Could Do','Noticia Text','Nova Cut','Nova Flat','Nova Mono','Nova Oval','Nova Round','Nova Script','Nova Slim','Nova Square','Numans','Nunito','Odor Mean Chey','Offside','Old Standard TT','Oldenburg','Oleo Script','Oleo Script Swash Caps','Open Sans','Open Sans Condensed','Oranienbaum','Orbitron','Oregano','Orienta','Original Surfer','Oswald','Over the Rainbow','Overlock','Overlock SC','Ovo','Oxygen','Oxygen Mono','PT Mono','PT Sans','PT Sans Caption','PT Sans Narrow','PT Serif','PT Serif Caption','Pacifico','Paprika','Parisienne','Passero One','Passion One','Patrick Hand','Patrick Hand SC','Patua One','Paytone One','Peralta','Permanent Marker','Petit Formal Script','Petrona','Philosopher','Piedra','Pinyon Script','Pirata One','Plaster','Play','Playball','Playfair Display','Playfair Display SC','Podkova','Poiret One','Poller One','Poly','Pompiere','Pontano Sans','Port Lligat Sans','Port Lligat Slab','Prata','Preahvihear','Press Start 2P','Princess Sofia','Prociono','Prosto One','Puritan','Purple Purse','Quando','Quantico','Quattrocento','Quattrocento Sans','Questrial','Quicksand','Quintessential','Qwigley','Racing Sans One','Radley','Raleway','Raleway Dots','Rambla','Rammetto One','Ranchers','Rancho','Rationale','Redressed','Reenie Beanie','Revalia','Ribeye','Ribeye Marrow','Righteous','Risque','Roboto','Roboto Condensed','Rochester','Rock Salt','Rokkitt','Romanesco','Ropa Sans','Rosario','Rosarivo','Rouge Script','Ruda','Rufina','Ruge Boogie','Ruluko','Rum Raisin','Ruslan Display','Russo One','Ruthie','Rye','Sacramento','Sail','Salsa','Sanchez','Sancreek','Sansita One','Sarina','Satisfy','Scada','Schoolbell','Seaweed Script','Sevillana','Seymour One','Shadows Into Light','Shadows Into Light Two','Shanti','Share','Share Tech','Share Tech Mono','Shojumaru','Short Stack','Siemreap','Sigmar One','Signika','Signika Negative','Simonetta','Sintony','Sirin Stencil','Six Caps','Skranji','Slackey','Smokum','Smythe','Sniglet','Snippet','Snowburst One','Sofadi One','Sofia','Sonsie One','Sorts Mill Goudy','Source Code Pro','Source Sans Pro','Special Elite','Spicy Rice','Spinnaker','Spirax','Squada One','Stalemate','Stalinist One','Stardos Stencil','Stint Ultra Condensed','Stint Ultra Expanded','Stoke','Strait','Sue Ellen Francisco','Sunshiney','Supermercado One','Suwannaphum','Swanky and Moo Moo','Syncopate','Tangerine','Taprom','Tauri','Telex','Tenor Sans','Text Me One','The Girl Next Door','Tienne','Tinos','Titan One','Titillium Web','Trade Winds','Trocchi','Trochut','Trykker','Tulpen One','Ubuntu','Ubuntu Condensed','Ubuntu Mono','Ultra','Uncial Antiqua','Underdog','Unica One','UnifrakturCook','UnifrakturMaguntia','Unkempt','Unlock','Unna','VT323','Vampiro One','Varela','Varela Round','Vast Shadow','Vibur','Vidaloka','Viga','Voces','Volkhov','Vollkorn','Voltaire','Waiting for the Sunrise','Wallpoet','Walter Turncoat','Warnes','Wellfleet','Wendy One','Wire One','Yanone Kaffeesatz','Yellowtail','Yeseva One','Yesteryear','Zeyada');
    
    public  $fields_list;
    public  $fields_list_slide;
    public  $fields_list_banner;
    public  $fields_value;
    public  $fields_form;
    public  $fields_form_slide;
	private $_html = '';
	private $spacer_size = '5';

	public function __construct()
	{
		$this->name          = 'stcameraslideshow';
		$this->tab           = 'front_office_features';
		$this->version       = '1.9.8';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
        $this->bootstrap     = true;

		parent::__construct();

		$this->displayName   = $this->l('Camera slideshow');
		$this->description   = $this->l('Module that allows you to create slideshows.');
	}
        
	public function install()
	{
		$res = parent::install() &&
			$this->createTables() &&
            $this->registerHook('displayHeader') &&
			$this->registerHook('displayLeftColumn') && 
			$this->registerHook('displayRightColumn') && 
            $this->registerHook('displayHome') &&
            $this->registerHook('displayHomeTop') &&
            $this->registerHook('displayHomeBottom') &&
            $this->registerHook('displayCategoryHeader') &&
			$this->registerHook('displayAnywhere') &&
			$this->registerHook('actionObjectCategoryDeleteAfter') &&
            $this->registerHook('displayFooterProduct') &&
            $this->registerHook('displayCategoryFooter') &&
			$this->registerHook('displayStBlogHome') &&
			$this->registerHook('displayStBlogLeftColumn') && 
			$this->registerHook('displayStBlogRightColumn') &&
            $this->registerHook('displayProductSecondaryColumn') &&
            $this->registerHook('displayHomeSecondaryRight') && 
            $this->registerHook('displayHomeVeryBottom') &&
            $this->registerHook('displayFullWidthTop');
		
        return $res;
	}
	
	/**
	 * Creates tables
	 */
	public function createTables()
	{
		/* Slides */
		$return = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_camera_slideshow` (
				`id_st_camera_slideshow` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_st_camera_slideshow_group` int(10) unsigned NOT NULL,
				`id_currency` int(10) unsigned DEFAULT 0,
    			`image` varchar(255) DEFAULT NULL,
    			`thumb` varchar(255) DEFAULT NULL,
                `new_window` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `text_position` varchar(32) DEFAULT NULL,
                `text_align` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `title_color` varchar(7) DEFAULT NULL,
                `title_bg` varchar(7) DEFAULT NULL,
                `title_font_family` varchar(255) DEFAULT NULL,
                `description_color` varchar(7) DEFAULT NULL,
                `description_bg` varchar(7) DEFAULT NULL,
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `position` int(10) unsigned NOT NULL DEFAULT 0,
                `isbanner` tinyint(1) unsigned NOT NULL DEFAULT 0,
				PRIMARY KEY (`id_st_camera_slideshow`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		/* Slides lang configuration */
		$return && $return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_camera_slideshow_lang` (
				`id_st_camera_slideshow` int(10) UNSIGNED NOT NULL,
				`id_lang` int(10) unsigned NOT NULL ,
    			`url` varchar(255) DEFAULT NULL,
                `video` text,
                `description` text,
                `button` varchar(255) DEFAULT NULL,
                `image_multi_lang` varchar(255) DEFAULT NULL,
                `thumb_multi_lang` varchar(255) DEFAULT NULL,
                `title` varchar(255) DEFAULT NULL,
				PRIMARY KEY (`id_st_camera_slideshow`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
            
		/* Slides group */
		$return && $return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_camera_slideshow_group` (
				`id_st_camera_slideshow_group` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,       
                `name` varchar(255) DEFAULT NULL,
                `location` int(10) unsigned NOT NULL DEFAULT 0,
                `id_category` int(10) unsigned NOT NULL DEFAULT 0,
                `id_cms` int(10) unsigned NOT NULL DEFAULT 0,
                `id_cms_category` int(10) unsigned NOT NULL DEFAULT 0,  
                `height_ratio` float(8,2) unsigned NOT NULL DEFAULT 35, 
                `prev_next` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `prev_next_on_hover` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `pag_nav` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `hide_on_mobile` tinyint(1) unsigned NOT NULL DEFAULT 0, 
                `effects` text, 
                `easing` tinyint(2) unsigned NOT NULL DEFAULT 0,  
                `time` int(10) unsigned NOT NULL DEFAULT 7000,
                `trans_period` int(10) unsigned NOT NULL DEFAULT 1000,
                `auto_advance` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `pause` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `mosaic_rows` int(10) unsigned NOT NULL DEFAULT 4,
                `mosaic_columns` int(10) unsigned NOT NULL DEFAULT 6,
                `blind_rows` int(10) unsigned NOT NULL DEFAULT 8,
                `curtain_columns` int(10) unsigned NOT NULL DEFAULT 12,
                `loader` tinyint(1) unsigned NOT NULL DEFAULT 1,    
                `bar_position` varchar(32) DEFAULT NULL,
                `pie_position` varchar(32) DEFAULT NULL,
                `loader_bg` varchar(7) DEFAULT NULL,
                `loader_color` varchar(7) DEFAULT NULL,
                `active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
                `position` int(10) unsigned NOT NULL DEFAULT 0, 
                `top_spacing` varchar(10) DEFAULT NULL,
                `bottom_spacing` varchar(10) DEFAULT NULL,
				PRIMARY KEY (`id_st_camera_slideshow_group`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		/* Slides group shop */
		$return && $return &= (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_camera_slideshow_group_shop` (
				`id_st_camera_slideshow_group` int(10) UNSIGNED NOT NULL,
                `id_shop` int(11) NOT NULL,      
                PRIMARY KEY (`id_st_camera_slideshow_group`,`id_shop`),    
                KEY `id_shop` (`id_shop`)   
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		return $return;
	}
    
	public function uninstall()
	{
	    $this->clearCameraSlideshowCache();
		// Delete configuration
		return $this->deleteTables() &&
			parent::uninstall();
	}

	/**
	 * deletes tables
	 */
	public function deleteTables()
	{
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_camera_slideshow`,`'._DB_PREFIX_.'st_camera_slideshow_lang`,`'._DB_PREFIX_.'st_camera_slideshow_group`,`'._DB_PREFIX_.'st_camera_slideshow_group_shop`');
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
        
        $check_result = $this->_checkImageDir();
        
        $id_st_camera_slideshow_group = (int)Tools::getValue('id_st_camera_slideshow_group');
        $id_st_camera_slideshow = (int)Tools::getValue('id_st_camera_slideshow');
	    if ((Tools::isSubmit('groupstatusstcameraslideshow')))
        {
            $slide_group = new StCameraSlideshowGroup((int)$id_st_camera_slideshow_group);
            if($slide_group->id && $slide_group->toggleStatus())
            {
                $this->clearCameraSlideshowCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
	    if ((Tools::isSubmit('slidestatusstcameraslideshow')))
        {
            $slide = new StCameraSlideshowClass((int)$id_st_camera_slideshow);
            if($slide->id && $slide->toggleStatus())
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));  
                $this->clearCameraSlideshowCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_camera_slideshow_group='.$slide->id_st_camera_slideshow_group.'&viewstcameraslideshow&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while updating the status.'));
        }
        if (Tools::isSubmit('way') && Tools::isSubmit('id_st_camera_slideshow') && (Tools::isSubmit('position')))
		{
		    $slide = new StCameraSlideshowClass((int)$id_st_camera_slideshow);
            if($slide->id && $slide->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position')))
            {
                //$this->_html .= $this->displayConfirmation($this->l('The status has been updated successfully.'));  
                $this->clearCameraSlideshowCache();
			    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_camera_slideshow_group='.$slide->id_st_camera_slideshow_group.'&viewstcameraslideshow&token='.Tools::getAdminTokenLite('AdminModules'));
            }
            else
                $this->_html .= $this->displayError($this->l('Failed to update the position.'));
		}
        if (Tools::getValue('action') == 'updatePositions')
        {
            $this->processUpdatePositions();
        }
		if (isset($_POST['savestcameraslideshowgroup']) || isset($_POST['savestcameraslideshowgroupAndStay']))
		{
            if ($id_st_camera_slideshow_group)
				$slide_group = new StCameraSlideshowGroup((int)$id_st_camera_slideshow_group);
			else
				$slide_group = new StCameraSlideshowGroup();
            
            $error = array();
    		$slide_group->copyFromPost();
            //
            $effects = Tools::getValue('effects');
            if(is_array($effects) && count($effects))
                $slide_group->effects = implode(',',$effects);
            else
                $slide_group->effects = 'random';
            
            if(!$slide_group->name)
                $error[] = $this->displayError($this->l('The field "Slideshow name" is required'));
            
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
		            Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'st_camera_slideshow_group_shop WHERE id_st_camera_slideshow_group='.(int)$slide_group->id);
                    if (!Shop::isFeatureActive())
            		{
            			Db::getInstance()->insert('st_camera_slideshow_group_shop', array(
            				'id_st_camera_slideshow_group' => (int)$slide_group->id,
            				'id_shop' => (int)Context::getContext()->shop->id,
            			));
            		}
            		else
            		{
            			$assos_shop = Tools::getValue('checkBoxShopAsso_st_camera_slideshow_group');
            			if (empty($assos_shop))
            				$assos_shop[(int)Context::getContext()->shop->id] = Context::getContext()->shop->id;
            			foreach ($assos_shop as $id_shop => $row)
            				Db::getInstance()->insert('st_camera_slideshow_group_shop', array(
            					'id_st_camera_slideshow_group' => (int)$slide_group->id,
            					'id_shop' => (int)$id_shop,
            				));
            		}
                    
                    $this->clearCameraSlideshowCache();
                    $this->writeCss();
                    if(isset($_POST['savestcameraslideshowgroupAndStay']) || Tools::getValue('fr') == 'view')
                    {
                        $rd_str = isset($_POST['savestcameraslideshowgroupAndStay']) && Tools::getValue('fr') == 'view' ? 'fr=view&update' : (isset($_POST['savestcameraslideshowgroupAndStay']) ? 'update' : 'view');
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_camera_slideshow_group='.$slide_group->id.'&conf='.($id_st_camera_slideshow_group?4:3).'&'.$rd_str.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                    }
                        
                    else
                        $this->_html .= $this->displayConfirmation($this->l('Slideshow').' '.($id_st_camera_slideshow_group ? $this->l('updated') : $this->l('added')));
                }                    
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during slideshow').' '.($id_st_camera_slideshow_group ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }
		if (isset($_POST['savestcameraslideshow']) || isset($_POST['savestcameraslideshowAndStay']))
		{
            if ($id_st_camera_slideshow)
				$slide = new StCameraSlideshowClass((int)$id_st_camera_slideshow);
			else
				$slide = new StCameraSlideshowClass();
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
                if(!$slide->id_st_camera_slideshow_group)
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
                            }
                            elseif(!Tools::isSubmit('has_image_'.$lang['id_lang']) && !$res['image'] && !$res['thumb'])
                            {
                                $slide->image_multi_lang[$lang['id_lang']] = $slide->image_multi_lang[$default_lang];
                                $slide->thumb_multi_lang[$lang['id_lang']] = $slide->thumb_multi_lang[$default_lang];
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
                    $this->clearCameraSlideshowCache();
                    $this->writeCss();
                    //$this->_html .= $this->displayConfirmation($this->l('Slide').' '.($id_st_camera_slideshow ? $this->l('updated') : $this->l('added')));
                    
                    if(isset($_POST['savestcameraslideshowAndStay']))
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_camera_slideshow='.$slide->id.'&conf='.($id_st_camera_slideshow?4:3).'&update'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));  
                    else
                        Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_camera_slideshow_group='.$slide->id_st_camera_slideshow_group.'&viewstcameraslideshow&token='.Tools::getAdminTokenLite('AdminModules'));
                }
                else
                    $this->_html .= $this->displayError($this->l('An error occurred during slide').' '.($id_st_camera_slideshow ? $this->l('updating') : $this->l('creation')));
            }
            else
                $this->_html .= count($error) ? implode('',$error) : $this->displayError($this->l('Invalid value for field(s).'));
        }
        
		if (Tools::isSubmit('addstcameraslideshowgroup') || (Tools::isSubmit('updatestcameraslideshow') && $id_st_camera_slideshow_group))
		{
            $helper = $this->initForm();
            return $helper->generateForm($this->fields_form);
		}
        elseif(Tools::isSubmit('addstcameraslideshow') || (Tools::isSubmit('updatestcameraslideshow') && $id_st_camera_slideshow))
        {
            $helper = $this->initFormSlide(0);
            return $helper->generateForm($this->fields_form_slide);
        }
        elseif(Tools::isSubmit('addstcameraslideshowbanner') || (Tools::isSubmit('updatestcameraslideshow') && $id_st_camera_slideshow))
        {
            $helper = $this->initFormSlide(1);
            return $helper->generateForm($this->fields_form_slide);
        }
        elseif(Tools::isSubmit('viewstcameraslideshow'))
        {
            $this->_html .= '<script type="text/javascript">var currentIndex="'.AdminController::$currentIndex.'&configure='.$this->name.'";</script>';
			$slide_group = new StCameraSlideshowGroup($id_st_camera_slideshow_group);
            if(!$slide_group->isAssociatedToShop())
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
			$helper = $this->initListSlide();
            if($slide_group->location==13 || $slide_group->location==14)
                $helper_banner = $this->initListBanner();
			return $this->_html.$helper->generateList(StCameraSlideshowClass::getAll($id_st_camera_slideshow_group,(int)$this->context->language->id,0,0), $this->fields_list).(isset($helper_banner) ? $helper_banner->generateList(StCameraSlideshowClass::getAll($id_st_camera_slideshow_group,(int)$this->context->language->id,0,1), $this->fields_list_banner) : '');
        }
		else if (Tools::isSubmit('deletestcameraslideshow') && $id_st_camera_slideshow)
		{
			$slide = new StCameraSlideshowClass($id_st_camera_slideshow);
            $slide->delete();
            $this->clearCameraSlideshowCache();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&id_st_camera_slideshow_group='.$id_st_camera_slideshow_group.'&viewstcameraslideshow&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else if (Tools::isSubmit('deletestcameraslideshow') && $id_st_camera_slideshow_group)
		{
			$slide_group = new StCameraSlideshowGroup($id_st_camera_slideshow_group);
            $slide_group->delete();
            $this->clearCameraSlideshowCache();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else
		{
			$helper = $this->initList();
			return $this->_html.$helper->generateList(StCameraSlideshowGroup::getAll(), $this->fields_list);
		}
	}
    public static function getType($row)
    {
        $type = array_flip(self::$_type);
        if($row['location'])
            return $type['location'];
        if($row['id_category'])
            return $type['id_category'];
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
			$imagesize = array();
			$imagesize = @getimagesize($_FILES[$item]['tmp_name']);
			if (!empty($imagesize) &&
				in_array(strtolower(substr(strrchr($imagesize['mime'], '/'), 1)), array('jpg', 'gif', 'jpeg', 'png')) &&
				in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
			{
                $this->_checkEnv();
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
    public function writeCss()
    {
        $css = '';
        $options = StCameraSlideshowClass::getOptions();
        if(is_array($options) && count($options))
            foreach($options as $v)    
            {
                $css_title = '';
                if($v['title_font_family'])
                    $css_title .= 'font-family:\''.$v['title_font_family'].'\';';
                if($v['title_color'])
                    $css_title .= 'color:'.$v['title_color'].';';
                if($v['title_bg'])
                    $css_title .= 'background-color:'.$v['title_bg'].';background-color:rgba('.self::hex2rgb($v['title_bg']).',0.4);';
                if($css_title)
                    $css .= '#camera_caption_'.$v['id_st_camera_slideshow'].' h1,#camera_caption_'.$v['id_st_camera_slideshow'].' h2,#camera_caption_'.$v['id_st_camera_slideshow'].' h3{'.$css_title.'}';
                $css_description = '';
                if($v['description_color'])
                    $css_description .= 'color:'.$v['description_color'].';';
                if($v['description_bg'])
                    $css_description .= 'background-color:'.$v['description_bg'].';background-color:rgba('.self::hex2rgb($v['description_bg']).',0.4);';
                if($css_description)
                    $css .= '#camera_caption_'.$v['id_st_camera_slideshow'].' p{'.$css_description.'}';            
            }

        $options = StCameraSlideshowGroup::getOptions();
        if(is_array($options) && count($options))
            foreach($options as $v)    
            {
                if(isset($v['top_spacing']) && ($v['top_spacing'] || $v['top_spacing']==='0'))
                    $css .= '#camera_container_'.$v['id_st_camera_slideshow_group'].'{margin-top:'.(int)$v['top_spacing'].'px;}';
                if(isset($v['bottom_spacing']) && ($v['bottom_spacing'] || $v['bottom_spacing']==='0'))
                    $css .= '#camera_container_'.$v['id_st_camera_slideshow_group'].'{margin-bottom:'.(int)$v['bottom_spacing'].'px;}';        
            }

        $cssFile = $this->local_path."views/css/custom.css";
		$write_fd = fopen($cssFile, 'w') or die('can\'t open file "'.$cssFile.'"');
		fwrite($write_fd, $css);
		fclose($write_fd);
        return true;
    }
    public static function getApplyTo()
    {
        $module = new StCameraSlideshow();
        $location = array();
        foreach(self::$location as $v)
            $location[] = array('id'=>'1-'.$v['id'],'name'=>$v['name']);
            
        $root_category = Category::getRootCategory();
        $category_arr = array();
        $module->getCategoryOption($category_arr,$root_category->id);
        //unset root category
        if(isset($category_arr[$root_category->id]))
            unset($category_arr[$root_category->id]);
            
        $cms_arr = array();
		$module->getCMSOptions($cms_arr, 0, 1);
                
        return array(
            array('name'=>$module->l('Hook'),'query'=>$location),
            array('name'=>$module->l('Category'),'query'=>$category_arr),
            array('name'=>$module->l('CMS'),'query'=>$cms_arr),
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
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Slide configuration'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Slideshow name:'),
					'name' => 'name',
                    'size' => 64,
                    'required'  => true,
				),
                array(
					'type' => 'select',
        			'label' => $this->l('Hook into:'),
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
					'type' => 'text',
					'label' => $this->l('Height:'),
					'name' => 'height_ratio',
                    'default_value' => 35,
                    'required'  => true,
                    'prefix' => '%',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Set the height as a percentage relative to the width., so if your image is landscape and measures 200px x 100px your height would be 50%.'),
				),
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
					'type' => 'switch',
					'label' => $this->l('Show previous/next navigation:'),
					'name' => 'prev_next',
					'is_bool' => true,
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'prev_next_on',
							'value' => 1,
							'label' => $this->l('Yes')),
						array(
							'id' => 'prev_next_off',
							'value' => 0,
							'label' => $this->l('No')),
					),
				),  
                array(
					'type' => 'hidden',
					'name' => 'prev_next_on_hover',
                    'default_value' => 1,
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Hide slideshow on mobile devices:'),
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
                    'desc' => $this->l('if set to Yes, slideshow will be hidden on mobile devices (if screen width is less than 768 pixels).'),
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
				'title' => $this->l('Effect'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'select',
        			'label' => $this->l('Effects:'),
        			'name' => 'effects[]',
                    'multiple' => true,
                    'size' => 8,
                    'options' => array(
        				'query' => self::$effects,
        				'id' => 'id',
        				'name' => 'name',
        			),
                    'desc' => $this->l('Here you can decide the effect of your slideshow. You can also select many effects, just click the left button of your mouse by keeping pressed CMD on Macs and CTRL on PCs.if you select "random" it will overwrite any other selection.')
				), 
                array(
					'type' => 'select',
        			'label' => $this->l('Easing:'),
        			'name' => 'easing',
                    'options' => array(
        				'query' => self::$easing,
        				'id' => 'id',
        				'name' => 'name',
        			),
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
				'title' => $this->l('Grid'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Mosaic rows:'),
					'name' => 'mosaic_rows',
                    'required'  => true,
                    'default_value' => 4,
                    'class' => 'fixed-width-sm',
                    'desc' => $this->l('The number of horizontal rows the mosaic effects are devided in.'),
				),
                array(
					'type' => 'text',
					'label' => $this->l('Mosaic columns:'),
					'name' => 'mosaic_columns',
                    'required'  => true,
                    'default_value' => 6,
                    'class' => 'fixed-width-sm',
                    'desc' => $this->l('The number of vertical rows the mosaic effects are devided in.'),
				),
                array(
					'type' => 'text',
					'label' => $this->l('Blind rows:'),
					'name' => 'blind_rows',
                    'required'  => true,
                    'default_value' => 8,
                    'class' => 'fixed-width-sm',
                    'desc' => $this->l('The number of horizontal rows the blind effects are devided in.'),
				),
                array(
					'type' => 'text',
					'label' => $this->l('Curtain columns:'),
					'name' => 'curtain_columns',
                    'required'  => true,
                    'default_value' => 12,
                    'class' => 'fixed-width-sm',
                    'desc' => $this->l('The number of vertical columns the curtain effects are devided in.'),
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
        
        $this->fields_form[3]['form'] = array(
			'legend' => array(
				'title' => $this->l('Loaders'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'radio',
					'label' => $this->l('Loader:'),
					'name' => 'loader',
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'loader_none',
							'value' => 0,
							'label' => $this->l('None')),
						array(
							'id' => 'loader_on',
							'value' => 1,
							'label' => $this->l('Pie')),
						array(
							'id' => 'loader_off',
							'value' => 2,
							'label' => $this->l('Bar')),
					),
                    'desc' => $this->l('In this section you can decide if display a pie loader, a bar loader or nothing at all.'),
				),
                array(
					'type' => 'select',
        			'label' => $this->l('Bar position:'),
        			'name' => 'bar_position',
                    'options' => array(
        				'query' => self::$bar_position,
        				'id' => 'id',
        				'name' => 'name',
        			),
				), 
                array(
					'type' => 'select',
        			'label' => $this->l('Pie position:'),
        			'name' => 'pie_position',
                    'options' => array(
        				'query' => self::$pie_position,
        				'id' => 'id',
        				'name' => 'name',
        			),
				), 
                array(
					'type' => 'color',
					'label' => $this->l('Loader background color:'),
					'name' => 'loader_bg',
					'class' => 'color',
					'size' => 20,
				),
                array(
					'type' => 'color',
					'label' => $this->l('Loader color:'),
					'name' => 'loader_color',
					'class' => 'color',
					'size' => 20,
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
        
        $id_st_camera_slideshow_group = (int)Tools::getValue('id_st_camera_slideshow_group');
		$slide_group = new StCameraSlideshowGroup($id_st_camera_slideshow_group);
        if($slide_group->id)
        {
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_camera_slideshow_group');
        }
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
        /** mutishop begin **/
        $helper->id = (int)$slide_group->id;
		$helper->table =  'st_camera_slideshow_group';
		$helper->identifier = 'id_st_camera_slideshow_group';
        /** mutishop end **/
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->submit_action = 'savestcameraslideshowgroup';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($slide_group),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
        
		$helper->tpl_vars['fields_value']['effects[]'] = explode(',',$slide_group->effects);
        if($slide_group->id)
        {
            $type  = self::getType(get_object_vars($slide_group));
            $field = self::$_type[$type];
            $type && $helper->tpl_vars['fields_value']['location'] = $type.'-'.$slide_group->$field;
        }
        
		return $helper;
	}

    public function fontOptions() {
        $system = $google = array();
        foreach($this->systemFonts as $v)
            $system[] = array('id'=>$v,'name'=>$v);
        foreach($this->googleFonts as $v)
            $google[] = array('id'=>$v,'name'=>$v);
        $module = new StCameraSlideshow();
        return array(
            array('name'=>$module->l('System Web fonts'),'query'=>$system),
            array('name'=>$module->l('Google Web Fonts'),'query'=>$google),
        );
	}
    
	protected function initFormSlide($isbanner=0)
	{
        $id_st_camera_slideshow = (int)Tools::getValue('id_st_camera_slideshow');
        $id_st_camera_slideshow_group = (int)Tools::getValue('id_st_camera_slideshow_group');
		$slide = new StCameraSlideshowClass($id_st_camera_slideshow);
		$this->fields_form_slide[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('slide'),
                'icon' => 'icon-confs'
			),
			'input' => array(
                array(
					'type' => 'select',
        			'label' => $this->l('Slideshow:'),
        			'name' => 'id_st_camera_slideshow_group',
                    'required'  => true,
                    'options' => array(
        				'query' => StCameraSlideshowGroup::getAll(),
        				'id' => 'id_st_camera_slideshow_group',
        				'name' => 'name',
						'default' => array(
							'value' => 0,
							'label' => $this->l('Please select')
						)
        			)
				),
                /*
                array(
					'type' => 'textarea',
					'label' => $this->l('Embedded video:'),
					'name' => 'video',
					'cols' => 80,
					'rows' => 10,
                    'lang' => true,
                    'desc' => $this->l('Paste here above an iFrame, set the width and the height to 100% to make the video fit the slideshow.'),
                ),
                */
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
            'desc' =>array(
				$this->l('Images Dimensions Guide').', <a href="javascript:;" onclick="$(\'#dimension_table\').toggle();return false;">'.$this->l('Click here').'</a>',           
                '<div id="dimension_table" style="display:none;">
                    <p>'.$this->l('You can change the height of images.').'</p>
                    <table class="table">
                        <tr>
                            <th>Hook into</th>
                            <th>Maximum Page Width 1200, One Column(px)</th>
                            <th>Maximum Page Width 1200, Two Columns(px)</th>
                            <th>Maximum Page Width 980, One Column(px)</th>
                            <th>Maximum Page Width 980, Two Columns(px)</th>
                        </tr>
                        <tr>
                            <th>Homepage top(fullwidth)<br />Blog homepage top(fullwidth)</th>
                            <td>1900*550</td>
                            <td>1900*550</td>
                            <td>1900*550</td>
                            <td>1900*550</td>
                        </tr>
                        <tr>
                            <th>Homepage top<br />Blog homepage top</th>
                            <td>1170*550</td>
                            <td></td>
                            <td>940*310</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Homepage<br />Blog homepage</th>
                            <td>1170*550</td>
                            <td>870*290</td>
                            <td>940*310</td>
                            <td>700*260</td>
                        </tr>
                        <tr>
                            <th>Left column<br />Right column<br />Blog left column<br />Blog right column</th>
                            <td></td>
                            <td>270*316</td>
                            <td></td>
                            <td>220*280</td>
                        </tr>
                        <tr>
                            <th>Footer product</th>
                            <td>1170*550</td>
                            <td>870*290</td>
                            <td>940*310</td>
                            <td>700*260</td>
                        </tr>
                        <tr>
                            <th>Catgegory<br/>Footer category</th>
                            <td>1170*550</td>
                            <td>870*290</td>
                            <td>940*310</td>
                            <td>700*260</td>
                        </tr>
                        <tr>
                            <th>Catgegory<br/>Homepage top(with two banners)</th>
                            <td>770*560(banner 370*265)</td>
                            <td></td>
                            <td>620*450(banner 300*216)</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Catgegory<br/>Homepage top(with three banners)</th>
                            <td>870*400(banner 270*124)</td>
                            <td></td>
                            <td>700*322(banner 220*100)</td>
                            <td></td>
                        </tr>
                    </table>
                </div>' 
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
        if(!$isbanner)
		$this->fields_form_slide[1]['form'] = array(
			'legend' => array(
				'title' => $this->l('More options'),
                'icon' => 'icon-cogs'
			),
            'description' => $this->l('1. Text will be automatically hidden on mobile devices.').'<br/>'.$this->l('2. Text will be hidden on category page and left/right column.'),
			'input' => array(
                array(                    
					'type' => 'textarea',
					'label' => $this->l('Text:'),
					'name' => 'description',
					'cols' => 80,
					'rows' => 10,
                    'lang' => true,
                    'desc' => array(
                        $this->l('Format your entry with some basic HTML.'),
                        '&lt;h1&gt;Heading level 1&lt;/h1&gt;',
                        '&lt;h2&gt;Heading level 2&lt;/h2&gt;',
                        '&lt;h3&gt;Heading level 3&lt;/h3&gt;',
                        $this->l('Paragraph').' &lt;p&gt;My first paragraph.&lt;/p&gt;',
                    ),
				),
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
					'type' => 'radio',
					'label' => $this->l('Text alignment:'),
					'name' => 'text_align',
                    'default_value' => 2,
					'values' => array(
						array(
							'id' => 'text_align_left',
							'value' => 2,
							'label' => $this->l('Left')),
						array(
							'id' => 'text_align_right',
							'value' => 3,
							'label' => $this->l('Right')),
					),
				), 
                array(
					'type' => 'color',
        			'label' => $this->l('Heading color:'),
        			'name' => 'title_color',
        			'size' => 33,
				),
                array(
					'type' => 'color',
        			'label' => $this->l('Heading background color:'),
        			'name' => 'title_bg',
        			'size' => 33,
				),
				array(
					'type' => 'select',
					'label' => $this->l('Heading font:'),
					'name' => 'title_font_family',
					'onchange' => 'handle_font_change(this,\''.implode(',',$this->systemFonts).'\');',
                    'class' => 'fontOptions',
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
                    'desc' => '<p id="title_font_family_example" class="fontshow">Example Title</p>',
				),
                array(
					'type' => 'color',
        			'label' => $this->l('Paragraph color:'),
        			'name' => 'description_color',
        			'size' => 33,
				),
                array(
					'type' => 'color',
        			'label' => $this->l('Paragraph background color:'),
        			'name' => 'description_bg',
        			'size' => 33,
				),
                array(
					'type' => 'text',
					'label' => $this->l('Button Text:'),
					'name' => 'button',
                    'lang' => true,
				),
                array(
					'type' => 'html',
                    'id' => 'a_cancel',
					'label' => '',
					'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&id_st_camera_slideshow_group='.$slide->id_st_camera_slideshow_group.'&viewstcameraslideshow&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
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
                );
        }
        $this->fields_form_slide[0]['form']['input'][] = array(
			'type' => 'html',
            'id' => 'a_cancel',
			'label' => '',
			'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&id_st_camera_slideshow_group='.$slide->id_st_camera_slideshow_group.'&viewstcameraslideshow&token='.Tools::getAdminTokenLite('AdminModules').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
		);
        if(Validate::isLoadedObject($slide))
        {
            $this->fields_form_slide[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_st_camera_slideshow');
            foreach ($languages as $lang)
                if($slide->image_multi_lang[$lang['id_lang']])
                {
                    StCameraSlideshowClass::fetchMediaServer($slide->thumb_multi_lang[$lang['id_lang']]);
                    $this->fields_form_slide[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'has_image_'.$lang['id_lang'], 'default_value'=>1);
                    $this->fields_form_slide[0]['form']['input']['image_multi_lang_'.$lang['id_lang']]['required'] = false;
                    $this->fields_form_slide[0]['form']['input']['image_multi_lang_'.$lang['id_lang']]['desc'] = '<img src="'.$slide->thumb_multi_lang[$lang['id_lang']].'"/>';
                }
        }
        elseif($id_st_camera_slideshow_group)
            $slide->id_st_camera_slideshow_group = $id_st_camera_slideshow_group;
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestcameraslideshow';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValueSt($slide,"fields_form_slide"),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
        $helper->tpl_vars['fields_value']['isbanner'] = ($isbanner || (Validate::isLoadedObject($slide) && $slide->isbanner))? 1 : 0;
		
		return $helper;
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
                $module = new StCameraSlideshow();
                $result = $category->name.'('.$module->l('Category').')';
            }
        }
        elseif($row['id_cms'])
        {
            $cms = new CMS((int)$row['id_cms'], (int)Context::getContext()->language->id);
            if ($cms->id)
            {
                $module = new StCameraSlideshow();
                $result = $cms->meta_title.'('.$module->l('CMS').')';
            }
        }
        else
        {
            $module = new StCameraSlideshow();
            $result = $module->l('--');
        }
        return $result;
    }
	protected function initList()
	{
		$this->fields_list = array(
			'id_st_camera_slideshow_group' => array(
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
				'callback_object' => 'StCameraSlideshow',
                'search' => false,
                'orderby' => false
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
		$helper->identifier = 'id_st_camera_slideshow_group';
		$helper->actions = array('view', 'edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstcameraslideshowgroup&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add a slideshow'),
		);

		$helper->title = $this->displayName;
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    public static function showSlideGroupName($value,$row)
    {
        $slide_group = new StCameraSlideshowGroup((int)$value);
        return $slide_group->id ? $slide_group->name : '-';
    }
    public static function showSlideImage($value,$row)
    {
        return '<img src="'.$value.'" />';
    }
	protected function initListSlide()
	{
		$this->fields_list = array(
			'id_st_camera_slideshow' => array(
				'title' => $this->l('Id'),
				'width' => 120,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'id_st_camera_slideshow_group' => array(
				'title' => $this->l('Slideshow'),
				'width' => 120,
				'type' => 'text',
				'callback' => 'showSlideGroupName',
				'callback_object' => 'StCameraSlideshow',
                'search' => false,
                'orderby' => false
			),
            'thumb_multi_lang' => array(
				'title' => $this->l('Image'),
				'type' => 'text',
				'callback' => 'showSlideImage',
				'callback_object' => 'StCameraSlideshow',
                'width' => 300,
                'search' => false,
                'orderby' => false
            ),
            'position' => array(
				'title' => $this->l('Position'),
				'width' => 40,
				'position' => 'position',
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
		$helper->identifier = 'id_st_camera_slideshow';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstcameraslideshow&id_st_camera_slideshow_group='.(int)Tools::getValue('id_st_camera_slideshow_group').'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add a slide')
		);
        $helper->toolbar_btn['edit'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&update'.$this->name.'&id_st_camera_slideshow_group='.(int)Tools::getValue('id_st_camera_slideshow_group').'&fr=view&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Edit slideshow'),
		);
		$helper->toolbar_btn['back'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Back to list')
		);

		$helper->title = $this->l('Slide');
		$helper->table = $this->name;
		$helper->orderBy = 'position';
		$helper->orderWay = 'ASC';
	    $helper->position_identifier = 'id_st_camera_slideshow';
        
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
	protected function initListBanner()
	{
		$this->fields_list_banner = array(
			'id_st_camera_slideshow' => array(
				'title' => $this->l('Id'),
				'width' => 120,
				'type' => 'text',
                'search' => false,
                'orderby' => false
			),
			'id_st_camera_slideshow_group' => array(
				'title' => $this->l('Slideshow'),
				'width' => 120,
				'type' => 'text',
				'callback' => 'showSlideGroupName',
				'callback_object' => 'StCameraSlideshow',
                'search' => false,
                'orderby' => false
			),
            'thumb_multi_lang' => array(
				'title' => $this->l('Image'),
				'type' => 'text',
				'callback' => 'showSlideImage',
				'callback_object' => 'StCameraSlideshow',
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
		$helper->identifier = 'id_st_camera_slideshow';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addstcameraslideshowbanner&id_st_camera_slideshow_group='.(int)Tools::getValue('id_st_camera_slideshow_group').'&token='.Tools::getAdminTokenLite('AdminModules'),
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
	    $helper->position_identifier = 'id_st_camera_slideshow';
        
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}
    private function _prepareHook($identify,$type=1)
    {
        $googleFontLinks = '';
        $fonts      = $this->systemFonts;
	    $slide_font = array();
        
        $slide_group = StCameraSlideshowGroup::getSlideGroup($identify,$type);
        if(!is_array($slide_group) || !count($slide_group))
            return false;
        foreach($slide_group as &$v)
        {
             $v['easing'] = array_key_exists($v['easing'],self::$easing) ? self::$easing[$v['easing']]['name'] : 'swing';
             $slide = StCameraSlideshowClass::getAll($v['id_st_camera_slideshow_group'],$this->context->language->id,1,0);
             if(is_array($slide) && $slide_nbr=count($slide))
             {
                foreach($slide as $m=>&$n)
                {
                    $slide_font[] = $n['title_font_family'];
                    /*
                    if($slide_nbr>1)
                    {
                        $n['next_thumb'] = isset($slide[$m+1]) 
                            ? ((isset($slide[$m+1]['thumb_multi_lang']) && $slide[$m+1]['thumb_multi_lang']) ? $slide[$m+1]['thumb_multi_lang'] : $slide[$m+1]['thumb']) 
                            : ((isset($slide[0]['thumb_multi_lang']) && $slide[0]['thumb_multi_lang']) ? $slide[0]['thumb_multi_lang'] : $slide[0]['thumb']);
                        $n['prev_thumb'] = isset($slide[$m-1]) 
                            ? ((isset($slide[$m-1]['thumb_multi_lang']) && $slide[$m-1]['thumb_multi_lang']) ? $slide[$m-1]['thumb_multi_lang'] : $slide[$m-1]['thumb']) 
                            : ((isset($slide[$slide_nbr-1]['thumb_multi_lang']) && $slide[$slide_nbr-1]['thumb_multi_lang']) ? $slide[$slide_nbr-1]['thumb_multi_lang'] : $slide[$slide_nbr-1]['thumb']);
                    }
                    */
                }
                $v['slide'] = $slide;
             }
             if($v['location']==13 || $v['location']==14)
             {
                $banners = StCameraSlideshowClass::getAll($v['id_st_camera_slideshow_group'],$this->context->language->id,1,1);
                if(is_array($banners) && count($banners))
                    $v['banners'] = $banners;
             }
        }
        $slide_font = array_unique($slide_font);
        $slide_font = array_diff($slide_font,$fonts);    
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
    	        $googleFontLinks .="<link href='//fonts.googleapis.com/css?family=".str_replace(' ', '+', $x).($font_support ? rtrim($font_support,',') : '')."' rel='stylesheet' type='text/css' />";
            }
	    $this->smarty->assign(array(
            'slide_group' => $slide_group,
            'google_font_links'  => $googleFontLinks,
            'image_path'  => _MODULE_DIR_.$this->name.'/views/images/',
        ));
        return true;
    }
    public function hookDisplayHeader($params)
    {
		$this->context->controller->addJS(($this->_path).'views/js/camera.js');
		$this->context->controller->addJQueryUI('ui.widget');
		$this->context->controller->addJS(($this->_path).'views/js/jquery.mobile.customized.min.js');
		$this->context->controller->addCSS(($this->_path).'views/css/camera.css');
        if(!file_exists($this->local_path.'views/css/custom.css'))
            $this->writeCss();
        if(file_exists($this->local_path.'views/css/custom.css'))
		  $this->context->controller->addCSS(($this->_path).'views/css/custom.css');
    }
	public function hookDisplayLeftColumn($params)
	{
		if (!$this->isCached('stcameraslideshow-column.tpl', $this->stGetCacheId(2)))
            if(!$this->_prepareHook(2,1))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow-column.tpl', $this->stGetCacheId(2));
	}
	public function hookDisplayRightColumn($params)
	{
		if (!$this->isCached('stcameraslideshow-column.tpl', $this->stGetCacheId(5)))
            if(!$this->_prepareHook(5,1))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow-column.tpl', $this->stGetCacheId(5));
	}
 
    public function hookDisplayHome($params)
    {
        if (!$this->isCached('stcameraslideshow.tpl', $this->stGetCacheId(3)))
            if(!$this->_prepareHook(3,1))
                return false;
        return $this->display(__FILE__, 'stcameraslideshow.tpl', $this->stGetCacheId(3));
    }
    
    public function hookDisplayHomeTop($params)
    {
        if (!$this->isCached('stcameraslideshow.tpl', $this->stGetCacheId(17)))
            if(!$this->_prepareHook(17,1))
                return false;
        return $this->display(__FILE__, 'stcameraslideshow.tpl', $this->stGetCacheId(17));
    }

    public function hookDisplayHomeBottom($params)
    {
        if (!$this->isCached('stcameraslideshow.tpl', $this->stGetCacheId(18)))
            if(!$this->_prepareHook(18,1))
                return false;
        return $this->display(__FILE__, 'stcameraslideshow.tpl', $this->stGetCacheId(18));
    }

	public function hookDisplayHomeVeryBottom($params)
	{
        if(!$this->_prepareHook(array(19,20),1))
                return false;
        return $this->display(__FILE__, 'stcameraslideshow-topextra.tpl');
	}
    
	public function hookDisplayStBlogHome($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stcameraslideshow.tpl', $this->stGetCacheId(6)))
            if(!$this->_prepareHook(6,1))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow.tpl', $this->stGetCacheId(6));
	}
	public function hookDisplayStBlogLeftColumn($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stcameraslideshow-column.tpl', $this->stGetCacheId(9)))
            if(!$this->_prepareHook(9,1))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow-column.tpl', $this->stGetCacheId(9));
	}
	public function hookDisplayStBlogRightColumn($params)
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
		if (!$this->isCached('stcameraslideshow-column.tpl', $this->stGetCacheId(10)))
            if(!$this->_prepareHook(10,1))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow-column.tpl', $this->stGetCacheId(10));
	}
    
    public function displayMainSlide()
	{
	    if(Dispatcher::getInstance()->getController()!='index')
            return false;
        
		//if (!$this->isCached('stcameraslideshow-topextra.tpl', $this->stGetCacheId(1)))
            if(!$this->_prepareHook(array(1,4,13,14),1))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow-topextra.tpl');
	}
    
    public function hookDisplayFullWidthTop($params)
    {
        $page_name = Context::getContext()->smarty->getTemplateVars('page_name');
        if($page_name=='index')
        {
            if(!$this->_prepareHook(array(1,4,13,14),1))
                return false;
            return $this->display(__FILE__, 'stcameraslideshow-topextra.tpl');
        }
        elseif($page_name=='module-stblog-default')
        {
            if(!$this->_prepareHook(array(7,8),1))
                return false;
            return $this->display(__FILE__, 'stcameraslideshow-topextra.tpl');
        }
    }

    public function displayBlogMainSlide()
	{
	    if(!Module::isInstalled('stblog') || !Module::isEnabled('stblog'))
            return false;
        
		//if (!$this->isCached('stcameraslideshow-topextra.tpl', $this->stGetCacheId(1)))
            if(!$this->_prepareHook(array(7,8),1))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow-topextra.tpl');
	}
	
    public function hookDisplayCategoryHeader($params)
    {
        $id_category = (int)Tools::getValue('id_category');
        if(!$id_category)
            return false;
		if (!$this->isCached('stcameraslideshow-category.tpl', $this->stGetCacheId($id_category,'category-header')))
            if(!$this->_prepareHook($id_category,2))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow-category.tpl', $this->stGetCacheId($id_category,'category-header'));
    }
    public function hookDisplayCategoryFooter($params)
    {
        if (!$this->isCached('stcameraslideshow.tpl', $this->stGetCacheId(12)))
            if(!$this->_prepareHook(12,1))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow.tpl', $this->stGetCacheId(12));
    }
    
    public function hookDisplayFooterProduct($params)
    {
        if (!$this->isCached('stcameraslideshow.tpl', $this->stGetCacheId(11)))
            if(!$this->_prepareHook(11,1))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow.tpl', $this->stGetCacheId(11));
    }
    
    public function hookDisplayHomeSecondaryRight($params)
	{
		if (!$this->isCached('stcameraslideshow-column.tpl', $this->stGetCacheId(15)))
            if(!$this->_prepareHook(15,1))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow-column.tpl', $this->stGetCacheId(15));
	}
    
    public function hookDisplayProductSecondaryColumn($params)
    {
		if (!$this->isCached('stcameraslideshow-column.tpl', $this->stGetCacheId(16)))
            if(!$this->_prepareHook(16,1))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow-column.tpl', $this->stGetCacheId(16));
    }
    
	public function hookDisplayAnywhere($params)
	{
	    if(!isset($params['caller']) || $params['caller']!=$this->name)
            return false;
	   if(isset($params['function']) && method_exists($this,$params['function']))
        {
            if($params['function']=='displayMainSlide')
                return call_user_func(array($this,$params['function']));
            elseif($params['function']=='displayBySlideId')
                return call_user_func_array(array($this,$params['function']),array($params['identify']));
            elseif($params['function']=='displayBlogMainSlide')
                return call_user_func(array($this,$params['function']));
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
		return $this->display(__FILE__, 'stcameraslideshow-category.tpl');
    }
    public function displayCmsCategoryMainSlide($identify)
    {
        if(!$identify || !$this->_prepareHook($identify, 5))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow-category.tpl');
    }
    public function displayBySlideId($identify)
    {
        if(!Validate::isUnsignedInt($identify))
            return false;
            
        $slide_group_obj = new StCameraSlideshowGroup($identify);
        if(!$slide_group_obj->id || !$slide_group_obj->active)
            return false;
		if (!$this->isCached('stcameraslideshow.tpl', $this->stGetCacheId($slide_group_obj->id,'id')))
            if(!$this->_prepareHook($identify,3))
                return false;
		return $this->display(__FILE__, 'stcameraslideshow.tpl', $this->stGetCacheId($slide_group_obj->id,'id'));
    }
    public function hookActionObjectCategoryDeleteAfter($params)
    {
        if(!$params['object']->id)
            return ;
        
        $slide_group = StCameraSlideshowGroup::getSlideGroup($params['object']->id,2);
        if(!is_array($slide_group) || !count($slide_group))
            return ;
        $res = true;
        foreach($slide_group as $v)
        {
            $slide_group = new StCameraSlideshowGroup($v['id_st_camera_slideshow_group']);
            $res &= $slide_group->delete();
        }
        
        return $res;
    }
	protected function stGetCacheId($key,$type='location',$name = null)
	{
		$cache_id = parent::getCacheId($name);
		return $cache_id.'_'.$key.'_'.$type;
	}
	private function clearCameraSlideshowCache()
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
        
    public function processUpdatePositions()
	{
		if (Tools::getValue('action') == 'updatePositions' && Tools::getValue('ajax'))
		{
			$way = (int)(Tools::getValue('way'));
			$id = (int)(Tools::getValue('id'));
			$positions = Tools::getValue('st_camera_slideshow');
            $msg = '';
			if (is_array($positions))
				foreach ($positions as $position => $value)
				{
					$pos = explode('_', $value);

					if ((isset($pos[2])) && ((int)$pos[2] === $id))
					{
						if ($object = new StCameraSlideshowClass((int)$pos[2]))
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
