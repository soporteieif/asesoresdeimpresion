<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_1_7($object)
{
	$result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_easy_tabs` `id_manufacturer`');  
      
    if(!is_array($field) || !count($field))
        return Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_easy_tabs` ADD `id_manufacturer` int(10) unsigned NOT NULL DEFAULT 0');
    
    return $result;
}
