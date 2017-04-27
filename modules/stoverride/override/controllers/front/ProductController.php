<?php

class ProductController extends ProductControllerCore
{
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

