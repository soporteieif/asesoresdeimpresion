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
include_once(dirname(__FILE__).'/../../stblogcomments.php');
include_once(dirname(__FILE__).'/../../classes/StBlogCommentClass.php');

class StBlogCommentsDefaultModuleFrontController extends ModuleFrontController
{
	public function __construct()
	{
		parent::__construct();

		$this->context = Context::getContext();
	}

	public function initContent()
	{
		parent::initContent();

		if (Tools::isSubmit('action'))
		{
			switch(Tools::getValue('action'))
			{
				case 'add_comment':
					$this->ajaxProcessAddComment();
					break;
			}
		}
	}

	protected function ajaxProcessAddComment()
	{
		$module_instance = new StBlogComments();

		$result = array(
            'r' => false,
            'm' => '',
        );
		$id_guest = 0;
		$id_customer = $this->context->customer->id;
		if (!$id_customer)
			$id_guest = $this->context->cookie->id_guest;

		$errors = array();
		// Validation
		if (!Validate::isInt(Tools::getValue('id_blog')))
			$errors[] = $module_instance->l('ID blog is incorrect', 'default');
		if (!Tools::getValue('content') || !Validate::isMessage(Tools::getValue('content')))
			$errors[] = $module_instance->l('Comment is incorrect', 'default');
		if (!$id_customer && (!Tools::isSubmit('customer_name') || !Tools::getValue('customer_name') || !Validate::isGenericName(Tools::getValue('customer_name'))))
			$errors[] = $module_instance->l('Customer name is incorrect', 'default');
		if (!$id_customer && Tools::getValue('customer_email') && !Validate::isEmail(Tools::getValue('customer_email')))
			$errors[] = $module_instance->l('Customer email is incorrect', 'default');
		if (!$this->context->customer->id && !Configuration::get('ST_BLOG_C_ALLOW_GUESTS'))
			$errors[] = $module_instance->l('You must be logged in order to send a comment', 'default');

		$blog = new StBlogClass(Tools::getValue('id_blog'));
		if (!$blog->id)
			$errors[] = $module_instance->l('Blog not found', 'default');

		if (!count($errors))
		{
			$customer_comment = StBlogCommentClass::getByCustomer((int)(Tools::getValue('id_blog','0')), $id_customer, true, (int)$id_guest, $this->context->shop->id);
			if (!$customer_comment || ($customer_comment && (strtotime($customer_comment['date_add']) + Configuration::get('ST_BLOG_C_MINIMAL_TIME')) < time()))
			{

				$comment = new StBlogCommentClass();
				$comment->content = strip_tags(Tools::getValue('content'));
				$comment->id_st_blog = (int)Tools::getValue('id_blog');
				$comment->id_parent = (int)Tools::getValue('id_parent');
				$comment->id_customer = (int)$id_customer;
				$comment->id_guest = $id_guest;
				$comment->customer_name = Tools::getValue('customer_name');
				if (!$comment->customer_name)
					$comment->customer_name = pSQL($this->context->customer->firstname.' '.$this->context->customer->lastname);
				$comment->customer_email = Tools::getValue('customer_email');
				if (!$comment->customer_email)
					$comment->customer_email = $this->context->customer->email;
				$comment->id_shop = $this->context->shop->id;;
				$comment->active = 0;
				$comment->save();
                
                $result['r'] = true;
			}
			else
			{
                $result['m'] = $module_instance->l('You should wait', 'default').' '.Configuration::get('ST_BLOG_C_MINIMAL_TIME').' '.$module_instance->l('seconds before posting a new comment', 'default');
			}
		}
		else
            $result['m'] = implode('<br/>',$errors);

		die(Tools::jsonEncode($result));
	}
}
