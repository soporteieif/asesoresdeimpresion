<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_6($object)
{
	$result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_advanced_banner` `bg_color`');  
      
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_advanced_banner` 
            ADD `bg_color` varchar(7) DEFAULT NULL,
            ADD `text_width` tinyint(2) unsigned NOT NULL DEFAULT 0'))
    		$result &= false;
            
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_advanced_banner_group` `padding`');  
    
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_advanced_banner_group` 
        ADD `padding` varchar(10) DEFAULT NULL,
        ADD `top_spacing` varchar(10) DEFAULT NULL,
        ADD `bottom_spacing` varchar(10) DEFAULT NULL'
        ))
		$result &= false;

    return $result;
}
