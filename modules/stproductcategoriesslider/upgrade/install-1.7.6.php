<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_6($object)
{
    $result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_product_categories_slider` `title_no_bg`');  
   
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_product_categories_slider` 
        ADD `title_no_bg` tinyint(1) unsigned NOT NULL DEFAULT 0'))
        $result &= false;
        
    $result &= Configuration::updateValue('ST_PRO_CATE_TAB_TITLE_NO_BG', 0);
    
    return $result;
}