<?php
class ProductController extends ProductControllerCore
{
    /*
    * module: stoverride
    * date: 2017-04-17 10:26:35
    * version: 1.2.0
    */
    public function initContent()
	{
		parent::initContent();
        if(!$this->display_column_left && !$this->display_column_right && Configuration::get('STSN_PRODUCT_SECONDARY'))
            $this->context->smarty->assign(array(   
    			'HOOK_PRODUCT_SECONDARY_COLUMN' => Hook::exec('displayProductSecondaryColumn'),     
    		));
        $this->context->smarty->assign(array(
            'show_brand_logo' => Configuration::get('STSN_SHOW_BRAND_LOGO'),
        ));
	}
}
