<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_0_9($object)
{
    $result = true;
 
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_owl_carousel` `text_width`');  
      
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_owl_carousel` ADD `text_width` tinyint(2) unsigned NOT NULL DEFAULT 0'))
            $result &= false;
 
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_owl_carousel_group` `top_spacing`');  
   
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_owl_carousel_group` 
        ADD `top_spacing` varchar(10) DEFAULT NULL,
        ADD `bottom_spacing` varchar(10) DEFAULT NULL,
        ADD `slider_spacing` varchar(10) DEFAULT NULL'))
        $result &= false;;
    
    return $result;
}
