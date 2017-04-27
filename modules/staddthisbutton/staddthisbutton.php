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

class StAddThisButton extends Module
{
    private $_html = '';
    public $fields_form;
    public $fields_value;
    private static $customizing_specail = array(
            'facebook_like' => 'Facebook Like',
            'facebook_send' => 'Facebook Send',
            'facebook_share' => 'Facebook Share',
            'tweet' => 'Twitter',
            'google_plusone_badge' => 'Google+ Badge',
            'linkedin_counter' => 'LinkedIn',
            'twitter_follow_native' => 'Twitter Follw',
            'hyves_respect' => 'Hyves',
            'pinterest_pinit' => 'Pinterest',
            'google_plusone' => 'Google +2'
            );
    private static $customizing_array = array(
            'facebook'=>'Facebook',
            'twitter'=>'Twitter',  
            'hyves'=>'Hyves',
            'linkedin'=>'LinkedIn',
            'google_plusone_share'=>'Google+', 
            'email'=>'Email',
            'print'=>'Print',
            'gmail'=>'Gmail',
            'whatsapp'=>'WhatsApp',
            'telegram' => 'Telegram',
            'stumbleupon'=>'StumbleUpon',
            'favorites'=>'Favorites',
            'tumblr'=>'Tumblr',
            'pinterest_share'=>'Pinterest',
            'google'=>'Google',
            'mailto'=>'Email App',
            'blogger'=>'Blogger', 
            'delicious'=>'Delicious',  
            'yahoomail'=>'Y! Mail',   
            'hotmail'=>'Outlook',   
            'printfriendly'=>'PrintFriendly', 
            'aolmail'=>'AOL Mail', 
            'livejournal' =>'LiveJournal',
            'wordpress'=>'WordPress',
            'friendfeed'=>'FriendFeed',
            '100zakladok'=>'100zakladok',
            '2linkme'=>'2linkme',
            '2tag'=>'2 Tag',
            'a97abi'=>'A97abi',
            'adfty'=>'Adfty',
            'adifni'=>'Adifni',
            'advqr'=>'ADV QR',
            'aim'=>'Lifestream',
            'tumblr'=>'Tumblr',
            'amazonwishlist'=>'Amazon',
            'amenme'=>'Amen Me!',
            'apsense'=>'APSense',
            'arto'=>'Arto',
            'azadegi'=>'Azadegi', 
            'baang'=>'Baang',  
            'baidu'=>'Baidu',   
            'balltribe'=>'BallTribe',   
            'beat100'=>'Beat100', 
            'biggerpockets'=>'BiggerPockets', 
            'bitly' =>'Bit.ly',
            'bizsugar'=>'BizSugar',
            'bland'=>'Bland takkinn',
            'blinklist'=>'Blinklist',
            'blip'=>'Blip',
            'bloggy'=>'Bloggy',
            'blogkeen' =>'Blogkeen',
            'blogmarks'=>'Blogmarks',
            'blurpalicious'=>'Blurpalicious',
            'bobrdobr'=>'Bobrdobr',
            'bonzobox'=>'BonzoBox',
            'bookmarkycz'=>'Bookmarky.cz',
            'bookmerkende'=>'Bookmerken',
            'box'=>'Box',
            'brainify'=>'Brainify',
            'bryderi'=>'Bryderi.se',
            'buddymarks'=>'BuddyMarks',
            'buffer'=>'Buffer',
            'buzzzy'=>'Buzzzy', 
            'camyoo'=>'Camyoo',  
            'care2'=>'Care2',   
            'chimein'=>'Chime',   
            'chiq'=>'Chiq', 
            'cirip'=>'Cirip', 
            'classicalplace' =>'ClassicalPlace',
            'cleanprint'=>'CleanPrint',
            'cleansave'=>'CleanSave',
            'cndig'=>'Cndig',
            'colivia'=>'Colivia.de',
            'cosmiq'=>'COSMiQ',
            'cssbased'=>'CSS Based',
            'curateus'=>'Curate.us',
            'digaculturanet'=>'DigaCultura',
            'digg'=>'Digg',
            'diggita'=>'Diggita',
            'digo'=>'Digo',
            'diigo'=>'Diigo',
            'domaintoolswhois'=>'Whois Lookup',
            'domelhor'=>'DoMelhor',
            'dosti'=>'Dosti',
            'dotnetshoutout'=>'.netShoutout', 
            'douban'=>'Douban',  
            'draugiem'=>'Draugiem.lv',   
            'dropjack'=>'Dropjack',   
            'dudu'=>'Dudu', 
            'dzone'=>'Dzone', 
            'edelight' =>'Edelight',
            'efactor'=>'EFactor',
            'ekudos'=>'eKudos',
            'elefantapl'=>'elefanta.pl',
            'embarkons'=>'Embarkons',
            'evernote'=>'Evernote',
            'extraplay' =>'extraplay',
            'ezyspot'=>'EzySpot',
            'fabulously40'=>'Fabulously40',
            'fark'=>'Fark',
            'farkinda'=>'Farkinda',
            'fashiolista'=>'Fashiolista',
            'favable'=>'FAVable',
            'faves'=>'Faves',
            'favlogde'=>'Brainify',
            'favlog'=>'favlog',
            'favoritende'=>'Favoriten',
            'favoritus'=>'Favoritus',
            'flaker'=>'Flaker', 
            'folkd'=>'Folkd',  
            'foodlve'=>'Cherry Share',   
            'formspring'=>'Formspring',   
            'fresqui'=>'Fresqui', 
            'funp'=>'funP', 
            'fwisp' =>'fwisp',
            'gabbr'=>'Gabbr',
            'gamekicker'=>'Gamekicker',
            'gg'=>'GG',
            'giftery'=>'Giftery.me',
            'gigbasket'=>'GigBasket',
            'givealink'=>'GiveALink',
            'gluvsnap'=>'Healthimize',
            'goodnoows'=>'Good Noows',
            'googletranslate'=>'Translate',
            'govn'=>'Go.vn',
            'greaterdebater'=>'GreaterDebater',
            'hackernews'=>'Hacker News',
            'hatena'=>'Hatena',
            'hedgehogs'=>'Hedgehogs',
            'historious'=>'historious', 
            'hotklix'=>'Hotklix',  
            'identica'=>'Identi.ca',   
            'ihavegot'=>'ihavegot', 
            'index4'=>'Index4', 
            'indexor' =>'Indexor',
            'informazione'=>'Informazione',
            'instapaper'=>'Instapaper',
            'iorbix'=>'iOrbix',
            'irepeater'=>'IRepeater',
            'isociety'=>'iSociety',
            'iwiw' =>'iWiW',
            'jamespot'=>'Jamespot',
            'jappy'=>'Jappy Ticker',
            'jolly'=>'Jolly',
            'jumptags'=>'Jumptags',
            'kaboodle'=>'Kaboodle',
            'kaevur'=>'Kaevur',
            'kaixin'=>'Kaixin Repaste',
            'ketnooi'=>'Ketnooi',
            'kindleit'=>'Kindle It',
            'kledy'=>'Kledy',
            'kommenting'=>'Kommenting',
            'latafaneracat'=>'La tafanera', 
            'librerio'=>'Librerio',  
            'lidar'=>'LiDAR Online',   
            'link'=>'Copy Link',   
            'linksgutter'=>'Links Gutter', 
            'linkshares'=>'LinkShares', 
            'linkuj' =>'Linkuj.cz',
            'live'=>'Messenger',
            'lockerblogger'=>'LockerBlogger',
            'logger24'=>'Logger24',
            'markme'=>'Markme',
            'mashant'=>'Mashant',
            'mashbord'=>'Mashbord',
            'me2day'=>'me2day',
            'meinvz'=>'meinVZ',
            'mekusharim'=>'Mekusharim',
            'memonic'=>'Memonic',
            'memori'=>'Memori.ru',
            'mendeley'=>'Mendeley',
            'meneame'=>'Meneame',
            'misterwong'=>'Mister Wong',
            'mixi'=>'Mixi',
            'moemesto'=>'Moemesto.ru', 
            'moikrug'=>'Moikrug',  
            'mrcnetworkit'=>'mRcNEtwORK',   
            'mymailru'=>'Mail.ru',   
            'myspace'=>'Myspace', 
            'n4g'=>'N4G', 
            'naszaklasa' =>'Nasza-klasa',
            'netlog'=>'NetLog',
            'netvibes'=>'Netvibes',
            'netvouz'=>'Netvouz',
            'newsmeback'=>'NewsMeBack',
            'newstrust'=>'NewsTrust',
            'newsvine' =>'Newsvine',
            'nujij'=>'Nujij',
            'odnoklassniki_ru'=>'Odnoklassniki',
            'oknotizie'=>'OKNOtizie',
            'orkut'=>'Orkut',
            'oyyla'=>'Oyyla',
            'packg'=>'Packg',
            'pafnetde'=>'Pafnet',
            'pdfmyurl'=>'PDFmyURL',
            'pdfonline'=>'PDF Online',
            'phonefavs'=>'PhoneFavs',
            'planypus'=>'Planypus',
            'plaxo'=>'Plaxo', 
            'plurk'=>'Plurk',  
            'pocket'=>'Pocket',   
            'posteezy'=>'Posteezy',   
            'posterous'=>'Posterous', 
            'pusha'=>'Pusha', 
            'qrfin' =>'QRF.in',
            'qrsrc'=>'QRSrc.com',
            'quantcast'=>'Quantcast',
            'qzone'=>'Qzone',
            'raiseyourvoice'=>'Write Your Rep',
            'reddit'=>'Reddit',
            'rediff'=>'Rediff MyPage',
            'redkum'=>'RedKum',
            'researchgate'=>'ResearchGate',
            'safelinking'=>'Safelinking',
            'scoopat'=>'Scoop.at',
            'scoopit'=>'Scoop.it',
            'sekoman'=>'Sekoman',
            'select2gether'=>'Select2Gether',
            'sharer'=>'Sharer',
            'shaveh'=>'Shaveh',
            'shetoldme'=>'She Told Me', 
            'sinaweibo'=>'Sina Weibo',  
            'skyrock'=>'Skyrock Blog',   
            'smiru'=>'SMI',   
            'socialbookmarkingnet'=>'BookmarkingNet', 
            'sodahead'=>'SodaHead', 
            'sonico' =>'Sonico',
            'spinsnap'=>'SpinSnap',
            'springpad'=>'springpad',
            'startaid'=>'Startaid',
            'startlap'=>'Startlap',
            'storyfollower'=>'StoryFollower',
            'studivz' =>'studiVZ',
            'stuffpit'=>'Stuffpit',
            'stumpedia'=>'Stumpedia',
            'stylishhome'=>'FabDesign',
            'sunlize'=>'Sunlize',
            'supbro'=>'SUP BRO',
            'surfingbird'=>'Surfingbird',
            'svejo'=>'Svejo',
            'symbaloo'=>'Symbaloo',
            'taaza'=>'TaazaShare',
            'tagza'=>'Tagza',
            'taringa'=>'Taringa!',
            'technerd'=>'Communicate', 
            'textme'=>'Textme',  
            'thefancy'=>'The Fancy',   
            'thefreedictionary'=>'FreeDictionary',   
            'thewebblend'=>'The Web Blend', 
            'thinkfinity'=>'Thinkfinity', 
            'thisnext' =>'ThisNext',
            'throwpile'=>'Throwpile',
            'toly'=>'to.ly',
            'topsitelernet'=>'TopSiteler',
            'transferr'=>'Transferr',
            'tuenti'=>'Tuenti',
            'tulinq'=>'Tulinq',
            'tvinx'=>'Tvinx',
            'twitthis'=>'TwitThis',
            'typepad'=>'Typepad',
            'upnews'=>'Upnews.it',
            'urlaubswerkde'=>'Urlaubswerk',
            'viadeo'=>'Viadeo',
            'virb'=>'Virb',
            'visitezmonsite'=>'VisitezMonSite',
            'vk'=>'VKontakte',
            'vkrugudruzei'=>'vKruguDruzei', 
            'voxopolis'=>'VOX Social',  
            'vybralisme'=>'VybraliSME',   
            'w3validator'=>'HTML Validator',   
            'webnews'=>'Webnews',
            'webshare'=>'WebShare', 
            'werkenntwen' =>'WerKenntWen',
            'wirefan'=>'WireFan',
            'windows'=>'Windows Gadget',
            'wowbored'=>'WowBored',
            'wykop'=>'Wykop',
            'xanga'=>'Xanga',
            'xing' =>'XING',
            'yahoobkm'=>'Y! Bookmarks',
            'yammer'=>'Yammer',
            'yardbarker'=>'Yardbarker',
            'yigg'=>'Yigg',
            'yiid'=>'Spreadly',
            'yookos'=>'Yookos',
            'yoolink'=>'Yoolink',
            'yorumcuyum'=>'Yorumcuyum',
            'youmob'=>'YouMob',
            'yuuby'=>'Yuuby',
            'zakladoknet'=>'Zakladok.net',
            'ziczac'=>'ZicZac', 
            'zingme'=>'ZingMe',
        );
    private $_hooks = array();
	public function __construct()
	{
		$this->name          = 'staddthisbutton';
		$this->tab           = 'front_office_features';
		$this->version       = '1.2.7';
		$this->author        = 'SUNNYTOO.COM';
		$this->need_instance = 0;
        $this->bootstrap     = true;

		parent::__construct();
        
        $this->initHookArray();

		$this->displayName  = $this->l('Add this button');
		$this->description  = $this->l('The Largest Sharing and Social Data Platform.');
	}
    
