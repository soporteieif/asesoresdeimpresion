<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
require_once dirname(__FILE__).'../../../classes/StBlogLoader.php';
StBlogLoader::load(array('ImageClass'));
class AdminStBlogConfigController extends AdminController
{
    public static $easing = array(
		array('value' => 0, 'name' => 'swing'),
		array('value' => 1, 'name' => 'easeInQuad'),
		array('value' => 2, 'name' => 'easeOutQuad'),
		array('value' => 3, 'name' => 'easeInOutQuad'),
		array('value' => 4, 'name' => 'easeInCubic'),
		array('value' => 5, 'name' => 'easeOutCubic'),
		array('value' => 6, 'name' => 'easeInOutCubic'),
		array('value' => 7, 'name' => 'easeInQuart'),
		array('value' => 8, 'name' => 'easeOutQuart'),
		array('value' => 9, 'name' => 'easeInOutQuart'),
		array('value' => 10, 'name' => 'easeInQuint'),
		array('value' => 11, 'name' => 'easeOutQuint'),
		array('value' => 12, 'name' => 'easeInOutQuint'),
		array('value' => 13, 'name' => 'easeInSine'),
		array('value' => 14, 'name' => 'easeOutSine'),
		array('value' => 15, 'name' => 'easeInOutSine'),
		array('value' => 16, 'name' => 'easeInExpo'),
		array('value' => 17, 'name' => 'easeOutExpo'),
		array('value' => 18, 'name' => 'easeInOutExpo'),
		array('value' => 19, 'name' => 'easeInCirc'),
		array('value' => 20, 'name' => 'easeOutCirc'),
		array('value' => 21, 'name' => 'easeInOutCirc'),
		array('value' => 22, 'name' => 'easeInElastic'),
		array('value' => 23, 'name' => 'easeOutElastic'),
		array('value' => 24, 'name' => 'easeInOutElastic'),
		array('value' => 25, 'name' => 'easeInBack'),
		array('value' => 26, 'name' => 'easeOutBack'),
		array('value' => 27, 'name' => 'easeInOutBack'),
		array('value' => 28, 'name' => 'easeInBounce'),
		array('value' => 29, 'name' => 'easeOutBounce'),
		array('value' => 30, 'name' => 'easeInOutBounce'),
	);
    
    public static $items = array(
		array('value' => 1, 'name' => '1'),
		array('value' => 2, 'name' => '2'),
		array('value' => 3, 'name' => '3'),
		array('value' => 4, 'name' => '4'),
		array('value' => 5, 'name' => '5'),
		array('value' => 6, 'name' => '6'),
    );
        
    public static $sort_by = array(
        array('value' =>1 , 'name' => 'Date add: Desc'),
        array('value' =>2 , 'name' => 'Date add: Asc'),
        array('value' =>3 , 'name' => 'Date update: Desc'),
        array('value' =>4 , 'name' => 'Date update: Asc'),
        array('value' =>5 , 'name' => 'Blog title: A to Z'),
        array('value' =>6 , 'name' => 'Blog title: Z to A'),
        array('value' =>7 , 'name' => 'Blog ID: Desc'),
        array('value' =>8 , 'name' => 'Blog ID: Asc'),
    );
    
