<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_9_8($object)
{
	$result = true;
	$result &= $object->registerHook('actionObjectCategoryUpdateAfter');
	$result &= $object->registerHook('actionObjectCategoryDeleteAfter');
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_multi_link` `id_category`');  
       
    if(!is_array($field) || !count($field))
    {
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_multi_link` ADD `id_category` int(10) unsigned NOT NULL DEFAULT 0'))
    		$result &= false;
    }
    
    return $result;
}
