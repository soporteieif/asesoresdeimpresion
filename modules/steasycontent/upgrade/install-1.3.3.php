<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_3_3($object)
{
    $result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_easy_content` `id_category`');
   
    if(!is_array($field) || !count($field))
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_easy_content` ADD`id_category` int(10) unsigned NOT NULL DEFAULT 0'))
    		$result &= false;
            
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_easy_content` `id_manufacturer`');
   
    if(!is_array($field) || !count($field))
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_easy_content` ADD`id_manufacturer` int(10) unsigned NOT NULL DEFAULT 0'))
    		$result &= false;
    
	return $result;
}
