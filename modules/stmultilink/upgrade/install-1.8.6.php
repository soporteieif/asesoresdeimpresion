<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_8_6($object)
{
    $result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_multi_link_group` `new_window`');  
       
    if(!is_array($field) || !count($field))
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_multi_link_group` ADD `new_window` tinyint(1) unsigned NOT NULL DEFAULT 0'))
    		$result &= false;
        
    return $result;
}
