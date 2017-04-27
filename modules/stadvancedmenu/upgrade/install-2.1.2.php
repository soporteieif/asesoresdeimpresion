<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_1_2($object)
{
    $result = true;

    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_advanced_menu` `granditem`');  
   
    if(!is_array($field) || !count($field))
        $result &= Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_advanced_menu` ADD `granditem` tinyint(1) DEFAULT 0');
        
	return $result;
}