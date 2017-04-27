<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_7($object)
{
    $result = true;
    
    $result &= $object->registerHook('displayProductSecondaryColumn') 
    && $object->registerHook('displayHomeSecondaryRight');
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_camera_slideshow` `isbanner`');  
       
    if(!is_array($field) || !count($field))
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_camera_slideshow` ADD `isbanner` tinyint(10) unsigned DEFAULT 0')
        || !Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_camera_slideshow_lang` ADD `title` VARCHAR(255) DEFAULT NULL'))
    		$result &= false;
            
    return $result;
}