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

class BlockCart_mod extends Module
{
    public  $fields_list;
    public  $fields_value;
    public  $fields_form;
	private $_html = '';
    public  $hooks = array(
        array('id' =>'0-1', 'hook' => 'displayNav'),
        array('id' =>'2',   'hook' => 'displayTop'),
        array('id' =>'3',   'hook' => 'displayTopLeft'),
        array('id' =>'4',   'hook' => 'displayMainMenuWidget'),
    );
	public function __construct()
	{
		$this->name = 'blockcart_mod';
		$this->tab = 'front_office_features';
		$this->version = '1.7.2';
		$this->author = 'SUNNYTOO.COM';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Cart block mod');
		$this->description = $this->l('Adds a block containing the customer\'s shopping cart.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function assignContentVars($params)
	{
		global $errors;

		// Set currency
		if ((int)$params['cart']->id_currency && (int)$params['cart']->id_currency != $this->context->currency->id)
			$currency = new Currency((int)$params['cart']->id_currency);
		else
			$currency = $this->context->currency;

		$taxCalculationMethod = Group::getPriceDisplayMethod((int)Group::getCurrent()->id);

		$useTax = !($taxCalculationMethod == PS_TAX_EXC);

		$products = $params['cart']->getProducts(true);
		$nbTotalProducts = 0;
		foreach ($products as $product)
			$nbTotalProducts += (int)$product['cart_quantity'];
		$cart_rules = $params['cart']->getCartRules();

		if (empty($cart_rules))
			$base_shipping = $params['cart']->getOrderTotal($useTax, Cart::ONLY_SHIPPING);
		else
		{
			$base_shipping_with_tax    = $params['cart']->getOrderTotal(true, Cart::ONLY_SHIPPING);
			$base_shipping_without_tax = $params['cart']->getOrderTotal(false, Cart::ONLY_SHIPPING);
			if ($useTax)
				$base_shipping = $base_shipping_with_tax;
			else
				$base_shipping = $base_shipping_without_tax;
		}
		$shipping_cost = Tools::displayPrice($base_shipping, $currency);
		$shipping_cost_float = Tools::convertPrice($base_shipping, $currency);
		$wrappingCost = (float)($params['cart']->getOrderTotal($useTax, Cart::ONLY_WRAPPING));
		$totalToPay = $params['cart']->getOrderTotal($useTax);

		if ($useTax && Configuration::get('PS_TAX_DISPLAY') == 1)
		{
			$totalToPayWithoutTaxes = $params['cart']->getOrderTotal(false);
			$this->smarty->assign('tax_cost', Tools::displayPrice($totalToPay - $totalToPayWithoutTaxes, $currency));
		}

		// The cart content is altered for display
		foreach ($cart_rules as &$cart_rule)
		{
			if ($cart_rule['free_shipping'])
			{
				$shipping_cost = Tools::displayPrice(0, $currency);
				$shipping_cost_float = 0;
				$cart_rule['value_real'] -= Tools::convertPrice($base_shipping_with_tax, $currency);
				$cart_rule['value_tax_exc'] = Tools::convertPrice($base_shipping_without_tax, $currency);
			}
			if ($cart_rule['gift_product'])
			{
				foreach ($products as &$product)
					if ($product['id_product'] == $cart_rule['gift_product']
						&& $product['id_product_attribute'] == $cart_rule['gift_product_attribute'])
					{
						$product['is_gift'] = 1;
						$product['total_wt'] = Tools::ps_round($product['total_wt'] - $product['price_wt'],
							(int)$currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
						$product['total'] = Tools::ps_round($product['total'] - $product['price'],
							(int)$currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
						$cart_rule['value_real'] = Tools::ps_round($cart_rule['value_real'] - $product['price_wt'],
							(int)$currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
						$cart_rule['value_tax_exc'] = Tools::ps_round($cart_rule['value_tax_exc'] - $product['price'],
							(int)$currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
					}
			}
		}

		$total_free_shipping = 0;
		if ($free_shipping = Tools::convertPrice(floatval(Configuration::get('PS_SHIPPING_FREE_PRICE')), $currency))
		{
			$total_free_shipping =  floatval($free_shipping - ($params['cart']->getOrderTotal(true, Cart::ONLY_PRODUCTS) +
				$params['cart']->getOrderTotal(true, Cart::ONLY_DISCOUNTS)));
			$discounts = $params['cart']->getCartRules(CartRule::FILTER_ACTION_SHIPPING);
			if ($total_free_shipping < 0)
				$total_free_shipping = 0;
			if (is_array($discounts) && count($discounts))
				$total_free_shipping = 0;
		}

		$this->smarty->assign(array(
			'products' => $products,
			'customizedDatas' => Product::getAllCustomizedDatas((int)($params['cart']->id)),
			'CUSTOMIZE_FILE' => Product::CUSTOMIZE_FILE,
			'CUSTOMIZE_TEXTFIELD' => Product::CUSTOMIZE_TEXTFIELD,
			'discounts' => $cart_rules,
			'nb_total_products' => (int)($nbTotalProducts),
			'shipping_cost' => $shipping_cost,
			'shipping_cost_float' => $shipping_cost_float,
			'show_wrapping' => $wrappingCost > 0 ? true : false,
			'show_tax' => (int)(Configuration::get('PS_TAX_DISPLAY') == 1 && (int)Configuration::get('PS_TAX')),
			'wrapping_cost' => Tools::displayPrice($wrappingCost, $currency),
			'product_total' => Tools::displayPrice($params['cart']->getOrderTotal($useTax, Cart::BOTH_WITHOUT_SHIPPING), $currency),
			'total' => Tools::displayPrice($totalToPay, $currency),
			'order_process' => Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order',
			'ajax_allowed' => (int)(Configuration::get('PS_BLOCK_CART_AJAX')) == 1 ? true : false,
			'static_token' => Tools::getToken(false),
			'free_shipping' => $total_free_shipping,
			'block_cart_style' => Configuration::get('ST_BLOCK_CART_STYLE'),
            'block_cart_position' => (int)Configuration::get('ST_BLOCK_CART_POSITION'),
		));
		if (count($errors))
			$this->smarty->assign('errors', $errors);
		if (isset($this->context->cookie->ajax_blockcart_display))
			$this->smarty->assign('colapseExpandStatus', $this->context->cookie->ajax_blockcart_display);
	}

	public function getContent()
	{
	    if(Module::isInstalled('blockcart') && Module::isEnabled('blockcart'))
	    {
	    	$module_instance = Module::getInstanceByName('blockcart');
			if (Validate::isLoadedObject($module_instance))
				$module_instance->disable();
			Cache::clean('Module::isEnabledblockcart');
	    }

		if (Tools::isSubmit('submitBlockCart'))
		{
			$ajax = Tools::getValue('PS_BLOCK_CART_AJAX');
			if ($ajax != 0 && $ajax != 1)
				$this->_html .= $this->displayError($this->l('Ajax: Invalid choice.'));
			else
				Configuration::updateValue('PS_BLOCK_CART_AJAX', (int)($ajax));

			Configuration::updateValue('ST_BLOCK_CART_STYLE', (int)Tools::getValue('block_cart_style'));
			Configuration::updateValue('ST_BLOCK_CART_POSITION', (int)Tools::getValue('block_cart_position'));
            $this->prepareHooks((int)Configuration::get('ST_BLOCK_CART_POSITION'));
            $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
		}
		return $this->_html.$this->renderForm();
	}

	public function install()
	{
		if (
			parent::install() == false
			|| $this->registerHook('displayTop') == false
			|| $this->registerHook('displayHeader') == false
			|| $this->registerHook('actionCartListOverride') == false
			|| $this->registerHook('displayMobileBar') == false
			|| $this->registerHook('displaySideBar') == false
			|| Configuration::updateValue('ST_BLOCK_CART_STYLE', 0) == false
			|| Configuration::updateValue('PS_BLOCK_CART_AJAX', 1) == false
			|| Configuration::updateValue('ST_BLOCK_CART_POSITION', 0) == false)
			return false;
		return true;
	}

	public function hookRightColumn($params)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return;

		// @todo this variable seems not used
		$this->smarty->assign(array(
			'order_page' => (strpos($_SERVER['PHP_SELF'], 'order') !== false),
			'blockcart_top' => (isset($params['blockcart_top']) && $params['blockcart_top']) ? true : false,
		));
		$this->assignContentVars($params);
		return $this->display(__FILE__, 'blockcart.tpl');
	}

	public function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

	public function hookAjaxCall($params)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return;

		$this->assignContentVars($params);
		$res = Tools::jsonDecode($this->display(__FILE__, 'blockcart-json.tpl'), true);

		$res = Tools::jsonEncode($res);
		return $res;
	}

	public function hookActionCartListOverride($params)
	{
		if (!Configuration::get('PS_BLOCK_CART_AJAX'))
			return;

		$this->assignContentVars(array('cookie' => $this->context->cookie, 'cart' => $this->context->cart));
		$params['json'] = $this->display(__FILE__, 'blockcart-json.tpl');
	}

	public function hookHeader()
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return;

		if ((int)(Configuration::get('PS_BLOCK_CART_AJAX')))
		{
			$this->context->controller->addJS($this->_path.'views/js/ajax-cart.js');
			$this->context->controller->addJqueryPlugin(array('scrollTo'));
		}
	}
    
    public function hookDisplayTopLeft($params)
	{
		return $this->hookTop($params);
	}

	public function hookTop($params)
	{
		$params['blockcart_top'] = true;
		return $this->hookRightColumn($params);
	}

	public function hookDisplayMainMenuWidget($params)
	{
		$params['blockcart_top'] = true;
		$this->smarty->assign(array(
			'is_displaynav' => true,
		));
		return $this->hookRightColumn($params);
	}

	public function hookDisplayNav($params)
	{
		$params['blockcart_top'] = true;
		$this->smarty->assign(array(
			'is_displaynav' => true,
		));
		return $this->hookRightColumn($params);
	}
	public function hookDisplayMobileBar($params)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return;
		
        return $this->display(__FILE__, 'blockcart-mobile-tri.tpl');
	}

    public function hookDisplaySideBar($params)
    {
        if (Configuration::get('PS_CATALOG_MODE'))
			return;
		$this->smarty->assign(array(
			'order_page' => (strpos($_SERVER['PHP_SELF'], 'order') !== false),
		));
		$this->assignContentVars($params);

        return $this->display(__FILE__, 'blockcart-mobile.tpl');
    }

	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Ajax cart'),
						'name' => 'PS_BLOCK_CART_AJAX',
						'is_bool' => true,
						'desc' => $this->l('Activate Ajax mode for the cart (compatible with the default theme).'),
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
						'type' => 'radio',
						'label' => $this->l('Style:'),
						'name' => 'block_cart_style',
	                    'default_value' => 0,
						'values' => array(
							array(
								'id' => 'block_cart_style_default',
								'value' => 0,
								'label' => $this->l('Default, large cart icon')),
							array(
								'id' => 'block_cart_style_dialog',
								'value' => 1,
								'label' => $this->l('Small cart icon')),
						),
					),
	                array(
	                    'type' => 'radio',
	                    'label' => $this->l('Show on:'),
	                    'name' => 'block_cart_position',
	                    'default_value' => 0,
	                    'values' => array(
	                        array(
	                            'id' => 'block_cart_position_nav_right',
	                            'value' => 0,
	                            'label' => $this->l('Right side of the topbar')),
	                        array(
	                            'id' => 'block_cart_position_nav_left',
	                            'value' => 1,
	                            'label' => $this->l('Left side of the topbar')),
                            array(
	                            'id' => 'block_cart_position_top_right',
	                            'value' => 2,
	                            'label' => $this->l('Right side of the top')),
	                        array(
	                            'id' => 'block_cart_position_top_left',
	                            'value' => 3,
	                            'label' => $this->l('Left side of the top')),
	                        array(
	                            'id' => 'block_cart_position_menu',
	                            'value' => 4,
	                            'label' => $this->l('Menu')),
	                    ),
	                    'validation' => 'isUnsignedInt',
	                ),
				),
				'submit' => array(
					'title' => $this->l('Save')
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitBlockCart';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab
		.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
	    $posi = 0;
	    foreach($this->hooks AS $hook)
        {
            if($id_hook = Hook::getIdByName($hook['hook']))
                if(Hook::getModulesFromHook($id_hook, $this->id))
                {
                    if ($hook['hook'] != 'displayNav')
                        $posi = $hook['id'];
                    elseif(Configuration::get('ST_BLOCK_CART_POSITION') > 1)
                        Configuration::updateValue('ST_BLOCK_CART_POSITION', 0);
                    elseif (Configuration::get('ST_BLOCK_CART_POSITION'))
                        $posi = 1;
                }
        }
		return array(
			'PS_BLOCK_CART_AJAX' => (bool)Tools::getValue('PS_BLOCK_CART_AJAX', Configuration::get('PS_BLOCK_CART_AJAX')),
			'block_cart_style' => (int)Tools::getValue('ST_BLOCK_CART_STYLE', Configuration::get('ST_BLOCK_CART_STYLE')),
            'block_cart_position' => $posi,
		);
	}
    
    public function prepareHooks($val = 0)
    {
        foreach($this->hooks AS $hook)
        {
            $id_hook = Hook::getIdByName($hook['hook']);
            $val_array = explode('-', $hook['id']);
            if ($id_hook && count($val_array) && in_array($val, $val_array))
            {
                if ($id_hook && Hook::getModulesFromHook($id_hook, $this->id))
                    continue;
                if (!$this->isHookableOn($hook['hook']))
                    $this->validation_errors[] = $this->l('This module cannot be transplanted to '.$hook['hook'].'.');
                else
                    $this->registerHook($hook['hook'], Shop::getContextListShopID());
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
        Cache::clean('hook_module_list');
    }
}