    private function initHookArray()
    {
        $this->_hooks = array(
                array(
                    'id' => 'displayLeftColumn',
                    'val' => '1',
                    'name' => $this->l('Left column')
                ),
                array(
        			'id' => 'displayRightColumn',
        			'val' => '1',
        			'name' => $this->l('Right column')
        		),
        		array(
        			'id' => 'displayProductSecondaryColumn',
        			'val' => '1',
        			'name' => $this->l('Product secondary column')
        		),
                array(
        			'id' => 'displayLeftColumnProduct',
        			'val' => '1',
        			'name' => $this->l('Left column product')
        		),
                array(
                    'id' => 'displayRightColumnProduct',
                    'val' => '1',
                    'name' => $this->l('Right column product')
                ),
                array(
                    'id' => 'displayFooterProduct',
                    'val' => '1',
                    'name' => $this->l('Under the product description')
                ),
            );
    }
    
    private function saveHook()
    {
        foreach($this->_hooks AS $value)
        {
            $id_hook = Hook::getIdByName($value['id']);
            
            if (Tools::getValue('display_on_'.$value['id']))
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
        // clear module cache to apply new data.
        Cache::clean('hook_module_list');
    }

	public function install()
	{
		if (!parent::install() 
            || !$this->registerHook('displayStBlogArticleFooter')
            || !$this->registerHook('displayProductSecondaryColumn')
            || !$this->registerHook('displayHeader')
            || !Configuration::updateValue('ST_ADDTHIS_STYLE', 2)
            || !Configuration::updateValue('ST_ADDTHIS_STYLE_FOR_BLOG', 0)
            || !Configuration::updateValue('ST_ADDTHIS_PUBID', '')
            || !Configuration::updateValue('ST_ADDTHIS_CUSTOMIZING', '')
            || !Configuration::updateValue('ST_ADDTHIS_CUSTOMIZING_S', '')
            || !Configuration::updateValue('ST_ADDTHIS_CUSTOMIZING_FOR_BLOG', '')
            || !Configuration::updateValue('ST_ADDTHIS_CUSTOMIZING_FBS', '')
            || !Configuration::updateValue('ST_ADDTHIS_SHOW_MORE', 1)
            || !Configuration::updateValue('ST_ADDTHIS_SHOW_MORE_FOR_BLOG', 1)
            || !Configuration::updateValue('ST_ADDTHIS_EXTRA_ATTR', Tools::jsonEncode(array()))
            || !Configuration::updateValue('ST_ADDTHIS_EXTRA_ATTR_FOR_BLOG', Tools::jsonEncode(array()))
            || !Configuration::updateValue('ST_ADDTHIS_FB_IMAGE', '')
        )
			return false;
		$this->_clearCache('*');
		return true;
	}
	
	public function uninstall()
	{
		$this->_clearCache('*');   
		return parent::uninstall();
	}
    
    public function digPost($prefix = '', $trim = true)
    {
        $result = array();
        foreach($_POST AS $k => $v)
            if (strpos($k, $prefix) !== false)
                if ($trim)
                    $result[str_replace($prefix,'',$k)] = $v;
                else
                    $result[$k] = $v;
        return $result;
    }
    
    public function getContent()
	{
	    $this->context->controller->addCSS($this->_path. 'views/css/admin.css');
		$this->context->controller->addJS($this->_path. 'views/js/admin.js');
        if(Tools::getValue('act')=='delete_image' && Tools::getValue('ajax') == 1)
        {
            if($image = Configuration::get('ST_ADDTHIS_FB_IMAGE'))
            {
                @unlink(_PS_UPLOAD_DIR_.$image);
                Configuration::updateValue('ST_ADDTHIS_FB_IMAGE', '');
            }
            $this->_clearCache('*'); 
            $result['r'] = true;
            die(json_encode($result));
        }
		if (isset($_POST['savestaddthisbutton']))
		{
            $c      = Tools::getValue('customizing');
            $cfb    = Tools::getValue('customizing_for_blog');
            $cs     = Tools::getValue('customizing_specail');
            $cfbs   = Tools::getValue('customizing_for_blog_specail');
            if (Tools::getValue('style') == 3)
            {
                $c = array();
                $cs = array();
            }
            
            if (Tools::getValue('style_for_blog') == 3)
            {
                $cfbs = array();
                $cfb = array();
            }
            $errors = array();
            // Advanced attribute.
            $extra_attr = Tools::jsonEncode($this->digPost('at_ext_'));
            $extra_attr_for_blog = Tools::jsonEncode($this->digPost('for_blog_ext_'));
            
            if (Tools::getValue('addthis_extra_attr') && !Tools::getValue('addthis_for_blog_extra_attr'))
                $_POST['addthis_for_blog_extra_attr'] = Tools::getValue('addthis_extra_attr');
            if (!Tools::getValue('addthis_extra_attr') && Tools::getValue('addthis_for_blog_extra_attr'))
                $_POST['addthis_extra_attr'] = Tools::getValue('addthis_for_blog_extra_attr');
            
            if (!Configuration::updateValue('ST_ADDTHIS_STYLE', (int)Tools::getValue('style'))
                || !Configuration::updateValue('ST_ADDTHIS_STYLE_FOR_BLOG', (int)Tools::getValue('style_for_blog'))
                || !Configuration::updateValue('ST_ADDTHIS_PUBID', (string)Tools::getValue('pubid'))
                || !Configuration::updateValue('ST_ADDTHIS_CUSTOMIZING', $c?(string)serialize(array_unique($c)):'')
                || !Configuration::updateValue('ST_ADDTHIS_CUSTOMIZING_FOR_BLOG', $cfb?(string)serialize(array_unique($cfb)):'')
                || !Configuration::updateValue('ST_ADDTHIS_CUSTOMIZING_S', $cs?(string)serialize(array_unique($cs)):'')
                || !Configuration::updateValue('ST_ADDTHIS_CUSTOMIZING_FBS',$cfbs?(string)serialize(array_unique($cfbs)):'')
                || !Configuration::updateValue('ST_ADDTHIS_SHOW_MORE', (int)Tools::getValue('show_more'))
                || !Configuration::updateValue('ST_ADDTHIS_SHOW_MORE_FOR_BLOG', (int)Tools::getValue('show_more_for_blog'))
                || !Configuration::updateValue('ST_ADDTHIS_EXTRA_ATTR', $extra_attr)
                || !Configuration::updateValue('ST_ADDTHIS_EXTRA_ATTR_FOR_BLOG', $extra_attr_for_blog)
            )
                $this->_html .= count($errors) ? implode('',$errors) : $this->displayError($this->l('Cannot update settings'));
            
            if (isset($_FILES['fb_image']) && isset($_FILES['fb_image']['tmp_name']) && !empty($_FILES['fb_image']['tmp_name'])) 
            {
				$image_name = 'og_fb_image_'.(int)Shop::getContextShopID().'.jpg';
				if (!move_uploaded_file($_FILES['fb_image']['tmp_name'], _PS_UPLOAD_DIR_.$image_name))
					$errors[] = Tools::displayError('Error move uploaded file');
                else
				   Configuration::updateValue('ST_ADDTHIS_FB_IMAGE', $image_name);
			}
            if (count($errors))
                $this->_html .= implode('',$errors);
            else
            {
                $this->saveHook();
                $this->_html .= $this->displayConfirmation($this->l('Settings updated')); 
            }    
			
		    $this->_clearCache('*');       
        }
		$helper = $this->initForm();
		return $this->_html.$helper->generateForm($this->fields_form);
	}
    protected function initForm()
	{        
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Product page'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'radio',
					'label' => $this->l('Select style:'),
					'name' => 'style',
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'style_one',
							'value' => 0,
							'label' => $this->l('Style 1')),
						array(
							'id' => 'style_two',
							'value' => 1,
							'label' => $this->l('Style 2')),
						array(
							'id' => 'style_three',
							'value' => 2,
							'label' => $this->l('Style 3')),
						array(
							'id' => 'style_four',
							'value' => 3,
							'label' => $this->l('Style 4')),
					),
                    'desc' => $this->l('Style 1').'&nbsp;&nbsp;<img src="'.$this->_path.'views/img/style_1.jpg" /><br/>'.$this->l('Style 2').'&nbsp;&nbsp;<img src="'.$this->_path.'views/img/style_2.jpg" /><br/>'.$this->l('Style 3').'&nbsp;&nbsp;<img src="'.$this->_path.'views/img/style_3.jpg" /><br/>'.$this->l('Style 4').'&nbsp;&nbsp;<img src="'.$this->_path.'views/img/style_4.jpg" /><br/>',
				),
                array(
					'type' => 'selector',
                    'label' => $this->l('Disable and select your own buttons:'),
					'name' => 'customizing[]',
					'addthis' => self::$customizing_array,
                    'addthis_specail' => self::$customizing_specail,
                    'name_specail' => 'customizing_specail[]',
                    'class_t' => 'customizing',
                    'desc' => array(
                        $this->l('Leaving SelectedButtons empty will use default buttons.'),
                        $this->l('Style 4 does not support this feature.'),
                    ),
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Show "More" button:'),
					'name' => 'show_more',
					'values' => array(
						array(
                            'id'    => 's_1',
							'value' => 1,
							'label' => $this->l('Yes')
						),
						array(
                            'id'    => 's_0',
							'value' => 0,
							'label' => $this->l('No')
						)
					),
				),
                array(
					'type' => 'checkbox',
					'label' => $this->l('Display on:'),
					'name' => 'display_on',
					'lang' => true,
					'values' => array(
						'query' => $this->_hooks,
						'id' => 'id',
						'name' => 'name'
					)
				),
			),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			)
		);
        
        $this->fields_form[1]['form'] = array(
			'legend' => array(
				'title' => $this->l('Blog page'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                array(
					'type' => 'radio',
					'label' => $this->l('Select style(Blog page):'),
					'name' => 'style_for_blog',
                    'default_value' => 0,
					'values' => array(
						array(
							'id' => 'style_for_blog_one',
							'value' => 0,
							'label' => $this->l('Style 1')),
						array(
							'id' => 'style_for_blog_two',
							'value' => 1,
							'label' => $this->l('Style 2')),
						array(
							'id' => 'style_for_blog_three',
							'value' => 2,
							'label' => $this->l('Style 3')),
						array(
							'id' => 'style_for_blog_four',
							'value' => 3,
							'label' => $this->l('Style 4')),
					),
                    'desc' => $this->l('Style 1').'&nbsp;&nbsp;<img src="'.$this->_path.'views/img/style_1.jpg" /><br/>'.$this->l('Style 2').'&nbsp;&nbsp;<img src="'.$this->_path.'views/img/style_2.jpg" /><br/>'.$this->l('Style 3').'&nbsp;&nbsp;<img src="'.$this->_path.'views/img/style_3.jpg" /><br/>'.$this->l('Style 4').'&nbsp;&nbsp;<img src="'.$this->_path.'views/img/style_4.jpg" /><br/>',
				),
                array(
					'type' => 'selector',
                    'label' => $this->l('Disable and select your own buttons:'),
					'name' => 'customizing_for_blog[]',
					'addthis' => self::$customizing_array,
                    'addthis_specail' => self::$customizing_specail,
                    'name_specail' => 'customizing_for_blog_specail[]',
                    'class_t' => 'customizing_for_blog',
                    'desc' => array(
                        $this->l('Leaving SelectedButtons empty will use default buttons.'),
                        $this->l('Style 4 does not support this feature.'),
                    ),
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Show "More" button:'),
					'name' => 'show_more_for_blog',
					'values' => array(
						array(
                            'id'    => 's_m_1',
							'value' => 1,
							'label' => $this->l('Yes')
						),
						array(
                            'id'    => 's_m_0',
							'value' => 0,
							'label' => $this->l('No')
						)
					),
				),  
			),
			'submit' => array(
				'title' => $this->l('   Save all   '),
			)
		);
        
        $this->fields_form[2]['form'] = array(
			'legend' => array(
				'title' => $this->l('Setting'),
                'icon' => 'icon-cogs'
			),
			'input' => array(
                
                array(
					'type' => 'text',
					'label' => $this->l('Addthis ID:'),
					'name' => 'pubid',
					'size' => 64,
                    'desc' => $this->l('Input your own Addthis id ex.: ra-516bd3c977d5eb6c for analitycs.').'<br/><img src="'.$this->_path.'views/img/addthis_id.jpg" />',
				),
                'fb_image' => array(
					'type' => 'file',
					'label' => $this->l('Facebook thumbnail:'),
					'name' => 'fb_image',
					'size' => 64,
                    'desc' => $this->l('The minimum size is 200px x 200px. This image will be shown when someone shares your site on Facebook.'),
				)
            ),
			'submit' => array(
				'title' => $this->l('   Save all  '),
			)
        );
        
        if (Configuration::get('ST_ADDTHIS_FB_IMAGE'))
            $this->fields_form[2]['form']['input']['fb_image']['desc'] .= '<br/><img src="'._THEME_PROD_PIC_DIR_.Configuration::get('ST_ADDTHIS_FB_IMAGE').'" class="img_preview">
            <p><a href="javascript:;" class="btn btn-default st_delete_image"><i class="icon-trash"></i> Delete</a></p>';
        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
        $helper->module = $this;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savestaddthisbutton';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
        $helper->tpl_vars['extra_attr'] = Configuration::get('ST_ADDTHIS_EXTRA_ATTR')?Configuration::get('ST_ADDTHIS_EXTRA_ATTR'):Tools::jsonEncode(array());
        $helper->tpl_vars['extra_attr_for_blog'] = Configuration::get('ST_ADDTHIS_EXTRA_ATTR_FOR_BLOG')?Configuration::get('ST_ADDTHIS_EXTRA_ATTR_FOR_BLOG'):Tools::jsonEncode(array());
		return $helper;
	}
    public function hookDisplayRightColumnProduct($params)
    {
		if (!$this->isCached('staddthisbutton.tpl', $this->stGetCacheId(1)))
        {
            if ($extra_attr = Configuration::get('ST_ADDTHIS_EXTRA_ATTR'))
                $extra_attr = (array)Tools::jsonDecode($extra_attr);
    		$this->smarty->assign(array(
                'addthis_style' => Configuration::get('ST_ADDTHIS_STYLE'),
                'addthis_pubid' => Configuration::get('ST_ADDTHIS_PUBID'),
                'addthis_customizing' => Configuration::get('ST_ADDTHIS_CUSTOMIZING')?@unserialize(Configuration::get('ST_ADDTHIS_CUSTOMIZING')):array(),
                'addthis_show_more' => Configuration::get('ST_ADDTHIS_SHOW_MORE'),
                'addthis_style_one' => Configuration::get('ST_ADDTHIS_CUSTOMIZING_S')?@unserialize(Configuration::get('ST_ADDTHIS_CUSTOMIZING_S')):array(),
                'addthis_extra_attr' => $extra_attr
    		));
        }
		return $this->display(__FILE__, 'staddthisbutton.tpl', $this->stGetCacheId(1));
    }
    public function hookDisplayLeftColumnProduct($params)
    {
        return $this->hookDisplayRightColumnProduct($params);
    }
    public function hookDisplayProductSecondaryColumn($params)
    {
        return $this->hookDisplayRightColumnProduct($params);
    }
    public function hookDisplayStBlogArticleFooter($params)
    {
		if (!$this->isCached('staddthisbutton.tpl', $this->stGetCacheId(2)))
        {
            if ($extra_attr = Configuration::get('ST_ADDTHIS_EXTRA_ATTR_FOR_BLOG'))
                $extra_attr = (array)Tools::jsonDecode($extra_attr);
    		$this->smarty->assign(array(
                'addthis_style' => Configuration::get('ST_ADDTHIS_STYLE_FOR_BLOG'),
                'addthis_pubid' => Configuration::get('ST_ADDTHIS_PUBID'),
                'addthis_customizing' => Configuration::get('ST_ADDTHIS_CUSTOMIZING_FOR_BLOG')?@unserialize(Configuration::get('ST_ADDTHIS_CUSTOMIZING_FOR_BLOG')):array(),
                'addthis_show_more' => Configuration::get('ST_ADDTHIS_SHOW_MORE_FOR_BLOG'),
                'addthis_style_one' => Configuration::get('ST_ADDTHIS_CUSTOMIZING_FBS')?@unserialize(Configuration::get('ST_ADDTHIS_CUSTOMIZING_FBS')):array(),
                'addthis_extra_attr' => $extra_attr                
    		));
        }
		return $this->display(__FILE__, 'staddthisbutton.tpl', $this->stGetCacheId(2));
    }
	protected function stGetCacheId($key,$name = null)
	{
		$cache_id = parent::getCacheId($name);
		return $cache_id.'_'.$key;
	}
    public function hookDisplayLeftColumn($params)
    {
        return $this->hookDisplayRightColumnProduct($params);
    }
    public function hookDisplayRightColumn($params)
    {
        return $this->hookDisplayRightColumnProduct($params);
    }
    public function hookDisplayFooterProduct($params)
    {
        return $this->hookDisplayRightColumnProduct($params);
    }
    public function hookDisplayHeader()
    {
        $id_lang = Context::getContext()->language->id;
        $page_name = Context::getContext()->smarty->getTemplateVars('page_name');
        if ($page_name == 'product' && $id_product = Tools::getValue('id_product'))
        {
            $product = new Product($id_product, false, $id_lang);
            $cover   = $product->getCover($id_product);
            $this->smarty->assign(array('product'=>$product, 'cover'=>$cover, 'id_lang' => $id_lang));
        }
        if ($page_name == 'category' && $id_category = Tools::getValue('id_category'))
        {
            $category= new Category($id_category, $id_lang);
            $this->smarty->assign(array('category'=>$category,'id_lang' => $id_lang));
        }
        if($page_name == 'module-stblog-article' && $id_st_blog = Tools::getValue('id_blog'))
        {
            $blog = new StBlogClass($id_st_blog);
            $cover = StBlogImageClass::getCoverImage($id_st_blog, $id_lang, 1);
            if ($cover)
                $cover = StBlogImageClass::getImageLinks($cover,1);
   
            if ($blog->type == 2 && !$cover)
            {
                $galleris = StBlogImageClass::getGalleries($id_st_blog, $id_lang);
                foreach($galleris AS $gallery)
                {
                    $cover = StBlogImageClass::getImageLinks($gallery, 2);
                    break;
                }  
            }
            $this->smarty->assign(array(
                    'blog'=>$blog, 
                    'cover'=>$cover, 
                    'meta_title'=> $blog->meta_title[$id_lang],
                    'meta_description' => $blog->meta_description[$id_lang]
                ));
        }
        if ($fb_image = Configuration::get('ST_ADDTHIS_FB_IMAGE'))
        {
            $this->smarty->assign(array('fb_image_link' => _PS_BASE_URL_._THEME_PROD_PIC_DIR_.$fb_image));
        }
        return $this->display(__FILE__, 'staddthisbutton-header.tpl');
    }
    private function getConfigFieldsValues()
    {
        $fields_values = array(
            'style'             => (int)Configuration::get('ST_ADDTHIS_STYLE'),
            'style_for_blog'    => (int)Configuration::get('ST_ADDTHIS_STYLE_FOR_BLOG'),
            'pubid'             => Configuration::get('ST_ADDTHIS_PUBID'),
            'customizing[]'     => Configuration::get('ST_ADDTHIS_CUSTOMIZING')?@unserialize(Configuration::get('ST_ADDTHIS_CUSTOMIZING')):array(),
            'customizing_for_blog[]' => Configuration::get('ST_ADDTHIS_CUSTOMIZING_FOR_BLOG')?@unserialize(Configuration::get('ST_ADDTHIS_CUSTOMIZING_FOR_BLOG')):array(),
            'show_more'         => (int)Configuration::get('ST_ADDTHIS_SHOW_MORE'),
            'show_more_for_blog' => (int)Configuration::get('ST_ADDTHIS_SHOW_MORE_FOR_BLOG'),
            'customizing_specail[]' => Configuration::get('ST_ADDTHIS_CUSTOMIZING_S')?@unserialize(Configuration::get('ST_ADDTHIS_CUSTOMIZING_S')):array(),
            'customizing_for_blog_specail[]' => Configuration::get('ST_ADDTHIS_CUSTOMIZING_FBS')?@unserialize(Configuration::get('ST_ADDTHIS_CUSTOMIZING_FBS')):array(),
        );
        
        foreach($this->_hooks AS $value)
        {
            $fields_values['display_on_'.$value['id']] = 0;
            if($id_hook = Hook::getIdByName($value['id']))
                if(Hook::getModulesFromHook($id_hook, $this->id))
                    $fields_values['display_on_'.$value['id']] = 1;
        }
        
        return $fields_values;
    }
}