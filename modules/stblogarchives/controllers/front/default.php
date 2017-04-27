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

require_once _PS_MODULE_DIR_.'/stblogarchives/classes/StBlogArchivesClass.php';
require_once _PS_MODULE_DIR_.'/stblog/classes/StBlogLoader.php';
StBlogLoader::load(array('class','ImageClass'));
include_once(dirname(__FILE__).'/../../stblogarchives.php');
class StblogArchivesDefaultModuleFrontController extends ModuleFrontController
{
	public $nbr_blogs;
        
	public function initContent()
	{
		parent::initContent();
        
        if (!($m = Tools::getValue('m')))
            Tools::redirect('index.php?controller=404');
        
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
        
        if(!$this->errors)
		{
		    $archive = new StBlogArchivesClass();
    		$this->nbr_blogs = $archive->getBlogs($m, null, null, null, null, null, true); 
              
            $per_page = Configuration::get('ST_BLOG_PER_PAGE') ? Configuration::get('ST_BLOG_PER_PAGE') : 10;
            
            $this->blog_pagination((int)$this->nbr_blogs, $per_page);
                  
    		$blogs = $archive->getBlogs($m, $this->context->language->id, (int)$this->p, (int)$this->n);
            $year  = substr($m, 0, 4);
            $month = substr($m, 4, 2);
            if (strlen($year) == 4 && strlen($month) == 2)
                $date = $this->module->l(date('F', strtotime($year.'-'.$month))).', '.$year;
            elseif(strlen($year) == 4)
            {
                $date = $year;
                $month = null;
            }
            else
            {
                $date = $this->module->l('Unknow');
                $year = $month = null;
            }
                
            $url = $this->context->link->getModuleLink('stblogarchives','default',array('m'=>$m),false,$this->context->language->id,$this->context->shop->id);
            $vars = array();
    		$vars_pagination = array('p');
    
    		foreach ($_GET as $k => $value)
    		{
    			if ($k != 'controller' && $k != 'fc' && $k != 'module' && $k != 'm')
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
                
            $module_instanct = new StBlogArchives();
            $heading = $module_instanct->l('Archive for').' '.$date.' - ';
                
            $this->context->smarty->assign(array(
                'heading' => $date,
                'blogs' => $blogs,
                'nbr_blogs' => $this->nbr_blogs,
                'category_layouts' => Configuration::get('ST_BLOG_CATE_LAYOUTS'),
			    'path' => $this->getPath($year,$month),
                'imageSize' => StBlogImageClass::$imageTypeDef,
                'module_dir' => _PS_MODULE_DIR_,
                'display_viewcount' => Configuration::get('ST_BLOG_DISPLAY_VIEWCOUNT'),
                'requestPage'    => $requestPage,
                //
			    'HOOK_LEFT_COLUMN' => $this->display_column_left ? Hook::exec('displayStBlogLeftColumn') : '',
			    'HOOK_RIGHT_COLUMN' => $this->display_column_right ? Hook::exec('displayStBlogRightColumn') : '',
    			'hide_left_column' => !$this->display_column_left,
    			'hide_right_column' => !$this->display_column_right,
                'meta_title' => $heading . Configuration::get('ST_BLOG_META_TITLE', $this->context->language->id),
                'meta_description' => $heading . Configuration::get('ST_BLOG_META_KEYWORDS', $this->context->language->id),
                'meta_keywords' => $heading . Configuration::get('ST_BLOG_META_DESCRIPTION', $this->context->language->id),
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
    
    public function getPath($year = null, $month = null)
    {
		if (!$year && !$month)
            return false;

		$pipe = Configuration::get('PS_NAVIGATION_PIPE');
		if (empty($pipe))
			$pipe = '>';
            
        $module_instanct = new StBlogArchives();
        
        $pipe = '<li class="navigation-pipe">'.$pipe.'</li>';
        
        $full_path = '<li typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="'.Tools::safeOutput($this->context->link->getModuleLink('stblog','default')).'" title="'.htmlentities($module_instanct->l('Blog','default'), ENT_NOQUOTES, 'UTF-8').'">'.htmlentities($module_instanct->l('Blog','default'), ENT_NOQUOTES, 'UTF-8').'</a></li>'.$pipe;
        
        if ($year && $month)
		  $full_path .= '<li typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="'.Tools::safeOutput($this->context->link->getModuleLink('stblogarchives','default',array('m'=>$year))).'" title="'.htmlentities($year, ENT_NOQUOTES, 'UTF-8').'">'.htmlentities($year, ENT_NOQUOTES, 'UTF-8').'</a></li>';
        else
           $full_path .= '<li typeof="v:Breadcrumb">'.htmlentities($year, ENT_NOQUOTES, 'UTF-8').'</li>';   
        
        if ($month)
            $full_path .= $pipe.'<li typeof="v:Breadcrumb">'.htmlentities($module_instanct->l(date('F', strtotime($year.'-'.$month))), ENT_NOQUOTES, 'UTF-8').'</li>';

		return $full_path;
    }
}
