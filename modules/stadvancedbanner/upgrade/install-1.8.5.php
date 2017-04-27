<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_8_5($object)
{
	$result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_advanced_banner_group` `show_on_sub`');  
      
    if(!is_array($field) || !count($field))
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_advanced_banner_group` ADD `show_on_sub` tinyint(1) unsigned NOT NULL DEFAULT 1'))
		  $result &= false;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_advanced_banner_group` `style`');  
      
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_advanced_banner_group` ADD `style` tinyint(1) unsigned NOT NULL DEFAULT 0'))
		$result &= false;

    return $result;
}