    public function __construct()
	{
	    $this->bootstrap      = true;
		$this->className      = 'Configuration';
		$this->table          = 'configuration';

		parent::__construct();

		$this->fields_options = array(
			'general' => array(
				'title' =>	$this->l('General'),
                'icon' => 'icon-cogs',
				'fields' =>	array(
        			'ST_BLOG_META_TITLE' => array(
						'title' => $this->l('Meta title'),
        				'validation' => 'isGenericName',
        				'size' => 60,
        				'type' => 'textLang',
        			),
        			'ST_BLOG_META_KEYWORDS' => array(
						'title' => $this->l('Meta keywords'),
        				'validation' => 'isGenericName',
        				'size' => 60,
        				'type' => 'textLang',
        			),
        			'ST_BLOG_META_DESCRIPTION' => array(
						'title' => $this->l('Meta desciption'),
        				'validation' => 'isGenericName',
        				'size' => 30,
        				'type' => 'textareaLang',
        				'cols' => 60,
        				'rows' => 6,
        			),
					'ST_BLOG_CATE_LAYOUTS' => array(
						'title' => $this->l('Category layout'),
						'cast' => 'intval',
						'show' => true,
						'required' => false,
						'type' => 'radio',
						'validation' => 'isUnsignedInt',
						'choices' => array(
							1 => $this->l('Large image layout'),
							2 => $this->l('Medium image layout'),
							3 => $this->l('Grid layout'),
						),
					),
                    'ST_BLOG_ROUNT_NAME' => array(
						'title' => $this->l('Route name'),
        				'validation' => 'isGenericName',
        				'size' => 60,                        
        				'type' => 'textLang',
                        'desc' => $this->l('Default is "blog",for example: www.domain.com/blog'),                        
        			),
                    'ST_LENGTH_OF_ARTICLE_NAME' => array(
						'title' => $this->l('Length of article names'),
						'cast' => 'intval',
						'show' => true,
						'required' => false,
						'type' => 'radio',
						'validation' => 'isUnsignedInt',
						'choices' => array(
							0 => $this->l('Normal(70 characters)'),
							1 => $this->l('Full name'),
						),
					),                    
    				'STSN_BLOG_GRID_PER_LG_0' => array(
    					'title' => $this->l('Articles per row in grid layout on large devices (>1200px)'),
    					'validation' => 'isInt',
    					'cast' => 'intval',
    					'type' => 'select',
    					'list' => self::$items,
    					'identifier' => 'value',
    				),
    				'STSN_BLOG_GRID_PER_MD_0' => array(
    					'title' => $this->l('Articles per row in grid layout on medium devices (>992px)'),
    					'validation' => 'isInt',
    					'cast' => 'intval',
    					'type' => 'select',
    					'list' => self::$items,
    					'identifier' => 'value',
    				),
    				'STSN_BLOG_GRID_PER_SM_0' => array(
    					'title' => $this->l('Articles per row in grid layout on Small devices (>768px)'),
    					'validation' => 'isInt',
    					'cast' => 'intval',
    					'type' => 'select',
    					'list' => self::$items,
    					'identifier' => 'value',
    				),
    				'STSN_BLOG_GRID_PER_XS_0' => array(
    					'title' => $this->l('Articles per row in grid layout on Extra small devices (>480px)'),
    					'validation' => 'isInt',
    					'cast' => 'intval',
    					'type' => 'select',
    					'list' => self::$items,
    					'identifier' => 'value',
    				),
    				'STSN_BLOG_GRID_PER_XXS_0' => array(
    					'title' => $this->l('Articles per row in grid layout on Extremely small devices (<480px)'),
    					'validation' => 'isInt',
    					'cast' => 'intval',
    					'type' => 'select',
    					'list' => self::$items,
    					'identifier' => 'value',
    				),
					'ST_BLOG_COLUMN_HOMEPAGE' => array(
						'title' => $this->l('Homepage layout'),
						'cast' => 'intval',
						'show' => true,
						'required' => false,
						'type' => 'radio',
						'validation' => 'isUnsignedInt',
						'choices' => array(
							1 => $this->l('2 columns, leftcolumn'),
							2 => $this->l('2 columns, rightcolumn'),
							4 => $this->l('Single column, without left/right column'),
						),
					),
					'ST_BLOG_COLUMN_CATEGORY' => array(
						'title' => $this->l('Category layout'),
						'cast' => 'intval',
						'show' => true,
						'required' => false,
						'type' => 'radio',
						'validation' => 'isUnsignedInt',
						'choices' => array(
							1 => $this->l('2 columns, leftcolumn'),
							2 => $this->l('2 columns, rightcolumn'),
							4 => $this->l('Single column, without left/right column'),
						),
					),
					'ST_BLOG_COLUMN_ARTICLE' => array(
						'title' => $this->l('Article layout'),
						'cast' => 'intval',
						'show' => true,
						'required' => false,
						'type' => 'radio',
						'validation' => 'isUnsignedInt',
						'choices' => array(
							1 => $this->l('2 columns, leftcolumn'),
							2 => $this->l('2 columns, rightcolumn'),
							4 => $this->l('Single column, without left/right column'),
						),
					),
					'ST_BLOG_PER_PAGE' => array(
						'title' => $this->l('Blogs per page'),
						'desc' => $this->l('Number of blogs displayed per page. Default is 10.'),
						'validation' => 'isUnsignedInt',
						'cast' => 'intval',
						'type' => 'text',
                        'class' => 'fixed-width-sm'
					),
                    'ST_BLOG_CATE_SORT_BY' => array(
						'title' => $this->l('Default sort by'),
    					'validation' => 'isInt',
    					'cast' => 'intval',
    					'type' => 'select',
    					'list' => self::$sort_by,
    					'identifier' => 'value',
					),
					'ST_BLOG_DISPLAY_VIEWCOUNT' => array(
						'title' => $this->l('Display viewcount on each post'),
						'validation' => 'isBool',
						'cast' => 'intval',
						'required' => false,
						'type' => 'bool',
					),
				),
    			'submit' => array(
    				'title' => $this->l('Save all'),
    			)
			),
            'related' => array(
                'title' => $this->l('Related products'),
                'icon' => 'icon-cogs',
				'fields' =>	array(
					'ST_BLOG_RELATED_DISPLAY_PRICE' => array(
						'title' => $this->l('Display price on products'),
						'validation' => 'isBool',
						'cast' => 'intval',
						'required' => false,
						'type' => 'bool',
					),
    				'STSN_BLOG_R_PRO_PER_LG_0' => array(
    					'title' => $this->l('The number of columns on large devices (>1200px)'),
    					'validation' => 'isInt',
    					'cast' => 'intval',
    					'type' => 'select',
    					'list' => self::$items,
    					'identifier' => 'value',
    				),
    				'STSN_BLOG_R_PRO_PER_MD_0' => array(
    					'title' => $this->l('The number of columns on medium devices (>992px)'),
    					'validation' => 'isInt',
    					'cast' => 'intval',
    					'type' => 'select',
    					'list' => self::$items,
    					'identifier' => 'value',
    				),
    				'STSN_BLOG_R_PRO_PER_SM_0' => array(
    					'title' => $this->l('The number of columns on Small devices (>768px)'),
    					'validation' => 'isInt',
    					'cast' => 'intval',
    					'type' => 'select',
    					'list' => self::$items,
    					'identifier' => 'value',
    				),
    				'STSN_BLOG_R_PRO_PER_XS_0' => array(
    					'title' => $this->l('The number of columns on Extra small devices (>480px)'),
    					'validation' => 'isInt',
    					'cast' => 'intval',
    					'type' => 'select',
    					'list' => self::$items,
    					'identifier' => 'value',
    				),
    				'STSN_BLOG_R_PRO_PER_XXS_0' => array(
    					'title' => $this->l('The number of columns on Extra extra small devices (<480px)'),
    					'validation' => 'isInt',
    					'cast' => 'intval',
    					'type' => 'select',
    					'list' => self::$items,
    					'identifier' => 'value',
    				),
					'ST_BLOG_RELATED_SLIDESHOW' => array(
						'title' => $this->l('Autoplay'),
						'validation' => 'isBool',
						'cast' => 'intval',
						'required' => false,
						'type' => 'bool',
					),
					'ST_BLOG_RELATED_S_SPEED' => array(
						'title' => $this->l('Time'),
						'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
						'validation' => 'isUnsignedInt',
						'cast' => 'intval',
						'type' => 'text',
					),
					'ST_BLOG_RELATED_A_SPEED' => array(
						'title' => $this->l('Transition period'),
						'desc' => $this->l('The period, in milliseconds, of the transition effect.'),
						'validation' => 'isUnsignedInt',
						'cast' => 'intval',
						'type' => 'text',
					),
					'ST_BLOG_RELATED_PAUSE' => array(
						'title' => $this->l('Pause On Hover'),
						'validation' => 'isBool',
						'cast' => 'intval',
						'required' => false,
						'type' => 'bool',
					),
    				'ST_BLOG_RELATED_EASING' => array(
    					'title' => $this->l('Easing method'),
                        'desc' => $this->l('The type of easing applied to the transition animation'),
    					'validation' => 'isInt',
    					'cast' => 'intval',
    					'type' => 'select',
    					'list' => self::$easing,
    					'identifier' => 'value',
    				),
					'ST_BLOG_RELATED_LOOP' => array(
						'title' => $this->l('Loop'),
                        'desc' => $this->l('"No" if you want to perform the animation once; "Yes" to loop the animation'),
						'validation' => 'isBool',
						'cast' => 'intval',
						'required' => false,
						'type' => 'bool'
					),
					'ST_BLOG_RELATED_MOVE' => array(
						'title' => $this->l('Move'),
						'cast' => 'intval',
						'show' => true,
						'required' => false,
						'type' => 'radio',
						'validation' => 'isUnsignedInt',
						'choices' => array(
							1 => $this->l('1 item'),
							0 => $this->l('All visible items')
						),
					),
				),
    			'submit' => array(
    				'title' => $this->l('Save all'),
    			)
            ),
            'slideshow' => array(
                'title' => $this->l('Blog slideshow'),
                'image' => '../img/admin/tab-categories.gif',
				'fields' =>	array(
					'ST_BLOG_SS_SLIDESHOW' => array(
						'title' => $this->l('Autoplay'),
						'validation' => 'isBool',
						'cast' => 'intval',
						'required' => false,
						'type' => 'bool',
					),
					'ST_BLOG_SS_S_SPEED' => array(
						'title' => $this->l('Time'),
						'desc' => $this->l('The period, in milliseconds, between the end of a transition effect and the start of the next one.'),
						'validation' => 'isUnsignedInt',
						'cast' => 'intval',
						'type' => 'text',
					),
					'ST_BLOG_SS_A_SPEED' => array(
						'title' => $this->l('Transition period'),
						'desc' => $this->l('The period, in milliseconds, of the transition effect.'),
						'validation' => 'isUnsignedInt',
						'cast' => 'intval',
						'type' => 'text',
					),
					'ST_BLOG_SS_PAUSE' => array(
						'title' => $this->l('Pause On Hover'),
						'validation' => 'isBool',
						'cast' => 'intval',
						'required' => false,
						'type' => 'bool',
					),
					'ST_BLOG_SS_LOOP' => array(
						'title' => $this->l('Loop'),
                        'desc' => $this->l('"No" if you want to perform the animation once; "Yes" to loop the animation'),
						'validation' => 'isBool',
						'cast' => 'intval',
						'required' => false,
						'type' => 'bool'
					),
				),
    			'submit' => array(
    				'title' => $this->l('Save all'),
    			)
            ),
            'images' => array(
                'title' => $this->l('Blog images'),
                'image' => '../img/admin/tab-categories.gif',
                'description' => $this->l('Manage blog image dimension for cover and gallery.'),
				'fields' =>	array(
                    'ST_BLOG_IMG_GALLERY_LG_W' => array(
						'title' => $this->l('Large width'),
						'validation' => 'isUnsignedInt',
						'cast' => 'intval',
                        'hint' => $this->l('Images dimension of large width'),
						'type' => 'text',
                        'suffix' => 'px'
					),
                    'ST_BLOG_IMG_GALLERY_LG_H' => array(
						'title' => $this->l('Large height'),
						'validation' => 'isUnsignedInt',
						'cast' => 'intval',
                        'hint' => $this->l('Images dimension of large height'),
						'type' => 'text',
                        'suffix' => 'px'
					),
                    'ST_BLOG_IMG_GALLERY_MD_W' => array(
						'title' => $this->l('Medium width'),
						'validation' => 'isUnsignedInt',
						'cast' => 'intval',
                        'hint' => $this->l('Images dimension of medium width'),
						'type' => 'text',
                        'suffix' => 'px'
					),
                    'ST_BLOG_IMG_GALLERY_MD_H' => array(
						'title' => $this->l('Medium height'),
						'validation' => 'isUnsignedInt',
						'cast' => 'intval',
                        'hint' => $this->l('Images dimension of medium height'),
						'type' => 'text',
                        'suffix' => 'px'
					),
                    'ST_BLOG_IMG_GALLERY_SM_W' => array(
						'title' => $this->l('Small width'),
						'validation' => 'isUnsignedInt',
						'cast' => 'intval',
                        'hint' => $this->l('Images dimension of small width'),
						'type' => 'text',
                        'suffix' => 'px'
					),
                    'ST_BLOG_IMG_GALLERY_SM_H' => array(
						'title' => $this->l('Small height'),
						'validation' => 'isUnsignedInt',
						'cast' => 'intval',
                        'hint' => $this->l('Images dimension of small height'),
						'type' => 'text',
                        'suffix' => 'px'
					),
                    'ST_BLOG_IMG_GALLERY_XS_W' => array(
						'title' => $this->l('Thumb width'),
						'validation' => 'isUnsignedInt',
						'cast' => 'intval',
                        'hint' => $this->l('Images dimension of thumb width'),
						'type' => 'text',
                        'suffix' => 'px'
					),
                    'ST_BLOG_IMG_GALLERY_XS_H' => array(
						'title' => $this->l('Thumb height'),
						'validation' => 'isUnsignedInt',
						'cast' => 'intval',
                        'hint' => $this->l('Images dimension of thumb height'),
						'type' => 'text',
                        'suffix' => 'px',
                        'desc' => '<br><br><div class="alert alert-info">
            				'.$this->l('Regenerates thumbnails for all existing blog images').'<br>
            				'.$this->l('Please be patient. This can take several minutes.').'<br>
            				'.$this->l('Be careful! Manually uploaded thumbnails will be erased and replaced by automatically generated thumbnails.').'
            			</div>
                        <script type="text/javascript">var c_msg = "'.$this->l('Are you sure ?').'";</script>
                        <div id="progress-warning" class="alert alert-warning" style="display: none">
                    		'.$this->l('In progress, Please do not leave this page...').'
                    	</div>
                        <div id="ajax-message-ok" class="conf ajax-message alert alert-success" style="display: none">
                        	<span class="message">'.$this->l('Regenerate thumbails successfully.').'</span>
                        </div>
                        <div id="ajax-message-ko" class="error ajax-message alert alert-danger" style="display: none">
                        	<span class="message"></span>
                        </div>
                        <button type="button" name="submitRegenerateimage_type" class="btn btn-default pull-left" id="btn_regenerate_thumbs">
        					<i class="process-icon-cogs"></i> Regenerate thumbnails
        				</button>'
					),
				),
    			'submit' => array(
    				'title' => $this->l('Save all'),
    			)
            ),
		);
	}
    
