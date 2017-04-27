<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_8_9($object)
{
	$result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_multi_link_group` `nofollow`');  
   
    if(!is_array($field) || !count($field))
        $result &= Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_multi_link_group` ADD `nofollow` tinyint(1) unsigned NOT NULL DEFAULT 1');
        
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_multi_link` `nofollow`');  
   
    if(!is_array($field) || !count($field))
        $result &= Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_multi_link` ADD `nofollow` tinyint(1) unsigned NOT NULL DEFAULT 1');
        
    return $result;
}
