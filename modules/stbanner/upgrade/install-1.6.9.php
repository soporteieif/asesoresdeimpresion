<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_6_9($object)
{
    $result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_banner_lang` `width`');  
      
    if(!is_array($field) || !count($field))
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_banner_lang` ADD `width` int(10) unsigned NOT NULL DEFAULT 0'))
    		$result &= false;

    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_banner_lang` `height`');  
      
    if(!is_array($field) || !count($field))
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_banner_lang` ADD `height` int(10) unsigned NOT NULL DEFAULT 0'))
    		$result &= false;
            
    $result &= $object->registerHook('displayBanner');

    return $result;
}