    public function setMedia()
	{
		parent::setMedia();
        
        $this->addCss(_PS_MODULE_DIR_.'stblog/views/css/admin.css');
        $this->addJs(_PS_MODULE_DIR_.'stblog/views/js/admin_blog.js');
	}
    
    public function ajaxProcessRegenerateThumbails()
	{
	    $result = array(
            'r' => false,
            'm' => ''
        );
        
        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP)
            $id_shop = Shop::getContextListShopID();
        else
            $id_shop = array((int)Shop::getContextShopID());
        
        $images = Db::getInstance()->executeS('
        SELECT i.* FROM '._DB_PREFIX_.'st_blog_image `i`
        INNER JOIN '._DB_PREFIX_.'st_blog_image_shop `is`
        ON `i`.`id_st_blog_image` = `is`.`id_st_blog_image`
        WHERE `id_shop` IN ('.implode(',', $id_shop).')
        ORDER BY `type`
        ');
        
        if ($images)
        {
            $path = _PS_UPLOAD_DIR_.'stblog/';
            $ext  = 'jpg';
            if (!is_dir($path) || !is_writable($path))
                $result['m'] = $path.$this->l(' is not writable');
            else
            {
                $max_execution_time = (int)ini_get('max_execution_time');
                set_time_limit(10*60);
                foreach($images AS $image)
                {
                    $file = $path.$image['type'].'/'.$image['id_st_blog'].'/'.$image['id_st_blog_image'].'/'.$image['id_st_blog'].$image['id_st_blog_image'].'.'.$ext;
                    if (!file_exists($file))
                    {
                        $result['m'] .= $file."\n";
                        continue;
                    }
                    $this->resizeImage($file, $image['type'], $image['id_st_blog'].$image['id_st_blog_image'], $ext); 
                }
                set_time_limit($max_execution_time);
                $result['r'] = true;
                if ($result['m'])
                    $result['m'] = $this->l('The following origin file not exists:'."\n").$result['m'];
            }
        }
        else
            $result['r'] = true;
		
        echo Tools::jsonEncode($result);
	}
    
