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

class StBlogCommentsMyCommentsModuleFrontController extends ModuleFrontController
{
	public $nbr_comments;
    
    public $ssl = true;
    
	public function __construct()
	{
		parent::__construct();

		$this->context = Context::getContext();
	}

	public function initContent()
	{
		$this->display_column_left = false;
		$this->display_column_right = false;
        
		parent::initContent();

        $this->assign();
	}
    
    /**
	 * Assign blog comment template
	 */
	public function assign()
	{
		$errors = array();
        $message = '';

		if ($this->context->customer->isLogged())
		{
			if (Tools::isSubmit('submitAvatar'))
			{
				if (Configuration::get('PS_TOKEN_ACTIVATED') == 1 && strcmp(Tools::getToken(), Tools::getValue('token')))
					$errors[] = $this->module->l('Invalid token', 'mycomments');
				if (!count($errors))
				{
					$comment = new StBlogCommentClass();
                    $rs = $comment->uploadAvatar('avatar');
                    if (true === $rs)
                        $message = $this->module->l('Upload avatar successfully', 'mycomments');
					elseif(false === $rs)
                        $errors[] = $this->module->l('Upload avatar failed', 'mycomments');
                    elseif (-1 === $rs)
                        $errors[] = $this->module->l('Due to memory limit restrictions, this image cannot be loaded. Please increase your memory_limit value via your servers configuration settings.', 'mycomments');
                    elseif(-2 === $rs)
                        $errors[] = $this->module->l('Upload image failed,the uploading fold cant be writable.', 'mycomments');
				}
			}
            elseif(Tools::getValue('act') == 'delavatar')
            {
                $comment = new StBlogCommentClass();
                if($comment->deleteImage($comment->getAvatarPathForCreation(false)))
                {
                    $message = $this->module->l('Delete avatar successfully', 'mycomments');
                    Tools::redirect($this->context->link->getModuleLink('stblogcomments','mycomments'));
                }
                else
                    $errors[] = $this->module->l('Delete avatar failed', 'mycomments');
            }
		}
		else
			Tools::redirect('index.php?controller=authentication&back='.urlencode($this->context->link->getModuleLink('stblogcomments', 'mycomments')));
            
        $comment    = new StBlogCommentClass();
            
        $this->nbr_comments = StBlogCommentClass::getByCustomer($this->context->customer->id,null,null,$this->context->language->id,$this->context->shop->id,true); 
              
        $this->pagination($this->nbr_comments, 10);
                        
        $comments = StBlogCommentClass::getByCustomer($this->context->customer->id,(int)$this->p, (int)$this->n,$this->context->language->id,$this->context->shop->id);
        
        $url = $this->context->link->getModuleLink('stblogcomments','mycomments',array(),false,$this->context->language->id,$this->context->shop->id);
        $vars = array();
		$vars_pagination = array('p');

		foreach ($_GET as $k => $value)
		{
			if ($k != 'controller' && $k != 'fc' && $k != 'module')
			{
				if (Configuration::get('PS_REWRITING_SETTINGS') && ($k == 'isolang' || $k == 'id_lang'))
					continue;
				$if_pagination = !in_array($k, $vars_pagination);
				if ($if_pagination)
				{
					if (!is_array($value))
						$vars[urlencode($k)] = $value;
					else
					{
						foreach (explode('&', http_build_query(array($k => $value), '', '&')) as $key => $val)
						{
							$data = explode('=', $val);
							$vars[urldecode($data[0])] = $data[1];
						}
					}
				}
			}
		}

		if (count($vars))
			$requestPage = $url.(((int)Configuration::get('PS_REWRITING_SETTINGS') == 1) ? '?' : '&').http_build_query($vars, '', '&');
		else
			$requestPage = $url;
        
		$avatar  = StBlogCommentClass::getAvatar('large');

        $avatar  = StBlogCommentClass::getAvatar($this->context->customer->id,'large');
		$this->context->smarty->assign(array(
			'avatar'     => $avatar,
			'errors'     => $errors,
            'message'    => $message,
            'comments'    => $comments,
            'requestPage'    => $requestPage,
		));

		$this->setTemplate('mycomments.tpl');
	}
    public function pagination($nbProducts = 10, $per_page=10)
	{
		if (!$this->context)
			$this->context = Context::getContext();

		$this->n = abs((int)(Tools::getValue('n', $per_page)));
		$this->p = abs((int)Tools::getValue('p', 1));
        
		if (!is_numeric(Tools::getValue('p', 1)) || Tools::getValue('p', 1) < 0)
			Tools::redirect(self::$link->getPaginationLink(false, false, $this->n, false, 1, false));

		$current_url = tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']);
		//delete parameter page
		$current_url = preg_replace('/(\?)?(&amp;)?p=\d+/', '$1', $current_url);

		$range = 2; /* how many pages around page selected */

		if ($this->p < 0)
			$this->p = 0;
            
		$pages_nb = ceil($nbProducts / (int)$this->n);
		if ($this->p > $pages_nb && $nbProducts <> 0)
			Tools::redirect(self::$link->getPaginationLink(false, false, $this->n, false, $pages_nb, false));

		$start = (int)($this->p - $range);
		if ($start < 1)
			$start = 1;
		$stop = (int)($this->p + $range);
		if ($stop > $pages_nb)
			$stop = (int)$pages_nb;
		$this->context->smarty->assign('nb_products', $nbProducts);
		$pagination_infos = array(
			'pages_nb' => $pages_nb,
			'p' => $this->p,
			'n' => $this->n,
			'range' => $range,
			'start' => $start,
			'stop' => $stop,
			'current_url' => $current_url
		);
		$this->context->smarty->assign($pagination_infos);
	}
}
