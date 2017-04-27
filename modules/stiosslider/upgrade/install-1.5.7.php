<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_5_7($object)
{
    $result = true;
 
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_iosslider_group` `top_spacing`');  
   
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_iosslider_group` 
        ADD `top_spacing` varchar(10) DEFAULT NULL,
        ADD `bottom_spacing` varchar(10) DEFAULT NULL'))
        $result &= false;;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_iosslider` `btn_color`');  
   
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_iosslider` 
        ADD `btn_color` varchar(7) DEFAULT NULL,
        ADD `btn_bg` varchar(7) DEFAULT NULL,
        ADD `btn_hover_color` varchar(7) DEFAULT NULL,
        ADD `btn_hover_bg` varchar(7) DEFAULT NULL'))
        $result &= false;;

    $result &= Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_iosslider_font` (
            `id_st_iosslider` int(10) unsigned NOT NULL,
            `font_name` varchar(255) NOT NULL
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

    $cssFile = _PS_MODULE_DIR_ . $object->name. '/views/css/custom.css';
    @unlink($cssFile);
    
    return $result;
}
