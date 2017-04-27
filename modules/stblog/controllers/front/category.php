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

include_once(dirname(__FILE__).'/../../classes/controller/FrontController.php');

class StblogCategoryModuleFrontController extends StblogModuleFrontController
{
	protected $category;
	public $nbr_blogs;
        
    public function init()
	{
        $id_st_blog_category = (int)Tools::getValue('blog_id_category');
        
        if (!$id_st_blog_category || !Validate::isUnsignedId($id_st_blog_category))
			Tools::redirect('index.php?controller=404');
            
		$this->category = new StBlogCategory($id_st_blog_category, $this->context->language->id);
        
		parent::init();
        
        if (!Validate::isLoadedObject($this->category) || !$this->category->active || !$this->category->isAssociatedToShop())
		{
			header('HTTP/1.1 404 Not Found');
			header('Status: 404 Not Found');
		}
    }
	public function initContent()
	{
		parent::initContent();
        
        if(!$this->errors)
		{
    		$this->nbr_blogs = $this->category->getBlogs(null, null, null, null, null, true); 
              
            $per_page = Configuration::get('ST_BLOG_PER_PAGE') ? Configuration::get('ST_BLOG_PER_PAGE') : 10;
            
            $this->blog_pagination($this->nbr_blogs, $per_page);
            
    		$blogs = $this->category->getBlogs($this->context->language->id, (int)$this->p, (int)$this->n);
            
            $url = $this->context->link->getModuleLink('stblog','category',array('blog_id_category'=>$this->category->id,'rewrite'=>$this->category->link_rewrite),false,$this->context->language->id,$this->context->shop->id);
            $vars = array();
    		$vars_pagination = array('p');
    
    		foreach ($_GET as $k => $value)
    		{
    			if ($k != 'blog_id_category' && $k != 'controller' && $k != 'fc' && $k != 'module')
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
            /*
            if($blogs)
                foreach($comments as &$v)
                    $v['images'] = ProductCommentImage::getByComment($v['id_product_comment']);            
            */
            //var_dump($blogs);die;
            $this->context->smarty->assign(array(
    			'category' => $this->category,
                'blogs' => $blogs,
                'nbr_blogs' => $this->nbr_blogs,
                'blog_id_category' => $this->category->id,
                'category_layouts' => Configuration::get('ST_BLOG_CATE_LAYOUTS'),
			    'path' => $this->getPath($this->category->id),
                'imageSize' => StBlogImageClass::$imageTypeDef,
                'display_viewcount' => Configuration::get('ST_BLOG_DISPLAY_VIEWCOUNT'),
                'requestPage' => $requestPage,
            ));
        }
		$this->context->smarty->assign('errors', $this->errors);
		$this->setTemplate('category.tpl');
	}
    
    public function blog_pagination($nbBlogs = 10, $per_page=10)
	{
		if (!$this->context)
			$this->context = Context::getContext();

		$this->n = abs((int)(Tools::getValue('n', ((isset($this->context->cookie->blog_nb_item_per_page) && $this->context->cookie->blog_nb_item_per_page >= $per_page) ? $this->context->cookie->blog_nb_item_per_page : $per_page))));
		$this->p = abs((int)Tools::getValue('p', 1));
        
		if (!is_numeric(Tools::getValue('p', 1)) || Tools::getValue('p', 1) < 0)
			Tools::redirect(self::$link->getPaginationLink(false, false, $this->n, false, 1, false));

		$current_url = tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']);
		//delete parameter page
		$current_url = preg_replace('/(\?)?(&amp;)?p=\d+/', '$1', $current_url);

		$range = 2; /* how many pages around page selected */

		if ($this->p < 0)
			$this->p = 0;

		if (isset($this->context->cookie->blog_nb_item_per_page) && $this->n != $this->context->cookie->blog_nb_item_per_page && in_array($this->n, $nArray))
			$this->context->cookie->blog_nb_item_per_page = $this->n;

		$pages_nb = ceil($nbBlogs / (int)$this->n);
		if ($this->p > $pages_nb && $nbBlogs <> 0)
			Tools::redirect(self::$link->getPaginationLink(false, false, $this->n, false, $pages_nb, false));

		$start = (int)($this->p - $range);
		if ($start < 1)
			$start = 1;
		$stop = (int)($this->p + $range);
		if ($stop > $pages_nb)
			$stop = (int)$pages_nb;
		$this->context->smarty->assign('nb_blogs', $nbBlogs);
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