<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_8($object)
{
    $result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_easy_content` `display_on`');  
   
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_easy_content` 
        ADD `display_on` int(10) unsigned NOT NULL DEFAULT 0'))
        $result &= false;
    
    if (!Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'st_easy_content` set display_on = 1'))
        $result &= false;

	return $result;
}
