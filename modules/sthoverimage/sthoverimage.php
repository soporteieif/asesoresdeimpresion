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

class StHoverImage extends Module
{
    private $_html = '';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();
    public static $cache_hover = array();
	protected static $override = array(
		'controllers/admin/templates/products/images.tpl'
	);
    private $_effect = array();
	function __construct()
	{
		$this->name           = 'sthoverimage';
		$this->tab            = 'front_office_features';
		$this->version        = '1.0';
		$this->author         = 'SUNNYTOO.COM';
		$this->need_instance  = 0;
        $this->bootstrap      = true;

		parent::__construct();

		$this->displayName = $this->l('Hover image');
		$this->description = $this->l('Products thumb image change on mouse hover');
        
        $this->_effect = array(
            array('id' => 0, 'name' => $this->l('Fade')),
            array('id' => 1, 'name' => $this->l('From Top')),
            array('id' => 2, 'name' => $this->l('From Bottom')),
            array('id' => 3, 'name' => $this->l('From Left')),
            array('id' => 4, 'name' => $this->l('From Right')),
        );
	}

	function install()
	{
	    $result = true;
	    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'image` `hover`');
        if(!is_array($field) || !count($field))
            if (!Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'image` ADD `hover` BOOLEAN NOT NULL DEFAULT 0;'))
        		$result &= false;
                
        $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'image_shop` `hover`');
        if(!is_array($field) || !count($field))
            if (!Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'image_shop` ADD `hover` BOOLEAN NOT NULL DEFAULT 0;'))
        		$result &= false;
        
        if ($result)
        {
            $result = parent::install() &&
                $this->registerHook('BackOfficeHeader') &&
                $this->registerHook('displayAnywhere') &&
                $this->registerHook('displayHeader') &&
                Configuration::updateValue('ST_HOVER_IMAGE_WAY', 0);    
        }
        
        if ($result)
            foreach(self::$override as $file)
            {
				$explode = explode("/", $file);
				$file_name = $explode[count($explode)-1];
				unset($explode[count($explode)-1]);
				$folder = implode("/", $explode);
				@mkdir (_PS_OVERRIDE_DIR_.$folder, 0777, true);
				@copy ( _PS_MODULE_DIR_.$this->name.'/override/'.$folder."/".$file_name , _PS_OVERRIDE_DIR_.$folder."/".$file_name );
				$old = @umask(0);
				@chmod (_PS_OVERRIDE_DIR_.$folder."/".$file_name, 0777);
				@umask($old);
			}

		return $result;
	}
    
    public function uninstall()
	{
	    $result = true;
	    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'image` `hover`');
        if(is_array($field) && count($field))
            if (!Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'image` DROP `hover`;'))
        		$result &= false;
                
        $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'image_shop` `hover`');
        if(is_array($field) && count($field))
            if (!Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'image_shop` DROP `hover`;'))
        		$result &= false;
        
        foreach(self::$override as $file)
        {
			if(is_file(_PS_OVERRIDE_DIR_.$file))
				@unlink (_PS_OVERRIDE_DIR_.$file);
		}
		return $result && parent::uninstall();
	}
    
    public function updateHoverImage($id_image)
	{
	    $img = new Image((int)$id_image);
        $hovered = $img->hover;
	    self::deleteHover((int)$img->id_product);
        if ($hovered)
            return '-1';
		$img->hover = 1;
        return $img->update();
	}

	public function hookBackOfficeHeader()
    {
		if(Tools::getValue('controller') != 'AdminProducts' && !Tools::getValue('id_product'))
			return false;
        
		$this->context->smarty->assign(array(
			'ajax_link_hover' => 'index.php?controller=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'show_hover' => Module::isInstalled('sthoverimage') && Module::isEnabled('sthoverimage')
		));
	}
        
    public function getContent()
	{
	    $act = Tools::getValue('action');
        if (Tools::getValue('ajax'))
        {
           if ($act == 'building_hover_all')
            {
                echo self::buildingHover(Tools::getValue('cursor'));
                die;            
            }
            
            if ($act == 'clear_hover_all')
            { 
                echo self::ClearHover();
                die;            
            }
            
            if ($act == 'asso_update_hover')
            { 
                $id_product = Tools::getValue('id_product');
                $id_shop = Tools::getValue('id_shop');
                echo self::processImageShopAsso($id_product, $id_shop);
                die;            
            }
            
            if($act == 'update_hover')
            {
                $result['r'] = false;
                if($result['r'] = $this->updateHoverImage((int)Tools::getValue('id_image')))
                {
                    $result['m'] = $this->l('Your hover selection has been saved.');
                }
                echo json_encode($result);
                die;
            } 
        }
        
        $this->initFieldsForm();
		if (isset($_POST['savesthoverimage']))
		{
            foreach($this->fields_form as $form)
                foreach($form['form']['input'] as $field)
                    if(isset($field['validation']))
                    {
                        $errors = array();     
                        $value = Tools::getValue($field['name']);
                        if (isset($field['required']) && $field['required'] && $value==false && (string)$value != '0')
        						$errors[] = sprintf(Tools::displayError('Field "%s" is required.'), $field['label']);
                        elseif($value)
                        {
                            $field_validation = $field['validation'];
        					if (!Validate::$field_validation($value))
        						$errors[] = sprintf(Tools::displayError('Field "%s" is invalid.'), $field['label']);
                        }
        				// Set default value
        				if ($value === false && isset($field['default_value']))
        					$value = $field['default_value'];
                        
                        if($field['name']=='limit' && $value>20)
                             $value=20;
                        
                        if(count($errors))
                        {
                            $this->validation_errors = array_merge($this->validation_errors, $errors);
                        }
                        elseif($value==false)
                        {
                            switch($field['validation'])
                            {
                                case 'isUnsignedId':
                                case 'isUnsignedInt':
                                case 'isInt':
                                case 'isBool':
                                    $value = 0;
                                break;
                                default:
                                    $value = '';
                                break;
                            }
                            Configuration::updateValue('ST_'.strtoupper($field['name']), $value);
                        }
                        else
                            Configuration::updateValue('ST_'.strtoupper($field['name']), $value);
                    }
            $this->initFieldsForm();
            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else
            {
                Tools::clearSmartyCache();
    			Media::clearCache();
                $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
            }   
        }
        
	    $this->context->controller->addJS($this->_path. 'views/js/admin.js');
        $module_url = Tools::getProtocol(Tools::usingSecureMode()).$_SERVER['HTTP_HOST'].$this->getPathUri();
        $this->context->smarty->assign(array(
            'build_hover_url' => $module_url.'sthoverimage_ajax.php'.'?token='.substr(Tools::encrypt('sthoverimage/index'), 0, 10),
            'clear_hover_url' => $module_url.'sthoverimage_ajax.php'.'?token='.substr(Tools::encrypt('sthoverimage/index'), 0, 10),
            'show_error' => !self::checkOverrideFiles(),
            'ps_base_uri' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
        ));

		$this->_html .= $this->display(__FILE__, 'views/templates/admin/view.tpl');
        $this->_html .= $this->initForm()->generateForm($this->fields_form);
        return $this->_html;
	}
    
    protected function initFieldsForm()
    {
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Setting'),
                'icon' => 'icon-cogs'
			),
            'input' => array(
                array(
					'type' => 'select',
					'label' => $this->l('Hover effect:'),
					'name' => 'hover_image_way',
                    'validation' => 'isUnsignedId',
                    'required' => true,
                    'options' => array(
        				'query' => $this->_effect,
        				'id' => 'id',
        				'name' => 'name',
        			)
				),
			),
			'submit' => array(
				'title' => $this->l('   Save   ')
			)
		);
    }
    private function getConfigFieldsValues()
    {
        $fields_values = array(
            'hover_image_way' => Configuration::get('ST_HOVER_IMAGE_WAY'),
        );
        return $fields_values;
    }
    protected function initForm()
	{
	    $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
        $helper->module = $this;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'savesthoverimage';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper;
	}
    
    public function hookDisplayAnywhere($params)
    {
        if(!isset($params['caller']) || $params['caller']!=$this->name)
            return false;
        if(isset($params['function']) && method_exists($this,$params['function']))
        {
            if($params['function']=='getHoverImage')
                return call_user_func_array(array($this,$params['function']),array($params['id_product'],$params['product_link_rewrite'],$params['home_default_width'],$params['home_default_height'],$params['product_name']));
            else
                return false;
        }
        return false;
    }

    public function hookDisplayHeader($params)
    {
		if($hover_image_way = Configuration::get('ST_HOVER_IMAGE_WAY'))
			$this->context->controller->addCSS(($this->_path).'views/css/style-'.$hover_image_way.'.css');
		else
			$this->context->controller->addCSS(($this->_path).'views/css/style.css');

        $this->context->controller->addJS($this->_path.'views/js/sthoverimage.js');
    }

    public function getHoverImage($id_product,$product_link_rewrite,$home_default_width,$home_default_height,$product_name)
    {
    	if(!Validate::isInt($id_product))
    		return false;

    	$hover = $this->getHoverImageByProductId($id_product);
    	if(!is_array($hover) || !count($hover))
    		return false;
        $this->smarty->assign('product_id_image', $hover['id_image'], true);
        $this->smarty->assign('product_legend', $hover['legend'][Context::getContext()->language->id], true);

        $this->smarty->assign('product_link_rewrite', $product_link_rewrite, true);
		$this->smarty->assign('home_default_width', $home_default_width, true);
		$this->smarty->assign('home_default_height', $home_default_height, true);
        $this->smarty->assign('product_name', $product_name, true);
		return $this->display(__FILE__, 'product_hover_image.tpl');
    }

    public static function clearHover()
    {
        Db::getInstance()->update('image', array('hover'=>false));
        Db::getInstance()->update('image_shop', array('hover'=>false));
        echo '{"result":"ok"}';
    }
    
    public static function buildingHover($cursor = null)
	{
		$nb_products = (int)Db::getInstance()->getValue('
			SELECT count(DISTINCT p.`id_product`)
			FROM '._DB_PREFIX_.'product p
			INNER JOIN `'._DB_PREFIX_.'product_shop` ps
				ON (ps.`id_product` = p.`id_product` AND ps.`active` = 1 AND ps.`visibility` IN ("both", "catalog"))');
		
		$max_executiontime = @ini_get('max_execution_time');
		if ($max_executiontime > 5 || $max_executiontime <= 0)
			$max_executiontime = 5;
		
		$start_time = microtime(true);
		
		if (function_exists('memory_get_peak_usage'))
			do
			{
				$cursor = (int)self::processBuilding((int)$cursor);
				$time_elapsed = microtime(true) - $start_time;
			}
			while ($cursor < $nb_products && Tools::getMemoryLimit() > memory_get_peak_usage() && $time_elapsed < $max_executiontime);
		else
			do
			{
				$cursor = (int)self::processBuilding((int)$cursor);
				$time_elapsed = microtime(true) - $start_time;
			}
			while ($cursor < $nb_products && $time_elapsed < $max_executiontime);

		if ($nb_products > 0 && $cursor < $nb_products)
			return '{"cursor": '.$cursor.', "count": '.($nb_products - $cursor).'}';
		else
        {
            Tools::clearSmartyCache();
    		Media::clearCache();
            return '{"result": "ok"}';
        }

	}
    
    private static function processBuilding($cursor)
	{
		static $length = 100; // Nb of products to index
		
		if (is_null($cursor))
			$cursor = 0;
		
		$query = '
			SELECT p.`id_product`
			FROM `'._DB_PREFIX_.'product` p
			INNER JOIN `'._DB_PREFIX_.'product_shop` ps
				ON (ps.`id_product` = p.`id_product` AND ps.`active` = 1 AND ps.`visibility` IN ("both", "catalog"))
			GROUP BY p.`id_product`
			ORDER BY p.`id_product` LIMIT '.(int)$cursor.','.(int)$length;
		foreach (Db::getInstance()->executeS($query) as $product)
			self::processHover((int)$product['id_product']);

		return (int)($cursor + $length);
	}
    
    private static function processHover($id_product = 0)
    {
        if (!$id_product)
            return false;
        $count_images = Db::getInstance()->getValue('
					SELECT COUNT(0) from `'._DB_PREFIX_.'image` i
                    INNER JOIN `'._DB_PREFIX_.'image_shop` s
                    ON i.id_image = s.id_image
                    WHERE i.id_product = '.(int)$id_product.'
                    AND s.id_shop = '.(int)Shop::getContextShopID().'
                    AND s.hover > 0
				');
        if ($count_images)
            return false;
        $query = '
            SELECT s.* from `'._DB_PREFIX_.'image` i
            INNER JOIN `'._DB_PREFIX_.'image_shop` s
            ON i.id_image = s.id_image
            WHERE i.id_product = '.(int)$id_product.'
            AND s.id_shop = '.(int)Shop::getContextShopID().'
            ORDER BY s.cover DESC, position ASC
            ';
        $id_shop = Context::getContext()->shop->id;
        foreach(Db::getInstance()->executeS($query) AS $image)
        {
            if ($image['cover'])
                continue;
            $img = new Image($image['id_image']);
            $img->hover = 1;
            $img->update();
            return true;
        }
        return false;
    }
    
    public static function checkOverrideFiles()
    {
        $exists = true;
        foreach(self::$override AS $file)
            if (!file_exists(_PS_OVERRIDE_DIR_.$file) || !filesize(_PS_OVERRIDE_DIR_.$file))
                $exists = false;
        return $exists;
    }
    
    public static function getHoverImageByProductId($id_product)
    {
        if (isset(self::$cache_hover[$id_product]) && self::$cache_hover[$id_product])
            return self::$cache_hover[$id_product];

        self::$cache_hover[$id_product] = false;
        
        foreach(Image::getImages(Context::getContext()->language->id, (int)$id_product) AS $image)
        {
            if ($image['cover'] > 0)
                continue;
            if ($image['hover'] > 0)
            {
                $img = new Image($image['id_image']);
                self::$cache_hover[$id_product] = get_object_vars($img);
                break;
            }
        }
        return self::$cache_hover[$id_product];
    }
    
    public static function deleteHover($id_product)
	{
		return (Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'image`
			SET `hover` = 0
			WHERE `id_product` = '.(int)$id_product
		) &&
		Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'image` i, `'._DB_PREFIX_.'image_shop` image_shop
			SET image_shop.`hover` = 0
			WHERE image_shop.id_shop IN ('.implode(',', array_map('intval', Shop::getContextListShopID())).') AND image_shop.id_image = i.id_image AND i.`id_product` = '.(int)$id_product
		));
	}
    
    public static function processImageShopAsso($id_product, $id_shop)
    {
        if (!$id_product || !$id_shop)
            return false;
        // Clean hovers in image table
		$count_hover_image = Db::getInstance()->getValue('
			SELECT COUNT(*) FROM '._DB_PREFIX_.'image i 
			INNER JOIN '._DB_PREFIX_.'image_shop ish ON (i.id_image = ish.id_image AND ish.id_shop = '.(int)$id_shop.') 
			WHERE i.hover = 1 AND `id_product` = '.(int)$id_product);
		
		$id_image = Db::getInstance()->getValue('
			SELECT i.`id_image` FROM '._DB_PREFIX_.'image i 
			INNER JOIN '._DB_PREFIX_.'image_shop ish ON (i.id_image = ish.id_image AND ish.id_shop = '.(int)$id_shop.') 
			WHERE ish.`cover` = 0 AND `id_product` = '.(int)$id_product);
		
		if ($count_hover_image < 1)
			Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'image i SET i.hover = 1 WHERE i.id_image = '.(int)$id_image.' AND i.`id_product` = '.(int)$id_product.' LIMIT 1');
		
		if ($count_hover_image > 1)
			Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'image i SET i.hover = 0 WHERE i.id_image <> '.(int)$id_image.' AND i.`id_product` = '.(int)$id_product);
			
		// Clean hovers in image_shop table
		$count_hover_image_shop = Db::getInstance()->getValue('
			SELECT COUNT(*) 
			FROM '._DB_PREFIX_.'image_shop ish 
			INNER JOIN '._DB_PREFIX_.'image i ON (i.id_image = ish.id_image AND i.`id_product` = '.(int)$id_product.')
			WHERE ish.id_shop = '.(int)$id_shop.' AND ish.hover = 1');
		
		if ($count_hover_image_shop < 1)
			Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'image_shop ish SET ish.hover = 1 WHERE ish.id_image = '.(int)$id_image.' AND ish.id_shop =  '.(int)$id_shop.' LIMIT 1');
		if ($count_hover_image_shop > 1)
			Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'image_shop ish SET ish.hover = 0 WHERE ish.id_image <> '.(int)$id_image.' AND ish.hover = 1 AND ish.id_shop = '.(int)$id_shop.' LIMIT '.intval($count_hover_image_shop - 1));
    }
}