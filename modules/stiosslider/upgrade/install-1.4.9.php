<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_4_9($object)
{
    $result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_iosslider_group` `id_cms`');  
   
    if(!is_array($field) || !count($field))
        $result &= Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_iosslider_group` ADD `id_cms` int(10) unsigned NOT NULL DEFAULT 0 after `location`');
        
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_iosslider_group` `id_cms_category`');  
   
    if(!is_array($field) || !count($field))
        $result &= Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_iosslider_group` ADD `id_cms_category` int(10) unsigned NOT NULL DEFAULT 0 after `location`');
        
    return $result;
}
