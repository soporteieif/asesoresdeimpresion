<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_2_9($object)
{
    $result = true;
    
    $result &= $object->registerHook('displayManufacturerHeader');
    $result &= $object->registerHook('actionObjectManufacturerDeleteAfter');
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_owl_carousel_group` `id_manufacturer`');  
      
    if(!is_array($field) || !count($field))
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_owl_carousel_group` ADD `id_manufacturer` int(10) unsigned NOT NULL DEFAULT 0'))
            $result &= false;
        
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_owl_carousel_group` `show_on_sub`');  
      
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_owl_carousel_group` ADD `show_on_sub` tinyint(1) unsigned NOT NULL DEFAULT 1'))
            $result &= false;
    
    return $result;
}
