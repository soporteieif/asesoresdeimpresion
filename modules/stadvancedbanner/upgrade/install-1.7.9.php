<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_9($object)
{
	$result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_advanced_banner_group` `id_cms`');  
      
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_advanced_banner_group` ADD `id_cms` int(10) unsigned NOT NULL DEFAULT 0, ADD `id_cms_category` int(10) unsigned NOT NULL DEFAULT 0'))
		$result &= false;

    return $result;
}