    public function resizeImage($src_file, $image_type = 1, $basename = '', $ext = 'jpg')
    {
        if (!file_exists($src_file))
            return false;
        $ret = true;
        $types = StBlogImageClass::getDefImageTypes();
        if (!count($types) || !key_exists($image_type, $types))
            return false;
        foreach($types[$image_type] AS $key => $type)
        {
            if (!is_array($type) && count($type) < 2)
                continue;
                
            // Is image smaller than dest? fill it with white!
            $tmp_file_new = $src_file;
            list($src_width, $src_height) = getimagesize($src_file);
            if (!$src_width || !$src_height)
                continue;
            
            $width  = (int)$type[0];
            $height = $type[1] > 0 ? (int)$type[1] : $src_height;
            if ($src_width < $width || $src_height < $height)
            {
                $tmp_file_new = $src_file.'_new';
                ImageManager::resize($src_file, $tmp_file_new, $width, $height);
            }
                
            $options = array('jpegQuality' => Configuration::get('PS_JPEG_QUALITY') ? Configuration::get('PS_JPEG_QUALITY') : 80);
            $thumb = PhpThumbFactory::create($tmp_file_new, $options);
            if (!$type[1])
                $thumb->adaptiveResizeWidth($width);
            else
                $thumb->adaptiveResize($width, $height);
            $folder = dirname($src_file).'/';
            $thumb->save($folder.$basename.$key.'.'.$ext);
            $ret &= ImageManager::isRealImage($folder.$basename.$key.'.'.$ext);
        }
        if (file_exists($src_file.'_new'))
            @unlink($src_file.'_new');
        return $ret;
    }
}
