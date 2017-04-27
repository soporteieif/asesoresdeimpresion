<?php
class AdminStBlogCommentController extends ModuleAdminController
{
    
	public function __construct()
	{
		$this->is_Blog = true;
        $this->bootstrap = true;
		$this->table = 'st_blog_comment';
		$this->className = 'StBlogCommentClass';
		$this->addRowAction('edit');
        $this->addRowAction('view');
		$this->addRowAction('delete');
        $this->show_form_cancel_button = false;
		$this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));

        parent::__construct();
        
        if (!Module::isEnabled('stblogcomments'))
            $this->errors[] = $this->l('ST Blog Module Comments was disabled.');
        
		$this->fields_list = array(
            'id_st_blog_comment' => array(
                'title' => $this->l('ID'), 
                'align' => 'center', 
                'class' => 'fixed-width-xs',
                ),
            'customer_name' => array(
                'title' => $this->l('Customer Name'), 
                'class' => 'fixed-width-lg',
                ),
            'customer_email' => array(
                'title' => $this->l('Customer Email'), 
                'class' => 'fixed-width-lg',
                ),
            'content' => array(
                'title' => $this->l('Content'), 
                'orderby' => false
                ),
            'reply' => array(
                'title' => $this->l('Reply'), 
                'class' => 'fixed-width-xs',
                'callback' => 'displayReply',
                'callback_object' => $this,
                'filter' => false,
                'orderby' => false,
                'search' => false,
                ),
            'active' => array(
                'title' => $this->l('Accept'), 
                'class' => 'fixed-width-lg',
                'active' => 'status', 
                'align' => 'center',
                'type' => 'bool', 
                'orderby' => false
                ),
            'date_add' => array(
                'title' => $this->l('Date Add'), 
                'class' => 'fixed-width-md',
                'orderby' => false
                )
        );
        
        
        if ($id_st_blog = Tools::getValue('id_st_blog'))
            $this->_filter .= 'AND `id_st_blog` = '. $id_st_blog;
        $this->_where = ' AND id_parent = ' . Tools::getValue('id_st_blog_comment', 0);     
        $this->_where .= ' AND id_shop IN ('.implode(',', Shop::getContextListShopID()).')';

	}
    
   	public function initPageHeaderToolbar()
	{
		if ($this->display == 'edit')
        {
            $object = $this->loadObject();
			$back = self::$currentIndex.($object->id_parent?'&id_st_blog_comment='.$object->id_parent:'').'&token='.$this->token;
			$this->toolbar_btn['back'] = array(
				'href' => $back,
				'desc' => $this->l('Back to list')
			);
            $this->page_header_toolbar_btn['cancel'] = array(
				'href' => $back,
				'desc' => $this->l('Cancel')
            );
        }
        if (empty($this->display) && $id_st_blog_comment = Tools::getValue('id_st_blog_comment'))
        {
            $comment = new StBlogCommentClass($id_st_blog_comment);
            $this->page_header_toolbar_btn['cacel'] = array(
				'href' => self::$currentIndex.''.($comment->id_parent?'&id_st_blog_comment='.$comment->id_parent:'').'&token='.$this->token,
				'desc' => $this->l('Back to list'),
				'icon' => 'process-icon-back'
			);
        }
		parent::initPageHeaderToolbar();
	}
    public function initToolbar()
    {
        if (Tools::isSubmit('id_st_blog_comment'))
		{
			if (Validate::isLoadedObject($object = $this->loadObject(true)))
            {
                $back = self::$currentIndex.($object->id_parent?'&id_st_blog_comment='.$object->id_parent:'').'&token='.$this->token;
    			$this->toolbar_btn['back'] = array(
    				'href' => $back,
    				'desc' => $this->l('Back to list')
    			);    
            }
		}
    }

	/**
	 * Modifying initial getList method to display position feature (drag and drop)
	 */
	public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
	{
		if ($order_by && $this->context->cookie->__get($this->table.'Orderby'))
			$order_by = $this->context->cookie->__get($this->table.'Orderby');
		else
			$order_by = 'date_add';
        
        if($order_way==null)
            $order_way = 'desc';

		parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
        foreach($this->_list AS &$row)
            $row['reply'] = $row['id_st_blog_comment'];
	}
    
    public function initContent()
    {
        if ($this->display == 'view')
            $this->display = null;
        return parent::initContent();
    }

	public function postProcess()
	{
		$this->tabAccess = Profile::getProfileAccess($this->context->employee->id_profile, $this->id);
		if (Tools::isSubmit('submitAdd'.$this->table))
		{
			$this->action = 'save';

            $object = parent::postProcess();
            if ($object !== false)
                Tools::redirectAdmin(self::$currentIndex.'&conf=4&id_st_blog_comment='.(int)$object->id_parent.'&token='.Tools::getValue('token'));
            return $object;
		}
		/* Change object statuts (active, inactive) */
		elseif (Tools::isSubmit('status'.$this->table) && Tools::getValue($this->identifier))
		{
			if ($this->tabAccess['edit'] === '1')
			{
				if (Validate::isLoadedObject($object = $this->loadObject()))
				{
					if ($object->toggleStatus())
					{
						$identifier = ((int)$object->id_parent ? '&id_st_blog_comment='.(int)$object->id_parent : '');
						Tools::redirectAdmin(self::$currentIndex.'&conf=5'.$identifier.'&token='.Tools::getValue('token'));
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
		/* Delete object */
		elseif (Tools::isSubmit('delete'.$this->table))
		{
			if ($this->tabAccess['delete'] === '1')
			{
				if (Validate::isLoadedObject($object = $this->loadObject()))
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
								Tools::redirectAdmin(self::$currentIndex.'&conf=1&token='.Tools::getValue('token').$identifier);
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
		
		/* Delete multiple objects */
		elseif (Tools::getValue('submitDel'.$this->table) || Tools::getValue('submitBulkdelete'.$this->table))
		{
			if ($this->tabAccess['delete'] === '1')
			{
				if (Tools::isSubmit($this->table.'Box'))
				{
					$blog_comment = new StBlogCommentClass();
					$result = true;
					$result = $blog_comment->deleteSelection(Tools::getValue($this->table.'Box'));
					if ($result)
					{
						$token = Tools::getAdminTokenLite('AdminStBlogComment');
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

	public function renderForm()
	{
		$this->display = 'edit';
		$this->initToolbar();
		if (!$this->loadObject(true))
			return;

		$this->fields_form = array(
			'legend' => array(
				'title' => $this->l('Blog Category'),
				'icon' => 'icon-tags',
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Customer Name:'),
					'name' => 'customer_name',
					'required' => true,
					'class' => 'copy2friendlyUrl',
					'hint' => $this->l('Invalid characters:').' <>;=#{}'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Customer Email:'),
					'name' => 'customer_email',
					'hint' => $this->l('Invalid characters:').' <>;=#{}'
				),
                array(
					'type' => 'textarea',
                    'id'   => 'cm_content',
					'label' => $this->l('Content:'),
					'name' => 'content',
                    'required' => true,
					'rows' => 8,
					'cols' => 60,
					'hint' => $this->l('Invalid characters:').' <>;=#{}'
			 ),
             array(
					'type' => 'switch',
					'label' => $this->l('Accept:'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
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
			),
            array(
				'type' => 'html',
                'id' => 'a_cancel',
				'label' => '',
				'name' => '<a class="btn btn-default btn-block fixed-width-md" href="'.self::$currentIndex.'&id_st_blog_comment='.(int)$this->object->id_parent.'&token='.Tools::getValue('token').'"><i class="icon-arrow-left"></i> Back to list</a>',                  
			),
		),
			'submit' => array(
				'title' => $this->l('Save'),
			)
		);
		$this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
		return parent::renderForm();
	}
    public function displayReply($id)
    {
        $count = array();
        StBlogCommentClass::countChild($count, $id);
        if ($count['all'] > 0)
            $reply = '<span title="'.$this->l('Accepted:'.$count['accept']).'">'.$count['accept'].'</span>/<span title="'.$this->l('Reply total:'.$count['all']).'">'.$count['all'].'</span>';
        else
            $reply = '0';
        return $reply;
    }
}
