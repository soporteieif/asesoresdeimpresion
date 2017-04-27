<?php
require_once dirname(__FILE__).'../../../classes/StBlogLoader.php';
StBlogLoader::load(array('Class','Category','ImageClass','RssClass'));
class AdminStBlogController extends ModuleAdminController
{
	/**
	 * @var integer Max File Upload size.
	 */
     private $max_file_size = null;
     
     protected $id_current_category;
     
     protected $position_identifier = 'id_st_blog';
     
     protected $gallery_file_name = 'file';
     
    /**
     * @var integer Max Image Upload size.
     */
     private $max_image_size = null;
     
    public function __construct()
	{
	    $this->bootstrap = true;
		$this->is_Blog    = true;
		$this->table      = 'st_blog';
		$this->className  = 'StBlogClass';
		$this->lang       = true;
		$this->addRowAction('edit');
		$this->addRowAction('delete');
        $this->show_form_cancel_button = false;
		$this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'icon' => 'icon-trash','confirm' => $this->l('Delete selected items?')));
        
        if (Shop::isFeatureActive())
            Shop::addTableAssociation($this->table, array('type' => 'shop'));
		parent::__construct();
        
        $this->imageType = 'jpg';
		$this->max_file_size = (int)(Configuration::get('PS_LIMIT_UPLOAD_FILE_VALUE') * 1000000);
		$this->max_image_size = (int)Configuration::get('PS_PRODUCT_PICTURE_MAX_SIZE');

		$this->fields_list = array(
		'id_st_blog' => array(
            'title' => $this->l('ID'), 
            'align' => 'center', 
            'class' => 'fixed-width-xs'
            ),
		'image' => array(
            'title' => $this->l('cover'), 
            'orderby' => false, 
            'callback' => 'displayCover',
            'callback_object' => $this,
            'filter' => false,
            'search' => false,
            'class' => 'fixed-width-xl'
            ),
		'name' => array(
            'title' => $this->l('Title'), 
            'orderby' => true,
            'class' => 'fixed-width-xxl'
            ),
        );
		
        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP)
            $this->fields_list['shopname'] = array(
                'title' => $this->l('Shop'), 
                'align' => 'center', 
                'orderby' => false,
                'filter' => false,
                'search' => false,
                'class' => 'fixed-width-xl',
            );
       else
           $this->fields_list['catename'] = array(
                'title' => $this->l('Category'), 
                'align' => 'center', 
                'orderby' => false,
                'filter' => false,
                'search' => false,
                'class' => 'fixed-width-xxl',
            );
        
        
        $this->fields_list['active'] = array(
            'title' => $this->l('Displayed'), 
            'active' => 'status',
			'align' => 'left',
            'type' => 'bool', 
            'orderby' => false,
            'class' => 'fixed-width-xl',
        );
        
        if (Tools::getValue('reset_filter_category'))
			$this->context->cookie->id_st_category_products_filter = false;
        
        if ($id_category = (int)Tools::getValue('id_category'))
		{
			$this->id_current_category = $id_category;
			$this->context->cookie->id_st_category_products_filter = $id_category;	
		}
		elseif ($id_category = $this->context->cookie->id_st_category_products_filter)
			$this->id_current_category = $id_category;
        if (!$id_category)
		{
			$this->_defaultOrderBy = $this->identifier;
			if ($this->context->cookie->{'stblog'.$this->table.'Orderby'} == 'position')
			{
				unset($this->context->cookie->{'stblog'.$this->table.'Orderby'});
				unset($this->context->cookie->{'stblog'.$this->table.'Orderway'});
			}
		}else
            $this->_orderBy = 'position';
        
        if ($id_category)
            $this->fields_list['position'] = array(
				'title' => $this->l('Position'),
				'filter_key' => 'cb!position',
				'align' => 'center',
				'position' => 'position',
                'class' => 'fixed-width-xl',
			);
			
        
        $this->_join = 'LEFT JOIN '._DB_PREFIX_.'st_blog_shop bs ON a.id_st_blog=bs.id_st_blog && bs.id_shop IN('.implode(',',Shop::getContextListShopID()).')';
        $this->_join .= $id_category ? 'INNER JOIN '._DB_PREFIX_.'st_blog_category_blog cb ON a.id_st_blog = cb.id_st_blog' : '';

        $this->_select = 'bs.id_shop'.($id_category ? ',cb.position' : '');
        $this->_where = $id_category ? ' AND cb.id_st_blog_category = '.(int)$id_category : '';
        
        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP)
        {
           $this->_group = 'GROUP BY a.id_st_blog';
        }
	}
    
    public function displayCover($id)
    {
        $object = new StBlogClass($id);
        $url = $object->getCoverUrl();
        
        // Is gallery?
        if (!$url)
        {
            foreach($object->getGallery($id) AS $gallery)
            {
                $url = $gallery['image'];
                break;
            }
        }
        return '<img src="'.($url ? $url:_MODULE_DIR_.'stblog/views/img/thumb-default.jpg').'">';
    }
    
	public function setMedia()
	{
		parent::setMedia();

		$bo_theme = ((Validate::isLoadedObject($this->context->employee)
			&& $this->context->employee->bo_theme) ? $this->context->employee->bo_theme : 'default');

		if (!file_exists(_PS_BO_ALL_THEMES_DIR_.$bo_theme.DIRECTORY_SEPARATOR
			.'template'))
			$bo_theme = 'default';
        $this->addCss(_PS_MODULE_DIR_.'stblog/views/css/admin.css');
        $this->addJs(_PS_MODULE_DIR_.'stblog/views/js/admin_blog.js');
		$this->addJs(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$bo_theme.'/js/jquery.iframe-transport.js');
		$this->addJs(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$bo_theme.'/js/jquery.fileupload.js');
		$this->addJs(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$bo_theme.'/js/jquery.fileupload-process.js');
		$this->addJs(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$bo_theme.'/js/jquery.fileupload-validate.js');			
		$this->addJs(__PS_BASE_URI__.'js/vendor/spin.js');
		$this->addJs(__PS_BASE_URI__.'js/vendor/ladda.js');
	}

	public function renderList()
	{
		$this->initToolbar();
        
        if ((int)$this->id_current_category)
            self::$currentIndex .= '&id_category='.(int)$this->id_current_category;
        // category tree
        $root_category = StBlogCategory::getTopCategory();

        $this->tpl_list_vars['base_url'] = preg_replace('#&id_category=[0-9]*#', '', self::$currentIndex).'&token='.$this->token;
        $this->context->smarty->assign('table_dnd', 1);
        
        $tree = new HelperTreeCategories('categories-tree', $this->l('Filter by category'));
		$tree->setAttribute('is_category_filter', (bool)$this->id_current_category)
			->setAttribute('base_url', preg_replace('#&id_category=[0-9]*#', '', self::$currentIndex).'&token='.$this->token)
            ->setInputName('id-category')
			->setSelectedCategories(array((int)$this->id_current_category))
            ->setTemplate('tree_filter_categories.tpl')
            ->setHeaderTemplate('tree_filter_header.tpl')
            ->setData(StBlogCategory::getNestedCategories($root_category->id));
		$this->tpl_list_vars['category_tree'] = $tree->render();
        
        return parent::renderList();
	}

	/**
	 * Modifying initial getList method to display position feature (drag and drop)
	 */
	public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
	{
        $nin_multishop = Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP;
        
		parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
        
        foreach($this->_list AS $key => $value)
        {
            if ($nin_multishop)
            {
                $shop = Shop::getShop($value['id_shop']);
                $this->_list[$key]['shopname'] = $shop['name']; 
            } 
            else
                $this->_list[$key]['catename'] = implode(',', StBlogCategory::getCategoryMapInfo($value['id_st_blog']));
            
            $this->_list[$key]['image'] = $value['id_st_blog'];
        }
	}

	public function postProcess()
	{
		$this->tabAccess = Profile::getProfileAccess($this->context->employee->id_profile, $this->id);
		if (Tools::isSubmit('submitAdd'.$this->table) || Tools::isSubmit('submitAdd'.$this->table.'AndStay'))
		{
			$this->action = 'save';
            
            if (empty($_POST['id_parent']))
            {
                $root_category = StBlogCategory::getTopCategory();
                $_POST['id_parent'] = (array)$root_category->id;
                $_POST['id_category_default'] = $root_category->id;
            }
		    
            $_POST['id_st_blog_category_default'] = $_POST['id_category_default'];
            
            $cnf = Tools::getValue('id_st_blog') ? 4 : 3;
            $object = parent::postProcess();
            $cover_posted = true;
            if ($object !== false)
            {
               $this->postProductLink($object);
               $this->postCategories($object);
               $cover_posted = $this->postCover($object);
               $this->postTag($object);
               
               if ($this->display == 'edit')
                    $this->redirect_after  = self::$currentIndex.'&conf='.$cnf.'&update'.$this->table.'&id_st_blog='.(int)$object->id.'&token='.Tools::getValue('token');
               else 
                    $this->redirect_after  = self::$currentIndex.'&conf='.$cnf.'&id_st_blog='.(int)$object->id.'&token='.Tools::getValue('token');
            }
            
            if ($cover_posted === '-1')
            {
                $this->redirect_after = '';
                $this->errors[] = Tools::displayError('No permission to write for folder['._PS_ST_BLOG_IMG_DIR_.'].');
            }
            else
            {
                Tools::clearSmartyCache();
    			Media::clearCache();
                return $object;
            }
		}
		/* Change object statuts (active, inactive) */
		elseif (Tools::isSubmit('statusst_blog') && Tools::getValue($this->identifier))
		{
			if ($this->tabAccess['edit'] === '1')
			{
				if (Validate::isLoadedObject($object = $this->loadObject(true)))
				{
					if ($object->toggleStatus())
					{
						Tools::redirectAdmin(self::$currentIndex.'&conf=5&token='.Tools::getValue('token'));
					}
					else
						$this->errors[] = Tools::displayError('An error occurred while updating the status.');
				}
				else
					$this->errors[] = Tools::displayError('An error occurred while updating the status for an object.')
						.' <b>'.$this->table.'</b> '.Tools::displayError('(cannot load object)');
			}
			else
				$this->errors[] = Tools::displayError('You do not have permission to edit this.');
		}
        elseif(Tools::isSubmit('deletecoverimage'))
        {
            if ($is_st_blog = (int)Tools::getValue('id_st_blog'))
            {
                $id_image = StBlogImageClass::getImageIdByType($is_st_blog);
                $image = new StBlogImageClass('1', implode('',$id_image));     
        		if ($image->setShopList($image->getAssociatedShops())->delete())
                    Tools::redirectAdmin(self::$currentIndex.'&conf=7&updatest_blog&id_st_blog='.$is_st_blog.'&token='.Tools::getValue('token'));
        		else
        			$this->errors[] = Tools::displayError('An error occurred while attempting to delete the cover.');    
            }else
        			$this->errors[] = Tools::displayError('An error occurred while attempting to delete the cover[parameter error].');
            
       }
		/* Delete object */
		elseif (Tools::isSubmit('delete'.$this->table))
		{
			if ($this->tabAccess['delete'] === '1')
			{
				if (Validate::isLoadedObject($object = $this->loadObject(true)))
				{
					// check if request at least one object with noZeroObject
					if (isset($object->noZeroObject) && count($taxes = call_user_func(array($this->className, $object->noZeroObject))) <= 1)
						$this->errors[] = Tools::displayError('You need at least one object.')
							.' <b>'.$this->table.'</b><br />'.Tools::displayError('You cannot delete all of the items.');
					else
					{
                        $identifier = '';
						if ($this->deleted)
						{
							$object->deleted = 1;
							if ($object->update())
                            {
                                Tools::clearSmartyCache();
                                Media::clearCache();
                                Tools::redirectAdmin(self::$currentIndex.'&conf=1&token='.Tools::getValue('token').$identifier);
                            }
						}
						elseif ($object->delete())
							Tools::redirectAdmin(self::$currentIndex.'&conf=1&token='.Tools::getValue('token').$identifier);
						$this->errors[] = Tools::displayError('An error occurred during deletion.');
					}
				}
				else
					$this->errors[] = Tools::displayError('An error occurred while deleting the object.')
						.' <b>'.$this->table.'</b> '.Tools::displayError('(cannot load object)');
			}
			else
				$this->errors[] = Tools::displayError('You do not have permission to delete this.');
		}
		elseif (Tools::isSubmit('position'))
		{
			$object = new StBlogClass((int)Tools::getValue($this->identifier, Tools::getValue('id_st_blog_to_move', 1)));
			if ($this->tabAccess['edit'] !== '1')
				$this->errors[] = Tools::displayError('You do not have permission to edit this.');
			elseif (!Validate::isLoadedObject($object))
				$this->errors[] = Tools::displayError('An error occurred while updating the status for an object.')
					.' <b>'.$this->table.'</b> '.Tools::displayError('(cannot load object)');
			elseif (!$object->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position')))
				$this->errors[] = Tools::displayError('Failed to update the position.');
			else
			{
				$identifier = '';
				$token = Tools::getAdminTokenLite('AdminStBlog');
				Tools::redirectAdmin(
					self::$currentIndex.'&'.$this->table.'Orderby=position&'.$this->table.'Orderway=asc&conf=5'.$identifier.'&token='.$token
				);
			}
		}
		/* Delete multiple objects */
		elseif (Tools::getValue('submitDel'.$this->table) || Tools::getValue('submitBulkdelete'.$this->table))
		{
			if ($this->tabAccess['delete'] === '1')
			{
				if (Tools::isSubmit($this->table.'Box'))
				{
					$blog = new StBlogClass();
					$result = true;
					$result = $blog->deleteSelection(Tools::getValue($this->table.'Box'));
					if ($result)
					{
						$token = Tools::getAdminTokenLite('AdminStBlog');
						Tools::redirectAdmin(self::$currentIndex.'&conf=2&token='.$token);
					}
					$this->errors[] = Tools::displayError('An error occurred while deleting this selection.');

				}
				else
					$this->errors[] = Tools::displayError('You must select at least one element to delete.');
			}
			else
				$this->errors[] = Tools::displayError('You do not have permission to delete this.');
		}
		parent::postProcess();
	}
    
    public function postCover($obj)
    {
        if (isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name']))
        {
            $old_images = StBlogImageClass::getImageIdByType($obj->id);
            foreach($old_images AS $old)
            {
                $img = new StBlogImageClass('1', $old);
                $img->setShopList($img->getAssociatedShops())->delete();
            }
            $image = new StBlogImageClass('1');
            return $image->setShopList($obj->getAssociatedShops())->save('image');    
        }
        return true;
    }
    
    public function postCategories($obj)
    {
        if ($_POST['id_parent'])
            return $obj->saveCategoryMap($_POST['id_parent']);
    }

	public function renderForm()
	{
		$this->display = 'edit';
		$this->initToolbar();
		if (!$this->loadObject(true))
			return;
        
        $this->initFormToolBar();
       
        $id_blog = Tools::getValue('id_st_blog', 0);

        $selected_cat  = StBlogCategory::getCategoryMapIds($this->object->id);
		$root_category = StBlogCategory::getTopCategory();
        $default_category_array = StBlogCategory::getCategoryMapArray($selected_cat);
        $default_category = $this->object->getDefaultCategory();
        if (empty($selected_cat))
            $selected_cat = (array)$root_category->id;
        
        if (empty($default_category_array))
            $default_category_array[] = array('id_st_blog_category' => $root_category->id, 'name' => $root_category->name);
        
        $cover = false;    
        if ($this->object->id)
        {
            $cover = $this->object->getCoverUrl('small');
            if ($cover && file_exists(_PS_ROOT_DIR_.$cover))
            {
                $cover = '<img src="'.$cover.'" /><p><a class="btn btn-default st_blog_cover_image" href="javascript:;" data-id="'.(int)$this->object->id.'" data-token="'.Tools::getValue('token').'"><i class="icon-trash"></i> Delete</a></p>';
            }
            else
                $cover = false;
            $this->object->date_add = date('Y-m-d', strtotime($this->object->date_add));
        }
        $tree = new HelperTreeCategories('categories-tree');
		$tree->setTemplate('tree_associated_categories.tpl')
			->setHeaderTemplate('tree_associated_header.tpl')
            ->setSelectedCategories($selected_cat)
            ->setUseCheckBox(true)
            ->setInputName('id_parent')
            ->setData(StBlogCategory::getNestedCategories($root_category->id));
        
		$this->fields_form[0]['form'] = array(
            'tinymce' => true,        
			'legend' => array(
				'title' => $this->l('Blog Content'),
                'icon' => 'icon-tags'
			),
            
			'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Title:'),
					'name' => 'name',
                    'class' => 'copy2friendlyUrl',
                    'lang' => true,
                    'size' => 40,
                    'required' => true
				),
                array(
					'type' => 'radio',
					'label' => $this->l('Format:'),
					'name' => 'type',
					'required' => false,
					'class' => 't',
                    'default_value' => 1,
					'values' => array(
						array(
							'id' => 'id_standard',
							'value' => 1,
							'label' => $this->l('Standard')
						),
						array(
							'id' => 'id_gallery',
							'value' => 2,
							'label' => $this->l('Gallery')
						),
						array(
							'id' => 'id_video',
							'value' => 3,
							'label' => $this->l('Video')
						)
					),
				),
                array(
					'type' => 'blog_cateogroes',
                    'name' => 'blog_cateogroes',
					'label' => $this->l('Select category:'),
					'html' => $tree->render()
				),
                array(
					'type' => 'select',
					'label' => $this->l('Default category'),
					'name' => 'id_category_default',
                    'default_value' => $default_category,
					'options' => array(
						'query' => $default_category_array,
						'id' => 'id_st_blog_category',
						'name' => 'name'
					)
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Short content:'),
					'name' => 'content_short',
					'lang' => true,
					'cols' => 127,
                    'rows' => 10
				),
                array(
					'type' => 'textarea',
					'label' => $this->l('Content:'),
					'name' => 'content',
                    'autoload_rte' => true,
					'lang' => true,
				),
                array(
					'type' => 'file',
					'label' => $this->l('Cover image:'),
					'name' => 'image',
                    'image' => $cover,
					'desc' => $this->l('Upload a blog cover from your computer(width>870px, height>348px).')
				),
                array(
					'type' => 'textarea',
					'label' => $this->l('Video embed code:'),
					'name' => 'video',
                    'lang' => true,
					'cols' => 60,
                    'rows' => 6,
				),
                array(
					'type' => 'text',
					'label' => $this->l('Tags:'),
					'name' => 'tags',
                    'default_value' =>$this->object->getBlogTagsAll(),
                    'lang' => true,
                    'size' => 40,
					'desc' => $this->l('Tags separated by commas (e.g. dvd, dvd player, hifi).')
                ),
                array(
					'type' => 'switch',
					'label' => $this->l('Accept comment:'),
					'name' => 'accept_comment',
					'default_value' => 1,
					'class' => 't',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'accept_comment_on',
							'value' => 1,
							'label' => $this->l('Yes')
						),
						array(
							'id' => 'accept_comment_off',
							'value' => 0,
							'label' => $this->l('No')
						)
					),
                    'desc' => $this->l('Whether or not accept comments.')
				),
                array(
					'type' => 'text',
					'label' => $this->l('Author:'),
					'name' => 'author',
					'lang' => true,
                    'size' => 40,
					'hint' => $this->l('Invalid characters:').' <>;=#{}'
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Displayed:'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
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
                	'type' => 'date',
                	'label' => $this->l('Created on:'),
                	'name' => 'date_add',
                	'default_value' => date('Y-m-d'),
                	'size' => 9
                ),
                array(
					'type' => 'html',
                    'id' => 'a_cancel',
					'label' => '',
					'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.self::$currentIndex.'&token='.Tools::getValue('token').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
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
        
        $this->fields_form[1]['form'] = array(
			'legend' => array(
				'title' => $this->l('SEO')
			),
        				
			'input' => array(	
				array(
					'type' => 'text',
					'label' => $this->l('Meta title:'),
					'name' => 'meta_title',
					'lang' => true,
                    'size' => 40,
					'hint' => $this->l('Invalid characters:').' <>;=#{}'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Meta keywords:'),
					'name' => 'meta_keywords',
					'lang' => true,
                    'size' => 40,
					'hint' => $this->l('Invalid characters:').' <>;=#{}'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Meta description:'),
					'name' => 'meta_description',
                    'lang' => true,
                    'size' => 40,
					'hint' => $this->l('Invalid characters:').' <>;=#{}'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Friendly URL:'),
					'name' => 'link_rewrite',
                    'size' => 40,
					'lang' => true,
					'hint' => $this->l('Only letters and the minus (-) character are allowed.')
				),
                array(
					'type' => 'html',
                    'id' => 'a_cancel',
					'label' => '',
					'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.self::$currentIndex.'&token='.Tools::getValue('token').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
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
        
        // Products Link
        $products = $this->object->getLinkProducts(true);
        if ($products)
            foreach($products AS &$product)
                $product['name'] = $product['id_product'].' - '.$product['name'];
        $this->fields_form[2]['form'] = array(
			'legend' => array(
				'title' => $this->l('Related products'),
                'icon' => 'icon-cogs'
			),		
			'selector' => array(
				'products' => $products,
			    'products_unselected' => array(),//$this->object->getLinkProducts(false)
			),
            'input' => array(
                array(
					'type' => 'html',
                    'id' => 'a_cancel',
					'label' => '',
					'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.self::$currentIndex.'&token='.Tools::getValue('token').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
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
        
        if (Shop::isFeatureActive())
            $this->fields_form[3]['form'] = array(
			'legend' => array(
				'title' => $this->l('Select store'),
                'icon' => 'icon-cogs'
			),		
			'input' => array(	
				array(
					'type' => 'shop',
    				'label' => $this->l('Shop association:'),
    				'name' => 'checkBoxShopAsso',
				),
			
            )
        );
        
        // Gallery Image
        $gallery = $this->object->getGallery($id_blog);
        
        $shops = false;
		if (Shop::isFeatureActive())
			$shops = Shop::getShops();

		if ($shops)
			foreach ($shops as $key => $shop)
				if (!$this->object->isAssociatedToShop($shop['id_shop']))
					unset($shops[$key]);

		if ($this->context->shop->getContext() == Shop::CONTEXT_SHOP)
			$current_shop_id = (int)$this->context->shop->id;
		else
			$current_shop_id = 0;
            
        $languages = Language::getLanguages(true);
		$image_uploader = new HelperImageUploader($this->gallery_file_name);
		$image_uploader->setMultiple(true)
			->setUseAjax(true)->setUrl(
			Context::getContext()->link->getAdminLink('AdminStBlog').'&ajax=1&id_st_blog='.(int)$this->object->id
			.'&action=addImage');	
          
        $this->fields_form[4]['form'] = array(
			'legend' => array(
				'title' => $this->l('Gallery Image'),
                'icon' => 'icon-cogs'
			),	
			'gallary' => array(
                'images' => array(
                    'count' => count($gallery),
                    'max_image_size' => $this->max_image_size / 1024 / 1024,
                    'shops' => $shops,
                    'token' =>  $this->token,
				    'table' => $this->table,
                    'currency' => $this->context->currency,
				    'current_shop_id' => $current_shop_id,
                    'images' => $gallery,
                    'id_st_blog' => $id_blog,
                    'object' => $this->object,
                    'image_uploader' => $image_uploader->render()
                )
			)
        );
        
        
        $this->multiple_fieldsets = true;
		$this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
        $this->tpl_form_vars['lang_id'] = $this->context->language->id;
        $this->tpl_form_vars['ps_force_friendly_blog'] = true;
        $this->tpl_form_vars['default_form_language'] = $this->default_form_language;

        $this->addJqueryUI(array(
				'ui.core',
				'ui.widget'
			));

		$this->addjQueryPlugin(array(
			'autocomplete',
			'tablednd',
			'thickbox',
			'ajaxfileupload',
			'date',
			'tagify'
		));

		$this->addJS(array(
			_PS_JS_DIR_.'tiny_mce/tiny_mce.js',
			_PS_JS_DIR_.'tinymce.inc.js',
			_PS_JS_DIR_.'admin-dnd.js',
			_PS_JS_DIR_.'jquery/ui/jquery.ui.progressbar.min.js',
		));
        $this->addJqueryPlugin(array('autocomplete', 'fancybox', 'typewatch'));
        // TinyMCE
		$iso_tiny_mce = $this->context->language->iso_code;
		$iso_tiny_mce = (file_exists(_PS_JS_DIR_.'tiny_mce/langs/'.$iso_tiny_mce.'.js') ? $iso_tiny_mce : 'en');
        $this->context->smarty->assign(array(
            'ad'                => dirname($_SERVER['PHP_SELF']),
            'iso_tiny_mce'      => $iso_tiny_mce,
            'tinymce'           => true,
            'object'            => $this->object
        ));

		return parent::renderForm();
	}
    
   	public function ajaxProcessDeleteGalleryImage()
	{
		$this->display = 'content';
		
        if (!($id_image = (int)Tools::getValue('id_image')))
            $this->jsonError(Tools::displayError('An error occurred (the image not exists).'));
        
        $image = new StBlogImageClass('2', $id_image);
        
        if (!$image->id)
            $this->jsonError(Tools::displayError('An error occurred (unkown object).'));
        
		if ($image->setShopList($image->getAssociatedShops())->delete())
        {
            $image->refreshPositions();
            die(Tools::jsonEncode(array('success'=>Tools::displayError('The picture was deleted successful.'),'id'=>$id_image)));
        }
		else
			$this->jsonError(Tools::displayError('An error occurred while attempting to delete the image.'));
	}
    
    public function ajaxProcessUpdateImagePosition()
	{
		$res = false;
		if ($json = Tools::getValue('json'))
		{
			$res = true;
			$json = stripslashes($json);
			$images = Tools::jsonDecode($json, true);
			foreach ($images as $id => $position)
			{
				$img = new Image((int)$id);
				$img->position = (int)$position;
				$res &= Db::getInstance()->execute('
                UPDATE '._DB_PREFIX_.'st_blog_image
                SET position = '.$position.'
                WHERE id_st_blog_image = '.$id.'
                ');
			}
		}
		if ($res)
			$this->jsonConfirmation($this->_conf[25]);
		else
			$this->jsonError(Tools::displayError('An error occurred while attempting to move this picture.'));
	}


    public function ajaxProcessUpdateGalleryImageShopAsso()
	{
	    $images = null;
		if (($id_image = Tools::getValue('id_image')) && ($id_shop = (int)Tools::getValue('id_shop')))
		$images = Db::getInstance()->executeS('
        SELECT * FROM '._DB_PREFIX_.'st_blog_image
        WHERE id_st_blog_image = '.$id_image.'
        ');	
        
        if (empty($images))
             $this->jsonError(Tools::displayError('An error occurred while attempting to associate this image with your shop(Image not Found). '));
        
             
        $exist = Db::getInstance()->getValue('
        SELECT COUNT(0) FROM '._DB_PREFIX_.'st_blog_image_shop
        WHERE id_st_blog_image = '.$id_image.'
        AND id_shop = '.$id_shop.'
        ');
        
        if ($exist)
            $res = $exist = Db::getInstance()->execute('
            DELETE FROM '._DB_PREFIX_.'st_blog_image_shop
            WHERE id_st_blog_image = '.$id_image.'
            AND id_shop = '.$id_shop.'
            ');
        else
            $res = $exist = Db::getInstance()->execute('
            INSERT INTO '._DB_PREFIX_.'st_blog_image_shop
            VALUES('.(int)$id_image.', '.(int)$id_shop.')
            ');

		if ($res)
			$this->jsonConfirmation($this->_conf[27]);
		else
			$this->jsonError(Tools::displayError('An error occurred while attempting to associate this image with your shop. '));
	}

    
    public function postTag($obj)
    {
        $tags = array();
        foreach($_POST as $k => $v)
        {
            if (strpos($k,'tags_') !== false && !empty($v))
            {
                $key = str_replace('tags_','',$k);
                $tags[$key] = $v;
            }
        }
        $obj->saveTag($tags);
    }
    
    public function postProductLink($obj)
    {
        if (!isset($_POST['products']))
            $_POST['products'] = array();
        $obj->saveProductLink($_POST['products']);
    }
    
    public function initFormToolBar()
    {
        unset($this->toolbar_btn['back']);    
        $this->toolbar_btn['save-and-stay'] = array(
						'short' => 'SaveAndStay',
						'href' => '#',
						'desc' => $this->l('Save and stay'),
					);
        $this->toolbar_btn['back'] = array(
						'href' => self::$currentIndex.'&token='.Tools::getValue('token'),
						'desc' => $this->l('Back to list'),
					);
    }
    
    public function ajaxProcessGetCategoryChildren()
    {
    	$children_categories = StBLogCategory::getChildrenWithNbSelectedSubCat(Tools::getValue('id_category_parent'), Tools::getValue('selectedCat'), Context::getContext()->language->id, Tools::getValue('use_shop_context'));
    	die(Tools::jsonEncode($children_categories));
    }
    
    public function ajaxProcessUpdatePositions()
	{
		if ($this->tabAccess['edit'] === '1')
		{
			$way = (int)(Tools::getValue('way'));
			$id_st_blog = (int)(Tools::getValue('id'));
			$positions = Tools::getValue('st_blog');
            $_POST['id_category'] = $this->id_current_category;

			if (is_array($positions))
				foreach ($positions as $position => $value)
				{
					$pos = explode('_', $value);

					if ((isset($pos[1]) && isset($pos[2])) && ((int)$pos[2] === $id_st_blog))
					{
						if ($blog = new StBlogClass((int)$pos[2]))
							if (isset($position) && $blog->updatePosition($way, $position))
							{
								echo 'ok position '.(int)$position.' for blog '.(int)$pos[2]."\r\n";							
							}
							else
								echo '{"hasError" : true, "errors" : "Can not update blog '.(int)$id_st_blog.' to position '.(int)$position.' "}';
						else
							echo '{"hasError" : true, "errors" : "This blog ('.(int)$id_st_blog.') can t be loaded"}';

						break;
					}
				}
		}
	}
    
   	public function initPageHeaderToolbar()
	{
		if (empty($this->display))
			$this->page_header_toolbar_btn['new_blog'] = array(
					'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
					'desc' => $this->l('Add new blog'),
					'icon' => 'process-icon-new'
				);
		if ($this->display == 'edit' || $this->display == 'add')
        {
             $this->page_header_toolbar_btn['cancel'] = array(
				'href' => self::$currentIndex.'&token='.$this->token,
				'desc' => $this->l('Cancel')
		     );
        }
		parent::initPageHeaderToolbar();
	}
    
    public function ajaxProcessAddImage()
	{
		if (!Validate::isLoadedObject($object = $this->loadObject()))
		{
			$files = array();
			$files[0]['error'] = Tools::displayError('Cannot add image because blog creation failed.');
		}
        
        $files = array();
        $f = $this->gallery_file_name ? $this->gallery_file_name : 'file';
        if (isset($_FILES[$f]) && count($_FILES[$f]))
        {
            foreach($_FILES[$f] AS $key => $value)
            {
                $files[$key] = $value[0];
                $_FILES[$f][$key] = $value[0];
            }
        }
		if ($files)
            $files = array($files);

		foreach ($files as &$file)
		{
		    $oImg = new StBlogImageClass('2');
            $oImg->setShopList($object->getAssociatedShops());
            $ret =  $oImg->save($f);
            
            if ($ret === '-1')
                $files[0]['error'] = Tools::displayError('Cannot add image because blog creation failed.');
            $url = $oImg->getImageUrl('thumb');
            if (!$url)
                $files[0]['error'] = Tools::displayError('Cannot add image because blog creation failed.');
			
            if (!isset($files[0]['error']) || !$files[0]['error'])
            {
                $shops = $object->getAssociatedShops();
        		$json_shops = array();
        		foreach ($shops as $id_shop)
        			$json_shops[$id_shop] = true;
                
    			$file['status']   = 'ok';
    			$file['id']       = $oImg->id;
    			$file['position'] = $oImg->getHighestPosition();
    			$file['cover']    = 0;
    			$file['path']     = $url;
    			$file['shops']    = $json_shops;    
            }
		}

		die(Tools::jsonEncode(array($f => $files)));
	}
    
    public function ajaxProcessDeleteCoverImage()
	{
	    $result = array(
            'r' => false,
            'm' => '',
            'd' => ''
        );
		$image = StBlogImageClass::getCoverImage((int)Tools::getValue('id_st_blog'), Context::getContext()->language->id);
        if ($image)
        {
            $image = new StBlogImageClass('1', (int)$image['id_st_blog_image']);
    		if ($image->setShopList($image->getAssociatedShops())->delete())
            {
                $result['r'] = true;
                $result['m'] = Tools::displayError('The cover was Deleted successful.');
            }
    		else
    			$result['m'] = Tools::displayError('An error occurred while attempting to delete the cover.');
        }
        echo Tools::jsonEncode($result);
	}
}
