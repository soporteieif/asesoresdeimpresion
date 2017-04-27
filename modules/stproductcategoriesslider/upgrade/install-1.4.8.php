<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_4_8($object)
{
	$result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_product_categories_slider` `display_on`');  
   
    if(!is_array($field) || !count($field))
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_product_categories_slider` ADD `display_on` tinyint(2) unsigned NOT NULL DEFAULT 1'))
    		$result &= false;
        
    $result &= $object->registerHook('displayLeftColumn')
    && $object->registerHook('displayRightColumn')
    && $object->registerHook('displayFooterTop');
	return $result;
}
