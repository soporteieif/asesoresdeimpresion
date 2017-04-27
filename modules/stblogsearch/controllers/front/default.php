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

require_once _PS_MODULE_DIR_.'/stblogsearch/classes/StBlogSearchClass.php';
require_once _PS_MODULE_DIR_.'/stblog/classes/StBlogLoader.php';
StBlogLoader::load(array('class','ImageClass'));
include_once(dirname(__FILE__).'/../../stblogsearch.php');
class StblogSearchDefaultModuleFrontController extends ModuleFrontController
{
	public $nbr_blogs;
        
	public function initContent()
	{
		parent::initContent();
        
        $this->display_column_left = false;
        $this->display_column_right = false;

        if(Configuration::get('ST_BLOG_COLUMN_CATEGORY')==1)
            $this->display_column_left = true;
        elseif(Configuration::get('ST_BLOG_COLUMN_CATEGORY')==2)
            $this->display_column_right = true;
        elseif(Configuration::get('ST_BLOG_COLUMN_CATEGORY')==3)
        {
            $this->display_column_left = true;
            $this->display_column_right = true;
        }
        
        $query = Tools::replaceAccentedChars(urldecode(Tools::getValue('stb_search_query')));
        $original_query = Tools::getValue('stb_search_query');
        
        if(!$this->errors)
		{
		    $search = new StBlogSearchClass();
            $id_array = $search->prepareSearch($query);
            $this->nbr_blogs = $search->getBlogs($id_array, null, null, null, null, null, true);
            $per_page = Configuration::get('ST_BLOG_PER_PAGE') ? Configuration::get('ST_BLOG_PER_PAGE') : 10;
            $this->blog_pagination((int)$this->nbr_blogs, $per_page);                    
    		$blogs = $search->getBlogs($id_array, $this->context->language->id, (int)$this->p, (int)$this->n);

            $url = $this->context->link->getModuleLink('stblogsearch','default',array('k'=>Tools::getValue('q')),false,$this->context->language->id,$this->context->shop->id);
            $vars = array();
    		$vars_pagination = array('p');
    
    		foreach ($_GET as $k => $value)
    		{
    			if ($k != 'controller' && $k != 'fc' && $k != 'module' && $k != 'search_query')
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
                
            $this->context->smarty->assign(array(
                'search_tag' => $original_query,
                'blogs' => $blogs,
                'nbr_blogs' => $this->nbr_blogs,
                'category_layouts' => Configuration::get('ST_BLOG_CATE_LAYOUTS'),
			    'path' => $this->getPath(),
                'imageSize' => StBlogImageClass::$imageTypeDef,
                'module_dir' => _PS_MODULE_DIR_,
                'display_viewcount' => Configuration::get('ST_BLOG_DISPLAY_VIEWCOUNT'),
                'requestPage'    => $requestPage,
                //
			    'HOOK_LEFT_COLUMN' => $this->display_column_left ? Hook::exec('displayStBlogLeftColumn') : '',
			    'HOOK_RIGHT_COLUMN' => $this->display_column_right ? Hook::exec('displayStBlogRightColumn') : '',
    			'hide_left_column' => !$this->display_column_left,
    			'hide_right_column' => !$this->display_column_right,
                'meta_title' => Configuration::get('ST_BLOG_META_TITLE', $this->context->language->id),
                'meta_description' => Configuration::get('ST_BLOG_META_KEYWORDS', $this->context->language->id),
                'meta_keywords' => Configuration::get('ST_BLOG_META_DESCRIPTION', $this->context->language->id),
            ));
        }
        
		$this->context->smarty->assign('errors', $this->errors);
		$this->setTemplate('default.tpl');
	}
    
    public function blog_pagination($nbProducts = 10, $per_page=10)
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
    
    public function getPath()
    {
		$pipe = Configuration::get('PS_NAVIGATION_PIPE');
		if (empty($pipe))
			$pipe = '>';
            
        $module_instanct = new StBlogSearch();
        
        $pipe = '<li class="navigation-pipe">'.$pipe.'</li>';
        $full_path = '<li typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="'.Tools::safeOutput($this->context->link->getModuleLink('stblog','default')).'" title="'.htmlentities($module_instanct->l('Blog','default'), ENT_NOQUOTES, 'UTF-8').'">'.htmlentities($module_instanct->l('Blog','default'), ENT_NOQUOTES, 'UTF-8').'</a></li>'.$pipe;
        $full_path .= '<li typeof="v:Breadcrumb">'.htmlentities($module_instanct->l('Search','default'), ENT_NOQUOTES, 'UTF-8').'</li>';
        
		return $full_path;
    }
}
